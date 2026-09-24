<?php
require_once __DIR__ . '/includes/auth.php';

$user = require_login();

$pageTitle = 'News';
require __DIR__ . '/includes/header.php';
?>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">News Sources</div>
    <div class="card-value" style="font-size:20px;">Crypto + Stocks</div>
    <div class="card-sub">Yahoo Finance + crypto feeds</div>
  </div>
  <div class="card">
    <div class="card-label">Sentiment Engine</div>
    <div class="card-value" style="font-size:20px;">Keyword AI</div>
    <div class="card-sub">Bullish / Bearish per headline</div>
  </div>
  <div class="card">
    <div class="card-label">Refresh</div>
    <div class="card-value" style="font-size:20px;">Every 5 min</div>
    <div class="card-sub">Auto-updates in background</div>
  </div>
  <div class="card">
    <div class="card-label">Feed Status</div>
    <div class="card-value" style="font-size:16px;">
      <span class="badge badge-live">live</span>
    </div>
    <div class="card-sub">Streaming headlines</div>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>📰 Market Headlines</h2>
      <div class="tabs" style="margin:0;padding:2px;">
        <button class="tab active" data-news-tab="all"    style="padding:4px 10px;font-size:11px;">All</button>
        <button class="tab"        data-news-tab="crypto" style="padding:4px 10px;font-size:11px;">Crypto</button>
        <button class="tab"        data-news-tab="stock"  style="padding:4px 10px;font-size:11px;">Stocks</button>
      </div>
    </div>
    <div id="newsFeed">
      <div class="empty-state">Loading news…</div>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>🔥 Top Movers</h2>
      <span class="badge">live</span>
    </div>
    <div id="topMovers">
      <div class="empty-state">Loading live data…</div>
    </div>
  </div>
</div>

<script>
// Render top movers from live prices
(function () {
  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1)    return '$' + n.toFixed(2);
    if (n >= 0.01) return '$' + n.toFixed(4);
    return '$' + n.toFixed(7);
  }
  function fmtPct(n) { return (n >= 0 ? '+' : '') + Number(n).toFixed(2) + '%'; }

  function renderTopMovers() {
    const el = document.getElementById('topMovers');
    if (!el || !window.LivePrices) return;

    const all = window.LivePrices.getAll();
    const rows = Object.values(all)
      .filter(p => p && typeof p.changePct === 'number')
      .sort((a, b) => Math.abs(b.changePct) - Math.abs(a.changePct))
      .slice(0, 10);

    if (!rows.length) {
      el.innerHTML = '<div class="empty-state">Waiting for live prices…</div>';
      return;
    }

    el.innerHTML = rows.map(p => {
      const cls = p.changePct >= 0 ? 'pos' : 'neg';
      const arrow = p.changePct >= 0 ? '▲' : '▼';
      return `
        <div class="watchlist-row" data-buy="${p.symbol}" data-price="${p.price}" title="Click to buy ${p.symbol}">
          <div class="watchlist-icon" style="background:${p.color};">${p.symbol.slice(0,2)}</div>
          <div class="watchlist-name">
            ${p.symbol}
            <small>${p.name}</small>
          </div>
          <div class="watchlist-price">${fmtPrice(p.price)}</div>
          <div class="watchlist-change ${cls}">${arrow} ${fmtPct(p.changePct)}</div>
        </div>
      `;
    }).join('');
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Wait a tick for LivePrices to populate
    if (window.LivePrices) {
      LivePrices.subscribe(renderTopMovers);
      renderTopMovers();
    } else {
      window.addEventListener('liveprices:update', renderTopMovers);
      setTimeout(renderTopMovers, 1000);
    }
  });
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>