<?php
/**
 * Admin page footer.
 */
?>
  <footer class="footer">
    <div class="disclaimer">
      🛡️ Admin Console · AlphaEdge · All actions are logged.
      <?php if (current_user()): ?>
        · Times in <strong><?= e(tz_display_label()) ?></strong>
      <?php endif; ?>
    </div>
  </footer>
</div><!-- /.main -->

<script src="<?= e(APP_URL) ?>/assets/js/sidebar.js" defer></script>
<script>
// Live clock in admin topbar
(function(){
  const el = document.getElementById('adminClock');
  if (!el) return;
  function tick(){
    const d = new Date();
    el.textContent = d.toLocaleString('en-US', {
      month: 'short', day: 'numeric',
      hour: '2-digit', minute: '2-digit', hour12: false
    }) + ' ' + (window.ALPHAEDGE.timezone || '');
  }
  tick();
  setInterval(tick, 30000);
})();
</script>
</body>
</html>