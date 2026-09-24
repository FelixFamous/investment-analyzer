/**
 * AlphaEdge · Market Microstructure
 * Simulates a realistic order book and trade tape from the live price.
 * Updates every 1.5 seconds for a live-exchange feel.
 */

(function () {
  'use strict';

  const SYMBOL = window.MS_SYMBOL || 'BTC';
  const API    = window.MS_API || '';

  let currentPrice = Number(window.MS_PRICE) || 0;
  let lastMidPrice = currentPrice;

  // Sizes are larger for cheaper assets — makes big numbers on BTC, small on PEPE
  function baseSize() {
    if (currentPrice >= 10000) return 0.15;
    if (currentPrice >= 1000)  return 1.5;
    if (currentPrice >= 100)   return 8;
    if (currentPrice >= 10)    return 60;
    if (currentPrice >= 1)     return 400;
    if (currentPrice >= 0.01)  return 4000;
    return 200000;
  }

  // The tick size (smallest price increment) scales with the asset
  function tickSize() {
    if (currentPrice >= 10000) return 0.5;
    if (currentPrice >= 1000)  return 0.1;
    if (currentPrice >= 100)   return 0.01;
    if (currentPrice >= 10)    return 0.005;
    if (currentPrice >= 1)     return 0.001;
    if (currentPrice >= 0.01)  return 0.0001;
    return 0.0000001;
  }

  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1)    return n.toFixed(2);
    if (n >= 0.01) return n.toFixed(4);
    return n.toFixed(7);
  }
  function fmtSize(n) {
    n = Number(n);
    if (n >= 1e6) return (n / 1e6).toFixed(2) + 'M';
    if (n >= 1e3) return (n / 1e3).toFixed(2) + 'K';
    if (n >= 100) return n.toFixed(0);
    if (n >= 1)   return n.toFixed(2);
    return n.toFixed(4);
  }

  /* ============================================================
   *  LIVE PRICE FROM GLOBAL HOOK
   * ============================================================ */
  function initLivePrice() {
    if (window.LivePrices) {
      const p = window.LivePrices.get(SYMBOL);
      if (p) currentPrice = p.price;
      window.LivePrices.subscribe((prices) => {
        const live = prices[SYMBOL];
        if (!live) return;
        currentPrice = live.price;
        updateLiveHeader(live);
      });
    }
    window.addEventListener('liveprices:update', (e) => {
      const live = e.detail && e.detail[SYMBOL];
      if (live) {
        currentPrice = live.price;
        updateLiveHeader(live);
      }
    });
  }

  function updateLiveHeader(p) {
    const $p = document.getElementById('msPrice');
    if ($p) {
      $p.textContent = '$' + fmtPrice(p.price);
      // flash
      const prev = lastMidPrice;
      if (p.price > prev) {
        $p.classList.remove('flash-down'); $p.classList.add('flash-up');
        setTimeout(() => $p.classList.remove('flash-up'), 600);
      } else if (p.price < prev) {
        $p.classList.remove('flash-up'); $p.classList.add('flash-down');
        setTimeout(() => $p.classList.remove('flash-down'), 600);
      }
      lastMidPrice = p.price;
    }
    const $c = document.getElementById('msChange');
    if ($c) {
      $c.textContent = (p.changePct >= 0 ? '+' : '') + p.changePct.toFixed(2) + '%';
      $c.className = 'ms-live-change ' + (p.changePct >= 0 ? 'pos' : 'neg');
    }
  }

  /* ============================================================
   *  ORDER BOOK
   * ============================================================ */
  function buildOrderBook() {
    if (currentPrice <= 0) return { bids: [], asks: [], spread: 0 };

    const tick = tickSize();
    const base = baseSize();
    const mid = currentPrice;
    const spreadTicks = 1 + Math.floor(Math.random() * 3); // 1–3 ticks
    const halfSpread = spreadTicks * tick / 2;

    const bids = [];
    const asks = [];

    // Best bid / best ask
    let bidPrice = mid - halfSpread;
    let askPrice = mid + halfSpread;

    for (let i = 0; i < 12; i++) {
      // Sizes grow with depth — bigger further from mid
      const bidSize = base * (0.4 + Math.random() * 0.6) * (1 + i * 0.25);
      const askSize = base * (0.4 + Math.random() * 0.6) * (1 + i * 0.25);

      bids.push({ price: bidPrice, size: bidSize });
      asks.push({ price: askPrice, size: askSize });

      // Next level goes further away
      bidPrice -= tick * (1 + Math.floor(Math.random() * 2));
      askPrice += tick * (1 + Math.floor(Math.random() * 2));
    }

    return { bids, asks, spread: askPrice - bidPrice + tick };
  }

  function renderOrderBook(book) {
    const asks = book.asks.slice().reverse(); // highest ask at top
    const bids = book.bids;

    // Compute cumulative totals
    let cumAsk = 0;
    const askRows = asks.map(a => {
      cumAsk += a.size;
      return { ...a, cum: cumAsk };
    });
    let cumBid = 0;
    const bidRows = bids.map(b => {
      cumBid += b.size;
      return { ...b, cum: cumBid };
    });

    const maxCum = Math.max(cumAsk, cumBid);

    // Render asks
    const $asks = document.getElementById('obAsks');
    if ($asks) {
      $asks.innerHTML = askRows.map(r => `
        <div class="ob-row ask">
          <div class="ob-bar" style="width:${(r.cum / maxCum) * 100}%"></div>
          <div class="ob-price">${fmtPrice(r.price)}</div>
          <div class="ob-size">${fmtSize(r.size)}</div>
          <div class="ob-total">${fmtSize(r.cum)}</div>
        </div>
      `).join('');
    }

    // Render bids
    const $bids = document.getElementById('obBids');
    if ($bids) {
      $bids.innerHTML = bidRows.map(r => `
        <div class="ob-row bid">
          <div class="ob-bar" style="width:${(r.cum / maxCum) * 100}%"></div>
          <div class="ob-price">${fmtPrice(r.price)}</div>
          <div class="ob-size">${fmtSize(r.size)}</div>
          <div class="ob-total">${fmtSize(r.cum)}</div>
        </div>
      `).join('');
    }

    // Spread display
    const bestBid = bids[0]?.price || 0;
    const bestAsk = asks[asks.length - 1]?.price || 0;
    const mid = (bestBid + bestAsk) / 2;
    const spread = bestAsk - bestBid;
    const spreadPct = mid > 0 ? (spread / mid) * 100 : 0;

    setText('obMid', '$' + fmtPrice(mid));
    setText('obSpreadLabel', `Spread ${fmtPrice(spread)} · ${spreadPct.toFixed(3)}%`);

    // Stats
    setText('obBidDepth', fmtSize(cumBid));
    setText('obAskDepth', fmtSize(cumAsk));
    const total = cumBid + cumAsk;
    const imbalance = total > 0 ? (cumBid / total) * 100 : 50;
    const $imb = document.getElementById('obImbalance');
    if ($imb) {
      $imb.textContent = imbalance.toFixed(1) + '% buy';
      $imb.style.color = imbalance > 55 ? 'var(--green)' : (imbalance < 45 ? 'var(--red)' : 'var(--text)');
    }

    // Market info panel
    setText('miBestBid', '$' + fmtPrice(bestBid));
    setText('miBestAsk', '$' + fmtPrice(bestAsk));
    setText('miSpread', fmtPrice(spread) + ' (' + spreadPct.toFixed(3) + '%)');
    setText('miMid', '$' + fmtPrice(mid));

    // Session
    const utcHour = new Date().getUTCHours();
    let session = 'Asian';
    if (utcHour >= 7 && utcHour < 13) session = 'London';
    else if (utcHour >= 13 && utcHour < 21) session = 'New York';
    else if (utcHour >= 21 || utcHour < 2) session = 'Sydney';
    setText('miSession', session);
  }

  /* ============================================================
   *  TIME & SALES
   * ============================================================ */
  const tape = [];
  let buyVol = 0;
  let sellVol = 0;

  function pushTrade() {
    if (currentPrice <= 0) return;
    const tick = tickSize();
    const price = currentPrice + (Math.random() - 0.5) * tick * 4;
    const size = baseSize() * (0.2 + Math.random() * 1.8);
    const isBuy = Math.random() > 0.5;
    const ts = new Date();

    tape.unshift({ price, size, isBuy, ts });
    if (tape.length > 40) tape.pop();

    if (isBuy) buyVol += size;
    else sellVol += size;
  }

  function renderTape() {
    const $tape = document.getElementById('tsTape');
    if (!$tape) return;

    $tape.innerHTML = tape.map((t, i) => `
      <div class="ts-row ${t.isBuy ? 'buy' : 'sell'}" style="opacity:${1 - i * 0.015};">
        <div class="ts-time">${t.ts.toLocaleTimeString('en-US', { hour12: false })}</div>
        <div class="ts-price">${fmtPrice(t.price)}</div>
        <div class="ts-size">${fmtSize(t.size)}</div>
      </div>
    `).join('');

    setText('tsCount', tape.length + ' trades');
    setText('tsBuyVol', fmtSize(buyVol));
    setText('tsSellVol', fmtSize(sellVol));
    const delta = buyVol - sellVol;
    const $d = document.getElementById('tsDelta');
    if ($d) {
      $d.textContent = (delta >= 0 ? '+' : '') + fmtSize(Math.abs(delta));
      $d.style.color = delta >= 0 ? 'var(--green)' : 'var(--red)';
    }
  }

  /* ============================================================
   *  MARKET INFO EXTRAS
   * ============================================================ */
  async function loadHeldQty() {
    try {
      const r = await fetch(API + '/api/portfolio', { credentials: 'same-origin' });
      const json = await r.json();
      const h = (json.holdings || []).find(x => x.symbol === SYMBOL);
      setText('miHeld', h ? h.quantity.toFixed(6) + ' ' + SYMBOL : 'None');
    } catch {}
  }

  /* ============================================================
   *  HELPERS + LOOP
   * ============================================================ */
  function setText(id, txt) {
    const el = document.getElementById(id);
    if (el) el.textContent = txt;
  }

  function tick() {
    const book = buildOrderBook();
    renderOrderBook(book);
    pushTrade();
    renderTape();
    setText('miVol', fmtSize(baseSize() * 50000 * (0.8 + Math.random() * 0.4)));
  }

  document.addEventListener('DOMContentLoaded', () => {
    initLivePrice();
    loadHeldQty();
    tick();
    setInterval(tick, 1500);
  });
})();