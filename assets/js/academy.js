(function () {
  'use strict';

  const API = window.AC_API || '';
  const HAS_DEPOSIT = window.AC_HAS_DEPOSIT;
  const COURSE_ID = window.AC_COURSE_ID || 0;

  /* ============================================================
   *  ACADEMY LIST PAGE
   * ============================================================ */
  const stagesRoot = document.getElementById('academyStages');
  if (stagesRoot && HAS_DEPOSIT) {
    loadAcademyList();
  }

  async function loadAcademyList() {
    try {
      const r = await fetch(API + '/api/academy.php?action=list', { credentials: 'same-origin' });
      const json = await r.json();
      const courses = json.courses || [];

      // Group by stage
      const stages = ['basic','intermediate','advanced','strategies'];
      const stageLabels = {
        basic:        ['Basic Trader',        'Fundamentals — what markets are and how they work'],
        intermediate: ['Intermediate Trader', 'Structure, indicators, risk management'],
        advanced:     ['Advanced Trader',     'Patterns, multi-timeframe, order flow'],
        strategies:   ['Strategies Masterclass', 'Specific strategies — how to build and trade them'],
      };

      let html = '';
      let totalDone = 0;
      let totalLessons = 0;

      stages.forEach(stage => {
        const stageCourses = courses.filter(c => c.stage === stage);
        if (!stageCourses.length) return;

        stageCourses.forEach(c => {
          totalDone += c.done;
          totalLessons += c.total;
        });

        html += `<div class="academy-stage">
          <div class="academy-stage-title">${stageLabels[stage][0]}</div>
          <div class="academy-stage-sub">${stageLabels[stage][1]}</div>
          <div class="academy-courses-grid">`;

        stageCourses.forEach(c => {
          const pct = c.total > 0 ? (c.done / c.total) * 100 : 0;
          html += `
            <a href="${API}/academy-course.php?id=${c.id}" class="academy-course-card">
              <div class="academy-course-head">
                <span class="academy-course-icon">${c.icon}</span>
                <span class="academy-course-title">${esc(c.title)}</span>
              </div>
              <div class="academy-course-desc">${esc(c.description || '')}</div>
              <div class="academy-course-progress">
                <div class="academy-course-progress-fill" style="width:${pct}%"></div>
              </div>
              <div class="academy-course-meta">${c.done} / ${c.total} lessons</div>
            </a>
          `;
        });

        html += '</div></div>';
      });

      stagesRoot.innerHTML = html;

      // Stats
      document.getElementById('acDone').textContent = totalDone;
      const pct = totalLessons > 0 ? Math.round((totalDone / totalLessons) * 100) : 0;
      document.getElementById('acPct').textContent = pct + '%';
      document.getElementById('acBadges').textContent = totalDone > 15 ? '4' : (totalDone > 5 ? '2' : (totalDone > 0 ? '1' : '0'));
      document.getElementById('acStage').textContent = pct >= 75 ? 'Strategies' : (pct >= 50 ? 'Advanced' : (pct >= 25 ? 'Intermediate' : 'Basic'));
    } catch (err) {
      stagesRoot.innerHTML = '<div class="empty-state" style="color:var(--red);">Failed to load Academy.</div>';
    }
  }

  function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  /* ============================================================
   *  COURSE DETAIL PAGE
   * ============================================================ */
  const lessonList = document.querySelectorAll('.academy-lesson-item');
  if (lessonList.length) {
    // Click to switch lessons
    lessonList.forEach(item => {
      item.addEventListener('click', () => {
        const id = item.dataset.lessonId;

        lessonList.forEach(i => i.classList.remove('active'));
        item.classList.add('active');

        document.querySelectorAll('.academy-lesson-content').forEach(c => {
          c.style.display = c.dataset.lessonId === id ? '' : 'none';
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    });

    // Mark first as active
    lessonList[0].classList.add('active');

    // Complete button
    document.querySelectorAll('.academy-complete-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const lessonId = btn.dataset.complete;
        btn.disabled = true;
        btn.textContent = 'Saving…';

        try {
          const r = await fetch(API + '/api/academy.php?action=complete', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ lesson_id: lessonId }),
          });
          const json = await r.json();
          if (!r.ok) throw new Error(json.error);

          // Update UI
          btn.outerHTML = '<div class="academy-completed-badge">✓ Completed</div>';
          const listItem = document.querySelector(`.academy-lesson-item[data-lesson-id="${lessonId}"]`);
          if (listItem) {
            listItem.classList.add('done');
            if (!listItem.querySelector('.academy-lesson-check')) {
              const check = document.createElement('span');
              check.className = 'academy-lesson-check';
              check.textContent = '✓';
              listItem.appendChild(check);
            }
          }
        } catch (err) {
          btn.disabled = false;
          btn.textContent = '✓ Mark as complete';
          alert(err.message);
        }
      });
    });
  }
})();