<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-banners.css') ?>?v=<?= time() ?>">

<div class="banner-form-wrapper">
    <!-- Page Header & Action Bar -->
    <div class="banner-page-header">
        <div class="banner-header-left">
            <div class="banner-header-icon">
                <i class="fa-solid fa-photo-film"></i>
            </div>
            <div>
                <h2 class="banner-header-title">Add New Banner</h2>
                <p class="banner-header-subtitle">Create a promotional banner or video showcase for your store's homepage sections.</p>
            </div>
        </div>
        <div>
            <a href="<?= base_url('admin/banners') ?>" class="btn-banner-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Banners
            </a>
        </div>
    </div>

    <!-- Flash Error Notifications -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div style="background: rgba(239, 68, 68, 0.08); color: #dc2626; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; border: 1.5px solid rgba(239, 68, 68, 0.25); display: flex; align-items: flex-start; gap: 12px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 18px; margin-top: 2px;"></i>
            <div>
                <div style="font-weight: 700; margin-bottom: 4px; font-size: 14px;">Please review the following errors:</div>
                <ul style="margin: 0; padding-left: 18px; font-size: 13.5px; line-height: 1.5;">
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Container Card -->
    <div class="banner-card-container">
        <form action="<?= base_url('admin/banners/store') ?>" method="POST" enctype="multipart/form-data" id="bannerForm">
            <?= csrf_field() ?>

            <div class="banner-card-body">
                <!-- Banner Title -->
                <div class="banner-form-group">
                    <label class="banner-form-label" for="bannerTitle">
                        <i class="fa-solid fa-heading" style="color: #7a1530;"></i>
                        <span>Banner Title</span>
                        <span class="required-star">*</span>
                    </label>
                    <div class="banner-input-wrapper">
                        <i class="fa-solid fa-pen-nib banner-input-icon"></i>
                        <input 
                            type="text" 
                            id="bannerTitle" 
                            name="title" 
                            value="<?= old('title') ?>" 
                            required 
                            placeholder="e.g. Pure Kanchipuram Silk Saree Festival Collection" 
                            class="banner-form-input"
                            autocomplete="off"
                        >
                    </div>
                    <div class="banner-form-help">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>A descriptive title used for internal identification and alt attributes.</span>
                    </div>
                </div>

                <!-- Banner Position -->
                <div class="banner-form-group">
                    <label class="banner-form-label" for="bannerPositionSelect">
                        <i class="fa-solid fa-layer-group" style="color: #7a1530;"></i>
                        <span>Banner Position</span>
                        <span class="required-star">*</span>
                    </label>
                    <div class="banner-input-wrapper">
                        <i class="fa-solid fa-sliders banner-input-icon"></i>
                        <select name="position" id="bannerPositionSelect" required class="banner-form-select">
                            <option value="hero_section" <?= (old('position') == 'hero_section' || !old('position')) ? 'selected' : '' ?>>1. Hero Section (Main Top Banner)</option>
                            <option value="secondary-banner-carousel" <?= (old('position') == 'secondary-banner-carousel') ? 'selected' : '' ?>>2. Secondary Banner Carousel (Exclusive Sarees / Festival)</option>
                            <option value="diwali-dropdownvideo-banner" <?= (old('position') == 'diwali-dropdownvideo-banner') ? 'selected' : '' ?>>3. Diwali Saree Section (Scrolling Background Saree Banners)</option>
                        </select>
                    </div>

                    <!-- Visual Position Cards -->
                    <div class="banner-position-hints">
                        <div class="banner-pos-card active" data-position="hero_section">
                            <span class="banner-pos-num">1</span>
                            <div class="banner-pos-name">Hero Section</div>
                            <div class="banner-pos-desc">Main top slideshow or autoplay hero video at the very top of the homepage.</div>
                        </div>
                        <div class="banner-pos-card" data-position="secondary-banner-carousel">
                            <span class="banner-pos-num">2</span>
                            <div class="banner-pos-name">Secondary Carousel</div>
                            <div class="banner-pos-desc">Exclusive sarees, festive offers & season launches carousel showcase.</div>
                        </div>
                        <div class="banner-pos-card" data-position="diwali-dropdownvideo-banner">
                            <span class="banner-pos-num">3</span>
                            <div class="banner-pos-name">Diwali Section</div>
                            <div class="banner-pos-desc">Atmospheric scrolling background banners highlighting wedding silk collections.</div>
                        </div>
                    </div>
                </div>

                <!-- Banner Media (Image or Video) Redesign -->
                <div class="banner-form-group">
                    <label class="banner-form-label">
                        <i class="fa-solid fa-photo-film" style="color: #7a1530;"></i>
                        <span>Banner Media (Image or Video)</span>
                        <span class="required-star">*</span>
                        <span class="banner-label-badge">High Resolution</span>
                    </label>

                    <div class="banner-upload-dropzone" id="bannerDropzone">
                        <input 
                            type="file" 
                            name="media_url" 
                            id="bannerMediaInput" 
                            accept="image/png,image/jpeg,image/webp,image/gif,video/mp4,video/webm" 
                            required 
                            class="banner-hidden-file-input"
                        >
                        <div class="banner-upload-icon-circle">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div class="banner-upload-title">Drop your banner file here, or click to browse</div>
                        <div class="banner-upload-subtitle">Supports high quality images or MP4 / WEBM video</div>
                        <div class="banner-upload-tags">
                            <span class="banner-tag-badge badge-image"><i class="fa-solid fa-image"></i> PNG, JPG, WEBP</span>
                            <span class="banner-tag-badge badge-video"><i class="fa-solid fa-video"></i> MP4, WEBM</span>
                            <span class="banner-tag-badge">Max 50MB</span>
                        </div>
                    </div>

                    <!-- Live Media Preview Box (Hidden until file selected) -->
                    <div class="banner-preview-box" id="bannerPreviewContainer" style="display: none;">
                        <div class="banner-preview-media-holder" id="bannerPreviewMedia">
                            <!-- Populated dynamically by JS -->
                        </div>
                        <div class="banner-preview-info">
                            <div class="banner-preview-filename" id="bannerPreviewName">filename.ext</div>
                            <div class="banner-preview-meta">
                                <span class="banner-preview-type-pill type-image" id="bannerPreviewType">IMAGE</span>
                                <span id="bannerPreviewSize">0 KB</span>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-banner-remove-media" id="btnRemoveMedia">
                                <i class="fa-solid fa-trash-can"></i> Remove
                            </button>
                        </div>
                    </div>

                    <div class="banner-form-help">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>For Hero & Secondary banners, 1920×720 or 1920×650 is recommended. For videos, use 1080p MP4.</span>
                    </div>
                </div>

                <!-- Is Active Toggle -->
                <div class="banner-form-group">
                    <div class="banner-status-container">
                        <div class="banner-status-info">
                            <div class="banner-status-icon">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <div>
                                <div class="banner-status-title">Display Status</div>
                                <p class="banner-status-desc">When enabled, this banner will be actively displayed to customers in the selected position.</p>
                            </div>
                        </div>
                        <div>
                            <label class="banner-toggle-switch">
                                <input type="checkbox" name="is_active" value="1" <?= (old('is_active', '1') == '1') ? 'checked' : '' ?>>
                                <span class="banner-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="banner-card-footer">
                <div class="banner-footer-left">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>All changes are published immediately to the storefront.</span>
                </div>
                <div class="banner-footer-actions">
                    <a href="<?= base_url('admin/banners') ?>" class="btn-banner-cancel">Cancel</a>
                    <button type="submit" class="btn-banner-submit" id="btnSubmitBanner">
                        <i class="fa-solid fa-floppy-disk"></i> Save Banner
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/js/admin-banners.js') ?>?v=<?= time() ?>"></script>

<?= $this->endSection() ?>
