<?php
/**
 * POST /api/withdraw.php
 * Removes virtual demo funds. No real money is involved.
 */
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

csrf_verify_or_die();

$amount = (float)($_POST['amount'] ?? 0);

if ($amount <= 0) {
    json_response(['error' => 'Enter a positive amount.'], 400);
}

try {
    $pdo = db();
    $pdo->beginTransaction();

    // Lock user row before checking balance (prevents TOCTOU)
    $stmt = $pdo->prepare('SELECT cash_balance FROM users WHERE id = ? FOR UPDATE');
    $stmt->execute([$user['id']]);
    $balance = (float)$stmt->fetchColumn();

    if ($amount > $balance + 0.00000001) {
        $pdo->rollBack();
        json_response(['error' => 'Insufficient cash. Available: ' . usd($balance)], 400);
    }

    $pdo->prepare('UPDATE users SET cash_balance = cash_balance - ? WHERE id = ?')
        ->execute([$amount, $user['id']]);

    $pdo->prepare('INSERT INTO cash_transactions (user_id, type, amount, note)
                   VALUES (?, "WITHDRAW", ?, ?)')
        ->execute([$user['id'], $amount, 'Demo withdrawal']);

    $pdo->commit();

    json_response([
        'success' => true,
        'amount'  => $amount,
        'message' => 'Removed ' . usd($amount) . ' virtual funds',
    ]);
} catch (Throwable $e) {
    if (db()->inTransaction()) db()->rollBack();
    json_response(['error' => DEBUG_MODE ? $e->getMessage() : 'Withdrawal failed'], 500);
}