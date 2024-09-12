<?php
    include '../../helper/categories.php'
?>
<form   id="custom-form-gallery"
        v-if="isFormVisible"
        class="custom-form-container" 
        @submit.prevent="submitForm"
        method="POST" 
        enctype="multipart/form-data">
    <input type="hidden" name="action" value="store">
    <div class="custom-form-group">
        <label for="title">Title:</label>
        <input v-model="form.title" type="text" id="title">
        <span class="custom-error-message" id="title-error">{{ errors.title }}</span>
    </div>

    <div class="custom-form-group">
        <label for="image">Images:</label>
        <!-- <input type="file" id="image"  @change="handleFileChange"> -->
        <input type="file" id="image"  @change="handleFileChange" multiple>
        <span class="custom-error-message" id="image-error">{{ errors.image }}</span>
    </div>

    <div class="custom-form-group">
        <label for="description">Description:</label>
        <textarea v-model="form.description" id="description" name="description"></textarea>
        <span class="custom-error-message" id="description-error">{{ errors.description }}</span>
    </div>

    <div class="custom-form-group">
        <label for="category">Category:</label>
        <select v-model="form.category" id="category" name="category">
            <?php foreach(CATEGORIES as $key => $category): ?>
                <!-- <option value="<?php echo $key;?>"> <?php echo htmlspecialchars($category)?> </option> -->
                <option value="<?php echo htmlspecialchars($key); ?>" 
                    <?php echo ($key === array_key_first(CATEGORIES)) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span class="custom-error-message" id="category-error">{{ errors.category }}</span>
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
