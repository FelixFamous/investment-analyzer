<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/academy-engine.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
csrf_verify_or_die();

$lessonId = (int)($_POST['lesson_id'] ?? 0);
$note     = (string)($_POST['note'] ?? '');
if ($lessonId <= 0) json_response(['error' => 'Invalid lesson'], 400);

academy_note_save((int)$user['id'], $lessonId, $note);

json_response(['success' => true]);