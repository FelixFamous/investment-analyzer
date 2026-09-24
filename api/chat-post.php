<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    json_response(['error' => 'CSRF mismatch'], 419);
}

$body   = trim((string)($_POST['body'] ?? ''));
$symbol = strtoupper(trim((string)($_POST['symbol'] ?? '')));

if ($body === '')       json_response(['error' => 'Message is empty'], 400);
if (strlen($body) > 500) json_response(['error' => 'Max 500 characters'], 400);
if ($symbol !== '' && !preg_match('/^[A-Z0-9\-\.\^]{1,15}$/', $symbol)) {
    $symbol = '';
}

db()->prepare('INSERT INTO chat_messages (user_id, username, body, symbol)
               VALUES (?, ?, ?, ?)')
    ->execute([$user['id'], $user['username'], $body, $symbol ?: null]);

json_response(['success' => true]);