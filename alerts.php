<?php
require_once __DIR__ . '/includes/auth.php';

$user = require_login();

$preSymbol = strtoupper(trim((string)($_GET['symbol'] ?? '')));

$KNOWN_SYMBOLS = [
    'BTC'  => 'Bitcoin',
    'ETH'  => 'Ethereum',
    'SOL'  => 'Solana',
    'BNB'  => 'BNB',
    'XRP'  => 'XRP',
    'DOGE' => 'Dogecoin',
    'ADA'  => 'Cardano',
    'PEPE' => 'Pepe',
    'AAPL' => 'Apple',
    'MSFT' => 'Microsoft',
    'NVDA' => 'NVIDIA',
    'TSLA' => 'Tesla',
    'AMD'  => 'AMD',
    'META' => 'Meta',
    'GOOGL'=> 'Alphabet',
    'AMZN' => 'Amazon',
    'NFLX' => 'Netflix',
    'SPX'  => 'S&P 500',
    'NDX'  => 'US 100',
    'DJI'  => 'Dow Jones',
    'VIX'  => 'VIX',
];

$pageTitle = 'Price Alerts';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/alerts.css">

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Active Alerts</div>
    <div class="card-value" id="statActive">—</div>
    <div class="card-sub">Watching for triggers</div>
  </div>
  <div class="card">
    <div class="card-label">Triggered</div>
    <div class="card-value positive" id="statTriggered">—</div>
    <div class="card-sub">Alerts that fired</div>
  </div>
  <div class="card">
    <div class="card-label">Cancelled</div>
    <div class="card-value" id="statCancelled">—</div>
    <div class="card-sub">Manually cancelled</div>
  </div>
  <div class="card">
    <div class="card-label">Checker</div>
    <div class="card-value" style="font-size:16px;">
      <span class="badge badge-live">live</span>
    </div>
    <div class="card-sub">Evaluating every 30s</div>
  </div>
</div>

<div class="main-grid">
  <!-- Left: Create alert form -->
  <div class="panel">
    <div class="panel-header">
      <h2>🔔 Create Alert</h2>
      <span class="badge">Max 30 active</span>
    </div>

    <form id="createAlertForm">
      <div class="form-group">
        <label for="alertSymbol">Symbol</label>
        <select id="alertSymbol" name="symbol" required>
          <?php foreach ($KNOWN_SYMBOLS as $sym => $name): ?>
            <option value="<?= e($sym) ?>" <?= $preSymbol === $sym ? 'selected' : '' ?>>
              <?= e($sym) ?> — <?= e($name) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Trigger when price is</label>
        <div class="radio-row">
          <label class="radio-card">
            <input type="radio" name="condition_type" value="above" checked>
            <span class="radio-card-body">
              <span class="radio-card-icon">📈</span>
              <span>
                <strong>Above</strong>
                <small>Fires when price rises to target</small>
              </span>
            </span>
          </label>
          <label class="radio-card">
            <input type="radio" name="condition_type" value="below">
            <span class="radio-card-body">
              <span class="radio-card-icon">📉</span>
              <span>
                <strong>Below</strong>
                <small>Fires when price drops to target</small>
              </span>
            </span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label for="alertTarget">Target price (USD)</label>
        <input type="number" id="alertTarget" name="target_price" step="0.00000001" min="0.00000001" required placeholder="0.00">
        <small class="text-dim" id="alertCurrent" style="display:block;margin-top:6px;font-size:11px;">
          Current: —
        </small>
      </div>

      <div class="form-group">
        <label for="alertNote">Note (optional)</label>
        <input type="text" id="alertNote" name="note" maxlength="200" placeholder="e.g. Take-profit level">
      </div>

      <button type="submit" class="btn btn-primary btn-block" id="createAlertBtn">
        Create Alert
      </button>
    </form>

    <div class="alert-tip">
      💡 <strong>Tip:</strong> Set alerts to catch breakouts and support levels.
      Alerts are checked every 30 seconds while you have the app open.
    </div>
  </div>

  <!-- Right: Active alerts list -->
  <div class="panel">
    <div class="panel-header">
      <h2>📋 Your Alerts</h2>
      <div style="display:flex;gap:8px;align-items:center;">
        <span class="badge" id="alertListCount">0</span>
        <button type="button" class="export-btn" data-export="trades">
          📥 Export Data
        </button>
      </div>
    </div>

    <div class="tabs" id="alertTabs" style="margin-bottom:12px;padding:2px;">
      <button class="tab active" data-status="active"    style="padding:5px 12px;font-size:11px;">Active</button>
      <button class="tab"        data-status="triggered" style="padding:5px 12px;font-size:11px;">Triggered</button>
      <button class="tab"        data-status="cancelled" style="padding:5px 12px;font-size:11px;">Cancelled</button>
    </div>

    <div id="alertList">
      <div class="empty-state">Loading…</div>
    </div>
  </div>
</div>

<script>
  window.ALERTS_API = <?= json_encode(APP_URL) ?>;
  window.ALERTS_CSRF = <?= json_encode(csrf_token()) ?>;
  window.ALERTS_PRE_SYMBOL = <?= json_encode($preSymbol) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/alerts.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>