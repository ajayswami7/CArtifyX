<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Kolkata');

define('APP_NAME', 'CArtifyX');
define('DB_HOST', 'localhost');
define('DB_NAME', 'cartifyx');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', '/project/');
define('UPLOAD_URL', BASE_URL . 'assets/uploads/');
define('UPLOAD_PATH', dirname(__DIR__) . '/assets/uploads/');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    $pdo = null;
}

function db(): ?PDO
{
    global $pdo;
    return $pdo;
}
