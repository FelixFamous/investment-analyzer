/**
 * AlphaEdge AI Chatbot v3
 *  - Streaming responses (instant feel)
 *  - DeepSeek-style thinking process animation
 *  - Persistent chat history in localStorage
 *  - Multi-provider fallback (Pollinations → KeylessAI)
 */

(function () {
  'use strict';

  const API = (window.ALPHAEDGE && window.ALPHAEDGE.api) || '';
  const STORE_KEY = 'ae_chat_conversations_v1';
  const MAX_CHATS = 30;

  let sending = false;
  let currentChatId = null;
  let conversations = {};   // { id: { id, title, messages: [{role, content, time}], createdAt } }

  /* ---------- DOM refs ---------- */
  const $btn        = document.getElementById('aeChatBtn');
  const $panel      = document.getElementById('aeChatPanel');
  const $close      = document.getElementById('aeChatClose');
  const $msgs       = document.getElementById('aeChatMessages');
  const $form       = document.getElementById('aeChatForm');
  const $input      = document.getElementById('aeChatInput');
  const $send       = document.getElementById('aeChatSend');
  const $title      = document.getElementById('aeChatTitle');
  const $sub        = document.getElementById('aeChatSub');
  const $newBtn     = document.getElementById('aeChatNewBtn');
  const $histBtn    = document.getElementById('aeChatHistoryBtn');
  const $histDrawer = document.getElementById('aeChatHistory');
  const $histClose  = document.getElementById('aeChatHistoryClose');
  const $histList   = document.getElementById('aeChatHistoryList');

  if (!$btn || !$panel) return;

  /* ============================================================
   * PERSISTENCE
   * ============================================================ */
  function loadConversations() {
    try {
      const raw = localStorage.getItem(STORE_KEY);
      conversations = raw ? JSON.parse(raw) : {};
    } catch {
      conversations = {};
    }
  }
  function saveConversations() {
    try {
      // Trim to MAX_CHATS (most recent by createdAt)
      const list = Object.values(conversations)
        .sort((a, b) => b.createdAt - a.createdAt)
        .slice(0, MAX_CHATS);
      const trimmed = {};
      list.forEach(c => trimmed[c.id] = c);
      conversations = trimmed;
      localStorage.setItem(STORE_KEY, JSON.stringify(conversations));
    } catch (e) {
      console.warn('Could not persist chats:', e);
    }
  }
  function newChat() {
    const id = 'c_' + Date.now() + '_' + Math.random().toString(36).slice(2, 6);
    conversations[id] = {
      id,
      title: 'New chat',
      messages: [],
      createdAt: Date.now()
    };
    currentChatId = id;
    saveConversations();
    renderMessages();
    renderHistoryList();
    updateHeader();
    $input.focus();
  }
  function switchChat(id) {
    if (!conversations[id]) return;
    currentChatId = id;
    renderMessages();
    updateHeader();
    closeHistory();
  }
  function deleteChat(id) {
    if (!confirm('Delete this chat?')) return;
    delete conversations[id];
    saveConversations();
    if (currentChatId === id) {
      const remaining = Object.values(conversations);
      if (remaining.length) switchChat(remaining[0].id);
      else newChat();
    }
    renderHistoryList();
  }

  /* ============================================================
   * RENDERING
   * ============================================================ */
  function esc(s) {
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }
  function formatText(s) {
    return esc(s)
      .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
      .replace(/`([^`]+)`/g, '<code>$1</code>')
      .replace(/\n/g, '<br>');
  }

  function renderMessages() {
    const chat = conversations[currentChatId];
    if (!chat) return;

    if (!chat.messages.length) {
      $msgs.innerHTML = `
        <div class="chat-msg chat-msg-bot">
          Hi! I'm your AI trading assistant. Ask me anything:
          <ul style="margin:8px 0 0 18px;padding:0;font-size:12px;">
            <li>"Should I buy NVDA right now?"</li>
            <li>"Analyze Bitcoin's recent price action"</li>
            <li>"What is RSI and how do I use it?"</li>
          </ul>
        </div>`;
      return;
    }

    $msgs.innerHTML = '';
    chat.messages.forEach(m => {
      const div = document.createElement('div');
      div.className = 'chat-msg chat-msg-' + (m.role === 'user' ? 'user' : 'bot');
      div.innerHTML = formatText(m.content);
      $msgs.appendChild(div);
    });
    $msgs.scrollTop = $msgs.scrollHeight;
  }

  function renderHistoryList() {
    const list = Object.values(conversations)
      .sort((a, b) => b.createdAt - a.createdAt);

    if (!list.length) {
      $histList.innerHTML = '<div class="empty-state" style="padding:20px;">No chats yet</div>';
      return;
    }

    $histList.innerHTML = list.map(c => `
      <div class="chat-history-item ${c.id === currentChatId ? 'active' : ''}" data-chat-id="${c.id}">
        <div class="chat-history-title">${esc(c.title)}</div>
        <div class="chat-history-time">${timeAgo(c.createdAt)}</div>
        <button class="chat-history-del" data-del-id="${c.id}" title="Delete">✕</button>
      </div>
    `).join('');
  }

  function updateHeader() {
    const chat = conversations[currentChatId];
    if (!chat) return;
    $title.textContent = chat.title === 'New chat' ? 'AlphaEdge AI' : chat.title;
    $sub.textContent = chat.messages.length + ' message' + (chat.messages.length === 1 ? '' : 's');
  }

  function timeAgo(ts) {
    const diff = Math.floor((Date.now() - ts) / 1000);
    if (diff < 60) return 'just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
  }

  /* ============================================================
   * THINKING PROCESS (DeepSeek-style)
   * ============================================================ */
  const THINKING_STEPS = [
    { icon: '🔍', text: 'Understanding your question' },
    { icon: '📊', text: 'Checking live market data' },
    { icon: '📰', text: 'Scanning latest news' },
    { icon: '📈', text: 'Computing RSI & momentum' },
    { icon: '🧠', text: 'Weighing risk factors' },
    { icon: '✍️', text: 'Composing response' }
  ];

  function renderThinking() {
    const div = document.createElement('div');
    div.className = 'chat-thinking';
    div.innerHTML = `
      <div class="chat-thinking-header">
        <span class="chat-thinking-spinner"></span>
        <span class="chat-thinking-label">Thinking…</span>
      </div>
      <div class="chat-thinking-steps" id="aeThinkingSteps"></div>
    `;
    $msgs.appendChild(div);
    $msgs.scrollTop = $msgs.scrollHeight;

    const $steps = div.querySelector('#aeThinkingSteps');
    let i = 0;
    const interval = setInterval(() => {
      if (i >= THINKING_STEPS.length) return;
      const s = THINKING_STEPS[i];
      const row = document.createElement('div');
      row.className = 'chat-thinking-step';
      row.innerHTML = `<span class="step-icon">${s.icon}</span><span class="step-text">${s.text}</span><span class="step-check">✓</span>`;
      $steps.appendChild(row);
      requestAnimationFrame(() => row.classList.add('done'));
      $msgs.scrollTop = $msgs.scrollHeight;
      i++;
    }, 420);

    return {
      element: div,
      stop: () => clearInterval(interval),
      complete: () => {
        // Mark all steps complete
        $steps.querySelectorAll('.chat-thinking-step').forEach(el => el.classList.add('done'));
      }
    };
  }

  /* ============================================================
   * CONTEXT + AI CALLS
   * ============================================================ */
  function buildContext() {
    const lines = [];
    if (window.LivePrices) {
      const all = window.LivePrices.getAll();
      const syms = Object.keys(all);
      if (syms.length) {
        lines.push('Live market snapshot:');
        syms.slice(0, 20).forEach(s => {
          const p = all[s];
          const sign = p.changePct >= 0 ? '+' : '';
          lines.push(`- ${s} (${p.name}): $${p.price} (${sign}${p.changePct.toFixed(2)}%)`);
        });
      }
    }
    const cash = (window.ALPHAEDGE && window.ALPHAEDGE.cash) || 0;
    lines.push(`\nUser virtual cash balance: $${cash.toFixed(2)}`);
    return lines.join('\n');
  }

  function buildMessages(chat) {
    const systemPrompt = `You are AlphaEdge AI, a professional investment analysis assistant.
You give concise, actionable, educational answers about stocks, crypto, and markets.
You never promise profits. You always mention risk. Keep replies under 150 words.
You do not give financial advice — you analyze and educate.

Current live market context:
${buildContext()}`;

    return [
      { role: 'system', content: systemPrompt },
      ...chat.messages.slice(-8).map(m => ({ role: m.role, content: m.content }))
    ];
  }

  /**
   * Streaming call to Pollinations. Yields tokens via onChunk callback.
   * Falls back to non-streaming KeylessAI if streaming fails.
   */
  async function streamAI(messages, onChunk) {
    // --- Try streaming first ---
    try {
      const res = await fetch('https://text.pollinations.ai/openai', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          model: 'openai',
          messages,
          stream: true
        })
      });
      if (!res.ok) throw new Error('Pollinations HTTP ' + res.status);
      if (!res.body) throw new Error('No stream body');

      const reader = res.body.getReader();
      const decoder = new TextDecoder();
      let buffer = '';
      let fullText = '';

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        buffer += decoder.decode(value, { stream: true });

        // SSE: split by \n\n, each chunk starts with "data: "
        const lines = buffer.split('\n');
        buffer = lines.pop() || '';

        for (const line of lines) {
          const trimmed = line.trim();
          if (!trimmed.startsWith('data:')) continue;
          const payload = trimmed.slice(5).trim();
          if (payload === '[DONE]') continue;
          try {
            const json = JSON.parse(payload);
            const delta = json.choices?.[0]?.delta?.content;
            if (delta) {
              fullText += delta;
              onChunk(delta, fullText);
            }
          } catch {
            // Some providers send raw text chunks
            if (payload && !payload.startsWith('{')) {
              fullText += payload;
              onChunk(payload, fullText);
            }
          }
        }
      }

      if (!fullText) throw new Error('Empty stream');
      return fullText;
    } catch (err) {
      console.warn('Streaming failed, falling back to non-streaming:', err.message);
      // --- Fallback: non-streaming ---
      const reply = await askKeylessAI(messages);
      onChunk(reply, reply);
      return reply;
    }
  }

  async function askKeylessAI(messages) {
    const res = await fetch('https://keylessai.thomasjvu.workers.dev/', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ model: 'gpt-4o-mini', messages })
    });
    if (!res.ok) throw new Error('KeylessAI HTTP ' + res.status);
    const data = await res.json();
    const text = data?.choices?.[0]?.message?.content || data?.response;
    if (!text) throw new Error('KeylessAI empty');
    return text;
  }

  /* ============================================================
   * SEND
   * ============================================================ */
  async function sendMessage(text) {
    if (sending || !text.trim()) return;
    sending = true;
    $send.disabled = true;

    const chat = conversations[currentChatId];
    if (!chat) { sending = false; $send.disabled = false; return; }

    // Add user message
    chat.messages.push({ role: 'user', content: text, time: Date.now() });

    // Set title from first user message
    if (chat.title === 'New chat') {
      chat.title = text.length > 40 ? text.slice(0, 40) + '…' : text;
      updateHeader();
      renderHistoryList();
    }

    renderMessages();
    saveConversations();

    // Show thinking
    const thinking = renderThinking();

    // Prepare bot bubble (empty)
    const botEl = document.createElement('div');
    botEl.className = 'chat-msg chat-msg-bot';
    botEl.style.display = 'none';
    $msgs.appendChild(botEl);
    let botStarted = false;

    try {
      const messages = buildMessages(chat);
      const finalText = await streamAI(messages, (chunk, full) => {
        if (!botStarted) {
          thinking.complete();
          setTimeout(() => { thinking.element.remove(); }, 250);
          botEl.style.display = '';
          botStarted = true;
        }
        botEl.innerHTML = formatText(full);
        $msgs.scrollTop = $msgs.scrollHeight;
      });

      chat.messages.push({ role: 'assistant', content: finalText, time: Date.now() });
      saveConversations();
    } catch (err) {
      thinking.stop();
      thinking.element.remove();
      if (!botStarted) botEl.style.display = '';
      botEl.innerHTML = '⚠️ ' + (err.message || 'Something went wrong. Try again.');
      console.error('Chatbot error:', err);
    } finally {
      sending = false;
      $send.disabled = false;
      $input.focus();
    }
  }

  /* ============================================================
   * EVENTS
   * ============================================================ */
  $btn.addEventListener('click', () => {
    const open = $panel.classList.toggle('open');
    $panel.setAttribute('aria-hidden', open ? 'false' : 'true');
    if (open) {
      renderMessages();
      updateHeader();
      setTimeout(() => $input.focus(), 200);
    } else {
      closeHistory();
    }
  });
  $close.addEventListener('click', () => {
    $panel.classList.remove('open');
    $panel.setAttribute('aria-hidden', 'true');
    closeHistory();
  });

  $form.addEventListener('submit', e => {
    e.preventDefault();
    const text = $input.value.trim();
    if (!text) return;
    $input.value = '';
    sendMessage(text);
  });

  $newBtn.addEventListener('click', () => {
    newChat();
    closeHistory();
  });

  $histBtn.addEventListener('click', () => {
    renderHistoryList();
    $histDrawer.classList.toggle('open');
  });
  $histClose.addEventListener('click', closeHistory);

  function closeHistory() { $histDrawer.classList.remove('open'); }

  // History clicks (switch / delete)
  $histList.addEventListener('click', e => {
    const delBtn = e.target.closest('[data-del-id]');
    if (delBtn) {
      e.stopPropagation();
      deleteChat(delBtn.dataset.delId);
      return;
    }
    const item = e.target.closest('[data-chat-id]');
    if (item) switchChat(item.dataset.chatId);
  });

  /* ============================================================
   * INIT
   * ============================================================ */
  loadConversations();

  // Pick most recent chat or create a new one
  const existing = Object.values(conversations).sort((a, b) => b.createdAt - a.createdAt);
  if (existing.length) {
    currentChatId = existing[0].id;
  } else {
    newChat();
  }
  renderMessages();
  renderHistoryList();
  updateHeader();

  // Expose for debugging
  window.aeChat = {
    sendMessage,
    getConversations: () => conversations,
    newChat
  };
})();