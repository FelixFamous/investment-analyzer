<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/referral.php';

if (is_logged_in()) redirect(APP_URL . '/dashboard.php');

$errors = [];
$old    = ['username' => '', 'email' => '', 'timezone' => 'Africa/Lagos', 'agree' => false, 'ref_code' => ''];

// Capture referral code from URL
$incomingRef = strtoupper(trim((string)($_GET['ref'] ?? '')));
if ($incomingRef !== '') $old['ref_code'] = $incomingRef;

// Validate incoming code (if any)
$referrerInfo = null;
if ($incomingRef !== '') {
    $refId = find_user_by_referral_code($incomingRef);
    if ($refId) {
        $stmt = db()->prepare('SELECT username FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$refId]);
        $referrerInfo = $stmt->fetch();
    } else {
        $old['ref_code'] = '';
    }
}

$TIMEZONES = [
    'Africa/Lagos' => 'Nigeria (WAT · Lagos)',
    'Africa/Accra' => 'Ghana (GMT · Accra)',
    'Africa/Nairobi' => 'Kenya (EAT · Nairobi)',
    'Africa/Johannesburg' => 'South Africa (SAST · Johannesburg)',
    'Africa/Cairo' => 'Egypt (EET · Cairo)',
    'Africa/Casablanca' => 'Morocco (WET · Casablanca)',
    'Europe/London' => 'United Kingdom (GMT/BST · London)',
    'Europe/Paris' => 'France / Germany (CET · Paris)',
    'Europe/Moscow' => 'Russia (MSK · Moscow)',
    'America/New_York' => 'USA East (EST/EDT · New York)',
    'America/Chicago' => 'USA Central (CST · Chicago)',
    'America/Denver' => 'USA Mountain (MST · Denver)',
    'America/Los_Angeles' => 'USA Pacific (PST · Los Angeles)',
    'America/Sao_Paulo' => 'Brazil (BRT · São Paulo)',
    'Asia/Dubai' => 'UAE (GST · Dubai)',
    'Asia/Karachi' => 'Pakistan (PKT · Karachi)',
    'Asia/Kolkata' => 'India (IST · Kolkata)',
    'Asia/Dhaka' => 'Bangladesh (BST · Dhaka)',
    'Asia/Bangkok' => 'Thailand / Vietnam (ICT · Bangkok)',
    'Asia/Shanghai' => 'China (CST · Shanghai)',
    'Asia/Singapore' => 'Singapore (SGT)',
    'Asia/Tokyo' => 'Japan (JST · Tokyo)',
    'Asia/Seoul' => 'South Korea (KST · Seoul)',
    'Australia/Sydney' => 'Australia East (AEDT · Sydney)',
    'Pacific/Auckland' => 'New Zealand (NZDT · Auckland)',
    'UTC' => 'UTC (Coordinated Universal Time)',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $username = post('username');
    $email    = post('email');
    $timezone = post('timezone');
    $password = (string)($_POST['password'] ?? '');
    $confirm  = (string)($_POST['password_confirm'] ?? '');
    $agree    = isset($_POST['agree_terms']) && $_POST['agree_terms'] === '1';
    $refCode  = strtoupper(trim((string)($_POST['ref_code'] ?? '')));

    $old['username'] = $username;
    $old['email']    = $email;
    $old['timezone'] = $timezone;
    $old['agree']    = $agree;
    $old['ref_code'] = $refCode;

    if ($username === '' || !preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors[] = 'Username must be 3–30 characters: letters, numbers, underscore only.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (!isset($TIMEZONES[$timezone])) {
        $errors[] = 'Please select a country / timezone.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }
    if (!$agree) {
        $errors[] = 'You must accept the Terms of Service, Privacy Policy, and Cookie Policy to create an account.';
    }

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'That username or email is already registered.';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Generate this user's own referral code before insert
        $stmt = db()->prepare('SELECT MAX(id) FROM users');
        $stmt->execute();
        $nextId = ((int)$stmt->fetchColumn()) + 1;
        $myCode = generate_referral_code($nextId, $username);

        $stmt = db()->prepare(
            'INSERT INTO users (username, email, password_hash, cash_balance, timezone, referral_code)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$username, $email, $hash, STARTING_BALANCE, $timezone, $myCode]);
        $newId = (int)db()->lastInsertId();

        // Apply the referral (if valid and not the same user)
        $bonusApplied = false;
        if ($refCode !== '' && strtoupper($refCode) !== strtoupper($myCode)) {
            $bonusApplied = apply_referral($newId, $refCode);
        }

        login_user($newId);

        if ($bonusApplied) {
            flash_set('success',
                'Welcome to ' . APP_NAME . '! You have ' . usd((float)STARTING_BALANCE) . ' plus a '
                . usd(REFERRAL_BONUS_REFERRED) . ' referral bonus.'
            );
        } else {
            flash_set('success',
                'Welcome to ' . APP_NAME . '! Your demo account is ready with '
                . usd((float)STARTING_BALANCE) . ' virtual funds.'
            );
        }
        redirect(APP_URL . '/dashboard.php');
    }
}

$pageTitle = 'Create Account';
require __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/legal.css">

<div class="auth-container">
  <div class="auth-card">
    <h1><?= e(APP_NAME) ?></h1>
    <p class="subtitle">Create your demo trading account</p>

    <?php if ($referrerInfo): ?>
      <div class="referral-banner">
        <div class="referral-banner-icon">🎁</div>
        <div>
          <strong>You were invited by @<?= e($referrerInfo['username']) ?></strong>
          <div>You'll receive a <strong><?= e(usd(REFERRAL_BONUS_REFERRED)) ?></strong> welcome bonus on signup</div>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="flash flash-error">
        <?php foreach ($errors as $e): ?>
          <div><?= e($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" novalidate id="registerForm">
      <?= csrf_field() ?>
      <input type="hidden" name="ref_code" value="<?= e($old['ref_code']) ?>">

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               value="<?= e($old['username']) ?>" required autofocus>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= e($old['email']) ?>" required>
      </div>

      <div class="form-group">
        <label for="timezone">Country / Timezone</label>
        <select id="timezone" name="timezone" required>
          <?php foreach ($TIMEZONES as $tz => $label): ?>
            <option value="<?= e($tz) ?>" <?= $old['timezone'] === $tz ? 'selected' : '' ?>>
              <?= e($label) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="8" required>
      </div>

      <div class="form-group">
        <label for="password_confirm">Confirm password</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
      </div>

      <div class="terms-checkbox-wrap" id="termsWrap">
        <label class="terms-checkbox">
          <input type="checkbox" id="agree_terms" name="agree_terms" value="1" <?= $old['agree'] ? 'checked' : '' ?>>
          <span class="terms-checkmark"></span>
          <span class="terms-text">
            I have read and agree to the
            <a href="<?= e(APP_URL) ?>/terms.php" target="_blank" rel="noopener">Terms of Service</a>,
            <a href="<?= e(APP_URL) ?>/privacy.php" target="_blank" rel="noopener">Privacy Policy</a>, and
            <a href="<?= e(APP_URL) ?>/cookies.php" target="_blank" rel="noopener">Cookie Policy</a>.
          </span>
        </label>
      </div>

      <div class="risk-acknowledgment">
        <strong>⚠️ Risk acknowledgment:</strong> Trading involves substantial risk of loss.
        You understand that you alone are responsible for your trading decisions and that
        AlphaEdge bears no liability for financial outcomes. This is a demo platform with
        virtual funds only.
      </div>

      <button type="submit" class="btn btn-primary btn-block" id="registerBtn">Create Account</button>
    </form>

    <p class="auth-link">
      Already have an account? <a href="<?= e(APP_URL) ?>/login.php">Sign in</a>
    </p>
  </div>
</div>

<script>
(function () {
  const form = document.getElementById('registerForm');
  const checkbox = document.getElementById('agree_terms');
  const wrap = document.getElementById('termsWrap');
  if (!form || !checkbox) return;

  form.addEventListener('submit', (e) => {
    if (!checkbox.checked) {
      e.preventDefault();
      wrap.classList.add('terms-shake');
      wrap.scrollIntoView({ behavior: 'smooth', block: 'center' });
      setTimeout(() => wrap.classList.remove('terms-shake'), 600);
    }
  });

  checkbox.addEventListener('change', () => {
    if (checkbox.checked) wrap.classList.remove('terms-shake');
  });
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>