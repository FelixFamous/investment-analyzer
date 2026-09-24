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
$searchBody   = trim((string)($_GET['q'] ?? ''));

$where  = [];
$params = [];

if ($filterUser > 0) {
    $where[]  = 'user_id = ?';
    $params[] = $filterUser;
}
if ($filterSymbol !== '') {
    $where[]  = 'symbol = ?';
    $params[] = $filterSymbol;
}
if ($searchBody !== '') {
    $where[]  = 'body LIKE ?';
    $params[] = '%' . $searchBody . '%';
}

$sql = 'SELECT id, user_id, username, body, symbol, created_at FROM chat_messages';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY id DESC LIMIT 200';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

// Stats
$totalMsgs   = (int)$pdo->query('SELECT COUNT(*) FROM chat_messages')->fetchColumn();
$todayMsgs   = (int)$pdo->query('SELECT COUNT(*) FROM chat_messages WHERE DATE(created_at) = CURDATE()')->fetchColumn();
$uniqueUsers = (int)$pdo->query('SELECT COUNT(DISTINCT user_id) FROM chat_messages')->fetchColumn();

// Top chat participants
$topChatters = $pdo->query('
    SELECT username, COUNT(*) AS cnt
    FROM chat_messages
    GROUP BY user_id, username
    ORDER BY cnt DESC
    LIMIT 6
')->fetchAll();

// Distinct symbols used in chat
$chatSymbols = $pdo->query('
    SELECT DISTINCT symbol FROM chat_messages
    WHERE symbol IS NOT NULL AND symbol <> ""
    ORDER BY symbol
')->fetchAll(PDO::FETCH_COLUMN);

// Distinct users for filter
$chatUsers = $pdo->query('
    SELECT DISTINCT user_id, username
    FROM chat_messages
    ORDER BY username
')->fetchAll();

$pageTitle = 'Chat Log';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Total Messages</div>
    <div class="card-value"><?= $totalMsgs ?></div>
    <div class="card-sub"><?= $todayMsgs ?> today</div>
  </div>
  <div class="card">
    <div class="card-label">Active Chatters</div>
    <div class="card-value"><?= $uniqueUsers ?></div>
    <div class="card-sub">Unique participants</div>
  </div>
  <div class="card">
    <div class="card-label">Symbols Mentioned</div>
    <div class="card-value"><?= count($chatSymbols) ?></div>
    <div class="card-sub">Tagged with $SYM</div>
  </div>
  <div class="card">
    <div class="card-label">Showing</div>
    <div class="card-value"><?= count($messages) ?></div>
    <div class="card-sub">Most recent (limit 200)</div>
  </div>
</div>

<div class="main-grid">
  <div class="panel">
    <div class="panel-header">
      <h2>💬 Chat Log</h2>
      <form method="get" style="display:flex;gap:8px;flex-wrap:wrap;">
        <select name="user_id" style="background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;">
          <option value="0">All users</option>
          <?php foreach ($chatUsers as $u): ?>
            <option value="<?= (int)$u['user_id'] ?>" <?= $filterUser === (int)$u['user_id'] ? 'selected' : '' ?>>
              <?= e($u['username']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <select name="symbol" style="background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;">
          <option value="">All symbols</option>
          <?php foreach ($chatSymbols as $s): ?>
            <option value="<?= e($s) ?>" <?= $filterSymbol === $s ? 'selected' : '' ?>>
              $<?= e($s) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <input type="text" name="q" placeholder="Search body…"
               value="<?= e($searchBody) ?>"
               style="background:var(--bg-2);border:1px solid var(--border-2);border-radius:8px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;min-width:180px;">

        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        <a href="<?= e(APP_URL) ?>/admin-chat.php" class="btn btn-sm">Clear</a>
      </form>
    </div>

    <?php if (!$messages): ?>
      <div class="empty-state">No messages match those filters.</div>
    <?php else: ?>
      <?php foreach ($messages as $m): ?>
        <div style="padding:12px 0;border-bottom:1px solid var(--border);">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
            <span style="font-weight:700;color:#93a5ff;"><?= e($m['username']) ?></span>
            <?php if (!empty($m['symbol'])): ?>
              <span class="gchat-sym">$<?= e($m['symbol']) ?></span>
            <?php endif; ?>
            <span class="text-dim" style="font-size:11px;font-family:var(--mono);margin-left:auto;">
              <?= e(fmt_time($m['created_at'], 'M j, H:i')) ?>
            </span>
          </div>
          <div style="font-size:13px;line-height:1.5;color:var(--text);">
            <?= e($m['body']) ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="panel">
    <div class="panel-header">
      <h2>🏅 Top Chatters</h2>
      <span class="badge">By message count</span>
    </div>
    <?php if (!$topChatters): ?>
      <div class="empty-state">No messages yet.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr><th>#</th><th>User</th><th>Messages</th><th></th></tr>
        </thead>
        <tbody>
          <?php
            $maxCnt = max(array_column($topChatters, 'cnt'));
            foreach ($topChatters as $i => $c):
              $pct = $maxCnt > 0 ? ($c['cnt'] / $maxCnt) * 100 : 0;
          ?>
            <tr>
              <td style="font-weight:800;color:<?= $i === 0 ? '#93a5ff' : 'var(--text-dim)' ?>;">
                <?= $i + 1 ?>
              </td>
              <td style="font-weight:600;"><?= e($c['username']) ?></td>
              <td style="font-family:var(--mono);"><?= (int)$c['cnt'] ?></td>
              <td style="width:30%;">
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
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>