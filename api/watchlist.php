<?php
/**
 * /api/watchlist.php
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

/* ---------- LIST ---------- */
if ($action === 'list') {
    $stmt = db()->prepare('
        SELECT symbol, note, created_at
        FROM watchlist
        WHERE user_id = ?
        ORDER BY created_at DESC
    ');
    $stmt->execute([$user['id']]);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$r) {
        $r['price'] = get_price($r['symbol']);
    }
    unset($r);

    json_response(['items' => $rows]);
}

/* ---------- HAS ---------- */
if ($action === 'has') {
    $symbol = strtoupper(trim((string)($_GET['symbol'] ?? '')));
    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);

    $stmt = db()->prepare('SELECT 1 FROM watchlist WHERE user_id = ? AND symbol = ? LIMIT 1');
    $stmt->execute([$user['id'], $symbol]);
    json_response(['has' => (bool)$stmt->fetchColumn()]);
}

/* ---------- COUNT ---------- */
if ($action === 'count') {
    $stmt = db()->prepare('SELECT COUNT(*) FROM watchlist WHERE user_id = ?');
    $stmt->execute([$user['id']]);
    json_response(['count' => (int)$stmt->fetchColumn()]);
}

/* ---------- TOGGLE ---------- */
if ($action === 'toggle') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $symbol = strtoupper(trim((string)($_POST['symbol'] ?? '')));
    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);
    if (get_price($symbol) === null) json_response(['error' => 'Unknown symbol'], 400);

    $stmt = db()->prepare('SELECT id FROM watchlist WHERE user_id = ? AND symbol = ? LIMIT 1');
    $stmt->execute([$user['id'], $symbol]);
    $existing = $stmt->fetch();

    if ($existing) {
        db()->prepare('DELETE FROM watchlist WHERE id = ?')->execute([$existing['id']]);
        json_response(['success' => true, 'watching' => false, 'message' => $symbol . ' removed from watchlist']);
    } else {
        db()->prepare('INSERT INTO watchlist (user_id, symbol) VALUES (?, ?)')
            ->execute([$user['id'], $symbol]);
        json_response(['success' => true, 'watching' => true, 'message' => $symbol . ' added to watchlist']);
    }
}

/* ---------- ADD ---------- */
if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $symbol = strtoupper(trim((string)($_POST['symbol'] ?? '')));
    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);
    if (get_price($symbol) === null) json_response(['error' => 'Unknown symbol'], 400);

    db()->prepare('INSERT IGNORE INTO watchlist (user_id, symbol) VALUES (?, ?)')
        ->execute([$user['id'], $symbol]);

    json_response(['success' => true, 'watching' => true, 'message' => $symbol . ' added']);
}

/* ---------- REMOVE ---------- */
if ($action === 'remove') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $symbol = strtoupper(trim((string)($_POST['symbol'] ?? '')));
    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);

    db()->prepare('DELETE FROM watchlist WHERE user_id = ? AND symbol = ?')
        ->execute([$user['id'], $symbol]);

    json_response(['success' => true, 'watching' => false, 'message' => $symbol . ' removed']);
}

/* ---------- NOTE ---------- */
if ($action === 'note') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $symbol = strtoupper(trim((string)($_POST['symbol'] ?? '')));
    $note   = trim((string)($_POST['note'] ?? ''));
    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);
    if (strlen($note) > 200) $note = substr($note, 0, 200);

    $stmt = db()->prepare('UPDATE watchlist SET note = ? WHERE user_id = ? AND symbol = ?');
    $stmt->execute([$note !== '' ? $note : null, $user['id'], $symbol]);

    if ($stmt->rowCount() === 0) json_response(['error' => 'Symbol not in watchlist'], 404);

    json_response(['success' => true, 'message' => 'Note saved']);
}

json_response(['error' => 'Unknown action: ' . $action], 400);