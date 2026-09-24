/**
 * AlphaEdge · KYC Wizard
 * Steps through the multi-part KYC form. Validates each step before advancing.
 * The final submit only unlocks when the selfie is captured.
 */

(function () {
  'use strict';

  const $form = document.getElementById('kycForm');
  if (!$form) return;

  const $panels = Array.from(document.querySelectorAll('.kyc-step-panel'));
  const $indicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
  const $fill = document.getElementById('wizFill');
  const $prev = document.getElementById('wizPrev');
  const $next = document.getElementById('wizNext');
  const $submit = document.getElementById('kycSubmitBtn');
  const $selfieData = document.getElementById('selfieData');
  const $docType = document.getElementById('doc_type');
  const $docBackGroup = document.getElementById('docBackGroup');

  const TOTAL = $panels.length;
  let current = 1;

  /* ============================================================
   * STEP NAVIGATION
   * ============================================================ */
  function showStep(n) {
    if (n < 1 || n > TOTAL) return;
    current = n;

    $panels.forEach((p, i) => {
      p.style.display = (i + 1 === n) ? '' : 'none';
    });

    $indicators.forEach((el, i) => {
      const stepNum = i + 1;
      el.classList.toggle('active', stepNum === n);
      el.classList.toggle('done', stepNum < n);
    });

    $fill.style.width = ((n / TOTAL) * 100) + '%';
    $prev.disabled = (n === 1);

    // Swap Next/Submit
    if (n === TOTAL) {
      $next.style.display = 'none';
      $submit.style.display = 'inline-flex';
      // Submit only unlocks if selfie exists
      updateSubmitState();
    } else {
      $next.style.display = 'inline-flex';
      $submit.style.display = 'none';
    }

    // Scroll wizard into view
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function updateSubmitState() {
    if (!$submit) return;
    const hasSelfie = $selfieData && $selfieData.value !== '';
    $submit.disabled = !hasSelfie;
  }

  /* ============================================================
   * FIELD VALIDATION (per step)
   * ============================================================ */
  function visibleFieldsInStep(stepEl) {
    return Array.from(stepEl.querySelectorAll('input, select, textarea'))
      .filter(el => el.type !== 'hidden' && el.offsetParent !== null);
  }

  function validateStep(n) {
    const stepEl = $panels[n - 1];
    if (!stepEl) return true;

    const fields = visibleFieldsInStep(stepEl);
    for (const el of fields) {
      if (!el.checkValidity()) {
        el.reportValidity();
        el.focus();
        return false;
      }
    }
    return true;
  }

  /* ============================================================
   * DOC TYPE → toggle BACK upload
   * ============================================================ */
  function syncDocBack() {
    if (!$docType || !$docBackGroup) return;
    const needsBack = ['national_id', 'drivers_license'].includes($docType.value);
    $docBackGroup.style.display = needsBack ? '' : 'none';
    const backInput = document.getElementById('doc_back');
    if (backInput) backInput.required = needsBack;
  }
  if ($docType) $docType.addEventListener('change', syncDocBack);
  syncDocBack();

  /* ============================================================
   * SELFIE WATCH — unlock submit when captured
   * ============================================================ */
  if ($selfieData) {
    // Observe value changes (face-verify.js sets it on capture)
    const observer = new MutationObserver(updateSubmitState);
    observer.observe($selfieData, { attributes: true, attributeFilter: ['value'] });

    // Also poll every 500ms as a fallback
    setInterval(updateSubmitState, 500);
  }

  /* ============================================================
   * NEXT / PREV
   * ============================================================ */
  $next.addEventListener('click', () => {
    if (!validateStep(current)) return;
    showStep(current + 1);
  });

  $prev.addEventListener('click', () => {
    showStep(current - 1);
  });

  /* ============================================================
   * SUBMIT GUARD
   * ============================================================ */
  $form.addEventListener('submit', (e) => {
    // Validate all steps before submitting
    for (let i = 1; i <= TOTAL; i++) {
      if (!validateStep(i)) {
        showStep(i);
        e.preventDefault();
        return;
      }
    }

    if (!$selfieData.value) {
      e.preventDefault();
      alert('Please capture a selfie before submitting.');
      showStep(TOTAL);
      return;
    }

    // Lock the button to prevent double submit
    if ($submit) {
      $submit.disabled = true;
      $submit.textContent = 'Submitting…';
    }
  });

  /* ============================================================
   * INIT
   * ============================================================ */
  showStep(1);
})();