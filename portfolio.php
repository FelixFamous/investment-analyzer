<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';
require_once __DIR__ . '/includes/signal.php';

$user = require_login();

// Record today's equity snapshot (idempotent)
snapshot_portfolio();

// Holdings
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
        'cost'   => $cost,
        'pnl'    => $val - $cost,
    ];
    $holdingsValue += $val;
}

usort($holdings, fn($a, $b) => $b['value'] <=> $a['value']);

$cash     = (float)$user['cash_balance'];
$equity   = $cash + $holdingsValue;
$totalPnl = $equity - STARTING_BALANCE;
$totalPnlPct = STARTING_BALANCE > 0 ? ($totalPnl / STARTING_BALANCE) * 100 : 0;

$unrealized = 0.0;
foreach ($holdings as $h) $unrealized += $h['pnl'];

$stmt = db()->prepare('SELECT COALESCE(SUM(pnl),0) FROM trades WHERE user_id = ? AND pnl IS NOT NULL');
$stmt->execute([$user['id']]);
$realizedPnl = (float)$stmt->fetchColumn();

$stmt = db()->prepare(
    'SELECT symbol, side, quantity, price, total, pnl, created_at
     FROM trades WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 100'
);
$stmt->execute([$user['id']]);
$trades = $stmt->fetchAll();

$signals = compute_signals();
$analysis = analyze_holdings($holdings, $signals);

$allocation = [];
if ($holdingsValue + $cash > 0) {
    $allocation[] = ['label' => 'Cash', 'value' => $cash, 'color' => '#5b7cfa'];
    foreach ($holdings as $h) {
        $allocation[] = ['label' => $h['symbol'], 'value' => $h['value'], 'color' => null];
    }
}

$palette = ['#f0b90b','#0ecb81','#3b82f6','#8b5cf6','#f6465d','#14f195','#ffd966','#93c5fd','#c4b5fd','#fb923c'];
$colorIdx = 0;
foreach ($allocation as &$a) {
    if ($a['color'] === null) {
        $a['color'] = $palette[$colorIdx % count($palette)];
        $colorIdx++;
    }
}
unset($a);

$pageTitle = 'Portfolio';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/portfolio.css">

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Total Equity</div>
    <div class="card-value"><?= e(usd($equity)) ?></div>
    <div class="card-sub">Initial <?= e(usd((float)STARTING_BALANCE)) ?></div>
  </div>
  <div class="card">
    <div class="card-label">Cash</div>
    <div class="card-value"><?= e(usd($cash)) ?></div>
    <div class="card-sub">Available to trade</div>
  </div>
  <div class="card">
    <div class="card-label">Holdings</div>
    <div class="card-value"><?= e(usd($holdingsValue)) ?></div>
    <div class="card-sub"><?= count($holdings) ?> position<?= count($holdings) === 1 ? '' : 's' ?></div>
  </div>
  <div class="card">
    <div class="card-label">Total P&amp;L</div>
    <div class="card-value <?= $totalPnl >= 0 ? 'positive' : 'negative' ?>">
      <?= ($totalPnl >= 0 ? '+' : '-') . e(usd(abs($totalPnl))) ?>
    </div>
    <div class="card-sub"><?= ($totalPnlPct >= 0 ? '+' : '') . number_format($totalPnlPct, 2) ?>% all-time</div>
  </div>
</div>

<!-- Equity curve -->
<div class="panel mb-16">
  <div class="panel-header">
    <h2>📈 Equity Curve</h2>
    <div class="tabs" id="equityRangeTabs" style="margin:0;padding:2px;">
      <button class="tab active" data-eq-range="7"  style="padding:4px 12px;font-size:11px;">7D</button>
      <button class="tab" data-eq-range="30" style="padding:4px 12px;font-size:11px;">30D</button>
      <button class="tab" data-eq-range="90" style="padding:4px 12px;font-size:11px;">90D</button>
      <button class="tab" data-eq-range="all" style="padding:4px 12px;font-size:11px;">ALL</button>
    </div>
  </div>

  <div id="equityMeta" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;gap:10px;font-size:11px;color:var(--text-faint);font-family:var(--mono);flex-wrap:wrap;">
    <span id="equityStats">— data points</span>
    <span id="equityChange">—</span>
  </div>

  <div id="equityChartLoading" class="empty-state" style="padding:60px 20px;">Loading equity curve…</div>
  <div id="equityChartWrap" style="display:none;">
    <div id="equityChart" style="width:100%;"></div>
  </div>
  <div id="equityChartError" class="empty-state" style="display:none;padding:40px 20px;color:var(--red);">
    Could not load equity curve.
  </div>
</div>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Unrealized P&amp;L</div>
    <div class="card-value <?= $unrealized >= 0 ? 'positive' : 'negative' ?>">
      <?= ($unrealized >= 0 ? '+' : '-') . e(usd(abs($unrealized))) ?>
    </div>
    <div class="card-sub">Open positions</div>
  </div>
  <div class="card">
    <div class="card-label">Realized P&amp;L</div>
    <div class="card-value <?= $realizedPnl >= 0 ? 'positive' : 'negative' ?>">
      <?= ($realizedPnl >= 0 ? '+' : '-') . e(usd(abs($realizedPnl))) ?>
    </div>
    <div class="card-sub">From closed trades</div>
  </div>
  <div class="card">
    <div class="card-label">Total Trades</div>
    <div class="card-value"><?= count($trades) ?></div>
    <div class="card-sub">Last 100 shown below</div>
  </div>
  <div class="card">
    <div class="card-label">Best Position</div>
    <?php
      $best = null;
      foreach ($holdings as $h) if ($best === null || $h['pnl'] > $best['pnl']) $best = $h;
    ?>
    <?php if ($best): ?>
      <div class="card-value positive" style="font-size:20px;"><?= e($best['symbol']) ?></div>
      <div class="card-sub">+<?= e(usd($best['pnl'])) ?></div>
    <?php else: ?>
      <div class="card-value text-dim">—</div>
      <div class="card-sub">No positions</div>
    <?php endif; ?>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>💼 Holdings</h2>
      <span class="badge"><?= count($holdings) ?></span>
    </div>

    <?php if (!$holdings): ?>
      <div class="empty-state">
        You don't have any positions yet.<br>
        Visit <a href="<?= e(APP_URL) ?>/markets.php" style="color:var(--accent);">Markets</a>
        or <a href="<?= e(APP_URL) ?>/signals.php" style="color:var(--accent);">Signals</a> to start trading.
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Asset</th>
              <th>Qty</th>
              <th>Avg</th>
              <th>Current</th>
              <th>Value</th>
              <th>P&amp;L</th>
              <th>Weight</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($holdings as $h):
              $pnlPct  = $h['avg'] > 0 ? (($h['price'] - $h['avg']) / $h['avg']) * 100 : 0;
              $weight  = $equity > 0 ? ($h['value'] / $equity) * 100 : 0;
            ?>
              <tr>
                <td style="font-weight:700;"><?= e($h['symbol']) ?></td>
                <td style="font-family:var(--mono);font-size:12px;"><?= number_format($h['qty'], 4) ?></td>
                <td style="font-family:var(--mono);font-size:12px;"><?= e(price_fmt($h['avg'])) ?></td>
                <td data-live-price="<?= e($h['symbol']) ?>" style="font-family:var(--mono);"><?= e(price_fmt($h['price'])) ?></td>
                <td data-hold-value="<?= e($h['symbol']) ?>" style="font-family:var(--mono);font-weight:600;"><?= e(usd($h['value'])) ?></td>
                <td data-hold-pnl="<?= e($h['symbol']) ?>" class="pnl <?= $h['pnl'] >= 0 ? 'pos' : 'neg' ?>" style="font-family:var(--mono);font-weight:700;">
                  <?= ($h['pnl'] >= 0 ? '+' : '-') . e(usd(abs($h['pnl']))) ?>
                  <div style="font-size:11px;font-weight:500;"><?= ($pnlPct >= 0 ? '+' : '') . number_format($pnlPct, 2) ?>%</div>
                </td>
                <td>
                  <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:60px;height:6px;background:var(--panel-2);border-radius:3px;overflow:hidden;">
                      <div style="height:100%;width:<?= min(100, $weight) ?>%;background:var(--accent);"></div>
                    </div>
                    <span style="font-size:11px;font-family:var(--mono);color:var(--text-dim);"><?= number_format($weight, 1) ?>%</span>
                  </div>
                </td>
                <td>
                  <button class="btn btn-sm"
                          data-sell-row="<?= e($h['symbol']) ?>"
                          data-qty="<?= e((string)$h['qty']) ?>"
                          data-avg="<?= e((string)$h['avg']) ?>"
                          data-price="<?= e((string)$h['price']) ?>">
                    Sell
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>🥧 Allocation</h2>
      <span class="badge"><?= count($allocation) ?> slices</span>
    </div>

    <?php if (!$holdings): ?>
      <div class="empty-state">Portfolio is 100% cash.</div>
    <?php else: ?>
      <?php
        $totalAlloc = array_sum(array_column($allocation, 'value'));
        $stops = [];
        $acc = 0.0;
        foreach ($allocation as $a) {
            $pct = $totalAlloc > 0 ? ($a['value'] / $totalAlloc) * 100 : 0;
            $stops[] = "{$a['color']} {$acc}% " . ($acc + $pct) . "%";
            $acc += $pct;
        }
        $gradient = 'conic-gradient(' . implode(', ', $stops) . ')';
      ?>
      <div style="display:flex;justify-content:center;margin-bottom:16px;">
        <div style="width:160px;height:160px;border-radius:50%;background:<?= e($gradient) ?>;position:relative;">
          <div style="position:absolute;inset:22px;border-radius:50%;background:var(--panel);display:flex;flex-direction:column;align-items:center;justify-content:center;">
            <div style="font-size:10px;color:var(--text-faint);text-transform:uppercase;letter-spacing:0.6px;font-weight:700;">Total</div>
            <div style="font-size:14px;font-weight:800;font-family:var(--mono);"><?= e(usd($equity)) ?></div>
          </div>
        </div>
      </div>

      <?php foreach ($allocation as $a):
        $pct = $totalAlloc > 0 ? ($a['value'] / $totalAlloc) * 100 : 0;
      ?>
        <div style="display:flex;align-items:center;gap:8px;padding:6px 0;font-size:12px;">
          <div style="width:10px;height:10px;border-radius:2px;background:<?= e($a['color']) ?>;flex-shrink:0;"></div>
          <div style="flex:1;font-weight:600;"><?= e($a['label']) ?></div>
          <div style="font-family:var(--mono);color:var(--text-dim);"><?= number_format($pct, 1) ?>%</div>
          <div style="font-family:var(--mono);font-weight:600;min-width:80px;text-align:right;"><?= e(usd($a['value'])) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php if ($analysis): ?>
<div class="panel mb-16">
  <div class="panel-header">
    <h2>🎯 Position Recommendations</h2>
    <span class="badge">When to sell</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Asset</th><th>P&amp;L</th><th>Recommendation</th><th>Reasons</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($analysis as $a):
          $recClass = $a['recommendation'] === 'SELL' ? 'low' : ($a['recommendation'] === 'HOLD' ? 'medium' : '');
        ?>
          <tr>
            <td style="font-weight:700;"><?= e($a['symbol']) ?></td>
            <td class="pnl <?= $a['pnl'] >= 0 ? 'pos' : 'neg' ?>" style="font-family:var(--mono);font-weight:700;">
              <?= ($a['pnl'] >= 0 ? '+' : '-') . e(usd(abs($a['pnl']))) ?>
              <div style="font-size:11px;font-weight:500;"><?= ($a['pnl_pct'] >= 0 ? '+' : '') . number_format($a['pnl_pct'], 2) ?>%</div>
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

<div class="panel">
  <div class="panel-header">
    <h2>📋 Full Trade History</h2>
    <div style="display:flex;gap:8px;align-items:center;">
      <span class="badge"><?= count($trades) ?> trades</span>
      <button type="button" class="export-btn" data-export="trades">
        📥 Export CSV
      </button>
    </div>
  </div>
  <?php if (!$trades): ?>
    <div class="empty-state">No trades yet.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Time</th><th>Symbol</th><th>Side</th><th>Qty</th><th>Price</th><th>Total</th><th>P&amp;L</th></tr>
        </thead>
        <tbody>
          <?php foreach ($trades as $t): ?>
            <tr>
              <td class="text-dim" style="font-size:11px;font-family:var(--mono);"><?= e(fmt_time($t['created_at'], 'M j, H:i')) ?></td>
              <td style="font-weight:700;"><?= e($t['symbol']) ?></td>
              <td style="font-weight:700;color:<?= $t['side'] === 'BUY' ? 'var(--green)' : 'var(--red)' ?>;"><?= e($t['side']) ?></td>
              <td style="font-family:var(--mono);font-size:12px;"><?= number_format((float)$t['quantity'], 4) ?></td>
              <td style="font-family:var(--mono);font-size:12px;"><?= e(price_fmt((float)$t['price'])) ?></td>
              <td style="font-family:var(--mono);font-size:12px;"><?= e(usd((float)$t['total'])) ?></td>
              <td>
                <?php if ($t['pnl'] === null): ?>
                  <span class="text-dim">—</span>
                <?php else: $pnl = (float)$t['pnl']; ?>
                  <span style="font-weight:700;font-family:var(--mono);font-size:12px;color:<?= $pnl >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
                    <?= ($pnl >= 0 ? '+' : '-') . e(usd(abs($pnl))) ?>
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

<script>
  window.EQUITY_API = <?= json_encode(APP_URL) ?>;
</script>
<script src="https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"></script>
<script src="<?= e(APP_URL) ?>/assets/js/equity-chart.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>