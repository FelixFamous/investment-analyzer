<?php
/**
 * Referral system helpers.
 *
 * Flow:
 *   1. Every user gets a unique referral_code (generated on signup or on-demand)
 *   2. New signups can carry a ?ref=CODE in the URL
 *   3. On successful registration, both sides get a cash bonus
 *   4. The event is logged in referral_events for auditing
 */

/** Bonus amounts (in USD, virtual). */
define('REFERRAL_BONUS_REFERRER', 25.00);
define('REFERRAL_BONUS_REFERRED', 25.00);

/**
 * Generate a unique referral code for a user.
 * Format: 8 hex chars + 2 digits (e.g. "A3F92B7C41")
 */
function generate_referral_code(int $userId, string $username): string
{
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $raw = strtoupper(substr(hash('sha256', $userId . $username . random_bytes(8)), 0, 8));
        $code = $raw . str_pad((string)($userId % 100), 2, '0', STR_PAD_LEFT);

        $stmt = db()->prepare('SELECT id FROM users WHERE referral_code = ? LIMIT 1');
        $stmt->execute([$code]);
        if (!$stmt->fetchColumn()) return $code;
    }
    // Fallback (should never happen)
    return strtoupper(bin2hex(random_bytes(6)));
}

/**
 * Ensure a user has a referral code. Returns it.
 * Auto-generates on first call.
 */
function ensure_referral_code(int $userId): string
{
    $stmt = db()->prepare('SELECT referral_code, username FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row) return '';

    if (!empty($row['referral_code'])) return $row['referral_code'];

    $code = generate_referral_code($userId, $row['username']);
    db()->prepare('UPDATE users SET referral_code = ? WHERE id = ?')->execute([$code, $userId]);
    return $code;
}

/**
 * Look up a user id by referral code. Returns null if invalid.
 */
function find_user_by_referral_code(string $code): ?int
{
    $code = strtoupper(trim($code));
    if ($code === '') return null;

    $stmt = db()->prepare('SELECT id FROM users WHERE referral_code = ? LIMIT 1');
    $stmt->execute([$code]);
    $id = $stmt->fetchColumn();
    return $id ? (int)$id : null;
}

/**
 * Apply a referral when a new user registers.
 *
 * @param int    $referredUserId  The freshly-created user
 * @param string $code            The referral code they used
 * @return bool  True if applied successfully
 */
function apply_referral(int $referredUserId, string $code): bool
{
    $referrerId = find_user_by_referral_code($code);
    if (!$referrerId) return false;
    if ($referrerId === $referredUserId) return false; // can't refer yourself

    // Prevent double-application
    $stmt = db()->prepare('SELECT 1 FROM referral_events WHERE referred_id = ? LIMIT 1');
    $stmt->execute([$referredUserId]);
    if ($stmt->fetchColumn()) return false;

    try {
        $pdo = db();
        $pdo->beginTransaction();

        // Credit both sides
        $pdo->prepare('UPDATE users SET cash_balance = cash_balance + ?, referral_earnings = referral_earnings + ?, referral_count = referral_count + 1 WHERE id = ?')
            ->execute([REFERRAL_BONUS_REFERRER, REFERRAL_BONUS_REFERRER, $referrerId]);

        $pdo->prepare('UPDATE users SET cash_balance = cash_balance + ?, referred_by = ? WHERE id = ?')
            ->execute([REFERRAL_BONUS_REFERRED, $referrerId, $referredUserId]);

        // Log the event
        $pdo->prepare('INSERT INTO referral_events (referrer_id, referred_id, bonus_referrer, bonus_referred)
                       VALUES (?, ?, ?, ?)')
            ->execute([$referrerId, $referredUserId, REFERRAL_BONUS_REFERRER, REFERRAL_BONUS_REFERRED]);

        // Log as cash transactions for both users
        $pdo->prepare('INSERT INTO cash_transactions (user_id, type, amount, note)
                       VALUES (?, "ADJUSTMENT", ?, ?)')
            ->execute([$referrerId, REFERRAL_BONUS_REFERRER, 'Referral bonus for inviting a new user']);
        $pdo->prepare('INSERT INTO cash_transactions (user_id, type, amount, note)
                       VALUES (?, "ADJUSTMENT", ?, ?)')
            ->execute([$referredUserId, REFERRAL_BONUS_REFERRED, 'Welcome bonus from referral']);

        $pdo->commit();
        return true;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('apply_referral failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Get referral stats for a user.
 */
function get_referral_stats(int $userId): array
{
    $stmt = db()->prepare('SELECT referral_code, referral_earnings, referral_count
                           FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $me = $stmt->fetch() ?: ['referral_code' => null, 'referral_earnings' => 0, 'referral_count' => 0];

    $stmt = db()->prepare('
        SELECT re.id, re.created_at, re.bonus_referrer, re.bonus_referred,
               u.username, u.kyc_status, u.created_at AS joined
        FROM referral_events re
        JOIN users u ON u.id = re.referred_id
        WHERE re.referrer_id = ?
        ORDER BY re.created_at DESC
    ');
    $stmt->execute([$userId]);
    $invitees = $stmt->fetchAll();

    return [
        'code'     => $me['referral_code'],
        'earnings' => (float)$me['referral_earnings'],
        'count'    => (int)$me['referral_count'],
        'invitees' => $invitees,
    ];
}