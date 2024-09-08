<?php
require_once '../config/database.php';

function createGallery($data) {
    $errors = validateGallery($data);

    if (!empty($errors)) {
        return ['errors' => $errors];
    }

    // Logic to save the gallery to the database
    // ...

    return ['success' => true];
}

function validateGallery($data) {
    $errors = [];

    if (empty($data['title'])) {
        $errors['title'] = 'Judul wajib di isi.';
    }
    if (empty($data['image']['name'])) {
        $errors['image'] = 'Gambar wajib di isi.';
    }
    if (empty($data['description'])) {
        $errors['description'] = 'keterangan wajib di isi.';
    }
    if (empty($data['category'])) {
        $errors['category'] = 'kategori wajib di isi.';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $result = createGallery($_POST);
    echo json_encode($result);
}
