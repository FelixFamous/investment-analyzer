<?php
/**
 * Small, dependency-free helper functions used across the app.
 */

/** HTML-escape a value for safe output. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Send a Location header and exit. */
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/** JSON response helper for API endpoints. */
function json_response($data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

/** Read a POST field as a trimmed string. */
function post(string $key, $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

/** Read a GET field as a trimmed string. */
function get(string $key, $default = ''): string
{
    return trim((string) ($_GET[$key] ?? $default));
}

/** Format a number as USD with 2 decimals. */
function usd(float $amount): string
{
    return '$' . number_format($amount, 2, '.', ',');
}

/** Format a price with dynamic decimals (crypto-friendly). */
function price_fmt(float $n): string
{
    if ($n >= 1000) return '$' . number_format($n, 2);
    if ($n >= 1)    return '$' . number_format($n, 2);
    if ($n >= 0.01) return '$' . number_format($n, 4);
    return '$' . number_format($n, 7);
}

/** Set a one-time flash message for the next request. */
function flash_set(string $type, string $message): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** Pop the flash message (returns null if none). */
function flash_get(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

/**
 * Shared CSRF verification for API endpoints.
 * Fails closed if the session has no token (empty-string bypass protection).
 */
function csrf_verify_or_die(): void
{
    $expected = $_SESSION['csrf'] ?? '';
    $sent     = $_POST['csrf'] ?? '';
    if ($expected === '' || !hash_equals($expected, $sent)) {
        json_response(['error' => 'CSRF token mismatch'], 419);
    }
}

/* ==================== Timezone helpers ==================== */

function user_timezone(): string
{
    static $tz = null;
    if ($tz !== null) return $tz;
    $u = function_exists('current_user') ? current_user() : null;
    $tz = $u['timezone'] ?? 'UTC';
    try { new DateTimeZone($tz); } catch (Throwable $e) { $tz = 'UTC'; }
    return $tz;
}

function to_user_tz(string $utcTimestamp): DateTimeImmutable
{
    $dt = new DateTimeImmutable($utcTimestamp, new DateTimeZone('UTC'));
    return $dt->setTimezone(new DateTimeZone(user_timezone()));
}

function fmt_time(string $utcTimestamp, string $format = 'M j, H:i'): string
{
    try { return to_user_tz($utcTimestamp)->format($format); }
    catch (Throwable $e) { return '—'; }
}

function time_ago(string $utcTimestamp): string
{
    try { $then = to_user_tz($utcTimestamp)->getTimestamp(); }
    catch (Throwable $e) { return '—'; }
    $now = (new DateTimeImmutable('now', new DateTimeZone(user_timezone())))->getTimestamp();
    $diff = max(0, $now - $then);
    if ($diff < 60) return $diff . 's ago';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 2592000) return floor($diff / 86400) . 'd ago';
    return to_user_tz($utcTimestamp)->format('M j, Y');
}

function tz_short_label(): string
{
    try {
        $dt = new DateTimeImmutable('now', new DateTimeZone(user_timezone()));
        return $dt->format('T');
    } catch (Throwable $e) { return 'UTC'; }
}

function tz_display_label(): string
{
    $tz = user_timezone();
    $map = [
        'Africa/Lagos'        => 'Nigeria (WAT · Lagos)',
        'Africa/Accra'        => 'Ghana (GMT · Accra)',
        'Africa/Nairobi'      => 'Kenya (EAT · Nairobi)',
        'Africa/Johannesburg' => 'South Africa (SAST · Johannesburg)',
        'Africa/Cairo'        => 'Egypt (EET · Cairo)',
        'Africa/Casablanca'   => 'Morocco (WET · Casablanca)',
        'Europe/London'       => 'United Kingdom (GMT/BST · London)',
        'Europe/Paris'        => 'France / Germany (CET · Paris)',
        'Europe/Moscow'       => 'Russia (MSK · Moscow)',
        'America/New_York'    => 'USA East (EST/EDT · New York)',
        'America/Chicago'     => 'USA Central (CST · Chicago)',
        'America/Denver'      => 'USA Mountain (MST · Denver)',
        'America/Los_Angeles' => 'USA Pacific (PST · Los Angeles)',
        'America/Sao_Paulo'   => 'Brazil (BRT · São Paulo)',
        'Asia/Dubai'          => 'UAE (GST · Dubai)',
        'Asia/Karachi'        => 'Pakistan (PKT · Karachi)',
        'Asia/Kolkata'        => 'India (IST · Kolkata)',
        'Asia/Dhaka'          => 'Bangladesh (BST · Dhaka)',
        'Asia/Bangkok'        => 'Thailand / Vietnam (ICT · Bangkok)',
        'Asia/Shanghai'       => 'China (CST · Shanghai)',
        'Asia/Singapore'      => 'Singapore (SGT)',
        'Asia/Tokyo'          => 'Japan (JST · Tokyo)',
        'Asia/Seoul'          => 'South Korea (KST · Seoul)',
        'Australia/Sydney'    => 'Australia East (AEDT · Sydney)',
        'Pacific/Auckland'    => 'New Zealand (NZDT · Auckland)',
        'UTC'                 => 'UTC',
    ];
    return $map[$tz] ?? $tz;
}

/* ==================== Portfolio snapshots ==================== */

function snapshot_portfolio(?int $userId = null): void
{
    if ($userId === null) {
        $u = function_exists('current_user') ? current_user() : null;
        if (!$u) return;
        $userId = (int)$u['id'];
    }

    try {
        $stmt = db()->prepare('SELECT cash_balance FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $cashRow = $stmt->fetch();
        if (!$cashRow) return;
        $cash = (float)$cashRow['cash_balance'];

        $stmt = db()->prepare('SELECT symbol, quantity, avg_price FROM holdings WHERE user_id = ?');
        $stmt->execute([$userId]);
        $holdingsValue = 0.0;
        foreach ($stmt->fetchAll() as $h) {
            $price = function_exists('get_price') ? get_price($h['symbol']) : null;
            if ($price === null) $price = (float)$h['avg_price'];
            $holdingsValue += (float)$h['quantity'] * (float)$price;
        }
        $equity = $cash + $holdingsValue;

        $today = gmdate('Y-m-d');

        db()->prepare('
            INSERT INTO portfolio_snapshots (user_id, snapshot_date, equity, cash, holdings_value)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                equity = VALUES(equity),
                cash = VALUES(cash),
                holdings_value = VALUES(holdings_value),
                updated_at = CURRENT_TIMESTAMP
        ')->execute([$userId, $today, $equity, $cash, $holdingsValue]);

    } catch (Throwable $e) {
        error_log('snapshot_portfolio failed: ' . $e->getMessage());
    }
}