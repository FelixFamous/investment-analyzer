<?php
/**
 * Application configuration.
 * Works on XAMPP defaults AND on typical free PHP hosts.
 */

// --- Database (XAMPP defaults: user=root, no password) ---
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'investment_analyzer');
define('DB_USER', 'root');
define('DB_PASS', '');

// --- App ---
define('APP_NAME', 'AlphaEdge');
define('APP_URL',  'http://localhost/investment-analyzer');

// --- Session / Auth ---
define('SESSION_NAME', 'alphaedge_session');
define('APP_SECRET', 'dev_only_change_me_before_deploy');

// --- Market data (Finnhub) ---
// Get a free key at https://finnhub.io — paste it below when ready.
define('FINNHUB_API_KEY', '');

// --- Trading simulation ---
// Starting virtual balance for every new account (in USD).
define('STARTING_BALANCE', 100000.00);

// --- Timezone ---
date_default_timezone_set('UTC');

// --- Error display: ON in dev, OFF in production ---
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}