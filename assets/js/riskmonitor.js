(function () {
  'use strict';

  const API = window.RM_API || '';
  const $root = document.getElementById('rmRoot');
  const $loading = document.getElementById('rmLoading');
  const $content = document.getElementById('rmContent');
  if (!$content) return;

  function fmtUsd(n) {
    n = Number(n);
    const neg = n < 0;
    return (neg ? '-$' : '$') + Math.abs(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  async function load() {
    try {
      const r = await fetch(API + '/api/risk-monitor.php', { credentials: 'same-origin' });
      const json = await r.json();
      if (json.error) throw new Error(json.error);
      render(json);
    } catch (err) {
      $loading.innerHTML = '<div style="color:var(--red);padding:40px;">Failed to load risk data: ' + err.message + '</div>';
    }
  }

  function render(d) {
    $loading.style.display = 'none';
    $content.style.display = 'block';

    const riskLevelText = {
      low: 'Your portfolio risk profile looks healthy',
      medium: 'Moderate risk — some adjustments recommended',
      high: 'High risk — take action to protect capital',
    }[d.risk_level];

    const gaugeColor = d.risk_level === 'low' ? '#0ecb81' : (d.risk_level === 'medium' ? '#f0b90b' : '#f6465d');
    const circumference = 2 * Math.PI * 68;
    const offset = circumference - (d.risk_score / 100) * circumference;

    let html = `
      <div class="rm-hero risk-${d.risk_level}">
        <div>
          <div class="rm-score-label">AI Risk Assessment</div>
          <div class="rm-score-title">${riskLevelText}</div>
          <div class="rm-score-desc">
            Equity <strong style="color:var(--text);">${fmtUsd(d.equity)}</strong> ·
            Cash <strong style="color:var(--text);">${d.cash_pct.toFixed(1)}%</strong> ·
            Heat <strong style="color:var(--text);">${d.portfolio_heat_pct.toFixed(2)}%</strong> of ${d.max_heat_pct}% max ·
            ${d.positions_count} positions
          </div>
        </div>
        <div class="rm-gauge">
          <svg width="160" height="160" viewBox="0 0 160 160">
            <circle class="rm-gauge-bg" cx="80" cy="80" r="68"/>
            <circle class="rm-gauge-fill" cx="80" cy="80" r="68"
                    stroke="${gaugeColor}"
                    stroke-dasharray="${circumference}"
                    stroke-dashoffset="${offset}"/>
          </svg>
          <div class="rm-gauge-label">
            <div class="rm-gauge-num" style="color:${gaugeColor};">${d.risk_score}</div>
            <div class="rm-gauge-sub">Risk Score</div>
          </div>
        </div>
      </div>

      <div class="rm-stats">
        <div class="rm-stat">
          <div class="rm-stat-label">Open Risk</div>
          <div class="rm-stat-value ${d.total_open_risk > d.equity * 0.05 ? 'warn' : 'ok'}">${fmtUsd(d.total_open_risk)}</div>
        </div>
        <div class="rm-stat">
          <div class="rm-stat-label">Unprotected</div>
          <div class="rm-stat-value ${d.unprotected_count > 0 ? 'danger' : 'ok'}">${d.unprotected_count}</div>
        </div>
        <div class="rm-stat">
          <div class="rm-stat-label">Cash Buffer</div>
          <div class="rm-stat-value ${d.cash_pct < 10 ? 'warn' : 'ok'}">${d.cash_pct.toFixed(1)}%</div>
        </div>
        <div class="rm-stat">
          <div class="rm-stat-label">Peak Equity</div>
          <div class="rm-stat-value">${fmtUsd(d.peak_equity)}</div>
        </div>
      </div>
    `;

    if (d.alerts.length) {
      html += '<div class="rm-section-title">⚠️ Risk Alerts</div><div>';
      d.alerts.forEach(a => {
        html += `
          <div class="rm-alert ${a.level}">
            <div class="rm-alert-icon">${a.icon}</div>
            <div class="rm-alert-body">
              <div class="rm-alert-title">${esc(a.title)}</div>
              <div class="rm-alert-detail">${esc(a.detail)}</div>
            </div>
          </div>
        `;
      });
      html += '</div>';
    } else {
      html += `
        <div class="rm-section-title">✅ Risk Alerts</div>
        <div class="rm-alert info">
          <div class="rm-alert-icon">🎉</div>
          <div class="rm-alert-body">
            <div class="rm-alert-title">No alerts</div>
            <div class="rm-alert-detail">Your portfolio is within all configured risk limits.</div>
          </div>
        </div>
      `;
    }

    if (d.recommendations.length) {
      html += '<div class="rm-section-title">💡 AI Recommendations</div><div class="rm-recommendations">';
      d.recommendations.forEach(r => { html += `<div class="rm-rec">${esc(r)}</div>`; });
      html += '</div>';
    }

    if (d.holdings.length) {
      html += '<div class="rm-section-title">📊 Position Exposure</div><div class="panel" style="padding:0;overflow:hidden;">';
      const totalVal = d.holdings.reduce((s, h) => s + h.value, 0) || 1;
      d.holdings.forEach(h => {
        const pct = (h.value / totalVal) * 100;
        const pnlColor = h.pnl >= 0 ? 'var(--green)' : 'var(--red)';
        const statusCls = h.has_sl ? 'safe' : 'risk';
        const statusTxt = h.has_sl ? 'Protected' : 'No SL';
        html += `
          <div class="rm-holding-row">
            <div class="rm-holding-sym">${esc(h.symbol)}</div>
            <div class="rm-holding-bar"><div class="rm-holding-bar-fill" style="width:${pct}%"></div></div>
            <div style="text-align:right;font-family:var(--mono);font-weight:700;">${fmtUsd(h.value)}</div>
            <div style="text-align:right;font-family:var(--mono);color:${pnlColor};font-weight:700;">${h.pnl >= 0 ? '+' : ''}${fmtUsd(h.pnl).replace('-$','-$')}</div>
            <div style="text-align:right;"><span class="rm-holding-status ${statusCls}">${statusTxt}</span></div>
          </div>
        `;
      });
      html += '</div>';
    }

    $content.innerHTML = html;
  }

  function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  load();
})();