<?php
/**
 * POST /api/deposit.php
 * Adds virtual demo funds. Requires KYC approval.
 */
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

// KYC gate
$kycStatus = $user['kyc_status'] ?? 'none';
if ($kycStatus !== 'approved') {
    $msg = $kycStatus === 'pending'
        ? 'Your KYC is pending approval. Deposits unlock after verification.'
        : ($kycStatus === 'rejected'
            ? 'Your KYC was rejected. Please re-submit to enable deposits.'
            : 'Complete identity verification to enable deposits.');
    json_response(['error' => $msg, 'kyc_required' => true], 403);
}

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    json_response(['error' => 'CSRF mismatch'], 419);
}

$amount = (float)($_POST['amount'] ?? 0);

if ($amount <= 0) {
    json_response(['error' => 'Enter a positive amount.'], 400);
}
if ($amount > 1000000) {
    json_response(['error' => 'Max $1,000,000 per demo deposit.'], 400);
}

try {
    $pdo = db();
    $pdo->beginTransaction();

    $pdo->prepare('UPDATE users SET cash_balance = cash_balance + ? WHERE id = ?')
        ->execute([$amount, $user['id']]);

    $pdo->prepare('INSERT INTO cash_transactions (user_id, type, amount, note)
                   VALUES (?, "DEPOSIT", ?, ?)')
        ->execute([$user['id'], $amount, 'Demo top-up']);

    $pdo->commit();

    json_response([
        'success' => true,
        'amount'  => $amount,
        'message' => 'Added ' . usd($amount) . ' virtual funds',
    ]);
} catch (Throwable $e) {
    if (db()->inTransaction()) db()->rollBack();
    json_response(['error' => DEBUG_MODE ? $e->getMessage() : 'Deposit failed'], 500);
}