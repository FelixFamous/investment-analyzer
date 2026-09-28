<?php
/**
 * DCA engine — runs due recurring buys on each page load.
 * Throttled to once per 30 seconds per session.
 */

require_once __DIR__ . '/market.php';
require_once __DIR__ . '/copy-engine.php';

function run_dca_engine(int $userId): void
{
    $now = time();
    if (!empty($_SESSION['_dca_last']) && $now - $_SESSION['_dca_last'] < 30) return;
    $_SESSION['_dca_last'] = $now;

    try {
        $stmt = db()->prepare('
            SELECT * FROM recurring_buys
            WHERE user_id = ? AND status = "active" AND next_run_at <= UTC_TIMESTAMP()
            ORDER BY next_run_at ASC
            LIMIT 10
        ');
        $stmt->execute([$userId]);
        $due = $stmt->fetchAll();

        foreach ($due as $rb) {
            execute_dca_buy($rb);
        }
    } catch (Throwable $e) {
        error_log('run_dca_engine failed: ' . $e->getMessage());
    }
}

function execute_dca_buy(array $rb): bool
{
    $pdo = db();
    $userId = (int)$rb['user_id'];
    $symbol = $rb['symbol'];
    $amountUsd = (float)$rb['amount_usd'];

    $price = get_price($symbol);
    if ($price === null || $price <= 0) {
        schedule_next($rb, true);
        return false;
    }

    $quantity = $amountUsd / $price;

    try {
        $pdo->beginTransaction();

        // Lock user row before checking balance
        $stmt = $pdo->prepare('SELECT cash_balance FROM users WHERE id = ? FOR UPDATE');
        $stmt->execute([$userId]);
        $cash = (float)$stmt->fetchColumn();

        if ($cash < $amountUsd) {
            $pdo->rollBack();
            schedule_next($rb, true);
            return false;
        }

        $pdo->prepare('UPDATE users SET cash_balance = cash_balance - ? WHERE id = ?')
            ->execute([$amountUsd, $userId]);

        $stmt = $pdo->prepare('SELECT id, quantity, avg_price FROM holdings
                               WHERE user_id = ? AND symbol = ? LIMIT 1 FOR UPDATE');
        $stmt->execute([$userId, $symbol]);
        $h = $stmt->fetch();

        if ($h) {
            $newQty = (float)$h['quantity'] + $quantity;
            $newAvg = (((float)$h['quantity'] * (float)$h['avg_price']) + $amountUsd) / $newQty;
            $pdo->prepare('UPDATE holdings SET quantity = ?, avg_price = ? WHERE id = ?')
                ->execute([$newQty, $newAvg, $h['id']]);
        } else {
            $pdo->prepare('INSERT INTO holdings (user_id, symbol, quantity, avg_price) VALUES (?, ?, ?, ?)')
                ->execute([$userId, $symbol, $quantity, $price]);
        }

        $pdo->prepare('INSERT INTO trades (user_id, symbol, side, quantity, price, total)
                       VALUES (?, ?, "BUY", ?, ?, ?)')
            ->execute([$userId, $symbol, $quantity, $price, $amountUsd]);

        $pdo->prepare('UPDATE recurring_buys
                       SET last_run_at = UTC_TIMESTAMP(),
                           total_invested = total_invested + ?,
                           times_run = times_run + 1
                       WHERE id = ?')
            ->execute([$amountUsd, $rb['id']]);

        $pdo->commit();

        mirror_trade_to_followers($userId, $symbol, 'BUY', $quantity, $price);

        schedule_next($rb, false);

        error_log(sprintf('DCA: ran rule #%d · bought %.8f %s for $%.2f', $rb['id'], $quantity, $symbol, $amountUsd));
        return true;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('DCA execution failed: ' . $e->getMessage());
        schedule_next($rb, true);
        return false;
    }
}

function schedule_next(array $rb, bool $afterFailure): void
{
    $tz = new DateTimeZone('UTC');
    $now = new DateTimeImmutable('now', $tz);

    $freq = $rb['frequency'];

    if ($freq === 'daily') {
        $next = $now->modify('+1 day')->setTime((int)$rb['hour_utc'], 0, 0);
    } elseif ($freq === 'weekly') {
        $dow = (int)($rb['day_of_week'] ?? 1);
        $daysAhead = ($dow - (int)$now->format('w') + 7) % 7;
        if ($daysAhead === 0) $daysAhead = 7;
        $next = $now->modify("+{$daysAhead} days")->setTime((int)$rb['hour_utc'], 0, 0);
    } else {
        $dom = (int)($rb['day_of_month'] ?? 1);
        $day = min($dom, 28);
        $candidate = $now->setDate((int)$now->format('Y'), (int)$now->format('n'), $day)
                         ->setTime((int)$rb['hour_utc'], 0, 0);
        if ($candidate <= $now) {
            $nextMonth = $now->modify('first day of next month');
            $next = $nextMonth->setDate((int)$nextMonth->format('Y'), (int)$nextMonth->format('n'), $day)
                              ->setTime((int)$rb['hour_utc'], 0, 0);
        } else {
            $next = $candidate;
        }
    }

    if ($next <= $now) $next = $now->modify('+1 hour');

    db()->prepare('UPDATE recurring_buys SET next_run_at = ? WHERE id = ?')
        ->execute([$next->format('Y-m-d H:i:s'), $rb['id']]);
}