<?php
/**
 * Strategy engine — indicators, rule evaluation, backtesting.
 *
 * Rules format:
 *   {
 *     "logic": "AND" | "OR",
 *     "conditions": [
 *       { "indicator": "rsi", "operator": "<", "value": 30 },
 *       { "indicator": "close", "operator": ">", "value": "sma50" },
 *       { "indicator": "close", "operator": "crosses_above", "value": "sma200" }
 *     ]
 *   }
 */

/* ============================================================
 *  INDICATOR CALCULATIONS
 *  Each returns a flat array aligned with the candle array,
 *  using null for the warm-up period.
 * ============================================================ */
function calc_series_sma(array $candles, int $period): array
{
    $out = array_fill(0, count($candles), null);
    $sum = 0.0;
    for ($i = 0; $i < count($candles); $i++) {
        $sum += $candles[$i]['close'];
        if ($i >= $period) $sum -= $candles[$i - $period]['close'];
        if ($i >= $period - 1) $out[$i] = $sum / $period;
    }
    return $out;
}

function calc_series_ema(array $candles, int $period): array
{
    $out = array_fill(0, count($candles), null);
    $k = 2 / ($period + 1);
    $ema = null;
    for ($i = 0; $i < count($candles); $i++) {
        if ($i < $period - 1) continue;
        if ($ema === null) {
            $s = 0;
            for ($j = $i - $period + 1; $j <= $i; $j++) $s += $candles[$j]['close'];
            $ema = $s / $period;
        } else {
            $ema = $candles[$i]['close'] * $k + $ema * (1 - $k);
        }
        $out[$i] = $ema;
    }
    return $out;
}

function calc_series_rsi(array $candles, int $period = 14): array
{
    $out = array_fill(0, count($candles), null);
    $avgGain = 0.0; $avgLoss = 0.0;
    for ($i = 1; $i < count($candles); $i++) {
        $diff = $candles[$i]['close'] - $candles[$i - 1]['close'];
        $gain = $diff > 0 ? $diff : 0;
        $loss = $diff < 0 ? -$diff : 0;

        if ($i <= $period) {
            $avgGain += $gain / $period;
            $avgLoss += $loss / $period;
            if ($i === $period) {
                $rs = $avgLoss == 0 ? 100 : $avgGain / $avgLoss;
                $out[$i] = 100 - 100 / (1 + $rs);
            }
        } else {
            $avgGain = ($avgGain * ($period - 1) + $gain) / $period;
            $avgLoss = ($avgLoss * ($period - 1) + $loss) / $period;
            $rs = $avgLoss == 0 ? 100 : $avgGain / $avgLoss;
            $out[$i] = 100 - 100 / (1 + $rs);
        }
    }
    return $out;
}

function calc_series_macd(array $candles, int $fast = 12, int $slow = 26, int $signal = 9): array
{
    $emaFast = calc_series_ema($candles, $fast);
    $emaSlow = calc_series_ema($candles, $slow);
    $macd = [];
    foreach ($candles as $i => $c) {
        $macd[$i] = ($emaFast[$i] !== null && $emaSlow[$i] !== null) ? $emaFast[$i] - $emaSlow[$i] : null;
    }
    // Signal line = EMA of macd line (ignore nulls)
    $signalArr = array_fill(0, count($macd), null);
    $k = 2 / ($signal + 1);
    $sig = null;
    $count = 0;
    for ($i = 0; $i < count($macd); $i++) {
        if ($macd[$i] === null) continue;
        $count++;
        if ($sig === null) {
            if ($count >= $signal) {
                $sum = 0; $seen = 0;
                for ($j = $i; $j >= 0 && $seen < $signal; $j--) {
                    if ($macd[$j] === null) continue;
                    $sum += $macd[$j]; $seen++;
                }
                $sig = $sum / $signal;
                $signalArr[$i] = $sig;
            }
        } else {
            $sig = $macd[$i] * $k + $sig * (1 - $k);
            $signalArr[$i] = $sig;
        }
    }
    $hist = [];
    foreach ($macd as $i => $m) {
        $hist[$i] = ($m !== null && $signalArr[$i] !== null) ? $m - $signalArr[$i] : null;
    }
    return ['macd' => $macd, 'signal' => $signalArr, 'hist' => $hist];
}

function calc_series_bollinger(array $candles, int $period = 20, float $mult = 2): array
{
    $upper = array_fill(0, count($candles), null);
    $middle = array_fill(0, count($candles), null);
    $lower = array_fill(0, count($candles), null);
    $pos = array_fill(0, count($candles), null);

    for ($i = $period - 1; $i < count($candles); $i++) {
        $slice = array_slice($candles, $i - $period + 1, $period);
        $sum = 0; foreach ($slice as $c) $sum += $c['close'];
        $mean = $sum / $period;
        $varr = 0;
        foreach ($slice as $c) $varr += ($c['close'] - $mean) ** 2;
        $sd = sqrt($varr / $period);
        $u = $mean + $mult * $sd;
        $l = $mean - $mult * $sd;
        $upper[$i] = $u;
        $middle[$i] = $mean;
        $lower[$i] = $l;
        $range = $u - $l;
        $pos[$i] = $range > 0 ? (($candles[$i]['close'] - $l) / $range) * 2 - 1 : 0;
    }
    return ['upper' => $upper, 'middle' => $middle, 'lower' => $lower, 'pos' => $pos];
}

function calc_series_momentum(array $candles, int $lookback = 20): array
{
    $out = array_fill(0, count($candles), null);
    for ($i = $lookback; $i < count($candles); $i++) {
        $past = $candles[$i - $lookback]['close'];
        $out[$i] = $past > 0 ? (($candles[$i]['close'] - $past) / $past) : 0;
    }
    return $out;
}

/**
 * Compute the full indicator set once for a candle series.
 */
function build_indicator_set(array $candles): array
{
    return [
        'rsi'         => calc_series_rsi($candles, 14),
        'sma20'       => calc_series_sma($candles, 20),
        'sma50'       => calc_series_sma($candles, 50),
        'sma200'      => calc_series_sma($candles, 200),
        'ema12'       => calc_series_ema($candles, 12),
        'ema26'       => calc_series_ema($candles, 26),
        'macd'        => calc_series_macd($candles)['macd'],
        'macd_signal' => calc_series_macd($candles)['signal'],
        'macd_hist'   => calc_series_macd($candles)['hist'],
        'bb_upper'    => calc_series_bollinger($candles)['upper'],
        'bb_middle'   => calc_series_bollinger($candles)['middle'],
        'bb_lower'    => calc_series_bollinger($candles)['lower'],
        'bb_pos'      => calc_series_bollinger($candles)['pos'],
        'momentum'    => calc_series_momentum($candles, 20),
    ];
}

/* ============================================================
 *  RULE EVALUATION
 * ============================================================ */
function indicator_value(string $name, int $idx, array $indicators, array $candles)
{
    switch ($name) {
        case 'close':  return $candles[$idx]['close'];
        case 'open':   return $candles[$idx]['open'];
        case 'high':   return $candles[$idx]['high'];
        case 'low':    return $candles[$idx]['low'];
        case 'volume': return $candles[$idx]['volume'] ?? 0;
        case 'pnl_pct': return null; // handled separately in backtest loop
        default:
            return $indicators[$name][$idx] ?? null;
    }
}

/**
 * Evaluate a single condition at index $idx.
 * Returns true/false/null (null = warm-up, treat as false).
 */
function evaluate_condition(array $cond, int $idx, array $indicators, array $candles, ?float $pnlPct = null): bool
{
    $ind = $cond['indicator'] ?? '';
    $op  = $cond['operator']  ?? '>';
    $val = $cond['value']     ?? 0;

    // Special case: pnl_pct
    if ($ind === 'pnl_pct') {
        if ($pnlPct === null) return false;
        $left = $pnlPct;
    } else {
        $left = indicator_value($ind, $idx, $indicators, $candles);
        if ($left === null) return false;
    }

    // Right side: number or another indicator
    if (is_string($val) && !is_numeric($val)) {
        $right = indicator_value($val, $idx, $indicators, $candles);
        if ($right === null) return false;
    } else {
        $right = (float)$val;
    }

    // Crosses: need previous bar
    if ($op === 'crosses_above' || $op === 'crosses_below') {
        if ($idx < 1) return false;
        $prevLeft = ($ind === 'pnl_pct') ? null : indicator_value($ind, $idx - 1, $indicators, $candles);
        if ($prevLeft === null) return false;

        if (is_string($val) && !is_numeric($val)) {
            $prevRight = indicator_value($val, $idx - 1, $indicators, $candles);
            if ($prevRight === null) return false;
        } else {
            $prevRight = (float)$val;
        }

        if ($op === 'crosses_above') return $prevLeft <= $prevRight && $left > $right;
        else                         return $prevLeft >= $prevRight && $left < $right;
    }

    // Standard comparison
    switch ($op) {
        case '>':  return $left >  $right;
        case '<':  return $left <  $right;
        case '>=': return $left >= $right;
        case '<=': return $left <= $right;
        case '==': return abs($left - $right) < 1e-9;
        case '!=': return abs($left - $right) >= 1e-9;
    }
    return false;
}

/**
 * Evaluate a rule set (with logic AND/OR).
 */
function evaluate_rules(array $rules, int $idx, array $indicators, array $candles, ?float $pnlPct = null): bool
{
    $logic = strtoupper($rules['logic'] ?? 'AND');
    $conditions = $rules['conditions'] ?? [];
    if (!$conditions) return false;

    if ($logic === 'AND') {
        foreach ($conditions as $c) {
            if (!evaluate_condition($c, $idx, $indicators, $candles, $pnlPct)) return false;
        }
        return true;
    }

    // OR
    foreach ($conditions as $c) {
        if (evaluate_condition($c, $idx, $indicators, $candles, $pnlPct)) return true;
    }
    return false;
}

/* ============================================================
 *  BACKTEST
 * ============================================================ */
function run_backtest(array $candles, array $strategy, float $startingBalance = 100000.0): array
{
    $indicators = build_indicator_set($candles);
    $entry = $strategy['entry'] ?? ['logic' => 'AND', 'conditions' => []];
    $exit  = $strategy['exit']  ?? ['logic' => 'OR',  'conditions' => []];

    $cash = $startingBalance;
    $position = null; // ['qty', 'entryPrice', 'entryIdx', 'entryTime']
    $trades = [];
    $equity = [];

    for ($i = 0; $i < count($candles); $i++) {
        $c = $candles[$i];
        $price = $c['close'];

        // --- If in position, check exit ---
        if ($position !== null) {
            $pnlPct = (($price - $position['entryPrice']) / $position['entryPrice']) * 100;
            if (evaluate_rules($exit, $i, $indicators, $candles, $pnlPct)) {
                $proceeds = $position['qty'] * $price;
                $pnl = ($price - $position['entryPrice']) * $position['qty'];
                $cash += $proceeds;
                $trades[] = [
                    'entryTime' => $position['entryTime'],
                    'exitTime' => $c['time'],
                    'entryPrice' => $position['entryPrice'],
                    'exitPrice' => $price,
                    'qty' => $position['qty'],
                    'pnl' => $pnl,
                    'pnlPct' => (($price - $position['entryPrice']) / $position['entryPrice']) * 100,
                    'bars' => $i - $position['entryIdx'],
                ];
                $position = null;
            }
        }

        // --- If not in position, check entry ---
        if ($position === null && $cash > 0) {
            if (evaluate_rules($entry, $i, $indicators, $candles)) {
                $qty = $cash / $price;
                $position = [
                    'qty' => $qty,
                    'entryPrice' => $price,
                    'entryIdx' => $i,
                    'entryTime' => $c['time'],
                ];
                $cash = 0.0;
            }
        }

        // --- Equity snapshot ---
        $equityValue = $cash + ($position !== null ? $position['qty'] * $price : 0);
        $equity[] = ['time' => $c['time'], 'value' => $equityValue];
    }

    // Force close at end
    if ($position !== null) {
        $last = $candles[count($candles) - 1];
        $price = $last['close'];
        $proceeds = $position['qty'] * $price;
        $pnl = ($price - $position['entryPrice']) * $position['qty'];
        $cash += $proceeds;
        $trades[] = [
            'entryTime' => $position['entryTime'],
            'exitTime' => $last['time'],
            'entryPrice' => $position['entryPrice'],
            'exitPrice' => $price,
            'qty' => $position['qty'],
            'pnl' => $pnl,
            'pnlPct' => (($price - $position['entryPrice']) / $position['entryPrice']) * 100,
            'bars' => count($candles) - 1 - $position['entryIdx'],
            'forced' => true,
        ];
        $position = null;
    }

    /* ============================================================
     *  METRICS
     * ============================================================ */
    $endBalance = $cash;
    $totalReturnPct = (($endBalance - $startingBalance) / $startingBalance) * 100;

    $wins = 0; $losses = 0; $grossWin = 0.0; $grossLoss = 0.0;
    $largestWin = 0.0; $largestLoss = 0.0;
    foreach ($trades as $t) {
        if ($t['pnl'] > 0) { $wins++; $grossWin += $t['pnl']; if ($t['pnl'] > $largestWin) $largestWin = $t['pnl']; }
        elseif ($t['pnl'] < 0) { $losses++; $grossLoss += abs($t['pnl']); if (abs($t['pnl']) > $largestLoss) $largestLoss = abs($t['pnl']); }
    }
    $tradeCount = count($trades);
    $winRate = $tradeCount > 0 ? ($wins / $tradeCount) * 100 : 0;
    $profitFactor = $grossLoss > 0 ? $grossWin / $grossLoss : ($grossWin > 0 ? 999 : 0);
    $avgWin = $wins > 0 ? $grossWin / $wins : 0;
    $avgLoss = $losses > 0 ? $grossLoss / $losses : 0;

    // Max drawdown
    $peak = $startingBalance;
    $maxDD = 0.0;
    foreach ($equity as $e) {
        if ($e['value'] > $peak) $peak = $e['value'];
        $dd = ($peak - $e['value']) / $peak * 100;
        if ($dd > $maxDD) $maxDD = $dd;
    }

    // Sharpe ratio (daily returns)
    $dailyReturns = [];
    for ($i = 1; $i < count($equity); $i++) {
        $prev = $equity[$i - 1]['value'];
        if ($prev > 0) {
            $dailyReturns[] = ($equity[$i]['value'] - $prev) / $prev;
        }
    }
    $sharpe = 0.0;
    if (count($dailyReturns) > 1) {
        $mean = array_sum($dailyReturns) / count($dailyReturns);
        $variance = 0.0;
        foreach ($dailyReturns as $r) $variance += ($r - $mean) ** 2;
        $sd = sqrt($variance / count($dailyReturns));
        $sharpe = $sd > 0 ? ($mean / $sd) * sqrt(252) : 0.0; // annualized
    }

    // Buy-and-hold comparison
    $firstPrice = $candles[0]['close'];
    $lastPrice = $candles[count($candles) - 1]['close'];
    $buyHoldReturn = (($lastPrice - $firstPrice) / $firstPrice) * 100;

    return [
        'startBalance'   => $startingBalance,
        'endBalance'     => $endBalance,
        'totalReturnPct' => round($totalReturnPct, 2),
        'buyHoldPct'     => round($buyHoldReturn, 2),
        'tradeCount'     => $tradeCount,
        'winCount'       => $wins,
        'lossCount'      => $losses,
        'winRate'        => round($winRate, 2),
        'profitFactor'   => round($profitFactor, 2),
        'avgWin'         => round($avgWin, 2),
        'avgLoss'        => round($avgLoss, 2),
        'largestWin'     => round($largestWin, 2),
        'largestLoss'    => round($largestLoss, 2),
        'maxDrawdownPct' => round($maxDD, 2),
        'sharpe'         => round($sharpe, 3),
        'trades'         => $trades,
        'equity'         => $equity,
    ];
}

/**
 * Built-in preset strategies.
 */
function preset_strategies(): array
{
    return [
        'sma_cross' => [
            'name' => 'SMA 50/200 Golden Cross',
            'description' => 'Buy when SMA50 crosses above SMA200. Sell when it crosses back below.',
            'entry' => [
                'logic' => 'AND',
                'conditions' => [
                    ['indicator' => 'sma50', 'operator' => 'crosses_above', 'value' => 'sma200'],
                ],
            ],
            'exit' => [
                'logic' => 'OR',
                'conditions' => [
                    ['indicator' => 'sma50', 'operator' => 'crosses_below', 'value' => 'sma200'],
                ],
            ],
        ],
        'rsi_reversion' => [
            'name' => 'RSI Oversold Bounce',
            'description' => 'Buy when RSI is below 30. Sell when RSI exceeds 70.',
            'entry' => [
                'logic' => 'AND',
                'conditions' => [
                    ['indicator' => 'rsi', 'operator' => '<', 'value' => 30],
                ],
            ],
            'exit' => [
                'logic' => 'OR',
                'conditions' => [
                    ['indicator' => 'rsi', 'operator' => '>', 'value' => 70],
                ],
            ],
        ],
        'macd_trend' => [
            'name' => 'MACD Trend Follow',
            'description' => 'Buy when MACD histogram turns positive. Sell when it turns negative.',
            'entry' => [
                'logic' => 'AND',
                'conditions' => [
                    ['indicator' => 'macd_hist', 'operator' => 'crosses_above', 'value' => 0],
                ],
            ],
            'exit' => [
                'logic' => 'OR',
                'conditions' => [
                    ['indicator' => 'macd_hist', 'operator' => 'crosses_below', 'value' => 0],
                ],
            ],
        ],
        'bollinger_revert' => [
            'name' => 'Bollinger Mean Reversion',
            'description' => 'Buy when price dips below the lower Bollinger band. Sell at the middle band.',
            'entry' => [
                'logic' => 'AND',
                'conditions' => [
                    ['indicator' => 'bb_pos', 'operator' => '<', 'value' => -0.9],
                ],
            ],
            'exit' => [
                'logic' => 'OR',
                'conditions' => [
                    ['indicator' => 'bb_pos', 'operator' => '>', 'value' => 0],
                ],
            ],
        ],
        'rsi_sma_combo' => [
            'name' => 'RSI + SMA Trend + Risk',
            'description' => 'Buy when RSI is below 40 AND price is above SMA200. Sell on 5% profit, -3% loss, or RSI > 75.',
            'entry' => [
                'logic' => 'AND',
                'conditions' => [
                    ['indicator' => 'rsi', 'operator' => '<', 'value' => 40],
                    ['indicator' => 'close', 'operator' => '>', 'value' => 'sma200'],
                ],
            ],
            'exit' => [
                'logic' => 'OR',
                'conditions' => [
                    ['indicator' => 'pnl_pct', 'operator' => '>', 'value' => 5],
                    ['indicator' => 'pnl_pct', 'operator' => '<', 'value' => -3],
                    ['indicator' => 'rsi', 'operator' => '>', 'value' => 75],
                ],
            ],
        ],
    ];
}