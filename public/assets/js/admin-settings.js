/**
 * Admin Settings Script
 * Handles tab navigation, bulk category upload, trust card row additions/deletion, and preview updates.
 */

function showSeoPage(pageKey) {
    document.querySelectorAll('.seo-page-config').forEach(function (el) {
        el.style.display = 'none';
    });
    var target = document.getElementById('seo-config-' + pageKey);
    if (target) {
        target.style.display = 'block';
    }
}

function switchBannerPageConfig(pageKey) {
    document.querySelectorAll('.banner-page-panel').forEach(function (el) {
        el.style.display = 'none';
    });
    var target = document.getElementById('banner-config-' + pageKey);
    if (target) {
        target.style.display = 'block';
    }
}

var bannerUploadDataTransfers = {};

function previewNewBannerUploads(input, previewContainerId) {
    var container = document.getElementById(previewContainerId);
    if (!container) return;

    if (!bannerUploadDataTransfers[previewContainerId]) {
        bannerUploadDataTransfers[previewContainerId] = new DataTransfer();
    }
    var dt = bannerUploadDataTransfers[previewContainerId];

    // If new files were chosen via file dialog or drop
    if (input.files && input.files.length > 0) {
        var existingSignatures = new Set();
        for (var i = 0; i < dt.files.length; i++) {
            existingSignatures.add(dt.files[i].name + '_' + dt.files[i].size);
        }

        Array.from(input.files).forEach(function (file) {
            if (!file.type.startsWith('image/')) return;
            var sig = file.name + '_' + file.size;
            if (!existingSignatures.has(sig)) {
                dt.items.add(file);
                existingSignatures.add(sig);
            }
        });

        // Sync all accumulated files back to input so form submits all of them
        input.files = dt.files;
    }

    renderBannerStagedPreviews(input, previewContainerId);
}

function renderBannerStagedPreviews(input, previewContainerId) {
    var container = document.getElementById(previewContainerId);
    if (!container) return;
    container.innerHTML = '';

    var dt = bannerUploadDataTransfers[previewContainerId];
    if (!dt || dt.files.length === 0) return;

    Array.from(dt.files).forEach(function (file, index) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var previewBox = document.createElement('div');
            previewBox.className = 'banner-staged-box';
            previewBox.style.cssText = 'position: relative; width: 120px; height: 80px; border-radius: 10px; overflow: hidden; border: 2px dashed #7a1530; box-shadow: 0 2px 6px rgba(0,0,0,0.08); background: #fff;';
            previewBox.innerHTML = '<img src="' + e.target.result + '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" alt="Preview">' +
                '<div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 4px 6px; z-index: 2;">' +
                    '<div style="color: #fff; font-size: 10px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' + file.name + '</div>' +
                    '<div style="color: #cbd5e1; font-size: 9px;">' + (file.size / 1024).toFixed(0) + ' KB • New #' + (index + 1) + '</div>' +
                '</div>' +
                '<button type="button" title="Remove this new banner" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.95); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.3); z-index: 3;">' +
                    '<i class="fa-solid fa-xmark"></i>' +
                '</button>';

            previewBox.querySelector('button').onclick = function () {
                removeStagedBannerFile(input, previewContainerId, index);
            };

            container.appendChild(previewBox);
        };
        reader.readAsDataURL(file);
    });
}

function removeStagedBannerFile(input, previewContainerId, fileIndex) {
    var dt = bannerUploadDataTransfers[previewContainerId];
    if (!dt) return;

    var newDt = new DataTransfer();
    for (var i = 0; i < dt.files.length; i++) {
        if (i !== fileIndex) {
            newDt.items.add(dt.files[i]);
        }
    }
    bannerUploadDataTransfers[previewContainerId] = newDt;
    input.files = newDt.files;

    renderBannerStagedPreviews(input, previewContainerId);
}

function showTab(tabName, clickedEl) {
    document.querySelectorAll('.settings-section').forEach(function (el) {
        el.classList.remove('active');
    });
    document.querySelectorAll('.settings-tab').forEach(function (el) {
        el.classList.remove('active');
    });

    var targetSection = document.getElementById('tab-' + tabName);
    if (targetSection) {
        targetSection.classList.add('active');
    }

    if (clickedEl) {
        clickedEl.classList.add('active');
    } else {
        var matchingTab = document.querySelector('.settings-tab[data-tab="' + tabName + '"]');
        if (matchingTab) {
            matchingTab.classList.add('active');
        }
    }

    var redirectInput = document.getElementById('active_tab_redirect');
    if (redirectInput) {
        redirectInput.value = tabName;
    }

    if (window.history && window.history.replaceState) {
        window.history.replaceState(null, null, '#' + tabName);
    }

    if (tabName === 'homepage') {
        var homeSelect = document.getElementById('homepage-section-select');
        var homeVal = homeSelect ? homeSelect.value : 'homepage_trust';
        if (typeof switchHomepageSection === 'function') {
            switchHomepageSection(homeVal);
        }
    } else if (tabName === 'display') {
        var dispSelect = document.getElementById('header-display-section-select');
        var dispVal = dispSelect ? dispSelect.value : 'announcement_bar';
        if (typeof switchHeaderDisplaySection === 'function') {
            switchHeaderDisplaySection(dispVal);
        }
    }

    if (typeof window.syncSidebarActiveState === 'function') {
        window.syncSidebarActiveState();
    }
}

function switchHeaderDisplaySection(sectionKey) {
    var selectEl = document.getElementById('header-display-section-select');
    if (selectEl && selectEl.value !== sectionKey) {
        selectEl.value = sectionKey;
    }

    // Update pill buttons
    document.querySelectorAll('.display-section-pill').forEach(function(pill) {
        if (pill.getAttribute('data-section') === sectionKey) {
            pill.classList.add('active');
        } else {
            pill.classList.remove('active');
        }
    });

    var cardAnnouncement = document.getElementById('card-announcement-bar');
    var cardBanners = document.getElementById('card-page-banners');
    var cardCategories = document.getElementById('card-header-categories');

    if (sectionKey === 'all') {
        if (cardAnnouncement) cardAnnouncement.style.display = 'block';
        if (cardBanners) cardBanners.style.display = 'block';
        if (cardCategories) cardCategories.style.display = 'block';
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#display');
        }
    } else if (sectionKey === 'announcement_bar') {
        if (cardAnnouncement) cardAnnouncement.style.display = 'block';
        if (cardBanners) cardBanners.style.display = 'none';
        if (cardCategories) cardCategories.style.display = 'none';
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#announcement_bar');
        }
        if (cardAnnouncement) {
            cardAnnouncement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    } else if (sectionKey === 'page_banners') {
        if (cardAnnouncement) cardAnnouncement.style.display = 'none';
        if (cardBanners) cardBanners.style.display = 'block';
        if (cardCategories) cardCategories.style.display = 'none';
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#page_banners');
        }
        if (cardBanners) {
            cardBanners.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    } else if (sectionKey === 'header_categories') {
        if (cardAnnouncement) cardAnnouncement.style.display = 'none';
        if (cardBanners) cardBanners.style.display = 'none';
        if (cardCategories) cardCategories.style.display = 'block';
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#header_categories');
        }
        if (cardCategories) {
            cardCategories.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

function switchHomepageSection(sectionKey) {
    var selectEl = document.getElementById('homepage-section-select');
    if (selectEl && selectEl.value !== sectionKey) {
        selectEl.value = sectionKey;
    }

    // Update pill buttons
    document.querySelectorAll('.homepage-section-pill').forEach(function(pill) {
        if (pill.getAttribute('data-section') === sectionKey) {
            pill.classList.add('active');
        } else {
            pill.classList.remove('active');
        }
    });

    var cards = {
        homepage_trust: document.getElementById('card-home-trust'),
        homepage_live: document.getElementById('card-home-live'),
        homepage_hero: document.getElementById('card-home-hero'),
        homepage_budget: document.getElementById('card-home-budget'),
        homepage_diwali: document.getElementById('card-home-diwali'),
        homepage_shorts: [document.getElementById('card-home-shorts'), document.getElementById('settings-channel-tabs')],
        homepage_why: document.getElementById('card-home-why'),
        homepage_must_visit: document.getElementById('card-home-must-visit'),
        homepage_about: document.getElementById('card-home-about')
    };

    var showElement = function(el) {
        if (!el) return;
        if (Array.isArray(el)) {
            el.forEach(function(item) { if (item) item.style.display = 'block'; });
        } else {
            el.style.display = 'block';
        }
    };

    var hideElement = function(el) {
        if (!el) return;
        if (Array.isArray(el)) {
            el.forEach(function(item) { if (item) item.style.display = 'none'; });
        } else {
            el.style.display = 'none';
        }
    };

    if (sectionKey === 'all') {
        Object.keys(cards).forEach(function(key) {
            showElement(cards[key]);
        });
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#homepage');
        }
    } else {
        Object.keys(cards).forEach(function(key) {
            if (key === sectionKey) {
                showElement(cards[key]);
            } else {
                hideElement(cards[key]);
            }
        });

        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, '#' + sectionKey);
        }

        var activeTarget = cards[sectionKey];
        var scrollTarget = Array.isArray(activeTarget) ? activeTarget[0] : activeTarget;
        if (scrollTarget) {
            scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

function updateHeaderCategoryCount() {
    var tbody = document.getElementById('header-categories-container');
    var badge = document.getElementById('header-cat-count-badge');
    if (!tbody) return;
    var rows = tbody.querySelectorAll('tr');
    if (badge) {
        badge.textContent = rows.length + (rows.length === 1 ? ' Category' : ' Categories');
    }
    rows.forEach(function (tr, idx) {
        var indexBadge = tr.querySelector('.cat-row-index');
        if (indexBadge) {
            indexBadge.textContent = idx + 1;
        }
    });
}

function previewCatRowIcon(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var previewBox = input.previousElementSibling;
        var reader = new FileReader();
        reader.onload = function(e) {
            if (previewBox) {
                previewBox.innerHTML = '<img src="' + e.target.result + '" alt="Icon" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">';
            }
        };
        reader.readAsDataURL(file);
    }
}
window.previewCatRowIcon = previewCatRowIcon;

function createHeaderCategoryRow(catName, catImage) {
    var tbody = document.getElementById('header-categories-container');
    var count = tbody ? tbody.querySelectorAll('tr').length : 0;
    catName = catName || '';
    catImage = catImage || '';

    var tr = document.createElement('tr');
    tr.className = 'header-cat-row';
    tr.innerHTML = `
        <td style="text-align: center; vertical-align: middle;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                <i class="fa-solid fa-grip-vertical cat-row-handle" title="Category Position" style="color: #94a3b8; cursor: grab;"></i>
                <span class="cat-row-index" style="font-weight: 700; font-size: 13px; color: #7a1530; background: #fff5f7; width: 28px; height: 28px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">${count + 1}</span>
            </div>
        </td>
        <td style="text-align: center; vertical-align: middle; padding: 8px 10px;">
            <div class="cat-icon-uploader-cell" style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                <div class="cat-icon-preview-box" onclick="this.nextElementSibling.click()" style="width: 44px; height: 44px; border-radius: 8px; border: 1.5px dashed #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden;" title="Click to upload/change icon">
                    ${catImage ? `<img src="${catImage}" alt="Icon" style="width: 100%; height: 100%; object-fit: cover;">` : `<i class="fa-solid fa-image" style="color: #94a3b8; font-size: 18px;"></i>`}
                </div>
                <input type="file" name="header_categories_image[]" accept="image/*" style="display: none;" onchange="previewCatRowIcon(this)">
                <span style="font-size: 10px; color: #64748b; font-weight: 600; cursor: pointer;" onclick="this.previousElementSibling.click()">Upload</span>
            </div>
        </td>
        <td style="vertical-align: middle; padding: 10px 16px;">
            <div style="position: relative; width: 100%;">
                <input type="text" name="header_categories_name[]" class="premium-input header-cat-text-input" list="store-categories-list" value="${catName.replace(/"/g, '&quot;')}" placeholder="Enter category name (e.g. Silk Sarees, Cotton, Pudaivai...)" style="width: 100%; font-size: 14px; font-weight: 600; padding-left: 38px;">
                <i class="fa-solid fa-tag" style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #0d9488; font-size: 14px; pointer-events: none;"></i>
            </div>
            <input type="hidden" name="header_categories_original_name[]" value="${catName.replace(/"/g, '&quot;')}">
            <input type="hidden" name="header_categories_old_image[]" class="cat-old-image-input" value="${catImage.replace(/"/g, '&quot;')}">
        </td>
        <td style="text-align: center; vertical-align: middle;">
            <button type="button" class="btn-danger-icon remove-header-cat-btn" title="Remove Category">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;

    tr.querySelector('.remove-header-cat-btn').addEventListener('click', function() {
        tr.remove();
        updateHeaderCategoryCount();
    });

    return tr;
}

function quickAddSelectedCategory() {
    var quickSelect = document.getElementById('quick-add-category-select');
    if (!quickSelect) return;
    var catName = quickSelect.value.trim();
    if (!catName) {
        alert('Please select a store category from the dropdown first.');
        quickSelect.focus();
        return;
    }

    var tbody = document.getElementById('header-categories-container');
    if (!tbody) return;

    var newRow = createHeaderCategoryRow(catName);
    tbody.appendChild(newRow);
    updateHeaderCategoryCount();
    quickSelect.value = '';

    // Gentle flash highlight
    newRow.style.backgroundColor = '#ecfdf5';
    setTimeout(function() {
        newRow.style.transition = 'background-color 0.8s ease';
        newRow.style.backgroundColor = '';
    }, 800);
}

function handleBulkCategoryUpload(input) {
    var files = input.files;
    var tbody = document.getElementById('header-categories-container');
    if (!tbody || !files) return;

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var catName = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, " ");

        // Title case the category name
        catName = catName.replace(
            /\w\S*/g,
            function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            }
        );

        var newRow = createHeaderCategoryRow(catName);
        tbody.appendChild(newRow);
    }

    input.value = '';
    updateHeaderCategoryCount();
}

function updateTrustIconPreview(input) {
    var row = input.closest('.trust-card-admin-row');
    if (row) {
        var preview = row.querySelector('.trust-icon-preview');
        if (preview) {
            var iconClass = input.value.trim() || 'fa-solid fa-star';
            preview.innerHTML = '<i class="' + iconClass + '"></i>';
        }
    }
}

function renumberTrustCards() {
    var rows = document.querySelectorAll('.trust-card-admin-row');
    rows.forEach(function (row, i) {
        var numEl = row.querySelector('.card-num');
        if (numEl) numEl.textContent = i + 1;

        var iconInput = row.querySelector('input[name*="[icon]"]');
        if (iconInput) iconInput.name = 'trust_cards[' + i + '][icon]';

        var titleInput = row.querySelector('input[name*="[title]"]');
        if (titleInput) titleInput.name = 'trust_cards[' + i + '][title]';

        var orderInput = row.querySelector('input[name*="[order]"]');
        if (orderInput) orderInput.name = 'trust_cards[' + i + '][order]';

        var descInput = row.querySelector('textarea[name*="[desc]"]');
        if (descInput) descInput.name = 'trust_cards[' + i + '][desc]';

        var visibleInput = row.querySelector('input[name*="[visible]"]');
        if (visibleInput) visibleInput.name = 'trust_cards[' + i + '][visible]';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    function getValidTab(tabCandidate) {
        if (!document.querySelector('.settings-tab')) {
            return tabCandidate || 'modules';
        }
        var tabEl = document.querySelector('.settings-tab[data-tab="' + tabCandidate + '"]');
        if (tabEl) return tabCandidate;
        var firstTabEl = document.querySelector('.settings-tab');
        return firstTabEl ? firstTabEl.getAttribute('data-tab') : tabCandidate;
    }

    // Auto-select initial tab based on server parameter or URL hash
    var serverTab = document.getElementById('active_tab_redirect') ? document.getElementById('active_tab_redirect').value : 'business';
    var defaultTab = serverTab || 'business';
    var homepageSections = ['homepage_trust', 'homepage_live', 'homepage_hero', 'homepage_budget', 'homepage_diwali', 'homepage_shorts', 'homepage_why', 'homepage_must_visit', 'homepage_about', 'settings-channel-tabs'];

    if (window.location.hash) {
        var hashTab = window.location.hash.substring(1);
        if (document.getElementById('tab-' + hashTab)) {
            defaultTab = hashTab;
        } else if (hashTab === 'announcement_bar' || hashTab === 'page_banners' || hashTab === 'header_categories') {
            defaultTab = 'display';
        } else if (homepageSections.indexOf(hashTab) !== -1) {
            defaultTab = 'homepage';
        }
    }
    showTab(getValidTab(defaultTab));
    if (window.location.hash) {
        var sectionCandidate = window.location.hash.substring(1);
        if (sectionCandidate === 'announcement_bar' || sectionCandidate === 'page_banners' || sectionCandidate === 'header_categories') {
            switchHeaderDisplaySection(sectionCandidate);
        } else if (homepageSections.indexOf(sectionCandidate) !== -1) {
            var targetHomeKey = (sectionCandidate === 'settings-channel-tabs') ? 'homepage_shorts' : sectionCandidate;
            switchHomepageSection(targetHomeKey);
        }
    }

    window.addEventListener('hashchange', function () {
        var hashTab = window.location.hash.substring(1);
        if (hashTab && document.getElementById('tab-' + hashTab)) {
            showTab(getValidTab(hashTab));
        } else if (hashTab === 'announcement_bar' || hashTab === 'page_banners' || hashTab === 'header_categories') {
            showTab('display');
            switchHeaderDisplaySection(hashTab);
        } else if (homepageSections.indexOf(hashTab) !== -1) {
            showTab('homepage');
            var targetHomeKey = (hashTab === 'settings-channel-tabs') ? 'homepage_shorts' : hashTab;
            switchHomepageSection(targetHomeKey);
        }
    });

    // Header categories add button
    var addHeaderCatBtn = document.getElementById('add-header-cat-btn');
    if (addHeaderCatBtn) {
        addHeaderCatBtn.addEventListener('click', function () {
            var tbody = document.getElementById('header-categories-container');
            if (!tbody) return;
            var newRow = createHeaderCategoryRow('');
            tbody.appendChild(newRow);
            updateHeaderCategoryCount();
            var textInput = newRow.querySelector('.header-cat-text-input');
            if (textInput) {
                textInput.focus();
            }
        });
    }

    // Existing header category remove buttons
    document.querySelectorAll('.remove-header-cat-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            this.closest('tr').remove();
            updateHeaderCategoryCount();
        });
    });

    updateHeaderCategoryCount();

    // Add Trust Card button
    var addTrustBtn = document.getElementById('add-trust-card-btn');
    if (addTrustBtn) {
        addTrustBtn.addEventListener('click', function () {
            var container = document.getElementById('trust-cards-container');
            if (!container) return;
            var currentRows = container.querySelectorAll('.trust-card-admin-row').length;
            var newIndex = currentRows;

            var newCard = document.createElement('div');
            newCard.className = 'trust-card-admin-row';
            newCard.style.cssText = 'background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; position: relative;';
            newCard.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-weight: 700; color: #7a1530; font-size: 13px;"><i class="fa-solid fa-layer-group"></i> Card #<span class="card-num">${newIndex + 1}</span></span>
                        <div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 10px;">
                            <label style="font-size: 12px; color: #64748b; font-weight: 600;">Visible:</label>
                            <input type="checkbox" name="trust_cards[${newIndex}][visible]" value="1" checked style="accent-color: #7a1530; cursor: pointer;">
                        </div>
                    </div>
                    <button type="button" class="btn-danger-icon remove-trust-card-btn" title="Remove Card">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 2fr 100px; gap: 12px; margin-bottom: 12px;">
                    <div>
                        <label class="form-label" style="font-size: 11px;">Font Awesome Icon</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" name="trust_cards[${newIndex}][icon]" class="premium-input trust-icon-input" value="fa-solid fa-certificate" placeholder="e.g. fa-solid fa-landmark" style="padding: 10px 12px; font-size: 13px;">
                            <span class="trust-icon-preview" style="width: 38px; height: 38px; border-radius: 8px; background: #fff; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; color: #7a1530; font-size: 16px; flex-shrink: 0;">
                                <i class="fa-solid fa-certificate"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 11px;">Heading / Title</label>
                        <input type="text" name="trust_cards[${newIndex}][title]" class="premium-input" placeholder="e.g. Card Title" style="padding: 10px 12px; font-size: 13px;" required>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 11px;">Order</label>
                        <input type="number" name="trust_cards[${newIndex}][order]" class="premium-input" value="${newIndex + 1}" min="1" max="99" style="padding: 10px 12px; font-size: 13px; text-align: center;">
                    </div>
                </div>
                <div>
                    <label class="form-label" style="font-size: 11px;">Description</label>
                    <textarea name="trust_cards[${newIndex}][desc]" class="premium-input" rows="2" placeholder="Short description text..." style="padding: 10px 12px; font-size: 13px;"></textarea>
                </div>
            `;

            newCard.querySelector('.remove-trust-card-btn').addEventListener('click', function () {
                newCard.remove();
                renumberTrustCards();
            });

            var iconInp = newCard.querySelector('.trust-icon-input');
            iconInp.addEventListener('input', function () {
                updateTrustIconPreview(this);
            });

            container.appendChild(newCard);
        });
    }

    // Attach listeners to existing trust card icon inputs
    document.querySelectorAll('.trust-icon-input').forEach(function (input) {
        input.addEventListener('input', function () {
            updateTrustIconPreview(this);
        });
    });

    // Attach listeners to existing trust card delete buttons
    document.querySelectorAll('.remove-trust-card-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            this.closest('.trust-card-admin-row').remove();
            renumberTrustCards();
        });
    });

    // Initialize announcement bar preview on load
    updateAnnouncementPreview();
});

function syncColorInput(pickerEl, targetInputId) {
    var textInput = document.getElementById(targetInputId);
    if (textInput) {
        textInput.value = pickerEl.value;
    }
}

function syncColorPicker(inputEl, targetPickerId) {
    var picker = document.getElementById(targetPickerId);
    var val = inputEl.value.trim();
    if (picker && /^#[0-9A-Fa-f]{6}$/.test(val)) {
        picker.value = val;
    }
}

function formatMarqueeWithGoldenStars(text) {
    if (!text || !text.trim()) {
        return '<span><i class="fa-solid fa-star" style="color: #ffd700; margin-right: 6px;"></i> Free Shipping above Rs.1000/- Purchases.</span>';
    }
    var raw = text.trim();
    var parts = raw.split(/(\s{3,}|\s*•\s*|\s*\|\s*)/).filter(function (p) {
        var trimmed = p.trim();
        return trimmed.length > 0 && trimmed !== '•' && trimmed !== '|';
    });
    if (parts.length === 0) parts = [raw];
    var div = document.createElement('div');
    var htmlItems = parts.map(function (part) {
        div.textContent = part.trim();
        var safePart = div.innerHTML;
        return '<i class="fa-solid fa-star" style="color: #ffd700; margin: 0 8px; font-size: 11px;"></i> ' + safePart;
    });
    return htmlItems.join(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ') + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' + htmlItems.join(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ');
}

function insertOfferPreset(phrase) {
    var textArea = document.getElementById('announcement-bar-text-input');
    if (!textArea) return;
    var current = textArea.value.trim();
    if (current.length > 0) {
        if (phrase === ' • ') {
            textArea.value = current + ' • ';
        } else {
            textArea.value = current + '       ' + phrase;
        }
    } else {
        textArea.value = phrase;
    }
    updateAnnouncementPreview();
    textArea.focus();
}

function setAnnouncementColor(colorVal, targetType) {
    if (targetType === 'text') {
        var picker = document.getElementById('announcement-text-color-picker');
        var input = document.getElementById('announcement-text-color-input');
        if (picker) picker.value = colorVal;
        if (input) input.value = colorVal;
    } else if (targetType === 'bg') {
        var picker = document.getElementById('announcement-bg-color-picker');
        var input = document.getElementById('announcement-bg-color-input');
        if (picker) picker.value = colorVal;
        if (input) input.value = colorVal;
    }
    updateAnnouncementPreview();
}

function togglePreviewMarqueePause() {
    var track = document.getElementById('announcement-live-preview-text');
    var btn = document.getElementById('btn-pause-marquee-preview');
    if (!track) return;
    track.classList.toggle('paused');
    if (btn) {
        if (track.classList.contains('paused')) {
            btn.innerHTML = '<i class="fa-solid fa-play"></i> Play Marquee';
        } else {
            btn.innerHTML = '<i class="fa-solid fa-pause"></i> Pause Marquee';
        }
    }
}

function switchBannerPageTab(pageKey, btnEl) {
    document.querySelectorAll('.page-switcher-btn').forEach(function (b) {
        b.classList.remove('active');
    });
    if (btnEl) {
        btnEl.classList.add('active');
    }
    var selector = document.getElementById('banner-page-selector');
    if (selector) {
        selector.value = pageKey;
    }
    switchBannerPageConfig(pageKey);
}

function updateAnnouncementPreview() {
    var previewBox = document.getElementById('announcement-live-preview-box');
    var previewText = document.getElementById('announcement-live-preview-text');
    var textArea = document.getElementById('announcement-bar-text-input');
    var boldToggle = document.getElementById('announcement-bold-toggle');
    var fontSizeSelect = document.getElementById('announcement-font-size-select');
    var textColorInput = document.getElementById('announcement-text-color-input');
    var bgColorInput = document.getElementById('announcement-bg-color-input');
    var enabledToggle = document.getElementById('announcement-bar-enabled-toggle');
    var statusBadge = document.getElementById('announcement-status-badge');

    if (enabledToggle && statusBadge) {
        if (enabledToggle.checked) {
            statusBadge.className = 'badge-live-status active';
            statusBadge.innerHTML = '<i class="fa-solid fa-circle-dot"></i> Live on Storefront';
        } else {
            statusBadge.className = 'badge-live-status disabled';
            statusBadge.innerHTML = '<i class="fa-solid fa-eye-slash"></i> Disabled / Hidden';
        }
    }

    if (!previewBox) return;

    var textVal = textArea ? textArea.value : '';
    if (previewText) {
        previewText.innerHTML = formatMarqueeWithGoldenStars(textVal);
    }

    var isBold = boldToggle ? boldToggle.checked : true;
    previewBox.style.fontWeight = isBold ? '700' : '500';

    if (fontSizeSelect) {
        previewBox.style.fontSize = fontSizeSelect.value;
    }

    if (textColorInput && textColorInput.value) {
        previewBox.style.color = textColorInput.value;
    }

    if (bgColorInput && bgColorInput.value) {
        previewBox.style.backgroundColor = bgColorInput.value;
    }
}

function renumberBudgetTiers() {
    var rows = document.querySelectorAll('.budget-tier-admin-row');
    rows.forEach(function (row, i) {
        var numEl = row.querySelector('.tier-num');
        if (numEl) numEl.textContent = i + 1;

        var badgeInput = row.querySelector('input[name*="[badge]"]');
        if (badgeInput) badgeInput.name = 'budget_tiers[' + i + '][badge]';

        var priceInput = row.querySelector('input[name*="[price]"]');
        if (priceInput) priceInput.name = 'budget_tiers[' + i + '][price]';

        var titleInput = row.querySelector('input[name*="[title]"]');
        if (titleInput) titleInput.name = 'budget_tiers[' + i + '][title]';

        var linkInput = row.querySelector('input[name*="[link]"]');
        if (linkInput) linkInput.name = 'budget_tiers[' + i + '][link]';

        var visibleInput = row.querySelector('input[name*="[visible]"]');
        if (visibleInput) visibleInput.name = 'budget_tiers[' + i + '][visible]';
    });
}

function addNewBudgetTier() {
    var container = document.getElementById('budget-tiers-container');
    if (!container) return;

    var count = container.querySelectorAll('.budget-tier-admin-row').length;
    var row = document.createElement('div');
    row.className = 'budget-tier-admin-row';
    row.style.cssText = 'background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; position: relative; margin-top: 10px;';
    row.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-weight: 700; color: #1a237e; font-size: 13px;"><i class="fa-solid fa-wallet"></i> Tier #<span class="tier-num">${count + 1}</span></span>
                <div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 10px;">
                    <label style="font-size: 12px; color: #64748b; font-weight: 600;">Visible:</label>
                    <input type="checkbox" name="budget_tiers[${count}][visible]" value="1" checked style="accent-color: #1a237e; cursor: pointer;">
                </div>
            </div>
            <button type="button" class="btn-danger-icon" onclick="this.closest('.budget-tier-admin-row').remove(); renumberBudgetTiers();" title="Remove Tier">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
        <div style="display: grid; grid-template-columns: 140px 180px 1fr 1fr; gap: 14px;">
            <div>
                <label class="form-label" style="font-size: 11px;">Diamond Badge</label>
                <input type="text" name="budget_tiers[${count}][badge]" class="premium-input" value="BETWEEN" placeholder="e.g. BELOW, BETWEEN, ABOVE" style="padding: 9px 12px; font-size: 13px; font-weight: 700; text-transform: uppercase;" required>
            </div>
            <div>
                <label class="form-label" style="font-size: 11px;">Price Ribbon Text</label>
                <input type="text" name="budget_tiers[${count}][price]" class="premium-input" value="Rs.500 to 1000/-" placeholder="e.g. Rs.500/-" style="padding: 9px 12px; font-size: 13px; font-weight: 700;" required>
            </div>
            <div>
                <label class="form-label" style="font-size: 11px;">Collection Title</label>
                <input type="text" name="budget_tiers[${count}][title]" class="premium-input" value="Collection Title" placeholder="e.g. Below Rs 499/- Collection" style="padding: 9px 12px; font-size: 13px;" required>
            </div>
            <div>
                <label class="form-label" style="font-size: 11px;">Link URL</label>
                <input type="text" name="budget_tiers[${count}][link]" class="premium-input" value="collections" placeholder="e.g. collections?max_price=499" style="padding: 9px 12px; font-size: 13px;">
            </div>
        </div>
    `;
    container.appendChild(row);
    renumberBudgetTiers();
}

function renumberShortsChannels() {
    var rows = document.querySelectorAll('.shorts-channel-admin-row');
    rows.forEach(function (row, i) {
        var numEl = row.querySelector('.ch-num');
        if (numEl) numEl.textContent = i + 1;

        var nameInput = row.querySelector('input[name*="[name]"]');
        if (nameInput) nameInput.name = 'shorts_channels[' + i + '][name]';

        var targetSelect = row.querySelector('select[name*="[target]"]');
        if (targetSelect) targetSelect.name = 'shorts_channels[' + i + '][target]';

        var linkInput = row.querySelector('input[name*="[link]"]');
        if (linkInput) linkInput.name = 'shorts_channels[' + i + '][link]';

        var visibleInput = row.querySelector('input[name*="[visible]"]');
        if (visibleInput) visibleInput.name = 'shorts_channels[' + i + '][visible]';
    });
}

function addNewShortsChannel() {
    var container = document.getElementById('shorts-channels-container');
    if (!container) return;

    var count = container.querySelectorAll('.shorts-channel-admin-row').length;
    var firstRowSelect = container.querySelector('select');
    var selectHtml = firstRowSelect ? firstRowSelect.innerHTML : '<option value="">Select Category</option>';

    var row = document.createElement('div');
    row.className = 'shorts-channel-admin-row';
    row.style.cssText = 'background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; position: relative; margin-top: 10px;';
    row.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-weight: 700; color: #dc2626; font-size: 13px;"><i class="fa-brands fa-youtube"></i> Channel #<span class="ch-num">${count + 1}</span></span>
                <div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 10px;">
                    <label style="font-size: 12px; color: #64748b; font-weight: 600;">Visible:</label>
                    <input type="checkbox" name="shorts_channels[${count}][visible]" value="1" checked style="accent-color: #dc2626; cursor: pointer;">
                </div>
            </div>
            <button type="button" class="btn-danger-icon" onclick="this.closest('.shorts-channel-admin-row').remove(); renumberShortsChannels();" title="Remove Channel">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
        <div style="display: grid; grid-template-columns: 1.5fr 2fr 1.5fr; gap: 14px;">
            <div>
                <label class="form-label" style="font-size: 11px;">Channel Tab Name</label>
                <input type="text" name="shorts_channels[${count}][name]" class="premium-input" value="NEW CHANNEL" placeholder="e.g. OFFER CHANNEL" style="padding: 9px 12px; font-size: 13px; font-weight: 700;" required>
            </div>
            <div>
                <label class="form-label" style="font-size: 11px;">Display Products from Category / Subcategory</label>
                <select name="shorts_channels[${count}][target]" class="premium-input" style="padding: 9px 12px; font-size: 13px; cursor: pointer; background: #fff;">
                    ${selectHtml}
                </select>
            </div>
            <div>
                <label class="form-label" style="font-size: 11px;">Custom Link / URL (optional)</label>
                <input type="text" name="shorts_channels[${count}][link]" class="premium-input" value="" placeholder="e.g. category/sarees or YT URL" style="padding: 9px 12px; font-size: 13px;">
            </div>
        </div>
    `;
    container.appendChild(row);
    renumberShortsChannels();
}

/* ==========================================================================
   Payment Gateway (Razorpay) Settings Interactions
   ========================================================================== */
function switchRzpEnv(env) {
    var testPanel = document.getElementById('rzp-panel-test');
    var livePanel = document.getElementById('rzp-panel-live');
    if (env === 'live') {
        if (testPanel) testPanel.style.display = 'none';
        if (livePanel) livePanel.style.display = 'block';
    } else {
        if (testPanel) testPanel.style.display = 'block';
        if (livePanel) livePanel.style.display = 'none';
    }
}

function updateRzpStatusBadge(checkbox) {
    var badge = document.getElementById('razorpay-status-badge');
    if (!badge) return;
    if (checkbox.checked) {
        badge.className = 'badge-live-status active';
        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Gateway Active';
    } else {
        badge.className = 'badge-live-status disabled';
        badge.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Gateway Disabled';
    }
}

function togglePasswordVisibility(inputId, btn) {
    var input = document.getElementById(inputId);
    if (!input) return;
    var icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.className = 'fa-solid fa-eye-slash';
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.className = 'fa-solid fa-eye';
        }
    }
}

function testRazorpayConnection() {
    var btn = document.getElementById('btn-test-razorpay');
    var icon = document.getElementById('test-rzp-icon');
    var btnText = document.getElementById('test-rzp-btn-text');
    var resultText = document.getElementById('rzp-test-result-text');

    var activeEnv = document.querySelector('input[name="razorpay_env"]:checked');
    var envVal = activeEnv ? activeEnv.value : 'test';

    var keyId = envVal === 'live' ? 
        (document.getElementById('rzp_live_key_id') ? document.getElementById('rzp_live_key_id').value : '') :
        (document.getElementById('rzp_test_key_id') ? document.getElementById('rzp_test_key_id').value : '');

    var keySecret = envVal === 'live' ? 
        (document.getElementById('rzp_live_key_secret') ? document.getElementById('rzp_live_key_secret').value : '') :
        (document.getElementById('rzp_test_key_secret') ? document.getElementById('rzp_test_key_secret').value : '');

    if (btn) btn.disabled = true;
    if (icon) icon.className = 'fa-solid fa-spinner fa-spin';
    if (btnText) btnText.textContent = 'Verifying...';
    if (resultText) {
        resultText.innerHTML = '<span style="color: #64748b;"><i class="fa-solid fa-spinner fa-spin"></i> Contacting Razorpay API for authentication check...</span>';
    }

    var formData = new FormData();
    formData.append('env', envVal);
    formData.append('key_id', keyId);
    formData.append('key_secret', keySecret);

    fetch(window.location.origin + '/sri/public/admin/settings/test-razorpay', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(function(res) {
        return res.json();
    })
    .then(function(data) {
        if (btn) btn.disabled = false;
        if (icon) icon.className = 'fa-solid fa-bolt';
        if (btnText) btnText.textContent = 'Test Connection';

        if (resultText) {
            if (data.success) {
                resultText.innerHTML = '<span style="color: #16a34a; font-weight: 700;"><i class="fa-solid fa-circle-check"></i> ' + data.message + '</span>';
            } else {
                resultText.innerHTML = '<span style="color: #dc2626; font-weight: 700;"><i class="fa-solid fa-circle-exclamation"></i> ' + data.message + '</span>';
            }
        }
    })
    .catch(function(err) {
        if (btn) btn.disabled = false;
        if (icon) icon.className = 'fa-solid fa-bolt';
        if (btnText) btnText.textContent = 'Test Connection';
        if (resultText) {
            resultText.innerHTML = '<span style="color: #dc2626; font-weight: 700;"><i class="fa-solid fa-triangle-exclamation"></i> Request failed: ' + err.message + '</span>';
        }
    });
}

// Live preview function for Diwali Section multiple image uploads
window.previewDiwaliFiles = function(input, containerId) {
    var container = document.getElementById(containerId);
    if (!container) return;
    var thumbsGrid = container.querySelector('.diwali-new-thumbs');
    if (!thumbsGrid) return;
    
    thumbsGrid.innerHTML = '';
    if (!input.files || input.files.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    Array.from(input.files).forEach(function(file, index) {
        if (!file.type.startsWith('image/')) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var item = document.createElement('div');
            item.style.position = 'relative';
            item.style.aspectRatio = '3/4';
            item.style.borderRadius = '6px';
            item.style.overflow = 'hidden';
            item.style.border = '1.5px solid #38bdf8';
            item.style.boxShadow = '0 1px 4px rgba(0,0,0,0.1)';
            item.style.background = '#fff';
            item.title = file.name;
            item.innerHTML = `
                <img src="${e.target.result}" alt="New Preview" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                <span style="position: absolute; bottom: 2px; left: 2px; font-size: 8px; background: rgba(2,132,199,0.9); color: #fff; padding: 1px 3px; border-radius: 2px; font-weight: 700;">NEW ${index + 1}</span>
            `;
            thumbsGrid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
};

// Security & Password Form AJAX submission in Settings tab
document.addEventListener('DOMContentLoaded', function() {
    var secForm = document.getElementById('adminSettingsSecurityForm');
    if (!secForm) return;

    var alertBox = document.getElementById('securityTabAlert');
    var btnSave = document.getElementById('btnSaveSecurityTab');
    var btnText = document.getElementById('btnSaveSecurityTabText');

    function showSecAlert(msg, type) {
        if (!alertBox) return;
        alertBox.style.display = 'flex';
        alertBox.style.background = type === 'success' ? '#f0fdf4' : '#fef2f2';
        alertBox.style.color = type === 'success' ? '#166534' : '#991b1b';
        alertBox.style.border = '1px solid ' + (type === 'success' ? '#bbf7d0' : '#fecaca');
        var icon = alertBox.querySelector('.alert-icon');
        if (icon) {
            icon.className = 'alert-icon fa-solid ' + (type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation');
            icon.style.color = type === 'success' ? '#16a34a' : '#dc2626';
        }
        var text = alertBox.querySelector('.alert-text');
        if (text) text.textContent = msg;
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    secForm.addEventListener('submit', function(e) {
        e.preventDefault();

        var curPassEl = document.getElementById('tab_current_password');
        var newPassEl = document.getElementById('tab_new_password');
        var confPassEl = document.getElementById('tab_confirm_password');

        var curPass = curPassEl ? curPassEl.value : '';
        var newPass = newPassEl ? newPassEl.value : '';
        var confPass = confPassEl ? confPassEl.value : '';

        if (newPass !== '' || curPass !== '' || confPass !== '') {
            if (!curPass) {
                showSecAlert('Please enter your current (old) password to authorize the change.', 'error');
                if (curPassEl) curPassEl.focus();
                return;
            }
            if (!newPass) {
                showSecAlert('Please enter your new password.', 'error');
                if (newPassEl) newPassEl.focus();
                return;
            }
            if (newPass.length < 6) {
                showSecAlert('New password must be at least 6 characters long.', 'error');
                if (newPassEl) newPassEl.focus();
                return;
            }
            if (newPass !== confPass) {
                showSecAlert('New password and confirm password do not match.', 'error');
                if (confPassEl) confPassEl.focus();
                return;
            }
        }

        if (btnSave) btnSave.disabled = true;
        if (btnText) btnText.textContent = 'Saving...';

        var formData = new FormData(secForm);

        fetch(secForm.getAttribute('action'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(res) {
            return res.json();
        })
        .then(function(data) {
            // Update CSRF token on all forms
            if (data.csrf_hash || data.token) {
                var tokenVal = data.csrf_hash || data.token;
                document.querySelectorAll('input[type="hidden"]').forEach(function(inp) {
                    if (inp.name && inp.name.toLowerCase().indexOf('csrf') !== -1) {
                        inp.value = tokenVal;
                    }
                });
            }

            if (data.success) {
                showSecAlert(data.message || 'Administrator password and profile updated successfully!', 'success');

                var nameVal = document.getElementById('tab_admin_name') ? document.getElementById('tab_admin_name').value.trim() : '';
                if (nameVal) {
                    var initials = nameVal.substring(0, 2).toUpperCase();
                    var topName = document.getElementById('topbarAdminName');
                    if (topName) topName.textContent = nameVal;
                    var sideName = document.getElementById('sidebarAdminName');
                    if (sideName) sideName.textContent = nameVal;
                    var modalBadgeName = document.getElementById('modalBadgeName');
                    if (modalBadgeName) modalBadgeName.textContent = nameVal;

                    var topAv = document.getElementById('topbarAvatar');
                    if (topAv) topAv.textContent = initials;
                    var sideAv = document.getElementById('sidebarAvatar');
                    if (sideAv) sideAv.textContent = initials;
                }

                // Clear password fields
                if (curPassEl) curPassEl.value = '';
                if (newPassEl) newPassEl.value = '';
                if (confPassEl) confPassEl.value = '';
            } else {
                showSecAlert(data.message || 'Failed to update credentials.', 'error');
            }
        })
        .catch(function(err) {
            console.error(err);
            showSecAlert('A server error occurred. Please try again.', 'error');
        })
        .finally(function() {
            if (btnSave) btnSave.disabled = false;
            if (btnText) btnText.textContent = 'Save Credentials & Password';
        });
    });
});

/* ==========================================================================
   Live Real-Time Previews for Business Logo & Favicon
   ========================================================================== */
function previewBusinessLogo(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file (PNG, JPG, SVG, WebP)');
            input.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            var simImg = document.getElementById('simLogoImg');
            if (simImg) {
                simImg.src = e.target.result;
            }
            var infoBox = document.getElementById('logoFileSelectedInfo');
            var nameEl = document.getElementById('logoSelectedFileName');
            var badge = document.getElementById('logoStatusBadge');
            if (infoBox && nameEl) {
                nameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                infoBox.style.display = 'flex';
            }
            if (badge) {
                badge.textContent = 'New Logo Selected (Unsaved)';
                badge.className = 'branding-badge active-new';
            }
        };
        reader.readAsDataURL(file);
    }
}

function clearBusinessLogoSelection() {
    var input = document.getElementById('businessLogoInput');
    if (input) input.value = '';
    var infoBox = document.getElementById('logoFileSelectedInfo');
    if (infoBox) infoBox.style.display = 'none';
    var simImg = document.getElementById('simLogoImg');
    if (simImg) {
        simImg.src = simImg.getAttribute('data-original-src') || '';
    }
    var badge = document.getElementById('logoStatusBadge');
    if (badge) {
        badge.textContent = 'Current Saved Logo';
        badge.className = 'branding-badge';
    }
}

function previewFavicon(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var tabFavicon = document.getElementById('browserTabFavicon');
            if (tabFavicon) {
                tabFavicon.src = e.target.result;
            }
            var infoBox = document.getElementById('faviconFileSelectedInfo');
            var nameEl = document.getElementById('faviconSelectedFileName');
            var badge = document.getElementById('faviconStatusBadge');
            if (infoBox && nameEl) {
                nameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                infoBox.style.display = 'flex';
            }
            if (badge) {
                badge.textContent = 'New Favicon Selected (Unsaved)';
                badge.className = 'branding-badge active-new';
            }
        };
        reader.readAsDataURL(file);
    }
}

function clearFaviconSelection() {
    var input = document.getElementById('faviconInput');
    if (input) input.value = '';
    var infoBox = document.getElementById('faviconFileSelectedInfo');
    if (infoBox) infoBox.style.display = 'none';
    var tabFavicon = document.getElementById('browserTabFavicon');
    if (tabFavicon) {
        tabFavicon.src = tabFavicon.getAttribute('data-original-src') || '';
    }
    var badge = document.getElementById('faviconStatusBadge');
    if (badge) {
        badge.textContent = 'Current Saved Favicon';
        badge.className = 'branding-badge';
    }
}
function addShowroomLocationRow() {
    var container = document.getElementById('showroom-locations-container');
    if (!container) return;
    var index = container.querySelectorAll('.showroom-location-card').length;
    var card = document.createElement('div');
    card.className = 'showroom-location-card';
    card.style.cssText = 'background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 14px; position: relative; box-shadow: 0 2px 6px rgba(0,0,0,0.02); animation: fadeIn 0.25s ease;';
    card.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="location-badge" style="background: #0284c7; color: #fff; font-size: 10.5px; font-weight: 800; padding: 2px 8px; border-radius: 6px;">Location #${index + 1}</span>
                <input type="text" name="about_store_locations[${index}][name]" value="Showroom Branch #${index + 1}" class="premium-input" style="font-weight: 700; font-size: 13px; padding: 4px 10px; width: 220px;" placeholder="Location Name (e.g. Salem City Branch)">
            </div>
            <button type="button" onclick="this.closest('.showroom-location-card').remove(); reindexShowroomLocations();" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 8px; font-size: 11px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-solid fa-trash-can"></i> Delete
            </button>
        </div>
        <div style="margin-bottom: 10px;">
            <label class="form-label" style="font-size: 11.5px; margin-bottom: 3px;"><i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> Physical Address</label>
            <textarea name="about_store_locations[${index}][address]" class="premium-input" rows="2" style="width: 100%; font-size: 12.5px;" placeholder="Full address with town and pincode"></textarea>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
            <div>
                <label class="form-label" style="font-size: 11.5px; margin-bottom: 3px;"><i class="fa-regular fa-clock" style="color: #0d9488;"></i> Working Hours / Timings</label>
                <input type="text" name="about_store_locations[${index}][timings]" value="Monday - Sunday: 9:00 AM - 9:00 PM" class="premium-input" style="font-size: 12.5px;">
            </div>
            <div>
                <label class="form-label" style="font-size: 11.5px; margin-bottom: 3px;"><i class="fa-solid fa-map-location-dot" style="color: #0284c7;"></i> Google Maps URL (Directions)</label>
                <input type="text" name="about_store_locations[${index}][map_link]" value="https://maps.google.com" class="premium-input" style="font-size: 12.5px;" placeholder="https://maps.google.com/...">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
            <div>
                <label class="form-label" style="font-size: 11.5px; margin-bottom: 3px;"><i class="fa-solid fa-phone" style="color: #10b981;"></i> Customer Care / Phone</label>
                <input type="text" name="about_store_locations[${index}][phone]" value="9789296751" class="premium-input" style="font-size: 12.5px;">
            </div>
            <div>
                <label class="form-label" style="font-size: 11.5px; margin-bottom: 3px;"><i class="fa-brands fa-whatsapp" style="color: #25D366;"></i> WhatsApp Inquiries Number</label>
                <input type="text" name="about_store_locations[${index}][whatsapp]" value="9789296751" class="premium-input" style="font-size: 12.5px;">
            </div>
        </div>
        <div>
            <label class="form-label" style="font-size: 11.5px; margin-bottom: 3px;"><i class="fa-solid fa-map-pin" style="color: #7a1530;"></i> Google Maps Location Embed (Iframe or Embed URL)</label>
            <textarea name="about_store_locations[${index}][map_embed]" class="premium-input" rows="2" style="width: 100%; font-size: 12px; font-family: monospace;" placeholder="Paste Google Maps <iframe> code or embed link (e.g. https://www.google.com/maps/embed?pb=...)"></textarea>
            <small style="color: #64748b; font-size: 11px; display: block; margin-top: 2px;">Embeds an interactive Google Map on the About page and Homepage for this branch. If left blank, an interactive map is auto-generated from the physical address.</small>
        </div>
    `;
    container.appendChild(card);
}

function reindexShowroomLocations() {
    var container = document.getElementById('showroom-locations-container');
    if (!container) return;
    var cards = container.querySelectorAll('.showroom-location-card');
    cards.forEach(function(card, idx) {
        var badge = card.querySelector('.location-badge');
        if (badge) {
            badge.textContent = idx === 0 ? 'Location #1 (Flagship)' : 'Location #' + (idx + 1);
            badge.style.background = idx === 0 ? '#7a1530' : '#0284c7';
        }
        card.querySelectorAll('input, textarea').forEach(function(el) {
            var name = el.getAttribute('name');
            if (name && name.startsWith('about_store_locations[')) {
                el.setAttribute('name', name.replace(/about_store_locations\[\d+\]/, 'about_store_locations[' + idx + ']'));
            }
        });
    });
}
