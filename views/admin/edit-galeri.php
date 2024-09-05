<?php
    $config = include '../../config/app.php';
    $appName = $config['app_name'];
    $pageTitle = 'GALERI';
    $header = '<h1>Welcome to your Dashboard</h1>'; 
    $slot = 'views/admin/galeri-photo/edit/galeri.php'; 

    include '../../layouts/app.php'; 
?>
