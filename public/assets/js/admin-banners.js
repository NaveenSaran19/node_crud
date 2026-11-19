/**
 * Admin Banners Script
 * Handles drag-and-drop file upload, live image & video previews, position selectors, and form submission.
 */

document.addEventListener('DOMContentLoaded', function() {
    initBannerMediaUploader();
    initPositionSelector();
    initBannerForm();
});

/**
 * Banner Media (Image or Video) Drag-and-Drop & Live Preview
 */
function initBannerMediaUploader() {
    const dropzone = document.getElementById('bannerDropzone');
    const fileInput = document.getElementById('bannerMediaInput');
    const previewContainer = document.getElementById('bannerPreviewContainer');
    const previewMedia = document.getElementById('bannerPreviewMedia');
    const previewName = document.getElementById('bannerPreviewName');
    const previewSize = document.getElementById('bannerPreviewSize');
    const previewType = document.getElementById('bannerPreviewType');
    const removeBtn = document.getElementById('btnRemoveMedia');

    if (!fileInput || !dropzone) return;

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    // Highlight dropzone on drag
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, function() {
            dropzone.classList.add('drag-over');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, function() {
            dropzone.classList.remove('drag-over');
        }, false);
    });

    // Handle dropped files
    dropzone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // Handle standard file selection
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });

    // Handle media removal / reset
    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            resetUploader();
        });
    }

    function handleFileSelect(file) {
        if (!file) return;

        const isVideo = file.type.startsWith('video/') || file.name.match(/\.(mp4|webm|mov|mkv)$/i);
        const isImage = file.type.startsWith('image/') || file.name.match(/\.(jpg|jpeg|png|webp|gif|svg)$/i);

        if (!isVideo && !isImage) {
            alert('Please select an image (JPG, PNG, WEBP) or video (MP4, WEBM).');
            resetUploader();
            return;
        }

        // Format file size
        let sizeStr = '';
        if (file.size < 1024 * 1024) {
            sizeStr = (file.size / 1024).toFixed(1) + ' KB';
        } else {
            sizeStr = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        }

        previewName.textContent = file.name;
        previewSize.textContent = sizeStr;

        // Render preview depending on type
        if (isVideo) {
            const videoUrl = URL.createObjectURL(file);
            previewMedia.innerHTML = `<video src="${videoUrl}" controls class="banner-preview-video" style="max-height: 85px; width: 100%;"></video>`;
            previewType.textContent = 'VIDEO (' + (file.name.split('.').pop().toUpperCase() || 'MP4') + ')';
            previewType.className = 'banner-preview-type-pill type-video';
        } else {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewMedia.innerHTML = `<img src="${e.target.result}" alt="Preview" class="banner-preview-img">`;
            };
            reader.readAsDataURL(file);
            previewType.textContent = 'IMAGE';
            previewType.className = 'banner-preview-type-pill type-image';
        }

        if (previewContainer) {
            previewContainer.style.display = 'flex';
        }
    }

    function resetUploader() {
        fileInput.value = '';
        if (previewContainer) {
            previewContainer.style.display = 'none';
        }
        if (previewMedia) {
            previewMedia.innerHTML = '';
        }
    }
}

/**
 * Synchronize position select dropdown with visual position hint cards
 */
function initPositionSelector() {
    const positionSelect = document.getElementById('bannerPositionSelect');
    const posCards = document.querySelectorAll('.banner-pos-card');

    if (!positionSelect || !posCards.length) return;

    function syncActiveCard() {
        const selectedVal = positionSelect.value;
        posCards.forEach(card => {
            if (card.getAttribute('data-position') === selectedVal) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }

    // On select change
    positionSelect.addEventListener('change', syncActiveCard);

    // On card click
    posCards.forEach(card => {
        card.addEventListener('click', function() {
            const pos = this.getAttribute('data-position');
            positionSelect.value = pos;
            syncActiveCard();
        });
    });

    // Initial sync
    syncActiveCard();
}

/**
 * Handle form submission state
 */
function initBannerForm() {
    const form = document.getElementById('bannerForm');
    const submitBtn = document.getElementById('btnSubmitBanner');

    if (!form || !submitBtn) return;

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Saving...`;
    });
}
