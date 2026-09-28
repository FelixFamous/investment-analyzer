<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/academy-engine.php';

$user = require_login();

$slug = trim((string)($_GET['slug'] ?? ''));
if ($slug === '') redirect(APP_URL . '/academy.php');

$lesson = academy_lesson_by_slug($slug);
if (!$lesson) {
    http_response_code(404);
    $pageTitle = 'Lesson not found';
    require __DIR__ . '/includes/header.php';
    echo '<div class="panel"><div class="empty-state">That lesson does not exist.</div></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$lessonId = (int)$lesson['id'];
$uid      = (int)$user['id'];

$diagrams   = academy_diagrams_for_lesson($lessonId);
$videos     = academy_videos_for_lesson($lessonId, true);
$sources    = academy_sources_for_lesson($lessonId);
$quiz       = academy_quiz_for_lesson($lessonId);
$isDone     = academy_is_completed($uid, $lessonId);
$myNote     = academy_note_get($uid, $lessonId);

$bmStmt = db()->prepare('
    SELECT 1 FROM academy_bookmarks
    WHERE user_id = ? AND lesson_id = ? AND item_type = "lesson" AND item_id IS NULL
    LIMIT 1
');
$bmStmt->execute([$uid, $lessonId]);
$isBookmarked = (bool)$bmStmt->fetchColumn();

$siblings = academy_lessons_for_module((int)$lesson['module_id']);
$idx = 0;
foreach ($siblings as $i => $s) if ((int)$s['id'] === $lessonId) { $idx = $i; break; }
$prev = $siblings[$idx - 1] ?? null;
$next = $siblings[$idx + 1] ?? null;

$pageTitle = $lesson['title'];
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/academy.css">

<script>
window.ACADEMY = {
  api: '<?= e(APP_URL) ?>',
  csrf: '<?= e(csrf_token()) ?>',
  lessonId: <?= $lessonId ?>,
  isDone: <?= $isDone ? 'true' : 'false' ?>,
  isBookmarked: <?= $isBookmarked ? 'true' : 'false' ?>
};
</script>

<div class="academy-breadcrumb">
  <a href="<?= e(APP_URL) ?>/academy.php">Academy</a>
  <span>/</span>
  <?php if (!empty($lesson['module_slug'])): ?>
    <a href="<?= e(APP_URL) ?>/academy-module.php?slug=<?= e($lesson['module_slug']) ?>"><?= e($lesson['module_title']) ?></a>
    <span>/</span>
  <?php endif; ?>
  <span><?= e($lesson['title']) ?></span>
</div>

<div class="academy-lesson-layout">
  <article class="academy-lesson-main">
    <header class="academy-lesson-header">
      <h1><?= e($lesson['title']) ?></h1>
      <div class="academy-lesson-meta">
        <span class="badge"><?= e($lesson['difficulty'] ?? 'beginner') ?></span>
        <?php if (!empty($lesson['estimated_duration'])): ?>
          <span class="academy-meta-text">~<?= (int)$lesson['estimated_duration'] ?> min read</span>
        <?php endif; ?>
      </div>

      <div class="academy-lesson-actions">
        <button type="button" class="btn btn-sm" id="academyBookmarkBtn">
          <?= $isBookmarked ? '★ Bookmarked' : '☆ Bookmark' ?>
        </button>
        <button type="button" class="btn btn-sm btn-primary" id="academyMarkDoneBtn">
          <?= $isDone ? '✓ Completed' : 'Mark as complete' ?>
        </button>
      </div>
    </header>

    <?php if (!empty($lesson['learning_objectives'])): ?>
      <section class="academy-section academy-objectives">
        <h3>Learning Objectives</h3>
        <div><?= nl2br(e($lesson['learning_objectives'])) ?></div>
      </section>
    <?php endif; ?>

    <?php if (!empty($lesson['prerequisites'])): ?>
      <section class="academy-section academy-prereq">
        <h3>Prerequisites</h3>
        <div><?= nl2br(e($lesson['prerequisites'])) ?></div>
      </section>
    <?php endif; ?>

    <section class="academy-section academy-content">
      <?php if (!empty($lesson['content'])): ?>
        <?= $lesson['content'] ?>
      <?php else: ?>
        <div class="empty-state">The written lesson for this topic is still being prepared.</div>
      <?php endif; ?>
    </section>

    <?php if ($diagrams): ?>
      <section class="academy-section">
        <h3>Visual Explanations</h3>
        <?php foreach ($diagrams as $d): ?>
          <figure class="academy-diagram" data-diagram-id="<?= (int)$d['id'] ?>">
            <figcaption class="academy-diagram-title"><?= e($d['title']) ?></figcaption>
            <?php if (!empty($d['svg_content'])): ?>
              <div class="academy-diagram-svg"><?= $d['svg_content'] ?></div>
            <?php elseif (!empty($d['image_url'])): ?>
              <img src="<?= e($d['image_url']) ?>" alt="<?= e($d['alt_text']) ?>" loading="lazy">
            <?php endif; ?>
            <?php if (!empty($d['caption'])): ?>
              <div class="academy-diagram-caption"><?= e($d['caption']) ?></div>
            <?php endif; ?>
          </figure>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>

    <?php if ($videos): ?>
      <section class="academy-section">
        <h3>🎥 Watch &amp; Learn</h3>
        <p class="academy-section-note">Reinforcement from verified external educators. Watch after reading.</p>
        <div class="academy-video-grid">
          <?php foreach ($videos as $v): ?>
            <div class="academy-video-card">
              <div class="academy-video-title"><?= e($v['title']) ?></div>
              <div class="academy-video-meta">
                <?php if (!empty($v['instructor'])): ?>Instructor: <?= e($v['instructor']) ?><?php endif; ?>
                <?php if (!empty($v['channel_name'])): ?> · Channel: <?= e($v['channel_name']) ?><?php endif; ?>
              </div>
              <?php if (!empty($v['topic'])): ?>
                <div class="academy-video-meta">Topic: <?= e($v['topic']) ?></div>
              <?php endif; ?>
              <?php if (!empty($v['description'])): ?>
                <div class="academy-video-desc"><?= e($v['description']) ?></div>
              <?php endif; ?>
              <a class="btn btn-sm btn-primary" href="<?= e($v['url']) ?>" target="_blank" rel="noopener noreferrer">
                Watch video →
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($quiz && !empty($quiz['questions'])): ?>
      <section class="academy-section">
        <h3>Knowledge Check</h3>
        <form id="academyQuizForm" data-quiz-id="<?= (int)$quiz['id'] ?>">
          <?php foreach ($quiz['questions'] as $q): ?>
            <div class="academy-quiz-q" data-qid="<?= (int)$q['id'] ?>" data-type="<?= e($q['question_type']) ?>">
              <div class="academy-quiz-question"><?= e($q['question_text']) ?></div>
              <?php
                $opts = json_decode($q['options_json'] ?? '[]', true);
                if (is_array($opts) && count($opts) > 0):
                  foreach ($opts as $opt):
              ?>
                <label class="academy-quiz-option">
                  <input type="radio"
                         name="q<?= (int)$q['id'] ?>"
                         value="<?= e((string)$opt) ?>">
                  <span><?= e((string)$opt) ?></span>
                </label>
              <?php endforeach; else: ?>
                <input class="academy-quiz-input"
                       type="text"
                       name="q<?= (int)$q['id'] ?>"
                       placeholder="Your answer">
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
          <button type="submit" class="btn btn-primary">Submit answers</button>
        </form>
        <div id="academyQuizResult" class="academy-quiz-result" style="display:none;"></div>
      </section>
    <?php endif; ?>

    <?php if (!empty($lesson['summary'])): ?>
      <section class="academy-section academy-summary">
        <h3>Summary</h3>
        <div><?= nl2br(e($lesson['summary'])) ?></div>
      </section>
    <?php endif; ?>

    <?php if ($sources): ?>
      <section class="academy-section">
        <h3>Sources</h3>
        <ul class="academy-sources">
          <?php foreach ($sources as $s): ?>
            <li>
              <a href="<?= e($s['source_url']) ?>" target="_blank" rel="noopener noreferrer">
                <?= e($s['source_name']) ?>
              </a>
              <?php if (!empty($s['author'])): ?> · <?= e($s['author']) ?><?php endif; ?>
              <?php if (!empty($s['publication_date'])): ?> · <?= e($s['publication_date']) ?><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>
    <?php endif; ?>

    <nav class="academy-lesson-nav">
      <?php if ($prev): ?>
        <a class="btn" href="<?= e(APP_URL) ?>/academy-lesson.php?slug=<?= e($prev['slug']) ?>">
          ← <?= e($prev['title']) ?>
        </a>
      <?php else: ?><span></span><?php endif; ?>

      <?php if ($next): ?>
        <a class="btn btn-primary" href="<?= e(APP_URL) ?>/academy-lesson.php?slug=<?= e($next['slug']) ?>">
          <?= e($next['title']) ?> →
        </a>
      <?php endif; ?>
    </nav>
  </article>

  <aside class="academy-lesson-side">
    <div class="panel">
      <h4 class="academy-side-h">Module lessons</h4>
      <ol class="academy-side-list">
        <?php foreach ($siblings as $s): ?>
          <li class="<?= (int)$s['id'] === $lessonId ? 'current' : '' ?>">
            <a href="<?= e(APP_URL) ?>/academy-lesson.php?slug=<?= e($s['slug']) ?>">
              <?= e($s['title']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>

    <div class="panel">
      <h4 class="academy-side-h">My Notes</h4>
      <textarea id="academyNoteText"
                class="academy-note-textarea"
                rows="6"
                placeholder="Private notes for this lesson…"><?= e($myNote ?? '') ?></textarea>
      <button type="button" class="btn btn-sm btn-primary" id="academyNoteSaveBtn">Save note</button>
      <div id="academyNoteStatus" class="academy-note-status"></div>
    </div>
  </aside>
</div>

<script src="<?= e(APP_URL) ?>/assets/js/academy.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>