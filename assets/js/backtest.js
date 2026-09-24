(function () {
  'use strict';

  const API  = window.BT_API  || '';
  const CSRF = window.BT_CSRF || '';

  const $form = document.getElementById('backtestForm');
  if (!$form) return;

  const $presets    = document.getElementById('btPresets');
  const $mine       = document.getElementById('btMine');
  const $err        = document.getElementById('btError');
  const $loading    = document.getElementById('btLoading');
  const $results    = document.getElementById('btResults');
  const $runBtn     = document.getElementById('btRunBtn');

  let equityChart = null;

  /* ---------- Load strategies into dropdown ---------- */
  async function loadStrategies() {
    try {
      const r = await fetch(API + '/api/strategies.php?action=list', { credentials: 'same-origin' });
      const json = await r.json();

      $presets.innerHTML = '';
      (json.presets || []).forEach(p => {
        const o = document.createElement('option');
        o.value = p.id;
        o.textContent = p.name;
        $presets.appendChild(o);
      });

      $mine.innerHTML = '';
      (json.strategies || []).forEach(s => {
        const o = document.createElement('option');
        o.value = String(s.id);
        o.textContent = s.name;
        $mine.appendChild(o);
      });

      if (!(json.strategies || []).length) {
        const o = document.createElement('option');
        o.disabled = true;
        o.textContent = '— none saved —';
        $mine.appendChild(o);
      }
    } catch (err) {
      console.error('loadStrategies failed', err);
    }
  }

  /* ---------- Run backtest ---------- */
  $form.addEventListener('submit', async (e) => {
    e.preventDefault();
    $err.style.display = 'none';
    $results.style.display = 'none';
    $loading.style.display = 'block';
    $runBtn.disabled = true;
    $runBtn.textContent = 'Running…';

    try {
      const body = new URLSearchParams({
        symbol: document.getElementById('btSymbol').value,
        range: document.getElementById('btRange').value,
        strategy_id: document.getElementById('btStrategy').value,
        starting_balance: document.getElementById('btBalance').value,
        csrf: CSRF,
      });

      const r = await fetch(API + '/api/backtest.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body,
      });
      const json = await r.json();
      if (!r.ok) throw new Error(json.error || 'Backtest failed');

      renderResults(json);
    } catch (err) {
      $err.textContent = err.message;
      $err.className = 'order-feedback error';
      $err.style.display = 'block';
    } finally {
      $loading.style.display = 'none';
      $runBtn.disabled = false;
      $runBtn.textContent = '▶ Run Backtest';
    }
  });

  /* ---------- Render ---------- */
  function renderResults(json) {
    const r = json.result;

    const winRate = r.winRate;
    const returnCls = r.totalReturnPct >= 0 ? 'pos' : 'neg';
    const sharpeCls = r.sharpe >= 1 ? 'pos' : (r.sharpe < 0 ? 'neg' : '');

    const html = `
      <div class="panel mb-16">
        <div class="panel-header">
          <h2>📊 Results · ${json.symbol} · ${json.range.toUpperCase()} · ${json.candle_count.toLocaleString()} candles</h2>
          <span class="badge">Strategy simulation</span>
        </div>

        <div class="bt-hero">
          <div class="bt-stat">
            <div class="bt-stat-label">Total Return</div>
            <div class="bt-stat-value ${returnCls}">${r.totalReturnPct >= 0 ? '+' : ''}${r.totalReturnPct.toFixed(2)}%</div>
            <div class="bt-stat-sub">Buy & hold: ${r.buyHoldPct >= 0 ? '+' : ''}${r.buyHoldPct.toFixed(2)}%</div>
          </div>
          <div class="bt-stat">
            <div class="bt-stat-label">Final Balance</div>
            <div class="bt-stat-value">$${r.endBalance.toLocaleString('en-US', { maximumFractionDigits: 2 })}</div>
            <div class="bt-stat-sub">From $${r.startBalance.toLocaleString()}</div>
          </div>
          <div class="bt-stat">
            <div class="bt-stat-label">Win Rate</div>
            <div class="bt-stat-value">${winRate.toFixed(1)}%</div>
            <div class="bt-stat-sub">${r.winCount} W / ${r.lossCount} L</div>
          </div>
          <div class="bt-stat">
            <div class="bt-stat-label">Profit Factor</div>
            <div class="bt-stat-value">${r.profitFactor.toFixed(2)}</div>
            <div class="bt-stat-sub">Gross win ÷ gross loss</div>
          </div>
          <div class="bt-stat">
            <div class="bt-stat-label">Max Drawdown</div>
            <div class="bt-stat-value neg">-${r.maxDrawdownPct.toFixed(2)}%</div>
            <div class="bt-stat-sub">Peak-to-trough</div>
          </div>
          <div class="bt-stat">
            <div class="bt-stat-label">Sharpe Ratio</div>
            <div class="bt-stat-value ${sharpeCls}">${r.sharpe.toFixed(3)}</div>
            <div class="bt-stat-sub">Annualized</div>
          </div>
        </div>
      </div>

      <div class="bt-two-col">
        <div class="panel">
          <div class="panel-header"><h2>📈 Equity Curve</h2></div>
          <div id="btEquityChart"></div>
        </div>

        <div class="panel">
          <div class="panel-header"><h2>📋 Stats</h2></div>
          <table class="bt-trade-table">
            <tr><td>Total trades</td><td class="num">${r.tradeCount}</td></tr>
            <tr><td>Win / Loss</td><td class="num">${r.winCount} / ${r.lossCount}</td></tr>
            <tr><td>Avg win</td><td class="num pos">+$${r.avgWin.toLocaleString('en-US', { maximumFractionDigits: 2 })}</td></tr>
            <tr><td>Avg loss</td><td class="num neg">-$${r.avgLoss.toLocaleString('en-US', { maximumFractionDigits: 2 })}</td></tr>
            <tr><td>Largest win</td><td class="num pos">+$${r.largestWin.toLocaleString('en-US', { maximumFractionDigits: 2 })}</td></tr>
            <tr><td>Largest loss</td><td class="num neg">-$${r.largestLoss.toLocaleString('en-US', { maximumFractionDigits: 2 })}</td></tr>
          </table>
        </div>
      </div>

      ${r.trades.length ? `
      <div class="panel">
        <div class="panel-header">
          <h2>🎯 Recent Trades</h2>
          <span class="badge">${r.trades.length} of ${r.tradeCount}</span>
        </div>
        <div class="table-wrap">
          <table class="bt-trade-table">
            <thead>
              <tr>
                <th>Entry</th>
                <th>Exit</th>
                <th>Entry $</th>
                <th>Exit $</th>
                <th class="num">Qty</th>
                <th class="num">P&L</th>
                <th class="num">%</th>
                <th class="num">Bars</th>
              </tr>
            </thead>
            <tbody>
              ${r.trades.slice(0, 40).map(t => {
                const cls = t.pnl >= 0 ? 'pos' : 'neg';
                const dt = ts => new Date(ts * 1000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: '2-digit' });
                return `
                  <tr class="${t.forced ? 'forced' : ''}">
                    <td>${dt(t.entryTime)}</td>
                    <td>${dt(t.exitTime)}</td>
                    <td>$${fmtNum(t.entryPrice)}</td>
                    <td>$${fmtNum(t.exitPrice)}</td>
                    <td class="num">${t.qty.toFixed(6)}</td>
                    <td class="num ${cls}">${t.pnl >= 0 ? '+' : '-'}$${Math.abs(t.pnl).toFixed(2)}</td>
                    <td class="num ${cls}">${t.pnlPct >= 0 ? '+' : ''}${t.pnlPct.toFixed(2)}%</td>
                    <td class="num">${t.bars}</td>
                  </tr>
                `;
              }).join('')}
            </tbody>
          </table>
        </div>
      </div>` : ''}
    `;

    $results.innerHTML = html;
    $results.style.display = 'block';

    // Equity chart
    const $chart = document.getElementById('btEquityChart');
    if ($chart && window.LightweightCharts) {
      $chart.innerHTML = '';
      equityChart = LightweightCharts.createChart($chart, {
        width: $chart.clientWidth,
        height: 320,
        layout: {
          background: { type: 'solid', color: 'transparent' },
          textColor: '#8892a8',
          fontFamily: "'Inter', sans-serif",
          fontSize: 11,
        },
        grid: {
          vertLines: { color: 'rgba(31,39,52,0.5)' },
          horzLines: { color: 'rgba(31,39,52,0.5)' },
        },
        rightPriceScale: { borderColor: '#1f2734' },
        timeScale: { borderColor: '#1f2734', timeVisible: true, secondsVisible: false },
        crosshair: { mode: 0 },
      });

      const starting = r.startBalance;
      const last = r.equity.length ? r.equity[r.equity.length - 1].value : starting;
      const trending = last >= starting;

      const series = equityChart.addAreaSeries({
        lineColor: trending ? '#0ecb81' : '#f6465d',
        lineWidth: 2,
        topColor: trending ? 'rgba(14,203,129,0.28)' : 'rgba(246,70,93,0.28)',
        bottomColor: 'transparent',
        priceLineVisible: false,
        lastValueVisible: true,
      });

      series.setData(r.equity.map(p => ({ time: p.time, value: p.value })));
      equityChart.timeScale().fitContent();

      window.addEventListener('resize', () => {
        if (equityChart && $chart) equityChart.applyOptions({ width: $chart.clientWidth });
      });
    }
  }

  function fmtNum(n) {
    n = Number(n);
    if (n >= 1000) return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1) return n.toFixed(2);
    if (n >= 0.01) return n.toFixed(4);
    return n.toFixed(7);
  }

  loadStrategies();
})();