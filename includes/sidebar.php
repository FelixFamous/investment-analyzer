<?php
$user = current_user();
if (!$user) return;

$current = basename($_SERVER['PHP_SELF'] ?? '');

$kyc = $user['kyc_status'] ?? 'none';
$kycLabels = ['none'=>'Verify','pending'=>'Pending','approved'=>'Verified','rejected'=>'Rejected'];
$totpOn = (int)($user['totp_enabled'] ?? 0) === 1;

$navItems = [
    ['file' => 'dashboard.php',      'label' => 'Dashboard',       'icon' => '📊'],
    ['file' => 'markets.php',        'label' => 'Markets',         'icon' => '📈'],
    ['file' => 'multichart.php',     'label' => 'Multi-Chart',     'icon' => '⊞'],
    ['file' => 'microstructure.php', 'label' => 'Depth & Tape',    'icon' => '📉'],
    ['file' => 'scanner.php',        'label' => 'Market Scanner',  'icon' => '🔍'],
    ['file' => 'watchlist.php',      'label' => 'Watchlist',       'icon' => '⭐'],
    ['file' => 'signals.php',        'label' => 'Signals',         'icon' => '🎯'],
    ['file' => 'academy.php',        'label' => 'Academy',         'icon' => '🎓'],
    ['file' => 'leaderboard.php',    'label' => 'Leaderboard',     'icon' => '🏆'],
    ['file' => 'recurring.php',      'label' => 'Recurring Buys',  'icon' => '💹'],
    ['file' => 'alerts.php',         'label' => 'Alerts',          'icon' => '🔔'],
    ['file' => 'calendar.php',       'label' => 'Calendar',        'icon' => '📅'],
    ['file' => 'news.php',           'label' => 'News',            'icon' => '📰'],
    ['file' => 'portfolio.php',      'label' => 'Portfolio',       'icon' => '💼'],
    ['file' => 'journal.php',        'label' => 'Trading Journal', 'icon' => '📓'],
    ['file' => 'analytics.php',      'label' => 'Analytics',       'icon' => '📊'],
    ['file' => 'correlation.php',    'label' => 'Correlations',    'icon' => '🔗'],
    ['file' => 'calculator.php',     'label' => 'Trade Calculator','icon' => '🧮'],
    ['file' => 'strategies.php',     'label' => 'Strategy Builder','icon' => '⚙️'],
    ['file' => 'backtest.php',       'label' => 'Backtesting',     'icon' => '🧪'],
    ['file' => 'risk-monitor.php',   'label' => 'Risk Monitor',    'icon' => '🛡️'],
    ['file' => 'referral.php',       'label' => 'Invite Friends',  'icon' => '🎁'],
    ['file' => 'api-keys.php',       'label' => 'API Keys',        'icon' => '🔑'],
];

$bottomNav = [
    ['file' => 'dashboard.php',  'label' => 'Home',      'icon' => '🏠'],
    ['file' => 'markets.php',    'label' => 'Markets',   'icon' => '📈'],
    ['file' => 'academy.php',    'label' => 'Academy',   'icon' => '🎓'],
    ['file' => 'portfolio.php',  'label' => 'Portfolio', 'icon' => '💼'],
];
?>

<button class="sidebar-toggle" id="sidebarToggle" aria-label="Menu">☰</button>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">
  <a class="sidebar-brand" href="<?= e(APP_URL) ?>/dashboard.php">AlphaEdge</a>

  <div class="sidebar-label">Trading</div>
  <nav class="sidebar-nav">
    <?php foreach ($navItems as $item): ?>
      <a class="sidebar-link <?= $current === $item['file'] ? 'active' : '' ?>" href="<?= e(APP_URL) ?>/<?= e($item['file']) ?>">
        <span class="icon"><?= $item['icon'] ?></span>
        <span><?= e($item['label']) ?></span>
        <?php if ($item['file'] === 'watchlist.php'): ?>
          <span class="badge-count wl-badge" id="watchlistBadge" style="display:none;margin-left:auto;">0</span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-divider"></div>

  <div class="sidebar-label">Account</div>
  <nav class="sidebar-nav">
    <a class="sidebar-link <?= $current === 'kyc.php' ? 'active' : '' ?>" href="<?= e(APP_URL) ?>/kyc.php">
      <span class="icon">🪪</span>
      <span>KYC Verification</span>
      <span class="badge-count kyc-pill <?= e($kyc) ?>" style="margin-left:auto;"><?= e($kycLabels[$kyc] ?? 'Verify') ?></span>
    </a>
    <a class="sidebar-link <?= $current === 'settings.php' ? 'active' : '' ?>" href="<?= e(APP_URL) ?>/settings.php">
      <span class="icon">⚙️</span><span>Risk Settings</span>
    </a>
    <a class="sidebar-link <?= $current === 'security.php' ? 'active' : '' ?>" href="<?= e(APP_URL) ?>/security.php">
      <span class="icon">🔐</span><span>Security</span>
      <?php if ($totpOn): ?><span class="badge-count" style="background:var(--green-glow);color:var(--green);margin-left:auto;">2FA</span><?php endif; ?>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="avatar"><?= e(strtoupper(substr($user['username'], 0, 1))) ?></div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name"><?= e($user['username']) ?></div>
        <div class="sidebar-user-role"><?= (int)$user['is_admin'] === 1 ? 'Admin' : 'Trader' ?></div>
      </div>
    </div>
    <a class="sidebar-link" href="<?= e(APP_URL) ?>/logout.php"><span class="icon">↪</span><span>Logout</span></a>
  </div>
</aside>

<nav class="bottom-nav" id="bottomNav">
  <?php foreach ($bottomNav as $item): ?>
    <a class="bottom-nav-item <?= $current === $item['file'] ? 'active' : '' ?>" href="<?= e(APP_URL) ?>/<?= e($item['file']) ?>">
      <span class="bottom-nav-icon"><?= $item['icon'] ?></span>
      <span class="bottom-nav-label"><?= e($item['label']) ?></span>
    </a>
  <?php endforeach; ?>
  <button class="bottom-nav-item" id="bottomNavMore" type="button">
    <span class="bottom-nav-icon">☰</span>
    <span class="bottom-nav-label">More</span>
  </button>
</nav>