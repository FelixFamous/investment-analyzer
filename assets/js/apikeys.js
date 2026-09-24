(function () {
  'use strict';

  const API = window.AK_API || '';
  const CSRF = window.AK_CSRF || '';
  const $list = document.getElementById('akList');
  if (!$list) return;

  document.getElementById('akEndpoint').textContent = API + '/api';

  function toast(msg, kind) {
    let el = document.getElementById('ae-toast');
    if (!el) { el = document.createElement('div'); el.id = 'ae-toast'; el.className = 'toast'; document.body.appendChild(el); }
    el.textContent = msg;
    el.className = 'toast show' + (kind ? ' toast-' + kind : '');
    clearTimeout(el._t);
    el._t = setTimeout(() => { el.className = 'toast'; }, 3500);
  }

  function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  async function load() {
    try {
      const r = await fetch(API + '/api/apikeys.php?action=list', { credentials: 'same-origin' });
      const json = await r.json();
      const keys = json.keys || [];

      const active = keys.filter(k => !k.revoked_at).length;
      document.getElementById('akActive').textContent = active;
      document.getElementById('akRevoked').textContent = keys.length - active;
      document.getElementById('akCount').textContent = keys.length;

      if (!keys.length) {
        $list.innerHTML = '<div class="empty-state">No API keys yet.</div>';
        return;
      }

      $list.innerHTML = keys.map(k => {
        const revoked = !!k.revoked_at;
        return `
          <div class="dca-item ${revoked ? 'stopped' : ''}" style="margin-bottom:8px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
              <div style="min-width:0;flex:1;">
                <div style="font-weight:700;font-size:13.5px;color:var(--text);margin-bottom:4px;">${esc(k.label)}</div>
                <div style="font-family:var(--mono);font-size:11.5px;color:var(--text-faint);word-break:break-all;">${esc(k.key_prefix)}_••••••••••••</div>
                <div style="margin-top:8px;display:flex;gap:6px;flex-wrap:wrap;">
                  <span class="rm-holding-status ${k.permissions === 'trade' ? 'risk' : 'safe'}">${esc(k.permissions)}</span>
                  ${revoked ? '<span class="rm-holding-status risk">revoked</span>' : '<span class="rm-holding-status safe">active</span>'}
                </div>
              </div>
              ${!revoked ? `<button class="btn btn-sm btn-danger" data-revoke="${k.id}">Revoke</button>` : ''}
            </div>
          </div>
        `;
      }).join('');

      $list.querySelectorAll('[data-revoke]').forEach(b => {
        b.addEventListener('click', async () => {
          if (!confirm('Revoke this API key? Any apps using it will lose access.')) return;
          b.disabled = true;
          try {
            const r = await fetch(API + '/api/apikeys.php', {
              method: 'POST',
              credentials: 'same-origin',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams({ action: 'revoke', id: b.dataset.revoke, csrf: CSRF }),
            });
            const json = await r.json();
            if (!r.ok) throw new Error(json.error);
            toast('Key revoked', 'success');
            load();
          } catch (err) { toast(err.message, 'error'); b.disabled = false; }
        });
      });
    } catch (err) {
      $list.innerHTML = '<div class="empty-state" style="color:var(--red);">Failed to load keys.</div>';
    }
  }

  document.getElementById('akForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('akCreateBtn');
    const fb = document.getElementById('akFeedback');
    fb.style.display = 'none';
    btn.disabled = true;
    btn.textContent = 'Generating…';

    try {
      const r = await fetch(API + '/api/apikeys.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'create',
          label: document.getElementById('akLabel').value,
          permissions: document.getElementById('akPerms').value,
          csrf: CSRF,
        }),
      });
      const json = await r.json();
      if (!r.ok) throw new Error(json.error);

      // Show the key once
      fb.innerHTML = `
        <div style="margin-bottom:8px;">✅ API key created — copy it now:</div>
        <div style="display:flex;gap:8px;">
          <input type="text" id="newKeyField" value="${esc(json.key)}" readonly style="flex:1;background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:9px 11px;color:var(--accent);font-family:var(--mono);font-size:11.5px;">
          <button type="button" class="btn btn-sm" id="copyNewKey">Copy</button>
        </div>
        <div style="margin-top:8px;font-size:11px;color:var(--text-faint);">⚠️ You won't see this key again. Store it safely.</div>
      `;
      fb.className = 'order-feedback success';
      fb.style.display = 'block';

      document.getElementById('newKeyField').select();
      document.getElementById('copyNewKey').addEventListener('click', () => {
        navigator.clipboard.writeText(json.key);
        toast('Key copied', 'success');
      });

      document.getElementById('akLabel').value = '';
      load();
    } catch (err) {
      fb.textContent = err.message;
      fb.className = 'order-feedback error';
      fb.style.display = 'block';
    } finally {
      btn.disabled = false;
      btn.textContent = '🔑 Generate API Key';
    }
  });

  load();
})();