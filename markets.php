<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/signal.php';

$user = require_login();

$signals = compute_signals();
$sigBySym = [];
foreach ($signals as $s) $sigBySym[$s['symbol']] = $s;

$pageTitle = 'Markets';
require __DIR__ . '/includes/header.php';
?>

<div class="live-ticker" id="liveTicker">
  <span class="text-dim" style="font-size:12px;">Loading live prices…</span>
</div>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Tracked Assets</div>
    <div class="card-value"><?= count(tracked_assets()) ?></div>
    <div class="card-sub">Stocks · Crypto · Indices</div>
  </div>
  <div class="card">
    <div class="card-label">Buying Signals</div>
    <div class="card-value positive">
      <?php
        $buyCount = 0;
        foreach ($signals as $s) if (str_contains($s['action'], 'BUY')) $buyCount++;
        echo $buyCount;
      ?>
    </div>
    <div class="card-sub">Bullish setups</div>
  </div>
  <div class="card">
    <div class="card-label">Selling Signals</div>
    <div class="card-value negative">
      <?php
        $sellCount = 0;
        foreach ($signals as $s) if (str_contains($s['action'], 'SELL')) $sellCount++;
        echo $sellCount;
      ?>
    </div>
    <div class="card-sub">Bearish setups</div>
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
    <h2>📊 All Markets</h2>
    <span class="badge">Click any row to buy</span>
  </div>
  <div class="watchlist" id="liveWatchlist">
    <div class="empty-state">Loading live prices…</div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>