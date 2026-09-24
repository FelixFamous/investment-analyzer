<?php
/**
 * Copy-trade mirror engine.
 *
 * When a "leader" places a trade, call mirror_trade_to_followers().
 * It finds every active follower of that leader, computes their
 * scaled quantity, checks they have funds/holdings, and executes
 * an identical trade on their account — recording the link back
 * to the leader for traceability.
 *
 * Mirroring is best-effort: a follower running out of cash simply
 * skips that copy, it does not block the leader.
 */

/**
 * @param int    $leaderId   The user who placed the original trade
 * @param string $symbol     BTC / AAPL / etc.
 * @param string $side       'BUY' or 'SELL'
 * @param float  $quantity   Original quantity traded by the leader
 * @param float  $price      Execution price
 * @return int   Number of followers who successfully copied
 */
function mirror_trade_to_followers(int $leaderId, string $symbol, string $side, float $quantity, float $price): int
{
    if ($quantity <= 0 || $price <= 0) return 0;

    try {
        // Find all active followers
        $stmt = db()->prepare('
            SELECT id, follower_id, copy_ratio, max_per_trade
            FROM copy_relationships
            WHERE leader_id = ? AND status = "active"
        ');
        $stmt->execute([$leaderId]);
        $followers = $stmt->fetchAll();

        if (!$followers) return 0;

        $copied = 0;

        foreach ($followers as $rel) {
            $followerId = (int)$rel['follower_id'];
            $ratio      = max(0.01, (float)$rel['copy_ratio'] / 100.0);
            $maxPer     = $rel['max_per_trade'] !== null ? (float)$rel['max_per_trade'] : null;

            // Scale the quantity
            $mirrorQty = $quantity * $ratio;

            // Apply the per-trade dollar cap, if set
            if ($maxPer !== null && $maxPer > 0) {
                $maxQty = $maxPer / $price;
                if ($mirrorQty > $maxQty) $mirrorQty = $maxQty;
            }

            if ($mirrorQty <= 0) continue;

            // Attempt the mirror
            if (execute_mirror($followerId, $symbol, $side, $mirrorQty, $price, $leaderId, (int)$rel['id'])) {
                $copied++;
            }
        }

        return $copied;

    } catch (Throwable $e) {
        error_log('mirror_trade_to_followers failed: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Execute a mirrored trade for a single follower.
 * Returns true on success, false if skipped (insufficient funds, etc.).
 */
function execute_mirror(
    int $followerId,
    string $symbol,
    string $side,
    float $quantity,
    float $price,
    int $leaderId,
    int $relationshipId
): bool {
    $pdo = db();

    try {
        $pdo->beginTransaction();

        $total = $price * $quantity;

        if ($side === 'BUY') {
            // Check cash
            $stmt = $pdo->prepare('SELECT cash_balance FROM users WHERE id = ? LIMIT 1 FOR UPDATE');
            $stmt->execute([$followerId]);
            $cash = (float)$stmt->fetchColumn();
            if ($cash < $total - 0.00000001) {
                $pdo->rollBack();
                return false;
            }

            $pdo->prepare('UPDATE users SET cash_balance = cash_balance - ? WHERE id = ?')
                ->execute([$total, $followerId]);

            // Upsert holding
            $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings
                                   WHERE user_id = ? AND symbol = ? LIMIT 1');
            $stmt->execute([$followerId, $symbol]);
            $h = $stmt->fetch();

            if ($h) {
                $newQty = (float)$h['quantity'] + $quantity;
                $newAvg = (((float)$h['quantity'] * (float)$h['avg_price']) + $total) / $newQty;
                $pdo->prepare('UPDATE holdings SET quantity = ?, avg_price = ? WHERE id = ?')
                    ->execute([$newQty, $newAvg, $h['id']]);
            } else {
                $pdo->prepare('INSERT INTO holdings (user_id, symbol, quantity, avg_price)
                               VALUES (?, ?, ?, ?)')
                    ->execute([$followerId, $symbol, $quantity, $price]);
            }

            // Log the trade with copy traceability
            $pdo->prepare('INSERT INTO trades
                           (user_id, symbol, side, quantity, price, total, copy_of_user_id, copy_relationship_id)
                           VALUES (?, ?, "BUY", ?, ?, ?, ?, ?)')
                ->execute([$followerId, $symbol, $quantity, $price, $total, $leaderId, $relationshipId]);

        } else { // SELL
            // Check holdings
            $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings
                                   WHERE user_id = ? AND symbol = ? LIMIT 1 FOR UPDATE');
            $stmt->execute([$followerId, $symbol]);
            $h = $stmt->fetch();

            if (!$h || (float)$h['quantity'] < $quantity - 0.00000001) {
                $pdo->rollBack();
                return false;
            }

            $pnl = ($price - (float)$h['avg_price']) * $quantity;

            $pdo->prepare('UPDATE users SET cash_balance = cash_balance + ? WHERE id = ?')
                ->execute([$total, $followerId]);

            $newQty = (float)$h['quantity'] - $quantity;
            if ($newQty <= 0.00000001) {
                $pdo->prepare('DELETE FROM holdings WHERE id = ?')->execute([$h['id']]);
            } else {
                $pdo->prepare('UPDATE holdings SET quantity = ? WHERE id = ?')
                    ->execute([$newQty, $h['id']]);
            }

            $pdo->prepare('INSERT INTO trades
                           (user_id, symbol, side, quantity, price, total, pnl, copy_of_user_id, copy_relationship_id)
                           VALUES (?, ?, "SELL", ?, ?, ?, ?, ?, ?)')
                ->execute([$followerId, $symbol, $quantity, $price, $total, $pnl, $leaderId, $relationshipId]);
        }

        $pdo->commit();
        return true;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('execute_mirror failed: ' . $e->getMessage());
        return false;
    }
}