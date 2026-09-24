(function () {
  'use strict';

  const API = window.ANALYTICS_API || '';
  const $ = id => document.getElementById(id);

  function fmtUsd(n) {
    n = Number(n);
    const neg = n < 0;
    return (neg ? '-$' : '$') + Math.abs(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function buildTable(rows, columns, opts = {}) {
    // rows: array of [label, dataObj]
    if (!rows.length) return '<div class="empty-state" style="padding:24px;">No data.</div>';

    const hasBar = opts.bar === true;
    const pnlKey = opts.pnlKey || 'pnl';

    let html = '<table class="an-table"><thead><tr>';
    columns.forEach(c => { html += `<th${c.align === 'right' ? ' style="text-align:right;"' : ''}>${c.label}</th>`; });
    html += '</tr></thead><tbody>';

    rows.forEach(([label, data]) => {
      html += '<tr>';
      columns.forEach(c => {
        const val = c.render ? c.render(data, label) : data[c.key];
        const cls = c.num ? 'num' + (data[pnlKey] >= 0 ? ' pos' : ' neg') : '';
        const style = c.align === 'right' ? ' style="text-align:right;"' : '';
        html += `<td class="${cls}"${style}>${val}</td>`;
      });

      if (hasBar) {
        const wr = data.win_rate || 0;
        const cls = wr >= 60 ? '' : (wr >= 40 ? 'mid' : 'low');
        html += `<td style="width:130px;"><div class="an-bar"><div class="an-bar-fill ${cls}" style="width:${wr}%;"></div></div></td>`;
      }

      html += '</tr>';
    });

    html += '</tbody></table>';
    return html;
  }

  fetch(API + '/api/analytics.php', { credentials: 'same-origin' })
    .then(r => r.json())
    .then(json => {
      const s = json.summary;

      $('anTotalPnl').textContent = (s.total_pnl >= 0 ? '+' : '-') + fmtUsd(Math.abs(s.total_pnl));
      $('anTotalPnl').className = 'card-value ' + (s.total_pnl >= 0 ? 'positive' : 'negative');
      $('anWinRate').textContent = s.win_rate.toFixed(1) + '%';
      $('anWinLoss').textContent = `${s.win_count} W / ${s.loss_count} L`;
      $('anProfitFactor').textContent = s.profit_factor.toFixed(2);
      $('anProfitFactor').className = 'card-value ' + (s.profit_factor >= 1 ? 'positive' : 'negative');
      $('anExpectancy').textContent = (s.expectancy >= 0 ? '+' : '-') + fmtUsd(Math.abs(s.expectancy));
      $('anExpectancy').className = 'card-value ' + (s.expectancy >= 0 ? 'positive' : 'negative');
      $('anTotal').textContent = s.total_trades;
      $('anAvgWin').textContent = '+' + fmtUsd(s.avg_win);
      $('anAvgLoss').textContent = '-' + fmtUsd(s.avg_loss);
      $('anStreaks').textContent = `${s.longest_win}W / ${s.longest_loss}L`;

      // By Asset
      const assetRows = Object.entries(json.by_asset).map(([sym, d]) => [sym, d]);
      $('anByAsset').innerHTML = buildTable(assetRows, [
        { label: 'Asset', render: (d, label) => `<strong>${label}</strong>` },
        { label: 'Trades', key: 'count', align: 'right' },
        { label: 'Win %', render: d => d.win_rate.toFixed(1) + '%', align: 'right' },
        { label: 'P&L', key: 'pnl', num: true, align: 'right', render: d => fmtUsd(d.pnl) },
      ], { bar: true });

      // By Day
      const dayOrder = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
      const dayRows = dayOrder.map(d => [d, json.by_day[d]]).filter(([, v]) => v.count > 0);
      $('anByDay').innerHTML = buildTable(dayRows, [
        { label: 'Day', render: (d, label) => `<strong>${label}</strong>` },
        { label: 'Trades', key: 'count', align: 'right' },
        { label: 'Win %', render: d => d.win_rate.toFixed(1) + '%', align: 'right' },
        { label: 'P&L', key: 'pnl', num: true, align: 'right', render: d => fmtUsd(d.pnl) },
      ], { bar: true });

      // By Session
      const sessionRows = Object.entries(json.by_session).filter(([, v]) => v.count > 0);
      $('anBySession').innerHTML = buildTable(sessionRows, [
        { label: 'Session', render: (d, label) => `<strong>${label}</strong>` },
        { label: 'Trades', key: 'count', align: 'right' },
        { label: 'Win %', render: d => d.win_rate.toFixed(1) + '%', align: 'right' },
        { label: 'P&L', key: 'pnl', num: true, align: 'right', render: d => fmtUsd(d.pnl) },
      ], { bar: true });

      // By Month
      const monthRows = Object.entries(json.by_month).map(([m, v]) => [m, { pnl: v, count: 0, win_rate: 0 }]);
      $('anByMonth').innerHTML = buildTable(monthRows, [
        { label: 'Month', render: (d, label) => `<strong>${label}</strong>` },
        { label: 'P&L', key: 'pnl', num: true, align: 'right', render: d => fmtUsd(d.pnl) },
      ]);
    })
    .catch(err => {
      console.warn('Analytics failed:', err);
      document.getElementById('analyticsRoot').innerHTML =
        '<div class="empty-state" style="color:var(--red);padding:40px;">Could not load analytics.</div>';
    });
})();