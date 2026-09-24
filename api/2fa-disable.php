<?php
/**
 * POST /api/2fa-disable.php
 * Requires the current password to disable 2FA.
 */
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

$password = (string)($_POST['password'] ?? '');
if ($password === '') json_response(['error' => 'Password required'], 400);

$stmt = db()->prepare('SELECT password_hash FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$hash = (string)$stmt->fetchColumn();

if (!password_verify($password, $hash)) {
    json_response(['error' => 'Incorrect password'], 400);
}

db()->prepare('UPDATE users
               SET totp_enabled = 0,
                   totp_secret = NULL,
                   totp_recovery = NULL,
                   totp_verified_at = NULL
               WHERE id = ?')
    ->execute([$user['id']]);

json_response(['success' => true, 'message' => 'Two-factor disabled']);