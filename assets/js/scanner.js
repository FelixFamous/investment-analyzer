(function () {
  'use strict';

  const API = window.SC_API || '';
  const $results = document.getElementById('scResults');
  if (!$results) return;

  let allAssets = [];

  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1) return '$' + n.toFixed(2);
    if (n >= 0.01) return '$' + n.toFixed(4);
    return '$' + n.toFixed(7);
  }
  function fmtPct(n) {
    return (n >= 0 ? '+' : '') + Number(n).toFixed(2) + '%';
  }

  async function loadAssets() {
    try {
      const r = await fetch(API + '/api/scanner.php', { credentials: 'same-origin' });
      const json = await r.json();
      allAssets = json.assets || [];

      document.getElementById('scTotal').textContent = allAssets.length;
      const bulls = allAssets.filter(a => a.scans.includes('trend_bull')).length;
      const bears = allAssets.filter(a => a.scans.includes('trend_bear')).length;
      document.getElementById('scBulls').textContent = bulls;
      document.getElementById('scBears').textContent = bears;

      render();
    } catch (err) {
      console.error(err);
      document.getElementById('scLoading').textContent = 'Failed to load.';
    }
  }

  function render() {
    const preset = document.getElementById('scPreset').value;
    const typeFilter = document.getElementById('scType').value;
    const minChange = parseFloat(document.getElementById('scMinChange').value);
    const maxRsi = parseFloat(document.getElementById('scMaxRsi').value);

    let filtered = allAssets.slice();

    if (preset !== 'all') {
      filtered = filtered.filter(a => a.scans.includes(preset));
    }
    if (typeFilter) {
      filtered = filtered.filter(a => a.type === typeFilter);
    }
    if (!isNaN(minChange)) {
      filtered = filtered.filter(a => a.changePct >= minChange);
    }
    if (!isNaN(maxRsi)) {
      filtered = filtered.filter(a => a.rsi !== null && a.rsi <= maxRsi);
    }

    document.getElementById('scMatches').textContent = filtered.length;

    if (!filtered.length) {
      $results.innerHTML = '<div class="scanner-empty">No assets match your filters. Try adjusting them.</div>';
      return;
    }

    $results.innerHTML = filtered.map(a => `
      <div class="scanner-card">
        <div class="scanner-card-head">
          <div class="scanner-card-sym">${a.symbol}</div>
          <div class="scanner-card-price">${fmtPrice(a.price)}</div>
        </div>
        <div class="scanner-card-stats">
          <span>Change</span><strong style="color:${a.changePct >= 0 ? 'var(--green)' : 'var(--red)'};">${fmtPct(a.changePct)}</strong>
          <span>RSI (14)</span><strong>${a.rsi !== null ? a.rsi.toFixed(1) : '—'}</strong>
          <span>20d momentum</span><strong style="color:${a.mom20 >= 0 ? 'var(--green)' : 'var(--red)'};">${fmtPct(a.mom20)}</strong>
          <span>Trend</span><strong>${a.trend === 'bull' ? '▲ Up' : '▼ Down'}</strong>
        </div>
        <div class="scanner-card-tags">
          ${a.tags.map(t => `<span class="scanner-tag ${t.class}">${t.text}</span>`).join('')}
        </div>
      </div>
    `).join('');
  }

  document.getElementById('scRun').addEventListener('click', render);
  document.getElementById('scPreset').addEventListener('change', render);
  document.getElementById('scType').addEventListener('change', render);
  document.getElementById('scMinChange').addEventListener('input', render);
  document.getElementById('scMaxRsi').addEventListener('input', render);

  document.getElementById('scReset').addEventListener('click', () => {
    document.getElementById('scPreset').value = 'all';
    document.getElementById('scType').value = '';
    document.getElementById('scMinChange').value = '';
    document.getElementById('scMaxRsi').value = '';
    render();
  });

  loadAssets();
})();