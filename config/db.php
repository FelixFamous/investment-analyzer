<?php
/**
 * PDO database connection.
 * Requires config.php to be loaded first.
 */
require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        // Force UTC so NOW() and CURRENT_TIMESTAMP store UTC everywhere.
        $pdo->exec("SET time_zone = '+00:00'");
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die('Database connection failed: ' . $e->getMessage());
        }
        die('Service temporarily unavailable. Please try again later.');
    }

    return $pdo;
}