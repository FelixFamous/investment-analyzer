<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/signal.php';

$user = require_login();

// Record today's equity snapshot (idempotent — one row per day)
snapshot_portfolio();

$signals = compute_signals();
$grouped = group_signals($signals);

$stmt = db()->prepare('SELECT symbol, quantity, avg_price FROM holdings WHERE user_id = ?');
$stmt->execute([$user['id']]);
$rawHoldings = $stmt->fetchAll();

$holdings = [];
$holdingsValue = 0.0;
foreach ($rawHoldings as $h) {
    $live = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $val  = (float)$h['quantity'] * $live;
    $cost = (float)$h['quantity'] * (float)$h['avg_price'];
    $holdings[] = [
        'symbol' => $h['symbol'],
        'qty'    => (float)$h['quantity'],
        'avg'    => (float)$h['avg_price'],
        'price'  => $live,
        'value'  => $val,
        'pnl'    => $val - $cost,
    ];
    $holdingsValue += $val;
}

$holdingsAnalysis = analyze_holdings($holdings, $signals);

$stmt = db()->prepare(
    'SELECT symbol, side, quantity, price, total, pnl, created_at
     FROM trades WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 20'
);
$stmt->execute([$user['id']]);
$trades = $stmt->fetchAll();

$cash     = (float)$user['cash_balance'];
$equity   = $cash + $holdingsValue;
$unrealized = 0.0;
foreach ($holdings as $h) $unrealized += $h['pnl'];

$pageTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>

<div class="live-ticker" id="liveTicker">
  <span class="text-dim" style="font-size:12px;">Loading live prices…</span>
</div>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Cash Balance</div>
    <div class="card-value"><?= e(usd($cash)) ?></div>
    <div class="card-sub">Available to trade</div>
  </div>
  <div class="card">
    <div class="card-label">Holdings Value</div>
    <div class="card-value" id="statHoldings"><?= e(usd($holdingsValue)) ?></div>
    <div class="card-sub"><?= count($holdings) ?> position<?= count($holdings) === 1 ? '' : 's' ?></div>
  </div>
  <div class="card">
    <div class="card-label">Total Equity</div>
    <div class="card-value" id="statEquity"><?= e(usd($equity)) ?></div>
    <div class="card-sub">Initial deposit <?= e(usd((float)STARTING_BALANCE)) ?></div>
  </div>
  <div class="card">
    <div class="card-label">Unrealized P&amp;L</div>
    <div class="card-value <?= $unrealized >= 0 ? 'positive' : 'negative' ?>" id="statPnl">
      <?= ($unrealized >= 0 ? '+' : '-') . e(usd(abs($unrealized))) ?>
    </div>
    <div class="card-sub">Open positions</div>
  </div>
</div>

<?php if ($holdingsAnalysis): ?>
<div class="panel mb-16">
  <div class="panel-header">
    <h2>🎯 Position Analysis · When to Sell</h2>
    <span class="badge">Live recommendation</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Asset</th>
          <th>Qty</th>
          <th>Avg → Now</th>
          <th>P&amp;L</th>
          <th>Recommendation</th>
          <th>Why</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($holdingsAnalysis as $a):
          $recClass = $a['recommendation'] === 'SELL' ? 'low'
                    : ($a['recommendation'] === 'HOLD' ? 'medium' : '');
        ?>
          <tr>
            <td style="font-weight:700;"><?= e($a['symbol']) ?></td>
            <td style="font-family:var(--mono);"><?= number_format($a['qty'], 4) ?></td>
            <td style="font-family:var(--mono);font-size:12px;">
              <?= e(price_fmt($a['avg'])) ?> → <?= e(price_fmt($a['price'])) ?>
            </td>
            <td style="font-family:var(--mono);font-weight:700;color:<?= $a['pnl'] >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
              <?= ($a['pnl'] >= 0 ? '+' : '-') . e(usd(abs($a['pnl']))) ?>
              <div style="font-size:11px;font-weight:500;">
                <?= ($a['pnl_pct'] >= 0 ? '+' : '') . number_format($a['pnl_pct'], 2) ?>%
              </div>
            </td>
            <td><span class="prob-badge <?= $recClass ?>"><?= e($a['recommendation']) ?></span></td>
            <td><div class="signal-reason"><?= e(implode(' · ', $a['reasons'])) ?></div></td>
            <td>
              <button class="btn btn-sm <?= $a['recommendation'] === 'SELL' ? 'btn-danger' : '' ?>"
                      data-sell-row="<?= e($a['symbol']) ?>"
                      data-qty="<?= e((string)$a['qty']) ?>"
                      data-avg="<?= e((string)$a['avg']) ?>"
                      data-price="<?= e((string)$a['price']) ?>">
                Sell
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>📈 Signal Engine</h2>
      <span class="badge badge-live">live</span>
    </div>

    <div class="tabs" id="signalTabs">
      <button class="tab active" data-tab="buy">🟢 Buy (<?= count($grouped['buy']) ?>)</button>
      <button class="tab" data-tab="hold">🟡 Hold (<?= count($grouped['hold']) ?>)</button>
      <button class="tab" data-tab="sell">🔴 Sell (<?= count($grouped['sell']) ?>)</button>
    </div>

    <?php foreach (['buy', 'hold', 'sell'] as $tab):
      $list = $grouped[$tab];
      $hidden = $tab !== 'buy' ? 'style="display:none"' : '';
    ?>
      <div class="signal-panel" data-panel="<?= $tab ?>" <?= $hidden ?>>
        <?php if (!$list): ?>
          <div class="empty-state">No <?= $tab ?> signals right now.</div>
        <?php else: ?>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Asset</th>
                  <th>Confidence</th>
                  <th>Price</th>
                  <th>Target</th>
                  <th>Why</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($list as $s):
                  $conf = $s['confidence'] * 100;
                  $class = $conf >= 60 ? '' : ($conf >= 40 ? 'medium' : 'low');
                ?>
                  <tr>
                    <td>
                      <div class="ticker">
                        <?= e($s['symbol']) ?>
                        <small><?= e($s['name']) ?></small>
                      </div>
                    </td>
                    <td><span class="prob-badge <?= $class ?>"><?= number_format($conf, 0) ?>%</span></td>
                    <td data-live-price="<?= e($s['symbol']) ?>" style="font-family:var(--mono);">
                      <?= e(price_fmt($s['price'])) ?>
                    </td>
                    <td>
                      <?php if ($s['action'] === 'SELL'): ?>
                        <div class="text-red" style="font-weight:600;font-family:var(--mono);">
                          <?= e(price_fmt($s['take_profit'])) ?>
                        </div>
                        <div class="text-dim" style="font-size:11px;">take-profit</div>
                      <?php else: ?>
                        <div class="text-green" style="font-weight:600;font-family:var(--mono);">
                          <?= e(price_fmt($s['target'])) ?>
                        </div>
                        <div class="text-dim" style="font-size:11px;">
                          +<?= number_format((($s['target'] / $s['price']) - 1) * 100, 1) ?>% upside
                        </div>
                      <?php endif; ?>
                    </td>
                    <td><div class="signal-reason"><?= e(implode(' · ', array_slice($s['reasons'], 0, 2))) ?></div></td>
                    <td>
                      <button class="btn btn-sm <?= $s['action'] === 'SELL' ? '' : 'btn-primary' ?>"
                              data-buy="<?= e($s['symbol']) ?>"
                              data-price="<?= e((string)$s['price']) ?>">
                        <?= $s['action'] === 'SELL' ? 'Sell' : 'Buy' ?>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>📊 Markets</h2>
      <span class="badge">live</span>
    </div>
    <div class="watchlist" id="liveWatchlist">
      <div class="empty-state">Loading…</div>
    </div>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>📰 Market News</h2>
      <div class="tabs" style="margin:0;padding:2px;">
        <button class="tab active" data-news-tab="all" style="padding:4px 10px;font-size:11px;">All</button>
        <button class="tab" data-news-tab="crypto" style="padding:4px 10px;font-size:11px;">Crypto</button>
        <button class="tab" data-news-tab="stock" style="padding:4px 10px;font-size:11px;">Stocks</button>
      </div>
    </div>
    <div id="newsFeed">
      <div class="empty-state">Loading news…</div>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>📋 Trade History</h2>
      <span class="badge"><?= count($trades) ?></span>
    </div>
    <div class="table-wrap">
      <?php if (!$trades): ?>
        <div class="empty-state">No trades yet.</div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Time</th>
              <th>Asset</th>
              <th>Side</th>
              <th>Qty</th>
              <th>P&amp;L</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($trades as $t): ?>
              <tr>
                <td class="text-dim" style="font-size:11px;font-family:var(--mono);">
                  <?= e(fmt_time($t['created_at'], 'M j H:i')) ?>
                </td>
                <td style="font-weight:600;"><?= e($t['symbol']) ?></td>
                <td style="font-weight:700;color:<?= $t['side'] === 'BUY' ? 'var(--green)' : 'var(--red)' ?>;">
                  <?= e($t['side']) ?>
                </td>
                <td style="font-family:var(--mono);font-size:12px;">
                  <?= number_format((float)$t['quantity'], 4) ?>
                </td>
                <td>
                  <?php if ($t['pnl'] === null): ?>
                    <span class="text-dim">—</span>
                  <?php else:
                    $pnl = (float)$t['pnl'];
                  ?>
                    <span style="font-weight:700;font-family:var(--mono);font-size:12px;color:<?= $pnl >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
                      <?= ($pnl >= 0 ? '+' : '-') . e(usd(abs($pnl))) ?>
                    </span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>