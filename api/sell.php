<?php
/**
 * POST /api/sell.php
 * Body: symbol, quantity, csrf
 * Executes a market SELL and mirrors to active copy followers.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';
require_once __DIR__ . '/../includes/copy-engine.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) {
    json_response(['error' => 'Not authenticated'], 401);
}

csrf_verify_or_die();

$symbol   = strtoupper(post('symbol'));
$quantity = (float)($_POST['quantity'] ?? 0);

if ($symbol === '' || $quantity <= 0) {
    json_response(['error' => 'Invalid symbol or quantity'], 400);
}

$price = get_price($symbol);
if ($price === null) json_response(['error' => 'Unknown symbol'], 404);

$pdo = db();

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings
                           WHERE user_id = ? AND symbol = ? LIMIT 1 FOR UPDATE');
    $stmt->execute([$user['id'], $symbol]);
    $holding = $stmt->fetch();

    if (!$holding || (float)$holding['quantity'] < $quantity - 0.00000001) {
        $pdo->rollBack();
        json_response(['error' => 'Not enough shares to sell'], 400);
    }

    $proceeds = $price * $quantity;
    $pnl      = ($price - (float)$holding['avg_price']) * $quantity;

    $pdo->prepare('UPDATE users SET cash_balance = cash_balance + ? WHERE id = ?')
        ->execute([$proceeds, $user['id']]);

    $newQty = (float)$holding['quantity'] - $quantity;
    if ($newQty <= 0.00000001) {
        $pdo->prepare('DELETE FROM holdings WHERE id = ?')->execute([$holding['id']]);
    } else {
        $pdo->prepare('UPDATE holdings SET quantity = ? WHERE id = ?')
            ->execute([$newQty, $holding['id']]);
    }

    $pdo->prepare('INSERT INTO trades (user_id, symbol, side, quantity, price, total, pnl)
                   VALUES (?, ?, "SELL", ?, ?, ?, ?)')
        ->execute([$user['id'], $symbol, $quantity, $price, $proceeds, $pnl]);

    $pdo->commit();

    $copied = mirror_trade_to_followers((int)$user['id'], $symbol, 'SELL', $quantity, $price);

    json_response([
        'success'  => true,
        'symbol'   => $symbol,
        'quantity' => $quantity,
        'price'    => $price,
        'total'    => $proceeds,
        'pnl'      => $pnl,
        'message'  => "Sold {$quantity} {$symbol} · P&L " . ($pnl >= 0 ? '+' : '-') . usd(abs($pnl))
                      . ($copied > 0 ? ' · mirrored to ' . $copied . ' follower' . ($copied === 1 ? '' : 's') : ''),
    ]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    json_response(['error' => DEBUG_MODE ? $e->getMessage() : 'Trade failed'], 500);
}