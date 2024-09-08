<div class="container mx-auto p-4">
    
        <?php
        include '_form-galeri-photo.php';
        ?>
        <div id="header">
            <h2 class="text-2xl font-semibold mb-4">KONTENT LIST GALERI PHOTO ADMIN</h2>
            <p>Welcome to your dashboard. Here is where you can manage your settings, view notifications, and more.</p>
            <!-- Tombol Buat Galeri -->
            <button id="btn-add-gallery" class="btn-add">Tambah Galeri</button>
        </div>
        
        <div id="gallery-app">
            <!-- <galeri-table></galeri-table> -->
            <galeri-table :galleries="galleries" :loading="loading" :error="error"></galeri-table>
            <!-- {{ galleries }} -->
        </div>
</div>

<!-- script untuk vue js -->
<script type="module">
    import { createApp, ref, onMounted } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
    import ButtonComponent from '/belajar-web-native/assets/js/components/ButtonComponent.js';
    import GaleriTable from '/belajar-web-native/assets/js/components/GaleriTable.js';
    createApp({
        components: {
                ButtonComponent,
                GaleriTable
            },
        setup() {
            const galleries = ref([]);
            const message = ref('pesan dari vue js');
            const error = ref('');
            const loading = ref(false);

            async function fetchGalleries() {
                loading.value = true;
                try {
                    const response = await axios.get('/belajar-web-native/services/galeri.php');
                    const result = await response.data;
                    galleries.value = result.data;
                } catch (err) {
                    error.value = 'Gagal mengambil data galeri.';
                    console.error(err);
                } finally {
                    loading.value = false;
                }
            }


            onMounted(async()=> {
                await fetchGalleries();
            });
            return {
                galleries,
                error,
                loading,
                message
            };
        }
    }).mount('#gallery-app');
</script>


<!-- script untuk ajax -->
<script>
    // Script untuk menampilkan form galeri saat tombol ditekan
    document.getElementById('btn-add-gallery').addEventListener('click', function() {
        // Menampilkan atau menyembunyikan form
        document.getElementById('custom-form-gallery').classList.toggle('custom-hidden');
        // Menyembunyikan header
        document.getElementById('header').style.display = 'none';
        
    });

    // Script untuk menangani validasi dan mengirimkan data dengan fetch API
    document.getElementById('custom-form-gallery').addEventListener('submit', async function(event) {
        event.preventDefault();

        const form = document.getElementById('custom-form-gallery');
        const formData = new FormData(form);
        const fileInput = form.querySelector('input[type="file"]');
        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png'];
        const title = form.querySelector('input[name="title"]').value.trim();
        const description = form.querySelector('textarea[name="description"]').value.trim();
        const category = form.querySelector('select[name="category"]').value.trim();

        // Reset errors
        ['title', 'image', 'description', 'category'].forEach(field => {
            document.getElementById(`${field}-error`).textContent = '';
        });

        // Validate
        if (!title || !description || !category || !file) {
            document.getElementById('form-error').textContent = 'Semua field harus diisi.';
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            document.getElementById('image-error').textContent = 'Tipe file tidak diperbolehkan. Harap unggah gambar JPEG atau PNG.';
            return;
        }

        // Submit form
        const response = await fetch('/belajar-web-native/services/galeri.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        if (result.errors) {
            for (const [key, value] of Object.entries(result.errors)) {
                document.getElementById(`${key}-error`).textContent = value;
            }
        } else {
            form.reset();
            form.classList.add('custom-hidden');
            document.getElementById('header').style.display = 'block';
        }
    });


    // Script untuk menangani aksi tombol Cancel
    document.getElementById('btn-cancel').addEventListener('click', function() {
        const form = document.getElementById('custom-form-gallery');
        const title = form.querySelector('input[name="title"]').value.trim();
        const image = form.querySelector('input[name="image"]').files.length;  // Mengecek apakah ada file yang diupload
        const description = form.querySelector('textarea[name="description"]').value.trim();
        const category = form.querySelector('select[name="category"]').value.trim();
        
        // Memeriksa jika ada field yang kosong
        if (title === '' || description === '' || category === '' || image === 0) {
            // Tampilkan alert jika ada field yang kosong
            const confirmCancel = confirm('Beberapa field kosong. Apakah Anda yakin ingin membatalkan?');
            
            if (confirmCancel) {
                // Jika pengguna mengkonfirmasi, hapus pesan error
                document.getElementById('title-error').textContent = '';
                document.getElementById('image-error').textContent = '';
                document.getElementById('description-error').textContent = '';
                document.getElementById('category-error').textContent = '';
                
                // Reset formulir dan tampilkan header serta tabel galeri
                form.reset();
                form.classList.add('custom-hidden');
                document.getElementById('header').style.display = 'block';
                document.getElementById('galeri-table').style.display = 'block';
            }
            // Jika pengguna tidak mengkonfirmasi, tetap di halaman
            return;
        }

        // Jika semua field sudah diisi, proses untuk menutup formulir
        if (confirm('Apakah Anda yakin ingin membatalkan? Semua data yang belum disimpan akan dihapus.')) {
            form.reset();  // Reset formulir untuk menghapus inputan
            form.classList.add('custom-hidden');  // Sembunyikan formulir
            document.getElementById('header').style.display = 'block';  // Tampilkan header
            document.getElementById('galeri-table').style.display = 'block';  // Tampilkan tabel galeri
        }
    });
</script>

