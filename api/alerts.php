<?php
/**
 * /api/alerts.php
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

/* ---------- LIST ---------- */
if ($action === 'list') {
    $stmt = db()->prepare('
        SELECT id, symbol, condition_type, target_price, status, note,
               created_at, triggered_at, trigger_price
        FROM price_alerts
        WHERE user_id = ?
        ORDER BY
          FIELD(status, "active","triggered","cancelled"),
          created_at DESC
    ');
    $stmt->execute([$user['id']]);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$r) {
        $live = get_price($r['symbol']);
        $r['current_price'] = $live;
        if ($live && $r['status'] === 'active') {
            $target = (float)$r['target_price'];
            $r['distance_pct'] = $target > 0 ? (($live - $target) / $target) * 100 : 0;
        } else {
            $r['distance_pct'] = null;
        }
    }
    unset($r);

    json_response(['alerts' => $rows]);
}

/* ---------- COUNT ---------- */
if ($action === 'count') {
    $stmt = db()->prepare('SELECT COUNT(*) FROM price_alerts WHERE user_id = ? AND status = "active"');
    $stmt->execute([$user['id']]);
    json_response(['active' => (int)$stmt->fetchColumn()]);
}

/* ---------- CREATE ---------- */
if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $symbol    = strtoupper(trim((string)($_POST['symbol'] ?? '')));
    $condition = strtolower(trim((string)($_POST['condition_type'] ?? '')));
    $target    = (float)($_POST['target_price'] ?? 0);
    $note      = trim((string)($_POST['note'] ?? ''));

    if ($symbol === '')                                        json_response(['error' => 'Symbol required'], 400);
    if (!in_array($condition, ['above', 'below'], true))       json_response(['error' => 'Condition must be above or below'], 400);
    if ($target <= 0)                                          json_response(['error' => 'Target price must be greater than zero'], 400);
    if (strlen($note) > 200) $note = substr($note, 0, 200);

    $live = get_price($symbol);
    if ($live === null)                                        json_response(['error' => 'Unknown symbol'], 400);

    $stmt = db()->prepare('SELECT COUNT(*) FROM price_alerts WHERE user_id = ? AND status = "active"');
    $stmt->execute([$user['id']]);
    if ((int)$stmt->fetchColumn() >= 30) {
        json_response(['error' => 'You already have 30 active alerts. Cancel some first.'], 400);
    }

    $stmt = db()->prepare('
        INSERT INTO price_alerts (user_id, symbol, condition_type, target_price, note)
        VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([$user['id'], $symbol, $condition, $target, $note ?: null]);

    json_response([
        'success' => true,
        'id'      => (int)db()->lastInsertId(),
        'message' => 'Alert set for ' . $symbol . ' ' . $condition . ' ' . price_fmt($target),
    ]);
}

/* ---------- CANCEL ---------- */
if ($action === 'cancel') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $id = (int)($_POST['alert_id'] ?? 0);
    if ($id <= 0) json_response(['error' => 'Invalid alert'], 400);

    $stmt = db()->prepare('UPDATE price_alerts SET status = "cancelled"
                           WHERE id = ? AND user_id = ? AND status = "active"');
    $stmt->execute([$id, $user['id']]);

    if ($stmt->rowCount() === 0) json_response(['error' => 'Alert not found or already inactive'], 404);
    json_response(['success' => true, 'message' => 'Alert cancelled']);
}

/* ---------- DELETE ---------- */
if ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $id = (int)($_POST['alert_id'] ?? 0);
    if ($id <= 0) json_response(['error' => 'Invalid alert'], 400);

    $stmt = db()->prepare('DELETE FROM price_alerts WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $user['id']]);

    if ($stmt->rowCount() === 0) json_response(['error' => 'Alert not found'], 404);
    json_response(['success' => true, 'message' => 'Alert deleted']);
}

/* ---------- CHECK ---------- */
if ($action === 'check') {
    $stmt = db()->prepare('
        SELECT id, symbol, condition_type, target_price
        FROM price_alerts
        WHERE user_id = ? AND status = "active"
    ');
    $stmt->execute([$user['id']]);
    $active = $stmt->fetchAll();

    $fired = [];

    foreach ($active as $a) {
        $price = get_price($a['symbol']);
        if ($price === null) continue;

        $target = (float)$a['target_price'];
        $hit = false;

        if ($a['condition_type'] === 'above' && $price >= $target) $hit = true;
        if ($a['condition_type'] === 'below' && $price <= $target) $hit = true;

        if ($hit) {
            db()->prepare('UPDATE price_alerts
                           SET status = "triggered", triggered_at = UTC_TIMESTAMP(), trigger_price = ?
                           WHERE id = ?')
                ->execute([$price, $a['id']]);

            $fired[] = [
                'id'            => (int)$a['id'],
                'symbol'        => $a['symbol'],
                'condition'     => $a['condition_type'],
                'target'        => $target,
                'trigger_price' => $price,
                'message'       => $a['symbol'] . ' is now ' . $a['condition_type'] . ' ' . price_fmt($target)
                                   . ' (current: ' . price_fmt($price) . ')',
            ];
        }
    }

    json_response(['fired' => $fired]);
}

json_response(['error' => 'Unknown action: ' . $action], 400);