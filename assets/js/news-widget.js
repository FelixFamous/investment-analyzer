/**
 * News widget + signal tab switching for the dashboard.
 */
(function () {
  'use strict';

  const API = (window.ALPHAEDGE && window.ALPHAEDGE.api) || '';
  let allNews = { crypto: [], stocks: [] };
  let currentFilter = 'all';

  function timeAgo(iso) {
    const t = new Date(iso).getTime();
    if (isNaN(t)) return 'just now';
    const diff = Math.floor((Date.now() - t) / 1000);
    if (diff < 60) return diff + 's ago';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
  }

  function esc(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function renderNews() {
    const el = document.getElementById('newsFeed');
    if (!el) return;

    let items = [];
    if (currentFilter === 'all') {
      items = [...allNews.crypto, ...allNews.stocks];
      items.sort((a, b) => new Date(b.time) - new Date(a.time));
    } else if (currentFilter === 'crypto') {
      items = allNews.crypto;
    } else {
      items = allNews.stocks;
    }

    items = items.slice(0, 15);

    if (!items.length) {
      el.innerHTML = '<div class="empty-state">No news right now.</div>';
      return;
    }

    el.innerHTML = items.map(n => {
      const sentClass = n.sentiment > 0.15 ? 'pos' : (n.sentiment < -0.15 ? 'neg' : '');
      const sentLabel = n.sentiment > 0.15 ? '▲ Bullish' : (n.sentiment < -0.15 ? '▼ Bearish' : '● Neutral');
      const typeBadge = n.type === 'crypto'
        ? '<span style="color:var(--accent);font-weight:700;">CRYPTO</span>'
        : '<span style="color:var(--blue);font-weight:700;">STOCK</span>';

      return `
        <a class="news-item" href="${esc(n.url)}" target="_blank" rel="noopener" style="display:block;text-decoration:none;color:inherit;">
          <div class="news-source">
            ${typeBadge} · ${esc(n.source)} · ${timeAgo(n.time)}
          </div>
          <div class="news-title">${esc(n.title)}</div>
          <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
            <span class="pnl ${sentClass}" style="font-size:10px;font-weight:700;">${sentLabel}</span>
            ${n.symbol ? `<span class="text-dim" style="font-size:10px;font-family:var(--mono);">$${esc(n.symbol)}</span>` : ''}
          </div>
        </a>
      `;
    }).join('');
  }

  async function loadNews() {
    try {
      const r = await fetch(API + '/api/news.php');
      if (!r.ok) throw new Error('HTTP ' + r.status);
      allNews = await r.json();
      renderNews();
      console.log('📰 News loaded:', allNews.crypto.length, 'crypto,', allNews.stocks.length, 'stocks');
    } catch (err) {
      console.warn('News load failed:', err.message);
      const el = document.getElementById('newsFeed');
      if (el) el.innerHTML = '<div class="empty-state">Could not load news.</div>';
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-tab]').forEach(btn => {
      btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        document.querySelectorAll('[data-tab]').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('[data-panel]').forEach(p => p.style.display = 'none');
        btn.classList.add('active');
        const panel = document.querySelector(`[data-panel="${tab}"]`);
        if (panel) panel.style.display = '';
      });
    });

    document.querySelectorAll('[data-news-tab]').forEach(btn => {
      btn.addEventListener('click', () => {
        currentFilter = btn.dataset.newsTab;
        document.querySelectorAll('[data-news-tab]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderNews();
      });
    });

    loadNews();
    setInterval(loadNews, 5 * 60 * 1000);
  });
})();