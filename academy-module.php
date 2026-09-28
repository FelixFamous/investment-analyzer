<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/academy-engine.php';

$user = require_login();

$slug = trim((string)($_GET['slug'] ?? ''));
if ($slug === '') redirect(APP_URL . '/academy.php');

$module = academy_module_by_slug($slug);
if (!$module) {
    http_response_code(404);
    $pageTitle = 'Module not found';
    require __DIR__ . '/includes/header.php';
    echo '<div class="panel"><div class="empty-state">That module does not exist.</div></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$lessons = academy_lessons_for_module((int)$module['id']);

foreach ($lessons as &$l) {
    $l['completed'] = academy_is_completed((int)$user['id'], (int)$l['id']);
}
unset($l);

$done  = count(array_filter($lessons, fn($l) => $l['completed']));
$total = count($lessons);

$pageTitle = $module['title'];
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/academy.css">

<div class="academy-breadcrumb">
  <a href="<?= e(APP_URL) ?>/academy.php">Academy</a>
  <span>/</span>
  <span><?= e($module['title']) ?></span>
</div>

<div class="panel">
  <h1 class="academy-module-h1"><?= e($module['title']) ?></h1>
  <p class="academy-module-intro"><?= e($module['description']) ?></p>

  <div class="academy-progress-bar">
    <div class="academy-progress-fill" style="width:<?= $total > 0 ? round(($done / $total) * 100) : 0 ?>%"></div>
  </div>
  <div class="academy-progress-text">
    <strong><?= $done ?></strong> of <strong><?= $total ?></strong> lessons completed
  </div>

  <?php if (!empty($module['learning_objectives'])): ?>
    <div class="academy-objectives">
      <h3>What you'll learn</h3>
      <div><?= nl2br(e($module['learning_objectives'])) ?></div>
    </div>
  <?php endif; ?>
</div>

<div class="panel">
  <div class="panel-header">
    <h2>Lessons</h2>
    <span class="badge"><?= $total ?></span>
  </div>

  <?php if (!$lessons): ?>
    <div class="empty-state">No lessons published in this module yet.</div>
  <?php else: ?>
    <ol class="academy-lesson-list">
      <?php foreach ($lessons as $l): ?>
        <li class="academy-lesson-row <?= $l['completed'] ? 'is-complete' : '' ?>">
          <a href="<?= e(APP_URL) ?>/academy-lesson.php?slug=<?= e($l['slug']) ?>" class="academy-lesson-link">
            <span class="academy-lesson-check"><?= $l['completed'] ? '✓' : '○' ?></span>
            <span class="academy-lesson-title"><?= e($l['title']) ?></span>
            <span class="academy-lesson-meta">
              <?= e((string)(int)$l['estimated_duration']) ?> min
              · <?= e($l['difficulty']) ?>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    </ol>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>