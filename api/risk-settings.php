<?php
/**
 * POST /api/risk-settings.php
 * Body: risk_per_trade_pct, max_portfolio_heat, max_position_pct, csrf
 */
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'Method not allowed'], 405);

$user = current_user();
if (!$user) json_response(['error' => 'Not authenticated'], 401);
csrf_verify_or_die();

$riskPerTrade = (float)($_POST['risk_per_trade_pct'] ?? 0);
$maxHeat      = (float)($_POST['max_portfolio_heat'] ?? 0);
$maxPos       = (float)($_POST['max_position_pct'] ?? 0);

if ($riskPerTrade < 0.1 || $riskPerTrade > 10) {
    json_response(['error' => 'Risk per trade must be between 0.1% and 10%'], 400);
}
if ($maxHeat < 1 || $maxHeat > 30) {
    json_response(['error' => 'Max portfolio heat must be between 1% and 30%'], 400);
}
if ($maxPos < 5 || $maxPos > 100) {
    json_response(['error' => 'Max position size must be between 5% and 100%'], 400);
}
if ($maxHeat < $riskPerTrade) {
    json_response(['error' => 'Max portfolio heat cannot be less than risk per trade'], 400);
}

db()->prepare('UPDATE users
               SET risk_per_trade_pct = ?,
                   max_portfolio_heat = ?,
                   max_position_pct   = ?,
                   risk_settings_updated = UTC_TIMESTAMP()
               WHERE id = ?')
    ->execute([$riskPerTrade, $maxHeat, $maxPos, $user['id']]);

json_response([
    'success' => true,
    'message' => 'Risk settings saved',
    'settings' => [
        'risk_per_trade_pct' => $riskPerTrade,
        'max_portfolio_heat' => $maxHeat,
        'max_position_pct'   => $maxPos,
    ],
]);