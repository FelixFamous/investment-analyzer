<?php
/**
 * GET /api/referral.php
 * Returns the current user's referral stats.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/referral.php';

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);

$code = ensure_referral_code((int)$user['id']);

$stats = get_referral_stats((int)$user['id']);
$stats['code'] = $code;
$stats['link'] = APP_URL . '/register.php?ref=' . urlencode($code);
$stats['bonus_referrer'] = REFERRAL_BONUS_REFERRER;
$stats['bonus_referred'] = REFERRAL_BONUS_REFERRED;

json_response($stats);