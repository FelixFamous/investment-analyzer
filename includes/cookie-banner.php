<?php
/**
 * Cookie consent banner.
 * Shown once, dismissed by setting a cookie.
 * Include right before </body> in footer.php.
 */
?>
<div id="cookieBanner" class="cookie-banner" style="display:none;" role="dialog" aria-label="Cookie consent">
  <div class="cookie-banner-content">
    <div class="cookie-banner-icon">🍪</div>
    <div class="cookie-banner-text">
      <strong>We use cookies</strong>
      <p>
        AlphaEdge uses cookies to keep you signed in, remember your preferences (like timezone),
        and analyze site usage. By clicking <em>Accept</em>, you agree to our
        <a href="<?= e(APP_URL) ?>/cookies.php">Cookie Policy</a>,
        <a href="<?= e(APP_URL) ?>/privacy.php">Privacy Policy</a>, and
        <a href="<?= e(APP_URL) ?>/terms.php">Terms of Service</a>.
      </p>
    </div>
    <div class="cookie-banner-actions">
      <button type="button" class="btn" id="cookieDecline">Decline</button>
      <button type="button" class="btn btn-primary" id="cookieAccept">Accept</button>
    </div>
  </div>
</div>

<script>
(function () {
  const KEY = 'alphaedge_cookie_consent';
  const banner = document.getElementById('cookieBanner');
  if (!banner) return;

  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + days * 86400000);
    document.cookie = name + '=' + encodeURIComponent(value)
      + ';expires=' + d.toUTCString()
      + ';path=/;SameSite=Lax';
  }

  function getCookie(name) {
    const match = document.cookie.match('(?:^|; )' + name + '=([^;]*)');
    return match ? decodeURIComponent(match[1]) : null;
  }

  if (!getCookie(KEY)) {
    setTimeout(() => { banner.style.display = 'flex'; }, 600);
  }

  document.getElementById('cookieAccept').addEventListener('click', () => {
    setCookie(KEY, 'accepted', 365);
    banner.style.display = 'none';
  });

  document.getElementById('cookieDecline').addEventListener('click', () => {
    setCookie(KEY, 'declined', 30);
    banner.style.display = 'none';
  });
})();
</script>