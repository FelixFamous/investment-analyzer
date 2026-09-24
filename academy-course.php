<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$courseId = (int)($_GET['id'] ?? 0);
if ($courseId <= 0) { redirect(APP_URL . '/academy.php'); }

$stmt = db()->prepare('SELECT * FROM academy_courses WHERE id = ? LIMIT 1');
$stmt->execute([$courseId]);
$course = $stmt->fetch();
if (!$course) { flash_set('error', 'Course not found.'); redirect(APP_URL . '/academy.php'); }

$stmt = db()->prepare('SELECT id, title, content, video_url FROM academy_lessons WHERE course_id = ? ORDER BY sort_order ASC');
$stmt->execute([$courseId]);
$lessons = $stmt->fetchAll();

$stmt = db()->prepare('SELECT lesson_id FROM academy_progress WHERE user_id = ?');
$stmt->execute([$user['id']]);
$completed = array_column($stmt->fetchAll(), 'lesson_id');

$pageTitle = $course['title'];
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/academy.css">

<div class="academy-lesson-wrap">

  <!-- Left: lesson list -->
  <aside class="academy-sidebar">
    <a href="<?= e(APP_URL) ?>/academy.php" class="btn btn-sm" style="margin-bottom:14px;width:100%;">← Back to Academy</a>
    <div class="academy-sidebar-title"><?= e($course['icon']) ?> <?= e($course['title']) ?></div>
    <ul class="academy-lesson-list">
      <?php foreach ($lessons as $i => $l): ?>
        <li class="academy-lesson-item <?= in_array($l['id'], $completed) ? 'done' : '' ?>" data-lesson-id="<?= (int)$l['id'] ?>">
          <span class="academy-lesson-num"><?= $i + 1 ?></span>
          <span class="academy-lesson-title"><?= e($l['title']) ?></span>
          <?php if (in_array($l['id'], $completed)): ?>
            <span class="academy-lesson-check">✓</span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>

  <!-- Right: lesson content -->
  <main class="academy-main" id="academyMain">
    <?php foreach ($lessons as $i => $l): ?>
      <article class="academy-lesson-content" data-lesson-id="<?= (int)$l['id'] ?>" style="<?= $i === 0 ? '' : 'display:none' ?>">
        <h1><?= e($l['title']) ?></h1>

        <?php if (!empty($l['video_url'])): ?>
          <a href="<?= e($l['video_url']) ?>" target="_blank" rel="noopener" class="academy-video-link">
            ▶️ Watch video explanation (opens YouTube)
          </a>
        <?php endif; ?>

        <div class="academy-text"><?= nl2br(e($l['content'])) ?></div>

        <?php if (!in_array($l['id'], $completed)): ?>
          <button class="btn btn-primary academy-complete-btn" data-complete="<?= (int)$l['id'] ?>">
            ✓ Mark as complete
          </button>
        <?php else: ?>
          <div class="academy-completed-badge">✓ Completed</div>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </main>

</div>

<script>
  window.AC_API = <?= json_encode(APP_URL) ?>;
  window.AC_COURSE_ID = <?= (int)$courseId ?>;
  window.AC_COMPLETED = <?= json_encode(array_map('intval', $completed)) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/academy.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>