<?php
require_once __DIR__ . '/../includes/auth.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$stmt = db()->prepare(
    'SELECT symbol, side, quantity, price, total, pnl, created_at
     FROM trades WHERE user_id = ? ORDER BY created_at DESC LIMIT 50'
);
$stmt->execute([$user['id']]);
json_response(['trades' => $stmt->fetchAll()]);