<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

// Check if user made the required deposit
$stmt = db()->prepare('SELECT COUNT(*) FROM cash_transactions WHERE user_id = ? AND type = "DEPOSIT" AND amount >= 50');
$stmt->execute([$user['id']]);
$hasDeposit = (int)$stmt->fetchColumn() > 0;

$pageTitle = 'Trading Academy';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/academy.css">

<?php if (!$hasDeposit): ?>
  <!-- Locked screen -->
  <div class="academy-locked">
    <div class="academy-lock-icon">🔒</div>
    <h1>Academy Locked</h1>
    <p>
      The AlphaEdge Academy is a complete learning library — 16 courses, 48 lessons, badges, and a
      strategies masterclass. Access is unlocked once you make your first demo deposit of at least <strong>$50</strong>.
    </p>
    <p class="academy-lock-hint">
      This is a simulated deposit. No real money is ever handled.
    </p>
    <a href="<?= e(APP_URL) ?>/dashboard.php" class="btn btn-primary" style="margin-top:10px;">
      💰 Make a demo deposit to unlock
    </a>
  </div>
<?php else: ?>

  <div class="summary-grid" style="margin-bottom:20px;">
    <div class="card">
      <div class="card-label">Lessons Completed</div>
      <div class="card-value" id="acDone">—</div>
      <div class="card-sub">Out of 48</div>
    </div>
    <div class="card">
      <div class="card-label">Progress</div>
      <div class="card-value" id="acPct">—</div>
      <div class="card-sub">Academy completion</div>
    </div>
    <div class="card">
      <div class="card-label">Badges Earned</div>
      <div class="card-value" id="acBadges">—</div>
      <div class="card-sub">Achievements unlocked</div>
    </div>
    <div class="card">
      <div class="card-label">Current Stage</div>
      <div class="card-value" id="acStage" style="font-size:18px;">—</div>
      <div class="card-sub">Your level</div>
    </div>
  </div>

  <div class="academy-stages" id="academyStages"></div>

<?php endif; ?>

<script>
  window.AC_API = <?= json_encode(APP_URL) ?>;
  window.AC_HAS_DEPOSIT = <?= json_encode($hasDeposit) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/academy.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>