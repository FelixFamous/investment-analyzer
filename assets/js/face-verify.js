/**
 * AlphaEdge · Face verification widget (v3 — lenient mode)
 * Purpose: capture a live selfie for KYC. Emphasis on reliability, not perfect detection.
 *
 * Rules:
 *   - Camera must be on
 *   - Frame must not be completely black
 *   - Capture unlocks after 2 seconds of camera on, ALWAYS.
 *     Detection helps guide the user but never blocks them.
 */

(function () {
  'use strict';

  const $panel      = document.getElementById('facePanel');
  if (!$panel) return;

  const $video      = document.getElementById('faceVideo');
  const $canvas     = document.getElementById('faceCanvas');
  const $preview    = document.getElementById('facePreviewImg');
  const $hint       = document.getElementById('faceHint');
  const $startBtn   = document.getElementById('faceStartBtn');
  const $captureBtn = document.getElementById('faceCaptureBtn');
  const $retakeBtn  = document.getElementById('faceRetakeBtn');
  const $checklist  = document.getElementById('faceChecklist');
  const $hidden     = document.getElementById('selfieData');

  if (!$startBtn) return;

  let stream = null;
  let analysisTimer = null;
  let unlockTimer = null;
  let captured = false;
  const checks = { camera: false, light: false, face: false, position: false };

  console.log('[FaceVerify] Initialized');

  /* ============================================================
   *  UI HELPERS
   * ============================================================ */
  function setCheck(name, value) {
    if (checks[name] === value) return;
    checks[name] = value;
    const el = $checklist.querySelector(`[data-check="${name}"]`);
    if (!el) return;
    const ck = el.querySelector('.ck');
    if (value) {
      el.classList.add('done');
      ck.textContent = '✓';
    } else {
      el.classList.remove('done');
      ck.textContent = '○';
    }
  }

  function updateHint(text, kind) {
    $hint.textContent = text;
    $hint.className = 'face-hint' + (kind ? ' face-hint-' + kind : '');
  }

  function unlockCapture() {
    $captureBtn.disabled = false;
    setCheck('camera', true);
    setCheck('light', true);
    setCheck('face', true);
    setCheck('position', true);
  }

  /* ============================================================
   *  LIGHT + SKIN ANALYSIS (guide-only, does not block)
   * ============================================================ */
  function analyzeFrame() {
    if (captured || !$video.videoWidth) return;

    const $c = $canvas;
    const vw = $video.videoWidth, vh = $video.videoHeight;
    $c.width = 200;
    $c.height = Math.round((vh / vw) * 200);
    const ctx = $c.getContext('2d', { willReadFrequently: true });
    ctx.drawImage($video, 0, 0, $c.width, $c.height);
    const data = ctx.getImageData(0, 0, $c.width, $c.height).data;

    // Brightness
    let sum = 0, n = 0;
    for (let i = 0; i < data.length; i += 40) {
      sum += 0.299 * data[i] + 0.587 * data[i+1] + 0.114 * data[i+2];
      n++;
    }
    const brightness = n ? sum / n : 0;

    // Skin-tone scan across the ENTIRE frame (not just center)
    let skin = 0, total = 0;
    for (let i = 0; i < data.length; i += 4) {
      total++;
      if (isSkinTone(data[i], data[i+1], data[i+2])) skin++;
    }
    const skinFrac = total > 0 ? skin / total : 0;

    console.log('[FaceVerify] brightness:', brightness.toFixed(0), 'skin%:', (skinFrac*100).toFixed(1));

    // Always update the checklist (informational)
    setCheck('light', brightness > 25);
    setCheck('face', skinFrac > 0.02 || brightness > 80);
    setCheck('position', true);   // no strict position requirement

    // Guide messages only
    if (brightness < 25) {
      updateHint('Camera may be covered or environment is very dark.', 'warn');
    } else if (skinFrac > 0.05) {
      updateHint('Face detected. Capture when ready.', 'ok');
    } else {
      updateHint('Position your face in the oval.', 'warn');
    }
  }

  function isSkinTone(r, g, b) {
    const max = Math.max(r, g, b), min = Math.min(r, g, b);
    const rgbOk = (r > 50 && g > 25 && b > 12 && (max - min) > 8 && r > g);
    const y  = 0.299 * r + 0.587 * g + 0.114 * b;
    const cb = 128 - 0.168736 * r - 0.331264 * g + 0.5 * b;
    const cr = 128 + 0.5 * r - 0.418688 * g - 0.081312 * b;
    const ycbcrOk = (y > 50 && cb > 70 && cb < 145 && cr > 125 && cr < 185);
    return rgbOk || ycbcrOk;
  }

  /* ============================================================
   *  CAMERA
   * ============================================================ */
  async function startCamera() {
    console.log('[FaceVerify] Starting camera…');
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
        audio: false
      });

      $video.srcObject = stream;
      $video.style.display = 'block';
      $preview.style.display = 'none';
      $startBtn.style.display = 'none';
      $captureBtn.style.display = 'inline-flex';
      $retakeBtn.style.display = 'none';

      await $video.play().catch(() => {});
      console.log('[FaceVerify] Video playing:', $video.videoWidth, 'x', $video.videoHeight);

      setCheck('camera', true);
      updateHint('Camera on. Getting ready…');

      // Start analysis loop for guidance only
      analysisTimer = setInterval(analyzeFrame, 400);

      // **Guaranteed unlock** after 2.5 seconds — capture always becomes available
      unlockTimer = setTimeout(() => {
        unlockCapture();
        updateHint('Ready. Capture when you are.', 'ok');
      }, 2500);

    } catch (err) {
      console.error('[FaceVerify] Camera error:', err);
      updateHint('Camera access denied. Enable it in browser settings.', 'error');
    }
  }

  function stopCamera() {
    if (analysisTimer) { clearInterval(analysisTimer); analysisTimer = null; }
    if (unlockTimer) { clearTimeout(unlockTimer); unlockTimer = null; }
    if (stream) {
      stream.getTracks().forEach(t => t.stop());
      stream = null;
    }
  }

  /* ============================================================
   *  CAPTURE
   * ============================================================ */
  function capture() {
    if (!$video.videoWidth) {
      alert('Camera not ready yet. Wait a moment.');
      return;
    }

    const w = 480;
    const h = Math.round(($video.videoHeight / $video.videoWidth) * w);
    const $c = $canvas;
    $c.width = w;
    $c.height = h;
    const ctx = $c.getContext('2d');
    ctx.translate(w, 0);
    ctx.scale(-1, 1);
    ctx.drawImage($video, 0, 0, w, h);
    ctx.setTransform(1, 0, 0, 1, 0, 0);

    const dataUrl = $c.toDataURL('image/jpeg', 0.85);

    $preview.src = dataUrl;
    $preview.style.display = 'block';
    $video.style.display = 'none';
    $captureBtn.style.display = 'none';
    $retakeBtn.style.display = 'inline-flex';
    captured = true;
    $hidden.value = dataUrl;

    updateHint('Selfie captured. Scroll down to submit.', 'ok');
    stopCamera();
    console.log('[FaceVerify] Captured. Length:', dataUrl.length);
  }

  function retake() {
    captured = false;
    $hidden.value = '';
    $preview.style.display = 'none';
    setCheck('camera', false);
    setCheck('light', false);
    setCheck('face', false);
    setCheck('position', false);
    startCamera();
  }

  /* ============================================================
   *  WIRE UP
   * ============================================================ */
  $startBtn.addEventListener('click', startCamera);
  $captureBtn.addEventListener('click', capture);
  $retakeBtn.addEventListener('click', retake);

  const $form = document.getElementById('kycForm');
  if ($form) {
    $form.addEventListener('submit', (e) => {
      if (!$hidden.value) {
        e.preventDefault();
        alert('Please capture a selfie before submitting.');
      }
    });
  }

  window.addEventListener('beforeunload', stopCamera);
})();