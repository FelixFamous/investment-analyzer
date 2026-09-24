(function () {
  'use strict';

  const API  = window.DCA_API  || '';
  const CSRF = window.DCA_CSRF || '';
  const $list = document.getElementById('dcaList');
  if (!$list) return;

  const $form = document.getElementById('dcaForm');
  const $fb   = document.getElementById('dcaFeedback');
  const $freq = document.getElementById('dcaFreq');
  const $dowWrap = document.getElementById('dowWrap');
  const $domWrap = document.getElementById('domWrap');

  function toast(msg, kind) {
    if (!$fb) return;
    $fb.textContent = msg;
    $fb.className = 'order-feedback ' + (kind || 'info');
    $fb.style.display = 'block';
    clearTimeout($fb._t);
    $fb._t = setTimeout(() => { $fb.style.display = 'none'; }, 4500);
  }

  function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
  function fmtUsd(n) { return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

  function timeUntil(iso) {
    if (!iso) return '—';
    const t = new Date(iso.replace(' ', 'T') + 'Z').getTime();
    const diff = Math.max(0, Math.floor((t - Date.now()) / 1000));
    if (diff < 60) return 'in ' + diff + 's';
    if (diff < 3600) return 'in ' + Math.floor(diff / 60) + 'm';
    if (diff < 86400) return 'in ' + Math.floor(diff / 3600) + 'h';
    return 'in ' + Math.floor(diff / 86400) + 'd';
  }

  function freqLabel(r) {
    if (r.frequency === 'daily') return 'Daily at ' + String(r.hour_utc).padStart(2, '0') + ':00 UTC';
    if (r.frequency === 'weekly') {
      const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
      return 'Weekly · ' + days[r.day_of_week] + ' at ' + String(r.hour_utc).padStart(2, '0') + ':00 UTC';
    }
    return 'Monthly · day ' + r.day_of_month + ' at ' + String(r.hour_utc).padStart(2, '0') + ':00 UTC';
  }

  function monthlyEstimate(r) {
    const a = parseFloat(r.amount_usd);
    if (r.frequency === 'daily') return a * 30;
    if (r.frequency === 'weekly') return a * 4.33;
    return a;
  }

  async function post(action, data) {
    const body = new URLSearchParams({ ...data, action, csrf: CSRF });
    const res = await fetch(API + '/api/recurring.php', {
      method: 'POST', credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
    });
    let json; try { json = await res.json(); } catch { json = { error: 'Bad response' }; }
    if (!res.ok) throw new Error(json.error || 'HTTP ' + res.status);
    return json;
  }

  async function load() {
    try {
      const r = await fetch(API + '/api/recurring.php?action=list', { credentials: 'same-origin' });
      const json = await r.json();
      const items = json.items || [];

      const active = items.filter(i => i.status === 'active');
      document.getElementById('dcaActive').textContent = active.length;
      document.getElementById('dcaCount').textContent = items.length;

      let monthly = 0, invested = 0, nextDate = null, nextSym = null;
      active.forEach(i => {
        monthly += monthlyEstimate(i);
        invested += parseFloat(i.total_invested);
        const t = new Date(i.next_run_at.replace(' ', 'T') + 'Z').getTime();
        if (!nextDate || t < nextDate) { nextDate = t; nextSym = i.symbol; }
      });

      document.getElementById('dcaMonthly').textContent = fmtUsd(monthly);
      document.getElementById('dcaInvested').textContent = fmtUsd(invested);
      if (nextDate) {
        document.getElementById('dcaNext').textContent = nextSym;
        document.getElementById('dcaNextWhen').textContent = timeUntil(new Date(nextDate).toISOString().slice(0, 19).replace('T', ' '));
      } else {
        document.getElementById('dcaNext').textContent = '—';
        document.getElementById('dcaNextWhen').textContent = '—';
      }

      if (!items.length) {
        $list.innerHTML = '<div class="empty-state" style="padding:32px 12px;">No recurring buys yet. Create one on the left.</div>';
        return;
      }

      $list.innerHTML = items.map(r => `
        <div class="dca-item ${r.status}">
          <div class="dca-item-head">
            <div class="dca-item-sym">${esc(r.symbol)}</div>
            <div class="dca-item-amount">${fmtUsd(r.amount_usd)}</div>
            <span class="dca-status ${r.status}">${r.status}</span>
          </div>
          <div class="dca-item-freq">${freqLabel(r)}</div>
          <div class="dca-item-meta">
            <span>Runs: <strong>${r.times_run}</strong></span>
            <span>Invested: <strong>${fmtUsd(r.total_invested)}</strong></span>
            ${r.status === 'active' && r.next_run_at ? `<span>Next: <strong>${timeUntil(r.next_run_at)}</strong></span>` : ''}
          </div>
          <div class="dca-item-actions">
            ${r.status === 'active'
              ? `<button class="btn btn-sm" data-action="pause" data-id="${r.id}">Pause</button>`
              : (r.status === 'paused'
                ? `<button class="btn btn-sm btn-primary" data-action="resume" data-id="${r.id}">Resume</button>`
                : '')}
            <button class="btn btn-sm btn-danger" data-action="delete" data-id="${r.id}">Delete</button>
          </div>
        </div>
      `).join('');
    } catch (err) {
      $list.innerHTML = '<div class="empty-state" style="color:var(--red);">Failed to load.</div>';
    }
  }

  if ($freq) {
    $freq.addEventListener('change', () => {
      const f = $freq.value;
      $dowWrap.style.display = f === 'weekly' ? '' : 'none';
      $domWrap.style.display = f === 'monthly' ? '' : 'none';
    });
  }

  if ($form) {
    $form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('dcaSubmit');
      btn.disabled = true; btn.textContent = 'Scheduling…';

      try {
        const f = $freq.value;
        const data = {
          symbol: document.getElementById('dcaSymbol').value,
          amount_usd: document.getElementById('dcaAmount').value,
          frequency: f,
          hour_utc: document.getElementById('dcaHour').value,
        };
        if (f === 'weekly')  data.day_of_week = document.getElementById('dcaDow').value;
        if (f === 'monthly') data.day_of_month = document.getElementById('dcaDom').value;

        const r = await post('create', data);
        toast(r.message || 'Scheduled', 'success');
        await load();
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        btn.disabled = false; btn.textContent = 'Schedule Recurring Buy';
      }
    });
  }

  $list.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const { action, id } = btn.dataset;
    btn.disabled = true;
    try {
      const r = await post(action, { id });
      toast(r.message || 'Done', 'success');
      await load();
    } catch (err) {
      toast(err.message, 'error');
      btn.disabled = false;
    }
  });

  load();
})();