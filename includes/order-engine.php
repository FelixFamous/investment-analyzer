<?php
/**
 * Order execution engine.
 * Runs on each authenticated page load.
 *   1. Fills pending LIMIT / STOP orders whose trigger is met.
 *   2. Triggers Take-Profit / Stop-Loss on any holding that hits its levels.
 *
 * Throttled to once per 10 seconds per session.
 */

require_once __DIR__ . '/market.php';

function run_order_engine(int $userId): void
{
    $now = time();
    if (!empty($_SESSION['_order_engine_last']) && $now - $_SESSION['_order_engine_last'] < 10) {
        return;
    }
    $_SESSION['_order_engine_last'] = $now;

    try {
        /* ---------- 1. Pending LIMIT / STOP orders ---------- */
        $stmt = db()->prepare('SELECT * FROM pending_orders
                               WHERE user_id = ? AND status = "open"
                               ORDER BY created_at ASC LIMIT 50');
        $stmt->execute([$userId]);
        foreach ($stmt->fetchAll() as $o) {
            $price = get_price($o['symbol']);
            if ($price === null || $price <= 0) continue;

            $trigger = (float)$o['trigger_price'];
            $hit = false;

            if ($o['order_type'] === 'LIMIT') {
                if ($o['side'] === 'BUY'  && $price <= $trigger) $hit = true;
                if ($o['side'] === 'SELL' && $price >= $trigger) $hit = true;
            } else { // STOP
                if ($o['side'] === 'BUY'  && $price >= $trigger) $hit = true;
                if ($o['side'] === 'SELL' && $price <= $trigger) $hit = true;
            }

            if ($hit) fill_order($o, $price);
        }

        /* ---------- 2. Take-Profit / Stop-Loss on holdings ---------- */
        $stmt = db()->prepare('
            SELECT id, symbol, quantity, avg_price, take_profit, stop_loss,
                   tp_note, sl_note
            FROM holdings
            WHERE user_id = ?
              AND (take_profit IS NOT NULL OR stop_loss IS NOT NULL)
        ');
        $stmt->execute([$userId]);

        foreach ($stmt->fetchAll() as $h) {
            $price = get_price($h['symbol']);
            if ($price === null || $price <= 0) continue;

            $tp = $h['take_profit'] !== null ? (float)$h['take_profit'] : null;
            $sl = $h['stop_loss']   !== null ? (float)$h['stop_loss']   : null;

            $hitTp = $tp !== null && $price >= $tp;
            $hitSl = $sl !== null && $price <= $sl;

            if ($hitTp || $hitSl) {
                $reason = $hitTp ? 'TP' : 'SL';
                auto_sell_position($userId, $h, $price, $reason);
            }
        }

    } catch (Throwable $e) {
        error_log('order_engine failed: ' . $e->getMessage());
    }
}

/**
 * Fill a queued pending order (LIMIT or STOP).
 */
function fill_order(array $order, float $price): bool
{
    $pdo = db();
    $userId   = (int)$order['user_id'];
    $symbol   = $order['symbol'];
    $side     = $order['side'];
    $quantity = (float)$order['quantity'];
    $total    = $price * $quantity;

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('SELECT status FROM pending_orders WHERE id = ? FOR UPDATE');
        $stmt->execute([$order['id']]);
        if ($stmt->fetchColumn() !== 'open') { $pdo->rollBack(); return false; }

        if ($side === 'BUY') {
            $stmt = $pdo->prepare('SELECT cash_balance FROM users WHERE id = ? LIMIT 1');
            $stmt->execute([$userId]);
            $cash = (float)$stmt->fetchColumn();
            if ($cash < $total - 0.00000001) {
                $pdo->prepare('UPDATE pending_orders SET status = "cancelled" WHERE id = ?')
                    ->execute([$order['id']]);
                $pdo->commit();
                return false;
            }

            $pdo->prepare('UPDATE users SET cash_balance = cash_balance - ? WHERE id = ?')
                ->execute([$total, $userId]);

            $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings
                                   WHERE user_id = ? AND symbol = ? LIMIT 1');
            $stmt->execute([$userId, $symbol]);
            $h = $stmt->fetch();

            if ($h) {
                $newQty = (float)$h['quantity'] + $quantity;
                $newAvg = (((float)$h['quantity'] * (float)$h['avg_price']) + $total) / $newQty;
                $pdo->prepare('UPDATE holdings SET quantity = ?, avg_price = ? WHERE id = ?')
                    ->execute([$newQty, $newAvg, $h['id']]);
            } else {
                $pdo->prepare('INSERT INTO holdings (user_id, symbol, quantity, avg_price)
                               VALUES (?, ?, ?, ?)')
                    ->execute([$userId, $symbol, $quantity, $price]);
            }

            $pdo->prepare('INSERT INTO trades (user_id, symbol, side, quantity, price, total)
                           VALUES (?, ?, "BUY", ?, ?, ?)')
                ->execute([$userId, $symbol, $quantity, $price, $total]);

        } else {
            $ok = execute_sell_tx($pdo, $userId, $symbol, $quantity, $price);
            if (!$ok) {
                $pdo->prepare('UPDATE pending_orders SET status = "cancelled" WHERE id = ?')
                    ->execute([$order['id']]);
                $pdo->commit();
                return false;
            }
        }

        $pdo->prepare('UPDATE pending_orders SET status = "filled",
                       filled_at = UTC_TIMESTAMP(), filled_price = ? WHERE id = ?')
            ->execute([$price, $order['id']]);

        $pdo->commit();
        return true;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('fill_order failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Auto-sell an entire holding because TP or SL was hit.
 */
function auto_sell_position(int $userId, array $holding, float $price, string $reason): bool
{
    $pdo = db();
    $symbol   = $holding['symbol'];
    $quantity = (float)$holding['quantity'];

    try {
        $pdo->beginTransaction();

        $ok = execute_sell_tx($pdo, $userId, $symbol, $quantity, $price);
        if (!$ok) { $pdo->rollBack(); return false; }

        // Clear TP/SL on the holding (it will be deleted if qty=0, or reset if partial)
        $pdo->prepare('UPDATE holdings
                       SET take_profit = NULL, stop_loss = NULL,
                           tp_note = NULL, sl_note = NULL
                       WHERE id = ?')->execute([$holding['id']]);

        $pdo->commit();

        error_log(sprintf(
            'order_engine: %s hit on %s @ %.8f (qty %.6f)',
            $reason, $symbol, $price, $quantity
        ));

        return true;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('auto_sell_position failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Execute a SELL inside an existing transaction.
 * Returns true on success, false if not enough shares.
 */
function execute_sell_tx(PDO $pdo, int $userId, string $symbol, float $quantity, float $price): bool
{
    $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings
                           WHERE user_id = ? AND symbol = ? LIMIT 1');
    $stmt->execute([$userId, $symbol]);
    $h = $stmt->fetch();

    if (!$h || (float)$h['quantity'] < $quantity - 0.00000001) return false;

    $total = $price * $quantity;
    $pnl = ($price - (float)$h['avg_price']) * $quantity;

    $pdo->prepare('UPDATE users SET cash_balance = cash_balance + ? WHERE id = ?')
        ->execute([$total, $userId]);

    $newQty = (float)$h['quantity'] - $quantity;
    if ($newQty <= 0.00000001) {
        $pdo->prepare('DELETE FROM holdings WHERE id = ?')->execute([$h['id']]);
    } else {
        $pdo->prepare('UPDATE holdings SET quantity = ? WHERE id = ?')
            ->execute([$newQty, $h['id']]);
    }

    $pdo->prepare('INSERT INTO trades (user_id, symbol, side, quantity, price, total, pnl)
                   VALUES (?, ?, "SELL", ?, ?, ?, ?)')
        ->execute([$userId, $symbol, $quantity, $price, $total, $pnl]);

    return true;
}