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
define('FINNHUB_API_KEY', '');

// --- Trading simulation ---
define('STARTING_BALANCE', 100000.00);

// --- Timezone ---
date_default_timezone_set('UTC');

// --- Error display: OFF in production ---
// Set ALPHAEDGE_DEBUG=1 in your shell to enable verbose errors locally.
define('DEBUG_MODE', getenv('ALPHAEDGE_DEBUG') === '1');

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}