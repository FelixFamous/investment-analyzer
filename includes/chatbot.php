<?php
/**
 * AlphaEdge AI Chatbot widget.
 * Features: streaming responses, thinking-process display, persistent chat history.
 */
?>
<button id="aeChatBtn" class="chat-fab" title="Ask AlphaEdge AI">
  <span class="chat-fab-icon">💬</span>
  <span class="chat-fab-label">Ask AI</span>
</button>

<div id="aeChatPanel" class="chat-panel" aria-hidden="true">
  <div class="chat-header">
    <div style="display:flex;align-items:center;gap:10px;">
      <button id="aeChatHistoryBtn" class="chat-icon-btn" title="Chat history">☰</button>
      <div>
        <div class="chat-title" id="aeChatTitle">AlphaEdge AI</div>
        <div class="chat-sub" id="aeChatSub">Free · no sign-in</div>
      </div>
    </div>
    <div style="display:flex;gap:6px;">
      <button id="aeChatNewBtn" class="chat-icon-btn" title="New chat">＋</button>
      <button id="aeChatClose" class="chat-close" aria-label="Close">✕</button>
    </div>
  </div>

  <!-- History drawer -->
  <div id="aeChatHistory" class="chat-history">
    <div class="chat-history-header">
      <span>Recent Chats</span>
      <button id="aeChatHistoryClose" class="chat-icon-btn" style="font-size:12px;">✕</button>
    </div>
    <div id="aeChatHistoryList" class="chat-history-list">
      <div class="empty-state" style="padding:20px;">No chats yet</div>
    </div>
  </div>

  <div id="aeChatMessages" class="chat-messages">
    <div class="chat-msg chat-msg-bot">
      Hi! I'm your AI trading assistant. Ask me anything:
      <ul style="margin:8px 0 0 18px;padding:0;font-size:12px;">
        <li>"Should I buy NVDA right now?"</li>
        <li>"Analyze Bitcoin's recent price action"</li>
        <li>"What is RSI and how do I use it?"</li>
      </ul>
    </div>
  </div>

  <form id="aeChatForm" class="chat-input-row">
    <input type="text" id="aeChatInput" placeholder="Ask anything…" autocomplete="off">
    <button type="submit" id="aeChatSend" class="btn btn-primary">Send</button>
  </form>
</div>

<script src="<?= e(APP_URL) ?>/assets/js/chatbot.js" defer></script>