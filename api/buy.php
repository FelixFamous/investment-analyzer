<?php
/**
 * POST /api/buy.php
 * Body: symbol, quantity, csrf
 * Executes a market BUY and mirrors to active copy followers.
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
if ($price === null) {
    json_response(['error' => 'Unknown or unavailable symbol'], 404);
}

$cost = $price * $quantity;

try {
    $pdo = db();
    $pdo->beginTransaction();

    // Lock user row before checking balance (prevents TOCTOU)
    $stmt = $pdo->prepare('SELECT cash_balance FROM users WHERE id = ? FOR UPDATE');
    $stmt->execute([$user['id']]);
    $cash = (float)$stmt->fetchColumn();

    if ($cost > $cash + 0.00000001) {
        $pdo->rollBack();
        json_response(['error' => 'Insufficient cash. Need ' . usd($cost) . '.'], 400);
    }

    $pdo->prepare('UPDATE users SET cash_balance = cash_balance - ? WHERE id = ?')
        ->execute([$cost, $user['id']]);

    $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings
                           WHERE user_id = ? AND symbol = ? LIMIT 1 FOR UPDATE');
    $stmt->execute([$user['id'], $symbol]);
    $holding = $stmt->fetch();

    if ($holding) {
        $newQty = (float)$holding['quantity'] + $quantity;
        $newAvg = (((float)$holding['quantity'] * (float)$holding['avg_price']) + $cost) / $newQty;
        $pdo->prepare('UPDATE holdings SET quantity = ?, avg_price = ? WHERE id = ?')
            ->execute([$newQty, $newAvg, $holding['id']]);
    } else {
        $pdo->prepare('INSERT INTO holdings (user_id, symbol, quantity, avg_price)
                       VALUES (?, ?, ?, ?)')
            ->execute([$user['id'], $symbol, $quantity, $price]);
    }

    $pdo->prepare('INSERT INTO trades (user_id, symbol, side, quantity, price, total)
                   VALUES (?, ?, ?, ?, ?, ?)')
        ->execute([$user['id'], $symbol, 'BUY', $quantity, $price, $cost]);

    $pdo->commit();

    $copied = mirror_trade_to_followers((int)$user['id'], $symbol, 'BUY', $quantity, $price);

    json_response([
        'success'  => true,
        'symbol'   => $symbol,
        'quantity' => $quantity,
        'price'    => $price,
        'total'    => $cost,
        'message'  => "Bought {$quantity} {$symbol} @ " . price_fmt($price)
                      . ($copied > 0 ? ' · mirrored to ' . $copied . ' follower' . ($copied === 1 ? '' : 's') : ''),
    ]);
} catch (Throwable $e) {
    if (db()->inTransaction()) db()->rollBack();
    json_response(['error' => DEBUG_MODE ? $e->getMessage() : 'Trade failed'], 500);
}