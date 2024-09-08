<!-- guest.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . $appName : $appName; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="/belajar-web-native/assets/js/helper.js"></script>

</head>
    <body>
    <?php include 'navigation.php'; ?>

        <div class="container">
            <?php
                if (isset($slot)) {
                    include $slot;
                }
            ?>
        </div>
        <!-- <script src="assets/js/helper.js"></script> -->
    </body>
</html>
