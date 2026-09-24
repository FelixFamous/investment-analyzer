<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'Market Scanner';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/calculator.css">

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Assets Scanned</div>
    <div class="card-value" id="scTotal">—</div>
    <div class="card-sub">Across all markets</div>
  </div>
  <div class="card">
    <div class="card-label">Matches</div>
    <div class="card-value positive" id="scMatches">—</div>
    <div class="card-sub">Passing your filters</div>
  </div>
  <div class="card">
    <div class="card-label">Bullish Signals</div>
    <div class="card-value positive" id="scBulls">—</div>
    <div class="card-sub">Uptrend + RSI &gt; 50</div>
  </div>
  <div class="card">
    <div class="card-label">Bearish Signals</div>
    <div class="card-value negative" id="scBears">—</div>
    <div class="card-sub">Downtrend + RSI &lt; 50</div>
  </div>
</div>

<div class="panel mb-16">
  <div class="panel-header">
    <h2>🔍 Market Scanner</h2>
    <span class="badge">Find setups across all assets</span>
  </div>

  <div class="scanner-toolbar">
    <div class="scanner-filter">
      <label>Preset scan</label>
      <select id="scPreset">
        <option value="all">All assets</option>
        <option value="trend_bull">Bullish trend (SMA20 &gt; SMA50 + RSI &gt; 50)</option>
        <option value="trend_bear">Bearish trend (SMA20 &lt; SMA50 + RSI &lt; 50)</option>
        <option value="oversold">RSI oversold (&lt; 30)</option>
        <option value="overbought">RSI overbought (&gt; 70)</option>
        <option value="momentum_up">Strong upward momentum</option>
        <option value="momentum_down">Strong downward momentum</option>
        <option value="high_vol">High volatility</option>
      </select>
    </div>

    <div class="scanner-filter">
      <label>Asset class</label>
      <select id="scType">
        <option value="">All</option>
        <option value="crypto">Crypto</option>
        <option value="stock">Stocks</option>
        <option value="index">Indices</option>
      </select>
    </div>

    <div class="scanner-filter">
      <label>Min change %</label>
      <input type="number" id="scMinChange" step="0.1" placeholder="e.g. -5">
    </div>

    <div class="scanner-filter">
      <label>Max RSI</label>
      <input type="number" id="scMaxRsi" step="1" placeholder="e.g. 40">
    </div>

    <div class="scanner-actions">
      <button type="button" class="btn btn-primary" id="scRun">▶ Run Scan</button>
      <button type="button" class="btn" id="scReset">Reset</button>
    </div>
  </div>

  <div id="scLoading" class="empty-state" style="padding:40px;">Loading market data…</div>
  <div class="scanner-results" id="scResults"></div>
</div>

<script>
  window.SC_API = <?= json_encode(APP_URL) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/scanner.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>