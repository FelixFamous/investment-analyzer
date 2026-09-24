<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'Strategy Builder';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/strategy.css">

<div id="strategiesPage">

  <div class="summary-grid" style="margin-bottom:20px;">
    <div class="card">
      <div class="card-label">Your Strategies</div>
      <div class="card-value" id="sbCount">0</div>
      <div class="card-sub">Saved by you</div>
    </div>
    <div class="card">
      <div class="card-label">Available Presets</div>
      <div class="card-value" id="sbPresetCount">5</div>
      <div class="card-sub">Ready to run</div>
    </div>
    <div class="card">
      <div class="card-label">Total Conditions</div>
      <div class="card-value" id="sbCondCount">0</div>
      <div class="card-sub">Across all strategies</div>
    </div>
    <div class="card">
      <div class="card-label">Status</div>
      <div class="card-value" style="font-size:16px;"><span class="badge badge-live">active</span></div>
      <div class="card-sub">No-code builder</div>
    </div>
  </div>

  <div class="main-grid" style="grid-template-columns:1fr 1fr;">

    <div class="panel">
      <div class="panel-header">
        <h2>⚙️ Strategy Builder</h2>
        <button type="button" class="btn btn-sm" id="sbNew">+ New</button>
      </div>

      <div class="form-group">
        <label for="sbName">Strategy name</label>
        <input type="text" id="sbName" placeholder="My RSI Bounce" maxlength="80">
      </div>

      <div class="form-group">
        <label for="sbDesc">Description</label>
        <input type="text" id="sbDesc" placeholder="Brief summary" maxlength="255">
      </div>

      <div class="sb-section">
        <div class="sb-section-header">
          <h3>Entry rules <span class="text-dim" style="font-weight:400;font-size:11px;">— when to open a position</span></h3>
          <div class="sb-logic-tabs" data-target="entry">
            <button type="button" class="sb-logic active" data-logic="AND">AND</button>
            <button type="button" class="sb-logic" data-logic="OR">OR</button>
          </div>
        </div>
        <div id="entryConditions" class="sb-conditions"></div>
        <button type="button" class="btn btn-sm" data-add-condition="entry" style="margin-top:8px;">+ Add condition</button>
      </div>

      <div class="sb-section">
        <div class="sb-section-header">
          <h3>Exit rules <span class="text-dim" style="font-weight:400;font-size:11px;">— when to close</span></h3>
          <div class="sb-logic-tabs" data-target="exit">
            <button type="button" class="sb-logic active" data-logic="OR">OR</button>
            <button type="button" class="sb-logic" data-logic="AND">AND</button>
          </div>
        </div>
        <div id="exitConditions" class="sb-conditions"></div>
        <button type="button" class="btn btn-sm" data-add-condition="exit" style="margin-top:8px;">+ Add condition</button>
      </div>

      <div style="display:flex;gap:8px;margin-top:16px;">
        <button type="button" class="btn btn-primary" id="sbSave" style="flex:1;">💾 Save Strategy</button>
        <button type="button" class="btn" id="sbClear">Clear</button>
      </div>

      <div id="sbFeedback" class="order-feedback" style="display:none;margin-top:12px;"></div>
    </div>

    <div>
      <div class="panel mb-16">
        <div class="panel-header">
          <h2>💾 Your Saved Strategies</h2>
          <span class="badge" id="sbSavedCount">0</span>
        </div>
        <div id="sbSavedList"><div class="empty-state">Loading…</div></div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <h2>📚 Preset Strategies</h2>
          <span class="badge">Ready to use</span>
        </div>
        <div id="sbPresetList"><div class="empty-state">Loading…</div></div>
      </div>
    </div>
  </div>

</div>

<script>
  window.SB_API  = <?= json_encode(APP_URL) ?>;
  window.SB_CSRF = <?= json_encode(csrf_token()) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/strategy-builder.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>