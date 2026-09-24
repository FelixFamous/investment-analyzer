<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';

$user = require_login();

// ---------- Compute current portfolio stats for preview ----------
$cash = (float)$user['cash_balance'];

$stmt = db()->prepare('SELECT symbol, quantity, avg_price, take_profit, stop_loss
                       FROM holdings WHERE user_id = ?');
$stmt->execute([$user['id']]);
$rawHoldings = $stmt->fetchAll();

$equity = $cash;
$heatDollars = 0.0;
$positions = [];

foreach ($rawHoldings as $h) {
    $live = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $val  = (float)$h['quantity'] * $live;
    $equity += $val;

    $sl = $h['stop_loss'] !== null ? (float)$h['stop_loss'] : null;
    $riskPerUnit = $sl !== null ? max(0.0, $live - $sl) : 0.0;
    $riskDollars = $riskPerUnit * (float)$h['quantity'];
    $heatDollars += $riskDollars;

    $positions[] = [
        'symbol'    => $h['symbol'],
        'value'     => $val,
        'has_sl'    => $sl !== null,
        'risk'      => $riskDollars,
        'sl'        => $sl,
        'live'      => $live,
    ];
}

$heatPct = $equity > 0 ? ($heatDollars / $equity) * 100 : 0.0;
$maxPosValue = $equity * ((float)($user['max_position_pct'] ?? 20) / 100);

// Count holdings exceeding max position size
$violations = [];
foreach ($positions as $p) {
    if ($p['value'] > $maxPosValue) $violations[] = $p['symbol'];
}

// Count holdings with no stop-loss
$unprotected = [];
foreach ($positions as $p) {
    if (!$p['has_sl']) $unprotected[] = $p['symbol'];
}

$pageTitle = 'Risk Settings';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/risk.css">

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Total Equity</div>
    <div class="card-value"><?= e(usd($equity)) ?></div>
    <div class="card-sub">Cash + holdings</div>
  </div>
  <div class="card">
    <div class="card-label">Portfolio Heat</div>
    <div class="card-value <?= $heatPct > (float)$user['max_portfolio_heat'] ? 'negative' : ($heatPct > (float)$user['max_portfolio_heat'] * 0.75 ? '' : 'positive') ?>">
      <?= number_format($heatPct, 2) ?>%
    </div>
    <div class="card-sub">Max allowed <?= number_format((float)$user['max_portfolio_heat'], 1) ?>%</div>
  </div>
  <div class="card">
    <div class="card-label">Open Risk</div>
    <div class="card-value" style="font-family:var(--mono);font-size:20px;"><?= e(usd($heatDollars)) ?></div>
    <div class="card-sub">If all stops hit</div>
  </div>
  <div class="card">
    <div class="card-label">Positions</div>
    <div class="card-value"><?= count($positions) ?></div>
    <div class="card-sub">
      <?= count($unprotected) ?> without stop-loss
    </div>
  </div>
</div>

<!-- Portfolio heat meter -->
<div class="panel mb-16">
  <div class="panel-header">
    <h2>🌡️ Portfolio Heat</h2>
    <span class="badge <?= $heatPct > (float)$user['max_portfolio_heat'] ? 'badge-live' : '' ?>">
      <?= $heatPct > (float)$user['max_portfolio_heat'] ? 'Over limit' : 'Within limit' ?>
    </span>
  </div>

  <?php
    $maxHeat = max(1.0, (float)$user['max_portfolio_heat']);
    $fillPct = min(100, ($heatPct / $maxHeat) * 100);
    $barColor = $fillPct > 100 ? 'var(--red)' : ($fillPct > 75 ? 'var(--accent)' : 'var(--green)');
  ?>
  <div class="heat-meter">
    <div class="heat-meter-track">
      <div class="heat-meter-fill" style="width:<?= $fillPct ?>%;background:<?= $barColor ?>;"></div>
      <div class="heat-meter-marker" style="left:100%;" title="Max allowed"></div>
    </div>
    <div class="heat-meter-labels">
      <span>0%</span>
      <span>Current: <?= number_format($heatPct, 2) ?>%</span>
      <span>Max: <?= number_format((float)$user['max_portfolio_heat'], 1) ?>%</span>
    </div>
  </div>

  <p class="text-dim" style="font-size:12px;line-height:1.7;margin-top:16px;">
    <strong style="color:var(--text);">Portfolio heat</strong> is the total amount you would lose
    if every open position hit its stop-loss simultaneously. Professional traders keep
    heat below <strong>6%</strong> — that means no single market move can cost you more
    than 6% of your account.
  </p>
</div>

<!-- Position concentration -->
<?php if ($positions): ?>
<div class="panel mb-16">
  <div class="panel-header">
    <h2>⚖️ Position Concentration</h2>
    <span class="badge">Max <?= number_format((float)$user['max_position_pct'], 1) ?>% per position</span>
  </div>

  <?php foreach ($positions as $p):
    $pct = $equity > 0 ? ($p['value'] / $equity) * 100 : 0;
    $over = $p['value'] > $maxPosValue;
  ?>
    <div class="risk-position-row <?= $over ? 'over' : '' ?>">
      <div class="risk-position-sym">
        <strong><?= e($p['symbol']) ?></strong>
        <?php if (!$p['has_sl']): ?>
          <span class="risk-tag warn">no SL</span>
        <?php else: ?>
          <span class="risk-tag ok">SL</span>
        <?php endif; ?>
      </div>
      <div class="risk-position-bar-wrap">
        <div class="risk-position-bar">
          <div class="risk-position-fill" style="width:<?= min(100, ($pct / max(1, (float)$user['max_position_pct'])) * 100) ?>%;background:<?= $over ? 'var(--red)' : 'var(--accent)' ?>;"></div>
        </div>
        <span class="risk-position-pct"><?= number_format($pct, 1) ?>%</span>
      </div>
      <div class="risk-position-value"><?= e(usd($p['value'])) ?></div>
    </div>
  <?php endforeach; ?>

  <?php if ($violations): ?>
    <div class="risk-alert">
      ⚠️ <strong>Concentration warning:</strong> <?= implode(', ', $violations) ?>
      exceed<?= count($violations) === 1 ? 's' : '' ?> your
      <?= number_format((float)$user['max_position_pct'], 1) ?>% per-position cap.
    </div>
  <?php endif; ?>

  <?php if ($unprotected): ?>
    <div class="risk-alert warn">
      🛑 <strong>Unprotected positions:</strong> <?= implode(', ', $unprotected) ?>
      have no stop-loss. Consider setting one to cap your risk.
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Settings form -->
<div class="panel">
  <div class="panel-header">
    <h2>⚙️ Your Risk Profile</h2>
    <span class="badge">Affects position sizing on every trade</span>
  </div>

  <form id="riskForm">
    <div class="risk-field-group">
      <div class="form-group">
        <label for="riskPerTrade">Risk per trade (%)</label>
        <input type="number" id="riskPerTrade" name="risk_per_trade_pct"
               step="0.1" min="0.1" max="10"
               value="<?= e((string)$user['risk_per_trade_pct']) ?>">
        <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
          How much of your equity to risk on one trade. Default <strong>1%</strong>. Aggressive: <strong>2–3%</strong>.
        </small>
      </div>

      <div class="form-group">
        <label for="maxHeat">Max portfolio heat (%)</label>
        <input type="number" id="maxHeat" name="max_portfolio_heat"
               step="0.5" min="1" max="30"
               value="<?= e((string)$user['max_portfolio_heat']) ?>">
        <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
          Total open risk across all positions. Professional default <strong>6%</strong>.
        </small>
      </div>

      <div class="form-group">
        <label for="maxPos">Max position size (%)</label>
        <input type="number" id="maxPos" name="max_position_pct"
               step="1" min="5" max="100"
               value="<?= e((string)$user['max_position_pct']) ?>">
        <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
          Largest % of equity any single position can occupy. Default <strong>20%</strong>.
        </small>
      </div>
    </div>

    <div style="display:flex;align-items:center;gap:12px;margin-top:20px;flex-wrap:wrap;">
      <button type="submit" class="btn btn-primary" id="saveRiskBtn">Save Settings</button>
      <button type="button" class="btn" id="resetRiskBtn">Reset to defaults</button>
      <span class="text-dim" style="font-size:11.5px;">
        <?php if ($user['risk_settings_updated']): ?>
          Last updated <?= e(fmt_time($user['risk_settings_updated'], 'M j, Y H:i')) ?>
        <?php endif; ?>
      </span>
    </div>

    <div id="riskFeedback" class="order-feedback" style="display:none;"></div>
  </form>

  <div class="risk-presets">
    <div class="risk-presets-label">Quick presets</div>
    <div class="risk-presets-row">
      <button type="button" class="risk-preset" data-preset="conservative">
        <strong>Conservative</strong>
        <span>0.5% · 3% · 10%</span>
      </button>
      <button type="button" class="risk-preset" data-preset="balanced">
        <strong>Balanced</strong>
        <span>1% · 6% · 20%</span>
      </button>
      <button type="button" class="risk-preset" data-preset="aggressive">
        <strong>Aggressive</strong>
        <span>2% · 12% · 35%</span>
      </button>
    </div>
  </div>
</div>

<script>
  window.RISK_API  = <?= json_encode(APP_URL) ?>;
  window.RISK_CSRF = <?= json_encode(csrf_token()) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/risk-settings.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>