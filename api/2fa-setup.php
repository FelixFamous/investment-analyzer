<?php
/**
 * POST /api/2fa-setup.php
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/totp.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
csrf_verify_or_die();

if ((int)$user['totp_enabled'] === 1) {
    json_response(['error' => 'Two-factor is already enabled. Disable it first.'], 400);
}

$secret = TOTP::generateSecret();

db()->prepare('UPDATE users SET totp_secret = ?, totp_verified_at = NULL WHERE id = ?')
    ->execute([$secret, $user['id']]);

$uri = TOTP::getProvisioningUri($secret, $user['email'], 'AlphaEdge');

json_response([
    'success' => true,
    'secret'  => $secret,
    'uri'     => $uri,
]);