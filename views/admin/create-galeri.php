<?php
    $config = include '../../config/app.php';
    $appName = $config['app_name'];
    $pageTitle = 'GALERI';
    $header = '<h1>Welcome to your Dashboard</h1>'; // Contoh header
    $slot = 'views/admin/galeri-photo/create/galeri.php'; // Konten halaman dashboard

    include '../../layouts/app.php'; // Pastikan jalur relatif sudah benar
?>
