<?php
/**
 * GET /api/correlation.php?days=90
 * Computes pairwise correlation between all tracked assets
 * based on daily returns.
 *
 * Uses cached history via the internal history loader; if a symbol has no
 * cache, falls back to synthetic history so a matrix is always produced.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$days = max(30, min(180, (int)($_GET['days'] ?? 90)));

/* ============================================================
 *  SYMBOLS
 * ============================================================ */
$symbols = [
    'BTC' => 'bitcoin', 'ETH' => 'ethereum', 'SOL' => 'solana',
    'BNB' => 'binancecoin', 'XRP' => 'ripple', 'DOGE' => 'dogecoin',
    'AAPL' => 'AAPL', 'MSFT' => 'MSFT', 'NVDA' => 'NVDA', 'TSLA' => 'TSLA',
    'META' => 'META', 'AMZN' => 'AMZN',
];

/* ============================================================
 *  FETCH DAILY CLOSES
 *  Reads from cache/hist_*.json if present; falls back to synthetic.
 * ============================================================ */
function get_daily_closes(string $symbol, int $days): array
{
    // Try daily cache first
    $cacheDir = __DIR__ . '/../cache';
    $candidates = [
        $cacheDir . '/hist_' . preg_replace('/[^A-Z0-9]/', '', $symbol) . '_1y.json',
        $cacheDir . '/hist_' . preg_replace('/[^A-Z0-9]/', '', $symbol) . '_90d.json',
        $cacheDir . '/hist_' . preg_replace('/[^A-Z0-9]/', '', $symbol) . '_30d.json',
    ];

    foreach ($candidates as $file) {
        if (!is_file($file)) continue;
        $json = @file_get_contents($file);
        if (!$json) continue;
        $data = json_decode($json, true);
        if (!isset($data['candles'])) continue;

        // Group by UTC day → take last close of each day
        $byDay = [];
        foreach ($data['candles'] as $c) {
            $d = gmdate('Y-m-d', (int)($c['t'] / 1000));
            $byDay[$d] = (float)$c['c'];
        }
        ksort($byDay);
        return array_slice($byDay, -$days, null, true);
    }

    // Fallback: synthetic history
    $hist = synthetic_history($symbol, 60);
    $out = [];
    $now = time();
    for ($i = count($hist) - 1; $i >= 0; $i--) {
        $d = gmdate('Y-m-d', $now - (count($hist) - 1 - $i) * 86400);
        $out[$d] = $hist[$i];
    }
    return array_slice($out, -$days, null, true);
}

/* ============================================================
 *  BUILD RETURN SERIES
 * ============================================================ */
$returns = [];
$closes = [];

foreach ($symbols as $display => $src) {
    $series = get_daily_closes($display, $days);
    $closes[$display] = $series;

    $ret = [];
    $prev = null;
    foreach ($series as $date => $price) {
        if ($prev !== null && $prev > 0) {
            $ret[$date] = ($price - $prev) / $prev;
        }
        $prev = $price;
    }
    $returns[$display] = $ret;
}

/* ============================================================
 *  PEARSON CORRELATION ON COMMON DATES
 * ============================================================ */
function pearson(array $a, array $b): float
{
    // Filter to common dates
    $common = array_intersect_key($a, $b);
    $n = count($common);
    if ($n < 3) return 0.0;

    $xa = $xb = [];
    foreach ($common as $d => $_) {
        $xa[] = $a[$d];
        $xb[] = $b[$d];
    }

    $meanA = array_sum($xa) / $n;
    $meanB = array_sum($xb) / $n;

    $num = 0.0; $denA = 0.0; $denB = 0.0;
    for ($i = 0; $i < $n; $i++) {
        $da = $xa[$i] - $meanA;
        $db = $xb[$i] - $meanB;
        $num += $da * $db;
        $denA += $da * $da;
        $denB += $db * $db;
    }
    if ($denA == 0 || $denB == 0) return 0.0;
    return $num / sqrt($denA * $denB);
}

$names = array_keys($symbols);
$matrix = [];
foreach ($names as $symA) {
    $matrix[$symA] = [];
    foreach ($names as $symB) {
        if ($symA === $symB) { $matrix[$symA][$symB] = 1.0; continue; }
        $matrix[$symA][$symB] = round(pearson($returns[$symA], $returns[$symB]), 3);
    }
}

json_response([
    'symbols' => $names,
    'matrix'  => $matrix,
    'days'    => $days,
]);