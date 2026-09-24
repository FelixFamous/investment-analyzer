(function () {
  'use strict';

  const API = window.CORR_API || '';
  const $matrix = document.getElementById('corrMatrix');
  const $insight = document.getElementById('corrInsight');
  if (!$matrix) return;

  let currentDays = 30;

  function colorFor(value) {
    // value: -1 to 1
    const v = Math.abs(value);
    if (value >= 0.7) return `rgba(14,203,129,${0.15 + v * 0.75})`;
    if (value >= 0.3) return `rgba(14,203,129,${0.1 + v * 0.4})`;
    if (value > -0.3) return `rgba(136,146,168,0.10)`;
    if (value > -0.7) return `rgba(246,70,93,${0.1 + v * 0.4})`;
    return `rgba(246,70,93,${0.15 + v * 0.75})`;
  }

  async function load(days) {
    $matrix.innerHTML = '<div class="empty-state" style="padding:60px;">Computing matrix…</div>';
    if ($insight) $insight.innerHTML = '';

    try {
      const r = await fetch(API + '/api/correlation.php?days=' + days, { credentials: 'same-origin' });
      const json = await r.json();
      if (json.error) throw new Error(json.error);

      const symbols = json.symbols;
      const matrix = json.matrix;
      const n = symbols.length;

      // Build a CSS grid
      let html = `<div class="corr-grid" style="grid-template-columns: 60px repeat(${n}, 1fr);">`;

      // Header row
      html += '<div class="corr-header corner"></div>';
      symbols.forEach(sym => { html += `<div class="corr-header">${sym}</div>`; });

      // Data rows
      symbols.forEach(rowSym => {
        html += `<div class="corr-header">${rowSym}</div>`;
        symbols.forEach(colSym => {
          const val = matrix[rowSym][colSym];
          const bg = colorFor(val);
          const txt = val === 1 ? '1.00' : val.toFixed(2);
          const sign = val > 0 ? '+' : '';
          const display = val === 1 ? txt : sign + val.toFixed(2);
          html += `<div class="corr-cell" style="background:${bg};color:var(--text);" title="${rowSym} · ${colSym} = ${val}">${display}</div>`;
        });
      });

      html += '</div>';
      $matrix.innerHTML = html;

      // Insights
      renderInsights(symbols, matrix);
    } catch (err) {
      $matrix.innerHTML = '<div class="empty-state" style="color:var(--red);padding:40px;">Failed to load.</div>';
      console.warn(err);
    }
  }

  function renderInsights(symbols, matrix) {
    if (!$insight) return;

    // Find strongest positive pair (excluding diagonal)
    let strongestPos = { a: null, b: null, v: -2 };
    let strongestNeg = { a: null, b: null, v: 2 };

    for (let i = 0; i < symbols.length; i++) {
      for (let j = i + 1; j < symbols.length; j++) {
        const v = matrix[symbols[i]][symbols[j]];
        if (v > strongestPos.v) { strongestPos = { a: symbols[i], b: symbols[j], v }; }
        if (v < strongestNeg.v) { strongestNeg = { a: symbols[i], b: symbols[j], v }; }
      }
    }

    let html = '<strong>💡 Insights:</strong><br><br>';
    if (strongestPos.a) {
      html += `• <strong>${strongestPos.a}</strong> and <strong>${strongestPos.b}</strong> are the most correlated at <strong>+${strongestPos.v.toFixed(2)}</strong>. They tend to move together — holding both increases concentration risk.<br><br>`;
    }
    if (strongestNeg.a && strongestNeg.v < 0) {
      html += `• <strong>${strongestNeg.a}</strong> and <strong>${strongestNeg.b}</strong> are the most anti-correlated at <strong>${strongestNeg.v.toFixed(2)}</strong>. They often move opposite — useful for hedging.<br><br>`;
    }
    html += '• Values near <strong>+1</strong> mean two assets move in the same direction.<br>';
    html += '• Values near <strong>0</strong> mean no relationship.<br>';
    html += '• Values near <strong>-1</strong> mean two assets move in opposite directions.';

    $insight.innerHTML = html;
  }

  document.querySelectorAll('#corrDaysTabs [data-days]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#corrDaysTabs [data-days]').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentDays = parseInt(btn.dataset.days, 10);
      load(currentDays);
    });
  });

  load(currentDays);
})();