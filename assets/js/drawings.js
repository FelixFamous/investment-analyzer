/**
 * AlphaEdge · Drawing Engine
 * Trendline, Horizontal line, Rectangle, Fibonacci retracement.
 * Drawings are persisted via /api/drawings.php.
 *
 * Works alongside Lightweight Charts by overlaying a canvas.
 * Uses chart's coordinate conversion to translate between
 * screen pixels and (time, price) pairs.
 */

(function () {
  'use strict';

  const API = window.ASSET_API || '';
  const SYMBOL = window.ASSET_SYMBOL || 'BTC';
  const CSRF = window.ALPHAEDGE ? window.ALPHAEDGE.csrf : '';

  const $canvas = document.getElementById('drawingCanvas');
  const $chartContainer = document.getElementById('assetChart');
  if (!$canvas || !$chartContainer) return;

  const ctx = $canvas.getContext('2d');

  /* ============================================================
   *  STATE
   * ============================================================ */
  let activeTool = null;       // 'trendline' | 'hline' | 'rect' | 'fib' | null
  let activeColor = '#f0b90b';
  let drawStart = null;        // { x, y } in canvas pixels
  let mouse = null;            // current mouse position
  let drawings = [];           // [{ id, tool_type, p1_time, p1_price, p2_time, p2_price, color }]
  let renderScheduled = false;

  /* ============================================================
   *  CHART CONVERSION HELPERS
   * ============================================================ */
  function chart() { return window.AlphaChart && window.AlphaChart.chart; }
  function series() { return window.AlphaChart && window.AlphaChart.series; }

  function timeToX(time) {
    const c = chart();
    if (!c) return null;
    try { return c.timeScale().timeToCoordinate(time); } catch { return null; }
  }
  function priceToY(price) {
    const s = series();
    if (!s) return null;
    try { return s.priceToCoordinate(price); } catch { return null; }
  }
  function xToTime(x) {
    const c = chart();
    if (!c) return null;
    try { return c.timeScale().coordinateToTime(x); } catch { return null; }
  }
  function yToPrice(y) {
    const s = series();
    if (!s) return null;
    try { return s.coordinateToPrice(y); } catch { return null; }
  }

  /* ============================================================
   *  CANVAS SIZING — match the chart container exactly
   * ============================================================ */
  function resizeCanvas() {
    const rect = $chartContainer.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    $canvas.width = Math.round(rect.width * dpr);
    $canvas.height = Math.round(rect.height * dpr);
    $canvas.style.width = rect.width + 'px';
    $canvas.style.height = rect.height + 'px';
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    scheduleRender();
  }

  window.addEventListener('resize', resizeCanvas);
  if (typeof ResizeObserver !== 'undefined') {
    new ResizeObserver(resizeCanvas).observe($chartContainer);
  }

  /* ============================================================
   *  RENDER SCHEDULING
   * ============================================================ */
  function scheduleRender() {
    if (renderScheduled) return;
    renderScheduled = true;
    requestAnimationFrame(() => {
      renderScheduled = false;
      renderAll();
    });
  }

  function renderAll() {
    if (!ctx || !$canvas) return;
    const W = parseFloat($canvas.style.width);
    const H = parseFloat($canvas.style.height);
    ctx.clearRect(0, 0, W, H);

    drawings.forEach(d => renderDrawing(d));
    if (activeTool && drawStart && mouse) renderPreview();
  }

  /* ============================================================
   *  RENDER A SINGLE DRAWING
   * ============================================================ */
  function renderDrawing(d) {
    const color = d.color || '#f0b90b';

    if (d.tool_type === 'hline') {
      const y = priceToY(d.p1_price);
      if (y === null) return;
      drawHorizontalLine(y, color, false);
      drawLabel(`── ${fmtPrice(d.p1_price)}`, 12, y - 6, color);
      return;
    }

    const x1 = timeToX(d.p1_time);
    const y1 = priceToY(d.p1_price);
    const x2 = timeToX(d.p2_time);
    const y2 = priceToY(d.p2_price);
    if (x1 === null || y1 === null || x2 === null || y2 === null) return;

    if (d.tool_type === 'trendline') {
      drawLine(x1, y1, x2, y2, color, 2, []);
      drawCircle(x1, y1, 4, color);
      drawCircle(x2, y2, 4, color);
    } else if (d.tool_type === 'rect') {
      drawRect(x1, y1, x2, y2, color);
    } else if (d.tool_type === 'fib') {
      drawFib(x1, y1, x2, y2, color);
    }
  }

  /* ============================================================
   *  LIVE PREVIEW WHILE DRAWING
   * ============================================================ */
  function renderPreview() {
    const { x: x1, y: y1 } = drawStart;
    const x2 = mouse.x;
    const y2 = mouse.y;
    const color = activeColor;

    if (activeTool === 'hline') {
      drawHorizontalLine(y1, color, true);
      drawLabel(`── ${fmtPrice(yToPrice(y1))}`, 12, y1 - 6, color);
      return;
    }

    if (activeTool === 'trendline') {
      drawLine(x1, y1, x2, y2, color, 2, [6, 4]);
    } else if (activeTool === 'rect') {
      drawRect(x1, y1, x2, y2, color, true);
    } else if (activeTool === 'fib') {
      drawFib(x1, y1, x2, y2, color, true);
    }
  }

  /* ============================================================
   *  DRAWING PRIMITIVES
   * ============================================================ */
  function drawLine(x1, y1, x2, y2, color, width = 2, dash = []) {
    ctx.save();
    ctx.strokeStyle = color;
    ctx.lineWidth = width;
    ctx.setLineDash(dash);
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.stroke();
    ctx.restore();
  }

  function drawHorizontalLine(y, color, dashed = false) {
    const W = parseFloat($canvas.style.width);
    ctx.save();
    ctx.strokeStyle = color;
    ctx.lineWidth = 1.5;
    if (dashed) ctx.setLineDash([6, 4]);
    ctx.beginPath();
    ctx.moveTo(0, y);
    ctx.lineTo(W, y);
    ctx.stroke();
    ctx.restore();
  }

  function drawCircle(x, y, r, color) {
    ctx.save();
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.arc(x, y, r, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();
  }

  function drawRect(x1, y1, x2, y2, color, dashed = false) {
    const x = Math.min(x1, x2);
    const y = Math.min(y1, y2);
    const w = Math.abs(x2 - x1);
    const h = Math.abs(y2 - y1);

    ctx.save();
    // Fill
    ctx.fillStyle = hexToRgba(color, 0.12);
    ctx.fillRect(x, y, w, h);
    // Border
    ctx.strokeStyle = color;
    ctx.lineWidth = 1.5;
    if (dashed) ctx.setLineDash([6, 4]);
    ctx.strokeRect(x, y, w, h);
    ctx.restore();
  }

  function drawFib(x1, y1, x2, y2, color, dashed = false) {
    const W = parseFloat($canvas.style.width);
    const levels = [0, 0.236, 0.382, 0.5, 0.618, 0.786, 1];

    ctx.save();
    ctx.strokeStyle = color;
    ctx.lineWidth = 1;
    if (dashed) ctx.setLineDash([5, 4]);

    levels.forEach(lvl => {
      const y = y1 + (y2 - y1) * lvl;
      // Line
      ctx.beginPath();
      ctx.moveTo(Math.min(x1, x2), y);
      ctx.lineTo(Math.max(x1, x2), y);
      ctx.stroke();

      // Label
      const price = yToPrice(y);
      const pct = (lvl * 100).toFixed(1) + '%';
      ctx.fillStyle = color;
      ctx.font = '10px monospace';
      ctx.textAlign = 'left';
      ctx.fillText(`${pct}  ${fmtPrice(price)}`, Math.min(x1, x2) + 4, y - 3);
    });

    // Bounding zone
    ctx.setLineDash([]);
    ctx.strokeStyle = hexToRgba(color, 0.5);
    ctx.lineWidth = 1;
    ctx.strokeRect(
      Math.min(x1, x2),
      Math.min(y1, y2),
      Math.abs(x2 - x1),
      Math.abs(y2 - y1)
    );
    ctx.restore();
  }

  function drawLabel(text, x, y, color) {
    ctx.save();
    ctx.font = 'bold 11px monospace';
    ctx.fillStyle = color;
    ctx.textAlign = 'left';
    ctx.fillText(text, x, y);
    ctx.restore();
  }

  function hexToRgba(hex, alpha) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r},${g},${b},${alpha})`;
  }

  function fmtPrice(n) {
    n = Number(n);
    if (n === null || isNaN(n)) return '—';
    if (n >= 1000) return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1) return n.toFixed(2);
    if (n >= 0.01) return n.toFixed(4);
    return n.toFixed(7);
  }

  /* ============================================================
   *  MOUSE INTERACTION
   * ============================================================ */
  $canvas.addEventListener('mousedown', (e) => {
    if (!activeTool) return;
    const rect = $canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    if (activeTool === 'hline') {
      // One-click
      saveDrawing({
        tool_type: 'hline',
        p1_price: yToPrice(y),
      });
      resetTool();
      return;
    }

    if (!drawStart) {
      drawStart = { x, y };
      mouse = { x, y };
      scheduleRender();
    } else {
      // Complete
      const p1_time = xToTime(drawStart.x);
      const p1_price = yToPrice(drawStart.y);
      const p2_time = xToTime(x);
      const p2_price = yToPrice(y);

      if (p1_time === null || p2_time === null || p1_price === null || p2_price === null) {
        resetTool();
        return;
      }

      saveDrawing({
        tool_type: activeTool,
        p1_time, p1_price, p2_time, p2_price,
      });
      resetTool();
    }
  });

  $canvas.addEventListener('mousemove', (e) => {
    if (!activeTool) return;
    const rect = $canvas.getBoundingClientRect();
    mouse = {
      x: e.clientX - rect.left,
      y: e.clientY - rect.top,
    };
    if (drawStart) scheduleRender();
  });

  $canvas.addEventListener('mouseleave', () => {
    mouse = null;
    scheduleRender();
  });

  // Right-click a drawing to delete it
  $canvas.addEventListener('contextmenu', (e) => {
    if (activeTool) return; // don't delete while drawing
    e.preventDefault();

    const rect = $canvas.getBoundingClientRect();
    const mx = e.clientX - rect.left;
    const my = e.clientY - rect.top;

    const hit = findDrawingAt(mx, my);
    if (!hit) return;

    if (!confirm('Delete this drawing?')) return;

    fetch(API + '/api/drawings.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ action: 'delete', id: hit.id, csrf: CSRF }),
    })
    .then(r => r.json())
    .then(() => {
      drawings = drawings.filter(d => d.id !== hit.id);
      scheduleRender();
    })
    .catch(err => console.warn(err));
  });

  /* ============================================================
   *  HIT TEST — find a drawing near (mx, my)
   * ============================================================ */
  function findDrawingAt(mx, my) {
    const THRESHOLD = 8;
    for (let i = drawings.length - 1; i >= 0; i--) {
      const d = drawings[i];

      if (d.tool_type === 'hline') {
        const y = priceToY(d.p1_price);
        if (y !== null && Math.abs(y - my) < THRESHOLD) return d;
        continue;
      }

      const x1 = timeToX(d.p1_time);
      const y1 = priceToY(d.p1_price);
      const x2 = timeToX(d.p2_time);
      const y2 = priceToY(d.p2_price);
      if (x1 === null || y1 === null || x2 === null || y2 === null) continue;

      if (d.tool_type === 'trendline') {
        if (distToSegment(mx, my, x1, y1, x2, y2) < THRESHOLD) return d;
      } else {
        // Rect or Fib — use bounding box for hit test
        const bx = Math.min(x1, x2);
        const by = Math.min(y1, y2);
        const bw = Math.abs(x2 - x1);
        const bh = Math.abs(y2 - y1);
        if (mx >= bx - 4 && mx <= bx + bw + 4 && my >= by - 4 && my <= by + bh + 4) return d;
      }
    }
    return null;
  }

  function distToSegment(px, py, x1, y1, x2, y2) {
    const dx = x2 - x1;
    const dy = y2 - y1;
    const len2 = dx * dx + dy * dy;
    if (len2 === 0) return Math.hypot(px - x1, py - y1);
    let t = ((px - x1) * dx + (py - y1) * dy) / len2;
    t = Math.max(0, Math.min(1, t));
    return Math.hypot(px - (x1 + t * dx), py - (y1 + t * dy));
  }

  /* ============================================================
   *  SAVE TO SERVER
   * ============================================================ */
  async function saveDrawing(data) {
    const payload = {
      action: 'create',
      symbol: SYMBOL,
      color: activeColor,
      csrf: CSRF,
      ...data,
    };

    try {
      const res = await fetch(API + '/api/drawings.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(payload),
      });
      const json = await res.json();
      if (!res.ok) throw new Error(json.error || 'Save failed');

      drawings.push({ id: json.id, color: activeColor, ...data });
      scheduleRender();
    } catch (err) {
      console.warn('Drawing save failed:', err.message);
      alert('Could not save drawing: ' + err.message);
    }
  }

  /* ============================================================
   *  LOAD DRAWINGS
   * ============================================================ */
  async function loadDrawings() {
    try {
      const res = await fetch(API + '/api/drawings.php?symbol=' + encodeURIComponent(SYMBOL), {
        credentials: 'same-origin',
      });
      const json = await res.json();
      drawings = json.drawings || [];
      scheduleRender();
    } catch (err) {
      console.warn('Load drawings failed:', err.message);
    }
  }

  /* ============================================================
   *  TOOL MANAGEMENT
   * ============================================================ */
  function setTool(tool) {
    activeTool = tool;
    drawStart = null;
    mouse = null;

    if (tool) {
      $canvas.classList.add('active');
    } else {
      $canvas.classList.remove('active');
    }

    // Update toolbar buttons
    document.querySelectorAll('[data-draw-tool]').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.drawTool === tool);
    });

    // Update hint
    const $hint = document.getElementById('drawingHint');
    if ($hint) {
      if (!tool) {
        $hint.textContent = 'Pick a tool to start drawing';
      } else if (tool === 'hline') {
        $hint.textContent = 'Click on the chart to place a horizontal line';
      } else {
        $hint.textContent = 'Click two points on the chart';
      }
    }

    scheduleRender();
  }

  function resetTool() {
    drawStart = null;
    mouse = null;
    // Keep tool active so user can draw multiple
    scheduleRender();
  }

  function setColor(color) {
    activeColor = color;
    document.querySelectorAll('.color-swatch').forEach(sw => {
      sw.classList.toggle('active', sw.dataset.color === color);
    });
  }

  async function clearAllDrawings() {
    if (!confirm('Delete all drawings for ' + SYMBOL + '?')) return;

    try {
      const res = await fetch(API + '/api/drawings.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'clear', symbol: SYMBOL, csrf: CSRF }),
      });
      const json = await res.json();
      if (!res.ok) throw new Error(json.error || 'Failed');
      drawings = [];
      scheduleRender();
    } catch (err) {
      alert('Could not clear drawings: ' + err.message);
    }
  }

  /* ============================================================
   *  HOOK INTO CHART EVENTS
   * ============================================================ */
  function hookChart() {
    const c = chart();
    if (!c) {
      // Chart not ready — retry
      setTimeout(hookChart, 200);
      return;
    }

    // Redraw on pan/zoom
    try {
      c.timeScale().subscribeVisibleLogicalRangeChange(scheduleRender);
      c.timeScale().subscribeVisibleTimeRangeChange(scheduleRender);
    } catch (err) {
      console.warn('Chart subscription failed:', err);
    }

    // Initial canvas size
    resizeCanvas();
  }

  /* ============================================================
   *  WIRE UP TOOLBAR
   * ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-draw-tool]').forEach(btn => {
      btn.addEventListener('click', () => {
        const tool = btn.dataset.drawTool;
        setTool(activeTool === tool ? null : tool);
      });
    });

    document.querySelectorAll('.color-swatch').forEach(sw => {
      sw.addEventListener('click', () => setColor(sw.dataset.color));
    });

    const $clearBtn = document.getElementById('drawClearAll');
    if ($clearBtn) $clearBtn.addEventListener('click', clearAllDrawings);

    // Set initial swatch state
    const firstSwatch = document.querySelector('.color-swatch');
    if (firstSwatch) setColor(firstSwatch.dataset.color);

    // Boot
    loadDrawings();
    hookChart();

    // Initial hint
    setTool(null);
  });

  // Listen for chart data reloads — after a range change, redraw
  window.addEventListener('resize', scheduleRender);
  const _mut = new MutationObserver(() => scheduleRender());
  if ($chartContainer) _mut.observe($chartContainer, { attributes: true, attributeFilter: ['style'] });

})();