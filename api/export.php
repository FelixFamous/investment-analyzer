<?php
/**
 * GET /api/export.php?type=trades|holdings|snapshots|cash|all
 * Streams a CSV file download for the current user.
 *
 * Optional params:
 *   from=YYYY-MM-DD   (inclusive)
 *   to=YYYY-MM-DD     (inclusive)
 *   symbol=BTC        (filter to a single symbol)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';

$user = current_user();
if (!$user) {
    http_response_code(401);
    exit('Not authenticated');
}

$type   = trim((string)($_GET['type'] ?? 'trades'));
$from   = trim((string)($_GET['from'] ?? ''));
$to     = trim((string)($_GET['to'] ?? ''));
$symbol = strtoupper(trim((string)($_GET['symbol'] ?? '')));

// Valid types
$validTypes = ['trades', 'holdings', 'snapshots', 'cash', 'all'];
if (!in_array($type, $validTypes, true)) $type = 'trades';

// Build filename: e.g. "alphaedge_trades_2026-09-20.csv"
$stamp = date('Y-m-d');
$filename = 'alphaedge_' . $type . '_' . $stamp . '.csv';

// Send headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// UTF-8 BOM so Excel opens it correctly
echo "\xEF\xBB\xBF";

$out = fopen('php://output', 'w');

/* ============================================================
 *  Helper: write a section header row + column headers
 * ============================================================ */
function write_section_header($out, string $title): void
{
    fputcsv($out, []);
    fputcsv($out, ['=== ' . $title . ' ===']);
}

/* ============================================================
 *  TRADES
 * ============================================================ */
function export_trades($out, int $userId, string $from, string $to, string $symbol): void
{
    $sql = 'SELECT
                t.created_at, t.symbol, t.side, t.quantity, t.price, t.total, t.pnl,
                (SELECT u.username FROM users u WHERE u.id = t.user_id) AS username
            FROM trades t
            WHERE t.user_id = ?';
    $params = [$userId];

    if ($from !== '') { $sql .= ' AND DATE(t.created_at) >= ?'; $params[] = $from; }
    if ($to   !== '') { $sql .= ' AND DATE(t.created_at) <= ?'; $params[] = $to; }
    if ($symbol !== '') { $sql .= ' AND t.symbol = ?'; $params[] = $symbol; }

    $sql .= ' ORDER BY t.created_at ASC';

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    write_section_header($out, 'Trade History (' . count($rows) . ' rows)');

    fputcsv($out, [
        'Date (UTC)', 'Symbol', 'Side',
        'Quantity', 'Price (USD)', 'Total (USD)',
        'Realized P&L (USD)',
    ]);

    $totalRealized = 0.0;
    foreach ($rows as $r) {
        $pnl = $r['pnl'] !== null ? (float)$r['pnl'] : null;
        if ($pnl !== null) $totalRealized += $pnl;

        fputcsv($out, [
            $r['created_at'],
            $r['symbol'],
            $r['side'],
            number_format((float)$r['quantity'], 8, '.', ''),
            number_format((float)$r['price'],    8, '.', ''),
            number_format((float)$r['total'],    2, '.', ''),
            $pnl !== null ? number_format($pnl, 2, '.', '') : '',
        ]);
    }

    fputcsv($out, []);
    fputcsv($out, ['', '', '', '', '', 'Total Realized P&L:', number_format($totalRealized, 2, '.', '')]);
}

/* ============================================================
 *  HOLDINGS
 * ============================================================ */
function export_holdings($out, int $userId): void
{
    $stmt = db()->prepare('
        SELECT symbol, quantity, avg_price, take_profit, stop_loss, created_at
        FROM holdings WHERE user_id = ?
        ORDER BY symbol ASC
    ');
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();

    write_section_header($out, 'Current Holdings (' . count($rows) . ' positions)');

    fputcsv($out, [
        'Symbol', 'Quantity', 'Avg Cost (USD)',
        'Current Price (USD)', 'Market Value (USD)',
        'Unrealized P&L (USD)', 'Unrealized P&L (%)',
        'Take Profit', 'Stop Loss', 'Opened',
    ]);

    $totalValue = 0.0;
    $totalCost  = 0.0;

    foreach ($rows as $r) {
        $qty = (float)$r['quantity'];
        $avg = (float)$r['avg_price'];
        $live = get_price($r['symbol']) ?? $avg;
        $val = $qty * $live;
        $cost = $qty * $avg;
        $pnl = $val - $cost;
        $pct = $cost > 0 ? ($pnl / $cost) * 100 : 0;

        $totalValue += $val;
        $totalCost  += $cost;

        fputcsv($out, [
            $r['symbol'],
            number_format($qty, 8, '.', ''),
            number_format($avg, 8, '.', ''),
            number_format($live, 8, '.', ''),
            number_format($val, 2, '.', ''),
            number_format($pnl, 2, '.', ''),
            number_format($pct, 2, '.', ''),
            $r['take_profit'] !== null ? number_format((float)$r['take_profit'], 8, '.', '') : '',
            $r['stop_loss']   !== null ? number_format((float)$r['stop_loss'],   8, '.', '') : '',
            $r['created_at'],
        ]);
    }

    $totalPnl = $totalValue - $totalCost;
    $totalPct = $totalCost > 0 ? ($totalPnl / $totalCost) * 100 : 0;

    fputcsv($out, []);
    fputcsv($out, ['', '', '', '', number_format($totalValue, 2, '.', ''), number_format($totalPnl, 2, '.', ''), number_format($totalPct, 2, '.', '')]);
}

/* ============================================================
 *  CASH TRANSACTIONS
 * ============================================================ */
function export_cash($out, int $userId, string $from, string $to): void
{
    $sql = 'SELECT created_at, type, amount, note
            FROM cash_transactions
            WHERE user_id = ?';
    $params = [$userId];

    if ($from !== '') { $sql .= ' AND DATE(created_at) >= ?'; $params[] = $from; }
    if ($to   !== '') { $sql .= ' AND DATE(created_at) <= ?'; $params[] = $to; }

    $sql .= ' ORDER BY created_at ASC';

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    write_section_header($out, 'Cash Transactions (' . count($rows) . ' rows)');

    fputcsv($out, ['Date (UTC)', 'Type', 'Amount (USD)', 'Note']);

    $deposits = 0.0; $withdrawals = 0.0;
    foreach ($rows as $r) {
        $amt = (float)$r['amount'];
        if ($r['type'] === 'DEPOSIT')  $deposits    += $amt;
        if ($r['type'] === 'WITHDRAW') $withdrawals += $amt;

        fputcsv($out, [
            $r['created_at'],
            $r['type'],
            number_format($amt, 2, '.', ''),
            $r['note'] ?? '',
        ]);
    }

    fputcsv($out, []);
    fputcsv($out, ['', 'Total Deposits:',   number_format($deposits,    2, '.', '')]);
    fputcsv($out, ['', 'Total Withdrawals:', number_format($withdrawals, 2, '.', '')]);
    fputcsv($out, ['', 'Net Cash Flow:',    number_format($deposits - $withdrawals, 2, '.', '')]);
}

/* ============================================================
 *  PORTFOLIO SNAPSHOTS
 * ============================================================ */
function export_snapshots($out, int $userId, string $from, string $to): void
{
    $sql = 'SELECT snapshot_date, cash, holdings_value, equity
            FROM portfolio_snapshots
            WHERE user_id = ?';
    $params = [$userId];

    if ($from !== '') { $sql .= ' AND snapshot_date >= ?'; $params[] = $from; }
    if ($to   !== '') { $sql .= ' AND snapshot_date <= ?'; $params[] = $to; }

    $sql .= ' ORDER BY snapshot_date ASC';

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    write_section_header($out, 'Portfolio Equity Snapshots (' . count($rows) . ' days)');

    fputcsv($out, ['Date', 'Cash (USD)', 'Holdings Value (USD)', 'Total Equity (USD)', 'Daily Change (%)']);

    $prev = null;
    foreach ($rows as $r) {
        $eq = (float)$r['equity'];
        $chg = $prev !== null && $prev > 0 ? (($eq - $prev) / $prev) * 100 : null;

        fputcsv($out, [
            $r['snapshot_date'],
            number_format((float)$r['cash'], 2, '.', ''),
            number_format((float)$r['holdings_value'], 2, '.', ''),
            number_format($eq, 2, '.', ''),
            $chg !== null ? number_format($chg, 2, '.', '') : '',
        ]);

        $prev = $eq;
    }
}

/* ============================================================
 *  Route to the right export
 * ============================================================ */
// Common metadata header for every file
fputcsv($out, ['AlphaEdge · Export Report']);
fputcsv($out, ['Account',     $user['username']]);
fputcsv($out, ['Email',       $user['email']]);
fputcsv($out, ['Timezone',    $user['timezone'] ?? 'UTC']);
fputcsv($out, ['Exported At', gmdate('Y-m-d H:i:s') . ' UTC']);
fputcsv($out, ['Report Type', strtoupper($type)]);
if ($from !== '')   fputcsv($out, ['From Date',   $from]);
if ($to   !== '')   fputcsv($out, ['To Date',     $to]);
if ($symbol !== '') fputcsv($out, ['Symbol',      $symbol]);

if ($type === 'trades' || $type === 'all')     export_trades($out, (int)$user['id'], $from, $to, $symbol);
if ($type === 'holdings' || $type === 'all')   export_holdings($out, (int)$user['id']);
if ($type === 'cash' || $type === 'all')       export_cash($out, (int)$user['id'], $from, $to);
if ($type === 'snapshots' || $type === 'all')  export_snapshots($out, (int)$user['id'], $from, $to);

fclose($out);
exit;