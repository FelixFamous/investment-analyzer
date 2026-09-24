<?php
/**
 * POST /api/order.php
 * Body: symbol, side, order_type (MARKET|LIMIT|STOP), quantity, trigger_price, note, csrf
 *
 * MARKET orders execute immediately at the live price.
 * LIMIT and STOP orders queue in `pending_orders` and are evaluated by the order engine.
 * Market orders automatically mirror to the trader's copy followers.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';
require_once __DIR__ . '/../includes/copy-engine.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    json_response(['error' => 'CSRF mismatch'], 419);
}

/* ---------- Input ---------- */
$symbol    = strtoupper(trim((string)($_POST['symbol'] ?? '')));
$side      = strtoupper(trim((string)($_POST['side'] ?? '')));
$orderType = strtoupper(trim((string)($_POST['order_type'] ?? 'MARKET')));
$quantity  = (float)($_POST['quantity'] ?? 0);
$trigger   = (float)($_POST['trigger_price'] ?? 0);
$note      = trim((string)($_POST['note'] ?? ''));
if (strlen($note) > 200) $note = substr($note, 0, 200);

/* ---------- Validate ---------- */
if ($symbol === '')                                json_response(['error' => 'Symbol required'], 400);
if (!in_array($side, ['BUY', 'SELL'], true))       json_response(['error' => 'Invalid side'], 400);
if (!in_array($orderType, ['MARKET', 'LIMIT', 'STOP'], true))
                                                    json_response(['error' => 'Invalid order type'], 400);
if ($quantity <= 0)                                json_response(['error' => 'Quantity must be greater than zero'], 400);

$livePrice = get_price($symbol);
if ($livePrice === null || $livePrice <= 0)        json_response(['error' => 'Unknown or unavailable symbol'], 400);

// Trigger sanity for limit/stop orders
if ($orderType !== 'MARKET') {
    if ($trigger <= 0) json_response(['error' => 'Trigger price required'], 400);

    if ($orderType === 'LIMIT') {
        if ($side === 'BUY'  && $trigger > $livePrice)  json_response(['error' => 'Limit buy price must be at or below market'], 400);
        if ($side === 'SELL' && $trigger < $livePrice)  json_response(['error' => 'Limit sell price must be at or above market'], 400);
    } else { // STOP
        if ($side === 'BUY'  && $trigger < $livePrice)  json_response(['error' => 'Stop buy price must be at or above market'], 400);
        if ($side === 'SELL' && $trigger > $livePrice)  json_response(['error' => 'Stop sell price must be at or below market'], 400);
    }
}

/* ---------- Pre-checks for cash / holdings ---------- */
if ($side === 'BUY') {
    $priceForReserve = $orderType === 'MARKET' ? $livePrice : $trigger;
    $cost = $priceForReserve * $quantity;
    if ($cost > (float)$user['cash_balance'] + 0.00000001) {
        json_response(['error' => 'Insufficient cash. Need ' . usd($cost) . '.'], 400);
    }
} else {
    $stmt = db()->prepare('SELECT quantity FROM holdings WHERE user_id = ? AND symbol = ? LIMIT 1');
    $stmt->execute([$user['id'], $symbol]);
    $held = (float)$stmt->fetchColumn();

    $stmt = db()->prepare('SELECT COALESCE(SUM(quantity),0) FROM pending_orders
                           WHERE user_id = ? AND symbol = ? AND side = "SELL" AND status = "open"');
    $stmt->execute([$user['id'], $symbol]);
    $reserved = (float)$stmt->fetchColumn();

    if ($quantity > ($held - $reserved) + 0.00000001) {
        json_response(['error' => 'You don\'t hold enough ' . $symbol . ' (reserved: ' . number_format($reserved, 4) . ').'], 400);
    }
}

$pdo = db();

try {
    if ($orderType === 'MARKET') {
        /* ============================================
         *  MARKET ORDER — execute immediately
         * ============================================ */
        $price = $livePrice;
        $total = $price * $quantity;

        $pdo->beginTransaction();

        if ($side === 'BUY') {
            $pdo->prepare('UPDATE users SET cash_balance = cash_balance - ? WHERE id = ?')
                ->execute([$total, $user['id']]);

            $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings WHERE user_id = ? AND symbol = ? LIMIT 1');
            $stmt->execute([$user['id'], $symbol]);
            $h = $stmt->fetch();

            if ($h) {
                $newQty = (float)$h['quantity'] + $quantity;
                $newAvg = (((float)$h['quantity'] * (float)$h['avg_price']) + $total) / $newQty;
                $pdo->prepare('UPDATE holdings SET quantity = ?, avg_price = ? WHERE id = ?')
                    ->execute([$newQty, $newAvg, $h['id']]);
            } else {
                $pdo->prepare('INSERT INTO holdings (user_id, symbol, quantity, avg_price) VALUES (?, ?, ?, ?)')
                    ->execute([$user['id'], $symbol, $quantity, $price]);
            }

            $pdo->prepare('INSERT INTO trades (user_id, symbol, side, quantity, price, total)
                           VALUES (?, ?, "BUY", ?, ?, ?)')
                ->execute([$user['id'], $symbol, $quantity, $price, $total]);

        } else {
            $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings WHERE user_id = ? AND symbol = ? LIMIT 1');
            $stmt->execute([$user['id'], $symbol]);
            $h = $stmt->fetch();

            if (!$h || (float)$h['quantity'] < $quantity - 0.00000001) {
                throw new Exception('Not enough shares to sell');
            }

            $pnl = ($price - (float)$h['avg_price']) * $quantity;

            $pdo->prepare('UPDATE users SET cash_balance = cash_balance + ? WHERE id = ?')
                ->execute([$total, $user['id']]);

            $newQty = (float)$h['quantity'] - $quantity;
            if ($newQty <= 0.00000001) {
                $pdo->prepare('DELETE FROM holdings WHERE id = ?')->execute([$h['id']]);
            } else {
                $pdo->prepare('UPDATE holdings SET quantity = ? WHERE id = ?')
                    ->execute([$newQty, $h['id']]);
            }

            $pdo->prepare('INSERT INTO trades (user_id, symbol, side, quantity, price, total, pnl)
                           VALUES (?, ?, "SELL", ?, ?, ?, ?)')
                ->execute([$user['id'], $symbol, $quantity, $price, $total, $pnl]);
        }

        $pdo->commit();

        // Copy-trade mirror — notify all active followers
        $copied = mirror_trade_to_followers((int)$user['id'], $symbol, $side, $quantity, $price);

        json_response([
            'success' => true,
            'filled'  => true,
            'message' => 'Market ' . strtolower($side) . ' filled · ' . number_format($quantity, 6) . ' ' . $symbol . ' @ ' . price_fmt($price)
                         . ($copied > 0 ? ' · mirrored to ' . $copied . ' follower' . ($copied === 1 ? '' : 's') : ''),
        ]);

    } else {
        /* ============================================
         *  LIMIT / STOP — queue for later
         * ============================================ */
        $pdo->prepare('INSERT INTO pending_orders
                       (user_id, symbol, side, order_type, quantity, trigger_price, note)
                       VALUES (?, ?, ?, ?, ?, ?, ?)')
            ->execute([$user['id'], $symbol, $side, $orderType, $quantity, $trigger, $note ?: null]);

        json_response([
            'success' => true,
            'queued'  => true,
            'message' => $orderType . ' ' . strtolower($side) . ' queued · ' . number_format($quantity, 6) . ' ' . $symbol . ' @ ' . price_fmt($trigger),
        ]);
    }

} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    json_response(['error' => DEBUG_MODE ? $e->getMessage() : 'Order failed'], 500);
}