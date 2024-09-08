<div class="container mx-auto p-4">
    <div id="header">
        <h2 class="text-2xl font-semibold mb-4">KONTENT LIST GALERI PHOTO ADMIN</h2>
        <p>Welcome to your dashboard. Here is where you can manage your settings, view notifications, and more.</p>
        <!-- Tombol Buat Galeri -->
        <button id="btn-add-gallery" class="btn-add">Tambah Galeri</button>
    </div>
    

    <!-- Form Galeri Photo -->
    <?php
        include '_form-galeri-photo.php';
    ?>

    <!-- Tabel Galeri -->
    <table class="gallery-table" id="galeri-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Images</th>
                <th>Description</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($galleries)): ?>
                <?php foreach ($galleries as $gallery): ?>
                    <tr>
                        <td><?= $gallery['id']; ?></td>
                        <td><?= $gallery['title']; ?></td>
                        <td><img src="<?= $gallery['image_path']; ?>" alt="Gallery Image" width="100"></td>
                        <td><?= $gallery['description']; ?></td>
                        <td><?= $gallery['category']; ?></td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-delete">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Data galeri tidak ada atau belum ada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Script untuk menampilkan form galeri saat tombol ditekan
    document.getElementById('btn-add-gallery').addEventListener('click', function() {
        // Menampilkan atau menyembunyikan form
        document.getElementById('custom-form-gallery').classList.toggle('custom-hidden');
        // Menyembunyikan header
        document.getElementById('header').style.display = 'none';
        document.getElementById('galeri-table').style.display = 'none';
        
    });

    // Script untuk menangani validasi dan mengirimkan data dengan fetch API
    document.getElementById('custom-form-gallery').addEventListener('submit', async function(event) {
        event.preventDefault();

        // menggumpulkan semua inputan dari form
        const formData = new FormData(this);

        // melakukan validasi inputan melalui objek form
        const fileInput = form.querySelector('input[type="file"]'); // mengambil inputan file
        const file = fileInput.files[0]; // objek fileInput mengambil tipe file
        const allowedTypes = ['image/jpeg', 'image/png']; //registrasi jenis atau tipe file yang diizinkan

        console.log('data', formData)
        // Validasi tipe file
        if (file && !allowedTypes.includes(file.type)) {
            document.getElementById('image-error').textContent = 'File type is not allowed. Please upload a JPEG or PNG image.';
            return; // Menghentikan proses form
        }

        document.getElementById('image-error').textContent = ''; // Menghapus pesan error jika ada



        const response = await fetch('/belajar-web-native/services/galeri.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        // Display validation errors below each field
        if (result.errors) {
            document.getElementById('title-error').textContent = result.errors.title || '';
            document.getElementById('image-error').textContent = result.errors.image || '';
            document.getElementById('description-error').textContent = result.errors.description || '';
            document.getElementById('category-error').textContent = result.errors.category || '';
        } else {
            // Handle success (e.g., reload the page or reset the form)
            alert('Gallery created successfully!');
            window.location.reload();
        }
    });

    // Script untuk menangani aksi tombol Cancel
    document.getElementById('btn-cancel').addEventListener('click', function() {
        const form = document.getElementById('custom-form-gallery');
        const formData = new FormData(form);

        const fileInput = form.querySelector('input[type="file"]');
        const file = fileInput.files[0]; 
        const allowedTypes = ['image/jpeg', 'image/png'];
    
        console.log('file', formData.get('image'))
    if (formData.get('title') == '') {
        document.getElementById('title-error').textContent = 'Judul tidak boleh kosong'; 
    }

    if (file && !allowedTypes.includes(file.type)) {
        // Menampilkan pesan error jika tipe file tidak sesuai
        document.getElementById('image-error').textContent = 'File type is not allowed. Please upload a JPEG or PNG image.';
        return; 
    } 
    else {
        // Jika tipe file sesuai, lanjutkan dengan proses form
        document.getElementById('image-error').textContent = ''; // Menghapus pesan error jika ada
    }
        // if (formData.get('title') == '' || formData.get('description') == '' || formData.get('category') == '') {
        //     console.log('datanya kosong')
        //     return
        // }
        
        // console.log(formData.get('image'))

        if (formData.has('title') || formData.has('description') || formData.has('category') || formData.has('image')) {
            if (confirm('Are you sure you want to cancel? This will delete all unsaved data.')) {
                form.reset(); // Menghapus inputan
                form.classList.add('custom-hidden'); // Menyembunyikan form
                document.getElementById('header').style.display = 'block'; // Menampilkan header
            }
        } else {
            form.classList.add('custom-hidden'); // Menyembunyikan form
            document.getElementById('header').style.display = 'block'; // Menampilkan header
        }
    });
</script>

