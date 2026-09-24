<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';
require_once __DIR__ . '/includes/signal.php';

$user = require_login();

$KNOWN = [
    'BTC'  => ['name' => 'Bitcoin',            'type' => 'crypto', 'color' => '#f7931a'],
    'ETH'  => ['name' => 'Ethereum',           'type' => 'crypto', 'color' => '#627eea'],
    'SOL'  => ['name' => 'Solana',             'type' => 'crypto', 'color' => '#14f195'],
    'BNB'  => ['name' => 'BNB',                'type' => 'crypto', 'color' => '#f0b90b'],
    'XRP'  => ['name' => 'XRP',                'type' => 'crypto', 'color' => '#23292f'],
    'DOGE' => ['name' => 'Dogecoin',           'type' => 'crypto', 'color' => '#c2a633'],
    'ADA'  => ['name' => 'Cardano',            'type' => 'crypto', 'color' => '#0033ad'],
    'PEPE' => ['name' => 'Pepe',               'type' => 'crypto', 'color' => '#3d8130'],
    'AAPL' => ['name' => 'Apple Inc.',          'type' => 'stock', 'color' => '#a2aaad'],
    'MSFT' => ['name' => 'Microsoft Corp.',     'type' => 'stock', 'color' => '#0078d4'],
    'NVDA' => ['name' => 'NVIDIA Corp.',        'type' => 'stock', 'color' => '#76b900'],
    'TSLA' => ['name' => 'Tesla Inc.',          'type' => 'stock', 'color' => '#e82127'],
    'AMD'  => ['name' => 'Adv. Micro Devices',  'type' => 'stock', 'color' => '#ed1c24'],
    'META' => ['name' => 'Meta Platforms',      'type' => 'stock', 'color' => '#0866ff'],
    'GOOGL'=> ['name' => 'Alphabet Inc.',       'type' => 'stock', 'color' => '#4285f4'],
    'AMZN' => ['name' => 'Amazon.com Inc.',     'type' => 'stock', 'color' => '#ff9900'],
    'NFLX' => ['name' => 'Netflix Inc.',        'type' => 'stock', 'color' => '#e50914'],
    'SPX'  => ['name' => 'S&P 500',             'type' => 'index', 'color' => '#e50914'],
    'NDX'  => ['name' => 'US 100 Index',        'type' => 'index', 'color' => '#0ecb81'],
    'DJI'  => ['name' => 'Dow Jones Industrial','type' => 'index', 'color' => '#3b82f6'],
    'VIX'  => ['name' => 'Volatility Index',    'type' => 'index', 'color' => '#f0b90b'],
];

$symbol = strtoupper(trim((string)($_GET['symbol'] ?? 'BTC')));
if (!isset($KNOWN[$symbol])) {
    flash_set('error', 'Unknown asset.');
    redirect(APP_URL . '/markets.php');
}
$meta = $KNOWN[$symbol];
$isTradeable = in_array($meta['type'], ['crypto', 'stock'], true);

$livePrice = get_price($symbol);

$allSignals = compute_signals();
$mySignal = null;
foreach ($allSignals as $s) {
    if ($s['symbol'] === $symbol) { $mySignal = $s; break; }
}

$stmt = db()->prepare('SELECT quantity, avg_price, take_profit, stop_loss, tp_note, sl_note
                       FROM holdings WHERE user_id = ? AND symbol = ?');
$stmt->execute([$user['id'], $symbol]);
$pos = $stmt->fetch();

$positionValue = 0.0; $positionPnl = 0.0; $positionPnlPct = 0.0;
if ($pos && $livePrice) {
    $positionValue = (float)$pos['quantity'] * $livePrice;
    $positionPnl = $positionValue - ((float)$pos['quantity'] * (float)$pos['avg_price']);
    $positionPnlPct = (float)$pos['avg_price'] > 0 ? ($positionPnl / ((float)$pos['quantity'] * (float)$pos['avg_price'])) * 100 : 0;
}

$stmt = db()->prepare('SELECT side, quantity, price, total, pnl, created_at
                       FROM trades WHERE user_id = ? AND symbol = ?
                       ORDER BY created_at DESC LIMIT 15');
$stmt->execute([$user['id'], $symbol]);
$myTrades = $stmt->fetchAll();

$stmt = db()->prepare('SELECT COUNT(*) FROM price_alerts WHERE user_id = ? AND symbol = ? AND status = "active"');
$stmt->execute([$user['id'], $symbol]);
$myAlertCount = (int)$stmt->fetchColumn();

$stmt = db()->prepare('SELECT id, side, order_type, quantity, trigger_price, note, created_at
                       FROM pending_orders
                       WHERE user_id = ? AND symbol = ? AND status = "open"
                       ORDER BY created_at DESC LIMIT 20');
$stmt->execute([$user['id'], $symbol]);
$openOrders = $stmt->fetchAll();

$riskEquity = (float)$user['cash_balance'];
$stmtR = db()->prepare('SELECT symbol, quantity, avg_price FROM holdings WHERE user_id = ?');
$stmtR->execute([$user['id']]);
foreach ($stmtR->fetchAll() as $hh) {
    $pr = get_price($hh['symbol']) ?? (float)$hh['avg_price'];
    $riskEquity += (float)$hh['quantity'] * $pr;
}

$pageTitle = $symbol . ' · ' . $meta['name'];
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/asset.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/terminal.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/drawings.css">

<div style="margin-bottom:14px;">
  <a href="<?= e(APP_URL) ?>/markets.php" class="btn btn-sm">← All markets</a>
</div>

<div class="asset-header">
  <div class="asset-title">
    <div class="asset-icon" style="background:<?= e($meta['color']) ?>;">
      <?= e(substr($symbol, 0, 2)) ?>
    </div>
    <div>
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <h1><?= e($symbol) ?></h1>
        <span class="asset-type-badge asset-type-<?= e($meta['type']) ?>"><?= e(strtoupper($meta['type'])) ?></span>
        <?php if ($myAlertCount > 0): ?>
          <span class="asset-type-badge" style="background:rgba(240,185,11,0.12);color:var(--accent);">
            🔔 <?= $myAlertCount ?>
          </span>
        <?php endif; ?>
      </div>
      <div class="text-dim" style="font-size:13px;"><?= e($meta['name']) ?></div>
    </div>
  </div>

  <div class="asset-price-block" data-live-price="<?= e($symbol) ?>">
    <div class="asset-price" id="assetPrice">
      <?= $livePrice ? e(price_fmt($livePrice)) : '—' ?>
    </div>
    <div class="asset-change" id="assetChange">—</div>
  </div>
</div>

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Signal</div>
    <div class="card-value" style="font-size:18px;">
      <?php if ($mySignal): ?>
        <span class="prob-badge <?= str_contains($mySignal['action'],'SELL') ? 'low' : (str_contains($mySignal['action'],'BUY') ? '' : 'medium') ?>">
          <?= e($mySignal['action']) ?>
        </span>
      <?php else: ?><span class="text-dim">—</span><?php endif; ?>
    </div>
    <div class="card-sub"><?= $mySignal ? number_format($mySignal['confidence'] * 100, 0) . '% confidence' : 'No signal' ?></div>
  </div>
  <div class="card">
    <div class="card-label">RSI (14)</div>
    <div class="card-value" style="font-size:22px;"><?= $mySignal ? number_format($mySignal['rsi'], 1) : '—' ?></div>
    <div class="card-sub"><?php if ($mySignal) { if ($mySignal['rsi'] > 70) echo 'Overbought'; elseif ($mySignal['rsi'] < 30) echo 'Oversold'; else echo 'Neutral'; } ?></div>
  </div>
  <div class="card">
    <div class="card-label">Target</div>
    <div class="card-value" style="font-size:18px;font-family:var(--mono);">
      <?= $mySignal ? e(price_fmt($mySignal['target'])) : '—' ?>
    </div>
    <div class="card-sub">Next upside level</div>
  </div>
  <div class="card">
    <div class="card-label">Your Position</div>
    <?php if ($pos): ?>
      <div class="card-value <?= $positionPnl >= 0 ? 'positive' : 'negative' ?>" style="font-size:18px;">
        <?= ($positionPnl >= 0 ? '+' : '-') . e(usd(abs($positionPnl))) ?>
      </div>
      <div class="card-sub"><?= number_format((float)$pos['quantity'], 4) ?> @ <?= e(price_fmt((float)$pos['avg_price'])) ?></div>
    <?php else: ?>
      <div class="card-value text-dim" style="font-size:18px;">None</div>
      <div class="card-sub">You don't hold this asset</div>
    <?php endif; ?>
  </div>
</div>

<!-- ============================================================
     CHART PANEL
     ============================================================ -->
<div class="panel mb-16 chart-panel">
  <div class="panel-header">
    <h2>📈 Price Chart</h2>
    <span class="badge" id="rangeNote">—</span>
  </div>

  <!-- Timeframe tabs (candle size) -->
  <div class="tabs" id="timeframeTabs" style="margin:0 0 8px 0;padding:2px;overflow-x:auto;">
    <button class="tab" data-interval="1m"  style="padding:5px 12px;font-size:11px;">1m</button>
    <button class="tab" data-interval="5m"  style="padding:5px 12px;font-size:11px;">5m</button>
    <button class="tab" data-interval="15m" style="padding:5px 12px;font-size:11px;">15m</button>
    <button class="tab" data-interval="30m" style="padding:5px 12px;font-size:11px;">30m</button>
    <button class="tab" data-interval="1h"  style="padding:5px 12px;font-size:11px;">1H</button>
    <button class="tab" data-interval="4h"  style="padding:5px 12px;font-size:11px;">4H</button>
    <button class="tab" data-interval="1d"  style="padding:5px 12px;font-size:11px;">1D</button>
    <button class="tab active" data-interval="1w"  style="padding:5px 12px;font-size:11px;">1W</button>
  </div>

  <!-- Range tabs (how far back) -->
  <div class="tabs" id="rangeTabs" style="margin:0 0 10px 0;padding:2px;overflow-x:auto;">
    <button class="tab" data-range="1d"  style="padding:5px 12px;font-size:11px;">1D</button>
    <button class="tab active" data-range="7d" style="padding:5px 12px;font-size:11px;">7D</button>
    <button class="tab" data-range="30d" style="padding:5px 12px;font-size:11px;">30D</button>
    <button class="tab" data-range="90d" style="padding:5px 12px;font-size:11px;">90D</button>
    <button class="tab" data-range="1y"  style="padding:5px 12px;font-size:11px;">1Y</button>
    <button class="tab" data-range="2y"  style="padding:5px 12px;font-size:11px;">2Y</button>
    <button class="tab" data-range="5y"  style="padding:5px 12px;font-size:11px;">5Y</button>
    <button class="tab" data-range="max" style="padding:5px 12px;font-size:11px;">MAX</button>
  </div>

  <!-- Drawing toolbar -->
  <div class="drawing-toolbar">
    <span class="drawing-toolbar-label">Draw</span>
    <button type="button" class="drawing-tool" data-draw-tool="trendline">📏 Trendline</button>
    <button type="button" class="drawing-tool" data-draw-tool="hline">➖ Horizontal</button>
    <button type="button" class="drawing-tool" data-draw-tool="rect">▭ Rectangle</button>
    <button type="button" class="drawing-tool" data-draw-tool="fib">🌀 Fibonacci</button>
    <div class="drawing-colors">
      <button type="button" class="color-swatch active" data-color="#f0b90b" style="background:#f0b90b;"></button>
      <button type="button" class="color-swatch" data-color="#0ecb81" style="background:#0ecb81;"></button>
      <button type="button" class="color-swatch" data-color="#f6465d" style="background:#f6465d;"></button>
      <button type="button" class="color-swatch" data-color="#3b82f6" style="background:#3b82f6;"></button>
      <button type="button" class="color-swatch" data-color="#8b5cf6" style="background:#8b5cf6;"></button>
    </div>
    <button type="button" class="drawing-tool drawing-tool-clear" id="drawClearAll">🗑 Clear all</button>
    <span class="drawing-hint" id="drawingHint">Pick a tool to start drawing</span>
  </div>

  <!-- Chart type + tools -->
  <div class="chart-toolbar">
    <div class="chart-toolbar-group">
      <span class="chart-toolbar-label">Chart type</span>
      <button type="button" class="chart-type-btn active" data-chart-type="candles">🕯️ Candles</button>
      <button type="button" class="chart-type-btn" data-chart-type="heikin">HA</button>
      <button type="button" class="chart-type-btn" data-chart-type="renko">Renko</button>
      <button type="button" class="chart-type-btn" data-chart-type="linebreak">3LB</button>
      <button type="button" class="chart-type-btn" data-chart-type="line">Line</button>
      <button type="button" class="chart-type-btn" data-chart-type="area">Area</button>
    </div>

    <div class="chart-toolbar-group">
      <span class="chart-toolbar-label">Tools</span>
      <button type="button" class="chart-type-btn" data-pd="pdh">PDH</button>
      <button type="button" class="chart-type-btn" data-pd="pdl">PDL</button>
      <button type="button" class="chart-type-btn" data-session-toggle="1">Sessions</button>
      <button type="button" class="chart-type-btn" data-vp-toggle="1">Vol Profile</button>
      <button type="button" class="chart-type-btn replay-btn" data-replay-start="1">▶ Bar Replay</button>
    </div>
  </div>

  <div class="indicator-bar">
    <div class="indicator-group">
      <span class="indicator-label">Overlays</span>
      <button type="button" class="ind-btn" data-indicator="sma20" style="--ind-color:#f0b90b;">SMA 20</button>
      <button type="button" class="ind-btn" data-indicator="sma50" style="--ind-color:#3b82f6;">SMA 50</button>
      <button type="button" class="ind-btn" data-indicator="sma200" style="--ind-color:#8b5cf6;">SMA 200</button>
      <button type="button" class="ind-btn" data-indicator="ema12" style="--ind-color:#14f195;">EMA 12</button>
      <button type="button" class="ind-btn" data-indicator="ema26" style="--ind-color:#fb923c;">EMA 26</button>
      <button type="button" class="ind-btn" data-indicator="bb" style="--ind-color:#8b5cf6;">Bollinger</button>
    </div>
    <div class="indicator-group">
      <span class="indicator-label">Panes</span>
      <button type="button" class="ind-btn" data-indicator="rsi" style="--ind-color:#f0b90b;">RSI</button>
      <button type="button" class="ind-btn" data-indicator="macd" style="--ind-color:#3b82f6;">MACD</button>
    </div>
  </div>

  <div class="replay-controls" id="replayControls" style="display:none;">
    <div class="replay-info">
      <span>Bar Replay</span>
      <strong id="replayPos">— / —</strong>
    </div>
    <div class="replay-actions">
      <button type="button" class="btn btn-sm" data-replay-reset="1">↺ Reset</button>
      <button type="button" class="btn btn-sm" data-replay-step="1">⏭ Step</button>
      <button type="button" class="btn btn-sm btn-primary" data-replay-play="1">▶ / ⏸ Play</button>
      <span class="replay-speed-label">Speed</span>
      <button type="button" class="replay-speed" data-speed="800">0.5×</button>
      <button type="button" class="replay-speed active" data-speed="400">1×</button>
      <button type="button" class="replay-speed" data-speed="150">2×</button>
      <button type="button" class="btn btn-sm" data-replay-exit="1">✕ Exit</button>
    </div>
  </div>

  <div id="chartMeta" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;gap:10px;font-size:11px;color:var(--text-faint);font-family:var(--mono);flex-wrap:wrap;">
    <span id="chartCount">— candles</span>
    <div class="tabs" id="densityTabs" style="margin:0;padding:2px;">
      <button class="tab active" data-density="fit"    style="padding:3px 10px;font-size:10px;">FIT ALL</button>
      <button class="tab"        data-density="dense"  style="padding:3px 10px;font-size:10px;">DENSE</button>
      <button class="tab"        data-density="medium" style="padding:3px 10px;font-size:10px;">MEDIUM</button>
      <button class="tab"        data-density="detail" style="padding:3px 10px;font-size:10px;">DETAIL</button>
    </div>
  </div>

  <div id="chartLoading" class="empty-state" style="padding:80px 20px;">Loading chart…</div>
  <div id="chartWrap" style="display:none;">
    <div class="chart-and-profile">
      <div class="chart-wrapper">
        <div id="assetChart" style="width:100%;"></div>
        <canvas id="drawingCanvas"></canvas>
      </div>
      <div id="volumeProfile" class="volume-profile" style="display:none;"></div>
    </div>
    <div id="chartLegend" class="chart-legend">
      <span><strong>O</strong> <span id="lgO">—</span></span>
      <span><strong>H</strong> <span id="lgH">—</span></span>
      <span><strong>L</strong> <span id="lgL">—</span></span>
      <span><strong>C</strong> <span id="lgC">—</span></span>
    </div>
    <div id="sessionStrip" class="session-strip"></div>
  </div>
  <div id="chartError" class="empty-state" style="display:none;padding:60px 20px;color:var(--red);">Could not load chart.</div>
</div>

<!-- ============================================================
     TRADING TERMINAL
     ============================================================ -->
<?php if ($isTradeable): ?>
<div class="terminal-grid">
  <div class="panel order-panel">
    <div class="panel-header">
      <h2>⚡ Trade <?= e($symbol) ?></h2>
      <span class="badge">Virtual · demo only</span>
    </div>

    <div class="side-tabs">
      <button type="button" class="side-tab buy active" data-side="BUY">Buy</button>
      <button type="button" class="side-tab sell" data-side="SELL">Sell</button>
    </div>

    <div class="type-tabs">
      <button type="button" class="type-tab active" data-type="MARKET">Market</button>
      <button type="button" class="type-tab" data-type="LIMIT">Limit</button>
      <button type="button" class="type-tab" data-type="STOP">Stop</button>
    </div>

    <form id="orderForm">
      <input type="hidden" name="symbol" value="<?= e($symbol) ?>">
      <input type="hidden" name="side" id="orderSide" value="BUY">
      <input type="hidden" name="order_type" id="orderType" value="MARKET">

      <div class="form-group" id="priceGroup" style="display:none;">
        <label for="orderPrice">
          <span id="priceLabelText">Trigger price</span>
          <span class="text-dim" style="font-weight:400;font-size:11px;">(USD)</span>
        </label>
        <div class="input-with-hint">
          <input type="number" id="orderPrice" name="trigger_price" step="0.00000001" min="0" placeholder="0.00">
          <button type="button" class="input-hint" id="useMarketBtn">Use market</button>
        </div>
        <small class="text-dim" id="orderTypeHelp" style="display:block;margin-top:6px;font-size:11px;"></small>
      </div>

      <div class="form-group">
        <label for="orderQty">Quantity (<?= e($symbol) ?>)</label>
        <input type="number" id="orderQty" name="quantity" step="0.00000001" min="0.00000001" placeholder="0.00">
        <div class="pct-buttons">
          <button type="button" class="pct-btn" data-pct="25">25%</button>
          <button type="button" class="pct-btn" data-pct="50">50%</button>
          <button type="button" class="pct-btn" data-pct="75">75%</button>
          <button type="button" class="pct-btn" data-pct="100">MAX</button>
        </div>
      </div>

      <div class="sizing-panel" id="sizingPanel">
        <div class="sizing-header">
          <h3>📐 Position Sizing</h3>
          <span class="badge">Risk-aware</span>
        </div>

        <div class="form-group">
          <label for="calcStopPrice">
            Stop-loss price
            <span class="text-dim" style="font-weight:400;font-size:11px;">(to size the trade)</span>
          </label>
          <div class="input-with-hint">
            <input type="number" id="calcStopPrice" step="0.00000001" min="0" placeholder="0.00">
            <button type="button" class="input-hint" id="useCurrentAsStop">5% below</button>
          </div>
        </div>

        <div class="sizing-results" id="sizingResults">
          <div class="sizing-row"><span>Risk budget</span><strong id="sizingBudget">—</strong></div>
          <div class="sizing-row"><span>Risk per unit</span><strong id="sizingPerUnit">—</strong></div>
          <div class="sizing-row highlight"><span>Suggested quantity</span><strong id="sizingQty">—</strong></div>
          <div class="sizing-row"><span>Position value</span><strong id="sizingValue">—</strong></div>
          <div class="sizing-row"><span>Max loss if stop hits</span><strong id="sizingMaxLoss" style="color:var(--red);">—</strong></div>
        </div>

        <div id="sizingWarning" class="sizing-warning" style="display:none;"></div>

        <button type="button" class="btn btn-block" id="applySizingBtn" style="margin-top:10px;">
          Apply Suggested Quantity
        </button>
      </div>

      <div class="order-summary">
        <div class="summary-row">
          <span>Available</span>
          <strong id="availCash"><?= e(usd((float)$user['cash_balance'])) ?></strong>
        </div>
        <?php if ($pos): ?>
        <div class="summary-row">
          <span>You hold</span>
          <strong id="heldQty"><?= number_format((float)$pos['quantity'], 6) ?> <?= e($symbol) ?></strong>
        </div>
        <?php endif; ?>
        <div class="summary-row">
          <span id="totalLabel">Estimated total</span>
          <strong id="estTotal">$0.00</strong>
        </div>
        <div class="summary-row total">
          <span id="finalLabel">You pay</span>
          <strong id="finalTotal" style="color:var(--accent);">$0.00</strong>
        </div>
      </div>

      <div class="form-group" style="margin-top:14px;">
        <label for="orderNote">Note (optional)</label>
        <input type="text" id="orderNote" name="note" maxlength="200" placeholder="e.g. Taking profits">
      </div>

      <button type="button" class="btn btn-primary btn-block" id="placeOrderBtn" style="margin-top:14px;">
        Place Market Order
      </button>

      <div id="orderFeedback" class="order-feedback" style="display:none;"></div>
    </form>

    <?php if ($pos):
      $hasTp = !empty($pos['take_profit']);
      $hasSl = !empty($pos['stop_loss']);
    ?>
    <div class="tp-sl-panel" id="tpSlPanel">
      <div class="tp-sl-header">
        <h3>🛡️ Auto Sell (TP / SL)</h3>
        <span class="badge"><?= ($hasTp || $hasSl) ? 'active' : 'not set' ?></span>
      </div>

      <p class="text-dim" style="font-size:12px;line-height:1.55;margin-bottom:12px;">
        Automatically sell your entire <strong><?= e($symbol) ?></strong> position when the price hits your target or stop level.
      </p>

      <div class="tp-sl-grid">
        <div class="form-group">
          <label for="takeProfit">🎯 Take Profit</label>
          <input type="number" id="takeProfit" step="0.00000001" min="0"
                 placeholder="<?= $livePrice ? e(number_format($livePrice * 1.10, 8, '.', '')) : '0.00' ?>"
                 value="<?= $hasTp ? e(rtrim(rtrim(number_format((float)$pos['take_profit'], 8, '.', ''), '0'), '.')) : '' ?>">
          <small class="text-dim" style="display:block;margin-top:4px;font-size:10.5px;">Sells when price rises to this level.</small>
        </div>

        <div class="form-group">
          <label for="stopLoss">🛑 Stop Loss</label>
          <input type="number" id="stopLoss" step="0.00000001" min="0"
                 placeholder="<?= $livePrice ? e(number_format($livePrice * 0.95, 8, '.', '')) : '0.00' ?>"
                 value="<?= $hasSl ? e(rtrim(rtrim(number_format((float)$pos['stop_loss'], 8, '.', ''), '0'), '.')) : '' ?>">
          <small class="text-dim" style="display:block;margin-top:4px;font-size:10.5px;">Sells when price drops to this level.</small>
        </div>
      </div>

      <div style="display:flex;gap:8px;margin-top:6px;">
        <button type="button" class="btn btn-primary" id="saveTpSlBtn" style="flex:1;">
          <?= ($hasTp || $hasSl) ? 'Update Rules' : 'Set Rules' ?>
        </button>
        <?php if ($hasTp || $hasSl): ?>
          <button type="button" class="btn" id="clearTpSlBtn" style="flex:0 0 auto;">Clear</button>
        <?php endif; ?>
      </div>

      <?php if ($hasTp || $hasSl): ?>
        <div class="tp-sl-status">
          <?php if ($hasTp): ?>
            <div class="tp-sl-line tp">
              <span>🎯 Take-profit at</span>
              <strong><?= e(price_fmt((float)$pos['take_profit'])) ?></strong>
              <?php if ($livePrice): ?>
                <span class="text-dim">(<?= ((float)$pos['take_profit'] / $livePrice - 1) * 100 >= 0 ? '+' : '' ?><?= number_format(((float)$pos['take_profit'] / $livePrice - 1) * 100, 2) ?>% away)</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if ($hasSl): ?>
            <div class="tp-sl-line sl">
              <span>🛑 Stop-loss at</span>
              <strong><?= e(price_fmt((float)$pos['stop_loss'])) ?></strong>
              <?php if ($livePrice): ?>
                <span class="text-dim">(<?= number_format(((float)$pos['stop_loss'] / $livePrice - 1) * 100, 2) ?>% away)</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <div>
    <div class="panel mb-16">
      <div class="panel-header">
        <h2>📌 Open Orders</h2>
        <span class="badge" id="openOrdersBadge"><?= count($openOrders) ?></span>
      </div>
      <div id="openOrdersList">
        <?php if (!$openOrders): ?>
          <div class="empty-state" style="padding:24px 12px;">No open orders.</div>
        <?php else: ?>
          <?php foreach ($openOrders as $o):
            $isBuy = $o['side'] === 'BUY';
          ?>
            <div class="open-order-row" data-order-id="<?= (int)$o['id'] ?>">
              <div class="open-order-icon <?= $isBuy ? 'buy' : 'sell' ?>"><?= $isBuy ? '▲' : '▼' ?></div>
              <div class="open-order-body">
                <div class="open-order-headline">
                  <span class="sym"><?= e($symbol) ?></span>
                  <span class="tag <?= $isBuy ? 'buy' : 'sell' ?>"><?= e($o['side']) ?></span>
                  <span class="tag neutral"><?= e($o['order_type']) ?></span>
                </div>
                <div class="open-order-meta">
                  <span><?= number_format((float)$o['quantity'], 6) ?> @ <?= e(price_fmt((float)$o['trigger_price'])) ?></span>
                  <span>·</span>
                  <span><?= e(time_ago($o['created_at'])) ?></span>
                </div>
                <?php if (!empty($o['note'])): ?>
                  <div class="open-order-note">"<?= e($o['note']) ?>"</div>
                <?php endif; ?>
              </div>
              <button type="button" class="order-cancel" data-cancel-order="<?= (int)$o['id'] ?>" title="Cancel">✕</button>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header">
        <h2>📋 Recent Trades</h2>
        <span class="badge"><?= count($myTrades) ?></span>
      </div>
      <?php if (!$myTrades): ?>
        <div class="empty-state">No trades on this asset yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Time</th><th>Side</th><th>Qty</th><th>Price</th><th>P&amp;L</th></tr>
            </thead>
            <tbody>
              <?php foreach ($myTrades as $t): ?>
                <tr>
                  <td class="text-dim" style="font-size:11px;font-family:var(--mono);"><?= e(fmt_time($t['created_at'], 'M j, H:i')) ?></td>
                  <td style="font-weight:700;color:<?= $t['side']==='BUY' ? 'var(--green)' : 'var(--red)' ?>;"><?= e($t['side']) ?></td>
                  <td style="font-family:var(--mono);font-size:12px;"><?= number_format((float)$t['quantity'], 4) ?></td>
                  <td style="font-family:var(--mono);font-size:12px;"><?= e(price_fmt((float)$t['price'])) ?></td>
                  <td>
                    <?php if ($t['pnl'] === null): ?>
                      <span class="text-dim">—</span>
                    <?php else: $p = (float)$t['pnl']; ?>
                      <span style="font-weight:700;font-family:var(--mono);font-size:12px;color:<?= $p>=0?'var(--green)':'var(--red)' ?>;">
                        <?= ($p >= 0 ? '+' : '-') . e(usd(abs($p))) ?>
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
  window.ASSET_SYMBOL = <?= json_encode($symbol) ?>;
  window.ASSET_API = <?= json_encode(APP_URL) ?>;
  window.TERMINAL_DATA = {
    symbol: <?= json_encode($symbol) ?>,
    side: 'BUY',
    orderType: 'MARKET',
    currentPrice: <?= json_encode($livePrice) ?>,
    cash: <?= json_encode((float)$user['cash_balance']) ?>,
    held: <?= json_encode($pos ? (float)$pos['quantity'] : 0) ?>,
    csrf: <?= json_encode(csrf_token()) ?>,
    equity: <?= json_encode($riskEquity) ?>,
    riskPerTradePct: <?= json_encode((float)$user['risk_per_trade_pct']) ?>,
    maxPortfolioHeat: <?= json_encode((float)$user['max_portfolio_heat']) ?>,
    maxPositionPct: <?= json_encode((float)$user['max_position_pct']) ?>
  };
</script>
<script src="https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"></script>
<script src="<?= e(APP_URL) ?>/assets/js/asset-chart.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/terminal.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/drawings.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>