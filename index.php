<?php
    session_start();

    $config = include './config/app.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['app_name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>

<?php include 'layouts/navigation.php'; ?>

<div class="container">
    <h1>Selamat Datang di <?php echo htmlspecialchars($config['app_name']); ?></h1>
    <p>Silakan login atau register untuk masuk ke dalam sistem.</p>
</div>

</body>
</html>
