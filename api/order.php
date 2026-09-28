<?php
/**
 * POST /api/order.php
 * Body: symbol, side, order_type (MARKET|LIMIT|STOP), quantity, trigger_price, note, csrf
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';
require_once __DIR__ . '/../includes/copy-engine.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

csrf_verify_or_die();

$symbol    = strtoupper(trim((string)($_POST['symbol'] ?? '')));
$side      = strtoupper(trim((string)($_POST['side'] ?? '')));
$orderType = strtoupper(trim((string)($_POST['order_type'] ?? 'MARKET')));
$quantity  = (float)($_POST['quantity'] ?? 0);
$trigger   = (float)($_POST['trigger_price'] ?? 0);
$note      = trim((string)($_POST['note'] ?? ''));
if (strlen($note) > 200) $note = substr($note, 0, 200);

if ($symbol === '')                                json_response(['error' => 'Symbol required'], 400);
if (!in_array($side, ['BUY', 'SELL'], true))       json_response(['error' => 'Invalid side'], 400);
if (!in_array($orderType, ['MARKET', 'LIMIT', 'STOP'], true))
                                                    json_response(['error' => 'Invalid order type'], 400);
if ($quantity <= 0)                                json_response(['error' => 'Quantity must be greater than zero'], 400);

$livePrice = get_price($symbol);
if ($livePrice === null || $livePrice <= 0)        json_response(['error' => 'Unknown or unavailable symbol'], 400);

if ($orderType !== 'MARKET') {
    if ($trigger <= 0) json_response(['error' => 'Trigger price required'], 400);

    if ($orderType === 'LIMIT') {
        if ($side === 'BUY'  && $trigger > $livePrice)  json_response(['error' => 'Limit buy price must be at or below market'], 400);
        if ($side === 'SELL' && $trigger < $livePrice)  json_response(['error' => 'Limit sell price must be at or above market'], 400);
    } else {
        if ($side === 'BUY'  && $trigger < $livePrice)  json_response(['error' => 'Stop buy price must be at or above market'], 400);
        if ($side === 'SELL' && $trigger > $livePrice)  json_response(['error' => 'Stop sell price must be at or below market'], 400);
    }
}

// SELL-side pre-check (only one that needs it — the BUY check is inside tx)
if ($side === 'SELL') {
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
        $price = $livePrice;
        $total = $price * $quantity;

        $pdo->beginTransaction();

        if ($side === 'BUY') {
            // Lock user row before checking balance
            $stmt = $pdo->prepare('SELECT cash_balance FROM users WHERE id = ? FOR UPDATE');
            $stmt->execute([$user['id']]);
            $lockedCash = (float)$stmt->fetchColumn();

            if ($total > $lockedCash + 0.00000001) {
                $pdo->rollBack();
                json_response(['error' => 'Insufficient cash. Need ' . usd($total) . '.'], 400);
            }

            $pdo->prepare('UPDATE users SET cash_balance = cash_balance - ? WHERE id = ?')
                ->execute([$total, $user['id']]);

            $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings WHERE user_id = ? AND symbol = ? LIMIT 1 FOR UPDATE');
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
            $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings WHERE user_id = ? AND symbol = ? LIMIT 1 FOR UPDATE');
            $stmt->execute([$user['id'], $symbol]);
            $h = $stmt->fetch();

            if (!$h || (float)$h['quantity'] < $quantity - 0.00000001) {
                $pdo->rollBack();
                json_response(['error' => 'Not enough shares to sell'], 400);
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

        $copied = mirror_trade_to_followers((int)$user['id'], $symbol, $side, $quantity, $price);

        json_response([
            'success' => true,
            'filled'  => true,
            'message' => 'Market ' . strtolower($side) . ' filled · ' . number_format($quantity, 6) . ' ' . $symbol . ' @ ' . price_fmt($price)
                         . ($copied > 0 ? ' · mirrored to ' . $copied . ' follower' . ($copied === 1 ? '' : 's') : ''),
        ]);

    } else {
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