<?php
/**
 * POST /api/2fa-verify.php
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/totp.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
csrf_verify_or_die();

$code = trim((string)($_POST['code'] ?? ''));
if (!preg_match('/^\d{6}$/', $code)) json_response(['error' => 'Enter the 6-digit code from your app'], 400);

$secret = $user['totp_secret'] ?? '';
if (!$secret) json_response(['error' => 'Start setup first'], 400);

if (!TOTP::verifyCode($secret, $code)) {
    json_response(['error' => 'Incorrect code. Try again.'], 400);
}

$plain = TOTP::generateRecoveryCodes(8);
$hashed = array_map(fn($c) => password_hash($c, PASSWORD_DEFAULT), $plain);

db()->prepare('UPDATE users
               SET totp_enabled = 1,
                   totp_recovery = ?,
                   totp_verified_at = UTC_TIMESTAMP()
               WHERE id = ?')
    ->execute([json_encode($hashed), $user['id']]);

json_response([
    'success'        => true,
    'recovery_codes' => $plain,
    'message'        => 'Two-factor enabled successfully',
]);