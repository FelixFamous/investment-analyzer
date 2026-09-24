<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'Economic Calendar';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/calendar.css">

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">High Impact</div>
    <div class="card-value negative" id="statHigh">—</div>
    <div class="card-sub">Fed, CPI, NFP, earnings</div>
  </div>
  <div class="card">
    <div class="card-label">Medium Impact</div>
    <div class="card-value" id="statMedium" style="color:var(--accent);">—</div>
    <div class="card-sub">Secondary data releases</div>
  </div>
  <div class="card">
    <div class="card-label">Total Events</div>
    <div class="card-value" id="statTotal">—</div>
    <div class="card-sub">Next 30 days</div>
  </div>
  <div class="card">
    <div class="card-label">Next Event</div>
    <div class="card-value" id="statNext" style="font-size:15px;">—</div>
    <div class="card-sub" id="statNextDate">—</div>
  </div>
</div>

<div class="panel">
  <div class="panel-header">
    <h2>📅 Upcoming Events</h2>
    <div class="tabs" id="calFilters" style="margin:0;padding:2px;">
      <button class="tab active" data-impact="all"    style="padding:5px 12px;font-size:11px;">All</button>
      <button class="tab"        data-impact="high"   style="padding:5px 12px;font-size:11px;">High</button>
      <button class="tab"        data-impact="medium" style="padding:5px 12px;font-size:11px;">Medium</button>
    </div>
  </div>

  <div id="calendarList">
    <div class="empty-state" style="padding:40px;">Loading calendar…</div>
  </div>
</div>

<script>
  window.CAL_API = <?= json_encode(APP_URL) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/calendar.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>