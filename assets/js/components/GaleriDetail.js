import { defineComponent, ref, onMounted, watch } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

export default defineComponent({
    name: 'GaleriDetail',
    props: {
        galleries: {
            type: Array,
            default: () => []
        },
        isDetail: {
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
        const isDetail = ref(false);
        const error = ref(props.error); 

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
            { immediate: true } 
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
            loading,
            isDetail,
            error
        };
    },
    template: `
        <div v-if="isDetail">
            <p>Halaman Detail Galeri photo</p>
        </div>
    `
});
