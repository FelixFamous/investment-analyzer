<?php
/**
 * GET /api/risk-monitor.php
 * Real-time portfolio risk analysis with AI-style observations.
 * Returns: alerts, exposure, concentration, volatility, recommendations.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
$uid = (int)$user['id'];

/* ---------- Portfolio ---------- */
$stmt = db()->prepare('SELECT symbol, quantity, avg_price, take_profit, stop_loss FROM holdings WHERE user_id = ?');
$stmt->execute([$uid]);
$rawHoldings = $stmt->fetchAll();

$cash = (float)$user['cash_balance'];
$holdings = [];
$equity = $cash;

foreach ($rawHoldings as $h) {
    $live = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $val = (float)$h['quantity'] * $live;
    $cost = (float)$h['quantity'] * (float)$h['avg_price'];
    $pnl = $val - $cost;

    $sl = $h['stop_loss'] !== null ? (float)$h['stop_loss'] : null;
    $riskIfStopped = $sl !== null ? max(0, $live - $sl) * (float)$h['quantity'] : $val * 0.15;

    $holdings[] = [
        'symbol' => $h['symbol'],
        'qty' => (float)$h['quantity'],
        'avg' => (float)$h['avg_price'],
        'live' => $live,
        'value' => $val,
        'pnl' => $pnl,
        'pnl_pct' => $cost > 0 ? ($pnl / $cost) * 100 : 0,
        'has_sl' => $sl !== null,
        'risk_if_stopped' => $riskIfStopped,
        'stop_loss' => $sl,
    ];
    $equity += $val;
}

/* ---------- Alerts ---------- */
$alerts = [];
$recommendations = [];

// Concentration: any position > 25% of equity
$maxPos = (float)($user['max_position_pct'] ?? 20);
foreach ($holdings as $h) {
    $pct = $equity > 0 ? ($h['value'] / $equity) * 100 : 0;
    if ($pct > $maxPos) {
        $alerts[] = [
            'level' => 'warning',
            'icon' => '⚖️',
            'title' => $h['symbol'] . ' concentration ' . number_format($pct, 1) . '%',
            'detail' => 'Exceeds your ' . number_format($maxPos, 1) . '% per-position limit',
        ];
    }
}

// Missing stops
$noStop = array_filter($holdings, fn($h) => !$h['has_sl']);
if (count($noStop) > 0) {
    $symbols = implode(', ', array_column($noStop, 'symbol'));
    $alerts[] = [
        'level' => 'warning',
        'icon' => '🛑',
        'title' => count($noStop) . ' position' . (count($noStop) === 1 ? '' : 's') . ' without stop-loss',
        'detail' => $symbols,
    ];
}

// Portfolio heat
$totalRisk = array_sum(array_column($holdings, 'risk_if_stopped'));
$heatPct = $equity > 0 ? ($totalRisk / $equity) * 100 : 0;
$maxHeat = (float)($user['max_portfolio_heat'] ?? 6);

if ($heatPct > $maxHeat) {
    $alerts[] = [
        'level' => 'danger',
        'icon' => '🔥',
        'title' => 'Portfolio heat ' . number_format($heatPct, 2) . '%',
        'detail' => 'Above your ' . number_format($maxHeat, 1) . '% limit — reduce size or tighten stops',
    ];
}

// Cash level
$cashPct = $equity > 0 ? ($cash / $equity) * 100 : 0;
if ($cashPct < 5 && count($holdings) > 0) {
    $alerts[] = [
        'level' => 'warning',
        'icon' => '💧',
        'title' => 'Low cash reserve (' . number_format($cashPct, 1) . '%)',
        'detail' => 'Consider trimming a position to rebuild your cash buffer',
    ];
}
if ($cashPct > 80 && count($holdings) > 0) {
    $alerts[] = [
        'level' => 'info',
        'icon' => '💰',
        'title' => 'High cash position (' . number_format($cashPct, 1) . '%)',
        'detail' => 'You may be under-deployed. Consider adding to your best setups',
    ];
}

// Drawdown check from portfolio snapshots
$stmt = db()->prepare('SELECT MAX(equity) FROM portfolio_snapshots WHERE user_id = ?');
$stmt->execute([$uid]);
$peakEquity = (float)$stmt->fetchColumn();
if ($peakEquity > 0 && $equity < $peakEquity) {
    $dd = (($peakEquity - $equity) / $peakEquity) * 100;
    if ($dd > 10) {
        $alerts[] = [
            'level' => 'danger',
            'icon' => '📉',
            'title' => 'Drawdown ' . number_format($dd, 2) . '% from peak',
            'detail' => 'Peak equity was $' . number_format($peakEquity, 2) . ' — now $' . number_format($equity, 2),
        ];
    }
}

// Correlation exposure — count positions in same direction
$crypto = array_filter($holdings, fn($h) => in_array($h['symbol'], ['BTC','ETH','SOL','BNB','XRP','DOGE','ADA','PEPE']));
if (count($crypto) >= 4) {
    $cryptoVal = array_sum(array_column($crypto, 'value'));
    $cryptoPct = $equity > 0 ? ($cryptoVal / $equity) * 100 : 0;
    if ($cryptoPct > 50) {
        $alerts[] = [
            'level' => 'warning',
            'icon' => '🔗',
            'title' => 'High crypto exposure (' . number_format($cryptoPct, 1) . '%)',
            'detail' => count($crypto) . ' crypto positions — they tend to move together',
        ];
    }
}

// Recommendations
if (count($holdings) === 0) {
    $recommendations[] = 'You have no open positions. Visit Markets or Signals to start trading.';
} else {
    $best = null;
    foreach ($holdings as $h) if ($best === null || $h['pnl_pct'] > $best['pnl_pct']) $best = $h;
    if ($best && $best['pnl_pct'] > 8) {
        $recommendations[] = $best['symbol'] . ' is up ' . number_format($best['pnl_pct'], 1) . '% — consider taking partial profits.';
    }

    $worst = null;
    foreach ($holdings as $h) if ($worst === null || $h['pnl_pct'] < $worst['pnl_pct']) $worst = $h;
    if ($worst && $worst['pnl_pct'] < -5 && !$worst['has_sl']) {
        $recommendations[] = $worst['symbol'] . ' is down ' . number_format(abs($worst['pnl_pct']), 1) . '% with no stop-loss — set one now.';
    }

    if ($heatPct < $maxHeat * 0.5 && $cashPct > 30) {
        $recommendations[] = 'Portfolio heat is low — you have room to add to high-conviction setups.';
    }
}

/* ---------- Risk score (0-100) ---------- */
$riskScore = 0;
$riskScore += min(40, $heatPct * 4);            // heat contribution (0-40)
$riskScore += min(20, count($noStop) * 6);      // unprotected positions (0-20)
$riskScore += min(20, max(0, ($cashPct < 5 ? 20 : 0))); // low cash (0-20)
$riskScore += min(20, max(0, (100 - $cashPct - count($holdings) * 8))); // concentration (0-20)
$riskScore = max(0, min(100, round($riskScore)));

$riskLevel = $riskScore >= 70 ? 'high' : ($riskScore >= 40 ? 'medium' : 'low');

json_response([
    'equity' => round($equity, 2),
    'cash' => round($cash, 2),
    'cash_pct' => round($cashPct, 2),
    'holdings_value' => round($equity - $cash, 2),
    'portfolio_heat_pct' => round($heatPct, 2),
    'max_heat_pct' => $maxHeat,
    'total_open_risk' => round($totalRisk, 2),
    'positions_count' => count($holdings),
    'unprotected_count' => count($noStop),
    'risk_score' => $riskScore,
    'risk_level' => $riskLevel,
    'peak_equity' => round($peakEquity, 2),
    'alerts' => $alerts,
    'recommendations' => $recommendations,
    'holdings' => $holdings,
]);