<form id="custom-form-gallery" class="custom-hidden custom-form-container" method="POST" action="/services/galeri.php" enctype="multipart/form-data">
    <div class="custom-form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title">
        <span class="custom-error-message" id="title-error"></span>
    </div>

    <div class="custom-form-group">
        <label for="image">Images:</label>
        <input type="file" id="image" name="image">
        <span class="custom-error-message" id="image-error"></span>
    </div>

    <div class="custom-form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>
        <span class="custom-error-message" id="description-error"></span>
    </div>

    <div class="custom-form-group">
        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="">Select a category</option>
            <option value="general">General</option>
            <option value="education">Education</option>
            <option value="food">Food</option>
            <option value="traveling">Traveling</option>
            <!-- Tambahkan kategori lainnya jika diperlukan -->
        </select>
        <span class="custom-error-message" id="category-error"></span>
    </div>

    <div id="custom-btn-group-form-gallery">
        <button type="submit" class="custom-btn custom-btn-submit">Submit</button>
        <button type="button" class="custom-btn custom-btn-cancel" id="btn-cancel">Cancel</button>
    </div>
</form>
