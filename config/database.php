<?php
$config = include '../config/app.php'; 

// Membuat DSN
$dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']}";

// Membuat objek PDO
try {
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $config['db_options']);
} catch (PDOException $e) {
    // Menangani kesalahan koneksi
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

return $pdo;
