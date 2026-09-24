<?php
/**
 * /api/copy-trade.php
 *
 * Actions:
 *   POST action=start   leader_id, copy_ratio, max_per_trade
 *   POST action=stop    leader_id
 *   POST action=pause   leader_id
 *   POST action=resume  leader_id
 *   GET  ?action=list           → my relationships (as follower)
 *   GET  ?action=followers      → people copying me (as leader)
 *   GET  ?action=status&leader=X→ relationship with X
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/market.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

/* ============================================================
 *  LIST — relationships where I'm the follower
 * ============================================================ */
if ($action === 'list') {
    $stmt = db()->prepare('
        SELECT cr.id, cr.leader_id, cr.status, cr.copy_ratio, cr.max_per_trade,
               cr.created_at, cr.stopped_at,
               u.username AS leader_username,
               (SELECT COUNT(*) FROM trades t
                WHERE t.copy_relationship_id = cr.id) AS copies_made
        FROM copy_relationships cr
        JOIN users u ON u.id = cr.leader_id
        WHERE cr.follower_id = ?
        ORDER BY cr.created_at DESC
    ');
    $stmt->execute([$user['id']]);
    json_response(['items' => $stmt->fetchAll()]);
}

/* ============================================================
 *  FOLLOWERS — people copying me
 * ============================================================ */
if ($action === 'followers') {
    $stmt = db()->prepare('
        SELECT cr.id, cr.follower_id, cr.status, cr.copy_ratio, cr.max_per_trade,
               cr.created_at,
               u.username AS follower_username,
               (SELECT COUNT(*) FROM trades t
                WHERE t.copy_relationship_id = cr.id) AS copies_made
        FROM copy_relationships cr
        JOIN users u ON u.id = cr.follower_id
        WHERE cr.leader_id = ?
        ORDER BY cr.created_at DESC
    ');
    $stmt->execute([$user['id']]);
    json_response(['items' => $stmt->fetchAll()]);
}

/* ============================================================
 *  STATUS
 * ============================================================ */
if ($action === 'status') {
    $leaderId = (int)($_GET['leader_id'] ?? 0);
    if ($leaderId <= 0) json_response(['error' => 'Invalid leader'], 400);

    $stmt = db()->prepare('SELECT status, copy_ratio, max_per_trade FROM copy_relationships
                           WHERE follower_id = ? AND leader_id = ? LIMIT 1');
    $stmt->execute([$user['id'], $leaderId]);
    $rel = $stmt->fetch();
    json_response(['relationship' => $rel ?: null]);
}

/* ============================================================
 *  START
 * ============================================================ */
if ($action === 'start') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

    $leaderId = (int)($_POST['leader_id'] ?? 0);
    $ratio    = (float)($_POST['copy_ratio'] ?? 100);
    $maxRaw   = trim((string)($_POST['max_per_trade'] ?? ''));
    $maxPer   = $maxRaw === '' ? null : (float)$maxRaw;

    if ($leaderId <= 0)                          json_response(['error' => 'Invalid leader'], 400);
    if ($leaderId === (int)$user['id'])          json_response(['error' => 'You cannot copy yourself'], 400);
    if ($ratio < 1 || $ratio > 200)              json_response(['error' => 'Copy ratio must be between 1% and 200%'], 400);
    if ($maxPer !== null && $maxPer < 0)         json_response(['error' => 'Max per trade must be positive'], 400);

    // Leader must exist
    $stmt = db()->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$leaderId]);
    if (!$stmt->fetchColumn()) json_response(['error' => 'Leader not found'], 404);

    // Upsert
    $stmt = db()->prepare('
        INSERT INTO copy_relationships (follower_id, leader_id, status, copy_ratio, max_per_trade)
        VALUES (?, ?, "active", ?, ?)
        ON DUPLICATE KEY UPDATE
            status = "active",
            copy_ratio = VALUES(copy_ratio),
            max_per_trade = VALUES(max_per_trade),
            stopped_at = NULL
    ');
    $stmt->execute([$user['id'], $leaderId, $ratio, $maxPer]);

    json_response([
        'success' => true,
        'message' => 'Now copying this trader at ' . number_format($ratio, 0) . '%'
                     . ($maxPer !== null ? ' (max ' . usd($maxPer) . ' per trade)' : ''),
    ]);
}

/* ============================================================
 *  STOP
 * ============================================================ */
if ($action === 'stop') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

    $leaderId = (int)($_POST['leader_id'] ?? 0);
    if ($leaderId <= 0) json_response(['error' => 'Invalid leader'], 400);

    $stmt = db()->prepare('UPDATE copy_relationships
                           SET status = "stopped", stopped_at = UTC_TIMESTAMP()
                           WHERE follower_id = ? AND leader_id = ? AND status != "stopped"');
    $stmt->execute([$user['id'], $leaderId]);

    if ($stmt->rowCount() === 0) json_response(['error' => 'No active copy relationship found'], 404);

    json_response(['success' => true, 'message' => 'Stopped copying this trader']);
}

/* ============================================================
 *  PAUSE / RESUME
 * ============================================================ */
if ($action === 'pause' || $action === 'resume') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

    $leaderId = (int)($_POST['leader_id'] ?? 0);
    if ($leaderId <= 0) json_response(['error' => 'Invalid leader'], 400);

    $newStatus = $action === 'pause' ? 'paused' : 'active';

    $stmt = db()->prepare('UPDATE copy_relationships SET status = ?
                           WHERE follower_id = ? AND leader_id = ? AND status != "stopped"');
    $stmt->execute([$newStatus, $user['id'], $leaderId]);

    json_response(['success' => true, 'message' => $action === 'pause' ? 'Copy paused' : 'Copy resumed']);
}

/* ============================================================
 *  Unknown action
 * ============================================================ */
json_response(['error' => 'Unknown action: ' . $action], 400);