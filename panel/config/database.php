<?php
// config/database.php
function getDB(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $config = [
        'host'     => getenv('JUN_DB_HOST') !== false ? getenv('JUN_DB_HOST') : 'localhost',
        'dbname'   => getenv('JUN_DB_NAME') !== false ? getenv('JUN_DB_NAME') : 'jun',
        'username' => getenv('JUN_DB_USER') !== false ? getenv('JUN_DB_USER') : 'root',
        'password' => getenv('JUN_DB_PASSWORD') !== false ? getenv('JUN_DB_PASSWORD') : '',
        'charset'  => 'utf8mb4',
    ];

    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

    try {
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        throw $e;
    }

    return $pdo;
}

// Helper para respuestas JSON consistentes
function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}