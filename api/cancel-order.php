<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

$id = (int)($_POST['order_id'] ?? 0);
if ($id <= 0) json_response(['error' => 'Invalid order'], 400);

$stmt = db()->prepare('UPDATE pending_orders SET status = "cancelled"
                       WHERE id = ? AND user_id = ? AND status = "open"');
$stmt->execute([$id, $user['id']]);

if ($stmt->rowCount() === 0) json_response(['error' => 'Order not found or already closed'], 404);

json_response(['success' => true, 'message' => 'Order cancelled']);