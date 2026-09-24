/**
 * AlphaEdge dashboard — front-end wiring.
 * Buy / Sell / Deposit / Withdraw / Timezone modals + live price rendering.
 * Watchlist stars on every row.
 */

(function () {
  'use strict';

  const API  = (window.ALPHAEDGE && window.ALPHAEDGE.api)  || '';
  const CSRF = (window.ALPHAEDGE && window.ALPHAEDGE.csrf) || '';

  /* ---------- Toasts ---------- */
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
    el._t = setTimeout(() => { el.className = 'toast'; }, 3000);
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

  /* ---------- HTTP ---------- */
  async function post(url, data) {
    const body = new URLSearchParams({ ...data, csrf: CSRF });
    const res = await fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
    });
    let json;
    try { json = await res.json(); } catch { json = { error: 'Bad server response' }; }
    if (!res.ok) throw new Error(json.error || ('HTTP ' + res.status));
    return json;
  }

  /* ---------- Formatting ---------- */
  function fmtUsd(n) {
    if (n === null || n === undefined || isNaN(n)) return '$0.00';
    const neg = n < 0;
    const v = Math.abs(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return (neg ? '-$' : '$') + v;
  }
  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1)    return '$' + n.toFixed(2);
    if (n >= 0.01) return '$' + n.toFixed(4);
    return '$' + n.toFixed(7);
  }
  function fmtPct(n) {
    return (n >= 0 ? '+' : '') + Number(n).toFixed(2) + '%';
  }

  /* =========================================================
   * LIVE PRICE RENDERING
   * ========================================================= */
  const lastPrices = {};

  function applyLivePrices(prices) {
    if (!prices) return;

    renderTicker(prices);
    renderWatchlist(prices);

    document.querySelectorAll('[data-live-price]').forEach(cell => {
      const sym = cell.dataset.livePrice;
      const p = prices[sym];
      if (!p) return;
      cell.textContent = fmtPrice(p.price);
      flashCell(cell, sym, p.price);
    });

    const holdings = document.querySelectorAll('[data-sell-row]');
    let totalHoldingsValue = 0;
    let totalUnrealized = 0;

    holdings.forEach(row => {
      const sym    = row.dataset.sellRow;
      const qty    = parseFloat(row.dataset.qty) || 0;
      const avg    = parseFloat(row.dataset.avg) || 0;
      const p      = prices[sym];
      if (!p) return;

      const val = qty * p.price;
      const pnl = (p.price - avg) * qty;
      const pct = avg > 0 ? ((p.price - avg) / avg) * 100 : 0;

      totalHoldingsValue += val;
      totalUnrealized    += pnl;

      const valueEl = document.querySelector(`[data-hold-value="${sym}"]`);
      const pnlEl   = document.querySelector(`[data-hold-pnl="${sym}"]`);
      if (valueEl) valueEl.textContent = fmtUsd(val);
      if (pnlEl) {
        pnlEl.className = 'pnl ' + (pnl >= 0 ? 'pos' : 'neg');
        pnlEl.textContent = `${pnl >= 0 ? '+' : '-'}${fmtUsd(Math.abs(pnl))} (${fmtPct(pct)})`;
      }
      row.dataset.price = p.price;
    });

    const cash = parseFloat(window.ALPHAEDGE.cash) || 0;
    const equity = cash + totalHoldingsValue;

    setText('statHoldings', fmtUsd(totalHoldingsValue));
    setText('statEquity', fmtUsd(equity));
    const pnlEl = document.getElementById('statPnl');
    if (pnlEl) {
      pnlEl.textContent = (totalUnrealized >= 0 ? '+' : '-') + fmtUsd(Math.abs(totalUnrealized));
      pnlEl.className = 'card-value ' + (totalUnrealized >= 0 ? 'positive' : 'negative');
    }
    const topBal = document.getElementById('topBalance');
    if (topBal) {
      topBal.innerHTML = `<small>USD</small>${fmtUsd(equity)}`;
    }
  }

  function setText(id, txt) {
    const el = document.getElementById(id);
    if (el) el.textContent = txt;
  }

  function flashCell(el, sym, newPrice) {
    const prev = lastPrices[sym];
    lastPrices[sym] = newPrice;
    if (prev === undefined) return;
    if (newPrice > prev) {
      el.classList.remove('flash-down'); el.classList.add('flash-up');
      setTimeout(() => el.classList.remove('flash-up'), 600);
    } else if (newPrice < prev) {
      el.classList.remove('flash-up'); el.classList.add('flash-down');
      setTimeout(() => el.classList.remove('flash-down'), 600);
    }
  }

  function renderTicker(prices) {
    const el = document.getElementById('liveTicker');
    if (!el) return;

    const order = ['BTC','ETH','SOL','BNB','XRP','DOGE','ADA','PEPE','AAPL','MSFT','NVDA','TSLA','AMD','META','GOOGL','AMZN','NFLX','SPX','NDX','DJI','VIX'];

    const html = order.map(sym => {
      const p = prices[sym];
      if (!p) return '';
      const cls = p.changePct >= 0 ? 'pos' : 'neg';
      return `
        <div class="ticker-item">
          <span class="sym">${sym}</span>
          <span class="price">${fmtPrice(p.price)}</span>
          <span class="chg ${cls}">${fmtPct(p.changePct)}</span>
        </div>
      `;
    }).join('');

    el.innerHTML = html || '<span class="text-dim" style="font-size:12px;">No live data</span>';
  }

  function renderWatchlist(prices) {
    const el = document.getElementById('liveWatchlist');
    if (!el) return;

    const groups = [
      { title: 'Indices', syms: ['SPX', 'NDX', 'DJI', 'VIX'] },
      { title: 'Stocks',  syms: ['AAPL','MSFT','NVDA','TSLA','AMD','META','GOOGL','AMZN','NFLX'] },
      { title: 'Crypto',  syms: ['BTC','ETH','SOL','BNB','XRP','DOGE','ADA','PEPE'] },
    ];

    const starredSet = el._starredSet || (el._starredSet = new Set());
    const STAR_SVG = (filled) => `<svg viewBox="0 0 24 24" width="14" height="14" fill="${filled ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`;

    let html = '';
    groups.forEach(g => {
      const available = g.syms.filter(s => prices[s]);
      if (!available.length) return;

      html += `<div class="watchlist-section">${g.title}</div>`;
      available.forEach(sym => {
        const p = prices[sym];
        const cls = p.changePct >= 0 ? 'pos' : 'neg';
        const initial = sym.slice(0, 2).toUpperCase();
        const isStarred = starredSet.has(sym);
        html += `
          <div class="watchlist-row" data-buy="${sym}" data-price="${p.price}" title="View ${sym}">
            <div class="watchlist-icon" style="background:${p.color};">${initial}</div>
            <div class="watchlist-name">
              ${sym}
              <small>${p.name}</small>
            </div>
            <div class="watchlist-price">${fmtPrice(p.price)}</div>
            <div class="watchlist-change ${cls}">${fmtPct(p.changePct)}</div>
            <button type="button" class="star-btn-mini ${isStarred ? 'starred' : ''}"
                    data-star="${sym}" title="${isStarred ? 'Remove from watchlist' : 'Add to watchlist'}">
              ${STAR_SVG(isStarred)}
            </button>
          </div>
        `;
      });
    });

    el.innerHTML = html || '<div class="empty-state">Loading live prices…</div>';

    // Load user watchlist once and sync star states
    if (window.Watchlist && !el._wlLoaded) {
      el._wlLoaded = true;
      fetch(API + '/api/watchlist.php?action=list', { credentials: 'same-origin' })
        .then(r => r.json())
        .then(json => {
          if (json.items) {
            json.items.forEach(it => starredSet.add(it.symbol));
            el.querySelectorAll('[data-star]').forEach(btn => {
              const s = btn.dataset.star;
              if (starredSet.has(s)) {
                btn.classList.add('starred');
                const svg = btn.querySelector('svg');
                if (svg) {
                  const tmp = document.createElement('div');
                  tmp.innerHTML = STAR_SVG(true);
                  svg.replaceWith(tmp.firstElementChild);
                }
              }
            });
          }
        })
        .catch(() => {});
    }
  }

  // Keep star states in sync when Watchlist.js toggles them
  window.addEventListener('watchlist:updated', () => {
    // no-op; Watchlist.js already updates the buttons
  });

  /* =========================================================
   * MODALS
   * ========================================================= */

  function openBuyModal(symbol, price) {
    const maxQty = price > 0 ? Math.floor((window.ALPHAEDGE.cash || 0) / price * 10000) / 10000 : 0;

    const overlay = modal(`
      <h3>Buy ${symbol}</h3>
      <div class="sub">Price ${fmtPrice(price)} per unit</div>
      <div class="info-row"><span>Available cash</span><strong>${fmtUsd(window.ALPHAEDGE.cash)}</strong></div>
      <div class="info-row"><span>Max quantity</span><strong>${maxQty}</strong></div>
      <div class="form-group" style="margin-top:16px;">
        <label>Quantity</label>
        <input type="number" id="ae-qty" step="0.0001" min="0.0001" value="1" autofocus>
      </div>
      <div class="info-row"><span>Estimated cost</span><strong id="ae-cost">${fmtUsd(price)}</strong></div>
      <div class="modal-actions">
        <button class="btn" id="ae-cancel">Cancel</button>
        <button class="btn btn-primary" id="ae-confirm">Confirm Buy</button>
      </div>
    `);

    const qty  = overlay.querySelector('#ae-qty');
    const cost = overlay.querySelector('#ae-cost');
    qty.addEventListener('input', () => {
      const q = parseFloat(qty.value) || 0;
      cost.textContent = fmtUsd(q * price);
      cost.style.color = (q * price > (window.ALPHAEDGE.cash || 0)) ? 'var(--red)' : '';
    });

    overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
    overlay.querySelector('#ae-confirm').addEventListener('click', async () => {
      const q = parseFloat(qty.value);
      if (!q || q <= 0) return;
      const btn = overlay.querySelector('#ae-confirm');
      btn.disabled = true; btn.textContent = 'Placing…';
      try {
        const r = await post(API + '/api/buy.php', { symbol, quantity: q });
        toast(r.message || 'Trade placed', 'success');
        closeModal();
        setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false; btn.textContent = 'Confirm Buy';
      }
    });

    qty.focus(); qty.select();
  }

  function openSellModal(symbol, qty, avg, price) {
    const overlay = modal(`
      <h3>Sell ${symbol}</h3>
      <div class="sub">You hold ${qty} units @ ${fmtPrice(avg)}</div>
      <div class="info-row"><span>Current price</span><strong>${fmtPrice(price)}</strong></div>
      <div class="form-group" style="margin-top:16px;">
        <label>Quantity to sell</label>
        <input type="number" id="ae-sqty" step="0.0001" min="0.0001" max="${qty}" value="${qty}" autofocus>
      </div>
      <div class="info-row"><span>Estimated proceeds</span><strong id="ae-proc">${fmtUsd(qty * price)}</strong></div>
      <div class="modal-actions">
        <button class="btn" id="ae-cancel">Cancel</button>
        <button class="btn btn-primary" id="ae-confirm-sell">Confirm Sell</button>
      </div>
    `);

    const input = overlay.querySelector('#ae-sqty');
    const proc  = overlay.querySelector('#ae-proc');
    input.addEventListener('input', () => {
      const q = parseFloat(input.value) || 0;
      proc.textContent = fmtUsd(q * price);
    });

    overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
    overlay.querySelector('#ae-confirm-sell').addEventListener('click', async () => {
      const q = parseFloat(input.value);
      if (!q || q <= 0 || q > qty) return;
      const btn = overlay.querySelector('#ae-confirm-sell');
      btn.disabled = true; btn.textContent = 'Placing…';
      try {
        const r = await post(API + '/api/sell.php', { symbol, quantity: q });
        toast(r.message || 'Sold', 'success');
        closeModal();
        setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false; btn.textContent = 'Confirm Sell';
      }
    });

    input.focus(); input.select();
  }

  function openDepositModal() {
    const overlay = modal(`
      <h3>Deposit Funds</h3>
      <div class="sub">Add virtual demo funds. No real money is transferred.</div>
      <div class="info-row"><span>Current cash</span><strong>${fmtUsd(window.ALPHAEDGE.cash)}</strong></div>
      <div class="form-group" style="margin-top:16px;">
        <label>Amount (USD)</label>
        <input type="number" id="ae-amt" step="1" min="1" max="1000000" value="1000" autofocus>
      </div>
      <div class="modal-actions">
        <button class="btn" id="ae-cancel">Cancel</button>
        <button class="btn btn-primary" id="ae-confirm-dep">Add Funds</button>
      </div>
    `);

    const amt = overlay.querySelector('#ae-amt');
    overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
    overlay.querySelector('#ae-confirm-dep').addEventListener('click', async () => {
      const a = parseFloat(amt.value);
      if (!a || a <= 0) return;
      const btn = overlay.querySelector('#ae-confirm-dep');
      btn.disabled = true; btn.textContent = 'Processing…';
      try {
        const r = await post(API + '/api/deposit.php', { amount: a });
        toast(r.message || 'Deposited', 'success');
        closeModal();
        setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false; btn.textContent = 'Add Funds';
      }
    });

    amt.focus(); amt.select();
  }

  function openWithdrawModal() {
    const overlay = modal(`
      <h3>Withdraw Funds</h3>
      <div class="sub">Remove virtual demo funds. No real money is transferred.</div>
      <div class="info-row"><span>Available cash</span><strong>${fmtUsd(window.ALPHAEDGE.cash)}</strong></div>
      <div class="form-group" style="margin-top:16px;">
        <label>Amount (USD)</label>
        <input type="number" id="ae-wamt" step="1" min="1" value="100" autofocus>
      </div>
      <div class="modal-actions">
        <button class="btn" id="ae-cancel">Cancel</button>
        <button class="btn btn-primary" id="ae-confirm-wd">Withdraw</button>
      </div>
    `);

    const amt = overlay.querySelector('#ae-wamt');
    overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
    overlay.querySelector('#ae-confirm-wd').addEventListener('click', async () => {
      const a = parseFloat(amt.value);
      if (!a || a <= 0) return;
      const btn = overlay.querySelector('#ae-confirm-wd');
      btn.disabled = true; btn.textContent = 'Processing…';
      try {
        const r = await post(API + '/api/withdraw.php', { amount: a });
        toast(r.message || 'Withdrawn', 'success');
        closeModal();
        setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false; btn.textContent = 'Withdraw';
      }
    });

    amt.focus(); amt.select();
  }

  /* =========================================================
   * TIMEZONE PICKER
   * ========================================================= */

  const TZ_LIST = [
    ['Africa/Lagos','Nigeria (WAT · Lagos)'],
    ['Africa/Accra','Ghana (GMT · Accra)'],
    ['Africa/Nairobi','Kenya (EAT · Nairobi)'],
    ['Africa/Johannesburg','South Africa (SAST · Johannesburg)'],
    ['Africa/Cairo','Egypt (EET · Cairo)'],
    ['Africa/Casablanca','Morocco (WET · Casablanca)'],
    ['Europe/London','United Kingdom (GMT/BST · London)'],
    ['Europe/Paris','France / Germany (CET · Paris)'],
    ['Europe/Moscow','Russia (MSK · Moscow)'],
    ['America/New_York','USA East (EST/EDT · New York)'],
    ['America/Chicago','USA Central (CST · Chicago)'],
    ['America/Denver','USA Mountain (MST · Denver)'],
    ['America/Los_Angeles','USA Pacific (PST · Los Angeles)'],
    ['America/Sao_Paulo','Brazil (BRT · São Paulo)'],
    ['Asia/Dubai','UAE (GST · Dubai)'],
    ['Asia/Karachi','Pakistan (PKT · Karachi)'],
    ['Asia/Kolkata','India (IST · Kolkata)'],
    ['Asia/Dhaka','Bangladesh (BST · Dhaka)'],
    ['Asia/Bangkok','Thailand / Vietnam (ICT · Bangkok)'],
    ['Asia/Shanghai','China (CST · Shanghai)'],
    ['Asia/Singapore','Singapore (SGT)'],
    ['Asia/Tokyo','Japan (JST · Tokyo)'],
    ['Asia/Seoul','South Korea (KST · Seoul)'],
    ['Australia/Sydney','Australia East (AEDT · Sydney)'],
    ['Pacific/Auckland','New Zealand (NZDT · Auckland)'],
    ['UTC','UTC']
  ];

  function openTimezoneModal(current) {
    const options = TZ_LIST.map(([tz, label]) =>
      `<option value="${tz}"${tz === current ? ' selected' : ''}>${label}</option>`
    ).join('');

    const overlay = modal(`
      <h3>Change Timezone</h3>
      <div class="sub">All times on the platform will be shown in the timezone you choose.</div>
      <div class="form-group">
        <label>Country / Timezone</label>
        <select id="ae-tz">${options}</select>
      </div>
      <div class="modal-actions">
        <button class="btn" id="ae-cancel">Cancel</button>
        <button class="btn btn-primary" id="ae-confirm-tz">Save</button>
      </div>
    `);

    overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
    overlay.querySelector('#ae-confirm-tz').addEventListener('click', async () => {
      const tz = overlay.querySelector('#ae-tz').value;
      const btn = overlay.querySelector('#ae-confirm-tz');
      btn.disabled = true; btn.textContent = 'Saving…';
      try {
        await post(API + '/api/set-timezone.php', { timezone: tz });
        toast('Timezone updated', 'success');
        closeModal();
        setTimeout(() => window.location.reload(), 500);
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false; btn.textContent = 'Save';
      }
    });
  }

  /* =========================================================
   * WIRE EVERYTHING
   * ========================================================= */

  document.addEventListener('DOMContentLoaded', () => {
    const depBtn = document.getElementById('aeDepositBtn');
    const wdBtn  = document.getElementById('aeWithdrawBtn');
    const tzBtn  = document.getElementById('aeTzBtn');
    if (depBtn) depBtn.addEventListener('click', openDepositModal);
    if (wdBtn)  wdBtn.addEventListener('click', openWithdrawModal);
    if (tzBtn)  tzBtn.addEventListener('click', () => {
      const current = (window.ALPHAEDGE && window.ALPHAEDGE.timezone) || 'UTC';
      openTimezoneModal(current);
    });

    document.addEventListener('click', e => {
      // Star buttons handled by watchlist.js — skip our handlers for them
      if (e.target.closest('[data-star]')) return;

      // Watchlist rows navigate to the asset detail page
      const wlRow = e.target.closest('.watchlist-row');
      if (wlRow && wlRow.dataset.buy) {
        window.location.href = API + '/asset.php?symbol=' + encodeURIComponent(wlRow.dataset.buy);
        return;
      }

      const buyBtn = e.target.closest('[data-buy]');
      if (buyBtn) {
        e.preventDefault();
        const sym   = buyBtn.dataset.buy;
        const price = parseFloat(buyBtn.dataset.price) || 0;
        if (sym && price > 0) openBuyModal(sym, price);
        return;
      }

      const sellRow = e.target.closest('[data-sell-row]');
      if (sellRow) {
        e.preventDefault();
        openSellModal(
          sellRow.dataset.sellRow,
          parseFloat(sellRow.dataset.qty),
          parseFloat(sellRow.dataset.avg),
          parseFloat(sellRow.dataset.price)
        );
      }
    });

    function startLive() {
      if (!window.LivePrices) return;
      applyLivePrices(window.LivePrices.getAll());
      window.LivePrices.subscribe(applyLivePrices);
      window.LivePrices.start(20000);
    }

    if (window.LivePrices) {
      startLive();
    } else {
      window.addEventListener('load', startLive);
    }

    window.addEventListener('liveprices:update', e => applyLivePrices(e.detail));
  });

  window.aeOpenBuy      = openBuyModal;
  window.aeOpenSell     = openSellModal;
  window.aeOpenDeposit  = openDepositModal;
  window.aeOpenWithdraw = openWithdrawModal;
  window.aeOpenTimezone = openTimezoneModal;
  window.aeApplyPrices  = applyLivePrices;
})();