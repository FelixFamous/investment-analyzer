(function () {
  'use strict';

  const LINK = window.REFERRAL_LINK || '';
  const CODE = window.REFERRAL_CODE || '';

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
    el._t = setTimeout(() => { el.className = 'toast'; }, 2500);
  }

  async function copy(text) {
    try {
      await navigator.clipboard.writeText(text);
      return true;
    } catch {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.left = '-9999px';
      document.body.appendChild(ta);
      ta.select();
      let ok = false;
      try { ok = document.execCommand('copy'); } catch {}
      ta.remove();
      return ok;
    }
  }

  const $copyCode = document.getElementById('copyCodeBtn');
  const $copyLink = document.getElementById('copyLinkBtn');

  if ($copyCode) {
    $copyCode.addEventListener('click', async () => {
      (await copy(CODE)) ? toast('Code copied', 'success') : toast('Copy failed', 'error');
    });
  }
  if ($copyLink) {
    $copyLink.addEventListener('click', async () => {
      (await copy(LINK)) ? toast('Link copied', 'success') : toast('Copy failed', 'error');
    });
  }

  // Render QR
  const $qr = document.getElementById('qrBox');
  if ($qr && window.QRCode && LINK) {
    new QRCode($qr, {
      text: LINK,
      width: 160,
      height: 160,
      correctLevel: QRCode.CorrectLevel.M,
    });
  }
})();