/**
 * AlphaEdge · 2FA login verification
 */

(function () {
  'use strict';

  const API  = window.TWOFA_API  || '';
  const CSRF = window.TWOFA_CSRF || '';

  const $form   = document.getElementById('twofaForm');
  const $input  = document.getElementById('code');
  const $btn    = document.getElementById('twofaBtn');
  const $fb     = document.getElementById('twofaFeedback');
  const $recov  = document.getElementById('useRecoveryBtn');

  if (!$form) return;

  function showError(msg) {
    $fb.textContent = msg;
    $fb.className = 'order-feedback error';
    $fb.style.display = 'block';
  }

  function showSuccess(msg) {
    $fb.textContent = msg;
    $fb.className = 'order-feedback success';
    $fb.style.display = 'block';
  }

  function hideFeedback() {
    $fb.style.display = 'none';
  }

  // Auto-format: keep digits for 6-digit, allow dash for recovery codes
  $input.addEventListener('input', () => {
    let v = $input.value;

    // Detect mode: if user pasted a recovery code with letters, allow it
    if (/[a-fA-F]/.test(v) || v.includes('-')) {
      $input.setAttribute('maxlength', '9');
      v = v.toLowerCase().replace(/[^a-f0-9-]/g, '');
      // Auto-insert dash after 4 chars if not present
      if (v.length === 4 && !v.includes('-')) v = v + '-';
      if (v.length > 9) v = v.slice(0, 9);
      $input.value = v;
      $input.style.letterSpacing = '2px';
      $input.style.fontSize = '18px';
    } else {
      $input.setAttribute('maxlength', '6');
      v = v.replace(/\D/g, '').slice(0, 6);
      $input.value = v;
      $input.style.letterSpacing = '';
      $input.style.fontSize = '';
    }

    // Auto-submit when 6 digits
    if (/^\d{6}$/.test($input.value)) {
      $form.dispatchEvent(new Event('submit', { cancelable: true }));
    }
  });

  $form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const code = $input.value.trim();
    if (!code) {
      showError('Enter the code from your authenticator app.');
      return;
    }
    if (!/^\d{6}$/.test(code) && !/^[a-f0-9]{4}-?[a-f0-9]{4}$/.test(code)) {
      showError('Enter a 6-digit code or a recovery code (xxxx-xxxx).');
      return;
    }

    $btn.disabled = true;
    $btn.textContent = 'Verifying…';
    hideFeedback();

    try {
      const res = await fetch(API + '/api/login-verify-2fa.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ code, csrf: CSRF }),
      });
      const json = await res.json();
      if (!res.ok) throw new Error(json.error || 'Verification failed');

      showSuccess(json.message || 'Verified');
      setTimeout(() => { window.location.href = API + '/dashboard.php'; }, 500);
    } catch (err) {
      showError(err.message);
      $input.value = '';
      $input.focus();
      $btn.disabled = false;
      $btn.textContent = 'Verify & Sign In';
    }
  });

  // Recovery mode toggle
  if ($recov) {
    $recov.addEventListener('click', () => {
      $input.value = '';
      $input.setAttribute('maxlength', '9');
      $input.placeholder = 'abcd-ef12';
      $input.style.letterSpacing = '2px';
      $input.style.fontSize = '18px';
      $input.focus();
      hideFeedback();

      // Give them a subtle inline hint by injecting a small label
      const lbl = $input.closest('.form-group')?.querySelector('label');
      if (lbl) lbl.textContent = 'Recovery code';
    });
  }
})();