<?php
require_once __DIR__ . '/includes/auth.php';

$user = require_login();
$isOn = (int)$user['totp_enabled'] === 1;

$pageTitle = 'Security';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/security.css">

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Two-Factor Auth</div>
    <div class="card-value" style="font-size:20px;color:<?= $isOn ? 'var(--green)' : 'var(--red)' ?>;">
      <?= $isOn ? '✓ Enabled' : '✗ Disabled' ?>
    </div>
    <div class="card-sub">Google Authenticator / Authy</div>
  </div>
  <div class="card">
    <div class="card-label">Password</div>
    <div class="card-value" style="font-size:20px;">••••••••</div>
    <div class="card-sub">Set at registration</div>
  </div>
  <div class="card">
    <div class="card-label">Session</div>
    <div class="card-value" style="font-size:20px;">Active</div>
    <div class="card-sub">Signed in as <?= e($user['username']) ?></div>
  </div>
  <div class="card">
    <div class="card-label">Last Sign-in</div>
    <div class="card-value" style="font-size:20px;">
      <?= $user['last_seen'] ? e(fmt_time($user['last_seen'], 'M j')) : '—' ?>
    </div>
    <div class="card-sub"><?= $user['last_seen'] ? e(fmt_time($user['last_seen'], 'H:i')) : '' ?></div>
  </div>
</div>

<!-- 2FA Panel -->
<div class="panel">
  <div class="panel-header">
    <h2>🔐 Two-Factor Authentication</h2>
    <span class="badge <?= $isOn ? 'badge-live' : '' ?>">
      <?= $isOn ? 'active' : 'recommended' ?>
    </span>
  </div>

  <?php if ($isOn): ?>

    <!-- Already enabled view -->
    <div class="security-status">
      <div class="security-status-icon ok">🛡️</div>
      <div>
        <div style="font-size:15px;font-weight:700;color:var(--green);margin-bottom:4px;">
          2FA is protecting your account
        </div>
        <div class="text-dim" style="font-size:12.5px;line-height:1.6;">
          Every login requires a 6-digit code from your authenticator app.
          Enabled <?= $user['totp_verified_at'] ? e(fmt_time($user['totp_verified_at'], 'M j, Y')) : 'recently' ?>.
        </div>
      </div>
    </div>

    <div class="security-actions">
      <button type="button" class="btn" id="showRecoveryBtn">📄 View recovery codes</button>
      <button type="button" class="btn btn-danger" id="disable2faBtn">Disable 2FA</button>
    </div>

    <div id="recoveryWrap" style="display:none;margin-top:20px;">
      <div class="recovery-notice">
        ⚠️ <strong>Save these now.</strong> Each code can be used once if you lose your phone.
        They will never be shown again.
      </div>
      <div class="recovery-actions">
        <button type="button" class="btn btn-sm" id="copyRecoveryBtn">📋 Copy all</button>
        <button type="button" class="btn btn-sm" id="downloadRecoveryBtn">⬇️ Download .txt</button>
      </div>
      <div id="recoveryList" class="recovery-grid"></div>
    </div>

  <?php else: ?>

    <!-- Setup wizard -->
    <div id="setupIntro" class="security-intro">
      <div class="security-status-icon">🔒</div>
      <h3>Protect your account with 2FA</h3>
      <p class="text-dim">
        Add a second layer of security. You'll need an authenticator app like
        <strong>Google Authenticator</strong>, <strong>Authy</strong>, or
        <strong>1Password</strong>.
      </p>
      <button type="button" class="btn btn-primary" id="startSetupBtn" style="margin-top:14px;">
        Set up two-factor
      </button>
    </div>

    <div id="setupWizard" style="display:none;">
      <!-- Step 1: Scan QR -->
      <div class="setup-step">
        <div class="setup-step-num">1</div>
        <div class="setup-step-body">
          <h4>Scan this QR code</h4>
          <p class="text-dim" style="font-size:12.5px;">
            Open your authenticator app, tap <strong>+</strong> → <strong>Scan QR code</strong>,
            and point it at the image below.
          </p>

          <div class="qr-wrap">
            <div id="qrTarget" class="qr-code"></div>
          </div>

          <div class="setup-fallback">
            <div class="text-dim" style="font-size:11px;margin-bottom:6px;">
              Can't scan? Enter this key manually:
            </div>
            <div class="secret-display">
              <code id="secretText">—</code>
              <button type="button" class="btn btn-sm" id="copySecretBtn">Copy</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 2: Verify -->
      <div class="setup-step">
        <div class="setup-step-num">2</div>
        <div class="setup-step-body">
          <h4>Enter the code from your app</h4>
          <p class="text-dim" style="font-size:12.5px;">
            Type the current 6-digit code to confirm the setup.
          </p>

          <form id="verifyForm">
            <div class="code-input-row">
              <input type="text" id="verifyCode" class="code-input"
                     maxlength="6" inputmode="numeric" pattern="\d{6}"
                     placeholder="000000" autocomplete="off">
              <button type="submit" class="btn btn-primary" id="verifyBtn">
                Verify &amp; Enable
              </button>
            </div>
            <div id="verifyFeedback" class="order-feedback" style="display:none;margin-top:12px;"></div>
          </form>
        </div>
      </div>

      <!-- Step 3: Recovery codes (revealed after success) -->
      <div id="recoveryStep" class="setup-step" style="display:none;">
        <div class="setup-step-num">3</div>
        <div class="setup-step-body">
          <h4>Save your recovery codes</h4>
          <p class="text-dim" style="font-size:12.5px;">
            Each code can be used <strong>once</strong> if you lose your phone.
            Store them somewhere safe. They will not be shown again.
          </p>
          <div class="recovery-actions">
            <button type="button" class="btn btn-sm" id="copyRecoveryBtn2">📋 Copy all</button>
            <button type="button" class="btn btn-sm" id="downloadRecoveryBtn2">⬇️ Download .txt</button>
          </div>
          <div id="recoveryList2" class="recovery-grid"></div>
          <button type="button" class="btn btn-primary" id="finishBtn" style="margin-top:16px;">
            I've saved them — finish
          </button>
        </div>
      </div>
    </div>

  <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  window.SEC_API  = <?= json_encode(APP_URL) ?>;
  window.SEC_CSRF = <?= json_encode(csrf_token()) ?>;
  window.SEC_2FA_ENABLED = <?= json_encode($isOn) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/security.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>