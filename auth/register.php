<?php
    session_start();
    $config = include '../config/app.php';
    $pdo = include '../config/database.php'; // Mendapatkan objek PDO
    $appName = $config['app_name'];
    $pageTitle = 'Register';

    $slot = '../views/register_form.php';

    include '../layouts/guest.php';
?>
