<?php
    $config = include '../../config/app.php';
    $appName = $config['app_name'];
    $pageTitle = 'Like-Galeri';
    $header = '<h1>Welcome to your Like Galeri</h1>'; // Contoh header
    $slot = 'views/users/likes/galeri.php'; // Konten halaman dashboard

    include '../../layouts/app.php'; // Pastikan jalur relatif sudah benar
?>
