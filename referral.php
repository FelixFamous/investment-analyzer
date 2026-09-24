<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/referral.php';

$user = require_login();
$code = ensure_referral_code((int)$user['id']);
$stats = get_referral_stats((int)$user['id']);

$referralLink = APP_URL . '/register.php?ref=' . urlencode($code);

$pageTitle = 'Invite Friends';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/referral.css">

<div class="referral-hero">
  <div class="referral-hero-content">
    <div class="referral-hero-icon">🎁</div>
    <h1>Invite friends. Earn rewards.</h1>
    <p>
      Share your referral link. When someone signs up, <strong>you both get
      <?= e(usd(REFERRAL_BONUS_REFERRER)) ?> in virtual funds</strong> instantly.
      There's no limit — invite as many as you like.
    </p>
  </div>

  <div class="referral-code-card">
    <div class="referral-code-label">Your referral code</div>
    <div class="referral-code" id="refCode"><?= e($code) ?></div>
    <button type="button" class="btn btn-sm" id="copyCodeBtn">📋 Copy code</button>
  </div>
</div>

<div class="summary-grid" style="margin-bottom:20px;">
  <div class="card">
    <div class="card-label">Total Invited</div>
    <div class="card-value"><?= (int)$stats['count'] ?></div>
    <div class="card-sub">Signups using your link</div>
  </div>
  <div class="card">
    <div class="card-label">Total Earned</div>
    <div class="card-value positive"><?= e(usd($stats['earnings'])) ?></div>
    <div class="card-sub"><?= e(usd(REFERRAL_BONUS_REFERRER)) ?> per invite</div>
  </div>
  <div class="card">
    <div class="card-label">Friend Gets</div>
    <div class="card-value" style="font-size:20px;"><?= e(usd(REFERRAL_BONUS_REFERRED)) ?></div>
    <div class="card-sub">Welcome bonus for them</div>
  </div>
  <div class="card">
    <div class="card-label">Status</div>
    <div class="card-value" style="font-size:16px;">
      <span class="badge badge-live">active</span>
    </div>
    <div class="card-sub">Unlimited invites</div>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>🔗 Share your link</h2>
      <span class="badge">Anyone with the link can join</span>
    </div>

    <div class="referral-link-row">
      <input type="text" id="refLink" value="<?= e($referralLink) ?>" readonly onclick="this.select()">
      <button type="button" class="btn btn-primary" id="copyLinkBtn">Copy link</button>
    </div>

    <div class="referral-share-buttons">
      <a class="share-btn whatsapp" target="_blank" rel="noopener"
         href="https://wa.me/?text=<?= urlencode('Join me on AlphaEdge — a free paper-trading platform. ' . $referralLink) ?>">
        💬 WhatsApp
      </a>
      <a class="share-btn twitter" target="_blank" rel="noopener"
         href="https://twitter.com/intent/tweet?text=<?= urlencode('Join AlphaEdge — free paper trading. Sign up with my link:') ?>&url=<?= urlencode($referralLink) ?>">
        🐦 Twitter / X
      </a>
      <a class="share-btn telegram" target="_blank" rel="noopener"
         href="https://t.me/share/url?url=<?= urlencode($referralLink) ?>&text=<?= urlencode('Join AlphaEdge — free paper trading') ?>">
        ✈️ Telegram
      </a>
      <a class="share-btn email"
         href="mailto:?subject=Join%20AlphaEdge&body=<?= urlencode('Join me on AlphaEdge: ' . $referralLink) ?>">
        ✉️ Email
      </a>
    </div>

    <div class="qr-section">
      <div class="qr-label">Or scan the QR code</div>
      <div class="qr-box" id="qrBox"></div>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>👥 Your invites</h2>
      <span class="badge"><?= count($stats['invitees']) ?></span>
    </div>

    <?php if (!$stats['invitees']): ?>
      <div class="empty-state" style="padding:40px 20px;">
        <div style="font-size:38px;margin-bottom:12px;opacity:0.5;">📭</div>
        <div style="font-size:13px;">No invites yet. Share your link to get started.</div>
      </div>
    <?php else: ?>
      <?php foreach ($stats['invitees'] as $inv): ?>
        <div class="invite-row">
          <div class="invite-avatar"><?= e(strtoupper(substr($inv['username'], 0, 1))) ?></div>
          <div class="invite-info">
            <div class="invite-name">@<?= e($inv['username']) ?></div>
            <div class="invite-meta">
              Joined <?= e(fmt_time($inv['joined'], 'M j, Y')) ?>
              · <?= e($inv['kyc_status'] === 'approved' ? '✓ Verified' : 'Unverified') ?>
            </div>
          </div>
          <div class="invite-bonus">+<?= e(usd((float)$inv['bonus_referrer'])) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<div class="panel">
  <div class="panel-header">
    <h2>❓ How it works</h2>
  </div>
  <div class="how-steps">
    <div class="how-step">
      <div class="how-step-num">1</div>
      <div>
        <strong>Share your link</strong>
        <p>Send it to friends via WhatsApp, Twitter, or copy the code.</p>
      </div>
    </div>
    <div class="how-step">
      <div class="how-step-num">2</div>
      <div>
        <strong>They sign up</strong>
        <p>Anyone who joins with your link gets a <?= e(usd(REFERRAL_BONUS_REFERRED)) ?> welcome bonus.</p>
      </div>
    </div>
    <div class="how-step">
      <div class="how-step-num">3</div>
      <div>
        <strong>You both get rewarded</strong>
        <p>You earn <?= e(usd(REFERRAL_BONUS_REFERRER)) ?> in virtual funds per successful invite. No cap.</p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  window.REFERRAL_LINK = <?= json_encode($referralLink) ?>;
  window.REFERRAL_CODE = <?= json_encode($code) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/referral.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>