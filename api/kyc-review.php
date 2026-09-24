<?php
/**
 * POST /api/kyc-review.php
 * Body: user_id, action (approve|reject), reason (optional), csrf
 * Admin only.
 */
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$admin = current_user();
if (!$admin)                                    json_response(['error' => 'Not authenticated'], 401);
if ((int)$admin['is_admin'] !== 1)              json_response(['error' => 'Admin only'], 403);
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? ''))
                                                json_response(['error' => 'CSRF mismatch'], 419);

$targetId = (int)($_POST['user_id'] ?? 0);
$action   = trim((string)($_POST['action'] ?? ''));
$reason   = trim((string)($_POST['reason'] ?? ''));

if ($targetId <= 0)                             json_response(['error' => 'Invalid user'], 400);
if (!in_array($action, ['approve', 'reject'], true))
                                                json_response(['error' => 'Invalid action'], 400);

// Target must exist and be pending (or rejected → can be approved)
$stmt = db()->prepare('SELECT id, kyc_status FROM users WHERE id = ?');
$stmt->execute([$targetId]);
$target = $stmt->fetch();
if (!$target)                                   json_response(['error' => 'User not found'], 404);
if (!in_array($target['kyc_status'], ['pending', 'rejected'], true))
                                                json_response(['error' => 'User is not awaiting review'], 400);

if ($action === 'approve') {
    db()->prepare('UPDATE users SET
        kyc_status = "approved",
        kyc_reviewed_at = UTC_TIMESTAMP(),
        kyc_reviewer_id = ?,
        kyc_rejection_reason = NULL
        WHERE id = ?')->execute([$admin['id'], $targetId]);
    json_response(['success' => true, 'message' => 'KYC approved']);
} else {
    if ($reason === '') $reason = 'Documents unclear or incomplete.';
    if (strlen($reason) > 240) $reason = substr($reason, 0, 240);
    db()->prepare('UPDATE users SET
        kyc_status = "rejected",
        kyc_reviewed_at = UTC_TIMESTAMP(),
        kyc_reviewer_id = ?,
        kyc_rejection_reason = ?
        WHERE id = ?')->execute([$admin['id'], $reason, $targetId]);
    json_response(['success' => true, 'message' => 'KYC rejected']);
}