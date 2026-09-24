<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';

$user = require_login();

// Only admins may view this page
if ((int)$user['is_admin'] !== 1) {
    http_response_code(403);
    flash_set('error', 'Admin access required.');
    redirect(APP_URL . '/dashboard.php');
}

// Fetch all users with their cash balance
$users = db()->query('
    SELECT id, username, email, cash_balance, is_admin, created_at
    FROM users
    ORDER BY created_at DESC
')->fetchAll();

// Compute holdings value per user (in PHP for simplicity)
$holdingsValue = [];
$holdingsCount = [];
$stmt = db()->query('SELECT user_id, symbol, quantity FROM holdings');
foreach ($stmt->fetchAll() as $h) {
    $uid = (int)$h['user_id'];
    $price = get_price($h['symbol']) ?? 0.0;
    $val = (float)$h['quantity'] * $price;
    $holdingsValue[$uid] = ($holdingsValue[$uid] ?? 0) + $val;
    $holdingsCount[$uid] = ($holdingsCount[$uid] ?? 0) + 1;
}

// Platform totals
$totalCash = 0.0; $totalHoldings = 0.0;
foreach ($users as $u) {
    $totalCash     += (float)$u['cash_balance'];
    $totalHoldings += $holdingsValue[(int)$u['id']] ?? 0.0;
}

$pageTitle = 'Admin';
require __DIR__ . '/includes/header.php';
?>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Total Users</div>
    <div class="card-value"><?= count($users) ?></div>
    <div class="card-sub">Registered accounts</div>
  </div>
  <div class="card">
    <div class="card-label">Total Cash Held</div>
    <div class="card-value"><?= e(usd($totalCash)) ?></div>
    <div class="card-sub">Virtual funds across all users</div>
  </div>
  <div class="card">
    <div class="card-label">Total Holdings Value</div>
    <div class="card-value"><?= e(usd($totalHoldings)) ?></div>
    <div class="card-sub">Marked to market</div>
  </div>
  <div class="card">
    <div class="card-label">Platform Equity</div>
    <div class="card-value"><?= e(usd($totalCash + $totalHoldings)) ?></div>
    <div class="card-sub">Cash + holdings</div>
  </div>
</div>

<div class="panel">
  <div class="panel-header">
    <h2>👥 All Users</h2>
    <span class="badge"><?= count($users) ?> total</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Cash</th>
          <th>Holdings</th>
          <th>Equity</th>
          <th>Role</th>
          <th>Joined</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u):
          $uid = (int)$u['id'];
          $hv  = $holdingsValue[$uid] ?? 0.0;
          $hc  = $holdingsCount[$uid] ?? 0;
          $eq  = (float)$u['cash_balance'] + $hv;
        ?>
          <tr>
            <td class="text-dim"><?= $uid ?></td>
            <td style="font-weight:600;"><?= e($u['username']) ?></td>
            <td class="text-dim"><?= e($u['email']) ?></td>
            <td><?= e(usd((float)$u['cash_balance'])) ?></td>
            <td>
              <?= e(usd($hv)) ?>
              <small class="text-dim"> · <?= $hc ?> pos</small>
            </td>
            <td style="font-weight:600;"><?= e(usd($eq)) ?></td>
            <td>
              <?php if ((int)$u['is_admin'] === 1): ?>
                <span class="prob-badge" style="background:rgba(59,130,246,0.12);color:#93c5fd;">ADMIN</span>
              <?php else: ?>
                <span class="text-dim">user</span>
              <?php endif; ?>
            </td>
            <td class="text-dim" style="font-size:13px;">
              <?= e(date('M j, Y', strtotime($u['created_at']))) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>