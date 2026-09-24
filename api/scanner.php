<?php
/**
 * GET /api/scanner.php
 * Returns every tracked asset with a snapshot of its technical state,
 * plus which preset scans it triggers.
 *
 * Front-end can then filter/scan client-side.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';
require_once __DIR__ . '/../includes/signal.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$results = [];

foreach (tracked_assets() as $asset) {
    $sym = $asset['symbol'];

    // Pull recent history
    $hist = synthetic_history($sym, 60);

    // Latest real price
    $price = get_price($sym);
    if ($price === null) continue;

    // Compute indicators on the fly
    $sma20 = sma($hist, 20);
    $sma50 = sma($hist, 50);
    $rsi   = rsi($hist, 14);
    $mom20 = momentum($hist, 20);
    $vol   = $asset['vol']; // volatility proxy

    // Simple 24h change — we fake it from the last few synthetic points
    $changePct = count($hist) >= 2
        ? (($hist[count($hist)-1] - $hist[count($hist)-2]) / $hist[count($hist)-2]) * 100
        : 0;

    // Direction & state
    $trendBull = $sma20 !== null && $sma50 !== null && $sma20 > $sma50;

    // Build scan tags
    $tags = [];
    if ($trendBull) $tags[] = ['text' => 'Uptrend', 'class' => 'bull'];
    else            $tags[] = ['text' => 'Downtrend', 'class' => 'bear'];

    if ($rsi > 70) $tags[] = ['text' => 'Overbought', 'class' => 'ob'];
    elseif ($rsi < 30) $tags[] = ['text' => 'Oversold', 'class' => 'os'];

    if (abs($mom20) > 0.05) $tags[] = ['text' => ($mom20 > 0 ? 'Strong up' : 'Strong down'), 'class' => $mom20 > 0 ? 'bull' : 'bear'];

    if ($vol < 0.02) $tags[] = ['text' => 'Low vol', 'class' => 'neutral'];

    // Scan flags for client-side filtering
    $scans = [];
    if ($trendBull && $rsi > 50)          $scans[] = 'trend_bull';
    if (!$trendBull && $rsi < 50)         $scans[] = 'trend_bear';
    if ($rsi > 70)                        $scans[] = 'overbought';
    if ($rsi < 30)                        $scans[] = 'oversold';
    if ($mom20 > 0.05)                    $scans[] = 'momentum_up';
    if ($mom20 < -0.05)                   $scans[] = 'momentum_down';
    if ($vol > 0.05)                      $scans[] = 'high_vol';

    $results[] = [
        'symbol' => $sym,
        'name'   => $asset['name'],
        'type'   => $asset['type'],
        'price'  => $price,
        'changePct' => round($changePct, 2),
        'rsi'    => $rsi !== null ? round($rsi, 1) : null,
        'sma20'  => $sma20 !== null ? round($sma20, 4) : null,
        'sma50'  => $sma50 !== null ? round($sma50, 4) : null,
        'mom20'  => round($mom20 * 100, 2),
        'vol'    => round($vol * 100, 2),
        'trend'  => $trendBull ? 'bull' : 'bear',
        'tags'   => $tags,
        'scans'  => $scans,
    ];
}

json_response(['assets' => $results]);