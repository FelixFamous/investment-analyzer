/**
 * AlphaEdge · 2FA setup page
 */

(function () {
  'use strict';

  const API  = window.SEC_API  || '';
  const CSRF = window.SEC_CSRF || '';

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

  /* ---------- HTTP ---------- */
  async function post(endpoint, data) {
    const body = new URLSearchParams({ ...data, csrf: CSRF });
    const res = await fetch(API + endpoint, {
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

  /* ---------- Modal ---------- */
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

  /* ============================================================
   *  SETUP FLOW (when 2FA is off)
   * ============================================================ */
  const $startBtn    = document.getElementById('startSetupBtn');
  const $setupIntro  = document.getElementById('setupIntro');
  const $setupWizard = document.getElementById('setupWizard');
  const $qrTarget    = document.getElementById('qrTarget');
  const $secretText  = document.getElementById('secretText');
  const $verifyForm  = document.getElementById('verifyForm');
  const $verifyCode  = document.getElementById('verifyCode');
  const $verifyFb    = document.getElementById('verifyFeedback');

  let pendingSecret = '';

  if ($startBtn) {
    $startBtn.addEventListener('click', async () => {
      $startBtn.disabled = true;
      $startBtn.textContent = 'Generating…';

      try {
        const r = await post('/api/2fa-setup.php', {});
        pendingSecret = r.secret;

        // Hide intro, show wizard
        $setupIntro.style.display = 'none';
        $setupWizard.style.display = 'block';

        // Render QR
        if (window.QRCode) {
          $qrTarget.innerHTML = '';
          new QRCode($qrTarget, {
            text: r.uri,
            width: 200,
            height: 200,
            correctLevel: QRCode.CorrectLevel.M,
          });
        } else {
          $qrTarget.innerHTML = '<div style="padding:20px;color:#000;">QR library failed to load</div>';
        }

        // Show secret
        $secretText.textContent = r.secret;

        // Focus the code input
        setTimeout(() => $verifyCode.focus(), 200);

      } catch (err) {
        toast(err.message, 'error');
        $startBtn.disabled = false;
        $startBtn.textContent = 'Set up two-factor';
      }
    });
  }

  /* ---------- Copy secret ---------- */
  const $copySecretBtn = document.getElementById('copySecretBtn');
  if ($copySecretBtn) {
    $copySecretBtn.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText($secretText.textContent);
        toast('Secret copied', 'success');
      } catch {
        toast('Copy failed — select and copy manually', 'error');
      }
    });
  }

  /* ---------- Verify code ---------- */
  if ($verifyForm) {
    // Auto-format: strip non-digits
    $verifyCode.addEventListener('input', () => {
      $verifyCode.value = $verifyCode.value.replace(/\D/g, '').slice(0, 6);
      if ($verifyCode.value.length === 6) {
        $verifyForm.dispatchEvent(new Event('submit', { cancelable: true }));
      }
    });

    $verifyForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const code = $verifyCode.value.trim();

      if (!/^\d{6}$/.test(code)) {
        toast('Enter the 6-digit code from your app', 'error');
        return;
      }

      const btn = document.getElementById('verifyBtn');
      btn.disabled = true;
      btn.textContent = 'Verifying…';
      $verifyFb.style.display = 'none';

      try {
        const r = await post('/api/2fa-verify.php', { code });

        // Show recovery codes
        showRecoveryCodes(r.recovery_codes);

        // Hide the earlier steps, show recovery step
        document.querySelectorAll('.setup-step').forEach((el, i) => {
          if (i < 2) el.style.display = 'none';
        });
        const $recStep = document.getElementById('recoveryStep');
        if ($recStep) $recStep.style.display = 'flex';

        toast('Two-factor enabled', 'success');
      } catch (err) {
        $verifyFb.textContent = err.message;
        $verifyFb.className = 'order-feedback error';
        $verifyFb.style.display = 'block';
        $verifyCode.value = '';
        $verifyCode.focus();
        btn.disabled = false;
        btn.textContent = 'Verify & Enable';
      }
    });
  }

  /* ---------- Recovery codes rendering + actions ---------- */
  let allRecoveryCodes = [];

  function showRecoveryCodes(codes) {
    allRecoveryCodes = codes || [];
    const html = allRecoveryCodes.map(c => `<div class="recovery-code">${c}</div>`).join('');

    // Setup flow target
    const $list2 = document.getElementById('recoveryList2');
    if ($list2) $list2.innerHTML = html;

    // Already-enabled page target
    const $list = document.getElementById('recoveryList');
    if ($list && allRecoveryCodes.length) $list.innerHTML = html;
  }

  function copyAll() {
    const text = allRecoveryCodes.join('\n');
    navigator.clipboard.writeText(text)
      .then(() => toast('Codes copied', 'success'))
      .catch(() => toast('Copy failed', 'error'));
  }

  function downloadCodes() {
    const header =
      'AlphaEdge · 2FA Recovery Codes\n' +
      'Generated: ' + new Date().toISOString() + '\n' +
      'Each code can be used once.\n' +
      '===================================\n\n';
    const text = header + allRecoveryCodes.join('\n');
    const blob = new Blob([text], { type: 'text/plain' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'alphaedge-recovery-codes.txt';
    document.body.appendChild(a);
    a.click();
    setTimeout(() => { URL.revokeObjectURL(a.href); a.remove(); }, 100);
    toast('Download started', 'success');
  }

  ['copyRecoveryBtn', 'copyRecoveryBtn2'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', copyAll);
  });
  ['downloadRecoveryBtn', 'downloadRecoveryBtn2'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', downloadCodes);
  });

  /* ---------- Finish setup ---------- */
  const $finishBtn = document.getElementById('finishBtn');
  if ($finishBtn) {
    $finishBtn.addEventListener('click', () => {
      window.location.reload();
    });
  }

  /* ============================================================
   *  ALREADY-ENABLED ACTIONS
   * ============================================================ */
  const $showRecoveryBtn = document.getElementById('showRecoveryBtn');
  const $recoveryWrap    = document.getElementById('recoveryWrap');

  if ($showRecoveryBtn) {
    $showRecoveryBtn.addEventListener('click', async () => {
      const pwd = prompt('Enter your account password to reveal recovery codes:');
      if (pwd === null) return;
      if (!pwd) { toast('Password required', 'error'); return; }

      // For simplicity: we can't decrypt stored codes (they're hashed), so we
      // just fetch a check. In production you'd store an encrypted copy.
      // For this demo, we show a small notice.
      $recoveryWrap.style.display = 'block';
      const $list = document.getElementById('recoveryList');
      if ($list) {
        $list.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:14px;color:var(--text-dim);font-size:12px;">'
          + '⚠️ Recovery codes were shown only at setup time and are stored hashed for security. '
          + 'If you\'ve lost them, disable 2FA and set it up again.'
          + '</div>';
      }
      toast('Codes are hashed on the server', 'info');
    });
  }

  /* ---------- Disable 2FA ---------- */
  const $disableBtn = document.getElementById('disable2faBtn');
  if ($disableBtn) {
    $disableBtn.addEventListener('click', () => {
      const overlay = modal(`
        <h3>Disable two-factor auth?</h3>
        <div class="sub">
          This reduces your account security. You'll no longer be asked for a code at login.
        </div>
        <div class="form-group">
          <label for="disablePwd">Confirm your password</label>
          <input type="password" id="disablePwd" autofocus>
        </div>
        <div class="modal-actions">
          <button class="btn" id="ae-cancel">Cancel</button>
          <button class="btn btn-danger" id="ae-confirm-disable">Disable 2FA</button>
        </div>
      `);

      const $pwd = overlay.querySelector('#disablePwd');
      overlay.querySelector('#ae-cancel').addEventListener('click', closeModal);
      overlay.querySelector('#ae-confirm-disable').addEventListener('click', async () => {
        const btn = overlay.querySelector('#ae-confirm-disable');
        btn.disabled = true; btn.textContent = 'Disabling…';
        try {
          const r = await post('/api/2fa-disable.php', { password: $pwd.value });
          toast(r.message || '2FA disabled', 'success');
          closeModal();
          setTimeout(() => window.location.reload(), 800);
        } catch (err) {
          toast(err.message, 'error');
          btn.disabled = false; btn.textContent = 'Disable 2FA';
        }
      });
    });
  }
})();