<?php
require_once __DIR__ . '/../includes/auth.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not logged in'], 401);

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $courses = db()->query('SELECT * FROM academy_courses ORDER BY sort_order ASC')->fetchAll();
    $progress = db()->prepare('SELECT lesson_id FROM academy_progress WHERE user_id = ?');
    $progress->execute([$user['id']]);
    $completed = array_column($progress->fetchAll(), 'lesson_id');

    foreach ($courses as &$c) {
        $lessons = db()->prepare('SELECT id, title, video_url FROM academy_lessons WHERE course_id = ? ORDER BY sort_order ASC');
        $lessons->execute([$c['id']]);
        $c['lessons'] = $lessons->fetchAll();
        $done = 0;
        foreach ($c['lessons'] as $l) {
            if (in_array($l['id'], $completed)) $done++;
        }
        $c['done'] = $done;
        $c['total'] = count($c['lessons']);
    }

    json_response(['courses' => $courses]);
}

if ($action === 'complete') {
    $lessonId = (int)($_POST['lesson_id'] ?? 0);
    if ($lessonId <= 0) json_response(['error' => 'Bad lesson'], 400);

    db()->prepare('INSERT IGNORE INTO academy_progress (user_id, lesson_id) VALUES (?, ?)')
        ->execute([$user['id'], $lessonId]);

    json_response(['success' => true]);
}

json_response(['error' => 'Unknown action'], 400);