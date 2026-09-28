<?php
/**
 * Session bootstrap + authentication helpers.
 * Also fires the order, DCA engines on every authenticated page load.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

/**
 * Fetch the current user. No static caching — a trade in the same request
 * must be reflected on the next read (balances, KYC, etc.).
 */
function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;

    $now = time();

    $stmt = db()->prepare('
        SELECT id, username, email, cash_balance, is_admin, created_at, last_seen, timezone,
               kyc_status, kyc_full_name, kyc_dob, kyc_country, kyc_id_type, kyc_id_number,
               kyc_document, kyc_selfie, kyc_submitted_at, kyc_reviewed_at, kyc_rejection_reason,
               kyc_doc_type, kyc_doc_front, kyc_doc_back, kyc_tax_id, kyc_tax_country,
               kyc_poa_type, kyc_poa_file, kyc_bank_holder, kyc_bank_name, kyc_bank_account,
               kyc_bank_country, kyc_mobile, kyc_email_verified,
               totp_secret, totp_enabled, totp_recovery, totp_verified_at,
               risk_per_trade_pct, max_portfolio_heat, max_position_pct, risk_settings_updated,
               email_notif_prefs
        FROM users WHERE id = ?
    ');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_unset();
        session_destroy();
        return null;
    }

    if (empty($_SESSION['_last_touch']) || $now - $_SESSION['_last_touch'] > 60) {
        db()->prepare('UPDATE users SET last_seen = NOW() WHERE id = ?')->execute([$user['id']]);
        $_SESSION['_last_touch'] = $now;
    }

    return $user;
}

function is_logged_in(): bool { return current_user() !== null; }

function require_login(): array
{
    $u = current_user();
    if (!$u) redirect(APP_URL . '/login.php');
    return $u;
}

function login_user(int $userId): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['_last_touch'] = time();
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $expected = $_SESSION['csrf'] ?? '';
    $sent     = $_POST['csrf'] ?? '';
    if ($expected === '' || !hash_equals($expected, $sent)) {
        http_response_code(419);
        die('CSRF token mismatch.');
    }
}

/* ============================================================
 *  ORDER + DCA ENGINES — run on each authenticated page load
 * ============================================================ */
require_once __DIR__ . '/order-engine.php';
require_once __DIR__ . '/dca-engine.php';

$__script = basename($_SERVER['PHP_SELF'] ?? '');
$__dir    = basename(dirname($_SERVER['PHP_SELF'] ?? ''));
$__inApi  = ($__dir === 'api');

if (!empty($_SESSION['user_id']) && !$__inApi) {
    run_order_engine((int)$_SESSION['user_id']);
    run_dca_engine((int)$_SESSION['user_id']);
}