<?php
session_start();
$pdo = include '../config/database.php'; // Mendapatkan objek PDO dari database.php

// Mengatur header untuk JSON
header('Content-Type: application/json');

// Fungsi untuk membersihkan input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Ambil data dari request
$data = json_decode(file_get_contents('php://input'), true);

// Sanitasi input
$username = sanitizeInput($data['username']);
$email = sanitizeInput($data['email']);
$password = sanitizeInput($data['password']);
$confirmPassword = sanitizeInput($data['confirmPassword']);

// Validasi
$errors = [];
if (empty($username)) {
    $errors['username'] = 'Username tidak boleh kosong';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Format email tidak valid';
}
if (strlen($password) < 6) {
    $errors['password'] = 'Password harus lebih dari 6 karakter';
}
if ($password !== $confirmPassword) {
    $errors['confirmPassword'] = 'Password tidak cocok';
}

// Cek apakah email sudah ada di database
$sqlCheckEmail = "SELECT * FROM users WHERE email = :email";
$stmtCheckEmail = $pdo->prepare($sqlCheckEmail);
$stmtCheckEmail->execute([':email' => $email]);

if ($stmtCheckEmail->rowCount() > 0) {
    $errors['email'] = 'Email sudah terdaftar';
}

// Jika ada error, kirimkan respons error
if (!empty($errors)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Simpan ke database
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        if ($result) {
            // Setelah pendaftaran berhasil, atur sesi
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = $username; // Atur nama pengguna di sesi
            $_SESSION['user_role'] = $user['role'];
        
            // Kirim respons sukses
            http_response_code(201); // Created
            echo json_encode(['success' => true, 'message' => 'Registrasi berhasil', 'result' => $result]);
        } else {
            // Kirim respons gagal
            echo json_encode(['success' => false, 'message' => 'Registration failed.']);
        }

