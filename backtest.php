<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$KNOWN = [
    'BTC'=>'Bitcoin','ETH'=>'Ethereum','SOL'=>'Solana','BNB'=>'BNB','XRP'=>'XRP',
    'DOGE'=>'Dogecoin','ADA'=>'Cardano','PEPE'=>'Pepe',
    'AAPL'=>'Apple','MSFT'=>'Microsoft','NVDA'=>'NVIDIA','TSLA'=>'Tesla',
    'AMD'=>'AMD','META'=>'Meta','GOOGL'=>'Alphabet','AMZN'=>'Amazon','NFLX'=>'Netflix',
];

$pageTitle = 'Backtesting';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/strategy.css">

<div id="backtestPage">

  <div class="panel mb-16">
    <div class="panel-header">
      <h2>🧪 Backtest a Strategy</h2>
      <span class="badge">Historical simulation</span>
    </div>

    <form id="backtestForm" class="bt-form">
      <div class="bt-form-row">
        <div class="form-group">
          <label for="btSymbol">Asset</label>
          <select id="btSymbol" required>
            <?php foreach ($KNOWN as $sym => $name): ?>
              <option value="<?= e($sym) ?>"><?= e($sym) ?> — <?= e($name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="btRange">Time range</label>
          <select id="btRange" required>
            <option value="30d">Last 30 days</option>
            <option value="90d">Last 90 days</option>
            <option value="1y" selected>Last 1 year</option>
            <option value="2y">Last 2 years</option>
            <option value="5y">Last 5 years</option>
            <option value="max">Maximum available</option>
          </select>
        </div>

        <div class="form-group">
          <label for="btStrategy">Strategy</label>
          <select id="btStrategy" required>
            <optgroup label="Presets" id="btPresets"></optgroup>
            <optgroup label="Your strategies" id="btMine"></optgroup>
          </select>
        </div>

        <div class="form-group">
          <label for="btBalance">Starting balance ($)</label>
          <input type="number" id="btBalance" min="100" max="10000000" step="100" value="100000">
        </div>
      </div>

      <button type="submit" class="btn btn-primary" id="btRunBtn" style="margin-top:6px;">
        ▶ Run Backtest
      </button>
      <div id="btError" class="order-feedback" style="display:none;margin-top:12px;"></div>
    </form>
  </div>

  <div id="btLoading" style="display:none;padding:60px 20px;text-align:center;">
    <div class="bt-spinner"></div>
    <div style="margin-top:16px;color:var(--text-dim);font-size:13px;">Running simulation…</div>
  </div>

  <div id="btResults" style="display:none;"></div>

</div>

<script>
  window.BT_API  = <?= json_encode(APP_URL) ?>;
  window.BT_CSRF = <?= json_encode(csrf_token()) ?>;
</script>
<script src="https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"></script>
<script src="<?= e(APP_URL) ?>/assets/js/backtest.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>