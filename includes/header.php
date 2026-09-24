<?php
if (!defined('APP_NAME')) require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? APP_NAME;
$user      = current_user();
$flash     = flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> · <?= e(APP_NAME) ?></title>

  <!-- Favicon & theme -->
  <link rel="icon" type="image/svg+xml" href="<?= e(APP_URL) ?>/favicon.svg">
  <link rel="apple-touch-icon" href="<?= e(APP_URL) ?>/favicon.svg">
  <meta name="theme-color" content="#0a0b0f">

  <!-- Fonts & styles -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/premium.css">
  <link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/sidebar.css">

  <script>
    window.ALPHAEDGE = {
      api: '<?= e(APP_URL) ?>',
      csrf: '<?= e(csrf_token()) ?>',
      cash: <?= $user ? (float)$user['cash_balance'] : 0 ?>,
      userId: <?= $user ? (int)$user['id'] : 0 ?>,
      timezone: '<?= e(user_timezone()) ?>'
    };
  </script>
</head>
<body>

<?php if ($user): include __DIR__ . '/sidebar.php'; ?>

<div class="main">
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:12px;">
      <div class="balance-pill" id="topBalance">
        <small>USD</small><?= e(usd((float)$user['cash_balance'])) ?>
      </div>
    </div>

    <div class="topbar-right">
      <button class="btn btn-sm" id="aeDepositBtn" type="button">Deposit</button>
      <button class="btn btn-sm" id="aeWithdrawBtn" type="button">Withdraw</button>
      <button class="btn btn-sm" id="aeTzBtn" type="button" title="Change timezone">
        🌍 <?= e(tz_short_label()) ?>
      </button>
      <?php if ((int)$user['is_admin'] === 1): ?>
        <a class="btn btn-sm" href="<?= e(APP_URL) ?>/admin.php"
           style="border-color:rgba(91,124,250,0.4);color:#93a5ff;">
          🛡️ Admin
        </a>
      <?php endif; ?>
    </div>
  </header>

  <?php if ($flash): ?>
    <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
  <?php endif; ?>

<?php else: ?>

<div class="app">
  <header class="topbar">
    <a class="brand" href="<?= e(APP_URL) ?>/index.php">
      AlphaEdge <span>paper trading</span>
    </a>
    <div class="topbar-right">
      <a class="btn btn-sm" href="<?= e(APP_URL) ?>/login.php">Sign In</a>
      <a class="btn btn-sm btn-primary" href="<?= e(APP_URL) ?>/register.php">Create Account</a>
    </div>
  </header>

  <?php if ($flash): ?>
    <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
  <?php endif; ?>

<?php endif; ?>