/**
 * Sidebar + bottom-nav interactions.
 *  - Sidebar drawer toggle (mobile)
 *  - Bottom-nav "More" opens the sidebar drawer
 *  - Backdrop + Escape close
 */
(function () {
  'use strict';

  const toggle   = document.getElementById('sidebarToggle');
  const sidebar  = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  const moreBtn  = document.getElementById('bottomNavMore');

  if (!sidebar) return;

  function open() {
    sidebar.classList.add('open');
    if (backdrop) backdrop.classList.add('open');
  }
  function close() {
    sidebar.classList.remove('open');
    if (backdrop) backdrop.classList.remove('open');
  }
  function toggleDrawer() {
    sidebar.classList.contains('open') ? close() : open();
  }

  if (toggle)  toggle.addEventListener('click', toggleDrawer);
  if (moreBtn) moreBtn.addEventListener('click', open);
  if (backdrop) backdrop.addEventListener('click', close);

  // Auto-close when navigating
  sidebar.querySelectorAll('a.sidebar-link').forEach(a => {
    a.addEventListener('click', close);
  });

  // Escape key closes
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') close();
  });
})();