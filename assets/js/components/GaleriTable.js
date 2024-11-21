import { defineComponent, ref, onMounted, watch } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

export default defineComponent({
    name: 'GaleriTable',
    props: {
        galleries: {
            type: Array,
            default: () => []
        },
        loading: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: ''
        }
    },
    setup(props) {
        const localGalleries = ref(props.galleries);
        const loading = ref(false);
        const error = ref(props.error); 
        const selectAlbum = ref(null);

        watch(
            () => props.galleries,
            (newVal) => {
                console.log('Galleries updated:', newVal);
                localGalleries.value = newVal;
                if (newVal.length === 0) {
                    error.value = 'Tidak ada data galeri.';
                } else {
                    error.value = '';
                }
            },
            { immediate: true } // Immediate untuk langsung trigger saat mounted
        );

        function editGallery(id) {
            // Implement edit functionality here
            console.log(`Edit gallery with ID: ${id}`);
        }

        function deleteGallery(id) {
            // Implement delete functionality here
            console.log(`Delete gallery with ID: ${id}`);
        }

        function viewGaleri(gallery) {
            selectAlbum.value = gallery;
            // isGalleryDetailVisible.value = true;
            console.log(gallery);
        }

        return {
            editGallery,
            deleteGallery,
            viewGaleri,
            galleries: localGalleries,
            loading,
            error
        };
    },
    template: `
        <div id="galeri-table">
        <div v-if="loading">Loading..</div>
                    <div v-else-if="error">{{ error }}</div>
        <div v-else-if="!loading && galleries.length === 0">
            Data galeri belum ada.
        </div>
            <table class="gallery-table text-2xl font-semibold mb-4" v-if="!loading && !error && galleries.length > 0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(gallery, index) in galleries" :key="gallery.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ gallery.title }}</td>
                        <td>{{ gallery.description }}</td>
                        <td>{{ gallery.category }}</td>
                        <td>
                            <button @click="viewGaleri(gallery.slug)" class="btn-edit">View</button>
                            <button @click="editGallery(gallery.id)" class="btn-edit">Edit</button>
                            <button @click="deleteGallery(gallery.id)" class="btn-delete">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `
});
