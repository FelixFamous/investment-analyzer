<?php
/**
 * POST /api/backtest.php
 * Body:
 *   symbol (BTC/AAPL/etc)
 *   range  (1d/7d/30d/90d/1y/2y)
 *   strategy_id  (numeric or preset key)
 *   starting_balance (optional, default 100000)
 *
 * Loads candles from cache (or /api/history.php internally),
 * runs the strategy, saves the run, returns full results.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/strategy-engine.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

$symbol = strtoupper(trim((string)($_POST['symbol'] ?? 'BTC')));
$range  = strtolower(trim((string)($_POST['range'] ?? '1y')));
$strategyId = $_POST['strategy_id'] ?? '';
$startBal = (float)($_POST['starting_balance'] ?? 100000.0);
if ($startBal < 100) $startBal = 100.0;
if ($startBal > 10000000) $startBal = 10000000.0;

$validRanges = ['1d','7d','30d','90d','1y','2y','5y','max'];
if (!in_array($range, $validRanges, true)) $range = '1y';

/* ---------- Resolve strategy ---------- */
$strategyData = null;
$strategyDbId = null;

if (is_numeric($strategyId)) {
    $stmt = db()->prepare('SELECT * FROM strategies WHERE id = ? AND user_id = ? LIMIT 1');
    $stmt->execute([(int)$strategyId, $user['id']]);
    $s = $stmt->fetch();
    if (!$s) json_response(['error' => 'Strategy not found'], 404);
    $strategyData = [
        'entry' => json_decode($s['entry_rules'], true),
        'exit'  => json_decode($s['exit_rules'], true),
    ];
    $strategyDbId = (int)$s['id'];
} else {
    $presets = preset_strategies();
    if (!isset($presets[$strategyId])) json_response(['error' => 'Unknown strategy'], 404);
    $strategyData = ['entry' => $presets[$strategyId]['entry'], 'exit' => $presets[$strategyId]['exit']];
}

/* ---------- Load candles ---------- */
$candles = load_candles_from_cache($symbol, $range);
if (!$candles || count($candles) < 30) {
    json_response(['error' => 'Not enough historical data. Try a different symbol or range.'], 400);
}

/* ---------- Run backtest ---------- */
$result = run_backtest($candles, $strategyData, $startBal);

/* ---------- Persist run ---------- */
try {
    db()->prepare('
        INSERT INTO backtest_runs
        (user_id, strategy_id, symbol, range_label, starting_balance, ending_balance,
         total_return_pct, trade_count, win_count, loss_count, max_drawdown_pct, sharpe, results_json)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ')->execute([
        $user['id'], $strategyDbId, $symbol, $range,
        $startBal, $result['endBalance'],
        $result['totalReturnPct'], $result['tradeCount'],
        $result['winCount'], $result['lossCount'],
        $result['maxDrawdownPct'], $result['sharpe'],
        json_encode([
            'trades' => array_slice($result['trades'], 0, 50),
            'equity' => $result['equity'],
        ]),
    ]);
} catch (Throwable $e) {
    error_log('backtest persist failed: ' . $e->getMessage());
}

json_response([
    'success' => true,
    'symbol'  => $symbol,
    'range'   => $range,
    'candle_count' => count($candles),
    'result'  => $result,
]);

/* ============================================================
 *  Load candles — tries cache, falls back to fetching
 * ============================================================ */
function load_candles_from_cache(string $symbol, string $range): array
{
    $safe = preg_replace('/[^A-Z0-9]/', '', strtoupper($symbol));
    $cacheDir = __DIR__ . '/../cache';

    $candidates = [
        $cacheDir . '/hist_' . $safe . '_' . $range . '.json',
        $cacheDir . '/hist_' . $safe . '_1y.json',
    ];

    foreach ($candidates as $file) {
        if (!is_file($file)) continue;
        $json = @file_get_contents($file);
        if (!$json) continue;
        $data = json_decode($json, true);
        if (!isset($data['candles']) || !$data['candles']) continue;

        $candles = [];
        foreach ($data['candles'] as $c) {
            $candles[] = [
                'time'   => (int)($c['t'] / 1000),
                'open'   => (float)$c['o'],
                'high'   => (float)$c['h'],
                'low'    => (float)$c['l'],
                'close'  => (float)$c['c'],
                'volume' => (float)($c['v'] ?? 0),
            ];
        }
        return $candles;
    }
    return [];
}