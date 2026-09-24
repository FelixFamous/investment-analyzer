/**
 * AlphaEdge · CSV Export UI
 * Dropdown button that opens a small modal to pick:
 *   - What to export (trades, holdings, cash, snapshots, all)
 *   - Date range (optional)
 *   - Symbol filter (optional)
 * Then triggers a browser download.
 */

(function () {
  'use strict';

  const API = (window.ALPHAEDGE && window.ALPHAEDGE.api) || '';

  /* ---------- Toast ---------- */
  function toast(msg, kind) {
    let el = document.getElementById('ae-toast');
    if (!el) {
      el = document.createElement('div');
      el.id = 'ae-toast';
      el.className = 'toast';
      document.body.appendChild(el);
    }
    el.textContent = msg;
    el.className = 'toast show' + (kind ? ' toast-' + kind : '');
    clearTimeout(el._t);
    el._t = setTimeout(() => { el.className = 'toast'; }, 2600);
  }

  /* ---------- Modal helpers ---------- */
  function modal(html) {
    closeModal();
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.innerHTML = '<div class="modal">' + html + '</div>';
    document.body.appendChild(overlay);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
    return overlay;
  }
  function closeModal() {
    document.querySelectorAll('.modal-overlay').forEach(n => n.remove());
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  /* ---------- Export options ---------- */
  const EXPORT_TYPES = [
    { value: 'trades',    label: 'Trade History',      desc: 'Every buy and sell with realized P&L' },
    { value: 'holdings',  label: 'Current Holdings',   desc: 'Open positions with live market value' },
    { value: 'cash',      label: 'Cash Transactions',  desc: 'Deposits and withdrawals' },
    { value: 'snapshots', label: 'Portfolio Snapshots', desc: 'Daily equity history' },
    { value: 'all',       label: 'Everything',          desc: 'All of the above in one file' },
  ];

  const SYMBOLS = [
    '', 'BTC','ETH','SOL','BNB','XRP','DOGE','ADA','PEPE',
    'AAPL','MSFT','NVDA','TSLA','AMD','META','GOOGL','AMZN','NFLX',
    'SPX','NDX','DJI','VIX'
  ];

  function openExportModal() {
    const typeOptions = EXPORT_TYPES.map((t, i) =>
      `<option value="${t.value}">${t.label}</option>`
    ).join('');

    const symbolOptions = SYMBOLS.map(s =>
      `<option value="${s}">${s === '' ? '— All symbols —' : s}</option>`
    ).join('');

    const overlay = modal(`
      <h3>📥 Export Data</h3>
      <div class="sub">Download your account data as a CSV file.</div>

      <div class="form-group">
        <label for="exportType">What to export</label>
        <select id="exportType">${typeOptions}</select>
        <small class="text-dim" id="exportTypeDesc" style="display:block;margin-top:6px;font-size:11px;">
          ${EXPORT_TYPES[0].desc}
        </small>
      </div>

      <div class="form-group">
        <label>Date range (optional)</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
          <input type="date" id="exportFrom" placeholder="From">
          <input type="date" id="exportTo"   placeholder="To">
        </div>
        <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
          Leave blank to export the full history.
        </small>
      </div>

      <div class="form-group">
        <label for="exportSymbol">Symbol filter (optional)</label>
        <select id="exportSymbol">${symbolOptions}</select>
      </div>

      <div class="export-quick-picks">
        <button type="button" class="export-quick" data-quick="last7">Last 7 days</button>
        <button type="button" class="export-quick" data-quick="last30">Last 30 days</button>
        <button type="button" class="export-quick" data-quick="ytd">Year to date</button>
        <button type="button" class="export-quick" data-quick="all">All time</button>
      </div>

      <div class="modal-actions">
        <button class="btn" id="ae-cancel">Cancel</button>
        <button class="btn btn-primary" id="ae-confirm-export">📥 Download CSV</button>
      </div>
    `);

    const $type   = overlay.querySelector('#exportType');
    const $desc   = overlay.querySelector('#exportTypeDesc');
    const $from   = overlay.querySelector('#exportFrom');
    const $to     = overlay.querySelector('#exportTo');
    const $symbol = overlay.querySelector('#exportSymbol');

    // Update description on type change
    $type.addEventListener('change', () => {
      const t = EXPORT_TYPES.find(x => x.value === $type.value);
      $desc.textContent = t ? t.desc : '';
    });

    // Quick date picks
    overlay.querySelectorAll('[data-quick]').forEach(btn => {
      btn.addEventListener('click', () => {
        const today = new Date();
        const iso = d => d.toISOString().slice(0, 10);

        if (btn.dataset.quick === 'last7') {
          const d = new Date(); d.setDate(d.getDate() - 7);
          $from.value = iso(d); $to.value = iso(today);
        } else if (btn.dataset.quick === 'last30') {
          const d = new Date(); d.setDate(d.getDate() - 30);
          $from.value = iso(d); $to.value = iso(today);
        } else if (btn.dataset.quick === 'ytd') {
          $from.value = today.getFullYear() + '-01-01';
          $to.value = iso(today);
        } else {
          $from.value = ''; $to.value = '';
        }
      });
    });

    overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
    overlay.querySelector('#ae-confirm-export').addEventListener('click', () => {
      const params = new URLSearchParams();
      params.set('type', $type.value);
      if ($from.value)   params.set('from', $from.value);
      if ($to.value)     params.set('to', $to.value);
      if ($symbol.value) params.set('symbol', $symbol.value);

      // Trigger download
      const url = API + '/api/export.php?' + params.toString();

      // Use a temporary <a> with download attribute
      const a = document.createElement('a');
      a.href = url;
      a.style.display = 'none';
      document.body.appendChild(a);
      a.click();
      setTimeout(() => a.remove(), 100);

      toast('Download started…', 'success');
      closeModal();
    });

    $type.focus();
  }

  /* ---------- Wire any button with [data-export] ---------- */
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-export]');
    if (!btn) return;
    e.preventDefault();
    openExportModal();
  });

  // Expose for other scripts
  window.aeOpenExport = openExportModal;
})();