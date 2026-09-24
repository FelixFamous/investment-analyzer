/**
 * AlphaEdge · Micro-animations
 * Ripple, count-up, tilt, scroll reveal — all lightweight.
 */

(function () {
  'use strict';

  /* ============================================================
   *  1. BUTTON RIPPLE
   * ============================================================ */
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn, .sidebar-link, .tab, .pct-btn, .ind-btn');
    if (!btn) return;
    if (btn.disabled) return;

    const rect = btn.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;

    const ripple = document.createElement('span');
    ripple.className = 'ripple';
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';

    if (getComputedStyle(btn).position === 'static') {
      btn.style.position = 'relative';
    }
    btn.appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);
  });

  /* ============================================================
   *  2. NUMBER COUNT-UP
   *  Detects stat card values and animates them.
   *  Only runs on load + when the page changes enough.
   * ============================================================ */
  function animateNumber(el, duration = 900) {
    const text = el.textContent.trim();
    if (!text) return;

    // Skip if it doesn't look like a number (contains % or letters, skip)
    // But allow: $1,234.56, +$1.00, -$25.00, 1234.5, 45.8
    const match = text.match(/^([+\-]?)([^\d\-+]*)([\d,]+(?:\.\d+)?)(.*)$/);
    if (!match) return;

    const [, sign, prefix, numStr, suffix] = match;
    const target = parseFloat(numStr.replace(/,/g, ''));
    if (isNaN(target)) return;

    const decimals = (numStr.split('.')[1] || '').length;
    const hasCommas = numStr.includes(',');
    const start = performance.now();

    // Ease-out cubic
    const easeOut = t => 1 - Math.pow(1 - t, 3);

    function tick(now) {
      const elapsed = now - start;
      const t = Math.min(1, elapsed / duration);
      const current = target * easeOut(t);

      let formatted = current.toFixed(decimals);
      if (hasCommas) {
        const [intPart, decPart] = formatted.split('.');
        formatted = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',') + (decPart ? '.' + decPart : '');
      }

      el.textContent = sign + prefix + formatted + suffix;

      if (t < 1) requestAnimationFrame(tick);
    }

    requestAnimationFrame(tick);
  }

  function runCountUps() {
    const targets = document.querySelectorAll('.card-value, .stat-value, [data-count]');
    targets.forEach(el => {
      if (el.dataset.animated === '1') return;
      // skip if it contains only non-numeric content
      if (!/\d/.test(el.textContent)) return;
      // skip elements with sub-children (like nested spans)
      if (el.children.length > 1) return;
      el.dataset.animated = '1';
      animateNumber(el);
    });
  }

  /* ============================================================
   *  3. SCROLL REVEAL
   * ============================================================ */
  function initScrollReveal() {
    if (!('IntersectionObserver' in window)) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          io.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.08,
      rootMargin: '0px 0px -40px 0px',
    });

    const selectors = [
      '.panel',
      '.watchlist-card',
      '.invite-row',
      '.alert-item',
      '.open-order-row',
      '.dca-item',
      '.cal-event',
      '.news-item',
      '.position-box',
      '.how-step',
    ];

    document.querySelectorAll(selectors.join(',')).forEach((el, idx) => {
      // skip things already animated by the CSS grid stagger
      if (el.closest('.summary-grid') || el.closest('.main-grid')) return;
      if (el.dataset.revealed === '1') return;
      el.dataset.revealed = '1';

      el.style.opacity = '0';
      el.style.transform = 'translateY(16px)';
      el.style.transition = `opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1) ${idx * 0.03}s, transform 0.6s cubic-bezier(0.22, 1, 0.36, 1) ${idx * 0.03}s`;

      io.observe(el);
    });
  }

  /* ============================================================
   *  4. CARD TILT (very subtle, desktop only)
   * ============================================================ */
  function initTilt() {
    if (window.matchMedia('(hover: none)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const cards = document.querySelectorAll('.card, .watchlist-card');
    cards.forEach(card => {
      let rafId = null;

      card.addEventListener('mousemove', (e) => {
        if (rafId) return;
        rafId = requestAnimationFrame(() => {
          rafId = null;
          const rect = card.getBoundingClientRect();
          const x = (e.clientX - rect.left) / rect.width - 0.5;
          const y = (e.clientY - rect.top) / rect.height - 0.5;
          const tiltX = -y * 3; // max 3 degrees
          const tiltY = x * 3;
          card.style.transform = `translateY(-2px) perspective(900px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
        });
      });

      card.addEventListener('mouseleave', () => {
        card.style.transform = '';
      });
    });
  }

  /* ============================================================
   *  5. LIVE PRICE FLASH
   *  Watch for changes to [data-live-price] and flash
   * ============================================================ */
  function initLiveFlash() {
    const observed = new WeakMap();

    const observer = new MutationObserver((mutations) => {
      mutations.forEach(mutation => {
        if (mutation.type !== 'characterData' && mutation.type !== 'childList') return;
        const el = mutation.target.nodeType === 3 ? mutation.target.parentElement : mutation.target;
        if (!el) return;

        const oldText = observed.get(el);
        const newText = el.textContent;

        if (oldText !== undefined && oldText !== newText) {
          const oldNum = parseFloat(oldText.replace(/[^0-9.\-]/g, ''));
          const newNum = parseFloat(newText.replace(/[^0-9.\-]/g, ''));

          if (!isNaN(oldNum) && !isNaN(newNum) && oldNum !== newNum) {
            const cls = newNum > oldNum ? 'flash-up' : 'flash-down';
            el.classList.remove('flash-up', 'flash-down');
            void el.offsetWidth; // force reflow
            el.classList.add(cls);
            setTimeout(() => el.classList.remove(cls), 700);
          }
        }

        observed.set(el, newText);
      });
    });

    document.querySelectorAll('[data-live-price]').forEach(el => {
      observed.set(el, el.textContent);
      observer.observe(el, { characterData: true, childList: true, subtree: true });
    });
  }

  /* ============================================================
   *  6. SMOOTH ANCHOR SCROLL
   * ============================================================ */
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href^="#"]');
    if (!link) return;
    const id = link.getAttribute('href');
    if (id === '#' || id === '#!') return;
    const target = document.querySelector(id);
    if (!target) return;
    e.preventDefault();
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  /* ============================================================
   *  7. PULSE ON NEW NOTIFICATIONS / BADGES
   * ============================================================ */
  function pulseBadges() {
    document.querySelectorAll('.badge-count, .admin-count').forEach(el => {
      if (el.textContent && el.textContent.trim() !== '0' && !el.dataset.pulsed) {
        el.dataset.pulsed = '1';
        el.style.animation = 'badgePulse 1.6s ease-in-out infinite';
      }
    });
  }

  const badgeStyle = document.createElement('style');
  badgeStyle.textContent = `
    @keyframes badgePulse {
      0%, 100% { transform: scale(1); }
      50%      { transform: scale(1.12); }
    }
  `;
  document.head.appendChild(badgeStyle);

  /* ============================================================
   *  8. INIT
   * ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    // Small delay so everything is painted first
    requestAnimationFrame(() => {
      runCountUps();
      initScrollReveal();
      initTilt();
      initLiveFlash();
      pulseBadges();
    });

    // Re-run count-up on select changes (e.g., filtering switching stats)
    document.addEventListener('change', (e) => {
      if (e.target.matches('select')) {
        setTimeout(runCountUps, 100);
      }
    });
  });

  // Re-check for new content after navigation or AJAX
  window.addEventListener('load', () => {
    setTimeout(runCountUps, 200);
  });

})();