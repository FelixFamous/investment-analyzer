<?php
/**
 * /api/drawings.php
 *
 * GET  ?symbol=BTC                 → list drawings for this user + symbol
 * POST action=create  symbol, tool_type, p1_time, p1_price, p2_time, p2_price, color, label
 * POST action=delete  id
 * POST action=clear   symbol       → delete all drawings for this symbol
 */
require_once __DIR__ . '/../includes/auth.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$method = $_SERVER['REQUEST_METHOD'];

/* ============================================================
 *  LIST
 * ============================================================ */
if ($method === 'GET') {
    $symbol = strtoupper(trim((string)($_GET['symbol'] ?? '')));
    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);

    $stmt = db()->prepare('
        SELECT id, tool_type, p1_time, p1_price, p2_time, p2_price, color, label, created_at
        FROM chart_drawings
        WHERE user_id = ? AND symbol = ?
        ORDER BY created_at ASC
    ');
    $stmt->execute([$user['id'], $symbol]);
    $rows = $stmt->fetchAll();

    // Cast numerics so JSON is clean
    foreach ($rows as &$r) {
        $r['id'] = (int)$r['id'];
        $r['p1_time']  = $r['p1_time']  !== null ? (int)$r['p1_time'] : null;
        $r['p2_time']  = $r['p2_time']  !== null ? (int)$r['p2_time'] : null;
        $r['p1_price'] = $r['p1_price'] !== null ? (float)$r['p1_price'] : null;
        $r['p2_price'] = $r['p2_price'] !== null ? (float)$r['p2_price'] : null;
    }
    unset($r);

    json_response(['drawings' => $rows]);
}

/* ============================================================
 *  POST — create / delete / clear
 * ============================================================ */
if ($method !== 'POST') json_response(['error' => 'Method not allowed'], 405);

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    json_response(['error' => 'CSRF mismatch'], 419);
}

$action = (string)($_POST['action'] ?? '');

/* ---------- CREATE ---------- */
if ($action === 'create') {
    $symbol    = strtoupper(trim((string)($_POST['symbol'] ?? '')));
    $tool      = trim((string)($_POST['tool_type'] ?? ''));
    $p1_time   = isset($_POST['p1_time'])  && $_POST['p1_time']  !== '' ? (int)$_POST['p1_time']  : null;
    $p1_price  = isset($_POST['p1_price']) && $_POST['p1_price'] !== '' ? (float)$_POST['p1_price'] : null;
    $p2_time   = isset($_POST['p2_time'])  && $_POST['p2_time']  !== '' ? (int)$_POST['p2_time']  : null;
    $p2_price  = isset($_POST['p2_price']) && $_POST['p2_price'] !== '' ? (float)$_POST['p2_price'] : null;
    $color     = trim((string)($_POST['color'] ?? '#f0b90b'));
    $label     = trim((string)($_POST['label'] ?? ''));

    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);
    if (!in_array($tool, ['trendline','hline','rect','fib'], true)) json_response(['error' => 'Invalid tool'], 400);
    if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) $color = '#f0b90b';
    if (strlen($label) > 60) $label = substr($label, 0, 60);

    // Validation per tool
    if ($tool === 'hline') {
        if ($p1_price === null) json_response(['error' => 'Horizontal line requires a price'], 400);
    } else {
        if ($p1_time === null || $p1_price === null || $p2_time === null || $p2_price === null) {
            json_response(['error' => 'Two points required'], 400);
        }
    }

    $stmt = db()->prepare('
        INSERT INTO chart_drawings
        (user_id, symbol, tool_type, p1_time, p1_price, p2_time, p2_price, color, label)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $user['id'], $symbol, $tool,
        $p1_time, $p1_price, $p2_time, $p2_price,
        $color, $label !== '' ? $label : null,
    ]);

    json_response(['success' => true, 'id' => (int)db()->lastInsertId()]);
}

/* ---------- DELETE ---------- */
if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) json_response(['error' => 'Invalid id'], 400);

    $stmt = db()->prepare('DELETE FROM chart_drawings WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $user['id']]);

    if ($stmt->rowCount() === 0) json_response(['error' => 'Drawing not found'], 404);
    json_response(['success' => true]);
}

/* ---------- CLEAR ALL ---------- */
if ($action === 'clear') {
    $symbol = strtoupper(trim((string)($_POST['symbol'] ?? '')));
    if ($symbol === '') json_response(['error' => 'Symbol required'], 400);

    $stmt = db()->prepare('DELETE FROM chart_drawings WHERE user_id = ? AND symbol = ?');
    $stmt->execute([$user['id'], $symbol]);

    json_response(['success' => true, 'removed' => $stmt->rowCount()]);
}

json_response(['error' => 'Unknown action'], 400);