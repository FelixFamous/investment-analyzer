<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$KNOWN = [
    'BTC'=>'Bitcoin','ETH'=>'Ethereum','SOL'=>'Solana','BNB'=>'BNB','XRP'=>'XRP',
    'DOGE'=>'Dogecoin','ADA'=>'Cardano','PEPE'=>'Pepe',
    'AAPL'=>'Apple','MSFT'=>'Microsoft','NVDA'=>'NVIDIA','TSLA'=>'Tesla',
    'AMD'=>'AMD','META'=>'Meta','GOOGL'=>'Alphabet','AMZN'=>'Amazon','NFLX'=>'Netflix',
];

$pageTitle = 'Trade Calculator';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/calculator.css">

<div class="calc-tabs" id="calcTabs">
  <button type="button" class="calc-tab active" data-tab="position">Position Size</button>
  <button type="button" class="calc-tab" data-tab="rr">Risk / Reward</button>
  <button type="button" class="calc-tab" data-tab="pnl">P&amp;L</button>
  <button type="button" class="calc-tab" data-tab="compound">Compound</button>
  <button type="button" class="calc-tab" data-tab="leverage">Leverage</button>
</div>

<!-- ============================================================
     POSITION SIZE
     ============================================================ -->
<div class="calc-panel active" data-panel="position">
  <div class="panel">
    <div class="panel-header">
      <h2>📐 Position Size Calculator</h2>
      <span class="badge">Fixed fractional</span>
    </div>

    <div class="calc-grid">
      <div class="form-group">
        <label for="psBalance">Account balance ($)</label>
        <input type="number" id="psBalance" value="<?= e((string)(float)$user['cash_balance']) ?>" min="0" step="0.01">
      </div>

      <div class="form-group">
        <label for="psRiskPct">
          Risk per trade (%)
          <span class="text-dim" style="font-weight:400;font-size:11px;">— suggested: <?= e((string)$user['risk_per_trade_pct']) ?>%</span>
        </label>
        <input type="number" id="psRiskPct" value="<?= e((string)$user['risk_per_trade_pct']) ?>" min="0.1" max="100" step="0.1">
      </div>

      <div class="form-group">
        <label for="psEntry">Entry price ($)</label>
        <input type="number" id="psEntry" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="psStop">Stop-loss price ($)</label>
        <input type="number" id="psStop" placeholder="0.00" step="0.00000001" min="0">
      </div>
    </div>

    <div class="calc-results">
      <div class="calc-row highlight"><span>Risk amount</span><strong id="psRiskAmount">—</strong></div>
      <div class="calc-row"><span>Risk per unit</span><strong id="psRiskPerUnit">—</strong></div>
      <div class="calc-row highlight"><span>Suggested quantity</span><strong id="psQty">—</strong></div>
      <div class="calc-row"><span>Position value</span><strong id="psPositionValue">—</strong></div>
      <div class="calc-row"><span>Stop distance</span><strong id="psStopPct">—</strong></div>
    </div>
  </div>
</div>

<!-- ============================================================
     RISK / REWARD
     ============================================================ -->
<div class="calc-panel" data-panel="rr">
  <div class="panel">
    <div class="panel-header">
      <h2>⚖️ Risk / Reward Calculator</h2>
      <span class="badge">Ratio analysis</span>
    </div>

    <div class="calc-grid">
      <div class="form-group">
        <label for="rrEntry">Entry price ($)</label>
        <input type="number" id="rrEntry" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="rrStop">Stop price ($)</label>
        <input type="number" id="rrStop" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="rrTarget">Target price ($)</label>
        <input type="number" id="rrTarget" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="rrQty">Position size (units)</label>
        <input type="number" id="rrQty" placeholder="0.00" step="0.00000001" min="0">
      </div>
    </div>

    <div class="calc-results">
      <div class="calc-row"><span>Risk per unit</span><strong id="rrRiskUnit">—</strong></div>
      <div class="calc-row"><span>Reward per unit</span><strong id="rrRewardUnit">—</strong></div>
      <div class="calc-row highlight"><span>R:R ratio</span><strong id="rrRatio">—</strong></div>
      <div class="calc-row"><span>Total risk</span><strong id="rrTotalRisk" style="color:var(--red);">—</strong></div>
      <div class="calc-row"><span>Total reward</span><strong id="rrTotalReward" style="color:var(--green);">—</strong></div>
      <div class="calc-row"><span>Breakeven win rate</span><strong id="rrBreakeven">—</strong></div>
    </div>
  </div>
</div>

<!-- ============================================================
     P&L
     ============================================================ -->
<div class="calc-panel" data-panel="pnl">
  <div class="panel">
    <div class="panel-header">
      <h2>💰 Profit / Loss Calculator</h2>
      <span class="badge">Trade outcome</span>
    </div>

    <div class="calc-grid">
      <div class="form-group">
        <label for="pnlEntry">Entry price ($)</label>
        <input type="number" id="pnlEntry" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="pnlExit">Exit price ($)</label>
        <input type="number" id="pnlExit" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="pnlQty">Quantity (units)</label>
        <input type="number" id="pnlQty" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="pnlSide">Direction</label>
        <select id="pnlSide">
          <option value="long">Long (buy first)</option>
          <option value="short">Short (sell first)</option>
        </select>
      </div>

      <div class="form-group">
        <label for="pnlFeePct">Fees (%)</label>
        <input type="number" id="pnlFeePct" value="0" step="0.01" min="0" max="5">
      </div>
    </div>

    <div class="calc-results">
      <div class="calc-row"><span>Gross P&amp;L</span><strong id="pnlGross">—</strong></div>
      <div class="calc-row"><span>Fees</span><strong id="pnlFees">—</strong></div>
      <div class="calc-row highlight"><span>Net P&amp;L</span><strong id="pnlNet">—</strong></div>
      <div class="calc-row"><span>Return on trade</span><strong id="pnlReturn">—</strong></div>
      <div class="calc-row"><span>Capital deployed</span><strong id="pnlCapital">—</strong></div>
    </div>
  </div>
</div>

<!-- ============================================================
     COMPOUND
     ============================================================ -->
<div class="calc-panel" data-panel="compound">
  <div class="panel">
    <div class="panel-header">
      <h2>📈 Compound Interest</h2>
      <span class="badge">Long-term projection</span>
    </div>

    <div class="calc-grid">
      <div class="form-group">
        <label for="ciStart">Starting capital ($)</label>
        <input type="number" id="ciStart" value="10000" min="0" step="100">
      </div>

      <div class="form-group">
        <label for="ciReturn">Return per period (%)</label>
        <input type="number" id="ciReturn" value="3" step="0.1">
      </div>

      <div class="form-group">
        <label for="ciPeriods">Number of periods</label>
        <input type="number" id="ciPeriods" value="52" min="1" max="10000" step="1">
      </div>

      <div class="form-group">
        <label for="ciFrequency">Compounding</label>
        <select id="ciFrequency">
          <option value="1">Every period</option>
          <option value="1" selected>Simple (recommended for trades)</option>
        </select>
      </div>
    </div>

    <div class="calc-results">
      <div class="calc-row"><span>Final balance</span><strong id="ciFinal">—</strong></div>
      <div class="calc-row highlight"><span>Total profit</span><strong id="ciProfit">—</strong></div>
      <div class="calc-row"><span>Total return</span><strong id="ciTotalPct">—</strong></div>
      <div class="calc-row"><span>Effective per-period</span><strong id="ciPerPeriod">—</strong></div>
    </div>

    <div id="ciChart" style="margin-top:16px;height:220px;"></div>
  </div>
</div>

<!-- ============================================================
     LEVERAGE
     ============================================================ -->
<div class="calc-panel" data-panel="leverage">
  <div class="panel">
    <div class="panel-header">
      <h2>⚡ Leverage &amp; Liquidation</h2>
      <span class="badge">Futures-style math</span>
    </div>

    <div class="calc-grid">
      <div class="form-group">
        <label for="lvBalance">Account balance ($)</label>
        <input type="number" id="lvBalance" value="10000" min="0" step="100">
      </div>

      <div class="form-group">
        <label for="lvEntry">Entry price ($)</label>
        <input type="number" id="lvEntry" placeholder="0.00" step="0.00000001" min="0">
      </div>

      <div class="form-group">
        <label for="lvLeverage">Leverage (x)</label>
        <input type="number" id="lvLeverage" value="10" min="1" max="1000" step="1">
      </div>

      <div class="form-group">
        <label for="lvSide">Direction</label>
        <select id="lvSide">
          <option value="long">Long</option>
          <option value="short">Short</option>
        </select>
      </div>

      <div class="form-group">
        <label for="lvMMR">Maintenance margin (%)</label>
        <input type="number" id="lvMMR" value="0.5" min="0" max="10" step="0.1">
      </div>
    </div>

    <div class="calc-results">
      <div class="calc-row"><span>Position size</span><strong id="lvPosition">—</strong></div>
      <div class="calc-row"><span>Quantity at entry</span><strong id="lvQty">—</strong></div>
      <div class="calc-row highlight"><span>Liquidation price</span><strong id="lvLiq" style="color:var(--red);">—</strong></div>
      <div class="calc-row"><span>Liquidation distance</span><strong id="lvLiqDist">—</strong></div>
      <div class="calc-row"><span>Initial margin</span><strong id="lvMargin">—</strong></div>
    </div>

    <div class="calc-warning">
      ⚠️ <strong>Leverage amplifies both gains and losses.</strong>
      At 10× leverage, a 10% adverse move liquidates your entire position.
      Most professional traders keep leverage under 5×.
    </div>
  </div>
</div>

<script>
  window.CALC_API = <?= json_encode(APP_URL) ?>;
</script>
<script src="https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js"></script>
<script src="<?= e(APP_URL) ?>/assets/js/calculator.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>