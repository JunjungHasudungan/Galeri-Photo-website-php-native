<form   id="custom-form-gallery"
        v-if="isFormVisible"
        class="custom-form-container" 
        @submit.prevent="submitForm"
        method="POST" action="/services/galeri.php" 
        enctype="multipart/form-data">
    <input type="hidden" name="action" value="store">
    <div class="custom-form-group">
        <label for="title">Title:</label>
        <input v-model="form.title" type="text" id="title" name="title">
        <span class="custom-error-message" id="title-error">{{ errors.title }}</span>
        <!-- <input type="text" id="title" name="title">
        <span class="custom-error-message" id="title-error"></span> -->
    </div>

    <div class="custom-form-group">
        <label for="image">Images:</label>
        <input type="file" id="image" name="image" @change="handleFileChange">
        <span class="custom-error-message" id="image-error">{{ errors.image }}</span>
        <!-- <input type="file" id="image" name="image">
        <span class="custom-error-message" id="image-error"></span> -->
    </div>

    <div class="custom-form-group">
        <label for="description">Description:</label>
        <textarea v-model="form.description" id="description" name="description"></textarea>
        <span class="custom-error-message" id="description-error">{{ errors.description }}</span>
        <!-- <textarea id="description" name="description"></textarea>
        <span class="custom-error-message" id="description-error"></span> -->
    </div>

    <div class="custom-form-group">
        <label for="category">Category:</label>
        <select v-model="form.category" id="category" name="category">
            <option value="">Select a category</option>
            <option value="general">General</option>
            <option value="education">Education</option>
            <option value="food">Food</option>
            <option value="traveling">Traveling</option>
            <!-- Tambahkan kategori lainnya jika diperlukan -->
        </select>
        <span class="custom-error-message" id="category-error">{{ errors.category }}</span>
        <!-- <span class="custom-error-message" id="category-error"></span> -->
    </div>

    <div id="custom-btn-group-form-gallery">
        <button type="submit" class="custom-btn custom-btn-submit">Submit</button>
        <button 
            type="button" 
            class="custom-btn custom-btn-cancel" 
            @click="cancelStoreForm"
            id="btn-cancel">
            Cancel
        </button>
    </div>
</form>
