<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/signal.php';

$user = require_login();

$signals = compute_signals();
$grouped = group_signals($signals);

// Sort buy by confidence desc, sell by confidence desc
usort($grouped['buy'],  fn($a, $b) => $b['confidence'] <=> $a['confidence']);
usort($grouped['sell'], fn($a, $b) => $b['confidence'] <=> $a['confidence']);
usort($grouped['hold'], fn($a, $b) => $b['confidence'] <=> $a['confidence']);

$buyCount  = count($grouped['buy']);
$sellCount = count($grouped['sell']);
$holdCount = count($grouped['hold']);
$strongBuy = 0; $strongSell = 0;
foreach ($signals as $s) {
    if ($s['action'] === 'STRONG BUY')  $strongBuy++;
    if ($s['action'] === 'STRONG SELL') $strongSell++;
}

$pageTitle = 'Signals';
require __DIR__ . '/includes/header.php';
?>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Buy Signals</div>
    <div class="card-value positive"><?= $buyCount ?></div>
    <div class="card-sub"><?= $strongBuy ?> strong buy</div>
  </div>
  <div class="card">
    <div class="card-label">Sell Signals</div>
    <div class="card-value negative"><?= $sellCount ?></div>
    <div class="card-sub"><?= $strongSell ?> strong sell</div>
  </div>
  <div class="card">
    <div class="card-label">Hold</div>
    <div class="card-value"><?= $holdCount ?></div>
    <div class="card-sub">Neutral setup</div>
  </div>
  <div class="card">
    <div class="card-label">Engine</div>
    <div class="card-value" style="font-size:16px;">
      <span class="badge badge-live">live</span>
    </div>
    <div class="card-sub">9-factor analysis</div>
  </div>
</div>

<div class="panel">
  <div class="panel-header">
    <h2>🎯 Signal Engine</h2>
    <span class="badge">Click any row to buy</span>
  </div>

  <div class="tabs" id="signalTabs">
    <button class="tab active" data-tab="buy">🟢 Buy (<?= $buyCount ?>)</button>
    <button class="tab" data-tab="hold">🟡 Hold (<?= $holdCount ?>)</button>
    <button class="tab" data-tab="sell">🔴 Sell (<?= $sellCount ?>)</button>
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
                <th>Action</th>
                <th>Confidence</th>
                <th>Price</th>
                <th>Target</th>
                <th>Stop</th>
                <th>Why</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $s):
                $conf = $s['confidence'] * 100;
                $class = $conf >= 60 ? '' : ($conf >= 40 ? 'medium' : 'low');
                $actionClass = str_contains($s['action'], 'BUY') ? 'positive'
                             : (str_contains($s['action'], 'SELL') ? 'negative' : '');
                $btnLabel = str_contains($s['action'], 'SELL') ? 'Sell' : 'Buy';
              ?>
                <tr>
                  <td>
                    <div class="ticker">
                      <?= e($s['symbol']) ?>
                      <small><?= e($s['name']) ?></small>
                    </div>
                  </td>
                  <td>
                    <span class="prob-badge <?= $actionClass === 'negative' ? 'low' : ($actionClass === 'positive' ? '' : 'medium') ?>">
                      <?= e($s['action']) ?>
                    </span>
                  </td>
                  <td><span class="prob-badge <?= $class ?>"><?= number_format($conf, 0) ?>%</span></td>
                  <td data-live-price="<?= e($s['symbol']) ?>" style="font-family:var(--mono);">
                    <?= e(price_fmt($s['price'])) ?>
                  </td>
                  <td>
                    <div class="<?= str_contains($s['action'], 'SELL') ? 'text-red' : 'text-green' ?>"
                         style="font-weight:600;font-family:var(--mono);">
                      <?= e(price_fmt($s['action'] === 'SELL' ? $s['take_profit'] : $s['target'])) ?>
                    </div>
                  </td>
                  <td class="text-dim" style="font-family:var(--mono);font-size:12px;">
                    <?= e(price_fmt($s['stop'])) ?>
                  </td>
                  <td>
                    <div class="signal-reason">
                      <?= e(implode(' · ', array_slice($s['reasons'], 0, 3))) ?>
                    </div>
                  </td>
                  <td>
                    <button class="btn btn-sm <?= str_contains($s['action'], 'SELL') ? '' : 'btn-primary' ?>"
                            data-buy="<?= e($s['symbol']) ?>"
                            data-price="<?= e((string)$s['price']) ?>">
                      <?= $btnLabel ?>
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

<?php require __DIR__ . '/includes/footer.php'; ?>