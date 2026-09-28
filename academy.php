<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/academy-engine.php';

$user = require_login();

$levels     = academy_levels();
$modules    = academy_modules();
$progress   = academy_progress_summary((int)$user['id']);
$nextUp     = academy_next_recommended((int)$user['id']);

$byLevel = [];
foreach ($modules as $m) {
    $byLevel[$m['level_slug']][] = $m;
}

$pageTitle = 'Academy';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/academy.css">

<div class="academy-hero">
  <h1>AlphaEdge Academy</h1>
  <p class="academy-tagline">Learn the Market. Understand the Risk. Build Your Edge.</p>

  <div class="academy-progress-bar">
    <div class="academy-progress-fill" style="width:<?= e((string)$progress['percent']) ?>%"></div>
  </div>
  <div class="academy-progress-text">
    <strong><?= (int)$progress['completed'] ?></strong> of
    <strong><?= (int)$progress['total'] ?></strong> lessons completed
    · <?= e((string)$progress['percent']) ?>%
  </div>
</div>

<?php if ($nextUp): ?>
  <div class="panel academy-next-card">
    <div class="academy-next-left">
      <div class="academy-next-label">Recommended next</div>
      <div class="academy-next-title"><?= e($nextUp['title']) ?></div>
    </div>
    <a class="btn btn-primary" href="<?= e(APP_URL) ?>/academy-lesson.php?slug=<?= e($nextUp['slug']) ?>">
      Continue →
    </a>
  </div>
<?php endif; ?>

<?php foreach ($levels as $lvl): ?>
  <?php if (empty($byLevel[$lvl['slug']])) continue; ?>
  <div class="academy-level-section">
    <div class="academy-level-header">
      <h2><?= e($lvl['title']) ?></h2>
      <span class="badge"><?= count($byLevel[$lvl['slug']]) ?> module<?= count($byLevel[$lvl['slug']]) === 1 ? '' : 's' ?></span>
    </div>
    <p class="academy-level-desc"><?= e($lvl['description']) ?></p>

    <div class="academy-module-grid">
      <?php foreach ($byLevel[$lvl['slug']] as $mod): ?>
        <a class="academy-module-card" href="<?= e(APP_URL) ?>/academy-module.php?slug=<?= e($mod['slug']) ?>">
          <div class="academy-module-title"><?= e($mod['title']) ?></div>
          <div class="academy-module-desc"><?= e($mod['description']) ?></div>
          <div class="academy-module-meta">
            <span class="academy-module-pill"><?= e($lvl['title']) ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endforeach; ?>

<?php if (!$modules): ?>
  <div class="panel">
    <div class="empty-state">
      No modules have been published yet.<br>
      Check back soon — the curriculum is being built.
    </div>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>