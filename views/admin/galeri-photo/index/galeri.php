<div class="container" id="app-galery">
    <div id="header" v-if="!isFormVisible">
        <h2 class="text-2xl font-semibold mb-4">KONTENT LIST GALERI PHOTO ADMIN</h2>
        <p>Welcome to your dashboard. Here is where you can manage your settings, view notifications, and more.</p>
        <button id="btn-add-gallery" @click="showForm" class="btn-add">Tambah Galeri</button>
    </div>
    <div id="gallery-table" v-if="!isFormVisible">
        <galeri-table :galleries="galleries" :loading="loading" :error="error"></galeri-table>
    </div>
        <div v-if="isFormVisible">
            <?php
                include '_form-galeri-photo.php';
            ?>
        </div>

        <!-- start card for detail album -->
         <!-- <div v-if="isGalleryDetailVisible">
            <galeri-detail :galleries="galleries" :isDetail="isDetail" :error="error"></galeri-detail>
         </div> -->
        <!-- end card for detail album  -->
    </div>

<!-- script untuk vue js -->
<script type="module">
    import { createApp, ref, onMounted, reactive } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
    import GaleriTable from '/belajar-web-native/assets/js/components/GaleriTable.js';
    createApp({
        components: {
                GaleriTable, 
            },
        setup() {
            const galleries = ref([]);
            const message = ref('pesan dari vue js');
            const error = ref('');
            const loading = ref(false);
            const isOpen = ref(true);
            const isFormVisible = ref(false);

            const form = reactive({
                'title': '',
                'images': [],
                'description': '',
                'category': ''
            });

            // membuat objek error dari method reactive
            const errors = reactive({
                'title': '',
                'image': null,
                'description': '',
                'category' : ''
            });

            // function untuk menampilkan data 
            async function fetchGalleries() {
                loading.value = true;
                error.value = '';
                try {
                    const response = await axios.get('/belajar-web-native/services/galeri.php');
                    const result = await response.data;
                        
                    if (result.data && result.data.length > 0) {
                            galleries.value = result.data; // Set data galeri
                    } else {
                        error.value = 'Data galeri belum ada.'; // Tampilkan pesan jika data kosong
                    }
                    loading.value = false; // Sembunyikan loading setelah mendapatkan data
                    console.log('Fetched galleries:', galleries.value);

                } catch (err) {
                    // loading.vaue = false;
                    error.value = 'Gagal mengambil data galeri.';
                    console.error(err);
                } finally {
                    loading.value = false; // Pastikan loading disembunyikan di sini
                }
            }

            // funcion untuk menghandle perubahan file
            function handleFileChange(event) {
                // form.image = event.target.files[0];
                form.images = Array.from(event.target.files);
            }

            async function submitForm() {
                Object.keys(errors).forEach(key => errors[key] = '');

                // Validasi
                if (!form.title) errors.title = 'Title is required.';
                if (!form.description) errors.description = 'Description is required.';
                if (!form.category) errors.category = 'Category is required.';

                if (!form.images || form.images.length === 0) {
                    errors.image = 'At least one image is required.';
                } else {
                    form.images.forEach((image, index) => {
                        if (!['image/jpeg', 'image/png'].includes(image.type)) {
                            errors.image = `File ${index + 1} is an invalid type. Please upload JPEG or PNG images.`;
                        }
                    });
                }
                if (Object.values(errors).some(error => error)) return; 

                // Siapkan FormData
                const formData = new FormData();
                formData.append('action', 'store'); 
                formData.append('title', form.title);
                formData.append('description', form.description);
                formData.append('category', form.category);

                form.images.forEach((image, index) => {
                    formData.append(`images[${index}]`, image); 
                });

                try {
                    const response = await axios.post('/belajar-web-native/services/galeri.php', 
                        formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                        });
                    
                        const result = response.data;

                        if (result.errors) {
                            // Jika ada error, tampilkan error ke masing-masing input
                            Object.keys(form).forEach(key => form[key] = key === 'images' ? [] : '');
                            isFormVisible.value = false;
                        } else {
                            Object.keys(form).forEach(key => form[key] = '');
                            isFormVisible.value = false;
                            await fetchGalleries(); 
                        }
                } catch (err) {
                    console.error('Error submitting form:', err);
                }
            }
            // function untuk cancel storeForm
            function cancelStoreForm() {
                let emptyFields = []; // Array untuk menyimpan field yang kosong

                if (form.title === "") {
                    emptyFields.push('Title');
                }

                if (form.description === "") {
                    emptyFields.push('Description');
                }

                if (form.category === "") {
                    emptyFields.push('Category');
                }

                if (form.images.length === 0) {
                    emptyFields.push('Images');
                }

                // Jika semua field kosong
                if (emptyFields.length === 4) { 
                    isFormVisible.value = false;
                return;
                }
                else if (emptyFields.length > 0) {
                    if (confirm('Yakin ingin membatalkan?')) {
                        isFormVisible.value = false; 
                        form.title = ""
                        form.description = ""
                        form.category = ""
                        form.images = []
                    }
                }
                else {
                    if (confirm('Yakin ingin membatalkan?')) {
                        isFormVisible.value = false; 
                        form.title = ""
                        form.description = ""
                        form.category = ""
                        form.images = []
                    }
                }
            }

            function showGalery() {

            }

            // function untuk handle showForm
            function showForm() {
                isFormVisible.value = true; // Tampilkan form

            }

            onMounted(async()=> {
                await fetchGalleries();
            });
            return {
                galleries,
                error,
                errors,
                loading,
                message,
                isOpen,
                showForm,
                form,
                handleFileChange,
                isFormVisible,
                cancelStoreForm,
                submitForm,
                showGalery,
            };
        }
    }).mount('#app-galery');
</script>


<!-- script untuk ajax -->
<script>
    // Script untuk menampilkan form galeri saat tombol ditekan
    // document.getElementById('btn-add-gallery').addEventListener('click', function() {
    //     // Menampilkan atau menyembunyikan form
    //     document.getElementById('custom-form-gallery').classList.toggle('custom-hidden');
    //     // Menyembunyikan header
    //     document.getElementById('header').style.display = 'none';
    //     document.getElementById('galeri-table').classList.toggle('custom-hidden');
        
    // });

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
            // form.classList.add('custom-hidden');
            // document.getElementById('header').style.display = 'block';
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
                // form.classList.add('custom-hidden');
                // document.getElementById('header').style.display = 'block';
                // document.getElementById('galeri-table').style.display = 'block';
            }
            // Jika pengguna tidak mengkonfirmasi, tetap di halaman
            return;
        }

        // Jika semua field sudah diisi, proses untuk menutup formulir
        if (confirm('Apakah Anda yakin ingin membatalkan? Semua data yang belum disimpan akan dihapus.')) {
            form.reset();  // Reset formulir untuk menghapus inputan
            // form.classList.add('custom-hidden');  // Sembunyikan formulir
            // document.getElementById('header').style.display = 'block';  // Tampilkan header
            // document.getElementById('galeri-table').style.display = 'block';  // Tampilkan tabel galeri
        }
    });
</script>

