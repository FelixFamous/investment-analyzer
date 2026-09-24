<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$KNOWN = [
    'BTC'=>'Bitcoin','ETH'=>'Ethereum','SOL'=>'Solana','BNB'=>'BNB','XRP'=>'XRP',
    'DOGE'=>'Dogecoin','ADA'=>'Cardano','PEPE'=>'Pepe',
    'AAPL'=>'Apple','MSFT'=>'Microsoft','NVDA'=>'NVIDIA','TSLA'=>'Tesla',
    'AMD'=>'AMD','META'=>'Meta','GOOGL'=>'Alphabet','AMZN'=>'Amazon','NFLX'=>'Netflix',
    'SPX'=>'S&P 500','NDX'=>'US 100','DJI'=>'Dow Jones','VIX'=>'VIX',
];

$pageTitle = 'Multi-Chart';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/multichart.css">

<div class="mc-layout-bar">
  <div class="mc-layout-group">
    <span class="mc-layout-label">Layout</span>
    <button type="button" class="mc-layout-btn" data-layout="single">▢ Single</button>
    <button type="button" class="mc-layout-btn active" data-layout="2x1">▥ 2 Columns</button>
    <button type="button" class="mc-layout-btn" data-layout="2x2">⊞ 2×2 Grid</button>
  </div>

  <div class="mc-controls">
    <div class="mc-control">
      <label>Interval</label>
      <select id="mcInterval">
        <option value="1m">1m</option>
        <option value="5m">5m</option>
        <option value="15m">15m</option>
        <option value="30m">30m</option>
        <option value="1h" selected>1H</option>
        <option value="4h">4H</option>
        <option value="1d">1D</option>
        <option value="1w">1W</option>
      </select>
    </div>

    <div class="mc-control">
      <label>Range</label>
      <select id="mcRange">
        <option value="1d">1D</option>
        <option value="7d" selected>7D</option>
        <option value="30d">30D</option>
        <option value="90d">90D</option>
        <option value="1y">1Y</option>
      </select>
    </div>

    <div class="mc-control">
      <label>Chart type</label>
      <select id="mcChartType">
        <option value="candles" selected>Candles</option>
        <option value="line">Line</option>
        <option value="area">Area</option>
      </select>
    </div>

    <button type="button" class="btn btn-sm" id="mcSync">🔗 Sync symbols</button>
    <button type="button" class="btn btn-sm btn-primary" id="mcSaveWs">💾 Save workspace</button>
    <button type="button" class="btn btn-sm" id="mcLoadWs">📂 Load workspace</button>
  </div>
</div>

<div class="mc-grid" id="mcGrid" data-layout="2x1">
  <!-- Charts injected by JS -->
</div>

<!-- Template for a chart pane -->
<template id="mcPaneTemplate">
  <div class="mc-pane">
    <div class="mc-pane-header">
      <select class="mc-symbol-select"></select>
      <span class="mc-pane-price">—</span>
      <span class="mc-pane-change">—</span>
      <button type="button" class="mc-pane-close" title="Clear this pane">✕</button>
    </div>
    <div class="mc-pane-chart"></div>
  </div>
</template>

<!-- Workspace modal -->
<div id="mcWorkspaceModal" class="modal-overlay" style="display:none;"></div>

<script>
  window.MC_API  = <?= json_encode(APP_URL) ?>;
  window.MC_CSRF = <?= json_encode(csrf_token()) ?>;
  window.MC_SYMBOLS = <?= json_encode(array_keys($KNOWN)) ?>;
  window.MC_DEFAULT_SYMBOLS = ['BTC', 'ETH', 'SOL', 'NVDA'];
</script>
<script src="https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"></script>
<script src="<?= e(APP_URL) ?>/assets/js/multichart.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>