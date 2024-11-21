<!-- app.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/belajar-web-native/assets/css/style.css">
    <link rel="stylesheet" href="/belajar-web-native/assets/css/index-galeri.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . $appName : $appName; ?></title>
        <!-- Axios CDN -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
    <body>

        <?php include 'navigation.php'; ?>

        <div class="container">
            <div class="main-content" id="main-content">
                <?php
                    if (isset($slot)) {
                        $fullPath = __DIR__ . '/../' . $slot;
                        if (file_exists($fullPath)) {
                            include $fullPath;
                        } else {
                            echo "File not found: $fullPath";
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
