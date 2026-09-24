<?php
/**
 * Trading journal helpers.
 * Merges trades with journal_entries so every trade appears in the journal,
 * whether or not the trader has annotated it yet.
 */

/**
 * Get a single journal entry for a trade.
 */
function journal_get(int $userId, int $tradeId): ?array
{
    $stmt = db()->prepare('SELECT * FROM journal_entries WHERE user_id = ? AND trade_id = ? LIMIT 1');
    $stmt->execute([$userId, $tradeId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Insert or update a journal entry for a trade.
 */
function journal_upsert(int $userId, int $tradeId, array $data): bool
{
    $tags = isset($data['tags']) ? trim((string)$data['tags']) : '';
    $emotion = isset($data['emotion']) ? trim((string)$data['emotion']) : '';
    $setup = isset($data['setup_type']) ? trim((string)$data['setup_type']) : '';
    $notes = isset($data['notes']) ? trim((string)$data['notes']) : '';

    // Enforce max lengths
    if (strlen($tags) > 255) $tags = substr($tags, 0, 255);
    if (strlen($emotion) > 32) $emotion = substr($emotion, 0, 32);
    if (strlen($setup) > 64) $setup = substr($setup, 0, 64);

    // Verify trade belongs to this user
    $stmt = db()->prepare('SELECT id FROM trades WHERE id = ? AND user_id = ? LIMIT 1');
    $stmt->execute([$tradeId, $userId]);
    if (!$stmt->fetchColumn()) return false;

    try {
        db()->prepare('
            INSERT INTO journal_entries (user_id, trade_id, tags, emotion, setup_type, notes)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                tags = VALUES(tags),
                emotion = VALUES(emotion),
                setup_type = VALUES(setup_type),
                notes = VALUES(notes)
        ')->execute([
            $userId, $tradeId,
            $tags !== '' ? $tags : null,
            $emotion !== '' ? $emotion : null,
            $setup !== '' ? $setup : null,
            $notes !== '' ? $notes : null,
        ]);
        return true;
    } catch (Throwable $e) {
        error_log('journal_upsert failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Return trades merged with journal entries.
 * Filters: symbol, side, tag, setup_type, from, to, only_unreviewed
 */
function journal_list(int $userId, array $filters = [], int $limit = 200): array
{
    $sql = '
        SELECT
            t.id, t.symbol, t.side, t.quantity, t.price, t.total, t.pnl, t.created_at,
            je.id AS journal_id, je.tags, je.emotion, je.setup_type, je.notes
        FROM trades t
        LEFT JOIN journal_entries je
            ON je.trade_id = t.id AND je.user_id = t.user_id
        WHERE t.user_id = ?
    ';
    $params = [$userId];

    if (!empty($filters['symbol'])) {
        $sql .= ' AND t.symbol = ?';
        $params[] = strtoupper($filters['symbol']);
    }
    if (!empty($filters['side']) && in_array($filters['side'], ['BUY','SELL'], true)) {
        $sql .= ' AND t.side = ?';
        $params[] = $filters['side'];
    }
    if (!empty($filters['tag'])) {
        $sql .= ' AND je.tags LIKE ?';
        $params[] = '%' . $filters['tag'] . '%';
    }
    if (!empty($filters['setup_type'])) {
        $sql .= ' AND je.setup_type = ?';
        $params[] = $filters['setup_type'];
    }
    if (!empty($filters['from'])) {
        $sql .= ' AND DATE(t.created_at) >= ?';
        $params[] = $filters['from'];
    }
    if (!empty($filters['to'])) {
        $sql .= ' AND DATE(t.created_at) <= ?';
        $params[] = $filters['to'];
    }
    if (!empty($filters['only_unreviewed'])) {
        $sql .= ' AND je.id IS NULL';
    }

    $sql .= ' ORDER BY t.created_at DESC, t.id DESC LIMIT ' . (int)$limit;

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}