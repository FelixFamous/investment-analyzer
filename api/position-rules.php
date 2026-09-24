<?php
/**
 * POST /api/position-rules.php
 * Body: symbol, take_profit, stop_loss, tp_note, sl_note, csrf
 * Sets or clears TP/SL on the user's holding of the given symbol.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

$symbol = strtoupper(trim((string)($_POST['symbol'] ?? '')));
if ($symbol === '') json_response(['error' => 'Symbol required'], 400);

$tpRaw = trim((string)($_POST['take_profit'] ?? ''));
$slRaw = trim((string)($_POST['stop_loss']   ?? ''));
$tpNote = trim((string)($_POST['tp_note'] ?? ''));
$slNote = trim((string)($_POST['sl_note'] ?? ''));

$tp = $tpRaw === '' ? null : (float)$tpRaw;
$sl = $slRaw === '' ? null : (float)$slRaw;

if ($tp !== null && $tp <= 0) json_response(['error' => 'Take-profit must be positive'], 400);
if ($sl !== null && $sl <= 0) json_response(['error' => 'Stop-loss must be positive'], 400);

// Sanity: TP should be above current, SL should be below current
$live = get_price($symbol);
if ($live !== null) {
    if ($tp !== null && $tp <= $live) json_response(['error' => 'Take-profit must be above current price (' . price_fmt($live) . ')'], 400);
    if ($sl !== null && $sl >= $live) json_response(['error' => 'Stop-loss must be below current price (' . price_fmt($live) . ')'], 400);
}

// Must hold the symbol
$stmt = db()->prepare('SELECT id FROM holdings WHERE user_id = ? AND symbol = ? LIMIT 1');
$stmt->execute([$user['id'], $symbol]);
$h = $stmt->fetch();
if (!$h) json_response(['error' => 'You don\'t hold ' . $symbol], 400);

db()->prepare('UPDATE holdings
               SET take_profit = ?, stop_loss = ?, tp_note = ?, sl_note = ?
               WHERE id = ?')
    ->execute([
        $tp, $sl,
        $tpNote !== '' ? substr($tpNote, 0, 120) : null,
        $slNote !== '' ? substr($slNote, 0, 120) : null,
        $h['id'],
    ]);

$parts = [];
if ($tp !== null) $parts[] = 'TP ' . price_fmt($tp);
if ($sl !== null) $parts[] = 'SL ' . price_fmt($sl);

$msg = $parts
    ? 'Set ' . implode(' · ', $parts) . ' on ' . $symbol
    : 'Cleared TP/SL on ' . $symbol;

json_response(['success' => true, 'message' => $msg]);