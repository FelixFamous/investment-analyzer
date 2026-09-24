<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/signal.php';

$user = require_login();

$signals = compute_signals();

// Fetch holdings
$stmt = db()->prepare('SELECT symbol, quantity, avg_price FROM holdings WHERE user_id = ?');
$stmt->execute([$user['id']]);
$rawHoldings = $stmt->fetchAll();

$holdings = [];
$holdingsValue = 0.0;
foreach ($rawHoldings as $h) {
    $live = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $val  = (float)$h['quantity'] * $live;
    $cost = (float)$h['quantity'] * (float)$h['avg_price'];
    $pnl  = $val - $cost;

    $holdings[] = [
        'symbol' => $h['symbol'],
        'qty'    => (float)$h['quantity'],
        'avg'    => (float)$h['avg_price'],
        'price'  => $live,
        'value'  => $val,
        'pnl'    => $pnl,
    ];
    $holdingsValue += $val;
}

// Fetch recent trades
$stmt = db()->prepare(
    'SELECT symbol, side, quantity, price, total, pnl, created_at
     FROM trades WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 25'
);
$stmt->execute([$user['id']]);
$trades = $stmt->fetchAll();

$cash     = (float)$user['cash_balance'];
$equity   = $cash + $holdingsValue;
$pnlTotal = $equity - STARTING_BALANCE;
$pnlPct   = STARTING_BALANCE > 0 ? ($pnlTotal / STARTING_BALANCE) * 100 : 0;

$pageTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>

<!-- Summary cards -->
<div class="summary-grid">
  <div class="card">
    <div class="card-label">Cash Balance</div>
    <div class="card-value"><?= e(usd($cash)) ?></div>
    <div class="card-sub">Available to trade</div>
  </div>

  <div class="card">
    <div class="card-label">Holdings Value</div>
    <div class="card-value"><?= e(usd($holdingsValue)) ?></div>
    <div class="card-sub"><?= count($holdings) ?> position<?= count($holdings) === 1 ? '' : 's' ?></div>
  </div>

  <div class="card">
    <div class="card-label">Total Equity</div>
    <div class="card-value"><?= e(usd($equity)) ?></div>
    <div class="card-sub">Initial deposit <?= e(usd((float)STARTING_BALANCE)) ?></div>
  </div>

  <div class="card">
    <div class="card-label">Total P&L</div>
    <div class="card-value <?= $pnlTotal >= 0 ? 'positive' : 'negative' ?>">
      <?= ($pnlTotal >= 0 ? '+' : '-') . e(usd(abs($pnlTotal))) ?>
    </div>
    <div class="card-sub"><?= ($pnlPct >= 0 ? '+' : '') . number_format($pnlPct, 2) ?>% all-time</div>
  </div>
</div>

<!-- Main grid -->
<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>🔥 Top Picks · Signal Engine</h2>
      <span class="badge"><?= FINNHUB_API_KEY ? 'live data' : 'simulated' ?></span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Asset</th>
            <th>Prob. Up</th>
            <th>Price</th>
            <th>Target</th>
            <th>Why</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (array_slice($signals, 0, 7) as $s):
            $prob  = $s['prob_up'] * 100;
            $class = $prob >= 65 ? '' : ($prob >= 50 ? 'medium' : 'low');
          ?>
            <tr>
              <td>
                <div class="ticker">
                  <?= e($s['symbol']) ?>
                  <small><?= e($s['name']) ?></small>
                </div>
              </td>
              <td><span class="prob-badge <?= $class ?>"><?= number_format($prob, 1) ?>%</span></td>
              <td><?= e(price_fmt($s['price'])) ?></td>
              <td>
                <div class="text-green" style="font-weight:600;"><?= e(price_fmt($s['target'])) ?></div>
                <div class="text-dim" style="font-size:12px;">
                  +<?= number_format((($s['target'] / $s['price']) - 1) * 100, 1) ?>% upside
                </div>
              </td>
              <td><div class="signal-reason"><?= e(implode(' · ', array_slice($s['reasons'], 0, 2))) ?></div></td>
              <td>
                <button class="btn btn-sm btn-primary"
                        data-buy="<?= e($s['symbol']) ?>"
                        data-price="<?= e((string)$s['price']) ?>">
                  Buy
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>💼 Your Holdings</h2>
      <span class="badge"><?= count($holdings) ?></span>
    </div>

    <?php if (!$holdings): ?>
      <div class="empty-state">No open positions. Buy a top pick to get started.</div>
    <?php else: ?>
      <?php foreach ($holdings as $h):
        $pnlPct = $h['avg'] > 0 ? (($h['price'] - $h['avg']) / $h['avg']) * 100 : 0;
      ?>
        <div class="holding-item"
             data-sell-row="<?= e($h['symbol']) ?>"
             data-qty="<?= e((string)$h['qty']) ?>"
             data-avg="<?= e((string)$h['avg']) ?>"
             data-price="<?= e((string)$h['price']) ?>"
             title="Click to sell">
          <div class="holding-info">
            <div class="sym"><?= e($h['symbol']) ?></div>
            <div class="qty">
              <?= number_format($h['qty'], 4) ?> @ <?= e(price_fmt($h['avg'])) ?>
            </div>
          </div>
          <div class="holding-value">
            <div class="val"><?= e(usd($h['value'])) ?></div>
            <div class="pnl <?= $h['pnl'] >= 0 ? 'pos' : 'neg' ?>">
              <?= ($h['pnl'] >= 0 ? '+' : '-') . e(usd(abs($h['pnl']))) ?>
              (<?= ($pnlPct >= 0 ? '+' : '') . number_format($pnlPct, 2) ?>%)
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Trade history -->
<div class="panel mt-24">
  <div class="panel-header">
    <h2>📋 Trade History</h2>
    <span class="badge"><?= count($trades) ?> trade<?= count($trades) === 1 ? '' : 's' ?></span>
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
            <th>Price</th>
            <th>Total</th>
            <th>P&L</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($trades as $t): ?>
            <tr>
              <td class="text-dim" style="font-size:13px;">
                <?= e(date('M j, H:i', strtotime($t['created_at']))) ?>
              </td>
              <td style="font-weight:600;"><?= e($t['symbol']) ?></td>
              <td style="font-weight:600;color:<?= $t['side'] === 'BUY' ? 'var(--green)' : 'var(--red)' ?>;">
                <?= e($t['side']) ?>
              </td>
              <td><?= number_format((float)$t['quantity'], 4) ?></td>
              <td><?= e(price_fmt((float)$t['price'])) ?></td>
              <td><?= e(usd((float)$t['total'])) ?></td>
              <td>
                <?php if ($t['pnl'] === null): ?>
                  <span class="text-dim">—</span>
                <?php else:
                  $pnl = (float)$t['pnl'];
                ?>
                  <span style="font-weight:600;color:<?= $pnl >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
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

<?php require __DIR__ . '/includes/footer.php'; ?>