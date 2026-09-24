<?php
require_once __DIR__ . '/includes/auth.php';
$user = require_login();

$pageTitle = 'API Keys';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/riskmonitor.css">

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Active Keys</div>
    <div class="card-value" id="akActive">—</div>
    <div class="card-sub">Read + trade access</div>
  </div>
  <div class="card">
    <div class="card-label">Revoked</div>
    <div class="card-value" id="akRevoked" style="font-size:20px;">—</div>
    <div class="card-sub">No longer usable</div>
  </div>
  <div class="card">
    <div class="card-label">Endpoint</div>
    <div class="card-value" style="font-size:14px;font-family:var(--mono);" id="akEndpoint">—</div>
    <div class="card-sub">Base API URL</div>
  </div>
  <div class="card">
    <div class="card-label">Security</div>
    <div class="card-value" style="font-size:16px;">
      <span class="badge badge-live">encrypted</span>
    </div>
    <div class="card-sub">Hashed with bcrypt</div>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header"><h2>🔑 Create New Key</h2></div>

    <form id="akForm">
      <div class="form-group">
        <label for="akLabel">Label</label>
        <input type="text" id="akLabel" placeholder="e.g. Trading bot" maxlength="60" required>
      </div>

      <div class="form-group">
        <label for="akPerms">Permissions</label>
        <select id="akPerms">
          <option value="read">Read only</option>
          <option value="trade">Read + Trade</option>
        </select>
        <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
          Trading keys can place orders on your behalf. Never share them.
        </small>
      </div>

      <button type="submit" class="btn btn-primary btn-block" id="akCreateBtn">🔑 Generate API Key</button>
      <div id="akFeedback" class="order-feedback" style="display:none;margin-top:12px;"></div>
    </form>

    <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
      <div class="sidebar-label" style="padding:0 0 8px 0;">Example usage</div>
      <pre style="background:var(--bg-2);padding:12px;border-radius:10px;font-family:var(--mono);font-size:11px;color:var(--text-dim);overflow-x:auto;line-height:1.6;">curl -H "Authorization: Bearer ae_xxxxx_yyyy" \
     <?= e(APP_URL) ?>/api/portfolio</pre>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>📋 Your Keys</h2>
      <span class="badge" id="akCount">0</span>
    </div>
    <div id="akList"><div class="empty-state">Loading…</div></div>
  </div>
</div>

<script>
  window.AK_API  = <?= json_encode(APP_URL) ?>;
  window.AK_CSRF = <?= json_encode(csrf_token()) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/apikeys.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>