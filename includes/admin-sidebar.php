<?php
/**
 * Admin sidebar navigation.
 * Only rendered on admin pages.
 */
$adminUser = current_user();
if (!$adminUser || (int)$adminUser['is_admin'] !== 1) return;

$current = basename($_SERVER['PHP_SELF'] ?? '');

// Get pending KYC count for badge
$pendingKyc = 0;
try {
    $pendingKyc = (int)db()->query("SELECT COUNT(*) FROM users WHERE kyc_status = 'pending'")->fetchColumn();
} catch (Throwable $e) {}

$adminNav = [
    ['file' => 'admin.php',         'label' => 'Overview',     'icon' => '📊'],
    ['file' => 'admin-kyc.php',     'label' => 'KYC Review',   'icon' => '🪪', 'badge' => $pendingKyc],
    ['file' => 'admin-users.php',   'label' => 'Users',        'icon' => '👥'],
    ['file' => 'admin-trades.php',  'label' => 'Trades',       'icon' => '📈'],
    ['file' => 'admin-chat.php',    'label' => 'Chat Log',     'icon' => '💬'],
];
?>

<button class="sidebar-toggle" id="sidebarToggle" aria-label="Menu">☰</button>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">
  <a class="sidebar-brand" href="<?= e(APP_URL) ?>/admin.php">AlphaEdge</a>

  <div class="sidebar-label">Admin Console</div>
  <nav class="sidebar-nav">
    <?php foreach ($adminNav as $item):
      $badge = $item['badge'] ?? 0;
    ?>
      <a class="sidebar-link <?= $current === $item['file'] ? 'active' : '' ?>"
         href="<?= e(APP_URL) ?>/<?= e($item['file']) ?>">
        <span class="icon"><?= $item['icon'] ?></span>
        <span><?= e($item['label']) ?></span>
        <?php if ($badge > 0): ?>
          <span class="admin-count"><?= (int)$badge ?></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-divider"></div>

  <div class="sidebar-label">View as User</div>
  <nav class="sidebar-nav">
    <a class="sidebar-link" href="<?= e(APP_URL) ?>/dashboard.php">
      <span class="icon">↗</span>
      <span>Trader Dashboard</span>
    </a>
    <a class="sidebar-link" href="<?= e(APP_URL) ?>/markets.php">
      <span class="icon">↗</span>
      <span>Live Markets</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="avatar"><?= e(strtoupper(substr($adminUser['username'], 0, 1))) ?></div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name"><?= e($adminUser['username']) ?></div>
        <div class="sidebar-user-role" style="color:#93a5ff;">Admin</div>
      </div>
    </div>
    <a class="sidebar-link" href="<?= e(APP_URL) ?>/logout.php">
      <span class="icon">↪</span>
      <span>Logout</span>
    </a>
  </div>
</aside>