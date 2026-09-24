<?php
/**
 * /api/journal.php
 *
 * GET  ?action=list&symbol=&side=&tag=&setup_type=&from=&to=&only_unreviewed=1
 * GET  ?action=get&trade_id=123
 * POST action=save  trade_id, tags, emotion, setup_type, notes
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/journal.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

/* ---------- LIST ---------- */
if ($action === 'list') {
    $filters = [
        'symbol'          => trim((string)($_GET['symbol'] ?? '')),
        'side'            => strtoupper(trim((string)($_GET['side'] ?? ''))),
        'tag'             => trim((string)($_GET['tag'] ?? '')),
        'setup_type'      => trim((string)($_GET['setup_type'] ?? '')),
        'from'            => trim((string)($_GET['from'] ?? '')),
        'to'              => trim((string)($_GET['to'] ?? '')),
        'only_unreviewed' => !empty($_GET['only_unreviewed']),
    ];

    $rows = journal_list((int)$user['id'], $filters, 200);

    // Cast numerics
    foreach ($rows as &$r) {
        $r['id'] = (int)$r['id'];
        $r['journal_id'] = $r['journal_id'] !== null ? (int)$r['journal_id'] : null;
        $r['quantity'] = (float)$r['quantity'];
        $r['price'] = (float)$r['price'];
        $r['total'] = (float)$r['total'];
        $r['pnl'] = $r['pnl'] !== null ? (float)$r['pnl'] : null;
    }
    unset($r);

    json_response(['entries' => $rows]);
}

/* ---------- GET ONE ---------- */
if ($action === 'get') {
    $tradeId = (int)($_GET['trade_id'] ?? 0);
    if ($tradeId <= 0) json_response(['error' => 'Invalid trade_id'], 400);

    $entry = journal_get((int)$user['id'], $tradeId);
    json_response(['entry' => $entry]);
}

/* ---------- SAVE ---------- */
if ($action === 'save') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) json_response(['error' => 'CSRF mismatch'], 419);

    $tradeId = (int)($_POST['trade_id'] ?? 0);
    if ($tradeId <= 0) json_response(['error' => 'Invalid trade'], 400);

    $ok = journal_upsert((int)$user['id'], $tradeId, [
        'tags'       => (string)($_POST['tags'] ?? ''),
        'emotion'    => (string)($_POST['emotion'] ?? ''),
        'setup_type' => (string)($_POST['setup_type'] ?? ''),
        'notes'      => (string)($_POST['notes'] ?? ''),
    ]);

    if (!$ok) json_response(['error' => 'Could not save entry'], 500);
    json_response(['success' => true, 'message' => 'Journal saved']);
}

json_response(['error' => 'Unknown action'], 400);