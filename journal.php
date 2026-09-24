<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$KNOWN_SYMBOLS = ['BTC','ETH','SOL','BNB','XRP','DOGE','ADA','PEPE','AAPL','MSFT','NVDA','TSLA','AMD','META','GOOGL','AMZN','NFLX'];

$pageTitle = 'Trading Journal';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/intelligence.css">

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Total Trades</div>
    <div class="card-value" id="jStatTotal">—</div>
    <div class="card-sub">All executions</div>
  </div>
  <div class="card">
    <div class="card-label">Reviewed</div>
    <div class="card-value positive" id="jStatReviewed">—</div>
    <div class="card-sub">With notes / tags</div>
  </div>
  <div class="card">
    <div class="card-label">Unreviewed</div>
    <div class="card-value negative" id="jStatUnreviewed">—</div>
    <div class="card-sub">Needs your attention</div>
  </div>
  <div class="card">
    <div class="card-label">Reviewed Rate</div>
    <div class="card-value" id="jStatRate">—</div>
    <div class="card-sub">Journalling discipline</div>
  </div>
</div>

<div class="panel">
  <div class="panel-header">
    <h2>📓 Trade Journal</h2>
    <span class="badge" id="jCount">0</span>
  </div>

  <div class="journal-filters">
    <div class="journal-filter-group">
      <label>Symbol</label>
      <select id="jfSymbol">
        <option value="">All</option>
        <?php foreach ($KNOWN_SYMBOLS as $s): ?>
          <option value="<?= e($s) ?>"><?= e($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="journal-filter-group">
      <label>Side</label>
      <select id="jfSide">
        <option value="">Both</option>
        <option value="BUY">Buy</option>
        <option value="SELL">Sell</option>
      </select>
    </div>

    <div class="journal-filter-group">
      <label>Tag contains</label>
      <input type="text" id="jfTag" placeholder="e.g. breakout">
    </div>

    <div class="journal-filter-group">
      <label>From</label>
      <input type="date" id="jfFrom">
    </div>

    <div class="journal-filter-group">
      <label>To</label>
      <input type="date" id="jfTo">
    </div>

    <div class="journal-filter-group">
      <label>&nbsp;</label>
      <button type="button" class="btn btn-sm btn-primary" id="jfApply">Apply</button>
    </div>

    <div class="journal-filter-group">
      <label>&nbsp;</label>
      <button type="button" class="btn btn-sm" id="jfClear">Clear</button>
    </div>

    <div class="journal-filter-group" style="margin-left:auto;">
      <label>&nbsp;</label>
      <label class="journal-checkbox">
        <input type="checkbox" id="jfOnlyUnreviewed">
        <span>Only unreviewed</span>
      </label>
    </div>
  </div>

  <div id="journalList" class="journal-list">
    <div class="empty-state" style="padding:40px;">Loading trades…</div>
  </div>
</div>

<!-- Journal edit modal (hidden) -->
<div id="journalModal" class="modal-overlay" style="display:none;"></div>

<script>
  window.JOURNAL_API  = <?= json_encode(APP_URL) ?>;
  window.JOURNAL_CSRF = <?= json_encode(csrf_token()) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/journal.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>