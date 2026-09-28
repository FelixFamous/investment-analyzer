<?php
/**
 * /api/workspaces.php
 */
require_once __DIR__ . '/../includes/auth.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

if ($action === 'list') {
    $stmt = db()->prepare('SELECT * FROM workspaces WHERE user_id = ? ORDER BY is_default DESC, updated_at DESC');
    $stmt->execute([$user['id']]);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['id'] = (int)$r['id'];
        $r['is_default'] = (int)$r['is_default'];
        $r['symbols'] = json_decode($r['symbols_json'], true) ?: [];
        $r['indicators'] = json_decode($r['indicators_json'] ?? 'null', true) ?: [];
    }
    unset($r);
    json_response(['workspaces' => $rows]);
}

if ($action === 'save') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));
    $layout = (string)($_POST['layout'] ?? 'single');
    $symbolsJson = (string)($_POST['symbols_json'] ?? '[]');
    $interval = (string)($_POST['interval'] ?? '1h');
    $range = (string)($_POST['range_label'] ?? '7d');
    $chartType = (string)($_POST['chart_type'] ?? 'candles');
    $indicatorsJson = (string)($_POST['indicators_json'] ?? '{}');

    if ($name === '') json_response(['error' => 'Name required'], 400);
    if (strlen($name) > 60) $name = substr($name, 0, 60);
    if (!in_array($layout, ['single','2x1','2x2'], true)) $layout = 'single';

    $symbols = json_decode($symbolsJson, true);
    if (!is_array($symbols)) $symbols = [];
    $symbols = array_slice($symbols, 0, 4);

    $indicators = json_decode($indicatorsJson, true);
    if (!is_array($indicators)) $indicators = [];

    if ($id > 0) {
        db()->prepare('UPDATE workspaces
                       SET name = ?, layout = ?, symbols_json = ?, `interval` = ?,
                           range_label = ?, chart_type = ?, indicators_json = ?
                       WHERE id = ? AND user_id = ?')
            ->execute([$name, $layout, json_encode($symbols), $interval,
                       $range, $chartType, json_encode($indicators), $id, $user['id']]);
        json_response(['success' => true, 'id' => $id, 'message' => 'Workspace updated']);
    }

    db()->prepare('INSERT INTO workspaces
                   (user_id, name, layout, symbols_json, `interval`, range_label, chart_type, indicators_json)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
        ->execute([$user['id'], $name, $layout, json_encode($symbols),
                   $interval, $range, $chartType, json_encode($indicators)]);
    json_response(['success' => true, 'id' => (int)db()->lastInsertId(), 'message' => 'Workspace saved']);
}

if ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();
    $id = (int)($_POST['id'] ?? 0);
    db()->prepare('DELETE FROM workspaces WHERE id = ? AND user_id = ?')->execute([$id, $user['id']]);
    json_response(['success' => true]);
}

if ($action === 'default') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();
    $id = (int)($_POST['id'] ?? 0);
    db()->prepare('UPDATE workspaces SET is_default = 0 WHERE user_id = ?')->execute([$user['id']]);
    db()->prepare('UPDATE workspaces SET is_default = 1 WHERE id = ? AND user_id = ?')->execute([$id, $user['id']]);
    json_response(['success' => true]);
}

json_response(['error' => 'Unknown action'], 400);