<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: /belajar-web-native');
        exit();
    }
    $config = include '../../config/app.php';
    $appName = $config['app_name'];
    $pageTitle = 'User-Dashboard';
    $header = '<h1>Welcome to your Dashboard</h1>'; // Contoh header
    $slot = 'views/users/dashboard/galeri-photo.php'; // Konten halaman dashboard

    include '../../layouts/app.php'; // Pastikan jalur relatif sudah benar
?>
