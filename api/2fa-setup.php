<?php
/**
 * POST /api/2fa-setup.php
 * Generates a fresh TOTP secret for the current user (not yet enabled).
 * Returns the provisioning URI so the browser can render a QR code.
 *
 * The user must confirm a valid code via /api/2fa-verify.php before
 * the secret is activated.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/totp.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

// If 2FA is already on, refuse — user must disable first
if ((int)$user['totp_enabled'] === 1) {
    json_response(['error' => 'Two-factor is already enabled. Disable it first.'], 400);
}

// Generate a new secret and store it (pending verification)
$secret = TOTP::generateSecret();

db()->prepare('UPDATE users SET totp_secret = ?, totp_verified_at = NULL WHERE id = ?')
    ->execute([$secret, $user['id']]);

$uri = TOTP::getProvisioningUri($secret, $user['email'], 'AlphaEdge');

json_response([
    'success' => true,
    'secret'  => $secret,
    'uri'     => $uri,
]);