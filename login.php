<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) redirect(APP_URL . '/dashboard.php');

$error = '';
$oldEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $email    = post('email');
    $password = (string)($_POST['password'] ?? '');
    $oldEmail = $email;

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {
        $stmt = db()->prepare('SELECT id, password_hash, totp_enabled FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {

            // 2FA gate — if enabled, hold the session pending verification
            if ((int)$user['totp_enabled'] === 1) {
                session_regenerate_id(true);
                $_SESSION['pending_2fa_user_id'] = (int)$user['id'];
                $_SESSION['pending_2fa_at']      = time();
                redirect(APP_URL . '/login-2fa.php');
            }

            // No 2FA — complete login normally
            login_user((int)$user['id']);
            flash_set('success', 'Welcome back!');
            redirect(APP_URL . '/dashboard.php');

        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$pageTitle = 'Sign In';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-container">
  <div class="auth-card">
    <h1><?= e(APP_NAME) ?></h1>
    <p class="subtitle">Sign in to your dashboard</p>

    <?php if ($error): ?>
      <div class="flash flash-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= e($oldEmail) ?>" required autofocus>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>

    <p class="auth-link">
      Don't have an account? <a href="<?= e(APP_URL) ?>/register.php">Create one</a>
    </p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>