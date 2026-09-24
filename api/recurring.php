<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$KNOWN_SYMBOLS = [
    'BTC'=>'Bitcoin','ETH'=>'Ethereum','SOL'=>'Solana','BNB'=>'BNB','XRP'=>'XRP',
    'DOGE'=>'Dogecoin','ADA'=>'Cardano','PEPE'=>'Pepe',
    'AAPL'=>'Apple','MSFT'=>'Microsoft','NVDA'=>'NVIDIA','TSLA'=>'Tesla',
    'AMD'=>'AMD','META'=>'Meta','GOOGL'=>'Alphabet','AMZN'=>'Amazon','NFLX'=>'Netflix',
];

$pageTitle = 'Recurring Buys';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/recurring.css">

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Active Plans</div>
    <div class="card-value" id="dcaActive">—</div>
    <div class="card-sub">Auto-buy schedules</div>
  </div>
  <div class="card">
    <div class="card-label">Monthly Commitment</div>
    <div class="card-value" id="dcaMonthly" style="font-size:20px;">—</div>
    <div class="card-sub">Estimated per month</div>
  </div>
  <div class="card">
    <div class="card-label">Total Invested</div>
    <div class="card-value" id="dcaInvested" style="font-size:20px;">—</div>
    <div class="card-sub">Via DCA rules</div>
  </div>
  <div class="card">
    <div class="card-label">Next Run</div>
    <div class="card-value" id="dcaNext" style="font-size:15px;">—</div>
    <div class="card-sub" id="dcaNextWhen">—</div>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>💹 New Recurring Buy</h2>
      <span class="badge">Dollar-cost averaging</span>
    </div>

    <form id="dcaForm">
      <div class="form-group">
        <label for="dcaSymbol">Asset</label>
        <select id="dcaSymbol" required>
          <?php foreach ($KNOWN_SYMBOLS as $s => $n): ?>
            <option value="<?= e($s) ?>"><?= e($s) ?> — <?= e($n) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="dcaAmount">Amount per buy (USD)</label>
        <input type="number" id="dcaAmount" min="10" max="100000" step="1" value="100" required>
      </div>

      <div class="form-group">
        <label for="dcaFreq">Frequency</label>
        <select id="dcaFreq" required>
          <option value="daily">Daily</option>
          <option value="weekly" selected>Weekly</option>
          <option value="monthly">Monthly</option>
        </select>
      </div>

      <div class="form-group" id="dowWrap">
        <label for="dcaDow">Day of week</label>
        <select id="dcaDow">
          <option value="1" selected>Monday</option>
          <option value="2">Tuesday</option>
          <option value="3">Wednesday</option>
          <option value="4">Thursday</option>
          <option value="5">Friday</option>
          <option value="6">Saturday</option>
          <option value="0">Sunday</option>
        </select>
      </div>

      <div class="form-group" id="domWrap" style="display:none;">
        <label for="dcaDom">Day of month (1–28)</label>
        <input type="number" id="dcaDom" min="1" max="28" value="1">
      </div>

      <div class="form-group">
        <label for="dcaHour">Hour (UTC)</label>
        <input type="number" id="dcaHour" min="0" max="23" value="9">
      </div>

      <button type="submit" class="btn btn-primary btn-block" id="dcaSubmit">
        Schedule Recurring Buy
      </button>
      <div id="dcaFeedback" class="order-feedback" style="display:none;margin-top:12px;"></div>
    </form>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>📋 Your Plans</h2>
      <span class="badge" id="dcaCount">0</span>
    </div>
    <div id="dcaList">
      <div class="empty-state">Loading…</div>
    </div>
  </div>
</div>

<script>
  window.DCA_API  = <?= json_encode(APP_URL) ?>;
  window.DCA_CSRF = <?= json_encode(csrf_token()) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/recurring.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>