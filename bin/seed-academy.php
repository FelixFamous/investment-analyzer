<?php
/**
 * Academy content seeder.
 * Usage: php bin/seed-academy.php sql/content/module_01_intro.php
 *
 * Windows XAMPP:
 *   C:\xampp\php\php.exe bin\seed-academy.php sql\content\module_01_intro.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$file = $argv[1] ?? '';
if (!$file || !file_exists($file)) {
    fwrite(STDERR, "Usage: php seed-academy.php <content-file.php>\n");
    exit(1);
}

$data = require $file;
if (!is_array($data) || !isset($data['module']) || !isset($data['lessons'])) {
    fwrite(STDERR, "Content file must return ['module' => [...], 'lessons' => [...]]\n");
    exit(1);
}

$pdo = db();

/* ---------- Resolve level ---------- */
$levelSlug = $data['module']['level_slug'];
$stmt = $pdo->prepare('SELECT id FROM academy_levels WHERE slug = ? LIMIT 1');
$stmt->execute([$levelSlug]);
$levelId = (int)$stmt->fetchColumn();
if (!$levelId) {
    fwrite(STDERR, "Unknown level slug: $levelSlug\n");
    exit(1);
}

/* ---------- Upsert module ---------- */
$m = $data['module'];
$stmt = $pdo->prepare('
    INSERT INTO academy_modules
        (level_id, slug, title, description, learning_objectives, sort_order, published)
    VALUES (?, ?, ?, ?, ?, ?, 1)
    ON DUPLICATE KEY UPDATE
        title = VALUES(title),
        description = VALUES(description),
        learning_objectives = VALUES(learning_objectives),
        sort_order = VALUES(sort_order),
        published = 1
');
$stmt->execute([
    $levelId,
    $m['slug'],
    $m['title'],
    $m['description'] ?? null,
    $m['learning_objectives'] ?? null,
    $m['sort_order'] ?? 0,
]);

$stmt = $pdo->prepare('SELECT id FROM academy_modules WHERE slug = ? LIMIT 1');
$stmt->execute([$m['slug']]);
$moduleId = (int)$stmt->fetchColumn();

if (!$moduleId) {
    fwrite(STDERR, "Failed to resolve module id after insert.\n");
    exit(1);
}

echo "Module: {$m['title']} (id={$moduleId})\n";

/* ---------- Upsert each lesson ---------- */
foreach ($data['lessons'] as $lesson) {
    if (empty($lesson['slug']) || empty($lesson['title'])) {
        fwrite(STDERR, "Skipping lesson with missing slug/title\n");
        continue;
    }

    $stmt = $pdo->prepare('
        INSERT INTO academy_lessons
            (module_id, title, slug, difficulty, estimated_duration,
             learning_objectives, prerequisites, content, summary,
             sort_order, published)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ON DUPLICATE KEY UPDATE
            title = VALUES(title),
            difficulty = VALUES(difficulty),
            estimated_duration = VALUES(estimated_duration),
            learning_objectives = VALUES(learning_objectives),
            prerequisites = VALUES(prerequisites),
            content = VALUES(content),
            summary = VALUES(summary),
            sort_order = VALUES(sort_order),
            published = 1
    ');
    $stmt->execute([
        $moduleId,
        $lesson['title'],
        $lesson['slug'],
        $lesson['difficulty'] ?? 'beginner',
        (int)($lesson['estimated_duration'] ?? 10),
        $lesson['learning_objectives'] ?? null,
        $lesson['prerequisites'] ?? null,
        $lesson['content'] ?? '',
        $lesson['summary'] ?? null,
        (int)($lesson['sort_order'] ?? 0),
    ]);

    echo "  Seeded lesson: {$lesson['title']}\n";
}

echo "Done.\n";