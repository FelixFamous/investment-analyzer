<?php
/**
 * POST /api/withdraw.php
 * Removes virtual demo funds from the current user's balance.
 * No real money is involved.
 */
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$sent = $_POST['csrf'] ?? '';
if (!hash_equals($_SESSION['csrf'] ?? '', $sent)) {
    json_response(['error' => 'CSRF token mismatch'], 419);
}

$amount = (float)($_POST['amount'] ?? 0);

if ($amount <= 0) {
    json_response(['error' => 'Enter a positive amount.'], 400);
}

// Re-fetch user to get the fresh balance (session cache may be stale)
$stmt = db()->prepare('SELECT cash_balance FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$fresh = $stmt->fetch();
$balance = (float)$fresh['cash_balance'];

if ($amount > $balance + 0.00000001) {
    json_response(['error' => 'Insufficient cash. Available: ' . usd($balance)], 400);
}

try {
    $pdo = db();
    $pdo->beginTransaction();

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