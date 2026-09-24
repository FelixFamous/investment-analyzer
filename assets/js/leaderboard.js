/**
 * AlphaEdge · Leaderboard
 * Copy/stop-copy buttons, search, period filters.
 */

(function () {
  'use strict';

  const API  = window.LB_API  || '';
  const CSRF = window.LB_CSRF || '';
  const ME   = window.LB_ME   || 0;

  /* ---------- Toast ---------- */
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

  /* ---------- Modal helpers (used for copy options) ---------- */
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
  async function post(action, data) {
    const body = new URLSearchParams({ ...data, action, csrf: CSRF });
    const res = await fetch(API + '/api/copy-trade.php', {
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

  /* ---------- Copy modal ---------- */
  function openCopyModal(leaderId, leaderName, isActive) {
    if (isActive) {
      // Show stop option
      const overlay = modal(`
        <h3>Stop copying @${leaderName}?</h3>
        <div class="sub">
          New trades from this trader will no longer be mirrored to your account.
          Existing positions stay unchanged.
        </div>
        <div class="modal-actions">
          <button class="btn" id="ae-cancel">Keep copying</button>
          <button class="btn btn-danger" id="ae-confirm-stop">Stop copying</button>
        </div>
      `);

      overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
      overlay.querySelector('#ae-confirm-stop').addEventListener('click', async () => {
        const btn = overlay.querySelector('#ae-confirm-stop');
        btn.disabled = true; btn.textContent = 'Stopping…';
        try {
          const r = await post('stop', { leader_id: leaderId });
          toast(r.message || 'Stopped', 'success');
          closeModal();
          setTimeout(() => window.location.reload(), 700);
        } catch (err) {
          toast(err.message, 'error');
          btn.disabled = false; btn.textContent = 'Stop copying';
        }
      });
      return;
    }

    // Show start-copy options
    const overlay = modal(`
      <h3>Copy @${leaderName}</h3>
      <div class="sub">
        Every new trade this trader makes will be mirrored to your account at the same
        proportion you set below.
      </div>

      <div class="form-group">
        <label for="copyRatio">
          Copy ratio (%)
          <span class="text-dim" style="font-weight:400;font-size:11px;">— portion of their trade size to mirror</span>
        </label>
        <input type="number" id="copyRatio" step="1" min="1" max="200" value="100">
        <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
          100% = same position size. 50% = half the size. 200% = double (use caution).
        </small>
      </div>

      <div class="form-group">
        <label for="copyMax">
          Max per trade (USD)
          <span class="text-dim" style="font-weight:400;font-size:11px;">— optional safety cap</span>
        </label>
        <input type="number" id="copyMax" step="10" min="0" placeholder="e.g. 500">
        <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
          Leave blank for no cap. Mirrored trades will never exceed this dollar amount.
        </small>
      </div>

      <div class="modal-actions">
        <button class="btn" id="ae-cancel">Cancel</button>
        <button class="btn btn-primary" id="ae-confirm-copy">Start copying</button>
      </div>
    `);

    const $ratio = overlay.querySelector('#copyRatio');
    const $max   = overlay.querySelector('#copyMax');

    overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
    overlay.querySelector('#ae-confirm-copy').addEventListener('click', async () => {
      const ratio = parseFloat($ratio.value) || 100;
      const max   = $max.value !== '' ? parseFloat($max.value) : null;
      const btn   = overlay.querySelector('#ae-confirm-copy');

      if (ratio < 1 || ratio > 200) {
        toast('Copy ratio must be between 1 and 200', 'error');
        return;
      }
      if (max !== null && max < 0) {
        toast('Max per trade must be positive', 'error');
        return;
      }

      btn.disabled = true; btn.textContent = 'Starting…';
      try {
        const r = await post('start', {
          leader_id: leaderId,
          copy_ratio: ratio,
          max_per_trade: max !== null ? max : '',
        });
        toast(r.message || 'Copying started', 'success');
        closeModal();
        setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false; btn.textContent = 'Start copying';
      }
    });

    $ratio.focus();
    $ratio.select();
  }

  /* ---------- Wire copy buttons (delegated) ---------- */
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.lb-copy-btn');
    if (!btn) return;
    e.preventDefault();
    const leaderId   = parseInt(btn.dataset.leaderId, 10);
    const leaderName = btn.dataset.leaderName || 'trader';
    const isActive   = btn.dataset.copied === '1';
    if (!leaderId) return;
    openCopyModal(leaderId, leaderName, isActive);
  });

  /* ---------- Search filter ---------- */
  const $search = document.getElementById('lbSearch');
  const $body   = document.getElementById('lbBody');
  const $count  = document.getElementById('lbRowCount');

  if ($search && $body) {
    $search.addEventListener('input', () => {
      const q = $search.value.trim().toLowerCase();
      let visible = 0;
      $body.querySelectorAll('tr').forEach(row => {
        const u = row.dataset.username || '';
        if (!q || u.includes(q)) {
          row.style.display = '';
          visible++;
        } else {
          row.style.display = 'none';
        }
      });
      if ($count) $count.textContent = visible + ' trader' + (visible === 1 ? '' : 's');
    });
  }

  /* ---------- Period filters (visual only — full time-series would need a bigger query) ---------- */
  document.querySelectorAll('.lb-filter').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.lb-filter').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const period = btn.dataset.period;
      if (period === 'all') {
        toast('Showing all-time rankings', 'info');
      } else if (period === '30d') {
        toast('Showing last-30-day trends', 'info');
      } else if (period === '7d') {
        toast('Showing this-week trends', 'info');
      }
      // Reload — in a full build this would pass a period param to the server
      // setTimeout(() => window.location.href = API + '/leaderboard.php?period=' + period, 400);
    });
  });
})();