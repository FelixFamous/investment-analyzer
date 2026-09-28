<?php
/**
 * AlphaEdge Academy — content engine.
 * Pure data access. No HTML, no hard-coded lesson text.
 */

require_once __DIR__ . '/auth.php';

/* ============================================================
 *  LEVELS / MODULES / LESSONS
 * ============================================================ */

function academy_levels(): array
{
    return db()->query('SELECT * FROM academy_levels ORDER BY sort_order ASC')->fetchAll();
}

function academy_modules(?int $levelId = null, bool $onlyPublished = true): array
{
    $sql = 'SELECT m.*, l.slug AS level_slug, l.title AS level_title
            FROM academy_modules m
            JOIN academy_levels l ON l.id = m.level_id
            WHERE 1=1';
    $params = [];
    if ($levelId !== null) { $sql .= ' AND m.level_id = ?'; $params[] = $levelId; }
    if ($onlyPublished)    { $sql .= ' AND m.published = 1'; }
    $sql .= ' ORDER BY l.sort_order ASC, m.sort_order ASC';

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function academy_module_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('
        SELECT m.*, l.slug AS level_slug, l.title AS level_title
        FROM academy_modules m
        JOIN academy_levels l ON l.id = m.level_id
        WHERE m.slug = ?
        LIMIT 1
    ');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function academy_lessons_for_module(int $moduleId): array
{
    $stmt = db()->prepare('
        SELECT id, title, slug, difficulty, estimated_duration, sort_order
        FROM academy_lessons
        WHERE module_id = ? AND published = 1
        ORDER BY sort_order ASC, id ASC
    ');
    $stmt->execute([$moduleId]);
    return $stmt->fetchAll();
}

function academy_lesson_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('
        SELECT c.*, m.title AS module_title, m.slug AS module_slug,
               l.slug AS level_slug, l.title AS level_title
        FROM academy_lessons c
        LEFT JOIN academy_modules m ON m.id = c.module_id
        LEFT JOIN academy_levels  l ON l.id = m.level_id
        WHERE c.slug = ? AND c.published = 1
        LIMIT 1
    ');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

/* ============================================================
 *  DIAGRAMS
 * ============================================================ */

function academy_diagrams_for_lesson(int $lessonId): array
{
    $stmt = db()->prepare('
        SELECT * FROM academy_diagrams
        WHERE lesson_id = ?
        ORDER BY order_index ASC, id ASC
    ');
    $stmt->execute([$lessonId]);
    return $stmt->fetchAll();
}

/* ============================================================
 *  VIDEO RESOURCES
 *  Only verified AND published videos are exposed publicly.
 * ============================================================ */

function academy_videos_for_lesson(int $lessonId, bool $publicOnly = true): array
{
    $sql = 'SELECT * FROM academy_video_resources WHERE lesson_id = ?';
    if ($publicOnly) $sql .= ' AND verified = 1 AND published = 1';
    $sql .= ' ORDER BY sort_order ASC, id ASC';

    $stmt = db()->prepare($sql);
    $stmt->execute([$lessonId]);
    return $stmt->fetchAll();
}

function academy_videos_pending_verification(): array
{
    return db()->query('
        SELECT v.*, l.title AS lesson_title, l.slug AS lesson_slug
        FROM academy_video_resources v
        JOIN academy_lessons l ON l.id = v.lesson_id
        WHERE v.verified = 0
        ORDER BY v.created_at DESC
    ')->fetchAll();
}

function academy_verify_video(int $videoId, int $adminId): bool
{
    $stmt = db()->prepare('
        UPDATE academy_video_resources
        SET verified = 1,
            published = 1,
            verification_date = UTC_TIMESTAMP(),
            verified_by = ?
        WHERE id = ?
    ');
    $stmt->execute([$adminId, $videoId]);
    return $stmt->rowCount() > 0;
}

function academy_unverify_video(int $videoId): bool
{
    $stmt = db()->prepare('
        UPDATE academy_video_resources
        SET verified = 0, published = 0, verification_date = NULL, verified_by = NULL
        WHERE id = ?
    ');
    $stmt->execute([$videoId]);
    return $stmt->rowCount() > 0;
}

/* ============================================================
 *  SOURCES
 * ============================================================ */

function academy_sources_for_lesson(int $lessonId): array
{
    $stmt = db()->prepare('
        SELECT * FROM academy_sources
        WHERE lesson_id = ?
        ORDER BY id ASC
    ');
    $stmt->execute([$lessonId]);
    return $stmt->fetchAll();
}

/* ============================================================
 *  QUIZZES
 * ============================================================ */

function academy_quiz_for_lesson(int $lessonId): ?array
{
    $stmt = db()->prepare('SELECT * FROM academy_quizzes WHERE lesson_id = ? LIMIT 1');
    $stmt->execute([$lessonId]);
    $quiz = $stmt->fetch();
    if (!$quiz) return null;

    $q = db()->prepare('SELECT * FROM academy_quiz_questions WHERE quiz_id = ? ORDER BY sort_order ASC');
    $q->execute([$quiz['id']]);
    $quiz['questions'] = $q->fetchAll();
    return $quiz;
}

/* ============================================================
 *  PROGRESS
 * ============================================================ */

function academy_is_completed(int $userId, int $lessonId): bool
{
    $stmt = db()->prepare('SELECT 1 FROM academy_progress WHERE user_id = ? AND lesson_id = ? LIMIT 1');
    $stmt->execute([$userId, $lessonId]);
    return (bool)$stmt->fetchColumn();
}

function academy_mark_completed(int $userId, int $lessonId): void
{
    db()->prepare('INSERT IGNORE INTO academy_progress (user_id, lesson_id) VALUES (?, ?)')
        ->execute([$userId, $lessonId]);
}

function academy_progress_summary(int $userId): array
{
    $total = (int)db()->query('SELECT COUNT(*) FROM academy_lessons WHERE published = 1')->fetchColumn();
    $stmt = db()->prepare('SELECT COUNT(*) FROM academy_progress WHERE user_id = ?');
    $stmt->execute([$userId]);
    $done = (int)$stmt->fetchColumn();

    return [
        'total'    => $total,
        'completed'=> $done,
        'percent'  => $total > 0 ? round(($done / $total) * 100, 1) : 0.0,
    ];
}

function academy_next_recommended(int $userId): ?array
{
    $stmt = db()->prepare('
        SELECT l.id, l.title, l.slug
        FROM academy_lessons l
        JOIN academy_modules m ON m.id = l.module_id
        WHERE l.published = 1
          AND l.id NOT IN (SELECT lesson_id FROM academy_progress WHERE user_id = ?)
        ORDER BY m.sort_order ASC, l.sort_order ASC
        LIMIT 1
    ');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}

/* ============================================================
 *  BOOKMARKS + NOTES
 * ============================================================ */

function academy_bookmark_toggle(int $userId, int $lessonId, string $type = 'lesson', ?int $itemId = null): bool
{
    $stmt = db()->prepare('
        SELECT id FROM academy_bookmarks
        WHERE user_id = ? AND lesson_id = ? AND item_type = ?
          AND (item_id <=> ?)
        LIMIT 1
    ');
    $stmt->execute([$userId, $lessonId, $type, $itemId]);
    $existing = $stmt->fetchColumn();

    if ($existing) {
        db()->prepare('DELETE FROM academy_bookmarks WHERE id = ?')->execute([$existing]);
        return false;
    }
    db()->prepare('
        INSERT INTO academy_bookmarks (user_id, lesson_id, item_type, item_id)
        VALUES (?, ?, ?, ?)
    ')->execute([$userId, $lessonId, $type, $itemId]);
    return true;
}

function academy_note_get(int $userId, int $lessonId): ?string
{
    $stmt = db()->prepare('SELECT note_text FROM academy_notes WHERE user_id = ? AND lesson_id = ? LIMIT 1');
    $stmt->execute([$userId, $lessonId]);
    $n = $stmt->fetchColumn();
    return $n === false ? null : (string)$n;
}

function academy_note_save(int $userId, int $lessonId, string $text): void
{
    if (strlen($text) > 5000) $text = substr($text, 0, 5000);
    db()->prepare('
        INSERT INTO academy_notes (user_id, lesson_id, note_text)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE note_text = VALUES(note_text), updated_at = CURRENT_TIMESTAMP
    ')->execute([$userId, $lessonId, $text]);
}

/* ============================================================
 *  SEARCH
 * ============================================================ */

function academy_search(string $query, int $limit = 30): array
{
    $q = '%' . trim($query) . '%';
    $stmt = db()->prepare('
        SELECT l.id, l.title, l.slug, l.difficulty, l.estimated_duration,
               m.title AS module_title, lv.title AS level_title
        FROM academy_lessons l
        LEFT JOIN academy_modules m ON m.id = l.module_id
        LEFT JOIN academy_levels  lv ON lv.id = m.level_id
        WHERE l.published = 1
          AND (l.title LIKE ? OR l.content LIKE ? OR l.summary LIKE ?)
        ORDER BY l.sort_order ASC
        LIMIT ' . (int)$limit
    );
    $stmt->execute([$q, $q, $q]);
    return $stmt->fetchAll();
}

/* ============================================================
 *  CERTIFICATES
 * ============================================================ */

function academy_issue_certificate(int $userId, string $type, int $examScore = 0): bool
{
    if (!in_array($type, ['foundations','technical','advanced','mastery'], true)) return false;
    $stmt = db()->prepare('
        INSERT IGNORE INTO academy_certificates (user_id, certificate_type, exam_score)
        VALUES (?, ?, ?)
    ');
    $stmt->execute([$userId, $type, $examScore]);
    return $stmt->rowCount() > 0;
}

function academy_certificates_for_user(int $userId): array
{
    $stmt = db()->prepare('SELECT * FROM academy_certificates WHERE user_id = ? ORDER BY issued_at DESC');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}