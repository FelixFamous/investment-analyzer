<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'Correlation Matrix';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/intelligence.css">

<div class="panel mb-16">
  <div class="panel-header">
    <h2>🔗 Correlation Matrix</h2>
    <div class="tabs" id="corrDaysTabs" style="margin:0;padding:2px;">
      <button class="tab active" data-days="30"  style="padding:4px 12px;font-size:11px;">30D</button>
      <button class="tab" data-days="90"  style="padding:4px 12px;font-size:11px;">90D</button>
      <button class="tab" data-days="180" style="padding:4px 12px;font-size:11px;">180D</button>
    </div>
  </div>

  <p class="text-dim" style="font-size:12.5px;line-height:1.7;margin-bottom:16px;">
    Correlation measures how two assets move together:
  </p>

  <div class="corr-legend">
    <div class="corr-legend-item"><span class="corr-swatch strong-pos"></span> Strong positive (0.7 to 1.0)</div>
    <div class="corr-legend-item"><span class="corr-swatch weak-pos"></span> Weak positive (0.3 to 0.7)</div>
    <div class="corr-legend-item"><span class="corr-swatch neutral"></span> Neutral (-0.3 to 0.3)</div>
    <div class="corr-legend-item"><span class="corr-swatch weak-neg"></span> Weak negative (-0.7 to -0.3)</div>
    <div class="corr-legend-item"><span class="corr-swatch strong-neg"></span> Strong negative (-1.0 to -0.7)</div>
  </div>

  <div id="corrMatrix" class="corr-matrix">
    <div class="empty-state" style="padding:60px;">Loading matrix…</div>
  </div>

  <div class="corr-insight" id="corrInsight"></div>
</div>

<script>
  window.CORR_API = <?= json_encode(APP_URL) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/correlation.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>