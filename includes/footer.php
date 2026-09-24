<?php
$u = current_user();
?>
  <footer class="footer">
    <div class="disclaimer">
      ⚠️ Educational simulation. Virtual funds only — no real money is handled.
      <?php if ($u): ?> · Times shown in <strong><?= e(tz_display_label()) ?></strong><?php endif; ?>
    </div>
    <div class="footer-links">
      <a href="<?= e(APP_URL) ?>/cookies.php">Cookies</a>
      <span>·</span>
      <a href="<?= e(APP_URL) ?>/privacy.php">Privacy</a>
      <span>·</span>
      <a href="<?= e(APP_URL) ?>/terms.php">Terms</a>
    </div>
  </footer>

<?php if ($u): ?></div><!-- /.main -->
<?php else: ?></div><!-- /.app -->
<?php endif; ?>

<?php if ($u): ?>
<button id="gchatFab" class="gchat-fab" title="Global chat">🌐 <span>Global Chat</span></button>
<div id="gchatPanel" class="gchat-panel">
  <div class="gchat-header">
    <div>
      <div class="gchat-title">Global Chat</div>
      <div class="gchat-sub">Everyone using AlphaEdge right now</div>
    </div>
    <button id="gchatClose" class="gchat-close" aria-label="Close">✕</button>
  </div>
  <div id="gchatMessages" class="gchat-messages"><div class="gchat-empty">Loading messages…</div></div>
  <form id="gchatForm" class="gchat-form">
    <input type="text" id="gchatSymbol" placeholder="SYM" maxlength="15">
    <input type="text" id="gchatBody" placeholder="Say something…" maxlength="500" autocomplete="off">
    <button type="submit" id="gchatSend">Send</button>
  </form>
</div>
<?php endif; ?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/chatbot.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/global-chat.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/legal.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/watchlist.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/export.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/leaderboard.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/calendar.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/recurring.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/referral.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/microstructure.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/drawings.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/intelligence.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/strategy.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/calculator.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/multichart.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/riskmonitor.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/academy.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/glass.css">

<script src="<?= e(APP_URL) ?>/assets/js/live-prices.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/watchlist.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/dashboard.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/news-widget.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/global-chat.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/sidebar.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/export.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/leaderboard.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/calendar.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/recurring.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/microstructure.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/journal.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/analytics.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/correlation.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/calculator.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/scanner.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/multichart.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/riskmonitor.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/apikeys.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/academy.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/animations.js" defer></script>

<?php include __DIR__ . '/chatbot.php'; ?>
<?php include __DIR__ . '/cookie-banner.php'; ?>
</body>
</html>