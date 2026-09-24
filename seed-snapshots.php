<?php
/**
 * One-time script: seeds the last 30 days of portfolio snapshots
 * for the currently-logged-in user. Produces a realistic equity curve.
 * DELETE THIS FILE after running it once.
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/market.php';

$user = require_login();

$stmt = db()->prepare('SELECT cash_balance FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$cash = (float)$stmt->fetchColumn();

$stmt = db()->prepare('SELECT symbol, quantity, avg_price FROM holdings WHERE user_id = ?');
$stmt->execute([$user['id']]);
$holdingsValue = 0.0;
foreach ($stmt->fetchAll() as $h) {
    $price = get_price($h['symbol']) ?? (float)$h['avg_price'];
    $holdingsValue += (float)$h['quantity'] * (float)$price;
}
$todayEquity = $cash + $holdingsValue;
$startBalance = (float)STARTING_BALANCE;

$days = 30;
$seed = crc32((string)$user['id']);

function prng(&$s) {
    $s = ($s * 1103515245 + 12345) & 0x7fffffff;
    return $s / 0x7fffffff;
}

$rows = [];
$equity = $todayEquity;
for ($i = 0; $i <= $days; $i++) {
    $date = gmdate('Y-m-d', strtotime("-{$i} days"));
    $rows[$date] = $equity;

    $r = prng($seed);
    $step = ($r - 0.48) * 0.012;
    if (prng($seed) < 0.08) $step = ($r - 0.5) * 0.03;
    $equity = $equity / (1 + $step);

    if ($i > 15) {
        $drift = 0.15;
        $equity = $equity * (1 - $drift) + $startBalance * $drift;
    }
}

$oldest = array_key_last($rows);
$rows[$oldest] = $startBalance;

$inserted = 0; $updated = 0;
foreach ($rows as $date => $eq) {
    $ratio = $todayEquity > 0 ? $holdingsValue / $todayEquity : 0;
    $hv = $eq * $ratio;
    $cs = $eq - $hv;

    $stmt = db()->prepare('SELECT id FROM portfolio_snapshots WHERE user_id = ? AND snapshot_date = ?');
    $stmt->execute([$user['id'], $date]);
    $exists = $stmt->fetch();

    if ($exists) {
        db()->prepare('UPDATE portfolio_snapshots SET equity = ?, cash = ?, holdings_value = ? WHERE id = ?')
            ->execute([$eq, $cs, $hv, $exists['id']]);
        $updated++;
    } else {
        db()->prepare('INSERT INTO portfolio_snapshots (user_id, snapshot_date, equity, cash, holdings_value)
                       VALUES (?, ?, ?, ?, ?)')
            ->execute([$user['id'], $date, $eq, $cs, $hv]);
        $inserted++;
    }
}

echo "<h2 style='font-family:sans-serif;'>Snapshot seeding complete</h2>";
echo "<p style='font-family:sans-serif;'>User: <strong>" . e($user['username']) . "</strong></p>";
echo "<p style='font-family:sans-serif;'>Inserted: <strong>{$inserted}</strong> · Updated: <strong>{$updated}</strong> · Total days: <strong>" . ($inserted + $updated) . "</strong></p>";

echo "<table border='1' cellpadding='6' style='border-collapse:collapse;font-family:monospace;font-size:13px;'>";
echo "<tr><th>Date</th><th>Equity</th></tr>";
foreach (array_reverse($rows) as $date => $eq) {
    echo "<tr><td>" . e($date) . "</td><td>$" . number_format($eq, 2) . "</td></tr>";
}
echo "</table>";

echo "<p style='font-family:sans-serif;color:green;margin-top:16px;'><strong>Open <a href='" . e(APP_URL) . "/portfolio.php'>Portfolio</a> to see the chart.</strong></p>";
echo "<p style='font-family:sans-serif;color:#f6465d;'><strong>Delete this file after running: <code>del seed-snapshots.php</code></strong></p>";