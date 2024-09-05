<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

return [
    'db_host'       => 'localhost',
    'db_name'       => 'example-demo-galeri-photo-native',
    'db_user'       => 'root',
    'db_pass'       => '',
    'app_name'      => 'Galeri-Photo',
    'db_options'    => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];

