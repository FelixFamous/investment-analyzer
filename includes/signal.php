<?php
/**
 * Signal engine v3 — multi-factor, regime-aware, news-aware.
 *
 * NOTE ON SIMPLIFICATIONS (deliberate, for a lightweight sim):
 *   - atr() uses mean absolute close-to-close change, not True Range
 *     (which needs high/low data we don't have from the feed).
 *   - rsi() uses the simple average, not Wilder's EMA smoothing.
 *   - No commission, spread, or slippage modelling.
 * These keep the engine dependency-free. Documented so they are not
 * mistaken for oversights.
 */

require_once __DIR__ . '/market.php';

/* ==================== Indicators ==================== */

function sma(array $s, int $p): ?float {
    if (count($s) < $p) return null;
    return array_sum(array_slice($s, -$p)) / $p;
}

function ema_series(array $s, int $p): array {
    if (count($s) < $p) return [];
    $k = 2 / ($p + 1);
    $out = [];
    $prev = array_sum(array_slice($s, 0, $p)) / $p;
    $out[] = $prev;
    for ($i = $p; $i < count($s); $i++) {
        $prev = ($s[$i] - $prev) * $k + $prev;
        $out[] = $prev;
    }
    return $out;
}

function macd(array $s, int $fast = 12, int $slow = 26, int $signal = 9): array {
    if (count($s) < $slow + $signal) return ['macd' => 0, 'signal' => 0, 'hist' => 0];
    $emaFast = ema_series($s, $fast);
    $emaSlow = ema_series($s, $slow);
    $offset = count($emaFast) - count($emaSlow);
    $macdLine = [];
    for ($i = 0; $i < count($emaSlow); $i++) {
        $macdLine[] = $emaFast[$i + $offset] - $emaSlow[$i];
    }
    $signalLine = ema_series($macdLine, $signal);
    $lastMacd = end($macdLine) ?: 0;
    $lastSignal = end($signalLine) ?: 0;
    return [
        'macd'   => $lastMacd,
        'signal' => $lastSignal,
        'hist'   => $lastMacd - $lastSignal,
    ];
}

function rsi(array $s, int $p = 14): float {
    if (count($s) < $p + 1) return 50.0;
    $g = 0.0; $l = 0.0; $n = count($s);
    for ($i = $n - $p; $i < $n; $i++) {
        $d = $s[$i] - $s[$i - 1];
        if ($d >= 0) $g += $d; else $l -= $d;
    }
    if ($l == 0) return 100.0;
    $rs = ($g / $p) / ($l / $p);
    return 100.0 - (100.0 / (1.0 + $rs));
}

function bollinger(array $s, int $p = 20, float $mult = 2.0): array {
    if (count($s) < $p) return ['upper' => 0, 'mid' => 0, 'lower' => 0, 'pos' => 0];
    $slice = array_slice($s, -$p);
    $mid = array_sum($slice) / $p;
    $variance = 0;
    foreach ($slice as $x) $variance += ($x - $mid) ** 2;
    $sd = sqrt($variance / $p);
    $upper = $mid + $mult * $sd;
    $lower = $mid - $mult * $sd;
    $price = end($s);
    $range = $upper - $lower;
    $pos = $range > 0 ? (($price - $lower) / $range) * 2 - 1 : 0;
    return ['upper' => $upper, 'mid' => $mid, 'lower' => $lower, 'pos' => $pos];
}

function atr(array $s, int $p = 14): float {
    $n = count($s);
    if ($n < $p + 1) return $s[$n - 1] * 0.02;
    $sum = 0.0;
    for ($i = $n - $p; $i < $n; $i++) $sum += abs($s[$i] - $s[$i - 1]);
    return $sum / $p;
}

function momentum(array $s, int $lb): float {
    $n = count($s);
    if ($n < $lb + 1) return 0.0;
    return ($s[$n - 1] / $s[$n - 1 - $lb]) - 1.0;
}

/* ==================== News sentiment ==================== */

$GLOBALS['news_sentiment'] = [];

function set_news_sentiment(array $map): void {
    $GLOBALS['news_sentiment'] = $map;
}

function get_news_sentiment(string $symbol): float {
    return $GLOBALS['news_sentiment'][$symbol] ?? 0.0;
}

/**
 * Load sentiment from the shared news cache file (written by api/news.php).
 * No HTTP self-call → no deadlock risk under PHP-FPM.
 */
function load_news_sentiment(): void {
    static $loaded = false;
    if ($loaded) return;
    $loaded = true;

    $cacheFile = sys_get_temp_dir() . '/alphaedge_news.json';
    if (!file_exists($cacheFile) || (time() - filemtime($cacheFile)) > 600) {
        return; // missing or stale — skip sentiment this pass
    }

    $json = @file_get_contents($cacheFile);
    if (!$json) return;
    $data = json_decode($json, true);
    if (!is_array($data)) return;

    $map = [];
    foreach (['crypto', 'stocks'] as $bucket) {
        foreach ($data[$bucket] ?? [] as $item) {
            $sym = $item['symbol'] ?? null;
            if (!$sym) continue;
            $s = (float)($item['sentiment'] ?? 0);
            $map[$sym] = ($map[$sym] ?? 0) + $s;
        }
    }
    foreach ($map as $k => $v) {
        $map[$k] = max(-1.0, min(1.0, $v / 3.0));
    }
    set_news_sentiment($map);
}

/* ==================== Regime detection ==================== */

function detect_regime(array $history): string {
    $s20 = sma($history, 20);
    $s50 = sma($history, 50);
    if (!$s20 || !$s50) return 'ranging';

    $separation = ($s20 - $s50) / $s50;
    $mom = momentum($history, 20);

    if ($separation > 0.02 && $mom > 0.03) return 'trending_up';
    if ($separation < -0.02 && $mom < -0.03) return 'trending_down';
    return 'ranging';
}

/* ==================== Main engine ==================== */

function compute_signals(): array {
    load_news_sentiment();
    $rows = [];

    foreach (tracked_assets() as $asset) {
        $symbol  = $asset['symbol'];
        $history = synthetic_history($symbol, 60);
        $price   = get_price($symbol) ?? end($history);

        $s20  = sma($history, 20);
        $s50  = sma($history, 50);
        $r    = rsi($history, 14);
        $bb   = bollinger($history, 20, 2.0);
        $macd = macd($history);
        $a    = atr($history, 14);
        $m5   = momentum($history, 5);
        $m20  = momentum($history, 20);
        $regime = detect_regime($history);
        $news = get_news_sentiment($symbol);

        $f_trend = 0.0;
        if ($s20 && $s50) {
            $spread = ($s20 - $s50) / $s50;
            $f_trend = max(-1.0, min(1.0, $spread * 25.0));
        }

        $f_longTrend = $s50 ? max(-1.0, min(1.0, (($price - $s50) / $s50) * 10.0)) : 0.0;

        $f_rsi = 0.0;
        if ($r < 30)      $f_rsi = 0.8;
        elseif ($r > 70)  $f_rsi = -0.8;
        else              $f_rsi = (50 - $r) / 25.0 * 0.4;

        $histNorm = $a > 0 ? $macd['hist'] / $a : 0;
        $f_macd = max(-1.0, min(1.0, $histNorm * 1.5));

        $f_bb = -$bb['pos'];

        $f_m20 = max(-1.0, min(1.0, $m20 * 6.0));
        $f_m5  = max(-1.0, min(1.0, $m5 * 8.0));

        $atrPct = $price > 0 ? $a / $price : 0;
        $f_volConfirm = 0.0;
        if ($atrPct > 0.03) {
            $f_volConfirm = -0.15;
        } elseif ($atrPct < 0.01) {
            $f_volConfirm = -0.05;
        }

        $f_news = $news;

        if ($regime === 'trending_up' || $regime === 'trending_down') {
            $composite =
                  $f_trend      * 0.22
                + $f_longTrend  * 0.15
                + $f_rsi        * 0.05
                + $f_macd       * 0.20
                + $f_bb         * 0.05
                + $f_m20        * 0.15
                + $f_m5         * 0.08
                + $f_volConfirm * 0.02
                + $f_news       * 0.08;
        } else {
            $composite =
                  $f_trend      * 0.10
                + $f_longTrend  * 0.05
                + $f_rsi        * 0.22
                + $f_macd       * 0.08
                + $f_bb         * 0.22
                + $f_m20        * 0.08
                + $f_m5         * 0.05
                + $f_volConfirm * 0.05
                + $f_news       * 0.15;
        }

        $composite = max(-1.0, min(1.0, $composite));

        $factors = [
            $f_trend, $f_longTrend, $f_rsi, $f_macd, $f_bb,
            $f_m20, $f_m5, $f_volConfirm, $f_news
        ];
        $sign = $composite >= 0 ? 1 : -1;
        $agree = 0;
        foreach ($factors as $f) {
            if (abs($f) < 0.05) continue;
            if ($f * $sign > 0) $agree++;
        }
        $totalFactors = count($factors);
        $agreement = $agree / $totalFactors;

        $confidence = min(1.0, abs($composite) * 1.3 * (0.5 + $agreement * 0.7));

        if      ($composite >  0.55) $action = 'STRONG BUY';
        elseif  ($composite >  0.30) $action = 'BUY';
        elseif  ($composite >  0.10) $action = 'WEAK BUY';
        elseif  ($composite < -0.55) $action = 'STRONG SELL';
        elseif  ($composite < -0.30) $action = 'SELL';
        elseif  ($composite < -0.10) $action = 'WEAK SELL';
        else                          $action = 'HOLD';

        $bullish = $composite > 0;
        if ($bullish) {
            $target = $price + $a * (1.8 + $composite * 3.0);
            $stop   = $price - $a * 1.5;
        } else {
            $target = $price - $a * (1.8 + abs($composite) * 3.0);
            $stop   = $price + $a * 1.5;
        }
        $takeProfit = $bullish ? $target : null;

        $reasons = [];

        if ($s20 && $s50) {
            $reasons[] = $s20 > $s50
                ? 'Uptrend: SMA20 above SMA50'
                : 'Downtrend: SMA20 below SMA50';
        }

        if ($r < 30)      $reasons[] = 'RSI ' . round($r) . ' oversold (buy zone)';
        elseif ($r > 70)  $reasons[] = 'RSI ' . round($r) . ' overbought (sell zone)';
        elseif ($r < 45)  $reasons[] = 'RSI ' . round($r) . ' leaning weak';
        elseif ($r > 55)  $reasons[] = 'RSI ' . round($r) . ' leaning strong';

        if (abs($macd['hist']) > 0.001) {
            $reasons[] = $macd['hist'] > 0
                ? 'MACD histogram positive (bullish crossover)'
                : 'MACD histogram negative (bearish crossover)';
        }

        if ($bb['pos'] < -0.7)      $reasons[] = 'Price near lower Bollinger band';
        elseif ($bb['pos'] > 0.7)   $reasons[] = 'Price near upper Bollinger band';

        if (abs($m20) > 0.03) {
            $reasons[] = ($m20 > 0 ? '+' : '') . number_format($m20 * 100, 1) . '% 20d momentum';
        }

        if ($regime !== 'ranging') {
            $reasons[] = 'Market regime: ' . str_replace('_', ' ', $regime);
        }

        if (abs($news) > 0.15) {
            $reasons[] = $news > 0 ? 'Positive news sentiment' : 'Negative news sentiment';
        }

        $reasons[] = number_format($agreement * 100, 0) . '% factor agreement';

        $rows[] = [
            'symbol'      => $symbol,
            'name'        => $asset['name'],
            'type'        => $asset['type'],
            'price'       => $price,
            'action'      => $action,
            'confidence'  => $confidence,
            'score'       => $composite,
            'agreement'   => $agreement,
            'regime'      => $regime,
            'rsi'         => $r,
            'macd_hist'   => $macd['hist'],
            'bb_pos'      => $bb['pos'],
            'target'      => $target,
            'stop'        => $stop,
            'take_profit' => $takeProfit,
            'reasons'     => $reasons,
            'history'     => array_slice($history, -30),
        ];
    }

    usort($rows, fn($a, $b) => abs($b['score']) <=> abs($a['score']));
    return $rows;
}

function group_signals(array $signals): array {
    $out = ['buy' => [], 'sell' => [], 'hold' => []];
    foreach ($signals as $s) {
        $a = strtoupper($s['action']);
        if (str_contains($a, 'BUY'))       $out['buy'][] = $s;
        elseif (str_contains($a, 'SELL'))  $out['sell'][] = $s;
        else                                $out['hold'][] = $s;
    }
    return $out;
}

function analyze_holdings(array $holdings, array $signals): array {
    $bySymbol = [];
    foreach ($signals as $s) $bySymbol[$s['symbol']] = $s;

    $out = [];
    foreach ($holdings as $h) {
        $sym = $h['symbol'];
        $sig = $bySymbol[$sym] ?? null;
        if (!$sig) continue;

        $pnlPct = $h['avg'] > 0 ? (($h['price'] - $h['avg']) / $h['avg']) * 100 : 0;

        $recommendation = 'HOLD';
        $reasonList = [];

        if (str_contains($sig['action'], 'SELL')) {
            $recommendation = 'SELL';
            $reasonList[] = 'Engine: ' . $sig['action'];
        }
        if ($pnlPct >= 8) {
            $recommendation = 'SELL';
            $reasonList[] = 'Take profit: up ' . number_format($pnlPct, 1) . '%';
        }
        if ($pnlPct <= -5) {
            $recommendation = 'SELL';
            $reasonList[] = 'Stop loss: down ' . number_format($pnlPct, 1) . '%';
        }
        if ($sig['rsi'] > 72) {
            $recommendation = 'SELL';
            $reasonList[] = 'RSI ' . round($sig['rsi']) . ' — extremely overbought';
        }
        if ($sig['bb_pos'] > 0.85) {
            $recommendation = 'SELL';
            $reasonList[] = 'Price at upper Bollinger band';
        }
        if ($recommendation === 'HOLD' && str_contains($sig['action'], 'BUY') && $sig['confidence'] > 0.5) {
            $reasonList[] = 'Strong BUY signal — consider adding';
        }
        if (!$reasonList) $reasonList[] = 'No strong exit signal — hold';

        $out[$sym] = [
            'symbol'         => $sym,
            'qty'            => $h['qty'],
            'avg'            => $h['avg'],
            'price'          => $h['price'],
            'value'          => $h['value'],
            'pnl'            => $h['pnl'],
            'pnl_pct'        => $pnlPct,
            'recommendation' => $recommendation,
            'reasons'        => $reasonList,
            'engine_action'  => $sig['action'],
        ];
    }
    return $out;
}