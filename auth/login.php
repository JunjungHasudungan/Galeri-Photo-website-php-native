<?php
    // session_start();
    $config = include '../config/app.php';
    $pdo = include '../config/database.php'; 
    $appName = $config['app_name'];
    $pageTitle = 'Login';

    $slot = '../views/login_form.php';

    include '../layouts/guest.php';
?>
