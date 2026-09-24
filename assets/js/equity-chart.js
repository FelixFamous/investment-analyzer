/**
 * AlphaEdge · Portfolio equity curve
 * Uses TradingView Lightweight Charts, line + area style.
 * Data from /api/equity-history.php.
 */

(function () {
  'use strict';

  const API = window.EQUITY_API || '';
  const $container = document.getElementById('equityChart');
  const $wrap   = document.getElementById('equityChartWrap');
  const $loading = document.getElementById('equityChartLoading');
  const $error   = document.getElementById('equityChartError');
  const $stats   = document.getElementById('equityStats');
  const $change  = document.getElementById('equityChange');

  if (!$container) return;

  let chart = null;
  let areaSeries = null;
  let initialized = false;
  let currentRange = '30';

  /* ============================================================
   *  LIBRARY
   * ============================================================ */
  function loadScript(src, timeout = 6000) {
    return new Promise((resolve) => {
      const s = document.createElement('script');
      s.src = src; s.async = true;
      let done = false;
      s.onload = () => { if (!done) { done = true; resolve(true); } };
      s.onerror = () => { if (!done) { done = true; resolve(false); } };
      document.head.appendChild(s);
      setTimeout(() => { if (!done) { done = true; resolve(false); } }, timeout);
    });
  }
  async function ensureLibrary() {
    if (typeof window.LightweightCharts !== 'undefined') return true;
    const cdns = [
      'https://unpkg.com/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js',
      'https://cdn.jsdelivr.net/npm/lightweight-charts@4.1.3/dist/lightweight-charts.standalone.production.js',
    ];
    for (const src of cdns) {
      const ok = await loadScript(src);
      if (ok && typeof window.LightweightCharts !== 'undefined') return true;
    }
    return false;
  }

  /* ============================================================
   *  HELPERS
   * ============================================================ */
  function fmtUsd(n) {
    n = Number(n);
    const neg = n < 0;
    const v = Math.abs(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return (neg ? '-$' : '$') + v;
  }
  function fmtCompactUsd(n) {
    n = Number(n);
    if (Math.abs(n) >= 1e6) return '$' + (n / 1e6).toFixed(2) + 'M';
    if (Math.abs(n) >= 1e3) return '$' + (n / 1e3).toFixed(1) + 'K';
    return '$' + n.toFixed(0);
  }
  function fmtDateShort(sec) {
    const d = new Date(sec * 1000);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
  }

  function showLoading(msg) {
    if ($loading) { $loading.textContent = msg; $loading.style.display = 'block'; }
    if ($error) $error.style.display = 'none';
    if ($wrap) $wrap.style.display = 'none';
  }
  function showChart() {
    if ($loading) $loading.style.display = 'none';
    if ($error) $error.style.display = 'none';
    if ($wrap) $wrap.style.display = 'block';
  }
  function showError(msg) {
    if ($loading) $loading.style.display = 'none';
    if ($wrap) $wrap.style.display = 'none';
    if ($error) { $error.textContent = msg; $error.style.display = 'block'; }
  }

  function containerHeight() {
    const w = window.innerWidth;
    if (w < 500) return Math.max(240, Math.round(window.innerHeight * 0.35));
    if (w < 900) return 300;
    return 340;
  }

  /* ============================================================
   *  INIT
   * ============================================================ */
  function initChart() {
    if (initialized) return;
    const LWC = window.LightweightCharts;
    if (!LWC) throw new Error('Library missing');

    chart = LWC.createChart($container, {
      width: $container.clientWidth || 800,
      height: containerHeight(),
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
      rightPriceScale: {
        borderColor: '#1f2734',
        scaleMargins: { top: 0.1, bottom: 0.1 },
      },
      timeScale: {
        borderColor: '#1f2734',
        timeVisible: false,
        secondsVisible: false,
      },
      crosshair: {
        mode: 0,
        vertLine: { color: '#f0b90b', width: 1, style: 2, labelBackgroundColor: '#f0b90b' },
        horzLine: { color: '#f0b90b', width: 1, style: 2, labelBackgroundColor: '#f0b90b' },
      },
      handleScroll: { mouseWheel: false, pressedMouseMove: true },
      handleScale:  { mouseWheel: false, pinch: true, axisPressedMouseMove: false },
    });

    areaSeries = chart.addAreaSeries({
      lineColor: '#0ecb81',
      lineWidth: 2,
      topColor: 'rgba(14,203,129,0.28)',
      bottomColor: 'rgba(14,203,129,0.02)',
      priceLineVisible: false,
      lastValueVisible: true,
      crosshairMarkerVisible: true,
      crosshairMarkerRadius: 5,
      crosshairMarkerBorderColor: '#0ecb81',
      crosshairMarkerBackgroundColor: '#0a0b0f',
    });

    chart.subscribeCrosshairMove((param) => {
      if (!param.time || !param.point) {
        updateChangeFromSeries();
        return;
      }
      const p = param.seriesData.get(areaSeries);
      if (p) {
        if ($change) {
          $change.innerHTML = `${fmtDateShort(param.time)} · <strong style="color:var(--text);">${fmtUsd(p.value)}</strong>`;
        }
      }
    });

    window.addEventListener('resize', () => {
      if (chart && $container) {
        chart.applyOptions({
          width: $container.clientWidth,
          height: containerHeight(),
        });
      }
    });

    initialized = true;
  }

  let cachedStats = null;
  function updateChangeFromSeries() {
    if (!cachedStats) return;
    const sign = cachedStats.delta >= 0 ? '+' : '-';
    const color = cachedStats.delta >= 0 ? 'var(--green)' : 'var(--red)';
    if ($change) {
      $change.innerHTML =
        `<span style="color:${color};font-weight:700;">${sign}${fmtUsd(Math.abs(cachedStats.delta))} (${cachedStats.pct >= 0 ? '+' : ''}${cachedStats.pct.toFixed(2)}%)</span>`;
    }
  }

  /* ============================================================
   *  LOAD
   * ============================================================ */
  async function loadEquity(days) {
    currentRange = days;
    showLoading('Loading equity curve…');

    try {
      const url = API + '/api/equity-history.php?days=' + encodeURIComponent(days);
      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const json = await res.json();
      if (json.error) throw new Error(json.error);
      const points = json.points || [];

      if (!points.length) {
        showError('No snapshots yet — visit the dashboard to start recording.');
        return;
      }

      showChart();
      await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(r)));

      if (!initialized) initChart();

      // Format for Lightweight Charts (time = 'YYYY-MM-DD' or unix seconds)
      const series = points.map(p => ({
        time: Math.floor(new Date(p.date + 'T00:00:00Z').getTime() / 1000),
        value: p.equity,
      }));

      areaSeries.setData(series);
      chart.timeScale().fitContent();

      // Update series color based on overall trend
      const trending = json.delta >= 0;
      areaSeries.applyOptions({
        lineColor: trending ? '#0ecb81' : '#f6465d',
        topColor: trending ? 'rgba(14,203,129,0.28)' : 'rgba(246,70,93,0.24)',
        bottomColor: trending ? 'rgba(14,203,129,0.02)' : 'rgba(246,70,93,0.02)',
        crosshairMarkerBorderColor: trending ? '#0ecb81' : '#f6465d',
      });

      cachedStats = json;

      if ($stats) {
        $stats.textContent = json.count + ' day' + (json.count === 1 ? '' : 's')
          + ' · high ' + fmtCompactUsd(json.high)
          + ' · low ' + fmtCompactUsd(json.low);
      }
      updateChangeFromSeries();

      requestAnimationFrame(() => {
        if ($container.clientWidth > 0) {
          chart.applyOptions({
            width: $container.clientWidth,
            height: containerHeight(),
          });
        }
      });

    } catch (err) {
      console.error('Equity chart error:', err);
      showError('Could not load equity curve: ' + (err.message || 'unknown'));
    }
  }

  /* ============================================================
   *  RANGE TABS
   * ============================================================ */
  document.querySelectorAll('#equityRangeTabs [data-eq-range]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#equityRangeTabs [data-eq-range]').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      loadEquity(btn.dataset.eqRange);
    });
  });

  /* ============================================================
   *  BOOT
   * ============================================================ */
  (async function boot() {
    const ok = await ensureLibrary();
    if (!ok) { showError('Chart library failed to load.'); return; }
    loadEquity('30');
  })();
})();