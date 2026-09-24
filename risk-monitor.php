<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'AI Risk Monitor';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/riskmonitor.css">

<div id="rmRoot">
  <div id="rmLoading" class="empty-state" style="padding:80px 20px;">
    <div class="rm-spinner"></div>
    <div style="margin-top:14px;color:var(--text-dim);font-size:13px;">Analyzing portfolio risk…</div>
  </div>

  <div id="rmContent" style="display:none;"></div>
</div>

<script>
  window.RM_API = <?= json_encode(APP_URL) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/riskmonitor.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>