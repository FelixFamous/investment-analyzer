/**
 * AlphaEdge · Asset chart v5
 * Now with full timeframe support (1m, 5m, 15m, 30m, 1h, 4h, 1d, 1w)
 */

(function () {
  'use strict';

  const SYMBOL = window.ASSET_SYMBOL || 'BTC';
  const API    = window.ASSET_API    || '';
  const $container = document.getElementById('assetChart');
  const $wrapper   = document.getElementById('chartWrap');
  const $loading   = document.getElementById('chartLoading');
  const $error     = document.getElementById('chartError');
  const $count     = document.getElementById('chartCount');
  const $rangeNote = document.getElementById('rangeNote');
  if (!$container) return;

  const $legendO = document.getElementById('lgO');
  const $legendH = document.getElementById('lgH');
  const $legendL = document.getElementById('lgL');
  const $legendC = document.getElementById('lgC');

  let chart = null;
  let mainSeries = null;
  let volumeSeries = null;
  let overlaySeries = {};
  let subSeries = {};
  let pdhLine = null, pdlLine = null;

  let lastCandle = null;
  let allCandles = [];
  let visibleCandles = [];
  let initialized = false;
  let currentType = 'candles';
  let currentRange = '7d';
  let currentInterval = '30m';
  let replayMode = false;
  let replayIndex = 0;
  let replayTimer = null;
  let replaySpeed = 400;

  /* ============================================================
   *  INDICATORS
   * ============================================================ */
  function calcSMA(data, period) {
    const out = []; let sum = 0;
    for (let i = 0; i < data.length; i++) {
      sum += data[i].close;
      if (i >= period) sum -= data[i - period].close;
      if (i >= period - 1) out.push({ time: data[i].time, value: sum / period });
    }
    return out;
  }
  function calcEMA(data, period) {
    const k = 2 / (period + 1); const out = []; let ema = null;
    for (let i = 0; i < data.length; i++) {
      if (i < period - 1) continue;
      if (ema === null) {
        let s = 0; for (let j = i - period + 1; j <= i; j++) s += data[j].close;
        ema = s / period;
      } else ema = data[i].close * k + ema * (1 - k);
      out.push({ time: data[i].time, value: ema });
    }
    return out;
  }
  function calcBollinger(data, period = 20, mult = 2) {
    const upper = [], middle = [], lower = [];
    for (let i = period - 1; i < data.length; i++) {
      const slice = data.slice(i - period + 1, i + 1);
      const mean = slice.reduce((s, c) => s + c.close, 0) / period;
      const varr = slice.reduce((s, c) => s + (c.close - mean) ** 2, 0) / period;
      const sd = Math.sqrt(varr);
      upper.push({ time: data[i].time, value: mean + mult * sd });
      middle.push({ time: data[i].time, value: mean });
      lower.push({ time: data[i].time, value: mean - mult * sd });
    }
    return { upper, middle, lower };
  }
  function calcRSI(data, period = 14) {
    const out = []; let avgGain = 0, avgLoss = 0;
    for (let i = 1; i < data.length; i++) {
      const diff = data[i].close - data[i - 1].close;
      const gain = diff > 0 ? diff : 0;
      const loss = diff < 0 ? -diff : 0;
      if (i <= period) {
        avgGain += gain / period; avgLoss += loss / period;
        if (i === period) {
          const rs = avgLoss === 0 ? 100 : avgGain / avgLoss;
          out.push({ time: data[i].time, value: 100 - 100 / (1 + rs) });
        }
      } else {
        avgGain = (avgGain * (period - 1) + gain) / period;
        avgLoss = (avgLoss * (period - 1) + loss) / period;
        const rs = avgLoss === 0 ? 100 : avgGain / avgLoss;
        out.push({ time: data[i].time, value: 100 - 100 / (1 + rs) });
      }
    }
    return out;
  }
  function calcMACD(data, fast = 12, slow = 26, signal = 9) {
    const emaFast = calcEMA(data, fast);
    const emaSlow = calcEMA(data, slow);
    const mapFast = new Map(emaFast.map(x => [x.time, x.value]));
    const macdLine = [];
    emaSlow.forEach(x => { if (mapFast.has(x.time)) macdLine.push({ time: x.time, value: mapFast.get(x.time) - x.value }); });
    const k = 2 / (signal + 1); let sig = null;
    const signalLine = [];
    for (let i = 0; i < macdLine.length; i++) {
      if (i < signal - 1) continue;
      if (sig === null) {
        let s = 0; for (let j = i - signal + 1; j <= i; j++) s += macdLine[j].value;
        sig = s / signal;
      } else sig = macdLine[i].value * k + sig * (1 - k);
      signalLine.push({ time: macdLine[i].time, value: sig });
    }
    const mapSig = new Map(signalLine.map(x => [x.time, x.value]));
    const hist = [];
    macdLine.forEach(x => {
      if (mapSig.has(x.time)) {
        const d = x.value - mapSig.get(x.time);
        hist.push({ time: x.time, value: d, color: d >= 0 ? 'rgba(14,203,129,0.5)' : 'rgba(246,70,93,0.5)' });
      }
    });
    return { macd: macdLine, signal: signalLine, hist };
  }

  /* ============================================================
   *  CHART TYPE TRANSFORMS
   * ============================================================ */
  function toHeikinAshi(candles) {
    if (!candles.length) return [];
    const out = [];
    let prevHA = { open: (candles[0].open + candles[0].close) / 2, close: (candles[0].open + candles[0].close) / 2 };
    for (let i = 0; i < candles.length; i++) {
      const c = candles[i];
      const haClose = (c.open + c.high + c.low + c.close) / 4;
      const haOpen = i === 0 ? (c.open + c.close) / 2 : (prevHA.open + prevHA.close) / 2;
      const haHigh = Math.max(c.high, haOpen, haClose);
      const haLow = Math.min(c.low, haOpen, haClose);
      out.push({ time: c.time, open: haOpen, high: haHigh, low: haLow, close: haClose });
      prevHA = { open: haOpen, close: haClose };
    }
    return out;
  }
  function toRenko(candles, brickPct = 0.005) {
    if (candles.length < 2) return [];
    const avgPrice = candles.reduce((s, c) => s + c.close, 0) / candles.length;
    const brick = avgPrice * brickPct;
    const bricks = [];
    let lastClose = candles[0].close;
    let lastTime = candles[0].time;
    for (let i = 1; i < candles.length; i++) {
      const c = candles[i];
      let diff = c.close - lastClose;
      while (Math.abs(diff) >= brick) {
        const dir = diff > 0 ? 1 : -1;
        const newClose = lastClose + dir * brick;
        bricks.push({
          time: lastTime + bricks.length,
          open: Math.min(lastClose, newClose),
          close: Math.max(lastClose, newClose),
          high: Math.max(lastClose, newClose),
          low: Math.min(lastClose, newClose),
        });
        lastClose = newClose;
        diff = c.close - lastClose;
      }
      lastTime = c.time;
    }
    return bricks.length >= 5 ? bricks : candles;
  }
  function toLineBreak(candles, lines = 3) {
    if (candles.length < lines + 1) return candles;
    const out = [];
    for (let i = 0; i < candles.length; i++) {
      const c = candles[i];
      if (out.length < lines) {
        out.push({ time: c.time, open: c.open, high: c.high, low: c.low, close: c.close });
        continue;
      }
      const recent = out.slice(-lines);
      const hh = Math.max(...recent.map(r => r.high));
      const ll = Math.min(...recent.map(r => r.low));
      if (c.close > hh) out.push({ time: c.time, open: hh, high: c.close, low: hh, close: c.close });
      else if (c.close < ll) out.push({ time: c.time, open: ll, high: ll, low: c.close, close: c.close });
    }
    return out.length >= 5 ? out : candles;
  }

  /* ============================================================
   *  LIBRARY
   * ============================================================ */
  function loadScript(src, t = 6000) {
    return new Promise(res => {
      const s = document.createElement('script'); s.src = src; s.async = true;
      let done = false;
      s.onload = () => { if (!done) { done = true; res(true); } };
      s.onerror = () => { if (!done) { done = true; res(false); } };
      document.head.appendChild(s);
      setTimeout(() => { if (!done) { done = true; res(false); } }, t);
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
   *  FORMATTERS
   * ============================================================ */
  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1) return '$' + n.toFixed(2);
    if (n >= 0.01) return '$' + n.toFixed(4);
    return '$' + n.toFixed(7);
  }
  function updateLegend(c) {
    if (!c) return;
    if ($legendO) $legendO.textContent = fmtPrice(c.open);
    if ($legendH) $legendH.textContent = fmtPrice(c.high);
    if ($legendL) $legendL.textContent = fmtPrice(c.low);
    if ($legendC) $legendC.textContent = fmtPrice(c.close);
  }

  function containerHeight() {
    const w = window.innerWidth;
    if (w < 500) return Math.max(400, Math.round(window.innerHeight * 0.6));
    if (w < 900) return Math.max(450, Math.round(window.innerHeight * 0.55));
    return 560;
  }

  /* ============================================================
   *  INIT
   * ============================================================ */
  function initChart() {
    if (initialized) return;
    const LWC = window.LightweightCharts;
    if (!LWC) throw new Error('Library missing');

    const h = containerHeight();
    const w = $container.clientWidth || window.innerWidth - 40;

    chart = LWC.createChart($container, {
      width: w, height: h,
      layout: {
        background: { type: 'solid', color: '#0f1117' },
        textColor: '#8892a8',
        fontFamily: "'Inter', sans-serif",
        fontSize: 11,
      },
      grid: {
        vertLines: { color: 'rgba(31,39,52,0.55)' },
        horzLines: { color: 'rgba(31,39,52,0.55)' },
      },
      rightPriceScale: {
        borderColor: '#1f2734',
        scaleMargins: { top: 0.08, bottom: 0.32 },
      },
      timeScale: {
        borderColor: '#1f2734',
        timeVisible: true,
        secondsVisible: false,
        rightOffset: 5,
        barSpacing: 6,
        minBarSpacing: 0.5,
      },
      crosshair: {
        mode: 0,
        vertLine: { color: '#3b82f6', width: 1, style: 2, labelBackgroundColor: '#3b82f6' },
        horzLine: { color: '#3b82f6', width: 1, style: 2, labelBackgroundColor: '#3b82f6' },
      },
      handleScroll: { mouseWheel: true, pressedMouseMove: true, horzTouchDrag: true, vertTouchDrag: false },
      handleScale:  { mouseWheel: true, pinch: true, axisPressedMouseMove: true },
      kineticScroll: { touch: true, mouse: false },
    });

    volumeSeries = chart.addHistogramSeries({
      color: 'rgba(91,124,250,0.4)',
      priceFormat: { type: 'volume' },
      priceScaleId: 'volume',
    });
    chart.priceScale('volume').applyOptions({ scaleMargins: { top: 0.82, bottom: 0 } });

    const ov = (color, width = 1) => chart.addLineSeries({
      color, lineWidth: width, priceLineVisible: false, lastValueVisible: false, crosshairMarkerVisible: false,
    });
    overlaySeries.sma20 = ov('#f0b90b', 1);
    overlaySeries.sma50 = ov('#3b82f6', 1);
    overlaySeries.sma200 = ov('#8b5cf6', 2);
    overlaySeries.ema12 = ov('#14f195', 1);
    overlaySeries.ema26 = ov('#fb923c', 1);
    overlaySeries.bbUpper = ov('rgba(139,92,246,0.7)', 1);
    overlaySeries.bbLower = ov('rgba(139,92,246,0.7)', 1);
    overlaySeries.bbMiddle = ov('rgba(139,92,246,0.3)', 1);
    Object.values(overlaySeries).forEach(s => s.applyOptions({ visible: false }));

    subSeries.rsi = chart.addLineSeries({
      color: '#f0b90b', lineWidth: 1, priceScaleId: 'rsi',
      priceLineVisible: false, lastValueVisible: false, visible: false,
    });
    chart.priceScale('rsi').applyOptions({ scaleMargins: { top: 0.72, bottom: 0.18 }, visible: false });

    subSeries.macd = chart.addLineSeries({
      color: '#3b82f6', lineWidth: 1, priceScaleId: 'macd',
      priceLineVisible: false, lastValueVisible: false, visible: false,
    });
    subSeries.macdSignal = chart.addLineSeries({
      color: '#f0b90b', lineWidth: 1, priceScaleId: 'macd',
      priceLineVisible: false, lastValueVisible: false, visible: false,
    });
    subSeries.macdHist = chart.addHistogramSeries({
      priceFormat: { type: 'price' }, priceScaleId: 'macd', visible: false,
    });
    chart.priceScale('macd').applyOptions({ scaleMargins: { top: 0.72, bottom: 0.18 }, visible: false });

    chart.subscribeCrosshairMove((param) => {
      if (!param.time || !param.seriesData || !mainSeries) {
        if (lastCandle) updateLegend(lastCandle);
        return;
      }
      const c = param.seriesData.get(mainSeries);
      if (c) updateLegend(c);
    });

    window.addEventListener('resize', () => {
      if (chart && $container) chart.applyOptions({ width: $container.clientWidth, height: containerHeight() });
    });

    initialized = true;

    window.AlphaChart = {
      get chart() { return chart; },
      get series() { return mainSeries; },
      get candles() { return visibleCandles; },
      get initialized() { return initialized; }
    };
  }

  /* ============================================================
   *  SERIES
   * ============================================================ */
  function createMainSeries(type) {
    const common = { priceLineVisible: false, lastValueVisible: true, crosshairMarkerVisible: true };
    if (type === 'line') return chart.addLineSeries({ ...common, color: '#f0b90b', lineWidth: 2 });
    if (type === 'area') return chart.addAreaSeries({
      ...common, lineColor: '#f0b90b', lineWidth: 2,
      topColor: 'rgba(240,185,11,0.3)', bottomColor: 'rgba(240,185,11,0.02)',
    });
    return chart.addCandlestickSeries({
      ...common,
      upColor: '#0ecb81', downColor: '#f6465d',
      borderUpColor: '#0ecb81', borderDownColor: '#f6465d',
      wickUpColor: '#0ecb81', wickDownColor: '#f6465d',
    });
  }
  function rebuildSeries(type) {
    if (mainSeries) { try { chart.removeSeries(mainSeries); } catch {} }
    currentType = type;
    mainSeries = createMainSeries(type);
    if (visibleCandles.length) {
      mainSeries.setData(transformForType(visibleCandles, type));
    }
    applyPDHPDL(visibleCandles);
  }
  function transformForType(candles, type) {
    if (type === 'heikin') return toHeikinAshi(candles);
    if (type === 'renko') return toRenko(candles);
    if (type === 'linebreak') return toLineBreak(candles, 3);
    if (type === 'line' || type === 'area') return candles.map(c => ({ time: c.time, value: c.close }));
    return candles;
  }

  /* ============================================================
   *  PDH/PDL
   * ============================================================ */
  function applyPDHPDL(candles) {
    if (!mainSeries || !candles.length) return;
    const dayHighs = {}, dayLows = {};
    candles.forEach(c => {
      const d = new Date(c.time * 1000);
      const key = d.getUTCFullYear() + '-' + d.getUTCMonth() + '-' + d.getUTCDate();
      if (!dayHighs[key]) { dayHighs[key] = c.high; dayLows[key] = c.low; }
      else {
        if (c.high > dayHighs[key]) dayHighs[key] = c.high;
        if (c.low < dayLows[key]) dayLows[key] = c.low;
      }
    });
    const days = Object.keys(dayHighs).sort();
    const showPDH = document.querySelector('[data-pd="pdh"]')?.classList.contains('active');
    const showPDL = document.querySelector('[data-pd="pdl"]')?.classList.contains('active');

    if (pdhLine) { try { mainSeries.removePriceLine(pdhLine); } catch {} pdhLine = null; }
    if (pdlLine) { try { mainSeries.removePriceLine(pdlLine); } catch {} pdlLine = null; }

    if (days.length >= 2) {
      const prevDay = days[days.length - 2];
      if (showPDH) pdhLine = mainSeries.createPriceLine({
        price: dayHighs[prevDay], color: '#0ecb81', lineWidth: 1, lineStyle: 2,
        axisLabelVisible: true, title: 'PDH',
      });
      if (showPDL) pdlLine = mainSeries.createPriceLine({
        price: dayLows[prevDay], color: '#f6465d', lineWidth: 1, lineStyle: 2,
        axisLabelVisible: true, title: 'PDL',
      });
    }
  }

  /* ============================================================
   *  SESSIONS
   * ============================================================ */
  function renderSessionStrip(candles) {
    const $strip = document.getElementById('sessionStrip');
    if (!$strip) return;
    if (!candles.length) { $strip.innerHTML = ''; return; }
    const showSessions = document.querySelector('[data-session-toggle]')?.classList.contains('active');
    if (!showSessions) { $strip.innerHTML = ''; return; }

    const byDay = {};
    candles.forEach(c => {
      const d = new Date(c.time * 1000);
      const key = d.getUTCFullYear() + '-' + String(d.getUTCMonth()+1).padStart(2,'0') + '-' + String(d.getUTCDate()).padStart(2,'0');
      if (!byDay[key]) byDay[key] = [];
      byDay[key].push(c);
    });

    const days = Object.keys(byDay).sort();
    const dayWidth = 100 / days.length;

    let html = '<div class="session-strip-track">';
    days.forEach(day => {
      html += `<div class="session-day" style="width:${dayWidth}%;">`;
      const dayCandles = byDay[day];
      const segWidth = 100 / dayCandles.length;
      dayCandles.forEach((c, ci) => {
        const h = new Date(c.time * 1000).getUTCHours();
        let session = 'asian';
        if (h >= 7 && h < 13) session = 'london';
        else if (h >= 13 && h < 21) session = 'ny';
        else if (h >= 21 || h < 2) session = 'sydney';
        html += `<div class="session-seg ${session}" style="left:${ci*segWidth}%; width:${segWidth}%;"></div>`;
      });
      html += `</div>`;
    });
    html += '</div>';
    html += '<div class="session-legend">';
    html += '<span><i class="session-dot asian"></i> Asian</span>';
    html += '<span><i class="session-dot london"></i> London</span>';
    html += '<span><i class="session-dot ny"></i> New York</span>';
    html += '<span><i class="session-dot sydney"></i> Sydney</span>';
    html += '</div>';

    $strip.innerHTML = html;
  }

  /* ============================================================
   *  VOLUME PROFILE
   * ============================================================ */
  function renderVolumeProfile(candles) {
    const $vp = document.getElementById('volumeProfile');
    if (!$vp) return;
    const show = document.querySelector('[data-vp-toggle]')?.classList.contains('active');
    if (!show || !candles.length) { $vp.innerHTML = ''; $vp.style.display = 'none'; return; }
    $vp.style.display = 'block';

    const maxP = Math.max(...candles.map(c => c.high));
    const minP = Math.min(...candles.map(c => c.low));
    const bins = 24;
    const binSize = (maxP - minP) / bins;
    const profile = new Array(bins).fill(0);
    candles.forEach(c => {
      const top = Math.min(bins - 1, Math.floor((c.high - minP) / binSize));
      const bot = Math.max(0, Math.floor((c.low - minP) / binSize));
      const span = Math.max(1, top - bot + 1);
      const vol = c.volume || 1;
      for (let b = bot; b <= top; b++) profile[b] += vol / span;
    });
    const maxVol = Math.max(...profile);
    let html = '';
    for (let i = bins - 1; i >= 0; i--) {
      const priceMid = minP + (i + 0.5) * binSize;
      const w = maxVol > 0 ? (profile[i] / maxVol) * 100 : 0;
      html += `<div class="vp-row"><div class="vp-bar-wrap"><div class="vp-bar" style="width:${w}%;"></div></div><div class="vp-price">${fmtPrice(priceMid)}</div></div>`;
    }
    const pocIdx = profile.indexOf(maxVol);
    const pocPrice = minP + (pocIdx + 0.5) * binSize;
    html = `<div class="vp-header">Volume Profile <span>POC: ${fmtPrice(pocPrice)}</span></div>` + html;
    $vp.innerHTML = html;
  }

  /* ============================================================
   *  REPLAY
   * ============================================================ */
  function enterReplay() {
    if (!allCandles.length) return;
    replayMode = true;
    replayIndex = Math.max(30, Math.floor(allCandles.length * 0.15));
    showReplayControls(true);
    applyReplay();
  }
  function exitReplay() {
    replayMode = false;
    if (replayTimer) { clearInterval(replayTimer); replayTimer = null; }
    showReplayControls(false);
    visibleCandles = allCandles.slice();
    renderChartData();
  }
  function showReplayControls(show) {
    const $c = document.getElementById('replayControls');
    if ($c) $c.style.display = show ? 'flex' : 'none';
  }
  function applyReplay() {
    visibleCandles = allCandles.slice(0, replayIndex);
    renderChartData();
    const $pos = document.getElementById('replayPos');
    if ($pos) $pos.textContent = replayIndex + ' / ' + allCandles.length;
  }
  function replayStep() {
    if (replayIndex < allCandles.length) { replayIndex++; applyReplay(); }
    else { if (replayTimer) { clearInterval(replayTimer); replayTimer = null; } }
  }
  function toggleReplayPlay() {
    if (replayTimer) { clearInterval(replayTimer); replayTimer = null; }
    else replayTimer = setInterval(replayStep, replaySpeed);
  }
  function resetReplay() { replayIndex = Math.max(30, Math.floor(allCandles.length * 0.15)); applyReplay(); }

  /* ============================================================
   *  MAIN RENDER
   * ============================================================ */
  function renderChartData() {
    if (!mainSeries || !visibleCandles.length) return;

    mainSeries.setData(transformForType(visibleCandles, currentType));

    volumeSeries.setData(visibleCandles.map(c => ({
      time: c.time,
      value: c.volume || 0,
      color: c.close >= c.open ? 'rgba(14,203,129,0.45)' : 'rgba(246,70,93,0.45)',
    })));

    overlaySeries.sma20.setData(calcSMA(visibleCandles, 20));
    overlaySeries.sma50.setData(calcSMA(visibleCandles, 50));
    overlaySeries.sma200.setData(calcSMA(visibleCandles, 200));
    overlaySeries.ema12.setData(calcEMA(visibleCandles, 12));
    overlaySeries.ema26.setData(calcEMA(visibleCandles, 26));

    const bb = calcBollinger(visibleCandles, 20, 2);
    overlaySeries.bbUpper.setData(bb.upper);
    overlaySeries.bbMiddle.setData(bb.middle);
    overlaySeries.bbLower.setData(bb.lower);

    subSeries.rsi.setData(calcRSI(visibleCandles, 14));
    const macd = calcMACD(visibleCandles, 12, 26, 9);
    subSeries.macd.setData(macd.macd);
    subSeries.macdSignal.setData(macd.signal);
    subSeries.macdHist.setData(macd.hist);

    applyPDHPDL(visibleCandles);
    renderSessionStrip(visibleCandles);
    renderVolumeProfile(visibleCandles);

    if ($count) $count.textContent = visibleCandles.length.toLocaleString() + ' candles · ' + currentInterval;
    if ($rangeNote) $rangeNote.textContent = currentInterval + ' · ' + currentRange;

    lastCandle = visibleCandles[visibleCandles.length - 1];
    updateLegend(lastCandle);
    chart.timeScale().fitContent();

    window.dispatchEvent(new CustomEvent('chart:rendered'));
  }

  function applyVisibility() {
    const flags = window.CHART_INDICATORS || {};
    overlaySeries.sma20.applyOptions({ visible: !!flags.sma20 });
    overlaySeries.sma50.applyOptions({ visible: !!flags.sma50 });
    overlaySeries.sma200.applyOptions({ visible: !!flags.sma200 });
    overlaySeries.ema12.applyOptions({ visible: !!flags.ema12 });
    overlaySeries.ema26.applyOptions({ visible: !!flags.ema26 });
    overlaySeries.bbUpper.applyOptions({ visible: !!flags.bb });
    overlaySeries.bbLower.applyOptions({ visible: !!flags.bb });
    overlaySeries.bbMiddle.applyOptions({ visible: !!flags.bb });

    subSeries.rsi.applyOptions({ visible: !!flags.rsi });
    chart.priceScale('rsi').applyOptions({ visible: !!flags.rsi });
    subSeries.macd.applyOptions({ visible: !!flags.macd });
    subSeries.macdSignal.applyOptions({ visible: !!flags.macd });
    subSeries.macdHist.applyOptions({ visible: !!flags.macd });
    chart.priceScale('macd').applyOptions({ visible: !!flags.macd });

    const hasSub = flags.rsi || flags.macd;
    chart.priceScale('right').applyOptions({
      scaleMargins: { top: 0.08, bottom: hasSub ? 0.32 : 0.08 },
    });
  }

  /* ============================================================
   *  DATA LOAD
   * ============================================================ */
  async function loadData() {
    if ($loading) { $loading.textContent = 'Loading ' + currentInterval + ' candles…'; $loading.style.display = 'block'; }
    if ($error) $error.style.display = 'none';
    if ($wrapper) $wrapper.style.display = 'none';

    try {
      const url = API + '/api/history.php?symbol=' + encodeURIComponent(SYMBOL)
                + '&range=' + encodeURIComponent(currentRange)
                + '&interval=' + encodeURIComponent(currentInterval);
      const res = await fetch(url);
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const json = await res.json();
      if (json.error) throw new Error(json.error);
      if (!json.candles || !json.candles.length) throw new Error('No data');

      if ($wrapper) $wrapper.style.display = 'block';
      if ($loading) $loading.style.display = 'none';
      await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(r)));

      if (!initialized) initChart();
      if (!mainSeries) rebuildSeries(currentType);

      allCandles = json.candles.map(c => ({
        time: Math.floor(c.t / 1000),
        open: c.o, high: c.h, low: c.l, close: c.c, volume: c.v || 0,
      }));
      visibleCandles = allCandles.slice();

      if (replayMode) { replayMode = false; if (replayTimer) { clearInterval(replayTimer); replayTimer = null; } showReplayControls(false); }

      renderChartData();
      applyVisibility();

      requestAnimationFrame(() => {
        if ($container.clientWidth > 0) chart.applyOptions({ width: $container.clientWidth, height: containerHeight() });
      });
    } catch (err) {
      console.error('[Chart] Error:', err);
      if ($loading) $loading.style.display = 'none';
      if ($wrapper) $wrapper.style.display = 'none';
      if ($error) { $error.textContent = 'Could not load chart: ' + err.message; $error.style.display = 'block'; }
    }
  }

  /* ============================================================
   *  WIRE UP
   * ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    // Range tabs
    document.querySelectorAll('#rangeTabs [data-range]').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('#rangeTabs [data-range]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentRange = btn.dataset.range;
        loadData();
      });
    });

    // Timeframe tabs
    document.querySelectorAll('#timeframeTabs [data-interval]').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('#timeframeTabs [data-interval]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentInterval = btn.dataset.interval;

        // Auto-adjust range for fine timeframes (Yahoo doesn't allow 1m over 1 year)
        const rangeLimits = {
          '1m':  '1d',
          '5m':  '7d',
          '15m': '30d',
          '30m': '30d',
        };
        if (rangeLimits[currentInterval]) {
          const maxRange = rangeLimits[currentInterval];
          const rangeOrder = ['1d','7d','30d','90d','1y','2y','5y','max'];
          const maxIdx = rangeOrder.indexOf(maxRange);
          const curIdx = rangeOrder.indexOf(currentRange);
          if (curIdx > maxIdx) {
            currentRange = maxRange;
            document.querySelectorAll('#rangeTabs [data-range]').forEach(b => {
              b.classList.toggle('active', b.dataset.range === maxRange);
            });
          }
        }

        loadData();
      });
    });

    // Density
    document.querySelectorAll('[data-density]').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('[data-density]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (!chart || !visibleCandles.length) return;
        const total = visibleCandles.length;
        const m = btn.dataset.density;
        if (m === 'fit') chart.timeScale().fitContent();
        else if (m === 'dense') chart.timeScale().setVisibleLogicalRange({ from: total - Math.floor(total * 0.9), to: total + 2 });
        else if (m === 'medium') chart.timeScale().setVisibleLogicalRange({ from: total - 150, to: total + 2 });
        else if (m === 'detail') chart.timeScale().setVisibleLogicalRange({ from: total - 60, to: total + 2 });
      });
    });

    // Indicators
    window.CHART_INDICATORS = window.CHART_INDICATORS || {};
    document.querySelectorAll('[data-indicator]').forEach(btn => {
      const key = btn.dataset.indicator;
      if (window.CHART_INDICATORS[key]) btn.classList.add('active');
      btn.addEventListener('click', () => {
        btn.classList.toggle('active');
        window.CHART_INDICATORS[key] = btn.classList.contains('active');
        if (initialized) applyVisibility();
      });
    });

    // Chart types
    document.querySelectorAll('[data-chart-type]').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('[data-chart-type]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (initialized) rebuildSeries(btn.dataset.chartType);
      });
    });

    document.querySelectorAll('[data-pd]').forEach(btn => {
      btn.addEventListener('click', () => { btn.classList.toggle('active'); applyPDHPDL(visibleCandles); });
    });
    document.querySelectorAll('[data-session-toggle]').forEach(btn => {
      btn.addEventListener('click', () => { btn.classList.toggle('active'); renderSessionStrip(visibleCandles); });
    });
    document.querySelectorAll('[data-vp-toggle]').forEach(btn => {
      btn.addEventListener('click', () => { btn.classList.toggle('active'); renderVolumeProfile(visibleCandles); });
    });

    document.querySelector('[data-replay-start]')?.addEventListener('click', () => {
      replayMode ? exitReplay() : enterReplay();
      const btn = document.querySelector('[data-replay-start]');
      if (btn) btn.textContent = replayMode ? '⏹ Stop Replay' : '▶ Bar Replay';
    });
    document.querySelector('[data-replay-play]')?.addEventListener('click', toggleReplayPlay);
    document.querySelector('[data-replay-step]')?.addEventListener('click', replayStep);
    document.querySelector('[data-replay-reset]')?.addEventListener('click', resetReplay);
    document.querySelector('[data-replay-exit]')?.addEventListener('click', () => {
      exitReplay();
      const btn = document.querySelector('[data-replay-start]');
      if (btn) btn.textContent = '▶ Bar Replay';
    });
    document.querySelectorAll('[data-speed]').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('[data-speed]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        replaySpeed = parseInt(btn.dataset.speed, 10);
        if (replayTimer) { clearInterval(replayTimer); replayTimer = setInterval(replayStep, replaySpeed); }
      });
    });

    (async function boot() {
      const ok = await ensureLibrary();
      if (!ok) {
        if ($error) { $error.textContent = 'Chart library could not be loaded.'; $error.style.display = 'block'; }
        return;
      }
      loadData();
    })();
  });
})();