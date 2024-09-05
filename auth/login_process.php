<?php
// auth/login_process.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once '../config/database.php'; // Pastikan ini mengarah ke file koneksi database
require_once '../config/app.php';

// Cek apakah sesi sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

// Ambil data JSON dari fetch API
$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

$errors = [];

// Validasi server-side
if (empty($email)) {
    $errors['email'] = 'Email is required.';
}

if (empty($password)) {
    $errors['password'] = 'Password is required.';
}

// Jika ada error, kirimkan response JSON
if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Menghubungkan ke database dan mengecek email dan password
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Log data password dan hash password untuk debugging
    error_log("Input password: " . $password);
    error_log("Stored hash: " . $user['password']);
    error_log("Password verify result: " . (password_verify($password, $user['password']) ? 'true' : 'false'));
}


// Memeriksa apakah pengguna ditemukan dan password cocok
if ($user && password_verify($password, $user['password'])) {
    // Set session jika login berhasil
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id']; // Simpan informasi user jika diperlukan
    $_SESSION['user_email'] = $user['email']; // Menyimpan email pengguna untuk referensi
    $_SESSION['user_name'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];

    echo json_encode(['success' => true, 'role'=> $user['role']]);
} else {
    // Jika email atau password salah
    http_response_code(400);
    if (!$user) {
        $errors['email'] = 'Invalid email';
    }else {
        $errors['password'] = 'Invalid password'; 
    }

    echo json_encode(
        [
            'success' => false, 
            'errors' =>  $errors
        ]);
        exit;
}
?>
