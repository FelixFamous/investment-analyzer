<?php
require_once __DIR__ . '/../includes/auth.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$sinceId = (int)($_GET['since'] ?? 0);

if ($sinceId > 0) {
    $stmt = db()->prepare(
        'SELECT id, username, body, symbol, created_at
         FROM chat_messages WHERE id > ? ORDER BY id ASC LIMIT 100'
    );
    $stmt->execute([$sinceId]);
} else {
    $stmt = db()->query(
        'SELECT id, username, body, symbol, created_at
         FROM chat_messages ORDER BY id DESC LIMIT 50'
    );
    $rows = $stmt->fetchAll();
    $rows = array_reverse($rows);
    json_response(['messages' => $rows, 'current_user' => $user['username']]);
}

json_response(['messages' => $stmt->fetchAll(), 'current_user' => $user['username']]);