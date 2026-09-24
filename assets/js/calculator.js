(function () {
  'use strict';

  /* ============================================================
   *  TABS
   * ============================================================ */
  document.querySelectorAll('.calc-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.calc-tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.calc-panel').forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      const panel = document.querySelector(`[data-panel="${tab.dataset.tab}"]`);
      if (panel) panel.classList.add('active');
    });
  });

  /* ============================================================
   *  HELPERS
   * ============================================================ */
  function num(id) { const el = document.getElementById(id); return el ? (parseFloat(el.value) || 0) : 0; }
  function setText(id, txt) { const el = document.getElementById(id); if (el) el.textContent = txt; }
  function fmtUsd(n) {
    if (!isFinite(n)) return '—';
    const neg = n < 0;
    return (neg ? '-$' : '$') + Math.abs(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
  function fmtNum(n, dec = 6) {
    if (!isFinite(n)) return '—';
    return Number(n).toFixed(dec).replace(/0+$/, '').replace(/\.$/, '');
  }
  function fmtPct(n) {
    if (!isFinite(n)) return '—';
    return (n >= 0 ? '+' : '') + Number(n).toFixed(2) + '%';
  }

  /* ============================================================
   *  POSITION SIZE
   * ============================================================ */
  function calcPosition() {
    const balance = num('psBalance');
    const riskPct = num('psRiskPct');
    const entry = num('psEntry');
    const stop = num('psStop');

    if (!balance || !riskPct || !entry || !stop || entry === stop) {
      ['psRiskAmount', 'psRiskPerUnit', 'psQty', 'psPositionValue', 'psStopPct'].forEach(id => setText(id, '—'));
      return;
    }

    const riskAmount = balance * (riskPct / 100);
    const riskPerUnit = Math.abs(entry - stop);
    const qty = riskAmount / riskPerUnit;
    const positionValue = qty * entry;
    const stopPct = (Math.abs(entry - stop) / entry) * 100;

    setText('psRiskAmount', fmtUsd(riskAmount));
    setText('psRiskPerUnit', '$' + fmtNum(riskPerUnit, 8));
    setText('psQty', fmtNum(qty, 8));
    setText('psPositionValue', fmtUsd(positionValue));
    setText('psStopPct', stopPct.toFixed(2) + '%');
  }

  /* ============================================================
   *  RISK / REWARD
   * ============================================================ */
  function calcRR() {
    const entry = num('rrEntry');
    const stop = num('rrStop');
    const target = num('rrTarget');
    const qty = num('rrQty');

    if (!entry || !stop || !target || entry === stop || entry === target) {
      ['rrRiskUnit', 'rrRewardUnit', 'rrRatio', 'rrTotalRisk', 'rrTotalReward', 'rrBreakeven'].forEach(id => setText(id, '—'));
      return;
    }

    const riskUnit = Math.abs(entry - stop);
    const rewardUnit = Math.abs(target - entry);
    const ratio = rewardUnit / riskUnit;

    const totalRisk = riskUnit * (qty || 1);
    const totalReward = rewardUnit * (qty || 1);

    // Breakeven win rate = 1 / (1 + RR)
    const breakeven = (1 / (1 + ratio)) * 100;

    setText('rrRiskUnit', '$' + fmtNum(riskUnit, 8));
    setText('rrRewardUnit', '$' + fmtNum(rewardUnit, 8));
    setText('rrRatio', '1 : ' + ratio.toFixed(2));
    setText('rrTotalRisk', fmtUsd(totalRisk));
    setText('rrTotalReward', fmtUsd(totalReward));
    setText('rrBreakeven', breakeven.toFixed(1) + '%');
  }

  /* ============================================================
   *  P&L
   * ============================================================ */
  function calcPnl() {
    const entry = num('pnlEntry');
    const exit = num('pnlExit');
    const qty = num('pnlQty');
    const side = document.getElementById('pnlSide')?.value || 'long';
    const feePct = num('pnlFeePct');

    if (!entry || !exit || !qty) {
      ['pnlGross', 'pnlFees', 'pnlNet', 'pnlReturn', 'pnlCapital'].forEach(id => setText(id, '—'));
      return;
    }

    const direction = side === 'short' ? -1 : 1;
    const grossPnl = (exit - entry) * qty * direction;
    const capital = entry * qty;
    const fees = capital * (feePct / 100) * 2; // open + close
    const netPnl = grossPnl - fees;
    const returnPct = capital > 0 ? (netPnl / capital) * 100 : 0;

    setText('pnlGross', (grossPnl >= 0 ? '+' : '-') + fmtUsd(Math.abs(grossPnl)).substring(1));
    setText('pnlFees', fmtUsd(fees));
    setText('pnlNet', (netPnl >= 0 ? '+' : '-') + fmtUsd(Math.abs(netPnl)).substring(1));
    const netEl = document.getElementById('pnlNet');
    if (netEl) netEl.style.color = netPnl >= 0 ? 'var(--green)' : 'var(--red)';

    setText('pnlReturn', fmtPct(returnPct));
    const retEl = document.getElementById('pnlReturn');
    if (retEl) retEl.style.color = returnPct >= 0 ? 'var(--green)' : 'var(--red)';

    setText('pnlCapital', fmtUsd(capital));
  }

  /* ============================================================
   *  COMPOUND
   * ============================================================ */
  let ciChart = null;
  function calcCompound() {
    const start = num('ciStart');
    const retPct = num('ciReturn') / 100;
    const periods = Math.max(1, Math.min(10000, num('ciPeriods')));

    if (!start || !retPct) return;

    const final = start * Math.pow(1 + retPct, periods);
    const profit = final - start;
    const totalPct = ((final - start) / start) * 100;
    const perPeriod = retPct * 100;

    setText('ciFinal', fmtUsd(final));
    setText('ciProfit', (profit >= 0 ? '+' : '-') + fmtUsd(Math.abs(profit)).substring(1));
    const profEl = document.getElementById('ciProfit');
    if (profEl) profEl.style.color = profit >= 0 ? 'var(--green)' : 'var(--red)';

    setText('ciTotalPct', fmtPct(totalPct));
    setText('ciPerPeriod', perPeriod.toFixed(2) + '%');

    // Chart
    const $chart = document.getElementById('ciChart');
    if ($chart && window.LightweightCharts) {
      $chart.innerHTML = '';
      ciChart = LightweightCharts.createChart($chart, {
        width: $chart.clientWidth, height: 220,
        layout: {
          background: { type: 'solid', color: 'transparent' },
          textColor: '#8892a8',
          fontFamily: "'Inter', sans-serif",
          fontSize: 11,
        },
        grid: {
          vertLines: { color: 'rgba(31,39,52,0.4)' },
          horzLines: { color: 'rgba(31,39,52,0.4)' },
        },
        rightPriceScale: { borderColor: '#1f2734' },
        timeScale: { borderColor: '#1f2734', timeVisible: false },
      });

      const series = ciChart.addAreaSeries({
        lineColor: profit >= 0 ? '#0ecb81' : '#f6465d',
        lineWidth: 2,
        topColor: profit >= 0 ? 'rgba(14,203,129,0.3)' : 'rgba(246,70,93,0.3)',
        bottomColor: 'transparent',
        priceLineVisible: false,
      });

      const points = [];
      for (let i = 0; i <= Math.min(periods, 500); i++) {
        const actual = Math.round((i / Math.min(periods, 500)) * periods);
        points.push({ time: i, value: start * Math.pow(1 + retPct, actual) });
      }
      // Use indices as "time" for simplicity
      series.setData(points.map((p, i) => ({ time: i + 1, value: p.value })));
      ciChart.timeScale().fitContent();
    }
  }

  /* ============================================================
   *  LEVERAGE
   * ============================================================ */
  function calcLeverage() {
    const balance = num('lvBalance');
    const entry = num('lvEntry');
    const lev = Math.max(1, num('lvLeverage'));
    const side = document.getElementById('lvSide')?.value || 'long';
    const mmr = Math.max(0, num('lvMMR')) / 100;

    if (!balance || !entry) {
      ['lvPosition', 'lvQty', 'lvLiq', 'lvLiqDist', 'lvMargin'].forEach(id => setText(id, '—'));
      return;
    }

    const position = balance * lev;
    const qty = position / entry;
    const margin = balance;

    // Liquidation price formula (simplified for isolated margin):
    // Long:  liq = entry * (1 - (1/lev) + mmr)
    // Short: liq = entry * (1 + (1/lev) - mmr)
    let liq;
    if (side === 'long') {
      liq = entry * (1 - (1 / lev) + mmr);
    } else {
      liq = entry * (1 + (1 / lev) - mmr);
    }
    const liqDist = Math.abs((liq - entry) / entry) * 100;

    setText('lvPosition', fmtUsd(position));
    setText('lvQty', fmtNum(qty, 8));
    setText('lvLiq', '$' + fmtNum(liq, 8));
    setText('lvLiqDist', liqDist.toFixed(2) + '%');
    setText('lvMargin', fmtUsd(margin));
  }

  /* ============================================================
   *  WIRE INPUTS
   * ============================================================ */
  const inputs = [
    ['psBalance', calcPosition], ['psRiskPct', calcPosition], ['psEntry', calcPosition], ['psStop', calcPosition],
    ['rrEntry', calcRR], ['rrStop', calcRR], ['rrTarget', calcRR], ['rrQty', calcRR],
    ['pnlEntry', calcPnl], ['pnlExit', calcPnl], ['pnlQty', calcPnl], ['pnlFeePct', calcPnl],
    ['ciStart', calcCompound], ['ciReturn', calcCompound], ['ciPeriods', calcCompound],
    ['lvBalance', calcLeverage], ['lvEntry', calcLeverage], ['lvLeverage', calcLeverage], ['lvMMR', calcLeverage],
  ];
  inputs.forEach(([id, fn]) => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', fn);
  });

  ['pnlSide', 'lvSide'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('change', id === 'pnlSide' ? calcPnl : calcLeverage);
  });

  // Run once
  calcPosition();
  calcRR();
  calcPnl();
  calcCompound();
  calcLeverage();

  // Resize chart
  window.addEventListener('resize', () => {
    if (ciChart) {
      const $c = document.getElementById('ciChart');
      if ($c) ciChart.applyOptions({ width: $c.clientWidth });
    }
  });
})();