<?php
/**
 * Admin page header — replaces header.php on admin pages.
 */
if (!defined('APP_NAME')) require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'Admin';
$adminUser = current_user();

if (!$adminUser || (int)$adminUser['is_admin'] !== 1) {
    redirect(APP_URL . '/dashboard.php');
}

$flash = flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> · Admin Console</title>

  <!-- Favicon & theme (blue for admin) -->
  <link rel="icon" type="image/svg+xml" href="<?= e(APP_URL) ?>/favicon.svg">
  <link rel="apple-touch-icon" href="<?= e(APP_URL) ?>/favicon.svg">
  <meta name="theme-color" content="#5b7cfa">

  <!-- Fonts & styles -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/premium.css">
  <link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/sidebar.css">
  <link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/admin.css">

  <script>
    window.ALPHAEDGE = {
      api: '<?= e(APP_URL) ?>',
      csrf: '<?= e(csrf_token()) ?>',
      userId: <?= (int)$adminUser['id'] ?>,
      timezone: '<?= e(user_timezone()) ?>',
      adminMode: true
    };
  </script>
</head>
<body class="admin-mode">

<?php include __DIR__ . '/admin-sidebar.php'; ?>

<div class="main">
  <header class="admin-topbar">
    <div class="admin-topbar-left">
      <span class="admin-badge">🛡️ Admin</span>
      <div>
        <div class="admin-title"><?= e($pageTitle) ?></div>
        <div class="admin-title-sub">Signed in as <?= e($adminUser['username']) ?></div>
      </div>
    </div>

    <div class="admin-topbar-right">
      <span class="admin-clock" id="adminClock">
        <?= e(fmt_time(gmdate('Y-m-d H:i:s'), 'M j, H:i')) ?> <?= e(tz_short_label()) ?>
      </span>
      <a class="btn btn-sm" href="<?= e(APP_URL) ?>/dashboard.php">View as User</a>
      <a class="btn btn-sm" href="<?= e(APP_URL) ?>/logout.php">Logout</a>
    </div>
  </header>

  <?php if ($flash): ?>
    <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
  <?php endif; ?>