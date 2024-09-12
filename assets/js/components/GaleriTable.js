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
        const error = ref(props.error); 

        watch(
            () => props.galleries,
            (newVal) => {
                // console.log('Galleries updated:', newVal); // Ditampilkan saat data berubah
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

        return {
            editGallery,
            deleteGallery,
            galleries: localGalleries,
            loading: props.loading,
            error: props.error
        };
    },
    template: `
        <div id="galeri-table">
        <div v-if="!loading && !error && galleries.length === 0">Loading...</div>

            <table class="gallery-table text-2xl font-semibold mb-4" v-if="!loading && !error && galleries.length > 0">
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
                    <tr v-for="gallery in galleries" :key="gallery.id">
                        <td>{{ gallery.id }}</td>
                        <td>{{ gallery.title }}</td>
                        <td><img :src="gallery.image_path" alt="Gallery Image" width="100"></td>
                        <td>{{ gallery.description }}</td>
                        <td>{{ gallery.category }}</td>
                        <td>
                            <button @click="editGallery(gallery.id)" class="btn-edit">Edit</button>
                            <button @click="deleteGallery(gallery.id)" class="btn-delete">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            
        </div>
    `
});
