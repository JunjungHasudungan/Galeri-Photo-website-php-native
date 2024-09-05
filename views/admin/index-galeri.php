<?php
    $config = include '../../config/app.php';
    $appName = $config['app_name'];
    $pageTitle = 'List Galeri';
    $header = '<h1>Welcome to your Dashboard</h1>'; // Contoh header
    $slot = 'views/admin/galeri-photo/index/galeri.php'; // Konten halaman dashboard

    include '../../layouts/app.php'; // Pastikan jalur relatif sudah benar
?>
