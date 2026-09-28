/* ============================================================
   AlphaEdge Academy — client interactions
   ============================================================ */
(function () {
  'use strict';

  const cfg = window.ACADEMY;
  if (!cfg) return;

  function post(path, data) {
    const body = new URLSearchParams(Object.assign({ csrf: cfg.csrf }, data));
    return fetch(cfg.api + path, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body
    }).then(function (r) { return r.json(); });
  }

  /* ---------- Bookmark toggle ---------- */
  const bmBtn = document.getElementById('academyBookmarkBtn');
  if (bmBtn) {
    bmBtn.addEventListener('click', function () {
      bmBtn.disabled = true;
      post('/api/academy-bookmark.php', { lesson_id: cfg.lessonId, item_type: 'lesson' })
        .then(function (res) {
          bmBtn.disabled = false;
          if (res && res.success) {
            bmBtn.textContent = res.bookmarked ? '★ Bookmarked' : '☆ Bookmark';
          }
        })
        .catch(function () { bmBtn.disabled = false; });
    });
  }

  /* ---------- Mark complete ---------- */
  const doneBtn = document.getElementById('academyMarkDoneBtn');
  if (doneBtn) {
    doneBtn.addEventListener('click', function () {
      if (cfg.isDone) return;
      doneBtn.disabled = true;
      post('/api/academy-complete.php', { lesson_id: cfg.lessonId })
        .then(function (res) {
          if (res && res.success) {
            doneBtn.textContent = '✓ Completed';
            cfg.isDone = true;
          } else {
            doneBtn.disabled = false;
          }
        })
        .catch(function () { doneBtn.disabled = false; });
    });
  }

  /* ---------- Notes ---------- */
  const noteBtn = document.getElementById('academyNoteSaveBtn');
  const noteBox = document.getElementById('academyNoteText');
  const noteStatus = document.getElementById('academyNoteStatus');
  if (noteBtn && noteBox) {
    noteBtn.addEventListener('click', function () {
      noteBtn.disabled = true;
      if (noteStatus) noteStatus.textContent = 'Saving…';
      post('/api/academy-note.php', { lesson_id: cfg.lessonId, note: noteBox.value })
        .then(function (res) {
          noteBtn.disabled = false;
          if (noteStatus) noteStatus.textContent = (res && res.success) ? 'Saved.' : 'Could not save.';
        })
        .catch(function () {
          noteBtn.disabled = false;
          if (noteStatus) noteStatus.textContent = 'Network error.';
        });
    });
  }

  /* ---------- Quiz submission ---------- */
  const quizForm = document.getElementById('academyQuizForm');
  const quizResult = document.getElementById('academyQuizResult');
  if (quizForm && quizResult) {
    quizForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const answers = {};
      quizForm.querySelectorAll('.academy-quiz-q').forEach(function (qEl) {
        const qid = qEl.getAttribute('data-qid');
        const checked = qEl.querySelector('input[type="radio"]:checked');
        if (checked) {
          answers[qid] = checked.value;
        } else {
          const text = qEl.querySelector('input[type="text"]');
          if (text && text.value.trim() !== '') answers[qid] = text.value.trim();
        }
      });

      const submitBtn = quizForm.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;
      quizResult.style.display = 'block';
      quizResult.textContent = 'Scoring…';

      post('/api/academy-quiz-submit.php', {
        quiz_id: quizForm.getAttribute('data-quiz-id'),
        answers: JSON.stringify(answers)
      })
        .then(function (res) {
          if (submitBtn) submitBtn.disabled = false;
          if (!res || !res.success) {
            quizResult.textContent = 'Could not score your answers.';
            return;
          }
          quizResult.innerHTML =
            '<strong>' + res.score + '%</strong> — ' +
            (res.passed ? '✓ Passed' : 'Not quite. Review the lesson and try again.') +
            (res.correct_count !== undefined
              ? ' (' + res.correct_count + '/' + res.total + ' correct)'
              : '');
        })
        .catch(function () {
          if (submitBtn) submitBtn.disabled = false;
          quizResult.textContent = 'Network error.';
        });
    });
  }
})();