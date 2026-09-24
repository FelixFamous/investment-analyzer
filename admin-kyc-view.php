<?php
require_once __DIR__ . '/includes/auth.php';

$admin = require_login();
if ((int)$admin['is_admin'] !== 1) {
    flash_set('error', 'Admin access required.');
    redirect(APP_URL . '/dashboard.php');
}

$targetId = (int)($_GET['id'] ?? 0);
if ($targetId <= 0) {
    flash_set('error', 'Invalid submission.');
    redirect(APP_URL . '/admin-kyc.php');
}

$stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$targetId]);
$u = $stmt->fetch();

if (!$u) {
    flash_set('error', 'User not found.');
    redirect(APP_URL . '/admin-kyc.php');
}

if (!in_array($u['kyc_status'], ['pending', 'rejected', 'approved'], true)) {
    flash_set('error', 'This user has no KYC submission to review.');
    redirect(APP_URL . '/admin-kyc.php');
}

$reviewer = null;
if (!empty($u['kyc_reviewer_id'])) {
    $stmt = db()->prepare('SELECT username FROM users WHERE id = ?');
    $stmt->execute([$u['kyc_reviewer_id']]);
    $reviewer = $stmt->fetchColumn();
}

$stmt = db()->prepare('SELECT COUNT(*) FROM trades WHERE user_id = ?');
$stmt->execute([$targetId]);
$tradeCount = (int)$stmt->fetchColumn();

$stmt = db()->prepare('SELECT COUNT(*) FROM holdings WHERE user_id = ?');
$stmt->execute([$targetId]);
$holdingCount = (int)$stmt->fetchColumn();

$DOC_TYPES = [
    'passport'         => 'International Passport',
    'national_id'      => 'National ID Card',
    'drivers_license'  => "Driver's License",
    'residence_permit' => 'Residence Permit / BRP / Visa',
];

$POA_TYPES = [
    'utility_bill'   => 'Utility Bill',
    'bank_statement' => 'Bank Statement',
    'council_tax'    => 'Council Tax / Government Letter',
];

/**
 * Render a document preview block. Handles images, PDFs, and missing files.
 */
function render_doc(string $filename, string $label, string $accent = 'var(--accent)'): void
{
    $path = __DIR__ . '/uploads/kyc/' . $filename;
    $url  = APP_URL . '/uploads/kyc/' . $filename;

    echo '<div class="kyc-doc-card">';
    echo '<div class="kyc-doc-label">' . htmlspecialchars($label) . '</div>';

    if (empty($filename) || !is_file($path)) {
        echo '<div class="kyc-doc-empty">⚠️ Not uploaded</div>';
        echo '</div>';
        return;
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
        echo '<a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener">';
        echo '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($label) . '" class="kyc-doc-img">';
        echo '</a>';
    } else {
        echo '<a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener" class="kyc-doc-pdf">';
        echo '📄 Open ' . strtoupper($ext) . ' document';
        echo '</a>';
    }

    $size = filesize($path);
    $sizeStr = $size < 1024 ? $size . ' B'
             : ($size < 1048576 ? round($size / 1024, 1) . ' KB'
             : round($size / 1048576, 2) . ' MB');

    echo '<div class="kyc-doc-meta">' . htmlspecialchars($filename) . ' · ' . $sizeStr . '</div>';
    echo '</div>';
}

$pageTitle = 'KYC Review · ' . $u['username'];
require __DIR__ . '/includes/admin-header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/kyc-review.css">

<div style="margin-bottom:16px;">
  <a href="<?= e(APP_URL) ?>/admin-kyc.php" class="btn btn-sm">← Back to queue</a>
</div>

<!-- Header -->
<div class="panel mb-16" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
  <div class="avatar" style="width:56px;height:56px;font-size:20px;">
    <?= e(strtoupper(substr($u['username'], 0, 1))) ?>
  </div>
  <div style="flex:1;min-width:200px;">
    <div style="font-size:20px;font-weight:800;letter-spacing:-0.3px;">
      <?= e($u['username']) ?>
    </div>
    <div class="text-dim" style="font-size:13px;"><?= e($u['email']) ?></div>
    <div class="text-dim" style="font-size:11px;margin-top:4px;font-family:var(--mono);">
      User #<?= (int)$u['id'] ?> · joined <?= e(fmt_time($u['created_at'], 'M j, Y')) ?>
    </div>
  </div>
  <span class="kyc-pill <?= e($u['kyc_status']) ?>">
    <?= e(strtoupper($u['kyc_status'])) ?>
  </span>
</div>

<!-- Cross-check grid: what we asked for vs what we got -->
<div class="panel mb-16">
  <div class="panel-header">
    <h2>🔎 Verification Checklist</h2>
    <span class="badge">Cross-check required documents</span>
  </div>

  <div class="kyc-checklist-grid">
    <?php
      $checkItems = [
        ['Personal info',           !empty($u['kyc_full_name']) && !empty($u['kyc_dob'])],
        ['Nationality',             !empty($u['kyc_country'])],
        ['Identity document type',  !empty($u['kyc_doc_type'])],
        ['ID front image',          !empty($u['kyc_doc_front']) && is_file(__DIR__.'/uploads/kyc/'.$u['kyc_doc_front'])],
        ['ID back image',           !empty($u['kyc_doc_back'])  && is_file(__DIR__.'/uploads/kyc/'.$u['kyc_doc_back'])],
        ['Tax ID (TIN)',            !empty($u['kyc_tax_id'])],
        ['Tax residence',           !empty($u['kyc_tax_country'])],
        ['Proof of address',        !empty($u['kyc_poa_file'])  && is_file(__DIR__.'/uploads/kyc/'.$u['kyc_poa_file'])],
        ['Bank account',            !empty($u['kyc_bank_account'])],
        ['Mobile number',           !empty($u['kyc_mobile'])],
        ['Live selfie',             !empty($u['kyc_selfie'])    && is_file(__DIR__.'/uploads/kyc/'.$u['kyc_selfie'])],
      ];
      foreach ($checkItems as [$label, $ok]):
    ?>
      <div class="kyc-check-item <?= $ok ? 'ok' : 'miss' ?>">
        <span class="kyc-check-icon"><?= $ok ? '✓' : '✕' ?></span>
        <span><?= e($label) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Personal details + account snapshot -->
<div class="main-grid" style="grid-template-columns:1fr 1fr;gap:16px;">
  <div class="panel">
    <div class="panel-header"><h2>📋 Personal Information</h2></div>
    <table>
      <tr><th style="width:160px;">Full legal name</th><td style="font-weight:600;"><?= e($u['kyc_full_name'] ?? '—') ?></td></tr>
      <tr>
        <th>Date of birth</th>
        <td style="font-family:var(--mono);">
          <?= e($u['kyc_dob'] ?? '—') ?>
          <?php if (!empty($u['kyc_dob'])): ?>
            <span class="text-dim" style="font-size:12px;font-weight:400;">
              (age <?= (int)((time() - strtotime($u['kyc_dob'])) / 31536000) ?>)
            </span>
          <?php endif; ?>
        </td>
      </tr>
      <tr><th>Country of citizenship</th><td><?= e($u['kyc_country'] ?? '—') ?></td></tr>
      <tr><th>Tax ID / TIN</th><td style="font-family:var(--mono);"><?= e($u['kyc_tax_id'] ?? '—') ?></td></tr>
      <tr><th>Tax residence</th><td><?= e($u['kyc_tax_country'] ?? '—') ?></td></tr>
      <tr><th>Mobile number</th><td style="font-family:var(--mono);"><?= e($u['kyc_mobile'] ?? '—') ?></td></tr>
      <tr><th>Email</th><td><?= e($u['email']) ?></td></tr>
      <tr><th>Submitted</th><td style="font-family:var(--mono);font-size:12px;"><?= e(fmt_time($u['kyc_submitted_at'] ?? '', 'M j, Y · H:i')) ?></td></tr>
      <?php if (!empty($u['kyc_reviewed_at'])): ?>
        <tr>
          <th>Reviewed</th>
          <td style="font-family:var(--mono);font-size:12px;">
            <?= e(fmt_time($u['kyc_reviewed_at'], 'M j, Y · H:i')) ?>
            <?php if ($reviewer): ?>
              <span class="text-dim">by <strong style="color:#93a5ff;"><?= e($reviewer) ?></strong></span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endif; ?>
      <?php if ($u['kyc_status'] === 'rejected' && !empty($u['kyc_rejection_reason'])): ?>
        <tr><th>Rejection reason</th><td style="color:var(--red);"><?= e($u['kyc_rejection_reason']) ?></td></tr>
      <?php endif; ?>
    </table>
  </div>

  <div class="panel">
    <div class="panel-header"><h2>🏦 Bank Details</h2></div>
    <table>
      <tr><th style="width:160px;">Account holder</th><td style="font-weight:600;"><?= e($u['kyc_bank_holder'] ?? '—') ?></td></tr>
      <tr><th>Bank name</th><td><?= e($u['kyc_bank_name'] ?? '—') ?></td></tr>
      <tr><th>Account / IBAN</th><td style="font-family:var(--mono);letter-spacing:0.4px;"><?= e($u['kyc_bank_account'] ?? '—') ?></td></tr>
      <tr><th>Bank country</th><td><?= e($u['kyc_bank_country'] ?? '—') ?></td></tr>
    </table>

    <?php if (($u['kyc_bank_holder'] ?? '') && ($u['kyc_full_name'] ?? '')): ?>
      <?php
        $nameMatch = strcasecmp(trim($u['kyc_bank_holder']), trim($u['kyc_full_name'])) === 0;
      ?>
      <div class="kyc-notice" style="margin-top:14px;<?= $nameMatch ? 'background:rgba(14,203,129,0.06);border-color:rgba(14,203,129,0.3);' : '' ?>">
        <?php if ($nameMatch): ?>
          <strong style="color:var(--green);">✓ Name match</strong> — bank holder name matches the submitted full legal name.
        <?php else: ?>
          <strong>⚠ Name mismatch</strong> — bank holder name differs from the submitted full legal name. Confirm the user has authority over this account.
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="panel-header" style="margin-top:22px;"><h2>📊 Account Snapshot</h2></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div class="card" style="padding:12px 14px;">
        <div class="card-label">Cash</div>
        <div style="font-weight:700;font-family:var(--mono);font-size:14px;"><?= e(usd((float)$u['cash_balance'])) ?></div>
      </div>
      <div class="card" style="padding:12px 14px;">
        <div class="card-label">Holdings</div>
        <div style="font-weight:700;font-family:var(--mono);font-size:14px;"><?= $holdingCount ?> pos</div>
      </div>
      <div class="card" style="padding:12px 14px;">
        <div class="card-label">Trades</div>
        <div style="font-weight:700;font-family:var(--mono);font-size:14px;"><?= $tradeCount ?></div>
      </div>
      <div class="card" style="padding:12px 14px;">
        <div class="card-label">Last seen</div>
        <div style="font-weight:700;font-size:12px;"><?= $u['last_seen'] ? e(fmt_time($u['last_seen'], 'M j, H:i')) : '—' ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Identity document -->
<div class="panel mb-16">
  <div class="panel-header">
    <h2>🪪 Identity Document</h2>
    <span class="badge"><?= e($DOC_TYPES[$u['kyc_doc_type']] ?? '—') ?></span>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
    <div>
      <div class="text-dim" style="font-size:11px;text-transform:uppercase;letter-spacing:0.6px;font-weight:700;">Document number</div>
      <div style="font-family:var(--mono);font-size:14px;margin-top:4px;"><?= e($u['kyc_id_number'] ?? '—') ?></div>
    </div>
    <div>
      <div class="text-dim" style="font-size:11px;text-transform:uppercase;letter-spacing:0.6px;font-weight:700;">Type</div>
      <div style="font-size:14px;margin-top:4px;"><?= e($DOC_TYPES[$u['kyc_doc_type']] ?? '—') ?></div>
    </div>
  </div>

  <div class="kyc-docs-row">
    <?php render_doc($u['kyc_doc_front'] ?? '', 'Front'); ?>
    <?php if (in_array($u['kyc_doc_type'] ?? '', ['national_id', 'drivers_license'], true)): ?>
      <?php render_doc($u['kyc_doc_back'] ?? '', 'Back'); ?>
    <?php endif; ?>
  </div>
</div>

<!-- Proof of address -->
<div class="panel mb-16">
  <div class="panel-header">
    <h2>🏠 Proof of Address</h2>
    <span class="badge"><?= e($POA_TYPES[$u['kyc_poa_type']] ?? '—') ?></span>
  </div>

  <div class="kyc-docs-row">
    <?php render_doc($u['kyc_poa_file'] ?? '', $POA_TYPES[$u['kyc_poa_type']] ?? 'Proof of Address'); ?>
  </div>
</div>

<!-- Live selfie -->
<div class="panel mb-16">
  <div class="panel-header">
    <h2>🤳 Live Selfie (captured in browser)</h2>
    <span class="badge">Face verification</span>
  </div>

  <div class="kyc-docs-row">
    <?php render_doc($u['kyc_selfie'] ?? '', 'Live selfie'); ?>
  </div>
</div>

<!-- Decision -->
<?php if (in_array($u['kyc_status'], ['pending', 'rejected'], true)): ?>
<div class="panel" style="border-color:rgba(91,124,250,0.3);background:linear-gradient(135deg,rgba(91,124,250,0.05),transparent);">
  <div class="panel-header">
    <h2>⚖️ Decision</h2>
    <span class="badge">Review required</span>
  </div>

  <p class="text-dim" style="font-size:13px;margin-bottom:16px;line-height:1.7;">
    Confirm the submitted information matches the images above. Approving unlocks deposits and full trading features.
  </p>

  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <button class="btn btn-primary" id="approveBtn"
            data-user="<?= (int)$u['id'] ?>" data-name="<?= e($u['username']) ?>">
      ✅ Approve KYC
    </button>
    <button class="btn btn-danger" id="rejectBtn"
            data-user="<?= (int)$u['id'] ?>" data-name="<?= e($u['username']) ?>">
      ❌ Reject with reason
    </button>
  </div>
</div>
<?php else: ?>
<div class="panel" style="border-color:rgba(14,203,129,0.3);background:linear-gradient(135deg,rgba(14,203,129,0.05),transparent);">
  <div class="panel-header"><h2>✅ Already approved</h2></div>
  <p class="text-dim" style="font-size:13px;">
    Verified on <?= e(fmt_time($u['kyc_reviewed_at'] ?? '', 'M j, Y')) ?>.
  </p>
</div>
<?php endif; ?>

<script>
(function () {
  const API  = window.ALPHAEDGE.api;
  const CSRF = window.ALPHAEDGE.csrf;

  function toast(msg, kind) {
    let el = document.getElementById('ae-toast');
    if (!el) {
      el = document.createElement('div');
      el.id = 'ae-toast';
      el.className = 'toast';
      document.body.appendChild(el);
    }
    el.textContent = msg;
    el.className = 'toast show' + (kind ? ' toast-' + kind : '');
    clearTimeout(el._t);
    el._t = setTimeout(() => el.className = 'toast', 3000);
  }

  async function review(userId, action, reason) {
    const body = new URLSearchParams({ user_id: userId, action, reason: reason || '', csrf: CSRF });
    const r = await fetch(API + '/api/kyc-review.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
    });
    const json = await r.json();
    if (!r.ok) throw new Error(json.error || 'Failed');
    return json;
  }

  const approveBtn = document.getElementById('approveBtn');
  if (approveBtn) {
    approveBtn.addEventListener('click', async () => {
      if (!confirm(`Approve KYC for "${approveBtn.dataset.name}"?`)) return;
      approveBtn.disabled = true; approveBtn.textContent = 'Approving…';
      try {
        const r = await review(approveBtn.dataset.user, 'approve');
        toast(r.message || 'Approved', 'success');
        setTimeout(() => location.href = '<?= e(APP_URL) ?>/admin-kyc.php', 800);
      } catch (err) {
        toast(err.message, 'error');
        approveBtn.disabled = false; approveBtn.textContent = '✅ Approve KYC';
      }
    });
  }

  const rejectBtn = document.getElementById('rejectBtn');
  if (rejectBtn) {
    rejectBtn.addEventListener('click', async () => {
      const reason = prompt(`Rejection reason for "${rejectBtn.dataset.name}":`, '');
      if (reason === null) return;
      if (!reason.trim()) { alert('Please enter a reason.'); return; }
      rejectBtn.disabled = true; rejectBtn.textContent = 'Rejecting…';
      try {
        const r = await review(rejectBtn.dataset.user, 'reject', reason);
        toast(r.message || 'Rejected', 'success');
        setTimeout(() => location.href = '<?= e(APP_URL) ?>/admin-kyc.php', 800);
      } catch (err) {
        toast(err.message, 'error');
        rejectBtn.disabled = false; rejectBtn.textContent = '❌ Reject with reason';
      }
    });
  }
})();
</script>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>