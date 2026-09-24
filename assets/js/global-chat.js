/**
 * AlphaEdge · Global user chat
 * Polls api/chat-fetch.php every 5s, posts to api/chat-post.php
 */
(function () {
  'use strict';

  const API  = (window.ALPHAEDGE && window.ALPHAEDGE.api) || '';
  const CSRF = (window.ALPHAEDGE && window.ALPHAEDGE.csrf) || '';

  let lastId = 0;
  let currentUser = '';
  let polling = null;
  let sending = false;

  const $fab    = document.getElementById('gchatFab');
  const $panel  = document.getElementById('gchatPanel');
  const $close  = document.getElementById('gchatClose');
  const $msgs   = document.getElementById('gchatMessages');
  const $form   = document.getElementById('gchatForm');
  const $body   = document.getElementById('gchatBody');
  const $sym    = document.getElementById('gchatSymbol');
  const $send   = document.getElementById('gchatSend');

  if (!$fab || !$panel) return;

  /* ---------- Open / close ---------- */
  $fab.addEventListener('click', () => {
    const open = $panel.classList.toggle('open');
    if (open) {
      $panel.classList.add('open');
      startPolling();
      setTimeout(() => $body.focus(), 200);
    } else {
      stopPolling();
    }
  });
  $close.addEventListener('click', () => {
    $panel.classList.remove('open');
    stopPolling();
  });

  /* ---------- Helpers ---------- */
  function esc(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function timeAgo(iso) {
    const t = new Date(iso).getTime();
    if (isNaN(t)) return '';
    const diff = Math.floor((Date.now() - t) / 1000);
    if (diff < 60)    return diff + 's';
    if (diff < 3600)  return Math.floor(diff / 60) + 'm';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h';
    return Math.floor(diff / 86400) + 'd';
  }

  /* ---------- Rendering ---------- */
  function appendMessages(list) {
    if (!list || !list.length) return;

    // Remove empty state if present
    const empty = $msgs.querySelector('.gchat-empty');
    if (empty) empty.remove();

    list.forEach(m => {
      const el = document.createElement('div');
      el.className = 'gchat-msg' + (m.username === currentUser ? ' mine' : '');
      el.innerHTML = `
        <div class="gchat-meta">
          <span class="gchat-user">${esc(m.username)}</span>
          ${m.symbol ? `<span class="gchat-sym">$${esc(m.symbol)}</span>` : ''}
          <span class="gchat-time">${timeAgo(m.created_at)}</span>
        </div>
        <div class="gchat-body">${esc(m.body)}</div>
      `;
      $msgs.appendChild(el);
      lastId = Math.max(lastId, m.id);
    });

    // Auto-scroll if user was near bottom
    const nearBottom = $msgs.scrollHeight - $msgs.scrollTop - $msgs.clientHeight < 120;
    if (nearBottom) $msgs.scrollTop = $msgs.scrollHeight;
  }

  /* ---------- Polling ---------- */
  async function fetchMessages(initial = false) {
    try {
      const url = API + '/api/chat-fetch.php' + (initial ? '' : '?since=' + lastId);
      const r = await fetch(url, { credentials: 'same-origin' });
      if (!r.ok) return;
      const data = await r.json();
      if (data.current_user) currentUser = data.current_user;
      if (data.messages && data.messages.length) {
        appendMessages(data.messages);
      } else if (initial && !$msgs.children.length) {
        $msgs.innerHTML = '<div class="gchat-empty">No messages yet — say hello 👋</div>';
      }
    } catch (err) {
      console.warn('Chat fetch failed:', err.message);
    }
  }

  function startPolling() {
    if (polling) return;
    fetchMessages(true);
    polling = setInterval(() => fetchMessages(false), 5000);
  }
  function stopPolling() {
    if (polling) { clearInterval(polling); polling = null; }
  }

  /* ---------- Send ---------- */
  $form.addEventListener('submit', async e => {
    e.preventDefault();
    if (sending) return;

    const body = $body.value.trim();
    if (!body) return;

    sending = true;
    $send.disabled = true;

    const payload = new URLSearchParams({
      body,
      symbol: $sym.value.trim().toUpperCase(),
      csrf: CSRF,
    });

    try {
      const r = await fetch(API + '/api/chat-post.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: payload,
      });
      const json = await r.json();
      if (!r.ok) throw new Error(json.error || 'Send failed');

      $body.value = '';
      $sym.value  = '';
      await fetchMessages(false);   // pull the new message instantly
    } catch (err) {
      console.warn('Chat send failed:', err.message);
    } finally {
      sending = false;
      $send.disabled = false;
      $body.focus();
    }
  });

  // Preload messages quietly (even before panel opens)
  fetchMessages(true);
})();