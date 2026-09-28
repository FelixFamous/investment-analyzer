<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/academy-engine.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
csrf_verify_or_die();

$lessonId = (int)($_POST['lesson_id'] ?? 0);
$type     = (string)($_POST['item_type'] ?? 'lesson');
if ($lessonId <= 0) json_response(['error' => 'Invalid lesson'], 400);

$bookmarked = academy_bookmark_toggle((int)$user['id'], $lessonId, $type, null);

json_response(['success' => true, 'bookmarked' => $bookmarked]);