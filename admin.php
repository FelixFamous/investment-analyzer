<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';
require_once __DIR__ . '/includes/signal.php';

$user = require_login();
if ((int)$user['is_admin'] !== 1) {
    flash_set('error', 'Admin access required.');
    redirect(APP_URL . '/dashboard.php');
}

$pdo = db();

$totalUsers   = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$activeUsers  = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE last_seen >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->fetchColumn();
$todayUsers   = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()')->fetchColumn();

$totalTrades  = (int)$pdo->query('SELECT COUNT(*) FROM trades')->fetchColumn();
$todayTrades  = (int)$pdo->query('SELECT COUNT(*) FROM trades WHERE DATE(created_at) = CURDATE()')->fetchColumn();
$totalVolume  = (float)$pdo->query('SELECT COALESCE(SUM(total),0) FROM trades')->fetchColumn();

$totalCash    = (float)$pdo->query('SELECT COALESCE(SUM(cash_balance),0) FROM users')->fetchColumn();

$pendingKyc   = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE kyc_status = 'pending'")->fetchColumn();
$approvedKyc  = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE kyc_status = 'approved'")->fetchColumn();
$rejectedKyc  = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE kyc_status = 'rejected'")->fetchColumn();

$users = $pdo->query('
    SELECT id, username, email, cash_balance, is_admin, created_at, last_seen, timezone, kyc_status,
           (last_seen IS NOT NULL AND last_seen >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)) AS is_online
    FROM users ORDER BY created_at DESC
')->fetchAll();

$holdingsByUser = [];
$stmt = $pdo->query('SELECT user_id, symbol, quantity, avg_price FROM holdings');
foreach ($stmt->fetchAll() as $h) {
    $uid = (int)$h['user_id'];
    $price = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $val = (float)$h['quantity'] * $price;
    $cost = (float)$h['quantity'] * (float)$h['avg_price'];
    $holdingsByUser[$uid]['value'] = ($holdingsByUser[$uid]['value'] ?? 0) + $val;
    $holdingsByUser[$uid]['cost']  = ($holdingsByUser[$uid]['cost']  ?? 0) + $cost;
    $holdingsByUser[$uid]['count'] = ($holdingsByUser[$uid]['count'] ?? 0) + 1;
}

$leaderboard = [];
foreach ($users as $u) {
    $uid = (int)$u['id'];
    $hv = $holdingsByUser[$uid]['value'] ?? 0.0;
    $equity = (float)$u['cash_balance'] + $hv;
    $pnl = $equity - STARTING_BALANCE;
    $pnlPct = STARTING_BALANCE > 0 ? ($pnl / STARTING_BALANCE) * 100 : 0;
    $leaderboard[] = [
        'id'         => $uid,
        'username'   => $u['username'],
        'email'      => $u['email'],
        'cash'       => (float)$u['cash_balance'],
        'holdings'   => $hv,
        'equity'     => $equity,
        'pnl'        => $pnl,
        'pnl_pct'    => $pnlPct,
        'positions'  => $holdingsByUser[$uid]['count'] ?? 0,
        'is_admin'   => (int)$u['is_admin'] === 1,
        'created_at' => $u['created_at'],
        'last_seen'  => $u['last_seen'],
        'timezone'   => $u['timezone'] ?? 'UTC',
        'is_online'  => (int)$u['is_online'] === 1,
        'kyc_status' => $u['kyc_status'] ?? 'none',
    ];
}
usort($leaderboard, fn($a, $b) => $b['pnl'] <=> $a['pnl']);

$topSymbols = $pdo->query('
    SELECT symbol, COUNT(*) AS cnt, SUM(total) AS vol
    FROM trades GROUP BY symbol ORDER BY cnt DESC LIMIT 8
')->fetchAll();

$recentTrades = $pdo->query('
    SELECT t.*, u.username
    FROM trades t JOIN users u ON u.id = t.user_id
    ORDER BY t.created_at DESC, t.id DESC LIMIT 15
')->fetchAll();

$recentChat = $pdo->query('
    SELECT id, username, body, symbol, created_at
    FROM chat_messages ORDER BY id DESC LIMIT 20
')->fetchAll();

$buyCount  = (int)$pdo->query("SELECT COUNT(*) FROM trades WHERE side='BUY'")->fetchColumn();
$sellCount = (int)$pdo->query("SELECT COUNT(*) FROM trades WHERE side='SELL'")->fetchColumn();

$platformHoldings = array_sum(array_column($leaderboard, 'holdings'));
$platformEquity   = $totalCash + $platformHoldings;

function fmt_time_in(string $utcTs, string $tz, string $format = 'M j, H:i'): string
{
    try {
        $dt = new DateTimeImmutable($utcTs, new DateTimeZone('UTC'));
        $dt = $dt->setTimezone(new DateTimeZone($tz));
        return $dt->format($format);
    } catch (Throwable $e) { return '—'; }
}

$pageTitle = 'Overview';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Total Users</div>
    <div class="card-value"><?= $totalUsers ?></div>
    <div class="card-sub"><?= $activeUsers ?> active · <?= $todayUsers ?> new today</div>
  </div>
  <div class="card">
    <div class="card-label">Total Trades</div>
    <div class="card-value"><?= $totalTrades ?></div>
    <div class="card-sub"><?= $todayTrades ?> today · <?= $buyCount ?>B / <?= $sellCount ?>S</div>
  </div>
  <div class="card">
    <div class="card-label">Total Volume</div>
    <div class="card-value"><?= e(usd($totalVolume)) ?></div>
    <div class="card-sub">Lifetime traded</div>
  </div>
  <div class="card">
    <div class="card-label">Platform Equity</div>
    <div class="card-value"><?= e(usd($platformEquity)) ?></div>
    <div class="card-sub"><?= e(usd($totalCash)) ?> cash + holdings</div>
  </div>
</div>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">KYC Pending</div>
    <div class="card-value" style="color:<?= $pendingKyc > 0 ? 'var(--accent)' : 'var(--text)' ?>;">
      <?= $pendingKyc ?>
    </div>
    <div class="card-sub">
      <a href="<?= e(APP_URL) ?>/admin-kyc.php" style="color:#93a5ff;">Review queue →</a>
    </div>
  </div>
  <div class="card">
    <div class="card-label">KYC Approved</div>
    <div class="card-value positive"><?= $approvedKyc ?></div>
    <div class="card-sub">Verified users</div>
  </div>
  <div class="card">
    <div class="card-label">KYC Rejected</div>
    <div class="card-value" style="color:var(--red);"><?= $rejectedKyc ?></div>
    <div class="card-sub">Awaiting re-submission</div>
  </div>
  <div class="card">
    <div class="card-label">Platform Status</div>
    <div class="card-value" style="font-size:16px;">
      <span class="badge badge-live">operational</span>
    </div>
    <div class="card-sub">All systems normal</div>
  </div>
</div>

<div class="panel mb-16">
  <div class="panel-header">
    <h2>🏆 User Leaderboard</h2>
    <span class="badge">By total P&amp;L</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Email</th>
          <th>Cash</th>
          <th>Holdings</th>
          <th>Equity</th>
          <th>P&amp;L</th>
          <th>KYC</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($leaderboard as $i => $u):
          if ($u['is_online']) {
              $status = '<span style="color:var(--green);font-weight:700;">🟢 Online</span>';
          } elseif ($u['last_seen']) {
              $status = '<span class="text-dim" style="font-family:var(--mono);font-size:11px;">'
                      . e(fmt_time_in($u['last_seen'], $u['timezone'], 'M j, H:i'))
                      . '</span>';
          } else {
              $status = '<span class="text-dim">—</span>';
          }
          $kycPill = '<span class="kyc-pill ' . e($u['kyc_status']) . '">'
                   . e(ucfirst($u['kyc_status'])) . '</span>';
        ?>
          <tr>
            <td style="font-weight:800;color:<?= $i === 0 ? 'var(--accent)' : 'var(--text-dim)' ?>;">
              <?= $i + 1 ?>
            </td>
            <td style="font-weight:700;"><?= e($u['username']) ?></td>
            <td class="text-dim" style="font-size:12px;"><?= e($u['email']) ?></td>
            <td style="font-family:var(--mono);"><?= e(usd($u['cash'])) ?></td>
            <td style="font-family:var(--mono);">
              <?= e(usd($u['holdings'])) ?>
              <div style="font-size:11px;color:var(--text-faint);"><?= $u['positions'] ?> pos</div>
            </td>
            <td style="font-weight:700;font-family:var(--mono);"><?= e(usd($u['equity'])) ?></td>
            <td style="font-family:var(--mono);font-weight:700;color:<?= $u['pnl'] >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
              <?= ($u['pnl'] >= 0 ? '+' : '-') . e(usd(abs($u['pnl']))) ?>
              <div style="font-size:11px;font-weight:500;">
                <?= ($u['pnl_pct'] >= 0 ? '+' : '') . number_format($u['pnl_pct'], 2) ?>%
              </div>
            </td>
            <td><?= $kycPill ?></td>
            <td><?= $status ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>📊 Most Traded Symbols</h2>
      <span class="badge"><?= count($topSymbols) ?></span>
    </div>
    <?php if (!$topSymbols): ?>
      <div class="empty-state">No trades yet.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr><th>Symbol</th><th>Trades</th><th>Volume</th><th></th></tr>
        </thead>
        <tbody>
          <?php
            $maxCount = max(array_column($topSymbols, 'cnt'));
            foreach ($topSymbols as $s):
              $pct = $maxCount > 0 ? ($s['cnt'] / $maxCount) * 100 : 0;
          ?>
            <tr>
              <td style="font-weight:700;"><?= e($s['symbol']) ?></td>
              <td style="font-family:var(--mono);"><?= (int)$s['cnt'] ?></td>
              <td style="font-family:var(--mono);font-size:12px;"><?= e(usd((float)$s['vol'])) ?></td>
              <td style="width:40%;">
                <div style="height:6px;background:var(--panel-2);border-radius:3px;overflow:hidden;">
                  <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,#5b7cfa,#c4b5fd);"></div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>⚡ Live Activity</h2>
      <span class="badge badge-live">live</span>
    </div>
    <?php if (!$recentTrades): ?>
      <div class="empty-state">No trades yet.</div>
    <?php else: ?>
      <?php foreach ($recentTrades as $t): ?>
        <div style="padding:10px 0;border-bottom:1px solid var(--border);font-size:12px;display:flex;justify-content:space-between;align-items:center;">
          <div>
            <span style="font-weight:700;color:#93a5ff;"><?= e($t['username']) ?></span>
            <span style="color:var(--text-dim);"> · </span>
            <span style="color:<?= $t['side'] === 'BUY' ? 'var(--green)' : 'var(--red)' ?>;font-weight:700;">
              <?= e($t['side']) ?>
            </span>
            <span style="font-family:var(--mono);color:var(--text);">
              <?= number_format((float)$t['quantity'], 4) ?> <?= e($t['symbol']) ?>
            </span>
          </div>
          <div class="text-dim" style="font-size:11px;font-family:var(--mono);">
            <?= e(fmt_time($t['created_at'], 'H:i')) ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<div class="panel mt-24">
  <div class="panel-header">
    <h2>💬 Recent Chat Messages</h2>
    <span class="badge"><?= count($recentChat) ?></span>
  </div>
  <?php if (!$recentChat): ?>
    <div class="empty-state">No chat messages yet.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Time</th><th>User</th><th>Symbol</th><th>Message</th></tr>
        </thead>
        <tbody>
          <?php foreach ($recentChat as $c): ?>
            <tr>
              <td class="text-dim" style="font-size:11px;font-family:var(--mono);">
                <?= e(fmt_time($c['created_at'], 'M j, H:i')) ?>
              </td>
              <td style="font-weight:700;color:#93a5ff;"><?= e($c['username']) ?></td>
              <td>
                <?php if ($c['symbol']): ?>
                  <span class="gchat-sym">$<?= e($c['symbol']) ?></span>
                <?php else: ?>
                  <span class="text-dim">—</span>
                <?php endif; ?>
              </td>
              <td style="max-width:600px;"><?= e($c['body']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>