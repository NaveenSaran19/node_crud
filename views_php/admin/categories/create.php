<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-categories.css') ?>?v=<?= time() ?>">

<div class="category-form-wrapper">
    <!-- Page Header & Action Bar -->
    <div class="cat-page-header">
        <div class="cat-header-left">
            <div class="cat-header-icon">
                <i class="fa-solid fa-folder-plus"></i>
            </div>
            <div>
                <h2 class="cat-header-title">Add New Category</h2>
                <p class="cat-header-subtitle">Create a new subcategory linked under your store's header navigation categories.</p>
            </div>
        </div>
        <div>
            <a href="<?= base_url('admin/categories') ?>" class="btn-cat-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Categories
            </a>
        </div>
    </div>

    <!-- Flash Error Notifications -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; border: 1.5px solid rgba(239, 68, 68, 0.25); display: flex; align-items: flex-start; gap: 12px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 18px; margin-top: 2px;"></i>
            <div>
                <strong style="display: block; font-size: 14px; margin-bottom: 4px;">Please correct the following errors:</strong>
                <ul style="margin: 0; padding-left: 18px; font-size: 13.5px;">
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; border: 1.5px solid rgba(239, 68, 68, 0.25); display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 18px;"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Main Card -->
    <div class="cat-card-container">
        <form action="<?= base_url('admin/categories/store') ?>" method="POST" enctype="multipart/form-data" id="addCategoryForm">
            <div class="cat-card-body">
                
                <!-- 1. Header Category Selector -->
                <div class="cat-form-group">
                    <label class="cat-form-label" for="parent_category_id">
                        <i class="fa-solid fa-layer-group" style="color: #0d9488;"></i>
                        <span>Header Category Name</span>
                        <span class="required-star">*</span>
                        <span class="cat-label-badge">Navigation Parent</span>
                    </label>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div class="cat-input-wrapper">
                            <i class="fa-solid fa-list-check cat-input-icon" style="color: #0d9488;"></i>
                            <select name="parent_category_id" id="parent_category_id" class="cat-form-select" onchange="toggleNewMainCat()" required>
                                <option value="">-- Select Header Category --</option>
                                <?php if(!empty($headerCategories)): foreach($headerCategories as $hc): ?>
                                    <option value="<?= $hc['id'] ?>" <?= old('parent_category_id') == $hc['id'] ? 'selected' : '' ?>>
                                        🏷️ <?= esc($hc['name']) ?>
                                    </option>
                                <?php endforeach; endif; ?>
                                <option value="NEW" <?= old('parent_category_id') === 'NEW' ? 'selected' : '' ?>>
                                    ➕ Add New Header Category
                                </option>
                            </select>
                        </div>

                        <!-- Dynamic Input for New Header Category -->
                        <div class="cat-input-wrapper" id="new_main_category_wrapper" style="display: <?= old('parent_category_id') === 'NEW' ? 'flex' : 'none' ?>;">
                            <i class="fa-solid fa-plus-circle cat-input-icon" style="color: #e11d48;"></i>
                            <input type="text" name="new_main_category" id="new_main_category" class="cat-form-input" placeholder="Type new header category name..." value="<?= old('new_main_category') ?>">
                        </div>
                    </div>
                    
                    <div class="cat-form-help">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Select a header navigation category (e.g. Cotton, Pudaivai) or choose "Add New Header Category" to create one.</span>
                    </div>
                </div>

                <!-- 2. Subcategory Name -->
                <div class="cat-form-group">
                    <label class="cat-form-label" for="subcategory_name">
                        <i class="fa-solid fa-tag" style="color: #e11d48;"></i>
                        <span>Subcategory Name</span>
                        <span class="required-star">*</span>
                    </label>

                    <div class="cat-input-wrapper">
                        <i class="fa-solid fa-pen-nib cat-input-icon"></i>
                        <input type="text" name="name" id="subcategory_name" class="cat-form-input" value="<?= old('name') ?>" required placeholder="e.g. Pure Soft Silk, Kanchi Cotton, Gadwal Sarees">
                    </div>

                    <div class="cat-form-help">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>The category name that customers click on storefront menus and product listings.</span>
                    </div>
                </div>

                <!-- 3. Category Image Uploader -->
                <div class="cat-form-group">
                    <label class="cat-form-label">
                        <i class="fa-solid fa-image" style="color: #3b82f6;"></i>
                        <span>Category Image</span>
                        <small style="color: #64748b; font-weight: normal; margin-left: 4px;">(Optional)</small>
                    </label>

                    <div class="cat-upload-container" id="categoryUploadDropzone">
                        <div class="cat-upload-icon-circle">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div class="cat-upload-title">Click to browse or drag &amp; drop category image</div>
                        <p class="cat-upload-subtitle">Supports PNG, JPG, JPEG, WEBP (Recommended: 600 &times; 600px square)</p>
                        <input type="file" name="image" id="category_image_input" accept="image/png,image/jpeg,image/webp,image/jpg" class="cat-upload-input" onchange="previewCategoryImage(this)">
                    </div>

                    <!-- Live Image Preview Box -->
                    <div class="cat-preview-box" id="categoryImagePreviewBox" style="display: none;">
                        <img src="" id="categoryPreviewImg" class="cat-preview-thumb" alt="Preview">
                        <div class="cat-preview-info">
                            <div class="cat-preview-filename" id="categoryPreviewFilename">filename.jpg</div>
                            <div class="cat-preview-meta" id="categoryPreviewFilesize">0 KB</div>
                        </div>
                        <button type="button" class="btn btn-action btn-danger" onclick="clearCategoryImage()" title="Remove Image" style="padding: 8px 12px; border-radius: 8px;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Card Footer -->
            <div class="cat-card-footer">
                <div class="cat-footer-info">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Category will automatically appear in customer storefront navigation and catalog filters.</span>
                </div>
                <button type="submit" class="btn-cat-save">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Category</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/js/admin-categories.js') ?>?v=<?= time() ?>"></script>

<script>
function previewCategoryImage(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file (PNG, JPG, WEBP).');
            input.value = '';
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('categoryPreviewImg').src = e.target.result;
            document.getElementById('categoryPreviewFilename').textContent = file.name;
            document.getElementById('categoryPreviewFilesize').textContent = (file.size / 1024).toFixed(1) + ' KB';
            document.getElementById('categoryImagePreviewBox').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
}

function clearCategoryImage() {
    var input = document.getElementById('category_image_input');
    if (input) input.value = '';
    var preview = document.getElementById('categoryImagePreviewBox');
    if (preview) preview.style.display = 'none';
}
</script>

<?= $this->endSection() ?>
