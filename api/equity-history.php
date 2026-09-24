<?php
/**
 * GET /api/equity-history.php?days=30
 * Returns daily equity snapshots for the current user.
 * days = 7 | 30 | 90 | all
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$daysParam = trim((string)($_GET['days'] ?? '30'));

// Use fresh data — take a snapshot right now so "today" is up to date
require_once __DIR__ . '/../includes/market.php';
snapshot_portfolio((int)$user['id']);

if ($daysParam === 'all') {
    $stmt = db()->prepare('
        SELECT snapshot_date, equity, cash, holdings_value
        FROM portfolio_snapshots
        WHERE user_id = ?
        ORDER BY snapshot_date ASC
    ');
    $stmt->execute([$user['id']]);
} else {
    $days = max(1, min(365, (int)$daysParam));
    $stmt = db()->prepare('
        SELECT snapshot_date, equity, cash, holdings_value
        FROM portfolio_snapshots
        WHERE user_id = ?
          AND snapshot_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        ORDER BY snapshot_date ASC
    ');
    $stmt->execute([$user['id'], $days]);
}

$rows = $stmt->fetchAll();

$points = [];
foreach ($rows as $r) {
    $points[] = [
        'date'     => $r['snapshot_date'],
        'equity'   => (float)$r['equity'],
        'cash'     => (float)$r['cash'],
        'holdings' => (float)$r['holdings_value'],
    ];
}

// Compute summary stats
$start = $points ? $points[0]['equity'] : 0;
$end   = $points ? $points[count($points) - 1]['equity'] : 0;
$delta = $end - $start;
$pct   = $start > 0 ? ($delta / $start) * 100 : 0;

$high = 0; $low = PHP_FLOAT_MAX;
foreach ($points as $p) {
    if ($p['equity'] > $high) $high = $p['equity'];
    if ($p['equity'] < $low)  $low  = $p['equity'];
}
if (!$points) { $high = 0; $low = 0; }

json_response([
    'points'  => $points,
    'start'   => $start,
    'end'     => $end,
    'delta'   => $delta,
    'pct'     => $pct,
    'high'    => $high,
    'low'     => $low,
    'count'   => count($points),
]);