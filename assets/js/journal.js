(function () {
  'use strict';

  const API  = window.JOURNAL_API  || '';
  const CSRF = window.JOURNAL_CSRF || '';

  const $list = document.getElementById('journalList');
  if (!$list) return;

  let allEntries = [];

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

  function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
  function fmtPrice(n) {
    n = Number(n);
    if (n >= 1000) return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (n >= 1) return '$' + n.toFixed(2);
    if (n >= 0.01) return '$' + n.toFixed(4);
    return '$' + n.toFixed(7);
  }
  function fmtUsd(n) {
    n = Number(n);
    const neg = n < 0;
    return (neg ? '-$' : '$') + Math.abs(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function fmtDate(iso) {
    const d = new Date(iso.replace(' ', 'T') + 'Z');
    return d.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false });
  }

  async function load() {
    $list.innerHTML = '<div class="empty-state" style="padding:40px;">Loading trades…</div>';

    const params = new URLSearchParams({ action: 'list' });
    const f = {
      symbol: document.getElementById('jfSymbol').value,
      side: document.getElementById('jfSide').value,
      tag: document.getElementById('jfTag').value,
      from: document.getElementById('jfFrom').value,
      to: document.getElementById('jfTo').value,
      only_unreviewed: document.getElementById('jfOnlyUnreviewed').checked ? '1' : '',
    };
    Object.entries(f).forEach(([k, v]) => { if (v) params.set(k, v); });

    try {
      const r = await fetch(API + '/api/journal.php?' + params.toString(), { credentials: 'same-origin' });
      const json = await r.json();
      allEntries = json.entries || [];
      render();
      updateStats();
    } catch (err) {
      $list.innerHTML = '<div class="empty-state" style="color:var(--red);padding:40px;">Failed to load.</div>';
    }
  }

  function render() {
    if (!allEntries.length) {
      $list.innerHTML = '<div class="empty-state" style="padding:40px;">No trades match your filters.</div>';
      return;
    }

    $list.innerHTML = allEntries.map(e => {
      const reviewed = e.journal_id !== null;
      const pnlClass = e.pnl === null ? '' : (e.pnl >= 0 ? 'pos' : 'neg');
      const tags = e.tags ? e.tags.split(',').map(t => t.trim()).filter(Boolean) : [];
      const tagsHtml = tags.map(t => `<span class="journal-tag">${esc(t)}</span>`).join('');
      const emotionHtml = e.emotion ? `<span class="journal-tag emotion">😊 ${esc(e.emotion)}</span>` : '';
      const setupHtml = e.setup_type ? `<span class="journal-tag setup">${esc(e.setup_type)}</span>` : '';

      return `
        <div class="journal-entry ${reviewed ? 'reviewed' : 'unreviewed'}" data-trade-id="${e.id}">
          <div>
            <div class="journal-entry-time">${fmtDate(e.created_at)}</div>
            <div class="journal-entry-meta" style="margin-top:4px;">
              <span>${e.quantity.toFixed(4)} @ ${fmtPrice(e.price)}</span>
            </div>
          </div>
          <div class="journal-entry-main">
            <div class="journal-entry-title">
              <span class="sym">${esc(e.symbol)}</span>
              <span class="side ${e.side === 'BUY' ? 'buy' : 'sell'}">${e.side}</span>
              ${reviewed ? '' : '<span style="color:var(--accent);font-size:11px;font-weight:600;">• Unreviewed</span>'}
            </div>
            ${(tags.length || e.emotion || e.setup_type) ? `<div class="journal-entry-tags">${setupHtml}${tagsHtml}${emotionHtml}</div>` : ''}
            ${e.notes ? `<div style="font-size:12px;color:var(--text-dim);font-style:italic;margin-top:6px;line-height:1.5;">"${esc(e.notes)}"</div>` : ''}
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
            <div class="journal-entry-pnl ${pnlClass}">
              ${e.pnl === null ? '—' : (e.pnl >= 0 ? '+' : '-') + fmtUsd(Math.abs(e.pnl))}
            </div>
            <button class="journal-edit-btn" data-edit="${e.id}">
              ${reviewed ? '✎ Edit' : '＋ Add note'}
            </button>
          </div>
        </div>
      `;
    }).join('');
  }

  function updateStats() {
    const total = allEntries.length;
    const reviewed = allEntries.filter(e => e.journal_id !== null).length;
    const unreviewed = total - reviewed;
    const rate = total > 0 ? (reviewed / total) * 100 : 0;

    document.getElementById('jStatTotal').textContent = total;
    document.getElementById('jStatReviewed').textContent = reviewed;
    document.getElementById('jStatUnreviewed').textContent = unreviewed;
    document.getElementById('jStatRate').textContent = rate.toFixed(0) + '%';
    document.getElementById('jCount').textContent = total;
  }

  /* ---------- Edit modal ---------- */
  function openEditor(tradeId) {
    const e = allEntries.find(x => x.id === tradeId);
    if (!e) return;

    let $overlay = document.getElementById('journalModal');
    $overlay.style.display = 'flex';
    $overlay.innerHTML = `
      <div class="modal">
        <h3>Journal · ${esc(e.symbol)} ${e.side}</h3>
        <div class="sub">
          ${e.quantity.toFixed(4)} @ ${fmtPrice(e.price)} · ${fmtDate(e.created_at)}
          ${e.pnl !== null ? ' · P&L: ' + (e.pnl >= 0 ? '+' : '-') + fmtUsd(Math.abs(e.pnl)) : ''}
        </div>

        <div class="form-group">
          <label>Tags (comma separated)</label>
          <input type="text" id="jeTags" value="${esc(e.tags || '')}" placeholder="breakout, support-bounce, scalp">
        </div>

        <div class="form-group">
          <label>Setup type</label>
          <select id="jeSetup">
            <option value="">— None —</option>
            <option ${e.setup_type === 'Breakout' ? 'selected' : ''}>Breakout</option>
            <option ${e.setup_type === 'Trend Continuation' ? 'selected' : ''}>Trend Continuation</option>
            <option ${e.setup_type === 'Reversal' ? 'selected' : ''}>Reversal</option>
            <option ${e.setup_type === 'Range' ? 'selected' : ''}>Range</option>
            <option ${e.setup_type === 'Support/Resistance' ? 'selected' : ''}>Support/Resistance</option>
            <option ${e.setup_type === 'Liquidity Sweep' ? 'selected' : ''}>Liquidity Sweep</option>
            <option ${e.setup_type === 'News Play' ? 'selected' : ''}>News Play</option>
            <option ${e.setup_type === 'Other' ? 'selected' : ''}>Other</option>
          </select>
        </div>

        <div class="form-group">
          <label>Emotion</label>
          <select id="jeEmotion">
            <option value="">— None —</option>
            <option ${e.emotion === 'Confident' ? 'selected' : ''}>Confident</option>
            <option ${e.emotion === 'Calm' ? 'selected' : ''}>Calm</option>
            <option ${e.emotion === 'FOMO' ? 'selected' : ''}>FOMO</option>
            <option ${e.emotion === 'Anxious' ? 'selected' : ''}>Anxious</option>
            <option ${e.emotion === 'Greedy' ? 'selected' : ''}>Greedy</option>
            <option ${e.emotion === 'Fearful' ? 'selected' : ''}>Fearful</option>
            <option ${e.emotion === 'Revenge' ? 'selected' : ''}>Revenge</option>
            <option ${e.emotion === 'Bored' ? 'selected' : ''}>Bored</option>
          </select>
        </div>

        <div class="form-group">
          <label>Notes</label>
          <textarea id="jeNotes" rows="4" style="width:100%;background:var(--bg-2);border:1px solid var(--border-2);border-radius:10px;padding:10px 12px;color:var(--text);font-family:inherit;font-size:13px;outline:none;resize:vertical;">${esc(e.notes || '')}</textarea>
        </div>

        <div class="modal-actions">
          <button class="btn" id="jeCancel">Cancel</button>
          <button class="btn btn-primary" id="jeSave">Save Journal Entry</button>
        </div>
      </div>
    `;

    $overlay.querySelector('#jeCancel').addEventListener('click', closeEditor);
    $overlay.querySelector('#jeSave').addEventListener('click', async () => {
      const btn = $overlay.querySelector('#jeSave');
      btn.disabled = true; btn.textContent = 'Saving…';

      try {
        const r = await fetch(API + '/api/journal.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
            action: 'save',
            trade_id: tradeId,
            tags: $overlay.querySelector('#jeTags').value,
            setup_type: $overlay.querySelector('#jeSetup').value,
            emotion: $overlay.querySelector('#jeEmotion').value,
            notes: $overlay.querySelector('#jeNotes').value,
            csrf: CSRF,
          }),
        });
        const json = await r.json();
        if (!r.ok) throw new Error(json.error || 'Save failed');
        toast('Journal saved', 'success');
        closeEditor();
        await load();
      } catch (err) {
        toast(err.message, 'error');
        btn.disabled = false; btn.textContent = 'Save Journal Entry';
      }
    });
  }

  function closeEditor() {
    const $overlay = document.getElementById('journalModal');
    if ($overlay) {
      $overlay.style.display = 'none';
      $overlay.innerHTML = '';
    }
  }

  /* ---------- Wire ---------- */
  $list.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-edit]');
    if (btn) {
      e.stopPropagation();
      openEditor(parseInt(btn.dataset.edit, 10));
      return;
    }
    const row = e.target.closest('[data-trade-id]');
    if (row) openEditor(parseInt(row.dataset.tradeId, 10));
  });

  document.getElementById('jfApply').addEventListener('click', load);
  document.getElementById('jfClear').addEventListener('click', () => {
    document.getElementById('jfSymbol').value = '';
    document.getElementById('jfSide').value = '';
    document.getElementById('jfTag').value = '';
    document.getElementById('jfFrom').value = '';
    document.getElementById('jfTo').value = '';
    document.getElementById('jfOnlyUnreviewed').checked = false;
    load();
  });

  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeEditor(); });

  load();
})();