<?php
require_once '../config/database.php';

// Fungsi untuk validasi data sebelum disimpan
function validateStoreData($title, $category, $description, $imageFiles) {
    $errors = [];

    if (empty($title)) $errors['title'] = 'Judul wajib diisi.';
    if (empty($category)) $errors['category'] = 'Kategori wajib diisi.';
    if (empty($description)) $errors['description'] = 'Keterangan wajib diisi.';

    // Validasi gambar, pastikan array file tidak kosong
    if (empty($imageFiles['name'][0])) {
        $errors['image'] = 'Gambar wajib diisi.';
    } else {
        foreach ($imageFiles['error'] as $index => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $allowedExts = ['jpg', 'jpeg', 'png'];
                $fileExtension = strtolower(pathinfo($imageFiles['name'][$index], PATHINFO_EXTENSION));

                if (!in_array($fileExtension, $allowedExts)) {
                    $errors['image_' . $index] = 'Tipe file tidak diperbolehkan. Harap unggah gambar JPEG atau PNG.';
                }
            } else {
                $errors['image_' . $index] = 'Terjadi kesalahan saat mengunggah gambar.';
            }
        }
    }

    return $errors;
}

function getAllPost() {
    global $pdo;

    // Mengambil data dari posts dan images dengan LEFT JOIN
    $stmt = $pdo->query("
        SELECT 
            posts.id AS post_id, 
            posts.title AS post_title, 
            posts.description AS post_description, 
            posts.category AS post_category, 
            images.id AS image_id, 
            images.folder_name AS image_folder_name, 
            images.title AS image_title
        FROM posts
        LEFT JOIN images ON posts.id = images.post_id
    ");

    $posts = [];
    // Mengambil data hasil query dan menyusunnya dalam struktur array
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil semua hasil sebagai array

foreach ($rows as $row) {
    $post_id = $row['post_id'];

    // Jika post_id belum ada dalam array posts, buat entry baru
    if (!isset($posts[$post_id])) {
        $posts[$post_id] = [
            'id' => $post_id,
            'title' => $row['post_title'],
            'description' => $row['post_description'],
            'category' => $row['post_category'],
            'images' => []
        ];
    }

    // Cek jika ada image_id dan folder_name valid
    if ($row['image_id'] && $row['image_folder_name']) {
        // Hapus karakter escape yang tidak perlu dari folder_name dan image_title
        $folderName = trim(str_replace(['..\\', '\\', '../', '..\/'], '', $row['image_folder_name']), '/');

        // Buat path gambar yang benar
        $path = "{$folderName}";

        // Tambahkan gambar ke array images dari post
        $posts[$post_id]['images'][] = [
            'id' => $row['image_id'],
            'path' => $path
        ];
    }
}
    return array_values($posts);
}




// Fungsi untuk menyimpan data ke database
function storePost($title, $category, $description, $imageFiles) {
    global $pdo;
    // $errors = validateStoreData($title, $category, $description, $imageFile);
    $errors = validateStoreData($title, $category, $description, $imageFiles);

    if (!empty($errors)) {
        return ['errors' => $errors];
    }

    // try {
    //     // Mulai transaksi
    //     $pdo->beginTransaction();

    //     // Insert ke tabel posts
    //     $stmt = $pdo->prepare("INSERT INTO posts (title, category, description) VALUES (:title, :category, :description)");
    //     $stmt->execute([
    //         ':title' => $title,
    //         ':category' => $category,
    //         ':description' => $description,
    //     ]);

    //     // Ambil ID post yang baru diinsert
    //     $postId = $pdo->lastInsertId();

    //     // Cek apakah ada file gambar
    //     if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
    //         $allowedExts = ['jpg', 'jpeg', 'png'];
    //         $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
    //         $uploadDir = '../uploaded_images/';
    //         $fileName = basename($imageFile['name']); 
    //         $filePath = $uploadDir . $fileName;

    //         if (in_array($fileExtension, $allowedExts) && move_uploaded_file($imageFile['tmp_name'], $filePath)) {
    //             $stmt = $pdo->prepare("INSERT INTO images (folder_name, title, post_id) VALUES (:folder_name, :title, :post_id)");
    //             $stmt->execute([
    //                 ':folder_name'  => $filePath,
    //                 ':title'        => $fileName,
    //                 ':post_id'      => $postId,
    //             ]);
    //         } else {
    //             throw new Exception('Tipe file tidak diperbolehkan atau gagal memindahkan file.');
    //         }
    //     }

    //     // Commit transaksi
    //     $pdo->commit();
    //     return ['success' => true];
    // } catch (Exception $e) {
    //     // Rollback transaksi jika terjadi kesalahan
    //     $pdo->rollBack();
    //     return ['errors' => ['general' => $e->getMessage()]];
    // }

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Insert ke tabel posts
        $stmt = $pdo->prepare("INSERT INTO posts (title, category, description) VALUES (:title, :category, :description)");
        $stmt->execute([
            ':title' => $title,
            ':category' => $category,
            ':description' => $description,
        ]);

        // Ambil ID post yang baru diinsert
        $postId = $pdo->lastInsertId();

        // Loop melalui semua file gambar
        foreach ($imageFiles['error'] as $key => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $imageFile = [
                    'name' => $imageFiles['name'][$key],
                    'type' => $imageFiles['type'][$key],
                    'tmp_name' => $imageFiles['tmp_name'][$key],
                    'error' => $imageFiles['error'][$key],
                    'size' => $imageFiles['size'][$key],
                ];

                $allowedExts = ['jpg', 'jpeg', 'png'];
                $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
                $uploadDir = '../uploaded_images/';
                $originalFileName = pathinfo($imageFile['name'], PATHINFO_FILENAME); // Ambil nama file tanpa ekstensi
                $fileName = $originalFileName . '.' . $fileExtension; // Nama file awal
                $filePath = $uploadDir . $fileName;
                $counter = 1;
                
                // Cek apakah file dengan nama yang sama sudah ada, jika ya, tambahkan angka
                while (file_exists($filePath)) {
                    $fileName = $originalFileName . '_' . $counter . '.' . $fileExtension;
                    $filePath = $uploadDir . $fileName;
                    $counter++;
                }
                
                if (in_array($fileExtension, $allowedExts) && move_uploaded_file($imageFile['tmp_name'], $filePath)) {
                    $stmt = $pdo->prepare("INSERT INTO images (folder_name, title, post_id) VALUES (:folder_name, :title, :post_id)");
                    $stmt->execute([
                        ':folder_name' => $filePath, // Path lengkap dengan direktori
                        ':title' => $fileName,       // Nama file asli atau yang sudah dimodifikasi
                        ':post_id' => $postId,
                    ]);
                } else {
                    throw new Exception('Tipe file tidak diperbolehkan atau gagal memindahkan file.');
                }
                
            }
        }

        // Commit transaksi
        $pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $pdo->rollBack();
        return ['errors' => ['general' => $e->getMessage()]];
    }
}

function editPost($id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        $stmt = $pdo->prepare("SELECT * FROM images WHERE post_id = :post_id");
        $stmt->execute([':post_id' => $id]);

        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['post' => $post, 'images' => $images];
    } else {
        return ['errors' => ['general' => 'Post tidak ditemukan.']];
    }
}

// Fungsi untuk memperbarui data galeri
function updatePost($id, $title, $category, $description, $imageFile) {
    global $pdo;
    $errors = validateStoreData($title, $category, $description, $imageFile);

    if (!empty($errors)) {
        return ['errors' => $errors];
    }

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Update ke tabel posts
        $stmt = $pdo->prepare("UPDATE posts SET title = :title, category = :category, description = :description WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':category' => $category,
            ':description' => $description,
        ]);

        // Cek apakah ada file gambar
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $allowedExts = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
            $uploadDir = '../uploaded_images/';
            $filePath = $uploadDir . basename($imageFile['name']);

            if (in_array($fileExtension, $allowedExts) && move_uploaded_file($imageFile['tmp_name'], $filePath)) {
                // Insert atau update ke tabel images
                $stmt = $pdo->prepare("INSERT INTO images (folder_name, title, post_id) VALUES (:folder_name, :title, :post_id) ON DUPLICATE KEY UPDATE folder_name = VALUES(folder_name), title = VALUES(title)");
                $stmt->execute([
                    ':folder_name' => $filePath,
                    ':title' => basename($imageFile['name']),
                    ':post_id' => $id,
                ]);
            } else {
                throw new Exception('Tipe file tidak diperbolehkan atau gagal memindahkan file.');
            }
        }

        // Commit transaksi
        $pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $pdo->rollBack();
        return ['errors' => ['general' => $e->getMessage()]];
    }
}

// Fungsi untuk menghapus data galeri
function deletePost($id) {
    global $pdo;

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Hapus dari tabel images
        $stmt = $pdo->prepare("DELETE FROM images WHERE post_id = :post_id");
        $stmt->execute([':post_id' => $id]);

        // Hapus dari tabel posts
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Commit transaksi
        $pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $pdo->rollBack();
        return ['errors' => ['general' => $e->getMessage()]];
    }
}

// Menangani metode request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (in_array($action, ['store', 'update', 'edit', 'delete'])) {
        switch ($action) {
            case 'store':
                $title = $_POST['title'] ?? '';
                $category = $_POST['category'] ?? '';
                $description = $_POST['description'] ?? '';
                $image = $_FILES['image'] ?? null;
                $images = $_FILES['images'] ?? null;

                // $result = storePost($title, $category, $description, $image);
                $result = storePost($title, $category, $description, $images);
                echo json_encode($result);
                break;

            case 'update':
                $id = $_POST['id'] ?? 0;
                $title = $_POST['title'] ?? '';
                $category = $_POST['category'] ?? '';
                $description = $_POST['description'] ?? '';
                $image = $_FILES['image'] ?? null;

                $result = updatePost($id, $title, $category, $description, $image);
                echo json_encode($result);
                break;

            case 'edit':
                $id = $_POST['id'] ?? 0;

                $result = editPost($id);
                echo json_encode($result);
                break;

            case 'delete':
                $id = $_POST['id'] ?? 0;

                $result = deletePost($id);
                echo json_encode($result);
                break;
        }
    } else {
        echo json_encode(['errors' => ['general' => 'Aksi tidak valid.']]);
    }
} else {
    // Menyiapkan struktur response
    header('Content-Type: application/json'); // Pastikan header JSON
    $dataPost = getAllPost();
    echo json_encode(['data'=> $dataPost ]);
}
