<?php
/**
 * Video resource ingestion — enforces the "no fabricated URLs" rule.
 *
 * The ONLY way a video enters the database is via academy_add_video_resource().
 * It validates the URL structure, blocks obvious placeholder patterns, and
 * marks every new video as unverified by default.
 */

require_once __DIR__ . '/auth.php';

/**
 * Attempt to add a video resource.
 * Returns [bool $ok, string $message].
 */
function academy_add_video_resource(array $data): array
{
    $lessonId  = (int)($data['lesson_id'] ?? 0);
    $title     = trim((string)($data['title'] ?? ''));
    $instructor= trim((string)($data['instructor'] ?? ''));
    $channel   = trim((string)($data['channel_name'] ?? ''));
    $platform  = trim((string)($data['platform'] ?? 'YouTube'));
    $url       = trim((string)($data['url'] ?? ''));
    $topic     = trim((string)($data['topic'] ?? ''));
    $sourceType= (string)($data['source_type'] ?? 'official_creator');

    if ($lessonId <= 0)  return [false, 'Lesson required.'];
    if ($title === '')   return [false, 'Title required.'];
    if ($url === '')     return [false, 'URL required.'];

    $check = academy_validate_video_url($url);
    if (!$check['ok']) return [false, $check['reason']];

    $stmt = db()->prepare('SELECT id FROM academy_video_resources WHERE lesson_id = ? AND url = ? LIMIT 1');
    $stmt->execute([$lessonId, $url]);
    if ($stmt->fetchColumn()) return [false, 'That URL is already attached to this lesson.'];

    $stmt = db()->prepare('
        INSERT INTO academy_video_resources
            (lesson_id, title, instructor, channel_name, platform, url,
             topic, source_type, verified, published)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0)
    ');
    $stmt->execute([$lessonId, $title, $instructor, $channel, $platform, $url, $topic, $sourceType]);

    return [true, 'Video added. It is now awaiting admin verification.'];
}

/**
 * Validate a YouTube / educational URL.
 * Rejects placeholders, obviously fake IDs, and non-https schemes.
 */
function academy_validate_video_url(string $url): array
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ['ok' => false, 'reason' => 'Not a valid URL.'];
    }
    $parts = parse_url($url);
    if (($parts['scheme'] ?? '') !== 'https') {
        return ['ok' => false, 'reason' => 'Only https URLs are accepted.'];
    }

    $host = strtolower($parts['host'] ?? '');
    $path = $parts['path'] ?? '';
    parse_str($parts['query'] ?? '', $qs);

    /* ---------- YouTube validation ---------- */
    if (str_contains($host, 'youtube.com') || $host === 'youtu.be') {
        $id = '';
        if ($host === 'youtu.be') {
            $id = ltrim($path, '/');
        } elseif (isset($qs['v'])) {
            $id = (string)$qs['v'];
        } elseif (preg_match('#^/shorts/([A-Za-z0-9_-]+)#', $path, $m)) {
            $id = $m[1];
        } elseif (preg_match('#^/embed/([A-Za-z0-9_-]+)#', $path, $m)) {
            $id = $m[1];
        }

        if ($id === '') {
            return ['ok' => false, 'reason' => 'YouTube URL missing a video ID.'];
        }
        if (!preg_match('/^[A-Za-z0-9_-]{11}$/', $id)) {
            return ['ok' => false, 'reason' => 'YouTube video ID is not 11 characters — this looks fabricated.'];
        }
        $placeholders = ['example', 'xxxxxxxxxxx', 'yyyyyyyyyyy', 'placeholder',
                         'abcdefghijk', '1234567890', 'zzzzzzzzzz'];
        foreach ($placeholders as $p) {
            if (stripos($id, $p) !== false) {
                return ['ok' => false, 'reason' => 'This video ID looks like a placeholder.'];
            }
        }
        return ['ok' => true];
    }

    /* ---------- Known educational sites ---------- */
    $whitelist = [
        'babypips.com', 'www.babypips.com',
        'tradingwithrayner.com', 'www.tradingwithrayner.com',
        'theinnercircletrader.com', 'www.theinnercircletrader.com',
        'investopedia.com', 'www.investopedia.com',
    ];
    if (in_array($host, $whitelist, true)) {
        return ['ok' => true];
    }

    return ['ok' => false, 'reason' => 'Unrecognised source. Only YouTube or approved educational sites are allowed.'];
}

/**
 * Re-check all published videos for broken links.
 * Run from a cron job or admin button. Does NOT auto-replace.
 */
function academy_check_broken_videos(int $limit = 20): array
{
    $stmt = db()->prepare('
        SELECT id, url FROM academy_video_resources
        WHERE verified = 1 AND published = 1
        ORDER BY verification_date ASC
        LIMIT ' . (int)$limit
    );
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $broken = [];

    foreach ($rows as $r) {
        $ch = curl_init($r['url']);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code >= 400 || $code === 0) {
            db()->prepare('
                UPDATE academy_video_resources
                SET verified = 0, published = 0
                WHERE id = ?
            ')->execute([$r['id']]);
            $broken[] = $r;
        }
    }
    return $broken;
}