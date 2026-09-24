<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';

$user = require_login();

/* ============================================================
 *  Compute leaderboard metrics for every user
 * ============================================================ */

$pdo = db();

// Base user list (exclude nothing — admins can participate too)
$users = $pdo->query('
    SELECT id, username, created_at, is_admin
    FROM users
')->fetchAll();

// Aggregate holdings value per user
$holdingsValue = [];
$stmt = $pdo->query('SELECT user_id, symbol, quantity, avg_price FROM holdings');
foreach ($stmt->fetchAll() as $h) {
    $uid = (int)$h['user_id'];
    $price = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $val = (float)$h['quantity'] * $price;
    $holdingsValue[$uid] = ($holdingsValue[$uid] ?? 0) + $val;
}

// Aggregate trade stats per user
$tradeStats = [];
$stmt = $pdo->query('
    SELECT user_id,
           COUNT(*) AS trade_count,
           COALESCE(SUM(pnl), 0) AS realized_pnl,
           COALESCE(SUM(total), 0) AS volume,
           MAX(created_at) AS last_trade
    FROM trades
    GROUP BY user_id
');
foreach ($stmt->fetchAll() as $t) {
    $tradeStats[(int)$t['user_id']] = $t;
}

// Count followers per user (people copying them)
$followerCount = [];
$stmt = $pdo->query('
    SELECT leader_id, COUNT(*) AS cnt
    FROM copy_relationships
    WHERE status = "active"
    GROUP BY leader_id
');
foreach ($stmt->fetchAll() as $f) {
    $followerCount[(int)$f['leader_id']] = (int)$f['cnt'];
}

// Build leaderboard rows
$leaderboard = [];
$now = time();

foreach ($users as $u) {
    $uid = (int)$u['id'];

    // Compute equity
    $stmt = $pdo->prepare('SELECT cash_balance FROM users WHERE id = ?');
    $stmt->execute([$uid]);
    $cash = (float)$stmt->fetchColumn();
    $hv = $holdingsValue[$uid] ?? 0.0;
    $equity = $cash + $hv;

    $pnl = $equity - STARTING_BALANCE;
    $pnlPct = STARTING_BALANCE > 0 ? ($pnl / STARTING_BALANCE) * 100 : 0;

    $ts = $tradeStats[$uid] ?? [
        'trade_count'  => 0,
        'realized_pnl' => 0,
        'volume'       => 0,
        'last_trade'   => null,
    ];

    // Days active since signup
    $daysActive = max(1, (int)floor(($now - strtotime($u['created_at'])) / 86400));

    // Score: weighted blend of ROI% and activity (for ranking ties)
    // Higher PnL% is primary; small bonus for volume to break ties
    $score = $pnlPct + (min(1000, (float)$ts['volume']) / 100000);

    $leaderboard[] = [
        'id'             => $uid,
        'username'       => $u['username'],
        'is_admin'       => (int)$u['is_admin'] === 1,
        'equity'         => $equity,
        'pnl'            => $pnl,
        'pnl_pct'        => $pnlPct,
        'trade_count'    => (int)$ts['trade_count'],
        'realized_pnl'   => (float)$ts['realized_pnl'],
        'volume'         => (float)$ts['volume'],
        'last_trade'     => $ts['last_trade'],
        'followers'      => $followerCount[$uid] ?? 0,
        'days_active'    => $daysActive,
        'score'          => $score,
    ];
}

// Sort by PnL% descending (best first)
usort($leaderboard, fn($a, $b) => $b['score'] <=> $a['score']);

// Add rank
foreach ($leaderboard as $i => &$row) {
    $row['rank'] = $i + 1;
}
unset($row);

// Find current user's row + rank
$myRow = null;
foreach ($leaderboard as $r) {
    if ($r['id'] === (int)$user['id']) { $myRow = $r; break; }
}

// Find who the current user is already copying
$stmt = $pdo->prepare('
    SELECT leader_id, status
    FROM copy_relationships
    WHERE follower_id = ?
');
$stmt->execute([$user['id']]);
$myCopies = [];
foreach ($stmt->fetchAll() as $r) {
    $myCopies[(int)$r['leader_id']] = $r['status'];
}

$pageTitle = 'Leaderboard';
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/leaderboard.css">

<div class="summary-grid">
  <div class="card">
    <div class="card-label">Your Rank</div>
    <div class="card-value" style="font-size:26px;">
      <?php if ($myRow): ?>
        #<?= $myRow['rank'] ?>
      <?php else: ?>
        —
      <?php endif; ?>
    </div>
    <div class="card-sub">
      <?= count($leaderboard) ?> traders total
    </div>
  </div>
  <div class="card">
    <div class="card-label">Your P&amp;L</div>
    <div class="card-value <?= $myRow && $myRow['pnl'] >= 0 ? 'positive' : 'negative' ?>" style="font-size:22px;">
      <?php if ($myRow): ?>
        <?= ($myRow['pnl'] >= 0 ? '+' : '-') . e(usd(abs($myRow['pnl']))) ?>
      <?php else: ?>
        —
      <?php endif; ?>
    </div>
    <div class="card-sub">
      <?= $myRow ? ($myRow['pnl_pct'] >= 0 ? '+' : '') . number_format($myRow['pnl_pct'], 2) . '%' : 'No data' ?>
    </div>
  </div>
  <div class="card">
    <div class="card-label">Top Trader</div>
    <div class="card-value" style="font-size:20px;color:var(--accent);">
      <?= count($leaderboard) ? '@' . e($leaderboard[0]['username']) : '—' ?>
    </div>
    <div class="card-sub">
      <?= count($leaderboard) ? ($leaderboard[0]['pnl_pct'] >= 0 ? '+' : '') . number_format($leaderboard[0]['pnl_pct'], 2) . '%' : '' ?>
    </div>
  </div>
  <div class="card">
    <div class="card-label">Copying</div>
    <div class="card-value" style="font-size:20px;">
      <?= count(array_filter($myCopies, fn($s) => $s === 'active')) ?>
    </div>
    <div class="card-sub">
      Active copies
    </div>
  </div>
</div>

<!-- Filters -->
<div class="panel mb-16">
  <div class="lb-toolbar">
    <div class="lb-toolbar-left">
      <button type="button" class="lb-filter active" data-period="all">All time</button>
      <button type="button" class="lb-filter" data-period="30d">Last 30 days</button>
      <button type="button" class="lb-filter" data-period="7d">This week</button>
    </div>
    <div class="lb-toolbar-right">
      <input type="text" id="lbSearch" placeholder="Search traders…" autocomplete="off">
    </div>
  </div>

  <!-- Podium (top 3) -->
  <?php if (count($leaderboard) >= 3): ?>
    <div class="lb-podium">
      <?php
        $podiumOrder = [1, 0, 2]; // 2nd, 1st, 3rd
        foreach ($podiumOrder as $idx):
          $p = $leaderboard[$idx];
          $place = $idx + 1;
          $medalClass = $place === 1 ? 'gold' : ($place === 2 ? 'silver' : 'bronze');
      ?>
        <div class="lb-podium-item lb-place-<?= $place ?>">
          <div class="lb-medal <?= $medalClass ?>"><?= $place ?></div>
          <div class="lb-podium-avatar">
            <?= e(strtoupper(substr($p['username'], 0, 1))) ?>
          </div>
          <div class="lb-podium-name">
            @<?= e($p['username']) ?>
            <?php if ($p['is_admin']): ?>
              <span class="lb-admin-chip">admin</span>
            <?php endif; ?>
          </div>
          <div class="lb-podium-pnl <?= $p['pnl'] >= 0 ? 'pos' : 'neg' ?>">
            <?= ($p['pnl_pct'] >= 0 ? '+' : '') . number_format($p['pnl_pct'], 2) ?>%
          </div>
          <div class="lb-podium-equity"><?= e(usd($p['equity'])) ?></div>
          <button class="btn btn-sm btn-primary lb-copy-btn"
                  data-leader-id="<?= (int)$p['id'] ?>"
                  data-leader-name="<?= e($p['username']) ?>"
                  data-copied="<?= isset($myCopies[$p['id']]) ? '1' : '0' ?>"
                  <?= (int)$p['id'] === (int)$user['id'] ? 'disabled' : '' ?>>
            <?php if ((int)$p['id'] === (int)$user['id']): ?>
              That's you
            <?php elseif (isset($myCopies[$p['id']]) && $myCopies[$p['id']] === 'active'): ?>
              ✓ Copying
            <?php else: ?>
              Copy trades
            <?php endif; ?>
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Full ranking table -->
<div class="panel">
  <div class="panel-header">
    <h2>🏆 Full Rankings</h2>
    <span class="badge" id="lbRowCount"><?= count($leaderboard) ?> traders</span>
  </div>

  <div class="table-wrap">
    <table class="lb-table">
      <thead>
        <tr>
          <th style="width:50px;">#</th>
          <th>Trader</th>
          <th style="text-align:right;">Equity</th>
          <th style="text-align:right;">P&amp;L</th>
          <th style="text-align:right;">Return</th>
          <th style="text-align:right;">Trades</th>
          <th style="text-align:right;">Volume</th>
          <th style="text-align:right;">Followers</th>
          <th style="width:120px;"></th>
        </tr>
      </thead>
      <tbody id="lbBody">
        <?php foreach ($leaderboard as $r):
          $isMe = $r['id'] === (int)$user['id'];
          $isCopying = isset($myCopies[$r['id']]) && $myCopies[$r['id']] === 'active';
        ?>
          <tr class="<?= $isMe ? 'lb-me' : '' ?>" data-username="<?= e(strtolower($r['username'])) ?>">
            <td class="lb-rank">
              <?php if ($r['rank'] === 1): ?>
                <span class="lb-rank-medal gold">1</span>
              <?php elseif ($r['rank'] === 2): ?>
                <span class="lb-rank-medal silver">2</span>
              <?php elseif ($r['rank'] === 3): ?>
                <span class="lb-rank-medal bronze">3</span>
              <?php else: ?>
                <span class="lb-rank-num"><?= $r['rank'] ?></span>
              <?php endif; ?>
            </td>
            <td>
              <div class="lb-user">
                <div class="lb-user-avatar"><?= e(strtoupper(substr($r['username'], 0, 1))) ?></div>
                <div>
                  <div class="lb-user-name">
                    @<?= e($r['username']) ?>
                    <?php if ($isMe): ?><span class="lb-me-chip">you</span><?php endif; ?>
                    <?php if ($r['is_admin']): ?><span class="lb-admin-chip">admin</span><?php endif; ?>
                  </div>
                  <div class="lb-user-meta">
                    <?= $r['days_active'] ?>d active
                    <?php if ($r['last_trade']): ?>
                      · last trade <?= e(time_ago($r['last_trade'])) ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </td>
            <td style="text-align:right;font-family:var(--mono);">
              <?= e(usd($r['equity'])) ?>
            </td>
            <td style="text-align:right;font-family:var(--mono);font-weight:700;color:<?= $r['pnl'] >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
              <?= ($r['pnl'] >= 0 ? '+' : '-') . e(usd(abs($r['pnl']))) ?>
            </td>
            <td style="text-align:right;font-family:var(--mono);font-weight:700;color:<?= $r['pnl_pct'] >= 0 ? 'var(--green)' : 'var(--red)' ?>;">
              <?= ($r['pnl_pct'] >= 0 ? '+' : '') . number_format($r['pnl_pct'], 2) ?>%
            </td>
            <td style="text-align:right;font-family:var(--mono);color:var(--text-dim);">
              <?= $r['trade_count'] ?>
            </td>
            <td style="text-align:right;font-family:var(--mono);color:var(--text-dim);">
              <?= e(usd($r['volume'])) ?>
            </td>
            <td style="text-align:right;">
              <?php if ($r['followers'] > 0): ?>
                <span class="lb-followers-badge">👥 <?= $r['followers'] ?></span>
              <?php else: ?>
                <span class="text-dim">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($isMe): ?>
                <button class="btn btn-sm" disabled>That's you</button>
              <?php elseif ($isCopying): ?>
                <button class="btn btn-sm lb-copy-btn" data-leader-id="<?= (int)$r['id'] ?>" data-leader-name="<?= e($r['username']) ?>" data-copied="1">
                  ✓ Copying
                </button>
              <?php else: ?>
                <button class="btn btn-sm btn-primary lb-copy-btn" data-leader-id="<?= (int)$r['id'] ?>" data-leader-name="<?= e($r['username']) ?>" data-copied="0">
                  Copy trades
                </button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  window.LB_API  = <?= json_encode(APP_URL) ?>;
  window.LB_CSRF = <?= json_encode(csrf_token()) ?>;
  window.LB_ME   = <?= json_encode((int)$user['id']) ?>;
</script>
<script src="<?= e(APP_URL) ?>/assets/js/leaderboard.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>