<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';

$user = require_login();
if ((int)$user['is_admin'] !== 1) {
    flash_set('error', 'Admin access required.');
    redirect(APP_URL . '/dashboard.php');
}

$pdo = db();

$search = trim((string)($_GET['q'] ?? ''));

if ($search !== '') {
    $like = '%' . $search . '%';
    $stmt = $pdo->prepare('
        SELECT id, username, email, cash_balance, is_admin, created_at, last_seen, timezone, kyc_status,
               (last_seen IS NOT NULL AND last_seen >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)) AS is_online
        FROM users
        WHERE username LIKE ? OR email LIKE ?
        ORDER BY created_at DESC
    ');
    $stmt->execute([$like, $like]);
    $users = $stmt->fetchAll();
} else {
    $users = $pdo->query('
        SELECT id, username, email, cash_balance, is_admin, created_at, last_seen, timezone, kyc_status,
               (last_seen IS NOT NULL AND last_seen >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)) AS is_online
        FROM users
        ORDER BY created_at DESC
    ')->fetchAll();
}

// Holdings + trades summary per user
$holdingsByUser = [];
$stmt = $pdo->query('SELECT user_id, symbol, quantity, avg_price FROM holdings');
foreach ($stmt->fetchAll() as $h) {
    $uid = (int)$h['user_id'];
    $price = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $val = (float)$h['quantity'] * $price;
    $holdingsByUser[$uid]['value'] = ($holdingsByUser[$uid]['value'] ?? 0) + $val;
    $holdingsByUser[$uid]['count'] = ($holdingsByUser[$uid]['count'] ?? 0) + 1;
}

$tradeCountByUser = [];
$stmt = $pdo->query('SELECT user_id, COUNT(*) AS c FROM trades GROUP BY user_id');
foreach ($stmt->fetchAll() as $t) {
    $tradeCountByUser[(int)$t['user_id']] = (int)$t['c'];
}

function fmt_time_in(string $utcTs, string $tz, string $format = 'M j, H:i'): string
{
    try {
        $dt = new DateTimeImmutable($utcTs, new DateTimeZone('UTC'));
        $dt = $dt->setTimezone(new DateTimeZone($tz));
        return $dt->format($format);
    } catch (Throwable $e) { return '—'; }
}

$pageTitle = 'Users';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="panel mb-16">
  <div class="panel-header">
    <h2>👥 All Users (<?= count($users) ?>)</h2>
    <form method="get" style="display:flex;gap:8px;">
      <input type="text" name="q" placeholder="Search username or email…"
             value="<?= e($search) ?>"
             style="background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;min-width:240px;">
      <button type="submit" class="btn btn-sm btn-primary">Search</button>
      <?php if ($search !== ''): ?>
        <a href="<?= e(APP_URL) ?>/admin-users.php" class="btn btn-sm">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <?php if (!$users): ?>
    <div class="empty-state">No users found.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Cash</th>
            <th>Holdings</th>
            <th>Equity</th>
            <th>Trades</th>
            <th>KYC</th>
            <th>Role</th>
            <th>Last Seen</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u):
            $uid = (int)$u['id'];
            $hv = $holdingsByUser[$uid]['value'] ?? 0.0;
            $hc = $holdingsByUser[$uid]['count'] ?? 0;
            $tc = $tradeCountByUser[$uid] ?? 0;
            $equity = (float)$u['cash_balance'] + $hv;

            if ($u['is_online']) {
                $status = '<span style="color:var(--green);font-weight:700;">🟢 Online</span>';
            } elseif ($u['last_seen']) {
                $status = '<span class="text-dim" style="font-family:var(--mono);font-size:11px;">'
                        . e(fmt_time_in($u['last_seen'], $u['timezone'] ?? 'UTC', 'M j, H:i'))
                        . '</span>';
            } else {
                $status = '<span class="text-dim">—</span>';
            }
          ?>
            <tr>
              <td class="text-dim" style="font-family:var(--mono);"><?= $uid ?></td>
              <td style="font-weight:700;"><?= e($u['username']) ?></td>
              <td class="text-dim" style="font-size:12px;"><?= e($u['email']) ?></td>
              <td style="font-family:var(--mono);"><?= e(usd((float)$u['cash_balance'])) ?></td>
              <td style="font-family:var(--mono);">
                <?= e(usd($hv)) ?>
                <div style="font-size:11px;color:var(--text-faint);"><?= $hc ?> pos</div>
              </td>
              <td style="font-family:var(--mono);font-weight:700;"><?= e(usd($equity)) ?></td>
              <td style="font-family:var(--mono);"><?= $tc ?></td>
              <td>
                <span class="kyc-pill <?= e($u['kyc_status'] ?? 'none') ?>">
                  <?= e(ucfirst($u['kyc_status'] ?? 'none')) ?>
                </span>
              </td>
              <td>
                <?php if ((int)$u['is_admin'] === 1): ?>
                  <span class="prob-badge" style="background:rgba(91,124,250,0.15);color:#93a5ff;">ADMIN</span>
                <?php else: ?>
                  <span class="text-dim">user</span>
                <?php endif; ?>
              </td>
              <td><?= $status ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>