<?php
    $config = include '../../config/app.php';
    $appName = $config['app_name'];
    $pageTitle = 'Galeri Photo';
    $header = '<h1>Welcome to Content Galeri Photo</h1>'; 
    $slot = 'views/admin/galeri-photo/list-galeri-photo/content-list-galeri.php'; 

    include '../../../layouts/app.php'; 
?>
