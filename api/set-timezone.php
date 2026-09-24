<?php
/**
 * POST /api/set-timezone.php
 * Body: timezone, csrf
 * Updates the current user's timezone.
 */
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    json_response(['error' => 'CSRF mismatch'], 419);
}

$tz = trim((string)($_POST['timezone'] ?? ''));

try {
    new DateTimeZone($tz);
} catch (Throwable $e) {
    json_response(['error' => 'Invalid timezone'], 400);
}

db()->prepare('UPDATE users SET timezone = ? WHERE id = ?')
    ->execute([$tz, $user['id']]);

json_response(['success' => true, 'timezone' => $tz]);