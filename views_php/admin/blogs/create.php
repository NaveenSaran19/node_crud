<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Quill Rich Text Editor CDN -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<div style="max-width: 1050px; margin: 0 auto;">
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <a href="<?= base_url('admin/blogs') ?>" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 13.5px; text-decoration: none; margin-bottom: 8px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Blog Posts
            </a>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0;">Create New Blog Post</h1>
        </div>
        <div>
            <button type="button" class="btn" onclick="openLivePreviewModal()" style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 10px 18px; border-radius: 8px; font-weight: 600; color: #334155; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-eye"></i> Live Article Preview
            </button>
        </div>
    </div>

    <?php if(session()->getFlashdata('errors')): ?>
        <div style="background: #fef2f2; color: #991b1b; padding: 15px 18px; border-radius: 8px; margin-bottom: 22px; border: 1px solid #fecaca;">
            <strong style="display: block; margin-bottom: 6px;"><i class="fa-solid fa-circle-exclamation"></i> Please fix the following errors:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach(session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/blogs/store') ?>" method="POST" enctype="multipart/form-data" id="blogForm">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 24px;">
            
            <!-- Left Column: Core Blog Content -->
            <div>
                <div class="card" style="padding: 28px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff; margin-bottom: 24px;">
                    <!-- Title -->
                    <div style="margin-bottom: 20px;">
                        <label for="title" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                            Post Title <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="<?= old('title') ?>" required placeholder="e.g. The Timeless Artistry of Elampillai Handloom Sarees" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 16px; font-weight: 600; color: #0f172a; outline: none;" oninput="autoGenerateSlug(this.value)">
                    </div>

                    <!-- URL Slug -->
                    <div style="margin-bottom: 20px;">
                        <label for="slug" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                            URL Slug <span style="color: #dc2626;">*</span>
                        </label>
                        <div style="display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #f8fafc;">
                            <span style="padding: 12px 14px; color: #64748b; font-size: 14px; border-right: 1px solid #cbd5e1; background: #f1f5f9;">
                                <?= base_url('blogs/') ?>
                            </span>
                            <input type="text" id="slug" name="slug" value="<?= old('slug') ?>" required placeholder="artistry-of-elampillai-sarees" style="flex: 1; padding: 12px 14px; border: none; font-size: 14px; color: #0f172a; outline: none; background: transparent;">
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div style="margin-bottom: 24px;">
                        <label for="excerpt" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                            Short Excerpt / Summary
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="2" placeholder="Brief 1-2 sentence overview shown in blog listing cards..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; line-height: 1.5; color: #334155; outline: none;"><?= old('excerpt') ?></textarea>
                    </div>

                    <!-- Rich Text Editor -->
                    <div>
                        <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                            Article Content <span style="color: #dc2626;">*</span>
                        </label>
                        <div id="quill-editor" style="height: 380px; background: #ffffff; border-radius: 0 0 8px 8px; font-size: 15px;"></div>
                        <textarea name="content" id="content" style="display: none;"><?= old('content') ?></textarea>
                    </div>
                </div>

                <!-- SEO Metadata Card -->
                <div class="card" style="padding: 28px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                        <i class="fa-solid fa-magnifying-glass-chart" style="color: #e11d48; font-size: 18px;"></i>
                        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Search Engine Optimization (SEO)</h3>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="meta_title" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 13.5px;">SEO Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="<?= old('meta_title') ?>" placeholder="e.g. Elampillai Handloom Sarees Guide | Selvi Tex" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="meta_description" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 13.5px;">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3" placeholder="Google snippet description..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;"><?= old('meta_description') ?></textarea>
                    </div>

                    <div>
                        <label for="meta_keywords" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 13.5px;">Meta Keywords</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" value="<?= old('meta_keywords') ?>" placeholder="e.g. saree care, silk sarees, elampillai weaving" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Right Column: Publishing, Author, Date & Image -->
            <div>
                <!-- Publishing Status Card -->
                <div class="card" style="padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff; margin-bottom: 24px;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0 0 16px;">Publishing Options</h3>
                    
                    <div style="margin-bottom: 18px;">
                        <label for="status" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 13.5px;">Status</label>
                        <select id="status" name="status" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; font-weight: 600; background: #fff;">
                            <option value="published" <?= old('status') === 'draft' ? '' : 'selected' ?>>Published (Live)</option>
                            <option value="draft" <?= old('status') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label for="author" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 13.5px;">Author Name</label>
                        <input type="text" id="author" name="author" value="<?= old('author', 'Selvi Tex Master Weaver') ?>" placeholder="Selvi Tex" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="published_at" style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px; font-size: 13.5px;">Publication Date</label>
                        <input type="date" id="published_at" name="published_at" value="<?= old('published_at', date('Y-m-d')) ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; background: #e11d48; color: #fff; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(225, 29, 72,0.25);">
                            <i class="fa-solid fa-check"></i> Save &amp; Publish Post
                        </button>
                        <a href="<?= base_url('admin/blogs') ?>" class="btn" style="text-align: center; padding: 10px; background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1;">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Featured Image Card -->
                <div class="card" style="padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0 0 8px;">Featured Cover Image</h3>
                    <p style="font-size: 12.5px; color: #64748b; margin: 0 0 16px;">Primary thumbnail and header image for the article.</p>

                    <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center; background: #f8fafc; cursor: pointer;" onclick="document.getElementById('featured_image').click();">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 28px; color: #94a3b8; margin-bottom: 8px;"></i>
                        <div style="font-size: 13px; font-weight: 600; color: #334155;">Click to select cover image</div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">PNG, JPG, WEBP up to 5MB</div>
                        <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                    </div>
                    <div id="image_preview_box" style="margin-top: 14px; display: none;">
                        <img id="image_preview" src="" style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px;">
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- Live Preview Modal -->
<div id="previewModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.7); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 900px; max-height: 90vh; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">
        <div style="padding: 16px 24px; background: #0f172a; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 700; font-size: 16px;"><i class="fa-solid fa-eye" style="color: #fbbf24; margin-right: 8px;"></i> Article Content Preview</div>
            <button type="button" onclick="closeLivePreviewModal()" style="background: none; border: none; color: #94a3b8; font-size: 20px; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div style="padding: 30px 40px; overflow-y: auto; flex: 1;">
            <h1 id="previewTitle" style="font-size: 30px; font-weight: 800; color: #0f172a; margin: 0 0 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px;"></h1>
            <div id="previewBody" class="dynamic-page-html" style="line-height: 1.8; color: #334155;"></div>
        </div>
        <div style="padding: 14px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: right;">
            <button type="button" onclick="closeLivePreviewModal()" class="btn" style="padding: 8px 20px; background: #0f172a; color: #fff; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">Close Preview</button>
        </div>
    </div>
</div>

<script>
    // Initialize Quill Rich Text Editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Write your inspiring story, styling tips, or saree preservation guide...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ]
        }
    });

    // Sync Quill content with hidden textarea on submit
    var form = document.getElementById('blogForm');
    form.onsubmit = function() {
        document.getElementById('content').value = quill.root.innerHTML;
    };

    function autoGenerateSlug(title) {
        var slugInput = document.getElementById('slug');
        if (!slugInput.dataset.manualEdited) {
            slugInput.value = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    }
    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.manualEdited = "true";
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image_preview').src = e.target.result;
                document.getElementById('image_preview_box').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openLivePreviewModal() {
        var title = document.getElementById('title').value || 'Article Title Preview';
        var html = quill.root.innerHTML;
        document.getElementById('previewTitle').innerText = title;
        document.getElementById('previewBody').innerHTML = html;
        document.getElementById('previewModal').style.display = 'flex';
    }

    function closeLivePreviewModal() {
        document.getElementById('previewModal').style.display = 'none';
    }
</script>

<?= $this->endSection() ?>
