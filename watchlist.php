<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'Watchlist';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/watchlist.css">

<div id="watchlistPage">

  <div class="summary-grid" style="margin-bottom:20px;">
    <div class="card">
      <div class="card-label">Watching</div>
      <div class="card-value" id="wlTotal">0</div>
      <div class="card-sub">Symbols on your list</div>
    </div>
    <div class="card">
      <div class="card-label">Gainers Today</div>
      <div class="card-value positive" id="wlGainers">0</div>
      <div class="card-sub">Up since open</div>
    </div>
    <div class="card">
      <div class="card-label">Losers Today</div>
      <div class="card-value negative" id="wlLosers">0</div>
      <div class="card-sub">Down since open</div>
    </div>
    <div class="card">
      <div class="card-label">Live Feed</div>
      <div class="card-value" style="font-size:16px;">
        <span class="badge badge-live">live</span>
      </div>
      <div class="card-sub">Updates every 20s</div>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>⭐ Your Watchlist</h2>
      <span class="badge" id="watchlistCount">0</span>
    </div>

    <div class="watchlist-grid" id="watchlistGrid">
      <div class="empty-state" style="padding:40px 20px;">Loading…</div>
    </div>
  </div>

</div>

<script src="<?= e(APP_URL) ?>/assets/js/watchlist.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>