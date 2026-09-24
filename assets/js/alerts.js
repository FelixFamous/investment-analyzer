/**
 * AlphaEdge · Price Alerts
 * Create / list / cancel alerts. Polls for triggers every 30s.
 */

(function () {
  'use strict';

  const API  = window.ALERTS_API  || '';
  const CSRF = window.ALERTS_CSRF || '';
  const PRE  = window.ALERTS_PRE_SYMBOL || '';

  const $form       = document.getElementById('createAlertForm');
  const $symbol     = document.getElementById('alertSymbol');
  const $target     = document.getElementById('alertTarget');
  const $current    = document.getElementById('alertCurrent');
  const $list       = document.getElementById('alertList');
  const $listCount  = document.getElementById('alertListCount');
  const $statActive = document.getElementById('statActive');
  const $statTrig   = document.getElementById('statTriggered');
  const $statCanc   = document.getElementById('statCancelled');

  if (!$list) return;

  let currentFilter = 'active';
  let allAlerts = [];

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
    el._t = setTimeout(() => el.className = 'toast', 3000);
  }

  /* ---------- Formatting ---------- */
  function esc(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }
  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1)    return '$' + n.toFixed(2);
    if (n >= 0.01) return '$' + n.toFixed(4);
    return '$' + n.toFixed(7);
  }
  function timeAgo(iso) {
    const t = new Date(iso).getTime();
    if (isNaN(t)) return '';
    const diff = Math.floor((Date.now() - t) / 1000);
    if (diff < 60) return diff + 's ago';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
  }

  /* ---------- POST helper ---------- */
  async function post(action, data) {
    const body = new URLSearchParams({ ...data, action, csrf: CSRF });
    const res = await fetch(API + '/api/alerts.php', {
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

  /* ---------- Fetch alerts ---------- */
  async function loadAlerts() {
    try {
      const res = await fetch(API + '/api/alerts.php?action=list', { credentials: 'same-origin' });
      const json = await res.json();
      allAlerts = json.alerts || [];
      renderStats();
      renderList();
    } catch (err) {
      console.warn('Failed to load alerts:', err);
      $list.innerHTML = '<div class="alerts-empty">Could not load alerts.</div>';
    }
  }

  function renderStats() {
    const counts = { active: 0, triggered: 0, cancelled: 0 };
    allAlerts.forEach(a => { if (counts[a.status] !== undefined) counts[a.status]++; });
    if ($statActive) $statActive.textContent = counts.active;
    if ($statTrig)   $statTrig.textContent   = counts.triggered;
    if ($statCanc)   $statCanc.textContent   = counts.cancelled;

    // Update topbar badge if it exists
    const badge = document.getElementById('alertBadge');
    if (badge) {
      if (counts.active > 0) {
        badge.textContent = counts.active;
        badge.style.display = '';
      } else {
        badge.style.display = 'none';
      }
    }
  }

  function renderList() {
    const filtered = allAlerts.filter(a => a.status === currentFilter);
    if ($listCount) $listCount.textContent = filtered.length;

    if (!filtered.length) {
      const msgs = {
        active:    { icon: '🔔', text: 'No active alerts. Create one on the left.' },
        triggered: { icon: '✅', text: 'No triggered alerts yet.' },
        cancelled: { icon: '🚫', text: 'No cancelled alerts.' }
      };
      const m = msgs[currentFilter] || msgs.active;
      $list.innerHTML = `
        <div class="alerts-empty">
          <div class="alerts-empty-icon">${m.icon}</div>
          ${m.text}
        </div>`;
      return;
    }

    $list.innerHTML = filtered.map(a => {
      const isAbove = a.condition_type === 'above';
      const icon = isAbove ? '▲' : '▼';
      const iconCls = isAbove ? 'above' : 'below';
      const condText = isAbove ? 'rises to' : 'drops to';

      // Distance info for active alerts
      let distancePill = '';
      if (a.status === 'active' && a.current_price && a.distance_pct !== null) {
        const d = a.distance_pct;
        const cls = Math.abs(d) < 2 ? 'near' : 'far';
        distancePill = `<span class="distance-pill ${cls}">${d >= 0 ? '+' : ''}${d.toFixed(2)}% away</span>`;
      }

      // Meta line
      const meta = [];
      meta.push(`<span>📍 Created ${timeAgo(a.created_at)}</span>`);
      if (a.status === 'active' && a.current_price) {
        meta.push(`<span>Current: ${fmtPrice(a.current_price)}</span>`);
      }
      if (a.status === 'triggered') {
        meta.push(`<span>✅ Fired ${timeAgo(a.triggered_at)}</span>`);
        meta.push(`<span>@ ${fmtPrice(a.trigger_price)}</span>`);
      }

      // Actions
      let actions = '';
      if (a.status === 'active') {
        actions = `
          <button class="alert-action-btn" data-action="cancel" data-id="${a.id}" title="Cancel">✕</button>
        `;
      } else {
        actions = `
          <button class="alert-action-btn danger" data-action="delete" data-id="${a.id}" title="Delete">🗑</button>
        `;
      }

      return `
        <div class="alert-item ${a.status}">
          <div class="alert-icon ${iconCls}">${icon}</div>
          <div class="alert-body">
            <div class="alert-headline">
              <span class="sym">${esc(a.symbol)}</span>
              <span class="alert-condition">${condText} ${fmtPrice(a.target_price)}</span>
              ${distancePill}
            </div>
            <div class="alert-meta">${meta.join('')}</div>
            ${a.note ? `<div class="alert-note">"${esc(a.note)}"</div>` : ''}
          </div>
          <div class="alert-actions">${actions}</div>
        </div>
      `;
    }).join('');
  }

  /* ---------- Show current price under target input ---------- */
  async function updateCurrentPrice() {
    const sym = $symbol.value;
    if (!sym || !$current) return;
    try {
      const res = await fetch(API + '/api/alerts.php?action=list', { credentials: 'same-origin' });
      // We already have live prices via the shared LivePrices widget — use it if available
      if (window.LivePrices) {
        const p = window.LivePrices.get(sym);
        if (p) {
          $current.innerHTML = `Current: <strong style="color:var(--text);font-family:var(--mono);">${fmtPrice(p.price)}</strong>`;
          if (!$target.value) $target.placeholder = (p.price * 1.05).toFixed(2);
          return;
        }
      }
      $current.textContent = 'Current: —';
    } catch {}
  }

  /* ---------- Wire form ---------- */
  if ($form) {
    $form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('createAlertBtn');
      btn.disabled = true;
      btn.textContent = 'Creating…';

      try {
        const r = await post('create', {
          symbol: $symbol.value,
          condition_type: document.querySelector('input[name="condition_type"]:checked').value,
          target_price: $target.value,
          note: document.getElementById('alertNote').value,
        });
        toast(r.message || 'Alert created', 'success');
        $target.value = '';
        document.getElementById('alertNote').value = '';
        await loadAlerts();
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        btn.disabled = false;
        btn.textContent = 'Create Alert';
      }
    });

    $symbol.addEventListener('change', updateCurrentPrice);
    updateCurrentPrice();

    // Update current price every 10 seconds while user is on the page
    setInterval(updateCurrentPrice, 10000);
  }

  /* ---------- Wire list clicks ---------- */
  $list.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;

    const id = btn.dataset.id;
    const action = btn.dataset.action;

    if (action === 'cancel') {
      if (!confirm('Cancel this alert?')) return;
      btn.disabled = true;
      try {
        await post('cancel', { alert_id: id });
        toast('Alert cancelled', 'success');
        await loadAlerts();
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false;
      }
    } else if (action === 'delete') {
      if (!confirm('Delete this alert permanently?')) return;
      btn.disabled = true;
      try {
        await post('delete', { alert_id: id });
        toast('Alert deleted', 'success');
        await loadAlerts();
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false;
      }
    }
  });

  /* ---------- Wire tabs ---------- */
  document.querySelectorAll('#alertTabs [data-status]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#alertTabs [data-status]').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentFilter = btn.dataset.status;
      renderList();
    });
  });

  /* ---------- Background checker (every 30s) ---------- */
  async function checkAlerts() {
    try {
      const res = await fetch(API + '/api/alerts.php?action=check', { credentials: 'same-origin' });
      if (!res.ok) return;
      const json = await res.json();
      const fired = json.fired || [];

      if (fired.length) {
        fired.forEach(a => {
          toast('🔔 ' + a.message, 'success');
          // Browser notification if permission granted
          if ('Notification' in window && Notification.permission === 'granted') {
            try {
              new Notification('AlphaEdge Alert', { body: a.message });
            } catch {}
          }
        });
        await loadAlerts();
      } else {
        // Still refresh stats occasionally to keep everything current
        // (skip to avoid flicker)
      }
    } catch (err) {
      console.warn('Alert check failed:', err);
    }
  }

  /* ---------- Init ---------- */
  document.addEventListener('DOMContentLoaded', () => {
    loadAlerts();

    // Ask for browser notification permission once
    if ('Notification' in window && Notification.permission === 'default') {
      // Only ask if user has at least one active alert
      setTimeout(() => {
        if (allAlerts.some(a => a.status === 'active')) {
          Notification.requestPermission();
        }
      }, 5000);
    }

    // First check after 10s, then every 30s
    setTimeout(checkAlerts, 10000);
    setInterval(checkAlerts, 30000);
  });

})();