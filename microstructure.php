<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';

$user = require_login();

$KNOWN = [
    'BTC'=>'Bitcoin','ETH'=>'Ethereum','SOL'=>'Solana','BNB'=>'BNB','XRP'=>'XRP',
    'DOGE'=>'Dogecoin','ADA'=>'Cardano','PEPE'=>'Pepe',
    'AAPL'=>'Apple','MSFT'=>'Microsoft','NVDA'=>'NVIDIA','TSLA'=>'Tesla',
    'AMD'=>'AMD','META'=>'Meta','GOOGL'=>'Alphabet','AMZN'=>'Amazon','NFLX'=>'Netflix',
];

$symbol = strtoupper(trim((string)($_GET['symbol'] ?? 'BTC')));
if (!isset($KNOWN[$symbol])) $symbol = 'BTC';

$livePrice = get_price($symbol);

$pageTitle = 'Market Depth · ' . $symbol;
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/microstructure.css">

<div style="margin-bottom:14px;">
  <a href="<?= e(APP_URL) ?>/asset.php?symbol=<?= e($symbol) ?>" class="btn btn-sm">← Back to <?= e($symbol) ?></a>
</div>

<!-- Symbol selector + live price -->
<div class="ms-header">
  <div class="ms-symbol-picker">
    <label>Asset</label>
    <select id="msSymbol" onchange="window.location.href='microstructure.php?symbol=' + this.value">
      <?php foreach ($KNOWN as $sym => $name): ?>
        <option value="<?= e($sym) ?>" <?= $sym === $symbol ? 'selected' : '' ?>>
          <?= e($sym) ?> — <?= e($name) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="ms-live-price">
    <div class="ms-live-label">Live Price</div>
    <div class="ms-live-value" id="msPrice"><?= $livePrice ? e(price_fmt($livePrice)) : '—' ?></div>
    <div class="ms-live-change" id="msChange">—</div>
  </div>
</div>

<!-- Microstructure grid -->
<div class="ms-grid">

  <!-- ORDER BOOK -->
  <div class="panel ms-panel">
    <div class="panel-header">
      <h2>📊 Order Book</h2>
      <span class="badge badge-live" id="msDepthBadge">live</span>
    </div>

    <div class="ob-header">
      <div>Price (USD)</div>
      <div>Size</div>
      <div>Total</div>
    </div>

    <div class="ob-asks" id="obAsks"></div>

    <div class="ob-spread" id="obSpread">
      <div class="ob-spread-mid" id="obMid">—</div>
      <div class="ob-spread-label" id="obSpreadLabel">Spread —</div>
    </div>

    <div class="ob-bids" id="obBids"></div>

    <div class="ob-stats" id="obStats">
      <div><span>Bid depth</span><strong id="obBidDepth">—</strong></div>
      <div><span>Ask depth</span><strong id="obAskDepth">—</strong></div>
      <div><span>Imbalance</span><strong id="obImbalance">—</strong></div>
    </div>
  </div>

  <!-- TIME & SALES -->
  <div class="panel ms-panel">
    <div class="panel-header">
      <h2>⚡ Time &amp; Sales</h2>
      <span class="badge" id="tsCount">— trades</span>
    </div>

    <div class="ts-header">
      <div>Time</div>
      <div>Price</div>
      <div>Size</div>
    </div>

    <div class="ts-tape" id="tsTape"></div>

    <div class="ts-summary" id="tsSummary">
      <div><span>Buy volume</span><strong class="text-green" id="tsBuyVol">—</strong></div>
      <div><span>Sell volume</span><strong class="text-red" id="tsSellVol">—</strong></div>
      <div><span>Delta</span><strong id="tsDelta">—</strong></div>
    </div>
  </div>

  <!-- MARKET INFO -->
  <div class="panel ms-panel">
    <div class="panel-header">
      <h2>📈 Market Info</h2>
    </div>

    <div class="mi-row"><span>Symbol</span><strong id="miSymbol"><?= e($symbol) ?></strong></div>
    <div class="mi-row"><span>Name</span><strong><?= e($KNOWN[$symbol]) ?></strong></div>
    <div class="mi-row"><span>Best Bid</span><strong class="text-green" id="miBestBid">—</strong></div>
    <div class="mi-row"><span>Best Ask</span><strong class="text-red" id="miBestAsk">—</strong></div>
    <div class="mi-row"><span>Spread</span><strong id="miSpread">—</strong></div>
    <div class="mi-row"><span>Mid Price</span><strong id="miMid">—</strong></div>
    <div class="mi-row"><span>24h Volume</span><strong id="miVol">—</strong></div>
    <div class="mi-row"><span>Session</span><strong id="miSession">—</strong></div>

    <div class="mi-divider"></div>

    <div class="mi-row"><span>Your Cash</span><strong id="miCash"><?= e(usd((float)$user['cash_balance'])) ?></strong></div>
    <div class="mi-row"><span>Your Holdings</span><strong id="miHeld">—</strong></div>
  </div>
</div>

<script>
  window.MS_SYMBOL = <?= json_encode($symbol) ?>;
  window.MS_API    = <?= json_encode(APP_URL) ?>;
  window.MS_PRICE  = <?= json_encode($livePrice) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/microstructure.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>