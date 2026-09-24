<?php
require_once __DIR__ . '/includes/auth.php';

// Already fully logged in? Go to dashboard.
if (is_logged_in()) redirect(APP_URL . '/dashboard.php');

// Must have a pending 2FA session (set by login.php)
$pendingId = (int)($_SESSION['pending_2fa_user_id'] ?? 0);
$pendingAt = (int)($_SESSION['pending_2fa_at'] ?? 0);

// Expire the pending session after 10 minutes
if ($pendingId <= 0 || ($pendingAt > 0 && (time() - $pendingAt) > 600)) {
    unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_at']);
    flash_set('error', 'Your sign-in session expired. Please sign in again.');
    redirect(APP_URL . '/login.php');
}

// Look up the user (for masked email display)
$stmt = db()->prepare('SELECT username, email FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$pendingId]);
$pendingUser = $stmt->fetch();

if (!$pendingUser) {
    unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_at']);
    redirect(APP_URL . '/login.php');
}

// Mask email: al***@example.com
$email = $pendingUser['email'];
$masked = '';
if (strpos($email, '@') !== false) {
    [$local, $domain] = explode('@', $email, 2);
    $masked = substr($local, 0, 2) . str_repeat('•', max(2, strlen($local) - 2)) . '@' . $domain;
} else {
    $masked = $email;
}

$pageTitle = 'Two-Factor Authentication';
require __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/security.css">

<div class="auth-container">
  <div class="auth-card">
    <div class="twofa-icon">🔐</div>
    <h1>Two-factor required</h1>
    <p class="subtitle">
      Enter the 6-digit code from your authenticator app for
      <strong style="color:var(--text);"><?= e($masked) ?></strong>.
    </p>

    <div id="twofaFeedback" class="order-feedback" style="display:none;"></div>

    <form id="twofaForm" method="post" novalidate>
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="code">Authentication code</label>
        <input type="text" id="code" name="code"
               class="code-input"
               maxlength="6" inputmode="numeric" pattern="\d{6}"
               placeholder="000000" autocomplete="off" autofocus>
      </div>

      <button type="submit" class="btn btn-primary btn-block" id="twofaBtn">
        Verify &amp; Sign In
      </button>
    </form>

    <button type="button" class="btn btn-block" id="useRecoveryBtn" style="margin-top:10px;">
      Use a recovery code instead
    </button>

    <p class="auth-link" style="margin-top:18px;">
      <a href="<?= e(APP_URL) ?>/logout.php">← Back to sign in</a>
    </p>
  </div>
</div>

<script>
  window.TWOFA_API  = <?= json_encode(APP_URL) ?>;
  window.TWOFA_CSRF = <?= json_encode(csrf_token()) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/login-2fa.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>