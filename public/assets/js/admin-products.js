function buildDynamicRowSizeListHtml() {
    if (window.CONFIGURED_ATTRIBUTE_SIZES && window.CONFIGURED_ATTRIBUTE_SIZES.length > 0) {
        var grouped = {};
        window.CONFIGURED_ATTRIBUTE_SIZES.forEach(function(s) {
            var grp = s.category || 'General';
            if (!grouped[grp]) grouped[grp] = [];
            grouped[grp].push(s.name);
        });

        // Priority order: Kids first, then Apparel, Dhoti, Saree, General
        var categoryOrder = ['Kids', 'Apparel', 'Dhoti', 'Saree', 'General'];
        var sortedGroups = Object.keys(grouped).sort(function(a, b) {
            var idxA = categoryOrder.indexOf(a);
            var idxB = categoryOrder.indexOf(b);
            if (idxA === -1) idxA = 999;
            if (idxB === -1) idxB = 999;
            return idxA - idxB;
        });

        var html = '';
        sortedGroups.forEach(function(grp) {
            var iconHtml = '<i class="fa-solid fa-shapes"></i>';
            if (grp.toLowerCase().includes('kid')) iconHtml = '<i class="fa-solid fa-child" style="color: #ea580c;"></i>';
            else if (grp.toLowerCase().includes('apparel')) iconHtml = '<i class="fa-solid fa-shirt" style="color: #2563eb;"></i>';
            else if (grp.toLowerCase().includes('dhoti')) iconHtml = '<i class="fa-solid fa-vest" style="color: #d97706;"></i>';
            else if (grp.toLowerCase().includes('saree')) iconHtml = '<i class="fa-solid fa-scroll" style="color: #7a1530;"></i>';

            html += '<div class="dd-group-title">' + iconHtml + ' ' + grp + '</div>';
            grouped[grp].forEach(function(sz) {
                html += '<label class="dd-opt-label"><input type="checkbox" value="' + sz + '"> <span>' + sz + '</span></label>';
            });
        });
        return html;
    }
    return `
        <div class="dd-group-title"><i class="fa-solid fa-child" style="color: #ea580c;"></i> Kids Sizes</div>
        <label class="dd-opt-label"><input type="checkbox" value="0-3 Months"> <span>0-3 Months</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="3-6 Months"> <span>3-6 Months</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="6-12 Months"> <span>6-12 Months</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="1-2 Yrs"> <span>1-2 Yrs</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="2-3 Yrs"> <span>2-3 Yrs</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="3-4 Yrs"> <span>3-4 Yrs</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="4-5 Yrs"> <span>4-5 Yrs</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="5-6 Yrs"> <span>5-6 Yrs</span></label>
        <div class="dd-group-title"><i class="fa-solid fa-shirt" style="color: #2563eb;"></i> Clothing / Apparel</div>
        <label class="dd-opt-label"><input type="checkbox" value="S"> <span>S</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="M"> <span>M</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="L"> <span>L</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="XL"> <span>XL</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="XXL"> <span>XXL</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="3XL"> <span>3XL</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="Free Size"> <span>Free Size</span></label>
        <div class="dd-group-title"><i class="fa-solid fa-vest" style="color: #d97706;"></i> Dhoti Options</div>
        <label class="dd-opt-label"><input type="checkbox" value="4 Muzham (Single Dhoti)"> <span>4 Muzham (Single Dhoti)</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="8 Muzham (Double Dhoti)"> <span>8 Muzham (Double Dhoti)</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="9x5 (Dhoti + Angavastram Set)"> <span>9x5 (Dhoti + Angavastram Set)</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="10x6 (Pattu Dhoti + Shawl Set)"> <span>10x6 (Pattu Dhoti + Shawl Set)</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="2.0 Meters (Single Dhoti)"> <span>2.0 Meters (Single Dhoti)</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="4.0 Meters (Double Dhoti)"> <span>4.0 Meters (Double Dhoti)</span></label>
        <label class="dd-opt-label"><input type="checkbox" value="Readymade / Pocket Dhoti"> <span>Readymade / Pocket Dhoti</span></label>
    `;
}

/**
 * Admin Product Media & Variant Management Script
 * Handles primary image preview/replace/remove, gallery drag-and-drop & ordering,
 * product video preview, dynamic variant creation with HEX color pickers, and
 * inline variant image uploader modal.
 */

document.addEventListener('DOMContentLoaded', function() {
    initPrimaryImageUploader();
    initGalleryUploader();
    initProductVideoUploader();
    initVariantsTable();
    initVariantMediaModal();
    initBasicInfoPricing();
    initSizeMultiSelect();
});

/* ==========================================================================
   1. Primary Image Uploader & Live Preview
   ========================================================================== */
function initPrimaryImageUploader() {
    var input = document.getElementById('primary-image-input');
    var dropzone = document.getElementById('primary-upload-dropzone');
    var previewCard = document.getElementById('primary-image-preview-card');
    var previewImg = document.getElementById('primary-image-preview');
    var filenameEl = document.getElementById('primary-image-filename');
    var btnChange = document.getElementById('btn-change-primary');
    var btnRemove = document.getElementById('btn-remove-primary');
    var fromGalleryInput = document.getElementById('primary-image-from-gallery');

    if (!input) return;

    // Handle file selection
    input.addEventListener('change', function(e) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            if (!file.type.match('image.*')) {
                alert('Please choose a valid image file (JPG, PNG, WEBP).');
                input.value = '';
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                alert('Primary image file size must be less than 10MB.');
                input.value = '';
                return;
            }

            var reader = new FileReader();
            reader.onload = function(evt) {
                if (previewImg) previewImg.src = evt.target.result;
                if (filenameEl) filenameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                if (previewCard) previewCard.style.display = 'flex';
                if (dropzone) dropzone.style.display = 'none';
                if (fromGalleryInput) fromGalleryInput.value = '';
            };
            reader.readAsDataURL(file);
        }
    });

    // Change image trigger
    if (btnChange) {
        btnChange.addEventListener('click', function() {
            input.click();
        });
    }

    // Remove image
    if (btnRemove) {
        btnRemove.addEventListener('click', function() {
            input.value = '';
            if (previewImg) previewImg.src = '';
            if (previewCard) previewCard.style.display = 'none';
            if (dropzone) dropzone.style.display = 'block';
            if (fromGalleryInput) fromGalleryInput.value = '';
        });
    }

    // Drag and drop for dropzone
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('drag-over');
            }, false);
        });

        ['dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('drag-over');
            }, false);
        });

        dropzone.addEventListener('drop', function(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            if (files && files.length > 0) {
                input.files = files;
                var event = new Event('change');
                input.dispatchEvent(event);
            }
        });
    }
}

/* ==========================================================================
   2. Additional Images / Gallery Uploader
   ========================================================================== */
var selectedGalleryFiles = []; // Stored File objects for gallery

function initGalleryUploader() {
    var galleryInput = document.getElementById('gallery-images-input');
    var dropzone = document.getElementById('gallery-upload-dropzone');
    var container = document.getElementById('gallery-preview-container');

    if (!galleryInput || !container) return;

    galleryInput.addEventListener('change', function() {
        if (!galleryInput.files || galleryInput.files.length === 0) return;
        
        for (var i = 0; i < galleryInput.files.length; i++) {
            var file = galleryInput.files[i];
            if (file.type.match('image.*')) {
                selectedGalleryFiles.push(file);
            }
        }
        renderGalleryPreviews();
    });

    // Drag & drop
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('drag-over');
            }, false);
        });

        ['dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('drag-over');
            }, false);
        });

        dropzone.addEventListener('drop', function(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            if (files && files.length > 0) {
                for (var i = 0; i < files.length; i++) {
                    if (files[i].type.match('image.*')) {
                        selectedGalleryFiles.push(files[i]);
                    }
                }
                renderGalleryPreviews();
                syncGalleryInputFiles();
            }
        });
    }

    // Attach click listeners to server-rendered existing gallery cards
    attachExistingGalleryEvents();
}

function renderGalleryPreviews() {
    var container = document.getElementById('gallery-preview-container');
    if (!container) return;

    // Remove existing client-selected preview cards (keep existing server rendered ones with .existing-card)
    var clientCards = container.querySelectorAll('.client-gallery-card');
    clientCards.forEach(function(c) { c.remove(); });

    if (selectedGalleryFiles.length > 0) {
        container.style.display = 'grid';
    } else {
        var existingCards = container.querySelectorAll('.existing-card');
        if (existingCards.length === 0) {
            container.style.display = 'none';
        }
    }

    selectedGalleryFiles.forEach(function(file, idx) {
        var card = document.createElement('div');
        card.className = 'gallery-card client-gallery-card';
        card.dataset.index = idx;

        var thumbWrap = document.createElement('div');
        thumbWrap.className = 'gallery-thumb-wrap';

        var orderBadge = document.createElement('span');
        orderBadge.className = 'gallery-order-badge';
        orderBadge.textContent = '#' + (idx + 1);
        thumbWrap.appendChild(orderBadge);

        var img = document.createElement('img');
        img.alt = file.name;
        var reader = new FileReader();
        reader.onload = function(evt) {
            img.src = evt.target.result;
        };
        reader.readAsDataURL(file);
        thumbWrap.appendChild(img);
        card.appendChild(thumbWrap);

        var actions = document.createElement('div');
        actions.className = 'gallery-card-actions';

        var btnMakePrimary = document.createElement('button');
        btnMakePrimary.type = 'button';
        btnMakePrimary.className = 'btn-card-action';
        btnMakePrimary.innerHTML = '<i class="fa-solid fa-star"></i> Primary';
        btnMakePrimary.title = 'Set as Primary Image';
        btnMakePrimary.addEventListener('click', function() {
            setAsPrimaryImageFromFile(file, img.src);
        });

        var btnRemove = document.createElement('button');
        btnRemove.type = 'button';
        btnRemove.className = 'btn-card-action btn-del';
        btnRemove.innerHTML = '<i class="fa-solid fa-trash"></i>';
        btnRemove.title = 'Remove Image';
        btnRemove.addEventListener('click', function() {
            selectedGalleryFiles.splice(idx, 1);
            renderGalleryPreviews();
            syncGalleryInputFiles();
        });

        actions.appendChild(btnMakePrimary);
        actions.appendChild(btnRemove);
        card.appendChild(actions);

        container.appendChild(card);
    });

    syncGalleryInputFiles();
}

function syncGalleryInputFiles() {
    var galleryInput = document.getElementById('gallery-images-input');
    if (!galleryInput) return;

    try {
        var dataTransfer = new DataTransfer();
        selectedGalleryFiles.forEach(function(f) {
            dataTransfer.items.add(f);
        });
        galleryInput.files = dataTransfer.files;
    } catch(err) {
        // Fallback for browsers that don't support DataTransfer constructor
    }
}

function setAsPrimaryImageFromFile(file, imgSrc) {
    var previewCard = document.getElementById('primary-image-preview-card');
    var previewImg = document.getElementById('primary-image-preview');
    var filenameEl = document.getElementById('primary-image-filename');
    var dropzone = document.getElementById('primary-upload-dropzone');
    var primaryInput = document.getElementById('primary-image-input');
    var fromGalleryInput = document.getElementById('primary-image-from-gallery');

    if (previewImg) previewImg.src = imgSrc;
    if (filenameEl) filenameEl.textContent = file.name + ' (Promoted from Gallery)';
    if (previewCard) previewCard.style.display = 'flex';
    if (dropzone) dropzone.style.display = 'none';

    try {
        var dt = new DataTransfer();
        dt.items.add(file);
        if (primaryInput) primaryInput.files = dt.files;
    } catch(e) {}

    if (fromGalleryInput) fromGalleryInput.value = '';
    alert('Image promoted to Primary Image!');
}

function attachExistingGalleryEvents() {
    var container = document.getElementById('gallery-preview-container');
    if (!container) return;

    // Set as primary on existing server images
    container.querySelectorAll('.btn-existing-set-primary').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var mediaUrl = this.dataset.url;
            var mediaId = this.dataset.id;
            
            var previewCard = document.getElementById('primary-image-preview-card');
            var previewImg = document.getElementById('primary-image-preview');
            var filenameEl = document.getElementById('primary-image-filename');
            var dropzone = document.getElementById('primary-upload-dropzone');
            var fromGalleryInput = document.getElementById('primary-image-from-gallery');

            if (previewImg) previewImg.src = mediaUrl;
            if (filenameEl) filenameEl.textContent = 'Selected from gallery';
            if (previewCard) previewCard.style.display = 'flex';
            if (dropzone) dropzone.style.display = 'none';
            if (fromGalleryInput) fromGalleryInput.value = this.dataset.relativeUrl || mediaUrl;

            // Highlight card
            container.querySelectorAll('.gallery-card').forEach(function(c) { c.classList.remove('is-primary-card'); });
            var card = this.closest('.gallery-card');
            if (card) card.classList.add('is-primary-card');

            alert('Primary image updated to this gallery photo.');
        });
    });

    // Delete existing server images
    container.querySelectorAll('.btn-existing-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to delete this image?')) return;
            var mediaId = this.dataset.id;
            var card = this.closest('.gallery-card');

            // Add to hidden input of deleted media IDs
            var delInput = document.getElementById('deleted-media-ids');
            if (delInput) {
                var cur = delInput.value ? delInput.value.split(',') : [];
                cur.push(mediaId);
                delInput.value = cur.join(',');
            }
            if (card) card.remove();
        });
    });
}

/* ==========================================================================
   3. Product Video Uploader & Live Preview
   ========================================================================== */
function initProductVideoUploader() {
    var videoInput = document.getElementById('product-video-input');
    var dropzone = document.getElementById('video-upload-dropzone');
    var previewCard = document.getElementById('video-preview-card');
    var previewVideo = document.getElementById('product-video-preview');
    var filenameEl = document.getElementById('video-filename');
    var btnChange = document.getElementById('btn-change-video');
    var btnRemove = document.getElementById('btn-remove-video');

    if (!videoInput) return;

    videoInput.addEventListener('change', function() {
        if (videoInput.files && videoInput.files[0]) {
            var file = videoInput.files[0];
            
            // Format check
            if (!file.type.match('video.*') && !file.name.match(/\.(mp4|webm)$/i)) {
                alert('Please upload an MP4 or WEBM video format.');
                videoInput.value = '';
                return;
            }

            // Size check: 50MB
            if (file.size > 50 * 1024 * 1024) {
                alert('Video file size exceeds the 50MB maximum limit.');
                videoInput.value = '';
                return;
            }

            var videoUrl = URL.createObjectURL(file);
            if (previewVideo) {
                previewVideo.src = videoUrl;
                previewVideo.load();
            }
            if (filenameEl) {
                filenameEl.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(1) + ' MB)';
            }
            if (previewCard) previewCard.style.display = 'flex';
            if (dropzone) dropzone.style.display = 'none';
        }
    });

    if (btnChange) {
        btnChange.addEventListener('click', function() {
            videoInput.click();
        });
    }

    if (btnRemove) {
        btnRemove.addEventListener('click', function() {
            videoInput.value = '';
            if (previewVideo) {
                previewVideo.pause();
                previewVideo.src = '';
            }
            if (previewCard) previewCard.style.display = 'none';
            if (dropzone) dropzone.style.display = 'block';

            // If in edit mode, mark existing video for deletion
            var existingVideoId = this.dataset.existingId;
            if (existingVideoId) {
                var delInput = document.getElementById('deleted-media-ids');
                if (delInput) {
                    var cur = delInput.value ? delInput.value.split(',') : [];
                    cur.push(existingVideoId);
                    delInput.value = cur.join(',');
                }
            }
        });
    }
}

/* ==========================================================================
   4. Dynamic Product Variants System
   ========================================================================== */
var variantRowIndex = 0;
// Map storing staged File objects for each variant index: { [index]: [File, File] }
var variantImagesMap = {};

function checkVariantsEmptyState() {
    var container = document.getElementById('variants-container');
    if (!container) return;
    var rows = container.querySelectorAll('.variant-row, .variant-card');
    var existingPlaceholder = document.getElementById('no-variants-placeholder');

    if (rows.length === 0) {
        if (!existingPlaceholder) {
            var placeholder = document.createElement('div');
            placeholder.id = 'no-variants-placeholder';
            placeholder.className = 'variant-empty-card';
            placeholder.innerHTML = `
                <div class="empty-icon"><i class="fa-solid fa-layer-group"></i></div>
                <div class="empty-title">No Product Variants Added</div>
                <div class="empty-sub">This product will be sold as a standard product using base price, stock, and photos above.</div>
                <button type="button" class="btn-sm btn-add-variant-top" onclick="addVariantRow();" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 13px; cursor: pointer; border-radius: 8px;">
                    <i class="fa-solid fa-plus"></i> Add Variant
                </button>
            `;
            container.appendChild(placeholder);
        }
    } else {
        if (existingPlaceholder) {
            existingPlaceholder.remove();
        }
    }
}

function initVariantsTable() {
    var container = document.getElementById('variants-container');
    var addBtn = document.getElementById('add-variant-btn');
    var addBtnBottom = document.getElementById('add-variant-btn-bottom');

    if (!container) return;

    // Count existing cards / rows
    variantRowIndex = container.querySelectorAll('.variant-row, .variant-card').length;

    if (addBtn) {
        addBtn.addEventListener('click', function() {
            addVariantRow();
        });
    }
    if (addBtnBottom) {
        addBtnBottom.addEventListener('click', function() {
            addVariantRow();
        });
    }

    // Attach listeners to any server-rendered variant cards/rows (on edit page)
    container.querySelectorAll('.variant-row, .variant-card').forEach(function(row, idx) {
        setupVariantRowListeners(row, idx);
    });

    // Check empty state
    checkVariantsEmptyState();

    // Intercept form submit to append all variant images into the form
    var form = container.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            appendVariantFilesToForm(form);
        });
    }
}

function addVariantRow(defaultColor, defaultHex, defaultSize, defaultSale, defaultOriginal, defaultStock) {
    var container = document.getElementById('variants-container');
    if (!container) return;

    // Remove empty state placeholder if present
    var existingPlaceholder = document.getElementById('no-variants-placeholder');
    if (existingPlaceholder) {
        existingPlaceholder.remove();
    }

    var basePriceInput = document.querySelector('input[name="price"]');
    var baseOrigPriceInput = document.querySelector('input[name="original_price"]');
    var baseStockInput = document.querySelector('input[name="stock_quantity"]');

    var idx = variantRowIndex++;
    variantImagesMap[idx] = [];

    var cName = defaultColor || '';
    var cHex = defaultHex || '#7a1530';
    var cSize = defaultSize !== undefined ? defaultSize : '';
    var sale = defaultSale !== undefined ? defaultSale : (basePriceInput ? basePriceInput.value : '');
    var orig = defaultOriginal !== undefined ? defaultOriginal : (baseOrigPriceInput ? baseOrigPriceInput.value : '');
    var stock = defaultStock !== undefined ? defaultStock : (baseStockInput && baseStockInput.value !== '' ? baseStockInput.value : '10');

    var card = document.createElement('div');
    card.className = 'variant-card';
    card.dataset.index = idx;

    var numDisplay = container.querySelectorAll('.variant-card, .variant-row').length + 1;

    card.innerHTML = `
        <!-- Card Header -->
        <div class="variant-card-header">
            <div class="variant-card-header-left">
                <div class="variant-num-badge">#${numDisplay}</div>
                <div class="variant-header-swatch" style="background-color: ${cHex};"></div>
                <div class="variant-header-title-wrap">
                    <div class="variant-header-title">${cName ? cName : ('Variant #' + numDisplay)}</div>
                    <div class="variant-header-meta">
                        <span class="meta-tag color-tag"><i class="fa-solid fa-palette"></i> <span class="tag-color-text">${cName || 'Pick Color'}</span></span>
                        <span class="meta-tag size-tag"><i class="fa-solid fa-ruler"></i> <span class="tag-size-text">${cSize || 'Select Size'}</span></span>
                        <span class="meta-tag stock-tag"><i class="fa-solid fa-boxes-stacked"></i> <span class="tag-stock-text">${stock} in stock</span></span>
                    </div>
                </div>
            </div>
            <div class="variant-card-header-right">
                <button type="button" class="btn-variant-media" data-index="${idx}">
                    <i class="fa-solid fa-images"></i> Photos <span class="media-badge">0</span>
                </button>
                <button type="button" class="btn-danger-icon remove-variant-btn" title="Remove Variant">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>

        <!-- Card Body Grid -->
        <div class="variant-card-body">
            <div class="variant-field-group variant-field-color">
                <label class="variant-field-label"><i class="fa-solid fa-palette"></i> Color & Shade</label>
                <div class="variant-color-wrap">
                    <div class="color-picker-bubble" style="background-color: ${cHex};" title="Choose color">
                        <input type="color" name="variant_color_codes[]" value="${cHex}" class="color-picker-input">
                    </div>
                    <input type="text" name="variant_color_names[]" list="variant-color-options" class="premium-input color-name-input" value="${cName}" placeholder="Color (e.g. Pink)" required>
                    <span class="color-hex-badge">${cHex}</span>
                    <input type="hidden" name="variant_names[]" class="variant-combined-name" value="${cName || 'Variant'}">
                    <input type="hidden" name="variant_ids[]" value="">
                    <input type="hidden" name="variant_statuses[]" value="1">
                </div>
            </div>

            <div class="variant-field-group variant-field-size">
                <label class="variant-field-label"><i class="fa-solid fa-ruler-combined"></i> Size / Dhoti Option</label>
                <div class="row-size-multi-wrap">
                    <div class="row-size-multi-trigger" tabindex="0" role="button" aria-haspopup="true">
                        <i class="fa-solid fa-ruler-horizontal size-icon"></i>
                        <span class="row-size-text ${!cSize ? 'placeholder' : ''}">${cSize || 'Select Size(s)...'}</span>
                        <i class="fa-solid fa-chevron-down size-caret"></i>
                    </div>
                    <input type="hidden" name="variant_sizes[]" class="variant-size-input" value="${cSize}">
                    
                    <div class="row-size-multi-dropdown">
                        <div class="row-size-dd-header">
                            <label class="row-select-all-label">
                                <input type="checkbox" class="row-select-all-cb">
                                <span>Select All</span>
                            </label>
                            <button type="button" class="row-clear-btn">Clear</button>
                        </div>
                        
                        <div class="row-size-dd-list">
                            ${buildDynamicRowSizeListHtml()}
                        </div>

                        <div class="row-size-dd-custom">
                            <input type="text" class="dd-custom-input" placeholder="+ custom size">
                            <button type="button" class="dd-custom-add-btn">Add</button>
                        </div>

                        <div class="row-size-dd-footer">
                            <button type="button" class="row-done-btn">Done</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="variant-field-group variant-field-price">
                <label class="variant-field-label"><i class="fa-solid fa-tag"></i> Selling Price</label>
                <div class="input-prefix-wrapper">
                    <span class="input-prefix">₹</span>
                    <input type="number" step="0.01" name="variant_sale_prices[]" class="premium-input variant-price-input" value="${sale}" placeholder="0.00" required>
                </div>
            </div>

            <div class="variant-field-group variant-field-mrp">
                <label class="variant-field-label"><i class="fa-solid fa-tags"></i> MRP (Strikethrough)</label>
                <div class="input-prefix-wrapper">
                    <span class="input-prefix">₹</span>
                    <input type="number" step="0.01" name="variant_original_prices[]" class="premium-input" value="${orig}" placeholder="0.00">
                </div>
            </div>

            <div class="variant-field-group variant-field-stock">
                <label class="variant-field-label"><i class="fa-solid fa-cubes"></i> Stock Qty</label>
                <input type="number" name="variant_stocks[]" class="premium-input variant-stock-input" value="${stock}" min="0" placeholder="10">
            </div>

            ${window.skuEnabled !== false ? `
            <div class="variant-field-group variant-field-sku">
                <label class="variant-field-label"><i class="fa-solid fa-barcode"></i> SKU</label>
                <input type="text" name="variant_skus[]" class="premium-input" placeholder="e.g. SLV-PNK-01">
            </div>` : `<input type="hidden" name="variant_skus[]" value="">`}
        </div>
    `;

    setupVariantRowListeners(card, idx);
    container.appendChild(card);

    // Auto-focus the color input so user can start typing immediately
    var colorInput = card.querySelector('.color-name-input');
    if (colorInput && !cName) {
        colorInput.focus();
    }
}
function initRowSizeDropdown(row) {
    var wrap = row.querySelector('.row-size-multi-wrap');
    if (!wrap) return;

    var trigger = wrap.querySelector('.row-size-multi-trigger');
    var menu = wrap.querySelector('.row-size-multi-dropdown');
    var hiddenInput = wrap.querySelector('.variant-size-input');
    var displayText = wrap.querySelector('.row-size-text');
    var selectAllCb = wrap.querySelector('.row-select-all-cb');
    var clearBtn = wrap.querySelector('.row-clear-btn');
    var doneBtn = wrap.querySelector('.row-done-btn');
    var customInput = wrap.querySelector('.dd-custom-input');
    var customBtn = wrap.querySelector('.dd-custom-add-btn');
    var optList = wrap.querySelector('.row-size-dd-list');

    if (!trigger || !menu || !hiddenInput) return;

    // Synchronize checkboxes from initial hiddenInput value
    function syncFromInput() {
        var rawVal = hiddenInput.value.trim();
        var selectedSizes = rawVal ? rawVal.split(',').map(function(s) { return s.trim(); }).filter(Boolean) : [];
        
        var checkboxes = optList.querySelectorAll('input[type="checkbox"]');

        checkboxes.forEach(function(cb) {
            var isChecked = selectedSizes.some(function(sz) { return sz.toLowerCase() === cb.value.toLowerCase(); });
            cb.checked = isChecked;
            var lbl = cb.closest('.dd-opt-label');
            if (lbl) {
                if (isChecked) lbl.classList.add('checked');
                else lbl.classList.remove('checked');
            }
        });

        // Any custom values not in default checkboxes?
        selectedSizes.forEach(function(sz) {
            var found = Array.from(checkboxes).some(function(cb) { return cb.value.toLowerCase() === sz.toLowerCase(); });
            if (!found) {
                var newLbl = document.createElement('label');
                newLbl.className = 'dd-opt-label checked';
                newLbl.innerHTML = '<input type="checkbox" value="' + sz + '" checked> <span>' + sz + '</span>';
                optList.appendChild(newLbl);
            }
        });

        updateTriggerDisplay(selectedSizes);
    }

    function updateTriggerDisplay(selectedSizes) {
        if (!selectedSizes) {
            var rawVal = hiddenInput.value.trim();
            selectedSizes = rawVal ? rawVal.split(',').map(function(s) { return s.trim(); }).filter(Boolean) : [];
        }

        if (selectedSizes.length === 0) {
            displayText.textContent = 'Select Size(s)...';
            displayText.classList.add('placeholder');
        } else {
            displayText.textContent = selectedSizes.join(', ');
            displayText.classList.remove('placeholder');
        }

        // Update Select All state
        if (selectAllCb) {
            var allCb = optList.querySelectorAll('input[type="checkbox"]');
            var chkCb = optList.querySelectorAll('input[type="checkbox"]:checked');
            selectAllCb.checked = (allCb.length > 0 && allCb.length === chkCb.length);
            selectAllCb.indeterminate = (chkCb.length > 0 && chkCb.length < allCb.length);
        }

        // Trigger change event on hiddenInput for combined name update
        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function syncToInput() {
        var checked = optList.querySelectorAll('input[type="checkbox"]:checked');
        var selected = [];
        checked.forEach(function(cb) {
            selected.push(cb.value.trim());
        });
        hiddenInput.value = selected.join(', ');
        updateTriggerDisplay(selected);
    }

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        var isOpen = menu.classList.contains('show');
        closeAllRowSizeDropdowns();

        if (!isOpen) {
            menu.classList.add('show');
            trigger.classList.add('open');
            wrap.classList.add('open');
            var tr = wrap.closest('.variant-card, .variant-row');
            if (tr) tr.classList.add('has-open-dropdown');

            // Viewport collision check: open upwards if near bottom of window
            var rect = trigger.getBoundingClientRect();
            var spaceBelow = window.innerHeight - rect.bottom;
            if (spaceBelow < 300 && rect.top > 300) {
                menu.classList.add('dropup');
            } else {
                menu.classList.remove('dropup');
            }
        }
    });

    menu.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    optList.addEventListener('change', function(e) {
        if (e.target && e.target.type === 'checkbox') {
            var lbl = e.target.closest('.dd-opt-label');
            if (lbl) {
                if (e.target.checked) lbl.classList.add('checked');
                else lbl.classList.remove('checked');
            }
            syncToInput();
        }
    });

    if (selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            var checkState = selectAllCb.checked;
            optList.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
                cb.checked = checkState;
                var lbl = cb.closest('.dd-opt-label');
                if (lbl) {
                    if (checkState) lbl.classList.add('checked');
                    else lbl.classList.remove('checked');
                }
            });
            syncToInput();
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            optList.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
                cb.checked = false;
                var lbl = cb.closest('.dd-opt-label');
                if (lbl) lbl.classList.remove('checked');
            });
            syncToInput();
        });
    }

    if (doneBtn) {
        doneBtn.addEventListener('click', function() {
            closeAllRowSizeDropdowns();
        });
    }

    function addCustom() {
        if (!customInput) return;
        var val = customInput.value.trim();
        if (!val) return;

        var existing = Array.from(optList.querySelectorAll('input[type="checkbox"]')).find(function(cb) {
            return cb.value.toLowerCase() === val.toLowerCase();
        });

        if (existing) {
            existing.checked = true;
            var lbl = existing.closest('.dd-opt-label');
            if (lbl) lbl.classList.add('checked');
        } else {
            var newLbl = document.createElement('label');
            newLbl.className = 'dd-opt-label checked';
            newLbl.innerHTML = '<input type="checkbox" value="' + val + '" checked> <span>' + val + '</span>';
            // Insert at the very top of the list so new sizes are instantly visible
            if (optList.firstChild) {
                optList.insertBefore(newLbl, optList.firstChild);
            } else {
                optList.appendChild(newLbl);
            }
        }
        customInput.value = '';
        syncToInput();
    }

    if (customBtn) customBtn.addEventListener('click', addCustom);
    if (customInput) {
        customInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCustom();
            }
        });
    }

    hiddenInput.addEventListener('change', syncFromInput);

    // Initial sync
    syncFromInput();
}

function closeAllRowSizeDropdowns() {
    document.querySelectorAll('.row-size-multi-dropdown.show').forEach(function(m) {
        m.classList.remove('show');
        m.classList.remove('dropup');
        var w = m.closest('.row-size-multi-wrap');
        if (w) {
            w.classList.remove('open');
            var tr = w.closest('.variant-card, .variant-row');
            if (tr) tr.classList.remove('has-open-dropdown');
            var trg = w.querySelector('.row-size-multi-trigger');
            if (trg) trg.classList.remove('open');
        }
    });
}

// Global click handler to close any open row size dropdowns
document.addEventListener('click', function(e) {
    if (!e.target.closest('.row-size-multi-wrap')) {
        closeAllRowSizeDropdowns();
    }
});

function setupVariantRowListeners(row, idx) {
    var colorPicker = row.querySelector('.color-picker-input');
    var colorNameInput = row.querySelector('.color-name-input');
    var hexBadge = row.querySelector('.color-hex-badge');
    var sizeInput = row.querySelector('.variant-size-input');
    var combinedNameInput = row.querySelector('.variant-combined-name');
    var mediaBtn = row.querySelector('.btn-variant-media');
    var removeBtn = row.querySelector('.remove-variant-btn');

    // Initialize the row's multi-select size checkbox dropdown
    initRowSizeDropdown(row);

    // Sync combined name (Color / Size) & Card Header Preview
    function updateCombinedName() {
        var c = colorNameInput ? colorNameInput.value.trim() : '';
        var s = sizeInput ? sizeInput.value.trim() : '';
        var parts = [c, s].filter(Boolean);
        var combined = parts.length > 0 ? parts.join(' / ') : 'Variant';
        if (combinedNameInput) {
            combinedNameInput.value = combined;
        }

        var numBadge = row.querySelector('.variant-num-badge');
        var rowNum = numBadge ? numBadge.textContent.replace('#', '').trim() : (parseInt(idx) + 1);

        // Live update card header
        var titleEl = row.querySelector('.variant-header-title');
        var colorTag = row.querySelector('.tag-color-text');
        var sizeTag = row.querySelector('.tag-size-text');
        if (titleEl) {
            titleEl.textContent = 'Variant #' + rowNum + (c ? ': ' + c : '');
        }
        if (colorTag) colorTag.textContent = c || 'Pick Color';
        if (sizeTag) sizeTag.textContent = s || 'Select Size';
    }

    var stockInput = row.querySelector('.variant-stock-input');
    if (stockInput) {
        stockInput.addEventListener('input', function() {
            var stockTag = row.querySelector('.tag-stock-text');
            if (stockTag) stockTag.textContent = (this.value || '0') + ' in stock';
        });
    }

    if (colorPicker) {
        colorPicker.addEventListener('input', function() {
            var hexVal = this.value.toUpperCase();
            if (hexBadge) hexBadge.textContent = hexVal;
            this.closest('.color-picker-bubble').style.backgroundColor = hexVal;
            var headerSwatch = row.querySelector('.variant-header-swatch');
            if (headerSwatch) headerSwatch.style.backgroundColor = hexVal;
        });
    }

    function syncColorByName(name) {
        if (!name) return;
        var clean = name.trim().toLowerCase();
        var hex = null;

        // Check if name has a hex code embedded (e.g. "Blue (#2563EB)" or "#2563EB")
        var hexMatch = name.match(/#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})/);
        if (hexMatch) {
            var rawHex = hexMatch[1];
            hex = '#' + (rawHex.length === 3 ? rawHex.split('').map(function(ch){return ch+ch;}).join('') : rawHex);
        }

        // Clean name by stripping anything inside parentheses
        var cleanName = clean.replace(/\(.*?\)/g, '').trim();

        // 1. Check window.CONFIGURED_ATTRIBUTE_COLORS (handles both Array and Object)
        if (!hex && window.CONFIGURED_ATTRIBUTE_COLORS) {
            var colorList = Array.isArray(window.CONFIGURED_ATTRIBUTE_COLORS) 
                ? window.CONFIGURED_ATTRIBUTE_COLORS 
                : Object.values(window.CONFIGURED_ATTRIBUTE_COLORS);

            var match = colorList.find(function(c) {
                var cName = (c && c.name ? c.name : '').trim().toLowerCase();
                return cName === clean || cName === cleanName;
            });
            if (match && match.code) hex = match.code;
        }

        // 2. Check datalist options
        if (!hex) {
            var dl = document.getElementById('variant-color-options');
            if (dl) {
                var opt = Array.from(dl.options).find(function(o) {
                    var val = (o.value || '').trim().toLowerCase();
                    var txt = (o.text || '').trim().toLowerCase();
                    return val === clean || val === cleanName || txt === clean || txt === cleanName;
                });
                if (opt && opt.dataset && opt.dataset.hex) hex = opt.dataset.hex;
            }
        }

        // 3. Fallback standard color palette
        if (!hex) {
            var palette = {
                'red': '#dc2626', 'maroon': '#800020', 'pink': '#ec4899', 'blue': '#2563eb',
                'navy': '#1e3a8a', 'navy blue': '#1e3a8a', 'green': '#16a34a', 'teal': '#0d9488',
                'yellow': '#eab308', 'orange': '#ea580c', 'gold': '#d97706', 'golden': '#d97706',
                'purple': '#9333ea', 'lavender': '#c084fc', 'black': '#18181b', 'white': '#ffffff',
                'cream': '#fef08a', 'grey': '#64748b', 'gray': '#64748b', 'silver': '#94a3b8',
                'magenta': '#d946ef', 'cyan': '#06b6d4', 'brown': '#78350f', 'coffee': '#451a03',
                'olive': '#65a30d', 'rust': '#c2410c', 'coral': '#f43f5e', 'peach': '#fdba74',
                'pista': '#86efac', 'sky blue': '#0284c7', 'royal blue': '#1d4ed8'
            };
            if (palette[clean]) hex = palette[clean];
            else if (palette[cleanName]) hex = palette[cleanName];
        }

        if (hex) {
            if (!hex.startsWith('#')) hex = '#' + hex;
            hex = hex.toUpperCase();
            if (colorPicker) colorPicker.value = hex;
            if (hexBadge) hexBadge.textContent = hex;
            var bubble = colorPicker ? colorPicker.closest('.color-picker-bubble') : null;
            if (bubble) bubble.style.backgroundColor = hex;
            var headerSwatch = row.querySelector('.variant-header-swatch');
            if (headerSwatch) headerSwatch.style.backgroundColor = hex;
        }
    }

    if (colorNameInput) {
        var handleColorChange = function() {
            updateCombinedName();
            syncColorByName(this.value);
        };
        ['input', 'change', 'blur', 'keyup', 'paste'].forEach(function(evtName) {
            colorNameInput.addEventListener(evtName, handleColorChange);
        });

        // Run sync on init if value already present
        if (colorNameInput.value) {
            syncColorByName(colorNameInput.value);
        }
    }

    if (sizeInput) {
        sizeInput.addEventListener('input', updateCombinedName);
        sizeInput.addEventListener('change', updateCombinedName);
    }

    // Open Variant Media Modal
    if (mediaBtn) {
        mediaBtn.addEventListener('click', function() {
            openVariantMediaModal(idx, row);
        });
    }

    // Remove variant row freely without restriction
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            delete variantImagesMap[idx];
            row.remove();
            checkVariantsEmptyState();
        });
    }
}

/* ==========================================================================
   Quick Preset Generators (Dhoti, Shirts/Apparel, Kids, Saree)
   ========================================================================== */
function getActiveVariantInfo() {
    var basePrice = document.querySelector('input[name="price"]') ? document.querySelector('input[name="price"]').value : '';
    var baseOrigPrice = document.querySelector('input[name="original_price"]') ? document.querySelector('input[name="original_price"]').value : '';
    var baseStock = document.querySelector('input[name="stock_quantity"]') ? document.querySelector('input[name="stock_quantity"]').value : '10';

    var cName = '';
    var cHex = '#7a1530';
    var firstRow = document.querySelector('.variant-card, .variant-row');
    if (firstRow) {
        var cInput = firstRow.querySelector('.color-name-input');
        var hexInput = firstRow.querySelector('.color-picker-input');
        if (cInput && cInput.value) cName = cInput.value;
        if (hexInput && hexInput.value) cHex = hexInput.value;
    }
    return { cName: cName, cHex: cHex, sale: basePrice, orig: baseOrigPrice, stock: baseStock };
}

function addDhotiPresets() {
    quickSelectCategory('dhoti');
}

function addApparelPresets() {
    quickSelectCategory('apparel');
}

function addKidsPresets() {
    quickSelectCategory('kids');
}

function addSareePreset() {
    quickSelectCategory('saree');
}

window.addDhotiPresets = addDhotiPresets;
window.addApparelPresets = addApparelPresets;
window.addKidsPresets = addKidsPresets;
window.addSareePreset = addSareePreset;

/* ==========================================================================
   4B. Size / Dhoti Option Multi-Select Checkbox Dropdown Logic
   ========================================================================== */
function initSizeMultiSelect() {
    var container = document.getElementById('sizeMultiSelectContainer');
    if (!container) return;

    var triggerBtn = document.getElementById('btnSizeDropdownTrigger');
    var menu = document.getElementById('sizeDropdownMenu');
    var selectAllCb = document.getElementById('selectAllSizesCheckbox');
    var clearBtn = document.getElementById('btnClearSizeSelection');
    var searchInput = document.getElementById('sizeSearchInput');
    var filterTabs = container.querySelectorAll('.size-tab');
    var optionsList = document.getElementById('sizeOptionsList');
    var customInput = document.getElementById('customSizeInput');
    var addCustomBtn = document.getElementById('btnAddCustomSize');
    var applyBtn = document.getElementById('btnApplySelectedSizes');
    var countBadge = document.getElementById('sizeSelectedCountBadge');
    var summaryEl = document.getElementById('sizeSelectionSummary');

    if (!triggerBtn || !menu) return;

    // Toggle dropdown open/close
    triggerBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        var isOpen = menu.classList.contains('show');
        if (isOpen) {
            closeSizeDropdown();
        } else {
            openSizeDropdown();
        }
    });

    function openSizeDropdown() {
        menu.classList.add('show');
        triggerBtn.classList.add('open');
        triggerBtn.setAttribute('aria-expanded', 'true');
        if (searchInput) searchInput.focus();
    }

    function closeSizeDropdown() {
        menu.classList.remove('show');
        triggerBtn.classList.remove('open');
        triggerBtn.setAttribute('aria-expanded', 'false');
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            closeSizeDropdown();
        }
    });

    // Prevent clicks inside dropdown menu from closing it
    menu.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Update count badge & summary
    function updateSelectionStats() {
        if (!optionsList) return;
        var checkedBoxes = optionsList.querySelectorAll('.size-checkbox:checked');
        var count = checkedBoxes.length;

        if (countBadge) {
            countBadge.textContent = count + ' Selected';
            if (count > 0) {
                countBadge.classList.add('has-selected');
            } else {
                countBadge.classList.remove('has-selected');
            }
        }

        if (summaryEl) {
            if (count === 0) {
                summaryEl.textContent = 'No sizes selected';
            } else if (count === 1) {
                summaryEl.textContent = '1 size selected';
            } else {
                summaryEl.textContent = count + ' sizes selected';
            }
        }

        // Update Select All checkbox state
        var visibleCheckboxes = optionsList.querySelectorAll('.size-checkbox-item:not(.hidden) .size-checkbox');
        var visibleChecked = optionsList.querySelectorAll('.size-checkbox-item:not(.hidden) .size-checkbox:checked');
        if (selectAllCb) {
            if (visibleCheckboxes.length > 0 && visibleCheckboxes.length === visibleChecked.length) {
                selectAllCb.checked = true;
                selectAllCb.indeterminate = false;
            } else if (visibleChecked.length > 0) {
                selectAllCb.checked = false;
                selectAllCb.indeterminate = true;
            } else {
                selectAllCb.checked = false;
                selectAllCb.indeterminate = false;
            }
        }
    }

    // Individual checkbox click
    if (optionsList) {
        optionsList.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('size-checkbox')) {
                var item = e.target.closest('.size-checkbox-item');
                if (item) {
                    if (e.target.checked) {
                        item.classList.add('checked');
                    } else {
                        item.classList.remove('checked');
                    }
                }
                updateSelectionStats();
            }
        });
    }

    // "Select All" toggle
    if (selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            var checkState = selectAllCb.checked;
            var visibleItems = optionsList.querySelectorAll('.size-checkbox-item:not(.hidden)');
            visibleItems.forEach(function(item) {
                var cb = item.querySelector('.size-checkbox');
                if (cb) {
                    cb.checked = checkState;
                    if (checkState) {
                        item.classList.add('checked');
                    } else {
                        item.classList.remove('checked');
                    }
                }
            });
            updateSelectionStats();
        });
    }

    // Clear all selection
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            var allCheckboxes = optionsList.querySelectorAll('.size-checkbox');
            allCheckboxes.forEach(function(cb) {
                cb.checked = false;
                var item = cb.closest('.size-checkbox-item');
                if (item) item.classList.remove('checked');
            });
            updateSelectionStats();
        });
    }

    // Category Tabs filter
    filterTabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            filterTabs.forEach(function(t) { t.classList.remove('active'); });
            tab.classList.add('active');
            var cat = tab.getAttribute('data-category');

            var groups = optionsList.querySelectorAll('.size-option-group');
            groups.forEach(function(g) {
                if (cat === 'all' || g.getAttribute('data-group') === cat) {
                    g.classList.remove('hidden');
                } else {
                    g.classList.add('hidden');
                }
            });
            updateSelectionStats();
        });
    });

    // Realtime search / filter
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var q = searchInput.value.toLowerCase().trim();
            var items = optionsList.querySelectorAll('.size-checkbox-item');
            items.forEach(function(item) {
                var nameEl = item.querySelector('.size-name');
                var text = nameEl ? nameEl.textContent.toLowerCase() : '';
                if (!q || text.includes(q)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
            updateSelectionStats();
        });
    }

    // Add Custom Size
    function addCustomSize() {
        if (!customInput) return;
        var val = customInput.value.trim();
        if (!val) return;

        // Check if already in list
        var existing = Array.from(optionsList.querySelectorAll('.size-checkbox')).find(function(cb) {
            return cb.value.toLowerCase() === val.toLowerCase();
        });

        if (existing) {
            existing.checked = true;
            var p = existing.closest('.size-checkbox-item');
            if (p) p.classList.add('checked');
            updateSelectionStats();
            customInput.value = '';
            return;
        }

        // Create new item in first group
        var firstGroup = optionsList.querySelector('.size-option-group') || optionsList;
        var newItem = document.createElement('label');
        newItem.className = 'size-checkbox-item checked';
        newItem.innerHTML = `
            <input type="checkbox" class="size-checkbox" value="${val}" checked>
            <span class="checkmark"></span>
            <span class="size-name">${val}</span>
            <span class="size-tag">Custom</span>
        `;
        firstGroup.appendChild(newItem);
        customInput.value = '';
        updateSelectionStats();
    }

    if (addCustomBtn) {
        addCustomBtn.addEventListener('click', addCustomSize);
    }
    if (customInput) {
        customInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCustomSize();
            }
        });
    }

    // Apply / Add Selected Sizes to Variants Table
    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            var checkedBoxes = optionsList.querySelectorAll('.size-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select at least one Size or Dhoti Option from the checklist.');
                return;
            }

            var selectedSizes = [];
            checkedBoxes.forEach(function(cb) {
                selectedSizes.push(cb.value);
            });

            addSizesToVariantsTable(selectedSizes);
            closeSizeDropdown();
        });
    }

    // Expose update function
    window.updateSizeSelectionStats = updateSelectionStats;
}

function addSizesToVariantsTable(sizesArray) {
    if (!sizesArray || sizesArray.length === 0) return;

    var container = document.getElementById('variants-container');
    if (!container) return;

    var info = getActiveVariantInfo();
    var cColor = info.cName || 'Default Color';
    var cHex = info.cHex || '#7a1530';

    // Check if there is an empty variant row currently in the table
    var existingRows = container.querySelectorAll('.variant-card, .variant-row');
    var emptyRow = null;
    if (existingRows.length === 1) {
        var firstSize = existingRows[0].querySelector('.variant-size-input');
        var firstColor = existingRows[0].querySelector('.color-name-input');
        if ((!firstSize || !firstSize.value) && (!firstColor || !firstColor.value)) {
            emptyRow = existingRows[0];
        }
    }

    var addedCount = 0;
    sizesArray.forEach(function(sizeVal, idx) {
        if (idx === 0 && emptyRow) {
            // Populate the existing blank row
            var sInput = emptyRow.querySelector('.variant-size-input');
            var cInput = emptyRow.querySelector('.color-name-input');
            var hInput = emptyRow.querySelector('.color-picker-input');
            var hexBadge = emptyRow.querySelector('.color-hex-badge');
            var cBubble = emptyRow.querySelector('.color-picker-bubble');

            if (sInput) {
                sInput.value = sizeVal;
                sInput.dispatchEvent(new Event('change'));
            }
            if (cInput && !cInput.value) cInput.value = cColor;
            if (hInput && !hInput.value) hInput.value = cHex;
            if (hexBadge) hexBadge.textContent = cHex;
            if (cBubble) cBubble.style.backgroundColor = cHex;

            var comb = emptyRow.querySelector('.variant-combined-name');
            if (comb) comb.value = (cColor ? cColor + ' / ' : '') + sizeVal;
            addedCount++;
        } else {
            // Check if this size + color is already in the table
            var alreadyExists = false;
            existingRows.forEach(function(row) {
                var s = row.querySelector('.variant-size-input');
                var c = row.querySelector('.color-name-input');
                if (s && s.value.trim().toLowerCase() === sizeVal.toLowerCase() &&
                    c && c.value.trim().toLowerCase() === cColor.toLowerCase()) {
                    alreadyExists = true;
                }
            });

            if (!alreadyExists) {
                addVariantRow(cColor, cHex, sizeVal, info.sale, info.orig, info.stock);
                addedCount++;
            }
        }
    });

    // Show temporary feedback toast
    showVariantNotification('Added ' + addedCount + ' size variant' + (addedCount === 1 ? '' : 's') + ' to table!');
}

function quickSelectCategory(cat) {
    var container = document.getElementById('sizeMultiSelectContainer');
    if (!container) return;

    var checkboxes = container.querySelectorAll('.size-checkbox[data-group="' + cat + '"]');
    var selected = [];
    checkboxes.forEach(function(cb) {
        cb.checked = true;
        var p = cb.closest('.size-checkbox-item');
        if (p) p.classList.add('checked');
        selected.push(cb.value);
    });

    if (window.updateSizeSelectionStats) {
        window.updateSizeSelectionStats();
    }

    addSizesToVariantsTable(selected);
}
window.quickSelectCategory = quickSelectCategory;

function showVariantNotification(msg) {
    var existing = document.getElementById('variant-toast-notification');
    if (existing) existing.remove();

    var toast = document.createElement('div');
    toast.id = 'variant-toast-notification';
    toast.style.cssText = 'position: fixed; bottom: 24px; right: 24px; background: #064e3b; color: #ecfdf5; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 13.5px; box-shadow: 0 10px 25px rgba(0,0,0,0.25); z-index: 9999; display: flex; align-items: center; gap: 8px; animation: fadeIn 0.3s ease;';
    toast.innerHTML = '<i class="fa-solid fa-circle-check" style="color: #34d399; font-size: 16px;"></i> ' + msg;
    document.body.appendChild(toast);

    setTimeout(function() {
        toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(function() { toast.remove(); }, 400);
    }, 2800);
}


/* ==========================================================================
   5. Variant Media Modal / Drawer
   ========================================================================== */
var activeModalVariantIndex = null;
var activeModalVariantRow = null;

function initVariantMediaModal() {
    var modal = document.getElementById('variant-media-modal');
    var closeBtn = document.getElementById('btn-close-variant-modal');
    var cancelBtn = document.getElementById('btn-cancel-variant-modal');
    var saveBtn = document.getElementById('btn-save-variant-modal');
    var fileInput = document.getElementById('modal-variant-file-input');
    var dropzone = document.getElementById('modal-upload-dropzone');

    if (!modal) return;

    function closeModal() {
        modal.style.display = 'none';
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            updateVariantRowBadge(activeModalVariantIndex, activeModalVariantRow);
            closeModal();
        });
    }

    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            updateVariantRowBadge(activeModalVariantIndex, activeModalVariantRow);
            closeModal();
        }
    });

    // File selection inside modal
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (!fileInput.files || fileInput.files.length === 0) return;
            if (activeModalVariantIndex === null) return;

            if (!variantImagesMap[activeModalVariantIndex]) {
                variantImagesMap[activeModalVariantIndex] = [];
            }

            for (var i = 0; i < fileInput.files.length; i++) {
                var file = fileInput.files[i];
                if (file.type.match('image.*')) {
                    variantImagesMap[activeModalVariantIndex].push(file);
                }
            }
            renderModalVariantImages();
            fileInput.value = '';
        });
    }

    // Drag and drop in modal
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                dropzone.classList.add('drag-over');
            });
        });
        ['dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                dropzone.classList.remove('drag-over');
            });
        });
        dropzone.addEventListener('drop', function(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            if (files && files.length > 0 && activeModalVariantIndex !== null) {
                if (!variantImagesMap[activeModalVariantIndex]) {
                    variantImagesMap[activeModalVariantIndex] = [];
                }
                for (var i = 0; i < files.length; i++) {
                    if (files[i].type.match('image.*')) {
                        variantImagesMap[activeModalVariantIndex].push(files[i]);
                    }
                }
                renderModalVariantImages();
            }
        });
    }
}

function openVariantMediaModal(idx, row) {
    if (!row) {
        row = document.querySelector(`.variant-card[data-index="${idx}"], .variant-row[data-index="${idx}"]`);
    }
    activeModalVariantIndex = idx;
    activeModalVariantRow = row;

    var modal = document.getElementById('variant-media-modal');
    var titleEl = document.getElementById('modal-variant-name');
    var colorNameInput = row ? row.querySelector('.color-name-input') : null;

    var cVal = colorNameInput ? colorNameInput.value.trim() : '';
    var title = cVal || ('Variant #' + (parseInt(idx) + 1));

    if (titleEl) titleEl.textContent = title;

    // Update modal swatch dot color
    var swatchDot = document.getElementById('modal-swatch-dot');
    var colorPicker = row ? row.querySelector('.color-picker-input') : null;
    if (swatchDot && colorPicker) {
        swatchDot.style.backgroundColor = colorPicker.value || '#7a1530';
    }

    if (modal) {
        modal.classList.add('is-open');
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
    }

    renderModalVariantImages();
}
window.openVariantMediaModal = openVariantMediaModal;

// Global Delegated Click Listener for Variant Media Photos button
document.addEventListener('click', function(e) {
    var mediaBtn = e.target.closest('.btn-variant-media');
    if (mediaBtn) {
        e.preventDefault();
        e.stopPropagation();
        var card = mediaBtn.closest('.variant-card, .variant-row');
        var idx = mediaBtn.dataset.index !== undefined && mediaBtn.dataset.index !== '' 
            ? mediaBtn.dataset.index 
            : (card ? card.dataset.index : 0);
        openVariantMediaModal(idx, card);
    }
});

function renderModalVariantImages() {
    var grid = document.getElementById('modal-variant-images-grid');
    var counterEl = document.getElementById('modal-photo-counter');
    if (!grid) return;
    grid.innerHTML = '';

    var totalCount = 0;

    // 1. Existing server images (if any stored on row dataset)
    if (activeModalVariantRow && activeModalVariantRow.dataset.existingImages) {
        try {
            var existing = JSON.parse(activeModalVariantRow.dataset.existingImages);
            existing.forEach(function(imgData) {
                var card = document.createElement('div');
                card.className = 'variant-img-card';
                card.innerHTML = `
                    <img src="${imgData.url}" alt="Variant image">
                    <button type="button" class="variant-img-remove" title="Delete Image">
                        <i class="fa-solid fa-times"></i>
                    </button>
                `;
                card.querySelector('.variant-img-remove').addEventListener('click', function() {
                    if (confirm('Delete this saved variant image?')) {
                        var delInput = document.getElementById('deleted-media-ids');
                        if (delInput) {
                            var cur = delInput.value ? delInput.value.split(',') : [];
                            cur.push(imgData.id);
                            delInput.value = cur.join(',');
                        }
                        // Remove from dataset
                        var updated = existing.filter(function(x) { return x.id !== imgData.id; });
                        activeModalVariantRow.dataset.existingImages = JSON.stringify(updated);
                        card.remove();
                        updateVariantRowBadge(activeModalVariantIndex, activeModalVariantRow);
                    }
                });
                grid.appendChild(card);
            });
        } catch(e) {}
    }

    // 2. Newly selected client files
    var files = variantImagesMap[activeModalVariantIndex] || [];
    files.forEach(function(file, fIdx) {
        var card = document.createElement('div');
        card.className = 'variant-img-card';

        var img = document.createElement('img');
        var reader = new FileReader();
        reader.onload = function(evt) {
            img.src = evt.target.result;
        };
        reader.readAsDataURL(file);

        var delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.className = 'variant-img-remove';
        delBtn.innerHTML = '<i class="fa-solid fa-times"></i>';
        delBtn.title = 'Remove';
        delBtn.addEventListener('click', function() {
            files.splice(fIdx, 1);
            renderModalVariantImages();
            updateVariantRowBadge(activeModalVariantIndex, activeModalVariantRow);
        });

        card.appendChild(img);
        card.appendChild(delBtn);
        grid.appendChild(card);
    });

    var existingCount = 0;
    if (activeModalVariantRow && activeModalVariantRow.dataset.existingImages) {
        try { existingCount = JSON.parse(activeModalVariantRow.dataset.existingImages).length; } catch(e) {}
    }
    var totalPhotos = existingCount + (variantImagesMap[activeModalVariantIndex] || []).length;
    var counterEl = document.getElementById('modal-photo-counter');
    if (counterEl) {
        counterEl.textContent = totalPhotos + (totalPhotos === 1 ? ' photo' : ' photos');
    }

    if (totalPhotos === 0) {
        grid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 24px 10px; background: #fafaf9; border: 1.5px dashed #e2e8f0; border-radius: 10px; color: #94a3b8;">
                <i class="fa-regular fa-images" style="font-size: 28px; margin-bottom: 8px; display: block; color: #cbd5e1;"></i>
                <span style="font-size: 13px; font-weight: 600;">No photos uploaded for this color option yet</span>
            </div>
        `;
    }
}

function updateVariantRowBadge(idx, row) {
    if (!row) return;
    var badge = row.querySelector('.media-badge');
    if (!badge) return;

    var existingCount = 0;
    if (row.dataset.existingImages) {
        try {
            existingCount = JSON.parse(row.dataset.existingImages).length;
        } catch(e) {}
    }
    var clientCount = (variantImagesMap[idx] || []).length;
    var total = existingCount + clientCount;
    badge.textContent = total;
}

function appendVariantFilesToForm(form) {
    // Remove previous dynamic variant inputs
    form.querySelectorAll('.dynamic-variant-file-input').forEach(function(el) { el.remove(); });

    Object.keys(variantImagesMap).forEach(function(index) {
        var files = variantImagesMap[index];
        if (!files || files.length === 0) return;

        try {
            var dt = new DataTransfer();
            files.forEach(function(f) {
                dt.items.add(f);
            });
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'file';
            hiddenInput.name = 'variant_images_' + index + '[]';
            hiddenInput.multiple = true;
            hiddenInput.style.display = 'none';
            hiddenInput.className = 'dynamic-variant-file-input';
            hiddenInput.files = dt.files;
            form.appendChild(hiddenInput);
        } catch(e) {
            console.error('DataTransfer error:', e);
        }
    });
}

function insertDescFormat(textareaId, prefix, suffix) {
    var ta = document.getElementById(textareaId);
    if (!ta) return;
    var start = ta.selectionStart;
    var end = ta.selectionEnd;
    var selectedText = ta.value.substring(start, end);
    var replacement = prefix + (selectedText || 'Text') + (suffix || '');
    ta.value = ta.value.substring(0, start) + replacement + ta.value.substring(end);
    ta.focus();
    ta.setSelectionRange(start + prefix.length, start + prefix.length + (selectedText ? selectedText.length : 4));
}

function insertDescExample(textareaId) {
    var ta = document.getElementById(textareaId);
    if (!ta) return;
    var example = "**Special Discount Channel Offer on Trendy Designer Fancy Silk Saree**\n\n- Premium Quality Product\n- Fast Delivery Available\n- Blouse Attached (0.8 Meters)";
    if (ta.value.trim() !== '') {
        if (!confirm('Replace current description with the recommended template?')) return;
    }
    ta.value = example;
    ta.focus();
}

function handleShortsPreview(val) {
    var box = document.getElementById('youtube-shorts-preview-box');
    var iframe = document.getElementById('youtube-shorts-preview-iframe');
    if (!box || !iframe) return;
    
    val = (val || '').trim();
    if (!val) {
        iframe.src = '';
        box.style.display = 'none';
        return;
    }

    var videoId = '';
    var m1 = val.match(/shorts\/([a-zA-Z0-9_-]+)/);
    var m2 = val.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);
    var m3 = val.match(/[?&]v=([a-zA-Z0-9_-]+)/);
    if (m1) videoId = m1[1];
    else if (m2) videoId = m2[1];
    else if (m3) videoId = m3[1];

    if (videoId) {
        iframe.src = 'https://www.youtube.com/embed/' + videoId;
        box.style.display = 'block';
    } else {
        iframe.src = '';
        box.style.display = 'none';
    }
}

function extractInstagramEmbedUrl(val) {
    if (!val) return '';
    val = val.trim();
    var m = val.match(/(?:reel|reels|p|tv)\/([a-zA-Z0-9_-]+)/i);
    if (m && m[1]) {
        return 'https://www.instagram.com/reel/' + m[1] + '/embed/';
    }
    return '';
}

function extractFacebookEmbedUrl(val) {
    if (!val) return '';
    val = val.trim();
    if (val.indexOf('facebook.com') !== -1 || val.indexOf('fb.watch') !== -1 || val.indexOf('fb.com') !== -1) {
        return 'https://www.facebook.com/plugins/video.php?href=' + encodeURIComponent(val) + '&show_text=0&width=340';
    }
    return '';
}

function handleInstagramPreview(val) {
    var box = document.getElementById('instagram-preview-box');
    var iframe = document.getElementById('instagram-preview-iframe');
    var link = document.getElementById('instagram-preview-link');
    var badge = document.getElementById('instagram-preview-account-badge');
    var chSelect = document.getElementById('instagram_channel');
    var hint = document.getElementById('instagram-preview-hint');
    if (!box) return;

    val = (val || '').trim();
    if (!val) {
        if (iframe) iframe.src = '';
        box.style.display = 'none';
        if (badge) badge.style.display = 'none';
        if (hint) hint.style.display = 'none';
        return;
    }

    var embedUrl = extractInstagramEmbedUrl(val);
    if (embedUrl) {
        if (iframe && iframe.src !== embedUrl) {
            iframe.src = embedUrl;
        }
        if (link) link.href = val;

        if (badge) {
            var selectedCh = chSelect && chSelect.value ? chSelect.value : '';
            if (selectedCh) {
                badge.innerHTML = '<i class="fa-brands fa-instagram"></i> @' + selectedCh;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        }

        if (hint) hint.style.display = 'none';
        box.style.display = 'block';
    } else {
        if (iframe) iframe.src = '';
        if (val.indexOf('instagram.com') !== -1 || val.indexOf('instagr.am') !== -1) {
            if (hint) {
                hint.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> <strong>Invalid Instagram Link:</strong> Profile links cannot be embedded. Please enter a direct Reel or Post URL (e.g. <code>https://www.instagram.com/reel/DFXXXXX/</code>).';
                hint.style.display = 'block';
            }
        } else if (hint) {
            hint.style.display = 'none';
        }
        box.style.display = 'none';
        if (badge) badge.style.display = 'none';
    }
}

function handleFacebookPreview(val) {
    var box = document.getElementById('facebook-preview-box');
    var iframe = document.getElementById('facebook-preview-iframe');
    var link = document.getElementById('facebook-preview-link');
    var input = document.getElementById('admin_facebook_url');
    var hint = document.getElementById('facebook-preview-hint');
    if (!box) return;

    val = (val || '').trim();
    if (!val) {
        if (iframe) iframe.src = '';
        box.style.display = 'none';
        if (hint) hint.style.display = 'none';
        return;
    }

    // If user pasted a mobile /share/ link (e.g. facebook.com/share/r/1C3KaGw9zy/)
    if (val.indexOf('facebook.com/share/') !== -1) {
        if (hint) {
            hint.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <strong>Mobile Share Link Detected:</strong> Resolving to direct Reel URL...';
            hint.style.display = 'block';
            hint.style.color = '#1d4ed8';
            hint.style.background = '#eff6ff';
            hint.style.borderColor = '#bfdbfe';
        }

        var formData = new FormData();
        formData.append('url', val);

        var baseUrl = window.location.pathname.split('/admin/')[0] || '';
        fetch(baseUrl + '/admin/products/resolve-fb-url', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success && data.resolved && data.is_resolved) {
                if (input) input.value = data.resolved;
                val = data.resolved;
                if (hint) {
                    hint.innerHTML = '<i class="fa-solid fa-circle-check"></i> <strong>Auto-Converted!</strong> Mobile share link resolved to: <code>' + data.resolved + '</code>';
                    hint.style.color = '#15803d';
                    hint.style.background = '#f0fdf4';
                    hint.style.borderColor = '#bbf7d0';
                }
                var embedUrl = extractFacebookEmbedUrl(data.resolved);
                if (embedUrl) {
                    if (iframe) iframe.src = embedUrl;
                    if (link) link.href = data.resolved;
                    box.style.display = 'block';
                }
            } else {
                if (hint) {
                    hint.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> <strong>Notice:</strong> Please use the direct Reel URL from a computer browser (e.g. <code>https://www.facebook.com/reel/934075452461229/</code>).';
                    hint.style.color = '#b45309';
                    hint.style.background = '#fffbeb';
                    hint.style.borderColor = '#fde68a';
                }
            }
        })
        .catch(function() {
            // Fallback to direct embed attempt
        });
        return;
    }

    if (hint) hint.style.display = 'none';

    var embedUrl = extractFacebookEmbedUrl(val);
    if (embedUrl) {
        if (iframe && iframe.src !== embedUrl) {
            iframe.src = embedUrl;
        }
        if (link) link.href = val;
        box.style.display = 'block';
    } else {
        if (iframe) iframe.src = '';
        box.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var inp = document.getElementById('admin_shorts_url');
    if (inp && inp.value) {
        handleShortsPreview(inp.value);
    }
    var igInp = document.getElementById('admin_instagram_url');
    if (igInp && igInp.value) {
        handleInstagramPreview(igInp.value);
    }
    var igCh = document.getElementById('instagram_channel');
    if (igCh) {
        igCh.addEventListener('change', function() {
            var currVal = igInp ? igInp.value : '';
            if (currVal) handleInstagramPreview(currVal);
        });
    }
    var fbInp = document.getElementById('admin_facebook_url');
    if (fbInp && fbInp.value) {
        handleFacebookPreview(fbInp.value);
    }
});

function toggleBlouseAttached(enable) {
    var status = document.getElementById('blouse_attached_status');
    var inputBox = document.getElementById('blouse_input_box');
    var disabledBox = document.getElementById('blouse_disabled_box');
    var field = document.getElementById('blouse_length_field');
    var btnEnable = document.getElementById('btn_blouse_enable');
    var btnDisable = document.getElementById('btn_blouse_disable');

    if (enable) {
        if (status) status.value = '1';
        if (inputBox) inputBox.style.display = 'block';
        if (disabledBox) disabledBox.style.display = 'none';
        if (btnEnable) {
            btnEnable.style.background = '#16a34a';
            btnEnable.style.color = '#ffffff';
        }
        if (btnDisable) {
            btnDisable.style.background = 'transparent';
            btnDisable.style.color = '#64748b';
        }
        if (field) {
            field.removeAttribute('disabled');
            if (!field.value || field.value.trim() === '') {
                field.value = '0.8 Meters';
            }
        }
    } else {
        if (status) status.value = '0';
        if (inputBox) inputBox.style.display = 'none';
        if (disabledBox) disabledBox.style.display = 'block';
        if (btnDisable) {
            btnDisable.style.background = '#ef4444';
            btnDisable.style.color = '#ffffff';
        }
        if (btnEnable) {
            btnEnable.style.background = 'transparent';
            btnEnable.style.color = '#64748b';
        }
        if (field) {
            field.value = '';
            field.setAttribute('disabled', 'disabled');
        }
    }
}

function setBlousePreset(val) {
    var field = document.getElementById('blouse_length_field');
    if (field) {
        field.value = val;
    }
}

function handleBlouseDropdownChange(val) {
    var hiddenVal = document.getElementById('blouse_length_hidden');
    var status = document.getElementById('blouse_attached_status');
    var customInput = document.getElementById('blouse_custom_input');
    var hintText = document.getElementById('blouse_hint_text');

    if (val === 'none') {
        if (hiddenVal) hiddenVal.value = '';
        if (status) status.value = '0';
        if (customInput) customInput.style.display = 'none';
        if (hintText) {
            hintText.style.color = '#ef4444';
            hintText.innerHTML = '✗ Non-Attached (Without Blouse)';
        }
    } else if (val === 'custom') {
        if (status) status.value = '1';
        if (customInput) {
            customInput.style.display = 'block';
            customInput.focus();
            if (hiddenVal) hiddenVal.value = customInput.value;
        }
        if (hintText) {
            hintText.style.color = '#16a34a';
            hintText.innerHTML = '✓ Attached blouse active (Custom Meter)';
        }
    } else {
        if (hiddenVal) hiddenVal.value = val;
        if (status) status.value = '1';
        if (customInput) customInput.style.display = 'none';
        if (hintText) {
            hintText.style.color = '#16a34a';
            hintText.innerHTML = '✓ Attached (' + val + ') active';
        }
    }
}

/* ==========================================================================
   Basic Information: Live Pricing & Stock Logic
   ========================================================================== */
function initBasicInfoPricing() {
    calculatePriceSavings();
    var stockField = document.getElementById('stock_quantity_field') || document.querySelector('input[name="stock_quantity"]');
    if (stockField) {
        updateStockStatusPill(stockField.value);
    }
}

function calculatePriceSavings() {
    var priceField = document.getElementById('base_price_field') || document.querySelector('input[name="price"]');
    var origField = document.getElementById('original_price_field') || document.querySelector('input[name="original_price"]');
    var badge = document.getElementById('pricing-savings-badge');
    var text = document.getElementById('pricing-savings-text');

    if (!priceField || !origField || !badge || !text) return;

    var price = parseFloat(priceField.value);
    var orig = parseFloat(origField.value);

    if (!isNaN(price) && !isNaN(orig) && orig > price && price > 0) {
        var diff = orig - price;
        var pct = Math.round((diff / orig) * 100);
        text.textContent = pct + '% OFF (Save ₹' + diff.toLocaleString('en-IN', {maximumFractionDigits: 2}) + ')';
        badge.style.display = 'inline-flex';
    } else {
        badge.style.display = 'none';
    }
}

function updateStockStatusPill(val) {
    var pill = document.getElementById('stock-status-pill');
    if (!pill) return;
    var qty = parseInt(val, 10);
    pill.classList.remove('in-stock', 'low-stock', 'out-of-stock');
    if (isNaN(qty) || qty <= 0) {
        pill.textContent = 'Out of Stock';
        pill.classList.add('out-of-stock');
    } else if (qty <= 5) {
        pill.textContent = 'Low Stock (' + qty + ')';
        pill.classList.add('low-stock');
    } else {
        pill.textContent = 'In Stock (' + qty + ')';
        pill.classList.add('in-stock');
    }
}

function quickSetStock(qty) {
    var field = document.getElementById('stock_quantity_field') || document.querySelector('input[name="stock_quantity"]');
    if (field) {
        field.value = qty;
        updateStockStatusPill(qty);
        // Trigger input event for any listeners
        field.dispatchEvent(new Event('input'));
    }
}



/* ==========================================================================
   Product Form Section Tabs & Step Navigation Controller
   ========================================================================== */
var productSectionKeys = [
    "section-basic",
    "section-specs",
    "section-variants",
    "section-media",
    "section-display",
    "section-seo"
];

function getVisibleSectionKeys() {
    var keys = [];
    productSectionKeys.forEach(function(secId) {
        var el = document.getElementById(secId);
        var tabBtn = document.querySelector('.product-form-tab-btn[data-target="' + secId + '"]');
        // If tab button is hidden via display:none or element doesn't exist, skip it
        if (el && (!tabBtn || tabBtn.style.display !== 'none')) {
            keys.push(secId);
        }
    });
    return keys.length > 0 ? keys : productSectionKeys;
}

function switchProductSection(targetId, tabBtn) {
    productSectionKeys.forEach(function(secId) {
        var el = document.getElementById(secId);
        if (el) el.style.display = (secId === targetId) ? "block" : "none";
    });

    document.querySelectorAll(".product-form-tab-btn").forEach(function(btn) {
        btn.classList.remove("active");
    });
    if (tabBtn) {
        tabBtn.classList.add("active");
    } else {
        var matchingBtn = document.querySelector('.product-form-tab-btn[data-target="' + targetId + '"]');
        if (matchingBtn) matchingBtn.classList.add("active");
    }

    var tabsBar = document.querySelector(".product-form-tabs-bar");
    if (tabsBar) {
        tabsBar.scrollIntoView({ behavior: "smooth", block: "start" });
    }
}
window.switchProductSection = switchProductSection;

function nextProductSection(currentId) {
    var visibleKeys = getVisibleSectionKeys();
    var idx = visibleKeys.indexOf(currentId);
    if (idx !== -1 && idx < visibleKeys.length - 1) {
        var nextId = visibleKeys[idx + 1];
        switchProductSection(nextId);
    }
}
window.nextProductSection = nextProductSection;

function prevProductSection(currentId) {
    var visibleKeys = getVisibleSectionKeys();
    var idx = visibleKeys.indexOf(currentId);
    if (idx > 0) {
        var prevId = visibleKeys[idx - 1];
        switchProductSection(prevId);
    }
}
window.prevProductSection = prevProductSection;

document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector("form[action*='/admin/products']");
    if (!form) return;

    form.querySelectorAll("input, select, textarea").forEach(function(input) {
        input.addEventListener("invalid", function() {
            var pane = input.closest(".product-form-pane");
            if (pane && pane.id) {
                switchProductSection(pane.id);
            }
        });
    });
});
