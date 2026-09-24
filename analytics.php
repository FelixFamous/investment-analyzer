<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'Performance Analytics';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/intelligence.css">

<div id="analyticsRoot">
  <div class="summary-grid" style="margin-bottom:20px;">
    <div class="card">
      <div class="card-label">Net P&L</div>
      <div class="card-value" id="anTotalPnl">—</div>
      <div class="card-sub">Realized across all trades</div>
    </div>
    <div class="card">
      <div class="card-label">Win Rate</div>
      <div class="card-value" id="anWinRate">—</div>
      <div class="card-sub" id="anWinLoss">— W / — L</div>
    </div>
    <div class="card">
      <div class="card-label">Profit Factor</div>
      <div class="card-value" id="anProfitFactor">—</div>
      <div class="card-sub">Gross win ÷ gross loss</div>
    </div>
    <div class="card">
      <div class="card-label">Expectancy</div>
      <div class="card-value" id="anExpectancy">—</div>
      <div class="card-sub">Avg P&L per trade</div>
    </div>
  </div>

  <div class="summary-grid" style="margin-bottom:20px;">
    <div class="card">
      <div class="card-label">Total Trades</div>
      <div class="card-value" id="anTotal">—</div>
      <div class="card-sub">Closed positions</div>
    </div>
    <div class="card">
      <div class="card-label">Avg Win</div>
      <div class="card-value positive" id="anAvgWin">—</div>
      <div class="card-sub">Per winning trade</div>
    </div>
    <div class="card">
      <div class="card-label">Avg Loss</div>
      <div class="card-value negative" id="anAvgLoss">—</div>
      <div class="card-sub">Per losing trade</div>
    </div>
    <div class="card">
      <div class="card-label">Streaks</div>
      <div class="card-value" id="anStreaks" style="font-size:16px;">—</div>
      <div class="card-sub">Longest win / loss</div>
    </div>
  </div>

  <div class="main-grid" style="grid-template-columns:1fr 1fr;margin-bottom:20px;">
    <div class="panel">
      <div class="panel-header"><h2>💼 By Asset</h2></div>
      <div id="anByAsset"><div class="empty-state">Loading…</div></div>
    </div>
    <div class="panel">
      <div class="panel-header"><h2>📅 By Day of Week</h2></div>
      <div id="anByDay"><div class="empty-state">Loading…</div></div>
    </div>
  </div>

  <div class="main-grid" style="grid-template-columns:1fr 1fr;">
    <div class="panel">
      <div class="panel-header"><h2>🌍 By Session</h2></div>
      <div id="anBySession"><div class="empty-state">Loading…</div></div>
    </div>
    <div class="panel">
      <div class="panel-header"><h2>📈 Monthly P&amp;L</h2></div>
      <div id="anByMonth"><div class="empty-state">Loading…</div></div>
    </div>
  </div>
</div>

<script>
  window.ANALYTICS_API = <?= json_encode(APP_URL) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/analytics.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>