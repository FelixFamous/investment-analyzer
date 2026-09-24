/**
 * AlphaEdge · Watchlist
 * Star toggle from anywhere, page rendering, note editing.
 */

(function () {
  'use strict';

  const API  = (window.ALPHAEDGE && window.ALPHAEDGE.api)  || '';
  const CSRF = (window.ALPHAEDGE && window.ALPHAEDGE.csrf) || '';

  /* ============================================================
   *  TOAST
   * ============================================================ */
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

  /* ============================================================
   *  POST HELPER
   * ============================================================ */
  async function post(action, data) {
    const body = new URLSearchParams({ ...data, action, csrf: CSRF });
    const res = await fetch(API + '/api/watchlist.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
    });
    let json;
    try { json = await res.json(); } catch { json = { error: 'Bad response' }; }
    if (!res.ok) throw new Error(json.error || ('HTTP ' + res.status));
    return json;
  }

  /* ============================================================
   *  STAR RENDER HELPER — used by other scripts
   * ============================================================ */
  function starSvg(filled) {
    return `<svg viewBox="0 0 24 24" width="16" height="16" fill="${filled ? 'currentColor' : 'none'}"
              stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
    </svg>`;
  }

  window.Watchlist = {
    starSvg,
    async has(symbol) {
      try {
        const res = await fetch(API + '/api/watchlist.php?action=has&symbol=' + encodeURIComponent(symbol),
          { credentials: 'same-origin' });
        const json = await res.json();
        return !!json.has;
      } catch { return false; }
    },
    async toggle(symbol) {
      return post('toggle', { symbol });
    },
  };

  /* ============================================================
   *  GLOBAL STAR CLICK HANDLER
   *  Works on any page. Looks for [data-star="SYMBOL"]
   * ============================================================ */
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-star]');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();

    const symbol = btn.dataset.star;
    if (!symbol) return;

    btn.classList.add('star-busy');
    btn.disabled = true;

    try {
      const r = await post('toggle', { symbol });

      // Update visual state on every matching button
      document.querySelectorAll(`[data-star="${symbol}"]`).forEach(b => {
        b.classList.toggle('starred', r.watching);
        const icon = b.querySelector('.star-icon');
        if (icon) icon.innerHTML = starSvg(r.watching);
        b.setAttribute('aria-label', r.watching ? 'Remove from watchlist' : 'Add to watchlist');
        b.title = r.watching ? 'Remove from watchlist' : 'Add to watchlist';
      });

      toast(r.message || (r.watching ? 'Added to watchlist' : 'Removed'), 'success');

      // Update the watchlist page if we're on it
      if (document.getElementById('watchlistPage')) {
        loadWatchlistPage();
      }
      // Refresh badge count in sidebar if present
      const badge = document.getElementById('watchlistBadge');
      if (badge) refreshBadge();
    } catch (err) {
      toast(err.message, 'error');
    } finally {
      btn.classList.remove('star-busy');
      btn.disabled = false;
    }
  });

  /* ============================================================
   *  WATCHLIST PAGE RENDERING
   * ============================================================ */
  async function loadWatchlistPage() {
    const $grid = document.getElementById('watchlistGrid');
    if (!$grid) return;

    $grid.innerHTML = '<div class="empty-state" style="padding:40px 20px;">Loading…</div>';

    try {
      const res = await fetch(API + '/api/watchlist.php?action=list', { credentials: 'same-origin' });
      const json = await res.json();
      const items = json.items || [];

      const $count = document.getElementById('watchlistCount');
      if ($count) $count.textContent = items.length;

      // Update stats cards
      updateStats(items);

      if (!items.length) {
        $grid.innerHTML = `
          <div class="empty-state" style="padding:60px 20px;">
            <div style="font-size:44px;margin-bottom:12px;opacity:0.5;">⭐</div>
            <div style="font-size:14px;color:var(--text);margin-bottom:6px;font-weight:700;">No symbols on your watchlist</div>
            <div style="font-size:12.5px;">Open <a href="${API}/markets.php" style="color:var(--accent);">Markets</a> and click the ☆ on any row.</div>
          </div>`;
        return;
      }

      $grid.innerHTML = items.map(it => {
        const sym = it.symbol;
        const live = window.LivePrices ? window.LivePrices.get(sym) : null;
        const price = live ? live.price : it.price;
        const changePct = live ? live.changePct : 0;
        const cls = changePct >= 0 ? 'pos' : 'neg';
        const initial = sym.slice(0, 2).toUpperCase();

        return `
          <div class="watchlist-card" data-watch-sym="${sym}">
            <div class="watchlist-card-head">
              <a href="${API}/asset.php?symbol=${encodeURIComponent(sym)}" class="watchlist-card-link">
                <div class="watchlist-card-icon">${initial}</div>
                <div>
                  <div class="watchlist-card-sym">${sym}</div>
                  <div class="watchlist-card-name">${it.note ? escapeHtml(it.note) : 'Watching'}</div>
                </div>
              </a>
              <button class="star-btn starred" data-star="${sym}" title="Remove from watchlist" aria-label="Remove from watchlist">
                <span class="star-icon">${starSvg(true)}</span>
              </button>
            </div>

            <div class="watchlist-card-price" data-live-price="${sym}">
              ${price ? '$' + formatPrice(price) : '—'}
            </div>
            <div class="watchlist-card-change ${cls}" data-live-change="${sym}">
              ${fmtPct(changePct)}
            </div>

            <div class="watchlist-card-actions">
              <button class="btn btn-sm btn-primary" data-buy="${sym}" data-price="${price || 0}">Buy</button>
              <a class="btn btn-sm" href="${API}/asset.php?symbol=${encodeURIComponent(sym)}">Chart</a>
              <button class="btn btn-sm" data-note="${sym}">📝</button>
            </div>

            ${it.note ? `<div class="watchlist-card-note">"${escapeHtml(it.note)}"</div>` : ''}
          </div>
        `;
      }).join('');

      // Live update
      if (window.LivePrices) {
        window.LivePrices.subscribe(updateLivePrices);
        updateLivePrices(window.LivePrices.getAll());
      }
    } catch (err) {
      $grid.innerHTML = '<div class="empty-state" style="color:var(--red);">Failed to load watchlist.</div>';
      console.error('watchlist page error:', err);
    }
  }

  function updateLivePrices(prices) {
    document.querySelectorAll('[data-live-price]').forEach(el => {
      const sym = el.dataset.livePrice;
      const p = prices[sym];
      if (!p) return;
      el.textContent = '$' + formatPrice(p.price);
    });
    document.querySelectorAll('[data-live-change]').forEach(el => {
      const sym = el.dataset.liveChange;
      const p = prices[sym];
      if (!p) return;
      el.textContent = fmtPct(p.changePct);
      el.className = 'watchlist-card-change ' + (p.changePct >= 0 ? 'pos' : 'neg');
    });
  }

  function updateStats(items) {
    const n = items.length;
    let gainers = 0, losers = 0;
    if (window.LivePrices) {
      items.forEach(it => {
        const p = window.LivePrices.get(it.symbol);
        if (!p) return;
        if (p.changePct > 0) gainers++;
        else if (p.changePct < 0) losers++;
      });
    }
    setText('wlTotal', n);
    setText('wlGainers', gainers);
    setText('wlLosers', losers);
  }

  function setText(id, v) {
    const el = document.getElementById(id);
    if (el) el.textContent = v;
  }

  function formatPrice(n) {
    n = Number(n);
    if (n >= 1000) return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1) return n.toFixed(2);
    if (n >= 0.01) return n.toFixed(4);
    return n.toFixed(7);
  }

  function fmtPct(n) {
    return (n >= 0 ? '+' : '') + Number(n).toFixed(2) + '%';
  }

  function escapeHtml(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  /* ============================================================
   *  NOTE EDITOR
   * ============================================================ */
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-note]');
    if (!btn) return;
    const sym = btn.dataset.note;
    const current = btn.closest('.watchlist-card')?.querySelector('.watchlist-card-note');
    const existing = current ? current.textContent.replace(/^"|"$/g, '') : '';

    const note = prompt('Note for ' + sym + ' (leave empty to clear):', existing);
    if (note === null) return;

    try {
      const r = await post('note', { symbol: sym, note });
      toast(r.message || 'Saved', 'success');
      if (document.getElementById('watchlistPage')) loadWatchlistPage();
    } catch (err) {
      toast(err.message, 'error');
    }
  });

  /* ============================================================
   *  BADGE REFRESH (for sidebar count)
   * ============================================================ */
  async function refreshBadge() {
    try {
      const res = await fetch(API + '/api/watchlist.php?action=count', { credentials: 'same-origin' });
      const json = await res.json();
      const badge = document.getElementById('watchlistBadge');
      if (badge) {
        if (json.count > 0) {
          badge.textContent = json.count;
          badge.style.display = '';
        } else {
          badge.style.display = 'none';
        }
      }
    } catch {}
  }

  /* ============================================================
   *  INIT
   * ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    // If we're on the watchlist page, render it
    if (document.getElementById('watchlistPage')) {
      loadWatchlistPage();
    }
    refreshBadge();
  });
})();