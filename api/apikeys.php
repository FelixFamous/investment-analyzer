<?php
/**
 * /api/apikeys.php
 */
require_once __DIR__ . '/../includes/auth.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

if ($action === 'list') {
    $stmt = db()->prepare('
        SELECT id, label, key_prefix, permissions, ip_whitelist, last_used_at, revoked_at, created_at
        FROM api_keys WHERE user_id = ?
        ORDER BY created_at DESC
    ');
    $stmt->execute([$user['id']]);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['id'] = (int)$r['id'];
    }
    unset($r);
    json_response(['keys' => $rows]);
}

if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();

    $label = trim((string)($_POST['label'] ?? 'Default key'));
    $perms = (string)($_POST['permissions'] ?? 'read');
    if (strlen($label) > 60) $label = substr($label, 0, 60);
    if (!in_array($perms, ['read', 'trade'], true)) $perms = 'read';

    $prefix = 'ae_' . strtolower(bin2hex(random_bytes(4)));
    $secret = bin2hex(random_bytes(24));
    $fullKey = $prefix . '_' . $secret;

    db()->prepare('INSERT INTO api_keys (user_id, label, key_prefix, key_hash, permissions) VALUES (?, ?, ?, ?, ?)')
        ->execute([$user['id'], $label, $prefix, password_hash($fullKey, PASSWORD_DEFAULT), $perms]);

    json_response([
        'success' => true,
        'key' => $fullKey,
        'message' => 'API key created. Copy it now — you won\'t see it again.',
    ]);
}

if ($action === 'revoke') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    csrf_verify_or_die();
    $id = (int)($_POST['id'] ?? 0);
    db()->prepare('UPDATE api_keys SET revoked_at = UTC_TIMESTAMP() WHERE id = ? AND user_id = ?')
        ->execute([$id, $user['id']]);
    json_response(['success' => true, 'message' => 'Key revoked']);
}

json_response(['error' => 'Unknown action'], 400);