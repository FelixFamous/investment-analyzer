<?php
/**
 * GET /api/analytics.php
 * Computes trading performance metrics for the current user.
 */
require_once __DIR__ . '/../includes/auth.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$uid = (int)$user['id'];

/* ============================================================
 *  BASE — all SELL trades with realized P&L
 * ============================================================ */
$stmt = db()->prepare('
    SELECT id, symbol, quantity, price, total, pnl, created_at
    FROM trades
    WHERE user_id = ? AND side = "SELL" AND pnl IS NOT NULL
    ORDER BY created_at ASC
');
$stmt->execute([$uid]);
$trades = $stmt->fetchAll();

$wins = [];
$losses = [];
$totalPnl = 0.0;
$grossWin = 0.0;
$grossLoss = 0.0;

foreach ($trades as $t) {
    $p = (float)$t['pnl'];
    $totalPnl += $p;
    if ($p > 0) { $wins[] = $p; $grossWin += $p; }
    elseif ($p < 0) { $losses[] = abs($p); $grossLoss += abs($p); }
}

$n = count($trades);
$winCount = count($wins);
$lossCount = count($losses);
$winRate = $n > 0 ? ($winCount / $n) * 100 : 0;
$avgWin = $winCount > 0 ? $grossWin / $winCount : 0;
$avgLoss = $lossCount > 0 ? $grossLoss / $lossCount : 0;
$profitFactor = $grossLoss > 0 ? $grossWin / $grossLoss : ($grossWin > 0 ? 999 : 0);
$expectancy = $n > 0 ? $totalPnl / $n : 0;

/* ============================================================
 *  WIN RATE & P&L BY ASSET
 * ============================================================ */
$byAsset = [];
foreach ($trades as $t) {
    $sym = $t['symbol'];
    if (!isset($byAsset[$sym])) $byAsset[$sym] = ['count' => 0, 'wins' => 0, 'pnl' => 0.0];
    $byAsset[$sym]['count']++;
    if ((float)$t['pnl'] > 0) $byAsset[$sym]['wins']++;
    $byAsset[$sym]['pnl'] += (float)$t['pnl'];
}
foreach ($byAsset as $sym => &$b) {
    $b['win_rate'] = $b['count'] > 0 ? ($b['wins'] / $b['count']) * 100 : 0;
}
unset($b);
// Sort by pnl desc
uasort($byAsset, fn($a, $b) => $b['pnl'] <=> $a['pnl']);

/* ============================================================
 *  WIN RATE & P&L BY DAY OF WEEK
 * ============================================================ */
$days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
$byDay = [];
foreach ($days as $d) $byDay[$d] = ['count' => 0, 'wins' => 0, 'pnl' => 0.0];

foreach ($trades as $t) {
    $dow = (int)date('w', strtotime($t['created_at']));
    $day = $days[$dow];
    $byDay[$day]['count']++;
    if ((float)$t['pnl'] > 0) $byDay[$day]['wins']++;
    $byDay[$day]['pnl'] += (float)$t['pnl'];
}
foreach ($byDay as $day => &$b) {
    $b['win_rate'] = $b['count'] > 0 ? ($b['wins'] / $b['count']) * 100 : 0;
}
unset($b);

/* ============================================================
 *  WIN RATE & P&L BY SESSION (UTC)
 * ============================================================ */
$bySession = [
    'Sydney'   => ['count' => 0, 'wins' => 0, 'pnl' => 0.0],
    'Asian'    => ['count' => 0, 'wins' => 0, 'pnl' => 0.0],
    'London'   => ['count' => 0, 'wins' => 0, 'pnl' => 0.0],
    'New York' => ['count' => 0, 'wins' => 0, 'pnl' => 0.0],
];

foreach ($trades as $t) {
    $h = (int)date('G', strtotime($t['created_at'] . ' UTC'));
    if ($h >= 7 && $h < 13) $s = 'London';
    elseif ($h >= 13 && $h < 21) $s = 'New York';
    elseif ($h >= 21 || $h < 2) $s = 'Sydney';
    else $s = 'Asian';

    $bySession[$s]['count']++;
    if ((float)$t['pnl'] > 0) $bySession[$s]['wins']++;
    $bySession[$s]['pnl'] += (float)$t['pnl'];
}
foreach ($bySession as $s => &$b) {
    $b['win_rate'] = $b['count'] > 0 ? ($b['wins'] / $b['count']) * 100 : 0;
}
unset($b);

/* ============================================================
 *  MONTHLY RETURNS
 * ============================================================ */
$byMonth = [];
foreach ($trades as $t) {
    $m = date('Y-m', strtotime($t['created_at']));
    if (!isset($byMonth[$m])) $byMonth[$m] = 0.0;
    $byMonth[$m] += (float)$t['pnl'];
}
ksort($byMonth);

/* ============================================================
 *  STREAKS
 * ============================================================ */
$curStreak = 0;
$longestWin = 0;
$longestLoss = 0;
$curType = null;

foreach ($trades as $t) {
    $isWin = (float)$t['pnl'] > 0;
    if ($curType === null || $curType === $isWin) {
        $curStreak++;
        $curType = $isWin;
    } else {
        $curStreak = 1;
        $curType = $isWin;
    }
    if ($isWin) $longestWin = max($longestWin, $curStreak);
    else $longestLoss = max($longestLoss, $curStreak);
}

/* ============================================================
 *  EQUITY CURVE — daily cumulative
 * ============================================================ */
$equity = [];
$cum = 0.0;
foreach ($trades as $t) {
    $cum += (float)$t['pnl'];
    $d = date('Y-m-d', strtotime($t['created_at']));
    if (!isset($equity[$d])) $equity[$d] = 0;
    $equity[$d] = $cum;
}
$equitySeries = [];
foreach ($equity as $d => $v) $equitySeries[] = ['date' => $d, 'value' => $v];

/* ============================================================
 *  RESPONSE
 * ============================================================ */
json_response([
    'summary' => [
        'total_trades'   => $n,
        'win_count'      => $winCount,
        'loss_count'     => $lossCount,
        'win_rate'       => round($winRate, 2),
        'total_pnl'      => round($totalPnl, 2),
        'gross_win'      => round($grossWin, 2),
        'gross_loss'     => round($grossLoss, 2),
        'avg_win'        => round($avgWin, 2),
        'avg_loss'       => round($avgLoss, 2),
        'profit_factor'  => round($profitFactor, 2),
        'expectancy'     => round($expectancy, 2),
        'longest_win'    => $longestWin,
        'longest_loss'   => $longestLoss,
    ],
    'by_asset'   => $byAsset,
    'by_day'     => $byDay,
    'by_session' => $bySession,
    'by_month'   => $byMonth,
    'equity'     => $equitySeries,
]);