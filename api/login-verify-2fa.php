<?php
/**
 * POST /api/login-verify-2fa.php
 * Body: code, csrf
 *
 * Completes the login for a user who is in the "pending 2FA" session state.
 * Accepts either a 6-digit TOTP code OR a one-time recovery code.
 * On success, the user is fully logged in.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/totp.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    json_response(['error' => 'CSRF mismatch'], 419);
}

// Must be in pending-2FA state
$pendingId = (int)($_SESSION['pending_2fa_user_id'] ?? 0);
$pendingAt = (int)($_SESSION['pending_2fa_at'] ?? 0);

if ($pendingId <= 0) {
    json_response(['error' => 'No sign-in in progress. Please sign in again.'], 400);
}
if ($pendingAt > 0 && (time() - $pendingAt) > 600) {
    unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_at']);
    json_response(['error' => 'Session expired. Please sign in again.'], 400);
}

$code = trim((string)($_POST['code'] ?? ''));
$code = preg_replace('/[\s-]+/', '', $code); // allow "abc1-def2" / spaces for recovery codes

if ($code === '') {
    json_response(['error' => 'Enter the code from your authenticator app.'], 400);
}

$stmt = db()->prepare('SELECT id, totp_secret, totp_enabled, totp_recovery FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$pendingId]);
$user = $stmt->fetch();

if (!$user || (int)$user['totp_enabled'] !== 1 || empty($user['totp_secret'])) {
    // 2FA is not actually on — just log them in
    login_user($pendingId);
    unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_at']);
    json_response(['success' => true, 'message' => 'Signed in']);
}

$verified = false;
$usedRecovery = false;

/* ---------- Path A: standard 6-digit TOTP ---------- */
if (preg_match('/^\d{6}$/', $code)) {
    if (TOTP::verifyCode($user['totp_secret'], $code)) {
        $verified = true;
    }
}

/* ---------- Path B: recovery code (format xxxx-xxxx) ---------- */
if (!$verified && !empty($user['totp_recovery'])) {
    $candidates = json_decode($user['totp_recovery'], true);
    if (is_array($candidates)) {
        // Normalize input: remove dashes so users can type with or without them
        $inputDashless = str_replace('-', '', strtolower($code));

        foreach ($candidates as $idx => $hash) {
            // Reinsert dash for the "xxxx-xxxx" pattern the user typed
            $candidate = substr($inputDashless, 0, 4) . '-' . substr($inputDashless, 4, 4);

            if (password_verify($candidate, $hash)) {
                $verified = true;
                $usedRecovery = true;

                // One-time use: remove this code from the array
                unset($candidates[$idx]);
                $candidates = array_values($candidates);

                db()->prepare('UPDATE users SET totp_recovery = ? WHERE id = ?')
                    ->execute([json_encode($candidates), $pendingId]);

                break;
            }
        }
    }
}

if (!$verified) {
    json_response(['error' => 'Invalid code. Check your app and try again.'], 400);
}

/* ---------- Success: complete the login ---------- */
login_user($pendingId);
unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_at']);

if ($usedRecovery) {
    // Warn the user that they've used one recovery code
    $remaining = 0;
    if (!empty($user['totp_recovery'])) {
        $remaining = count(json_decode($user['totp_recovery'], true) ?: []) - 1;
        if ($remaining < 0) $remaining = 0;
    }
    json_response([
        'success' => true,
        'message' => 'Signed in via recovery code (' . $remaining . ' remaining). Please generate new codes soon.',
    ]);
}

json_response(['success' => true, 'message' => 'Signed in']);