<?php
require_once __DIR__ . '/includes/auth.php';

// Logged-in users go straight to the dashboard
if (is_logged_in()) redirect(APP_URL . '/dashboard.php');

$pageTitle = 'Welcome';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-container">
  <div class="auth-card" style="text-align:center;max-width:520px;">
    <h1 style="font-size:34px;margin-bottom:10px;"><?= e(APP_NAME) ?></h1>
    <p class="subtitle" style="font-size:15px;margin-bottom:28px;">
      AI-powered investment signals and paper trading.
      Simulate trading stocks &amp; crypto with real market data — no real money involved.
    </p>

    <div style="text-align:left;margin-bottom:28px;">
      <div class="info-row"><span>📊 Real-time market signals</span></div>
      <div class="info-row"><span>💼 Buy &amp; sell assets in a virtual portfolio</span></div>
      <div class="info-row"><span>📈 Live P&amp;L and holdings tracking</span></div>
      <div class="info-row"><span>🔐 Secure sessions and account management</span></div>
    </div>

    <a href="<?= e(APP_URL) ?>/register.php" class="btn btn-primary btn-block" style="display:block;text-decoration:none;margin-bottom:12px;">
      Create Free Account
    </a>
    <a href="<?= e(APP_URL) ?>/login.php" class="btn btn-block" style="display:block;text-decoration:none;">
      Sign In
    </a>

    <p class="auth-link" style="margin-top:24px;">
      ⚠️ Educational simulation. Virtual funds only.
    </p>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>