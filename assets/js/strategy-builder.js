(function () {
  'use strict';

  const API  = window.SB_API  || '';
  const CSRF = window.SB_CSRF || '';

  const $page = document.getElementById('strategiesPage');
  if (!$page) return;

  let editingId = 0;

  const INDICATORS = [
    { value: 'close',      label: 'Price (close)' },
    { value: 'open',       label: 'Open' },
    { value: 'high',       label: 'High' },
    { value: 'low',        label: 'Low' },
    { value: 'volume',     label: 'Volume' },
    { value: 'rsi',        label: 'RSI (14)' },
    { value: 'sma20',      label: 'SMA 20' },
    { value: 'sma50',      label: 'SMA 50' },
    { value: 'sma200',     label: 'SMA 200' },
    { value: 'ema12',      label: 'EMA 12' },
    { value: 'ema26',      label: 'EMA 26' },
    { value: 'macd',       label: 'MACD' },
    { value: 'macd_signal',label: 'MACD Signal' },
    { value: 'macd_hist',  label: 'MACD Histogram' },
    { value: 'bb_upper',   label: 'Bollinger Upper' },
    { value: 'bb_middle',  label: 'Bollinger Middle' },
    { value: 'bb_lower',   label: 'Bollinger Lower' },
    { value: 'bb_pos',     label: 'Bollinger Position (-1 to 1)' },
    { value: 'momentum',   label: 'Momentum (20d)' },
    { value: 'pnl_pct',    label: 'Position P&L % (exit only)' },
  ];

  const OPERATORS = [
    { value: '>',  label: '>' },
    { value: '<',  label: '<' },
    { value: '>=', label: '≥' },
    { value: '<=', label: '≤' },
    { value: 'crosses_above', label: '↑ crosses above' },
    { value: 'crosses_below', label: '↓ crosses below' },
  ];

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

  function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  /* ---------- Add condition row ---------- */
  function addCondition(target, data = { indicator: 'rsi', operator: '<', value: 30 }) {
    const container = document.getElementById(target + 'Conditions');
    const row = document.createElement('div');
    row.className = 'sb-condition';

    const indOpts = INDICATORS.map(i =>
      `<option value="${i.value}" ${data.indicator === i.value ? 'selected' : ''}>${i.label}</option>`
    ).join('');

    const opOpts = OPERATORS.map(o =>
      `<option value="${o.value}" ${data.operator === o.value ? 'selected' : ''}>${o.label}</option>`
    ).join('');

    row.innerHTML = `
      <select data-role="indicator">${indOpts}</select>
      <select data-role="operator">${opOpts}</select>
      <input type="text" data-role="value" value="${esc(data.value ?? 30)}" placeholder="value or indicator">
      <button type="button" class="sb-cond-remove">✕</button>
    `;
    row.querySelector('.sb-cond-remove').addEventListener('click', () => row.remove());
    container.appendChild(row);
  }

  /* ---------- Collect conditions ---------- */
  function collectConditions(target) {
    const container = document.getElementById(target + 'Conditions');
    const conditions = [];
    container.querySelectorAll('.sb-condition').forEach(row => {
      const ind = row.querySelector('[data-role="indicator"]').value;
      const op  = row.querySelector('[data-role="operator"]').value;
      const val = row.querySelector('[data-role="value"]').value.trim();
      if (!ind || !op || val === '') return;

      // If value is a numeric string, convert to number. Otherwise leave as indicator name.
      const numeric = !isNaN(parseFloat(val)) && isFinite(val) && /^-?\d+(\.\d+)?$/.test(val);
      conditions.push({
        indicator: ind,
        operator: op,
        value: numeric ? parseFloat(val) : val,
      });
    });
    return conditions;
  }

  function getLogic(target) {
    const active = document.querySelector(`.sb-logic-tabs[data-target="${target}"] .sb-logic.active`);
    return active ? active.dataset.logic : 'AND';
  }

  /* ---------- Load strategies ---------- */
  async function loadList() {
    try {
      const r = await fetch(API + '/api/strategies.php?action=list', { credentials: 'same-origin' });
      const json = await r.json();
      renderSavedList(json.strategies || []);
      renderPresetList(json.presets || []);
      updateStats(json);
    } catch (err) {
      console.error(err);
    }
  }

  function updateStats(json) {
    document.getElementById('sbCount').textContent = (json.strategies || []).length;
    document.getElementById('sbPresetCount').textContent = (json.presets || []).length;
    let condCount = 0;
    [...(json.strategies || []), ...(json.presets || [])].forEach(s => {
      condCount += ((s.entry_rules && s.entry_rules.conditions) || []).length;
      condCount += ((s.exit_rules && s.exit_rules.conditions) || []).length;
    });
    document.getElementById('sbCondCount').textContent = condCount;
  }

  function renderConditionsChips(rules) {
    if (!rules || !rules.conditions) return '';
    return rules.conditions.map(c => {
      const op = c.operator === 'crosses_above' ? '↑' : (c.operator === 'crosses_below' ? '↓' : c.operator);
      return `<span class="sb-cond-chip">${c.indicator} ${op} ${c.value}</span>`;
    }).join('');
  }

  function renderSavedList(list) {
    const $el = document.getElementById('sbSavedList');
    document.getElementById('sbSavedCount').textContent = list.length;
    if (!list.length) {
      $el.innerHTML = '<div class="empty-state" style="padding:24px;">No saved strategies yet.</div>';
      return;
    }
    $el.innerHTML = list.map(s => `
      <div class="sb-strategy-row">
        <div class="sb-strategy-head">
          <div class="sb-strategy-name">${esc(s.name)}</div>
        </div>
        ${s.description ? `<div class="sb-strategy-desc">${esc(s.description)}</div>` : ''}
        <div class="sb-strategy-conditions">
          ${renderConditionsChips(s.entry_rules)}
          ${renderConditionsChips(s.exit_rules)}
        </div>
        <div class="sb-strategy-actions">
          <button class="btn btn-sm" data-load="${s.id}">✎ Edit</button>
          <button class="btn btn-sm" data-run="${s.id}">▶ Backtest</button>
          <button class="btn btn-sm btn-danger" data-del="${s.id}">Delete</button>
        </div>
      </div>
    `).join('');

    $el.querySelectorAll('[data-load]').forEach(b => b.addEventListener('click', () => loadStrategyIntoEditor(parseInt(b.dataset.load, 10))));
    $el.querySelectorAll('[data-run]').forEach(b => b.addEventListener('click', () => {
      window.location.href = API + '/backtest.php?strategy=' + b.dataset.run;
    }));
    $el.querySelectorAll('[data-del]').forEach(b => b.addEventListener('click', () => deleteStrategy(parseInt(b.dataset.del, 10))));
  }

  function renderPresetList(list) {
    const $el = document.getElementById('sbPresetList');
    if (!list.length) {
      $el.innerHTML = '<div class="empty-state">No presets available.</div>';
      return;
    }
    $el.innerHTML = list.map(s => `
      <div class="sb-strategy-row">
        <div class="sb-strategy-head">
          <div class="sb-strategy-name">
            ${esc(s.name)}
            <span class="preset-chip">preset</span>
          </div>
        </div>
        <div class="sb-strategy-desc">${esc(s.description)}</div>
        <div class="sb-strategy-conditions">
          ${renderConditionsChips(s.entry_rules)}
          ${renderConditionsChips(s.exit_rules)}
        </div>
        <div class="sb-strategy-actions">
          <button class="btn btn-sm btn-primary" data-copy="${s.id}">📋 Copy to mine</button>
          <button class="btn btn-sm" data-run-preset="${s.id}">▶ Backtest</button>
        </div>
      </div>
    `).join('');

    $el.querySelectorAll('[data-copy]').forEach(b => b.addEventListener('click', () => copyPreset(b.dataset.copy)));
    $el.querySelectorAll('[data-run-preset]').forEach(b => b.addEventListener('click', () => {
      window.location.href = API + '/backtest.php?preset=' + b.dataset.runPreset;
    }));
  }

  /* ---------- Editor controls ---------- */
  async function loadStrategyIntoEditor(id) {
    try {
      const r = await fetch(API + '/api/strategies.php?action=get&id=' + id, { credentials: 'same-origin' });
      const json = await r.json();
      const s = json.strategy;
      if (!s) return;

      editingId = id;
      document.getElementById('sbName').value = s.name;
      document.getElementById('sbDesc').value = s.description || '';

      document.getElementById('entryConditions').innerHTML = '';
      (s.entry_rules.conditions || []).forEach(c => addCondition('entry', c));
      document.querySelectorAll('.sb-logic-tabs[data-target="entry"] .sb-logic').forEach(b => {
        b.classList.toggle('active', b.dataset.logic === (s.entry_rules.logic || 'AND'));
      });

      document.getElementById('exitConditions').innerHTML = '';
      (s.exit_rules.conditions || []).forEach(c => addCondition('exit', c));
      document.querySelectorAll('.sb-logic-tabs[data-target="exit"] .sb-logic').forEach(b => {
        b.classList.toggle('active', b.dataset.logic === (s.exit_rules.logic || 'OR'));
      });

      toast('Loaded strategy into editor', 'info');
    } catch (err) {
      toast(err.message, 'error');
    }
  }

  /* ---------- Save ---------- */
  async function saveStrategy() {
    const name = document.getElementById('sbName').value.trim();
    const desc = document.getElementById('sbDesc').value.trim();

    if (!name) { toast('Name is required', 'error'); return; }

    const entry = { logic: getLogic('entry'), conditions: collectConditions('entry') };
    const exit  = { logic: getLogic('exit'),  conditions: collectConditions('exit')  };

    if (!entry.conditions.length) { toast('Add at least one entry condition', 'error'); return; }
    if (!exit.conditions.length)  { toast('Add at least one exit condition', 'error'); return; }

    const btn = document.getElementById('sbSave');
    btn.disabled = true; btn.textContent = 'Saving…';

    try {
      const body = new URLSearchParams({
        action: 'save',
        id: editingId || '',
        name,
        description: desc,
        entry_rules: JSON.stringify(entry),
        exit_rules: JSON.stringify(exit),
        csrf: CSRF,
      });
      const r = await fetch(API + '/api/strategies.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body,
      });
      const json = await r.json();
      if (!r.ok) throw new Error(json.error || 'Save failed');
      toast(json.message || 'Saved', 'success');
      editingId = 0;
      clearEditor();
      await loadList();
    } catch (err) {
      toast(err.message, 'error');
    } finally {
      btn.disabled = false; btn.textContent = '💾 Save Strategy';
    }
  }

  function clearEditor() {
    editingId = 0;
    document.getElementById('sbName').value = '';
    document.getElementById('sbDesc').value = '';
    document.getElementById('entryConditions').innerHTML = '';
    document.getElementById('exitConditions').innerHTML = '';
    addCondition('entry', { indicator: 'rsi', operator: '<', value: 30 });
    addCondition('exit',  { indicator: 'rsi', operator: '>', value: 70 });
  }

  async function deleteStrategy(id) {
    if (!confirm('Delete this strategy?')) return;
    try {
      const r = await fetch(API + '/api/strategies.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'delete', id, csrf: CSRF }),
      });
      const json = await r.json();
      if (!r.ok) throw new Error(json.error || 'Delete failed');
      toast('Deleted', 'success');
      await loadList();
    } catch (err) {
      toast(err.message, 'error');
    }
  }

  async function copyPreset(key) {
    try {
      const r = await fetch(API + '/api/strategies.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'duplicate', preset_key: key, csrf: CSRF }),
      });
      const json = await r.json();
      if (!r.ok) throw new Error(json.error || 'Copy failed');
      toast('Preset copied to your strategies', 'success');
      await loadList();
    } catch (err) {
      toast(err.message, 'error');
    }
  }

  /* ---------- Wire ---------- */
  document.querySelectorAll('.sb-logic').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.closest('.sb-logic-tabs').dataset.target;
      document.querySelectorAll(`.sb-logic-tabs[data-target="${target}"] .sb-logic`).forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  document.querySelectorAll('[data-add-condition]').forEach(btn => {
    btn.addEventListener('click', () => addCondition(btn.dataset.addCondition));
  });

  document.getElementById('sbSave').addEventListener('click', saveStrategy);
  document.getElementById('sbClear').addEventListener('click', clearEditor);
  document.getElementById('sbNew').addEventListener('click', clearEditor);

  clearEditor();
  loadList();
})();