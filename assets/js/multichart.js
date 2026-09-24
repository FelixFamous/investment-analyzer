(function () {
  'use strict';

  const API = window.MC_API || '';
  const CSRF = window.MC_CSRF || '';
  const SYMBOLS = window.MC_SYMBOLS || [];
  const DEFAULTS = window.MC_DEFAULT_SYMBOLS || ['BTC','ETH'];

  const $grid = document.getElementById('mcGrid');
  const $template = document.getElementById('mcPaneTemplate');
  if (!$grid || !$template) return;

  let layout = '2x1';
  let syncSymbols = false;
  let panes = []; // { symbol, chart, series, container }

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
    el._t = setTimeout(() => { el.className = 'toast'; }, 2800);
  }

  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1) return '$' + n.toFixed(2);
    if (n >= 0.01) return '$' + n.toFixed(4);
    return '$' + n.toFixed(7);
  }

  /* ============================================================
   *  PANE CREATION
   * ============================================================ */
  function createPane(symbol, slotIndex) {
    const node = $template.content.cloneNode(true);
    const pane = node.querySelector('.mc-pane');
    const select = node.querySelector('.mc-symbol-select');
    const chartDiv = node.querySelector('.mc-pane-chart');
    const closeBtn = node.querySelector('.mc-pane-close');
    const priceEl = node.querySelector('.mc-pane-price');
    const changeEl = node.querySelector('.mc-pane-change');

    // Populate symbols
    SYMBOLS.forEach(s => {
      const opt = document.createElement('option');
      opt.value = s;
      opt.textContent = s;
      if (s === symbol) opt.selected = true;
      select.appendChild(opt);
    });

    $grid.appendChild(node);

    // Create chart
    const chart = LightweightCharts.createChart(chartDiv, {
      width: chartDiv.clientWidth || 400,
      height: chartDiv.clientHeight || 260,
      layout: {
        background: { type: 'solid', color: 'transparent' },
        textColor: '#8892a8',
        fontFamily: "'Inter', sans-serif",
        fontSize: 10,
      },
      grid: {
        vertLines: { color: 'rgba(31,39,52,0.4)' },
        horzLines: { color: 'rgba(31,39,52,0.4)' },
      },
      rightPriceScale: { borderColor: '#1f2734' },
      timeScale: { borderColor: '#1f2734', timeVisible: true, secondsVisible: false },
      crosshair: { mode: 0 },
    });

    const series = chart.addCandlestickSeries({
      upColor: '#0ecb81', downColor: '#f6465d',
      borderUpColor: '#0ecb81', borderDownColor: '#f6465d',
      wickUpColor: '#0ecb81', wickDownColor: '#f6465d',
      priceLineVisible: false,
      lastValueVisible: true,
    });

    const paneObj = {
      symbol,
      chart,
      series,
      chartDiv,
      priceEl,
      changeEl,
      select,
    };

    panes.push(paneObj);

    // Load data
    loadPaneData(paneObj);

    // Wire symbol change
    select.addEventListener('change', () => {
      paneObj.symbol = select.value;
      if (syncSymbols) {
        panes.forEach(p => { p.select.value = select.value; p.symbol = select.value; loadPaneData(p); });
      } else {
        loadPaneData(paneObj);
      }
    });

    // Close pane
    closeBtn.addEventListener('click', () => {
      const idx = panes.indexOf(paneObj);
      if (idx >= 0) panes.splice(idx, 1);
      try { chart.remove(); } catch {}
      pane.remove();
    });

    // Resize observer
    if (typeof ResizeObserver !== 'undefined') {
      const ro = new ResizeObserver(() => {
        if (paneObj.chart) {
          paneObj.chart.applyOptions({
            width: chartDiv.clientWidth,
            height: chartDiv.clientHeight,
          });
        }
      });
      ro.observe(chartDiv);
    }
  }

  /* ============================================================
   *  LOAD DATA INTO A PANE
   * ============================================================ */
  async function loadPaneData(paneObj) {
    const interval = document.getElementById('mcInterval').value;
    const range = document.getElementById('mcRange').value;
    const chartType = document.getElementById('mcChartType').value;

    try {
      const url = API + '/api/history.php?symbol=' + encodeURIComponent(paneObj.symbol)
                + '&range=' + range + '&interval=' + interval;
      const r = await fetch(url);
      const json = await r.json();
      if (!json.candles || !json.candles.length) throw new Error('No data');

      const candles = json.candles.map(c => ({
        time: Math.floor(c.t / 1000),
        open: c.o, high: c.h, low: c.l, close: c.c,
      }));

      // Rebuild series to change type
      try { paneObj.chart.removeSeries(paneObj.series); } catch {}
      let series;
      if (chartType === 'line') {
        series = paneObj.chart.addLineSeries({ color: '#f0b90b', lineWidth: 2, priceLineVisible: false });
        series.setData(candles.map(c => ({ time: c.time, value: c.close })));
      } else if (chartType === 'area') {
        series = paneObj.chart.addAreaSeries({
          lineColor: '#f0b90b', lineWidth: 2,
          topColor: 'rgba(240,185,11,0.3)', bottomColor: 'transparent',
          priceLineVisible: false,
        });
        series.setData(candles.map(c => ({ time: c.time, value: c.close })));
      } else {
        series = paneObj.chart.addCandlestickSeries({
          upColor: '#0ecb81', downColor: '#f6465d',
          borderUpColor: '#0ecb81', borderDownColor: '#f6465d',
          wickUpColor: '#0ecb81', wickDownColor: '#f6465d',
          priceLineVisible: false, lastValueVisible: true,
        });
        series.setData(candles);
      }
      paneObj.series = series;
      paneObj.chart.timeScale().fitContent();

      // Price + change (from latest two candles)
      const last = candles[candles.length - 1];
      const prev = candles[candles.length - 2] || last;
      const changePct = prev.close ? ((last.close - prev.close) / prev.close) * 100 : 0;

      paneObj.priceEl.textContent = fmtPrice(last.close);
      paneObj.changeEl.textContent = (changePct >= 0 ? '+' : '') + changePct.toFixed(2) + '%';
      paneObj.changeEl.className = 'mc-pane-change ' + (changePct >= 0 ? 'pos' : 'neg');
    } catch (err) {
      paneObj.priceEl.textContent = 'Error';
      paneObj.changeEl.textContent = err.message;
      paneObj.changeEl.className = 'mc-pane-change neg';
    }
  }

  /* ============================================================
   *  LAYOUT SWITCHING
   * ============================================================ */
  function setLayout(newLayout) {
    layout = newLayout;
    document.querySelectorAll('[data-layout]').forEach(b => {
      if (b.classList.contains('mc-layout-btn')) b.classList.toggle('active', b.dataset.layout === layout);
    });
    $grid.dataset.layout = layout;

    // Ensure we have the right number of panes
    const required = layout === 'single' ? 1 : (layout === '2x1' ? 2 : 4);
    while (panes.length > required) {
      const p = panes.pop();
      try { p.chart.remove(); } catch {}
      p.chartDiv.closest('.mc-pane').remove();
    }
    while (panes.length < required) {
      const sym = DEFAULTS[panes.length] || SYMBOLS[0];
      createPane(sym, panes.length);
    }

    // Resize after layout change
    setTimeout(() => {
      panes.forEach(p => {
        p.chart.applyOptions({
          width: p.chartDiv.clientWidth,
          height: p.chartDiv.clientHeight,
        });
      });
    }, 100);
  }

  /* ============================================================
   *  WORKSPACES
   * ============================================================ */
  async function openWorkspaceModal() {
    let wsList = [];
    try {
      const r = await fetch(API + '/api/workspaces.php?action=list', { credentials: 'same-origin' });
      const json = await r.json();
      wsList = json.workspaces || [];
    } catch {}

    const $m = document.getElementById('mcWorkspaceModal');
    $m.style.display = 'flex';
    $m.innerHTML = `
      <div class="modal" style="max-width:520px;">
        <h3>💾 Save Workspace</h3>
        <div class="sub">Save the current layout + symbols as a preset you can reload anytime.</div>

        <div class="form-group">
          <label for="wsName">Name</label>
          <input type="text" id="wsName" placeholder="e.g. Scalping 4-chart" maxlength="60" autofocus>
        </div>

        <div class="modal-actions" style="margin-top:6px;">
          <button type="button" class="btn" id="wsCancel">Cancel</button>
          <button type="button" class="btn btn-primary" id="wsSave">Save Workspace</button>
        </div>

        ${wsList.length ? `
          <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border);">
            <h4 style="font-size:13px;color:var(--text);margin-bottom:10px;">Your saved workspaces</h4>
            ${wsList.map(w => `
              <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border);">
                <div>
                  <div style="font-weight:700;font-size:13px;">${esc(w.name)}</div>
                  <div style="font-size:11px;color:var(--text-faint);font-family:var(--mono);">${w.layout} · ${w.interval} · ${(w.symbols || []).join(', ')}</div>
                </div>
                <div style="display:flex;gap:6px;">
                  <button class="btn btn-sm" data-load-ws="${w.id}">Load</button>
                  <button class="btn btn-sm btn-danger" data-del-ws="${w.id}">✕</button>
                </div>
              </div>
            `).join('')}
          </div>
        ` : ''}
      </div>
    `;

    $m.querySelector('#wsCancel').addEventListener('click', closeWsModal);

    $m.querySelector('#wsSave').addEventListener('click', async () => {
      const name = $m.querySelector('#wsName').value.trim();
      if (!name) { toast('Name required', 'error'); return; }

      const symbols = panes.map(p => p.symbol);
      const indicators = window.CHART_INDICATORS || {};

      try {
        const r = await fetch(API + '/api/workspaces.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
            action: 'save',
            name,
            layout,
            symbols_json: JSON.stringify(symbols),
            interval: document.getElementById('mcInterval').value,
            range_label: document.getElementById('mcRange').value,
            chart_type: document.getElementById('mcChartType').value,
            indicators_json: JSON.stringify(indicators),
            csrf: CSRF,
          }),
        });
        const json = await r.json();
        if (!r.ok) throw new Error(json.error || 'Save failed');
        toast(json.message || 'Workspace saved', 'success');
        closeWsModal();
      } catch (err) {
        toast(err.message, 'error');
      }
    });

    $m.querySelectorAll('[data-load-ws]').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.loadWs;
        const w = wsList.find(x => x.id == id);
        if (w) applyWorkspace(w);
      });
    });

    $m.querySelectorAll('[data-del-ws]').forEach(btn => {
      btn.addEventListener('click', async () => {
        if (!confirm('Delete this workspace?')) return;
        try {
          await fetch(API + '/api/workspaces.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'delete', id: btn.dataset.delWs, csrf: CSRF }),
          });
          toast('Deleted', 'success');
          openWorkspaceModal();
        } catch {}
      });
    });

    function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
  }

  function applyWorkspace(w) {
    // Set layout
    setLayout(w.layout);

    // Set controls
    document.getElementById('mcInterval').value = w.interval || '1h';
    document.getElementById('mcRange').value = w.range_label || '7d';
    document.getElementById('mcChartType').value = w.chart_type || 'candles';

    // Replace symbols
    const syms = w.symbols || [];
    panes.forEach((p, i) => {
      const newSym = syms[i];
      if (!newSym) return;
      p.symbol = newSym;
      p.select.value = newSym;
      loadPaneData(p);
    });

    // Apply indicators
    if (w.indicators && typeof w.indicators === 'object') {
      window.CHART_INDICATORS = { ...w.indicators };
    }

    closeWsModal();
    toast('Workspace loaded', 'success');
  }

  function closeWsModal() {
    const $m = document.getElementById('mcWorkspaceModal');
    if ($m) { $m.style.display = 'none'; $m.innerHTML = ''; }
  }

  /* ============================================================
   *  WIRE
   * ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.mc-layout-btn').forEach(btn => {
      btn.addEventListener('click', () => setLayout(btn.dataset.layout));
    });

    document.getElementById('mcInterval').addEventListener('change', () => panes.forEach(loadPaneData));
    document.getElementById('mcRange').addEventListener('change', () => panes.forEach(loadPaneData));
    document.getElementById('mcChartType').addEventListener('change', () => panes.forEach(loadPaneData));

    document.getElementById('mcSync').addEventListener('click', () => {
      syncSymbols = !syncSymbols;
      document.getElementById('mcSync').textContent = syncSymbols ? '🔗 Synced' : '🔗 Sync symbols';
      toast(syncSymbols ? 'Symbol sync ON' : 'Symbol sync OFF', 'info');
    });

    document.getElementById('mcSaveWs').addEventListener('click', openWorkspaceModal);
    document.getElementById('mcLoadWs').addEventListener('click', openWorkspaceModal);

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeWsModal(); });

    // Initial layout
    setLayout('2x1');
  });
})();