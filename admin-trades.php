<?php
require_once __DIR__ . '/includes/auth.php';

$user = require_login();
if ((int)$user['is_admin'] !== 1) {
    flash_set('error', 'Admin access required.');
    redirect(APP_URL . '/dashboard.php');
}

$pdo = db();

// Filters
$filterUser   = (int)($_GET['user_id'] ?? 0);
$filterSymbol = strtoupper(trim((string)($_GET['symbol'] ?? '')));
$filterSide   = strtoupper(trim((string)($_GET['side'] ?? '')));

$where  = [];
$params = [];

if ($filterUser > 0) {
    $where[]  = 't.user_id = ?';
    $params[] = $filterUser;
}
if ($filterSymbol !== '') {
    $where[]  = 't.symbol = ?';
    $params[] = $filterSymbol;
}
if (in_array($filterSide, ['BUY', 'SELL'], true)) {
    $where[]  = 't.side = ?';
    $params[] = $filterSide;
}

$sql = 'SELECT t.id, t.user_id, t.symbol, t.side, t.quantity, t.price, t.total, t.pnl, t.created_at, u.username
        FROM trades t
        JOIN users u ON u.id = t.user_id';

if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY t.created_at DESC, t.id DESC LIMIT 200';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$trades = $stmt->fetchAll();

// Stats
$totalTrades  = (int)$pdo->query('SELECT COUNT(*) FROM trades')->fetchColumn();
$totalBuy     = (int)$pdo->query("SELECT COUNT(*) FROM trades WHERE side='BUY'")->fetchColumn();
$totalSell    = (int)$pdo->query("SELECT COUNT(*) FROM trades WHERE side='SELL'")->fetchColumn();
$totalVolume  = (float)$pdo->query('SELECT COALESCE(SUM(total),0) FROM trades')->fetchColumn();
$realizedPnl  = (float)$pdo->query('SELECT COALESCE(SUM(pnl),0) FROM trades WHERE pnl IS NOT NULL')->fetchColumn();

// Distinct symbols + users for filter dropdowns
$allSymbols = $pdo->query('SELECT DISTINCT symbol FROM trades ORDER BY symbol')->fetchAll(PDO::FETCH_COLUMN);
$allUsers   = $pdo->query('SELECT id, username FROM users ORDER BY username')->fetchAll();

$pageTitle = 'Trades';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Total Trades</div>
    <div class="card-value"><?= $totalTrades ?></div>
    <div class="card-sub"><?= $totalBuy ?> buys · <?= $totalSell ?> sells</div>
  </div>
  <div class="card">
    <div class="card-label">Total Volume</div>
    <div class="card-value"><?= e(usd($totalVolume)) ?></div>
    <div class="card-sub">Lifetime</div>
  </div>
  <div class="card">
    <div class="card-label">Realized P&amp;L</div>
    <div class="card-value <?= $realizedPnl >= 0 ? 'positive' : 'negative' ?>">
      <?= ($realizedPnl >= 0 ? '+' : '-') . e(usd(abs($realizedPnl))) ?>
    </div>
    <div class="card-sub">From all sell orders</div>
  </div>
  <div class="card">
    <div class="card-label">Showing</div>
    <div class="card-value"><?= count($trades) ?></div>
    <div class="card-sub">Most recent (limit 200)</div>
  </div>
</div>

<div class="panel mb-16">
  <div class="panel-header">
    <h2>📈 Trade Log</h2>
    <form method="get" style="display:flex;gap:8px;flex-wrap:wrap;">
      <select name="user_id" style="background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;">
        <option value="0">All users</option>
        <?php foreach ($allUsers as $u): ?>
          <option value="<?= (int)$u['id'] ?>" <?= $filterUser === (int)$u['id'] ? 'selected' : '' ?>>
            <?= e($u['username']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="symbol" style="background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;">
        <option value="">All symbols</option>
        <?php foreach ($allSymbols as $s): ?>
          <option value="<?= e($s) ?>" <?= $filterSymbol === $s ? 'selected' : '' ?>>
            <?= e($s) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="side" style="background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;">
        <option value="">Both sides</option>
        <option value="BUY"  <?= $filterSide === 'BUY'  ? 'selected' : '' ?>>BUY</option>
        <option value="SELL" <?= $filterSide === 'SELL' ? 'selected' : '' ?>>SELL</option>
      </select>

      <button type="submit" class="btn btn-sm btn-primary">Filter</button>
      <a href="<?= e(APP_URL) ?>/admin-trades.php" class="btn btn-sm">Clear</a>
    </form>
  </div>

  <?php if (!$trades): ?>
    <div class="empty-state">No trades match those filters.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Time</th>
            <th>User</th>
            <th>Symbol</th>
            <th>Side</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
            <th>P&amp;L</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($trades as $t): ?>
            <tr>
              <td class="text-dim" style="font-family:var(--mono);font-size:11px;">#<?= (int)$t['id'] ?></td>
              <td class="text-dim" style="font-family:var(--mono);font-size:11px;">
                <?= e(fmt_time($t['created_at'], 'M j, H:i')) ?>
              </td>
              <td style="font-weight:600;">
                <a href="<?= e(APP_URL) ?>/admin-trades.php?user_id=<?= (int)$t['user_id'] ?>"
                   style="color:#93a5ff;text-decoration:none;">
                  <?= e($t['username']) ?>
                </a>
              </td>
              <td style="font-weight:700;"><?= e($t['symbol']) ?></td>
              <td style="font-weight:700;color:<?= $t['side'] === 'BUY' ? 'var(--green)' : 'var(--red)' ?>;">
                <?= e($t['side']) ?>
              </td>
              <td style="font-family:var(--mono);"><?= number_format((float)$t['quantity'], 4) ?></td>
              <td style="font-family:var(--mono);"><?= e(price_fmt((float)$t['price'])) ?></td>
              <td style="font-family:var(--mono);"><?= e(usd((float)$t['total'])) ?></td>
              <td>
                <?php if ($t['pnl'] === null): ?>
                  <span class="text-dim">—</span>
                <?php else:
                  $pnl = (float)$t['pnl'];
                ?>
                  <span style="font-weight:700;font-family:var(--mono);color:<?= $pnl >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
                    <?= ($pnl >= 0 ? '+' : '-') . e(usd(abs($pnl))) ?>
                  </span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>