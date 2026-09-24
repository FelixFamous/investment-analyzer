<?php
require_once __DIR__ . '/includes/auth.php';

$user = require_login();
$kyc  = $user['kyc_status'] ?? 'none';
$error = '';

$COUNTRIES = [
    'Nigeria','Ghana','Kenya','South Africa','Egypt','Morocco',
    'United Kingdom','France','Germany','Spain','Italy','Netherlands',
    'United States','Canada','Brazil','Mexico',
    'UAE','Saudi Arabia','Pakistan','India','Bangladesh',
    'China','Japan','South Korea','Singapore','Australia','New Zealand',
    'Other',
];

$DOC_TYPES = [
    'passport'         => 'International Passport',
    'national_id'      => 'National ID Card',
    'drivers_license'  => "Driver's License",
    'residence_permit' => 'Residence Permit / BRP / Visa',
];

$POA_TYPES = [
    'utility_bill'   => 'Utility Bill (gas, water, electricity)',
    'bank_statement' => 'Bank Statement',
    'council_tax'    => 'Council Tax / Government Letter',
];

/* ============================================================
 *  HANDLE SUBMISSION
 * ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !in_array($kyc, ['pending', 'approved'])) {
    csrf_check();

    $fullName    = post('full_name');
    $dob         = post('dob');
    $country     = post('country');
    $docType     = post('doc_type');
    $docNumber   = post('doc_number');
    $taxId       = post('tax_id');
    $taxCountry  = post('tax_country');
    $poaType     = post('poa_type');
    $bankHolder  = post('bank_holder');
    $bankName    = post('bank_name');
    $bankAcct    = post('bank_account');
    $bankCountry = post('bank_country');
    $mobile      = post('mobile');
    $selfieB64   = (string)($_POST['selfie'] ?? '');

    // --- Validation ---
    if (strlen($fullName) < 3)                                    $error = 'Please enter your full legal name.';
    elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob))           $error = 'Please enter a valid date of birth.';
    elseif (strtotime($dob) > strtotime('-18 years'))             $error = 'You must be at least 18 years old.';
    elseif ($country === '')                                      $error = 'Please select your country.';
    elseif (!isset($DOC_TYPES[$docType]))                         $error = 'Please select a valid ID document type.';
    elseif (strlen($docNumber) < 4)                               $error = 'Please enter your document number.';
    elseif (strlen($taxId) < 3)                                   $error = 'Please enter your national tax ID.';
    elseif ($taxCountry === '')                                   $error = 'Please select your country of tax residence.';
    elseif (!isset($POA_TYPES[$poaType]))                         $error = 'Please select a proof of address type.';
    elseif (strlen($bankHolder) < 3)                              $error = 'Please enter the bank account holder name.';
    elseif (strlen($bankName) < 2)                                $error = 'Please enter your bank name.';
    elseif (strlen($bankAcct) < 6)                                $error = 'Please enter a valid account number / IBAN.';
    elseif ($bankCountry === '')                                  $error = 'Please select the bank account country.';
    elseif (strlen($mobile) < 7)                                  $error = 'Please enter a valid mobile number.';
    elseif ($selfieB64 === '')                                    $error = 'Face verification is required. Please capture a selfie.';

    $uploadDir = __DIR__ . '/uploads/kyc';
    if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);

    // --- File saving helper ---
    $saveFile = function (string $field, string $prefix, int $maxBytes) use ($uploadDir, &$error): ?string {
        if (empty($_FILES[$field]['name'])) return null;
        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK) { $error = "Upload failed for $field."; return null; }
        if ($file['size'] > $maxBytes) { $error = "File too large ($field)."; return null; }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'application/pdf' => 'pdf',
        ];
        if (!isset($allowedMime[$mime])) { $error = "Invalid file type ($field). Use JPG, PNG, or PDF."; return null; }

        $ext = $allowedMime[$mime];
        $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $filename)) {
            $error = "Could not save $field.";
            return null;
        }
        return $filename;
    };

    // --- ID documents ---
    $docFront = null; $docBack = null;
    if (!$error) {
        $docFront = $saveFile('doc_front', 'docfront_' . $user['id'], 5 * 1024 * 1024);
        if (!$docFront) $error = 'Please upload the front of your ID document.';

        if (!$error && in_array($docType, ['national_id', 'drivers_license'], true)) {
            $docBack = $saveFile('doc_back', 'docback_' . $user['id'], 5 * 1024 * 1024);
            if (!$docBack) $error = 'Please upload the back of your ID document.';
        }
    }

    // --- Proof of address ---
    $poaFile = null;
    if (!$error) {
        $poaFile = $saveFile('poa_file', 'poa_' . $user['id'], 5 * 1024 * 1024);
        if (!$poaFile) $error = 'Please upload your proof of address document.';
    }

    // --- Selfie ---
    $selfieFilename = null;
    if (!$error) {
        if (!preg_match('#^data:image/(jpeg|jpg|png);base64,#i', $selfieB64)) {
            $error = 'Invalid selfie format. Please retake.';
        } else {
            $binary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $selfieB64), true);
            if ($binary === false || strlen($binary) < 2000) {
                $error = 'Selfie is corrupted or too small. Please retake.';
            } elseif (strlen($binary) > 5 * 1024 * 1024) {
                $error = 'Selfie is too large. Please retake.';
            } else {
                $selfieFilename = 'selfie_' . $user['id'] . '_' . time() . '.jpg';
                if (!file_put_contents($uploadDir . '/' . $selfieFilename, $binary)) {
                    $error = 'Could not save your selfie.';
                    $selfieFilename = null;
                }
            }
        }
    }

    // --- Persist ---
    if (!$error) {
        db()->prepare('UPDATE users SET
            kyc_status = "pending",
            kyc_full_name = ?,
            kyc_dob = ?,
            kyc_country = ?,
            kyc_doc_type = ?,
            kyc_id_number = ?,
            kyc_doc_front = ?,
            kyc_doc_back = ?,
            kyc_tax_id = ?,
            kyc_tax_country = ?,
            kyc_poa_type = ?,
            kyc_poa_file = ?,
            kyc_bank_holder = ?,
            kyc_bank_name = ?,
            kyc_bank_account = ?,
            kyc_bank_country = ?,
            kyc_mobile = ?,
            kyc_email_verified = 1,
            kyc_selfie = ?,
            kyc_submitted_at = UTC_TIMESTAMP(),
            kyc_rejection_reason = NULL
            WHERE id = ?')
            ->execute([
                $fullName, $dob, $country,
                $docType, $docNumber, $docFront, $docBack,
                $taxId, $taxCountry,
                $poaType, $poaFile,
                $bankHolder, $bankName, $bankAcct, $bankCountry,
                $mobile,
                $selfieFilename,
                $user['id']
            ]);

        flash_set('success', 'KYC submitted. Our team will review it shortly.');
        redirect(APP_URL . '/kyc.php');
    }

    $kyc = 'none';
}

$kyc = current_user()['kyc_status'] ?? 'none';

$statusMeta = [
    'none'     => ['icon' => '🪪', 'title' => 'Verify your identity',   'color' => 'var(--text-dim)',  'desc' => 'Complete KYC to unlock deposits and full trading features.'],
    'pending'  => ['icon' => '⏳', 'title' => 'Pending approval',       'color' => 'var(--accent)',    'desc' => 'Your submission is in review. This usually takes a few minutes.'],
    'approved' => ['icon' => '✅', 'title' => 'Identity verified',      'color' => 'var(--green)',     'desc' => 'You have full access to deposits and all trading features.'],
    'rejected' => ['icon' => '❌', 'title' => 'Verification rejected',  'color' => 'var(--red)',       'desc' => 'Please review the reason below and re-submit.'],
];
$meta = $statusMeta[$kyc] ?? $statusMeta['none'];

$pageTitle = 'KYC Verification';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/face-verify.css">
<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/kyc-wizard.css">

<div class="panel mb-16" style="display:flex;align-items:center;gap:16px;">
  <div style="font-size:36px;line-height:1;"><?= $meta['icon'] ?></div>
  <div style="flex:1;">
    <div style="font-size:18px;font-weight:800;color:<?= $meta['color'] ?>;">
      <?= e($meta['title']) ?>
    </div>
    <div class="text-dim" style="font-size:13px;margin-top:2px;">
      <?= e($meta['desc']) ?>
    </div>
  </div>
  <div><span class="kyc-pill <?= e($kyc) ?>"><?= e(strtoupper($kyc)) ?></span></div>
</div>

<?php if ($error): ?>
  <div class="flash flash-error"><?= e($error) ?></div>
<?php endif; ?>

<?php if ($kyc === 'approved'): ?>

  <div class="panel">
    <div class="panel-header"><h2>Verification Summary</h2></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
      <div>
        <table>
          <tr><th style="width:180px;">Full name</th><td><?= e($user['kyc_full_name'] ?? '—') ?></td></tr>
          <tr><th>Date of birth</th><td><?= e($user['kyc_dob'] ?? '—') ?></td></tr>
          <tr><th>Country</th><td><?= e($user['kyc_country'] ?? '—') ?></td></tr>
          <tr><th>Document type</th><td><?= e($DOC_TYPES[$user['kyc_doc_type']] ?? '—') ?></td></tr>
          <tr><th>Document number</th><td><?= e($user['kyc_id_number'] ?? '—') ?></td></tr>
          <tr><th>National tax ID</th><td><?= e($user['kyc_tax_id'] ?? '—') ?></td></tr>
          <tr><th>Tax residence</th><td><?= e($user['kyc_tax_country'] ?? '—') ?></td></tr>
          <tr><th>Bank holder</th><td><?= e($user['kyc_bank_holder'] ?? '—') ?></td></tr>
          <tr><th>Bank</th><td><?= e($user['kyc_bank_name'] ?? '—') ?></td></tr>
          <tr><th>Account (last 4)</th><td style="font-family:var(--mono);">•••• <?= e(substr((string)$user['kyc_bank_account'], -4)) ?></td></tr>
          <tr><th>Mobile</th><td><?= e($user['kyc_mobile'] ?? '—') ?></td></tr>
          <tr><th>Approved</th><td><?= e(fmt_time($user['kyc_reviewed_at'] ?? '', 'M j, Y H:i')) ?></td></tr>
        </table>
      </div>
      <div style="text-align:center;">
        <div class="card-label" style="margin-bottom:8px;">Selfie on file</div>
        <?php if (!empty($user['kyc_selfie'])): ?>
          <img src="<?= e(APP_URL) ?>/uploads/kyc/<?= e($user['kyc_selfie']) ?>"
               alt="Selfie"
               style="width:200px;height:200px;object-fit:cover;border-radius:16px;border:2px solid var(--green);">
        <?php else: ?>
          <div class="text-dim">No selfie</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php elseif ($kyc === 'pending'): ?>

  <div class="panel">
    <div class="panel-header"><h2>Pending approval</h2></div>
    <p class="text-dim" style="font-size:13px;line-height:1.7;">
      Your documents and selfie are queued for review. You'll unlock deposits the moment you're approved.
    </p>
    <div style="margin-top:16px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
      <div class="card">
        <div class="card-label">Submitted</div>
        <div style="font-weight:700;font-family:var(--mono);font-size:14px;">
          <?= e(fmt_time($user['kyc_submitted_at'] ?? '', 'M j, H:i')) ?>
        </div>
      </div>
      <div class="card">
        <div class="card-label">Status</div>
        <div style="font-weight:700;color:var(--accent);">Pending Approval</div>
      </div>
      <div class="card">
        <div class="card-label">Selfie</div>
        <?php if (!empty($user['kyc_selfie'])): ?>
          <img src="<?= e(APP_URL) ?>/uploads/kyc/<?= e($user['kyc_selfie']) ?>"
               alt="Selfie"
               style="width:56px;height:56px;object-fit:cover;border-radius:50%;border:2px solid var(--accent);">
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php else: ?>

  <?php if ($kyc === 'rejected' && !empty($user['kyc_rejection_reason'])): ?>
    <div class="flash flash-error" style="margin-bottom:16px;">
      <strong>Reason for rejection:</strong> <?= e($user['kyc_rejection_reason']) ?>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" novalidate id="kycForm" class="kyc-wizard">
    <?= csrf_field() ?>
    <input type="hidden" name="selfie" id="selfieData" value="">

    <div class="wizard-progress">
      <div class="wizard-progress-fill" id="wizFill" style="width:25%"></div>
    </div>
    <div class="wizard-steps">
      <div class="wizard-step active" data-step-indicator="1">
        <div class="wizard-step-dot">1</div>
        <div class="wizard-step-label">Identity</div>
      </div>
      <div class="wizard-step" data-step-indicator="2">
        <div class="wizard-step-dot">2</div>
        <div class="wizard-step-label">Address</div>
      </div>
      <div class="wizard-step" data-step-indicator="3">
        <div class="wizard-step-dot">3</div>
        <div class="wizard-step-label">Bank</div>
      </div>
      <div class="wizard-step" data-step-indicator="4">
        <div class="wizard-step-dot">4</div>
        <div class="wizard-step-label">Selfie &amp; Review</div>
      </div>
    </div>

    <div class="panel kyc-panel">

      <!-- STEP 1 -->
      <div class="kyc-step-panel" data-step="1">
        <div class="panel-header">
          <h2>Step 1 · Identity</h2>
          <span class="badge">Required</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group" style="grid-column:1/-1;">
            <label for="full_name">Full legal name (as on your ID)</label>
            <input type="text" id="full_name" name="full_name" required
                   value="<?= e($user['kyc_full_name'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="dob">Date of birth</label>
            <input type="date" id="dob" name="dob" required
                   value="<?= e($user['kyc_dob'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="country">Country of citizenship</label>
            <select id="country" name="country" required>
              <option value="">— Select —</option>
              <?php foreach ($COUNTRIES as $c): ?>
                <option value="<?= e($c) ?>" <?= ($user['kyc_country'] ?? '') === $c ? 'selected' : '' ?>>
                  <?= e($c) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="doc_type">Identity document type</label>
            <select id="doc_type" name="doc_type" required>
              <option value="">— Select —</option>
              <?php foreach ($DOC_TYPES as $k => $label): ?>
                <option value="<?= e($k) ?>" <?= ($user['kyc_doc_type'] ?? '') === $k ? 'selected' : '' ?>>
                  <?= e($label) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="doc_number">Document number</label>
            <input type="text" id="doc_number" name="doc_number" required
                   value="<?= e($user['kyc_id_number'] ?? '') ?>">
          </div>

          <div class="form-group" id="docFrontGroup">
            <label for="doc_front">Document — Front (JPG, PNG, PDF · max 5 MB)</label>
            <input type="file" id="doc_front" name="doc_front"
                   accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" required>
          </div>

          <div class="form-group" id="docBackGroup" style="display:none;">
            <label for="doc_back">Document — Back (required for National ID / Driver's License)</label>
            <input type="file" id="doc_back" name="doc_back"
                   accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">
          </div>

          <div class="form-group">
            <label for="tax_id">National Tax ID / TIN</label>
            <input type="text" id="tax_id" name="tax_id" required
                   value="<?= e($user['kyc_tax_id'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="tax_country">Country of tax residence</label>
            <select id="tax_country" name="tax_country" required>
              <option value="">— Select —</option>
              <?php foreach ($COUNTRIES as $c): ?>
                <option value="<?= e($c) ?>" <?= ($user['kyc_tax_country'] ?? '') === $c ? 'selected' : '' ?>>
                  <?= e($c) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- STEP 2 -->
      <div class="kyc-step-panel" data-step="2" style="display:none;">
        <div class="panel-header">
          <h2>Step 2 · Proof of Address</h2>
          <span class="badge">Required</span>
        </div>

        <p class="text-dim" style="font-size:13px;margin-bottom:18px;line-height:1.6;">
          Upload a document that shows your name and current address.
          Must have been issued <strong>within the last 3 months</strong>.
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group" style="grid-column:1/-1;">
            <label for="poa_type">Document type</label>
            <select id="poa_type" name="poa_type" required>
              <option value="">— Select —</option>
              <?php foreach ($POA_TYPES as $k => $label): ?>
                <option value="<?= e($k) ?>" <?= ($user['kyc_poa_type'] ?? '') === $k ? 'selected' : '' ?>>
                  <?= e($label) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group" style="grid-column:1/-1;">
            <label for="poa_file">Proof of address file (JPG, PNG, PDF · max 5 MB)</label>
            <input type="file" id="poa_file" name="poa_file"
                   accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" required>
            <small class="text-dim" style="display:block;margin-top:6px;font-size:11px;">
              Must clearly show: <strong>your full name</strong>, <strong>your address</strong>, and <strong>the date</strong>.
            </small>
          </div>
        </div>
      </div>

      <!-- STEP 3 -->
      <div class="kyc-step-panel" data-step="3" style="display:none;">
        <div class="panel-header">
          <h2>Step 3 · Bank Account</h2>
          <span class="badge">Required</span>
        </div>

        <p class="text-dim" style="font-size:13px;margin-bottom:18px;line-height:1.6;">
          We collect your bank account details so we can send withdrawals to you.
          We <strong>never</strong> ask for your banking username, password, or card details.
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group" style="grid-column:1/-1;">
            <label for="bank_holder">Account holder name (as on bank statement)</label>
            <input type="text" id="bank_holder" name="bank_holder" required
                   value="<?= e($user['kyc_bank_holder'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="bank_name">Bank name</label>
            <input type="text" id="bank_name" name="bank_name" required
                   value="<?= e($user['kyc_bank_name'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="bank_country">Bank country</label>
            <select id="bank_country" name="bank_country" required>
              <option value="">— Select —</option>
              <?php foreach ($COUNTRIES as $c): ?>
                <option value="<?= e($c) ?>" <?= ($user['kyc_bank_country'] ?? '') === $c ? 'selected' : '' ?>>
                  <?= e($c) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group" style="grid-column:1/-1;">
            <label for="bank_account">Account number / IBAN</label>
            <input type="text" id="bank_account" name="bank_account" required
                   value="<?= e($user['kyc_bank_account'] ?? '') ?>">
          </div>

          <div class="form-group" style="grid-column:1/-1;">
            <label for="mobile">Registered mobile number (with country code)</label>
            <input type="tel" id="mobile" name="mobile" placeholder="+234 801 234 5678" required
                   value="<?= e($user['kyc_mobile'] ?? '') ?>">
          </div>

          <div class="form-group" style="grid-column:1/-1;">
            <label>Verified contact</label>
            <div style="padding:12px 14px;background:var(--panel-2);border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;gap:10px;font-size:13px;">
              <span style="color:var(--green);font-weight:700;">✓</span>
              <span>Email <strong><?= e($user['email']) ?></strong> is registered on your account.</span>
            </div>
          </div>
        </div>
      </div>

      <!-- STEP 4 -->
      <div class="kyc-step-panel" data-step="4" style="display:none;">
        <div class="panel-header">
          <h2>Step 4 · Face Verification</h2>
          <span class="badge">Required</span>
        </div>

        <p class="text-dim" style="font-size:13px;margin-bottom:16px;line-height:1.6;">
          Take a live selfie. Your face must be well-lit, centered in the oval, and clearly visible.
        </p>

        <div id="facePanel" class="face-panel">
          <div class="face-preview">
            <video id="faceVideo" autoplay playsinline muted style="display:none;"></video>
            <canvas id="faceCanvas" style="display:none;"></canvas>
            <img id="facePreviewImg" class="face-img" style="display:none;" alt="Captured selfie">
            <div class="face-oval" id="faceOval"></div>
            <div class="face-hint" id="faceHint">Click "Start camera" to begin</div>
          </div>

          <div class="face-controls">
            <button type="button" class="btn" id="faceStartBtn">📷 Start camera</button>
            <button type="button" class="btn btn-primary" id="faceCaptureBtn" style="display:none;" disabled>📸 Capture selfie</button>
            <button type="button" class="btn" id="faceRetakeBtn" style="display:none;">↻ Retake</button>
          </div>

          <div class="face-checklist" id="faceChecklist">
            <div class="face-check" data-check="camera"><span class="ck">○</span> Camera ready</div>
            <div class="face-check" data-check="light"><span class="ck">○</span> Good lighting</div>
            <div class="face-check" data-check="face"><span class="ck">○</span> Face detected</div>
            <div class="face-check" data-check="position"><span class="ck">○</span> Face properly positioned</div>
          </div>
        </div>
      </div>

      <!-- Nav -->
      <div class="wizard-nav">
        <button type="button" class="btn" id="wizPrev" disabled>← Back</button>
        <button type="button" class="btn btn-primary" id="wizNext">Next →</button>
        <button type="submit" class="btn btn-primary" id="kycSubmitBtn" style="display:none;" disabled>
          Submit for review
        </button>
      </div>

    </div>
  </form>

<?php endif; ?>

<script src="<?= e(APP_URL) ?>/assets/js/face-verify.js" defer></script>
<script src="<?= e(APP_URL) ?>/assets/js/kyc-wizard.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>