/**
 * AlphaEdge · Economic Calendar
 */
(function () {
  'use strict';

  const API = window.CAL_API || '';
  const $list = document.getElementById('calendarList');
  if (!$list) return;

  let events = [];
  let filter = 'all';

  function fmtDate(iso) {
    const d = new Date(iso + 'T00:00:00Z');
    return d.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', timeZone: 'UTC' });
  }
  function daysUntil(iso) {
    const d = new Date(iso + 'T00:00:00Z');
    const today = new Date();
    today.setUTCHours(0, 0, 0, 0);
    const diff = Math.round((d - today) / 86400000);
    if (diff === 0) return 'Today';
    if (diff === 1) return 'Tomorrow';
    if (diff > 0) return 'In ' + diff + ' days';
    return diff + ' days ago';
  }

  function render() {
    const shown = filter === 'all' ? events : events.filter(e => e.impact === filter);

    if (!shown.length) {
      $list.innerHTML = '<div class="empty-state" style="padding:40px;">No events match this filter.</div>';
      return;
    }

    let html = '';
    let lastDate = '';

    shown.forEach(e => {
      if (e.date !== lastDate) {
        lastDate = e.date;
        html += `<div class="cal-date-group">
          <div class="cal-date-label">${fmtDate(e.date)}<span class="cal-days-until">${daysUntil(e.date)}</span></div>`;
      }

      const impactCls = e.impact === 'high' ? 'high' : (e.impact === 'medium' ? 'medium' : 'low');
      const catChip = `<span class="cal-cat">${e.category}</span>`;
      const sym = e.symbol ? `<span class="cal-sym">$${e.symbol}</span>` : '';

      html += `
        <div class="cal-event">
          <div class="cal-event-time">${e.time}</div>
          <div class="cal-event-bar ${impactCls}"></div>
          <div class="cal-event-body">
            <div class="cal-event-headline">
              ${e.title} ${catChip} ${sym}
            </div>
            ${e.note ? `<div class="cal-event-note">${e.note}</div>` : ''}
          </div>
          <div class="cal-impact-badge ${impactCls}">
            ${e.impact.toUpperCase()}
          </div>
        </div>`;
    });

    $list.innerHTML = html;
  }

  function updateStats() {
    const high = events.filter(e => e.impact === 'high').length;
    const med  = events.filter(e => e.impact === 'medium').length;
    document.getElementById('statHigh').textContent = high;
    document.getElementById('statMedium').textContent = med;
    document.getElementById('statTotal').textContent = events.length;

    if (events.length) {
      const next = events[0];
      document.getElementById('statNext').textContent = next.title;
      document.getElementById('statNextDate').textContent = daysUntil(next.date) + ' · ' + fmtDate(next.date);
    }
  }

  fetch(API + '/api/economic-calendar.php?days=30')
    .then(r => r.json())
    .then(json => {
      events = json.events || [];
      updateStats();
      render();
    })
    .catch(() => {
      $list.innerHTML = '<div class="empty-state" style="color:var(--red);padding:40px;">Failed to load calendar.</div>';
    });

  document.querySelectorAll('#calFilters [data-impact]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#calFilters [data-impact]').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      filter = btn.dataset.impact;
      render();
    });
  });
})();