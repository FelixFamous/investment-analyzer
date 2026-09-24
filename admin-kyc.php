<?php
require_once __DIR__ . '/includes/auth.php';

$user = require_login();
if ((int)$user['is_admin'] !== 1) {
    flash_set('error', 'Admin access required.');
    redirect(APP_URL . '/dashboard.php');
}

$pending  = db()->query("SELECT * FROM users WHERE kyc_status = 'pending'  ORDER BY kyc_submitted_at ASC")->fetchAll();
$approved = db()->query("SELECT * FROM users WHERE kyc_status = 'approved' ORDER BY kyc_reviewed_at DESC LIMIT 25")->fetchAll();
$rejected = db()->query("SELECT * FROM users WHERE kyc_status = 'rejected' ORDER BY kyc_reviewed_at DESC LIMIT 25")->fetchAll();

$ID_TYPES = [
    'passport'        => 'International Passport',
    'drivers_license' => "Driver's License",
    'national_id'     => 'National ID Card',
];

$pageTitle = 'KYC Review';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Awaiting Review</div>
    <div class="card-value" style="color:<?= count($pending) > 0 ? 'var(--accent)' : 'var(--text)' ?>;">
      <?= count($pending) ?>
    </div>
    <div class="card-sub">Pending submissions</div>
  </div>
  <div class="card">
    <div class="card-label">Approved</div>
    <div class="card-value positive"><?= count($approved) ?></div>
    <div class="card-sub">Recent approvals</div>
  </div>
  <div class="card">
    <div class="card-label">Rejected</div>
    <div class="card-value" style="color:var(--red);"><?= count($rejected) ?></div>
    <div class="card-sub">Recent rejections</div>
  </div>
</div>

<div class="panel mb-16">
  <div class="panel-header">
    <h2>⏳ Pending Submissions</h2>
    <span class="badge <?= count($pending) ? 'badge-live' : '' ?>"><?= count($pending) ?></span>
  </div>

  <?php if (!$pending): ?>
    <div class="empty-state">No pending submissions. All caught up.</div>
  <?php else: ?>
    <?php foreach ($pending as $p): ?>
      <div style="background:var(--panel-2);border:1px solid var(--border);border-radius:14px;padding:16px;margin-bottom:12px;display:flex;align-items:center;gap:18px;flex-wrap:wrap;">

        <!-- Selfie thumbnail -->
        <div style="flex-shrink:0;">
          <?php if (!empty($p['kyc_selfie']) && is_file(__DIR__ . '/uploads/kyc/' . $p['kyc_selfie'])): ?>
            <img src="<?= e(APP_URL) ?>/uploads/kyc/<?= e($p['kyc_selfie']) ?>"
                 alt="Selfie"
                 style="width:56px;height:56px;object-fit:cover;border-radius:50%;border:2px solid var(--accent);">
          <?php else: ?>
            <div style="width:56px;height:56px;border-radius:50%;background:var(--panel);border:2px dashed var(--border-2);display:flex;align-items:center;justify-content:center;font-size:20px;color:var(--text-faint);">
              ?
            </div>
          <?php endif; ?>
        </div>

        <!-- User info -->
        <div style="flex:1;min-width:200px;">
          <div style="font-weight:700;font-size:15px;margin-bottom:2px;"><?= e($p['username']) ?></div>
          <div class="text-dim" style="font-size:12px;margin-bottom:6px;"><?= e($p['email']) ?></div>
          <div style="font-size:12px;color:var(--text-dim);display:flex;gap:14px;flex-wrap:wrap;">
            <span><strong style="color:var(--text);">Name:</strong> <?= e($p['kyc_full_name'] ?? '—') ?></span>
            <span><strong style="color:var(--text);">Country:</strong> <?= e($p['kyc_country'] ?? '—') ?></span>
            <span><strong style="color:var(--text);">ID:</strong> <?= e($ID_TYPES[$p['kyc_id_type']] ?? '—') ?></span>
          </div>
        </div>

        <!-- Submitted timestamp -->
        <div style="text-align:right;min-width:140px;">
          <div class="text-dim" style="font-size:10px;text-transform:uppercase;letter-spacing:0.6px;font-weight:700;">Submitted</div>
          <div style="font-family:var(--mono);font-size:12px;margin-top:2px;">
            <?= e(fmt_time($p['kyc_submitted_at'] ?? '', 'M j, H:i')) ?>
          </div>
        </div>

        <!-- Action -->
        <div>
          <a href="<?= e(APP_URL) ?>/admin-kyc-view.php?id=<?= (int)$p['id'] ?>" class="btn btn-primary btn-sm">
            🔍 Review details
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<div class="panel mb-16">
  <div class="panel-header">
    <h2>✅ Recently Approved</h2>
    <span class="badge"><?= count($approved) ?></span>
  </div>
  <?php if (!$approved): ?>
    <div class="empty-state">No approvals yet.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Full Name</th>
            <th>Country</th>
            <th>ID</th>
            <th>Approved</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($approved as $a): ?>
            <tr>
              <td style="font-weight:700;"><?= e($a['username']) ?></td>
              <td><?= e($a['kyc_full_name'] ?? '—') ?></td>
              <td><?= e($a['kyc_country'] ?? '—') ?></td>
              <td style="font-family:var(--mono);font-size:12px;"><?= e($a['kyc_id_number'] ?? '—') ?></td>
              <td class="text-dim" style="font-family:var(--mono);font-size:12px;">
                <?= e(fmt_time($a['kyc_reviewed_at'] ?? '', 'M j, H:i')) ?>
              </td>
              <td>
                <a href="<?= e(APP_URL) ?>/admin-kyc-view.php?id=<?= (int)$a['id'] ?>" class="btn btn-sm">
                  View
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<div class="panel">
  <div class="panel-header">
    <h2>❌ Recently Rejected</h2>
    <span class="badge"><?= count($rejected) ?></span>
  </div>
  <?php if (!$rejected): ?>
    <div class="empty-state">No rejections yet.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Full Name</th>
            <th>Reason</th>
            <th>Rejected</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rejected as $r): ?>
            <tr>
              <td style="font-weight:700;"><?= e($r['username']) ?></td>
              <td><?= e($r['kyc_full_name'] ?? '—') ?></td>
              <td class="text-dim" style="font-size:12px;"><?= e($r['kyc_rejection_reason'] ?? '—') ?></td>
              <td class="text-dim" style="font-family:var(--mono);font-size:12px;">
                <?= e(fmt_time($r['kyc_reviewed_at'] ?? '', 'M j, H:i')) ?>
              </td>
              <td>
                <a href="<?= e(APP_URL) ?>/admin-kyc-view.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm">
                  View
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>