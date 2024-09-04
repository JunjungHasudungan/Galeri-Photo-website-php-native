<?php
// Memulai session
session_start();

// Menghapus semua session
session_unset();

// Menghancurkan session
session_destroy();

// Redirect ke halaman index.php
header("Location: /belajar-web-native/index.php");
exit();
?>
