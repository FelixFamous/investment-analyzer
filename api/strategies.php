<?php
/**
 * /api/strategies.php
 * CRUD for user-saved strategies + presets.
 *
 * GET  ?action=list               → user strategies + presets
 * GET  ?action=get&id=N           → one strategy (own or preset)
 * POST action=save                → create/update
 * POST action=delete  id
 * POST action=duplicate id        → copy a preset into the user's saved strategies
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/strategy-engine.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

/* ---------- LIST ---------- */
if ($action === 'list') {
    $stmt = db()->prepare('
        SELECT id, name, description, entry_rules, exit_rules, is_public, created_at, updated_at
        FROM strategies WHERE user_id = ?
        ORDER BY updated_at DESC
    ');
    $stmt->execute([$user['id']]);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$r) {
        $r['id'] = (int)$r['id'];
        $r['is_public'] = (int)$r['is_public'];
        $r['entry_rules'] = json_decode($r['entry_rules'], true);
        $r['exit_rules']  = json_decode($r['exit_rules'], true);
        $r['is_preset'] = false;
    }
    unset($r);

    $presets = [];
    foreach (preset_strategies() as $key => $s) {
        $presets[] = [
            'id' => $key,
            'name' => $s['name'],
            'description' => $s['description'],
            'entry_rules' => $s['entry'],
            'exit_rules'  => $s['exit'],
            'is_preset' => true,
        ];
    }

    json_response(['strategies' => $rows, 'presets' => $presets]);
}

/* ---------- GET ONE ---------- */
if ($action === 'get') {
    $id = $_GET['id'] ?? '';

    // Preset?
    if (!ctype_digit((string)$id)) {
        $presets = preset_strategies();
        if (!isset($presets[$id])) json_response(['error' => 'Strategy not found'], 404);
        $s = $presets[$id];
        json_response(['strategy' => [
            'id' => $id,
            'name' => $s['name'],
            'description' => $s['description'],
            'entry_rules' => $s['entry'],
            'exit_rules'  => $s['exit'],
            'is_preset' => true,
        ]]);
    }

    $id = (int)$id;
    $stmt = db()->prepare('SELECT * FROM strategies WHERE id = ? AND user_id = ? LIMIT 1');
    $stmt->execute([$id, $user['id']]);
    $s = $stmt->fetch();
    if (!$s) json_response(['error' => 'Strategy not found'], 404);

    $s['id'] = (int)$s['id'];
    $s['is_public'] = (int)$s['is_public'];
    $s['entry_rules'] = json_decode($s['entry_rules'], true);
    $s['exit_rules']  = json_decode($s['exit_rules'], true);
    $s['is_preset'] = false;

    json_response(['strategy' => $s]);
}

/* ---------- SAVE ---------- */
if ($action === 'save') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $entryJson = (string)($_POST['entry_rules'] ?? '');
    $exitJson  = (string)($_POST['exit_rules']  ?? '');
    $isPublic  = !empty($_POST['is_public']) ? 1 : 0;

    if ($name === '') json_response(['error' => 'Name required'], 400);
    if (strlen($name) > 80) $name = substr($name, 0, 80);
    if (strlen($description) > 255) $description = substr($description, 0, 255);

    $entry = json_decode($entryJson, true);
    $exit  = json_decode($exitJson, true);
    if (!is_array($entry) || !is_array($exit)) json_response(['error' => 'Invalid rules JSON'], 400);

    if ($id > 0) {
        $stmt = db()->prepare('
            UPDATE strategies SET name = ?, description = ?, entry_rules = ?, exit_rules = ?, is_public = ?
            WHERE id = ? AND user_id = ?
        ');
        $stmt->execute([$name, $description, json_encode($entry), json_encode($exit), $isPublic, $id, $user['id']]);
        if ($stmt->rowCount() === 0) {
            // Could be no change — verify it exists
            $stmt = db()->prepare('SELECT id FROM strategies WHERE id = ? AND user_id = ?');
            $stmt->execute([$id, $user['id']]);
            if (!$stmt->fetchColumn()) json_response(['error' => 'Strategy not found'], 404);
        }
        json_response(['success' => true, 'id' => $id, 'message' => 'Strategy updated']);
    }

    $stmt = db()->prepare('
        INSERT INTO strategies (user_id, name, description, entry_rules, exit_rules, is_public)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$user['id'], $name, $description, json_encode($entry), json_encode($exit), $isPublic]);
    json_response(['success' => true, 'id' => (int)db()->lastInsertId(), 'message' => 'Strategy saved']);
}

/* ---------- DELETE ---------- */
if ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) json_response(['error' => 'Invalid id'], 400);

    $stmt = db()->prepare('DELETE FROM strategies WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $user['id']]);
    if ($stmt->rowCount() === 0) json_response(['error' => 'Strategy not found'], 404);
    json_response(['success' => true, 'message' => 'Strategy deleted']);
}

/* ---------- DUPLICATE (preset → user) ---------- */
if ($action === 'duplicate') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

    $presetKey = (string)($_POST['preset_key'] ?? '');
    $presets = preset_strategies();
    if (!isset($presets[$presetKey])) json_response(['error' => 'Preset not found'], 404);

    $s = $presets[$presetKey];
    $stmt = db()->prepare('
        INSERT INTO strategies (user_id, name, description, entry_rules, exit_rules)
        VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $user['id'],
        $s['name'] . ' (copy)',
        $s['description'],
        json_encode($s['entry']),
        json_encode($s['exit']),
    ]);
    json_response(['success' => true, 'id' => (int)db()->lastInsertId(), 'message' => 'Preset copied to your strategies']);
}

json_response(['error' => 'Unknown action'], 400);