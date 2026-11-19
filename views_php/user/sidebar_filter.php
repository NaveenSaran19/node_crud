<?php
/**
 * Sidebar Filter Component (Availability, Price Slider, Color Selection)
 * Included in collections, new_arrivals, best_sellers, and category listing views.
 */
$filterSettingsModel = new \App\Models\SettingsModel();
$dbAttrColors = $filterSettingsModel->where('key', 'attribute_colors')->first();
$filterColorsList = (!empty($dbAttrColors['value'])) ? json_decode($dbAttrColors['value'], true) : [];
$dbAttrSizes = $filterSettingsModel->where('key', 'attribute_sizes')->first();
$filterSizesList = (!empty($dbAttrSizes['value'])) ? json_decode($dbAttrSizes['value'], true) : [];

$maxFilterPrice = isset($maxPrice) ? (int)$maxPrice : 3000;
if ($maxFilterPrice < 1000) $maxFilterPrice = 3000;
$minFilterPrice = 0;
$showFilters = isset($showFilters) ? (bool)$showFilters : true;
?>
<aside class="catalog-filter-sidebar" id="catalog-filter-sidebar" style="width: 260px; min-width: 240px; flex-shrink: 0; padding-right: 25px; border-right: 1.5px solid #eaeaea;">
    
    <!-- Mobile Filter Section Toggle Header (Only displays on mobile) -->
    <div class="mobile-filter-drawer-toggle" onclick="toggleMobileFilterSidebar(this)" style="display: none; align-items: center; justify-content: space-between; padding: 12px 16px; background: #fffdfa; border: 1.5px solid #d4af37; border-radius: 10px; cursor: pointer; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(225, 29, 72,0.06);">
        <span style="font-weight: 700; color: #e11d48; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-sliders" style="color: #d4af37;"></i> Filter Products (In Stock, Price, Color)
        </span>
        <i class="fa-solid fa-chevron-down mobile-filter-chevron" style="color: #e11d48; font-size: 13px; transition: transform 0.25s ease;"></i>
    </div>

    <div class="filter-sections-inner-wrapper" id="filter-sections-inner-wrapper">
    <?php if ($showFilters): ?>
    <!-- Active Filters Reset Header (shown when any filter is active) -->
    <div id="filter-active-summary" style="display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 10px 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
        <span style="font-size: 13px; font-weight: 700; color: #1e293b;"><i class="fa-solid fa-sliders" style="margin-right: 6px; color: #e11d48;"></i> Filtered</span>
        <button type="button" onclick="resetAllCatalogFilters()" style="background: none; border: none; color: #e11d48; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: underline; padding: 0;">Clear all</button>
    </div>

    <!-- 1. Availability Section -->
    <div class="filter-accordion-section" style="border-bottom: 1.5px solid #f1f5f9; padding-bottom: 22px; margin-bottom: 22px;">
        <div class="filter-accordion-header" onclick="toggleCatalogFilter('filter-availability-content', this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
            <span style="font-size: 15px; font-weight: 700; color: #1e293b; letter-spacing: 0.2px;">Availability</span>
            <i class="fa-solid fa-chevron-up filter-accordion-chevron" style="font-size: 12px; color: #475569; transition: transform 0.25s ease;"></i>
        </div>
        <div id="filter-availability-content" class="filter-accordion-body" style="margin-top: 18px; transition: all 0.25s ease;">
            <label class="stock-toggle-label" style="display: inline-flex; align-items: center; gap: 14px; cursor: pointer; user-select: none;">
                <input type="checkbox" id="filter-in-stock-checkbox" onchange="onStockFilterToggle()" style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;">
                <span class="stock-toggle-track">
                    <span class="stock-toggle-thumb"></span>
                </span>
                <span style="font-size: 14px; color: #334155; font-weight: 500;">In stock only</span>
            </label>
        </div>
    </div>

    <!-- 2. Price Section -->
    <div class="filter-accordion-section" style="border-bottom: 1.5px solid #f1f5f9; padding-bottom: 22px; margin-bottom: 22px;">
        <div class="filter-accordion-header" onclick="toggleCatalogFilter('filter-price-content', this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
            <span style="font-size: 15px; font-weight: 700; color: #1e293b; letter-spacing: 0.2px;">Price</span>
            <i class="fa-solid fa-chevron-up filter-accordion-chevron" style="font-size: 12px; color: #475569; transition: transform 0.25s ease;"></i>
        </div>
        <div id="filter-price-content" class="filter-accordion-body" style="margin-top: 20px; transition: all 0.25s ease;">
            
            <!-- Quick Price Filter Chips -->
            <div class="quick-price-chips" style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px;">
                <button type="button" class="quick-price-pill active" id="quick-price-all" data-quick-type="all" onclick="setQuickPrice(0, 999999, this)">All</button>
                <button type="button" class="quick-price-pill" id="quick-price-under" data-quick-type="under" onclick="setQuickPrice(0, 1000, this)">Under ₹1K</button>
                <button type="button" class="quick-price-pill" id="quick-price-between" data-quick-type="between" onclick="setQuickPrice(1000, 1500, this)">₹1K - ₹1.5K</button>
                <button type="button" class="quick-price-pill" id="quick-price-above" data-quick-type="above" onclick="setQuickPrice(1500, 999999, this)">Above ₹1.5K</button>
            </div>

            <!-- Dual Range Track with Circular Knobs Matching Design -->
            <div class="price-range-slider-wrap" id="price-range-slider-wrap" style="position: relative; height: 32px; display: flex; align-items: center; margin-bottom: 18px; touch-action: none; cursor: pointer;">
                <div class="price-track-base" style="position: absolute; width: 100%; height: 4px; background: #e2e8f0; border-radius: 2px;"></div>
                <div id="price-track-highlight" style="position: absolute; left: 0%; right: 0%; height: 4px; background: #e11d48; border-radius: 2px; transition: left 0.05s ease, right 0.05s ease;"></div>
                <input type="range" id="price-slider-min" min="0" max="<?= $maxFilterPrice ?>" value="0" step="10" oninput="onPriceSliderSync('min')" onchange="runCatalogFilter()" class="dual-range-input" style="position: absolute; width: 100%; appearance: none; background: transparent; -webkit-appearance: none; z-index: 3; margin: 0; outline: none;">
                <input type="range" id="price-slider-max" min="0" max="<?= $maxFilterPrice ?>" value="<?= $maxFilterPrice ?>" step="10" oninput="onPriceSliderSync('max')" onchange="runCatalogFilter()" class="dual-range-input" style="position: absolute; width: 100%; appearance: none; background: transparent; -webkit-appearance: none; z-index: 4; margin: 0; outline: none;">
            </div>

            <!-- Price Inputs: [ ₹ 0 ] to [ ₹ 3000 ] -->
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                <div class="price-input-container" style="display: flex; align-items: center; border: 1.5px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; width: 105px; background: #ffffff; transition: all 0.2s; box-sizing: border-box;">
                    <span style="color: #e11d48; font-size: 13px; font-weight: 700; margin-right: 4px;">₹</span>
                    <input type="number" id="price-box-min" value="0" min="0" max="<?= $maxFilterPrice ?>" step="any" oninput="onPriceBoxInputDebounced()" onchange="onPriceBoxSync()" onkeydown="if(event.key==='Enter') applyCatalogFilter(true)" style="border: none; outline: none; width: 100%; font-size: 13.5px; font-weight: 600; color: #1e293b; background: transparent;">
                </div>
                <span style="color: #64748b; font-size: 13px; font-weight: 600;">to</span>
                <div class="price-input-container" style="display: flex; align-items: center; border: 1.5px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; width: 105px; background: #ffffff; transition: all 0.2s; box-sizing: border-box;">
                    <span style="color: #e11d48; font-size: 13px; font-weight: 700; margin-right: 4px;">₹</span>
                    <input type="number" id="price-box-max" value="<?= $maxFilterPrice ?>" min="0" max="<?= $maxFilterPrice ?>" step="any" oninput="onPriceBoxInputDebounced()" onchange="onPriceBoxSync()" onkeydown="if(event.key==='Enter') applyCatalogFilter(true)" style="border: none; outline: none; width: 100%; font-size: 13.5px; font-weight: 600; color: #1e293b; background: transparent;">
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Color Selection Section -->
    <div class="filter-accordion-section" style="border-bottom: 1.5px solid #f1f5f9; padding-bottom: 22px; margin-bottom: 22px;">
        <div class="filter-accordion-header" onclick="toggleCatalogFilter('filter-color-content', this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 15px; font-weight: 700; color: #1e293b; letter-spacing: 0.2px;">Color</span>
                <span id="selected-color-counter" style="display: none; background: #e11d48; color: #fff; font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 10px;">0</span>
            </div>
            <i class="fa-solid fa-chevron-up filter-accordion-chevron" style="font-size: 12px; color: #475569; transition: transform 0.25s ease;"></i>
        </div>
        <div id="filter-color-content" class="filter-accordion-body" style="margin-top: 16px; transition: all 0.25s ease;">
            <div class="color-swatches-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; align-items: center;">
                <?php 
if (!empty($filterColorsList)) {
                    $sareeColors = $filterColorsList;
                } else {
                    $sareeColors = [
                        ['name' => 'Red', 'code' => '#dc2626', 'dark' => true],
                        ['name' => 'Maroon', 'code' => '#800020', 'dark' => true],
                        ['name' => 'Pink', 'code' => '#ec4899', 'dark' => true],
                        ['name' => 'Blue', 'code' => '#2563eb', 'dark' => true],
                        ['name' => 'Navy', 'code' => '#1e3a8a', 'dark' => true],
                        ['name' => 'Green', 'code' => '#16a34a', 'dark' => true],
                        ['name' => 'Teal', 'code' => '#0d9488', 'dark' => true],
                        ['name' => 'Yellow', 'code' => '#eab308', 'dark' => false],
                        ['name' => 'Orange', 'code' => '#ea580c', 'dark' => true],
                        ['name' => 'Gold', 'code' => '#d97706', 'dark' => true],
                        ['name' => 'Purple', 'code' => '#9333ea', 'dark' => true],
                        ['name' => 'Lavender', 'code' => '#c084fc', 'dark' => false],
                        ['name' => 'Black', 'code' => '#18181b', 'dark' => true],
                        ['name' => 'Cream', 'code' => '#fef08a', 'dark' => false],
                        ['name' => 'White', 'code' => '#ffffff', 'dark' => false, 'border' => true],
                    ];
                }
                foreach ($sareeColors as $c):
                ?>
                <button type="button" 
                        class="color-filter-swatch" 
                        data-color="<?= strtolower($c['name']) ?>" 
                        onclick="toggleColorFilter('<?= strtolower($c['name']) ?>', this)"
                        title="<?= esc($c['name']) ?>"
                        style="width: 32px; height: 32px; border-radius: 50%; background-color: <?= $c['code'] ?>; border: <?= !empty($c['border']) ? '1.5px solid #cbd5e1' : '1px solid rgba(0,0,0,0.1)' ?>; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; position: relative; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                    <i class="fa-solid fa-check color-check-icon" style="display: none; font-size: 13px; color: <?= $c['dark'] ? '#ffffff' : '#1e293b' ?>;"></i>
                </button>
                <?php endforeach; ?>
            </div>
            <div id="color-name-label" style="margin-top: 10px; font-size: 12px; color: #64748b; font-weight: 500; min-height: 18px;"></div>
        </div>
    </div>

    <!-- 3b. Size / Dimension Selection Section -->
    <div class="filter-accordion-section" style="border-bottom: 1.5px solid #f1f5f9; padding-bottom: 22px; margin-bottom: 22px;">
        <div class="filter-accordion-header" onclick="toggleCatalogFilter('filter-size-content', this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 15px; font-weight: 700; color: #1e293b; letter-spacing: 0.2px;">Size / Dimension</span>
                <span id="selected-size-counter" style="display: none; background: #2563eb; color: #fff; font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 10px;">0</span>
            </div>
            <i class="fa-solid fa-chevron-up filter-accordion-chevron" style="font-size: 12px; color: #475569; transition: transform 0.25s ease;"></i>
        </div>
        <div id="filter-size-content" class="filter-accordion-body" style="margin-top: 14px; transition: all 0.25s ease;">
            <div class="size-filter-chips" style="display: flex; flex-wrap: wrap; gap: 6px;">
                <?php
                $availableSizes = !empty($filterSizesList) ? $filterSizesList : [
                    ['name' => 'Free Size'],
                    ['name' => 'S'],
                    ['name' => 'M'],
                    ['name' => 'L'],
                    ['name' => 'XL'],
                    ['name' => 'XXL'],
                    ['name' => '4 Muzham (Single Dhoti)'],
                    ['name' => '8 Muzham (Double Dhoti)'],
                ];
                foreach ($availableSizes as $sz):
                    $szName = trim($sz['name'] ?? '');
                    if (empty($szName)) continue;
                ?>
                <button type="button"
                        class="size-filter-pill"
                        data-size="<?= esc($szName) ?>"
                        onclick="toggleSizeFilter('<?= esc(addslashes($szName)) ?>', this)"
                        style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; font-size: 12px; font-weight: 600; padding: 5px 11px; border-radius: 6px; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); user-select: none;">
                    <?= esc($szName) ?>
                </button>
                <?php endforeach; ?>
            </div>
            <div id="size-name-label" style="margin-top: 8px; font-size: 12px; color: #2563eb; font-weight: 600; min-height: 18px;"></div>
        </div>
    </div>

    <!-- 4. Filter Action Buttons (Apply & Reset) -->
    <div class="filter-actions-bar" style="margin-top: 14px; margin-bottom: 24px; display: flex; flex-direction: column; gap: 10px;">
        <button type="button" id="btn-apply-catalog-filters" onclick="applyCatalogFilter(true)" class="btn-apply-filters">
            <i class="fa-solid fa-sliders"></i>
            <span>Apply Filter</span>
            <span id="apply-filter-count-badge" class="apply-filter-badge" style="display: none;">0</span>
        </button>
        <button type="button" id="btn-reset-catalog-filters" onclick="resetAllCatalogFilters()" class="btn-reset-filters">
            <i class="fa-solid fa-rotate-left"></i>
            <span>Reset All Filters</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if (isset($showMainMenu) && $showMainMenu): ?>
    <?php
        $sidebarCatModel = new \App\Models\CategoryModel();
        $sidebarSubcatModel = new \App\Models\SubcategoryModel();
        $settingsModel = new \App\Models\SettingsModel();
        $headerCatSetting = $settingsModel->where('key', 'header_categories')->first();

        $sidebarCategories = [];
        if ($headerCatSetting && !empty($headerCatSetting['value'])) {
            $selectedCatData = json_decode($headerCatSetting['value'], true);
            if (is_array($selectedCatData) && count($selectedCatData) > 0) {
                foreach ($selectedCatData as $catData) {
                    $name = is_array($catData) ? ($catData['name'] ?? '') : $catData;
                    $name = trim($name);

                    if (!empty($name)) {
                        $dbCat = $sidebarCatModel->like('name', $name, 'both')->first();

                        if ($dbCat) {
                            $catItem = $dbCat;
                        } else {
                            $catItem = [
                                'id' => null,
                                'name' => ucfirst($name),
                                'slug' => url_title($name, '-', true)
                            ];
                        }

                        $catItem['subcategories'] = !empty($catItem['id'])
                            ? $sidebarSubcatModel->where('parent_category_id', $catItem['id'])->orderBy('name', 'ASC')->findAll()
                            : [];
                        $sidebarCategories[] = $catItem;
                    }
                }
            }
        } else {
            $sidebarCategories = $sidebarCatModel->orderBy('name', 'ASC')->findAll();
            foreach ($sidebarCategories as &$scat) {
                $scat['subcategories'] = $sidebarSubcatModel->where('parent_category_id', $scat['id'])->orderBy('name', 'ASC')->findAll();
            }
        }
    ?>
    <!-- Main Menu with Category & Subcategory Selection (Synced with Header Categories) -->
    <div class="filter-main-menu-block" style="padding-top: 8px;">
        <h4 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 16px 0; letter-spacing: 0.2px;">Main menu</h4>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
            <li><a href="<?= base_url('/') ?>" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500; display: block;">Home</a></li>
            
            <!-- Header Categories & Subcategories -->
            <?php foreach ($sidebarCategories as $catItem): ?>
                <?php $hasSubs = !empty($catItem['subcategories']); ?>
                <li class="sidebar-cat-item">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 2px 0;">
                        <a href="<?= base_url('category/' . esc($catItem['slug'])) ?>" style="color: #334155; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 7px; transition: color 0.2s;">
                            <span style="width: 5px; height: 5px; border-radius: 50%; background: #e11d48; display: inline-block;"></span>
                            <?= esc($catItem['name']) ?>
                        </a>
                        <?php if ($hasSubs): ?>
                        <button type="button" onclick="toggleSidebarSubMenu('cat-sub-<?= $catItem['id'] ?>', this)" title="Toggle subcategories" style="background: none; border: none; cursor: pointer; color: #64748b; padding: 2px 8px; font-size: 11px;">
                            <i class="fa-solid fa-chevron-down sub-arrow-icon" style="transition: transform 0.2s;"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php if ($hasSubs): ?>
                    <ul id="cat-sub-<?= $catItem['id'] ?>" class="sidebar-subcat-list" style="list-style: none; padding: 6px 0 6px 14px; margin: 4px 0 0 0; display: none; flex-direction: column; gap: 8px; border-left: 2px solid #e2e8f0;">
                        <?php foreach ($catItem['subcategories'] as $subItem): ?>
                        <li>
                            <a href="<?= base_url('category/' . esc($subItem['slug'])) ?>" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; display: block; transition: color 0.2s;">
                                • <?= esc($subItem['name']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>

            <li><a href="<?= base_url('collections/new-arrivals') ?>" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500; display: block;">New Arrivals</a></li>
            <li><a href="<?= base_url('collections') ?>" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500; display: block;">All collections</a></li>
            <li><a href="<?= base_url('collections/best-sellers') ?>" style="color: #4b5563; text-decoration: none; font-size: 14px; font-weight: 500; display: block;">Best Sellers</a></li>
        </ul>
    </div>
    <?php endif; ?>
    </div> <!-- /.filter-sections-inner-wrapper -->
</aside>

<style>
.product-card.filter-hidden,
.collection-card.filter-hidden,
.collection-item-card.filter-hidden {
    display: none !important;
}
@media (max-width: 991px) {
    .mobile-filter-drawer-toggle {
        display: flex !important;
    }
    .filter-sections-inner-wrapper {
        display: none;
        padding-top: 10px;
    }
    .filter-sections-inner-wrapper.mobile-expanded {
        display: block !important;
    }
    .catalog-filter-sidebar {
        position: static !important;
        max-height: none !important;
        overflow: visible !important;
        width: 100% !important;
        min-width: 0 !important;
        border-right: none !important;
        padding-right: 0 !important;
        margin-bottom: 20px !important;
    }
}

/* Sticky Left Sidebar */
.catalog-filter-sidebar {
    position: sticky;
    top: 75px;
    align-self: flex-start;
    max-height: calc(100vh - 90px);
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
}
.catalog-filter-sidebar::-webkit-scrollbar {
    width: 4px;
}
.catalog-filter-sidebar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Availability Switch Styling Matching Image */
.stock-toggle-track {
    width: 44px;
    height: 24px;
    background-color: #d1d5db;
    border-radius: 9999px;
    position: relative;
    display: inline-block;
    transition: background-color 0.25s ease;
}
.stock-toggle-thumb {
    width: 18px;
    height: 18px;
    background-color: #ffffff;
    border-radius: 50%;
    position: absolute;
    top: 3px;
    left: 3px;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0,0,0,0.25);
}
#filter-in-stock-checkbox:checked + .stock-toggle-track {
    background-color: #111827;
}
#filter-in-stock-checkbox:checked + .stock-toggle-track .stock-toggle-thumb {
    transform: translateX(20px);
}

/* Dual Range Slider Thumbs with High-Touch Target and Maroon Accents */
.dual-range-input {
    pointer-events: none;
}
.dual-range-input::-webkit-slider-thumb {
    appearance: none;
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #e11d48;
    border: 2.5px solid #ffffff;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 6px rgba(225, 29, 72,0.35);
    transition: transform 0.15s ease, background-color 0.15s ease;
}
.dual-range-input::-webkit-slider-thumb:hover,
.dual-range-input::-webkit-slider-thumb:active {
    transform: scale(1.25);
    background-color: #901738;
}
.dual-range-input::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #e11d48;
    border: 2.5px solid #ffffff;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 6px rgba(225, 29, 72,0.35);
}

/* Quick Price Pills */
.quick-price-pill {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 11.5px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
}
.quick-price-pill:hover {
    border-color: #d4af37;
    color: #e11d48;
}
.quick-price-pill.active {
    background: #e11d48 !important;
    color: #ffffff !important;
    border-color: #e11d48 !important;
    box-shadow: 0 2px 6px rgba(225, 29, 72,0.25);
}

/* Color Swatches Checkmark - strictly hidden by default */
.color-filter-swatch .color-check-icon,
.color-filter-swatch svg,
.color-filter-swatch i {
    display: none !important;
}

/* Color Swatches Active State */
.color-filter-swatch.active {
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #e11d48 !important;
    transform: scale(1.1);
}
.color-filter-swatch:hover {
    transform: scale(1.15);
}
.color-filter-swatch.active .color-check-icon,
.color-filter-swatch.active svg,
.color-filter-swatch.active i {
    display: inline-block !important;
}

.price-input-container:focus-within {
    border-color: #e11d48 !important;
    box-shadow: 0 0 0 2px rgba(225, 29, 72,0.1);
}

.sidebar-cat-item a:hover {
    color: #e11d48 !important;
}

/* Apply & Reset Buttons Styling */
.btn-apply-filters {
    width: 100%;
    padding: 12px 18px;
    background: linear-gradient(135deg, #e11d48 0%, #901738 100%);
    color: #ffffff;
    border: 1.5px solid #d4af37;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.3px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    user-select: none;
    outline: none;
}
.btn-apply-filters:hover {
    background: linear-gradient(135deg, #901738 0%, #a81c42 100%);
    box-shadow: 0 6px 16px rgba(225, 29, 72, 0.35);
    transform: translateY(-1px);
}
.btn-apply-filters:active {
    transform: translateY(1px);
}
.btn-apply-filters.has-pending-changes {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.4), 0 4px 14px rgba(225, 29, 72, 0.35);
}
.apply-filter-badge {
    background: #d4af37;
    color: #500724;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 12px;
    margin-left: 4px;
    line-height: 1;
}
.btn-reset-filters {
    width: 100%;
    padding: 10px 16px;
    background: #f8fafc;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: all 0.2s ease;
    user-select: none;
}
.btn-reset-filters:hover {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fca5a5;
}

@media (max-width: 992px) {
    .catalog-filter-sidebar {
        position: static !important;
        width: 100% !important;
        min-width: 0 !important;
        border-right: none !important;
        border-bottom: 1.5px solid #eaeaea !important;
        padding-right: 0 !important;
        margin-bottom: 20px !important;
        max-height: none !important;
    }
    .filter-main-menu-block {
        display: none !important;
    }
}
</style>

<script>
var activeSelectedColors = [];
    activeSelectedSizes = [];
    document.querySelectorAll('.size-filter-pill').forEach(function(el) {
        el.classList.remove('active');
        el.style.backgroundColor = '#ffffff';
        el.style.borderColor = '#cbd5e1';
        el.style.color = '#334155';
    });
    var sCounter = document.getElementById('selected-size-counter');
    if (sCounter) sCounter.style.display = 'none';
    var sLabel = document.getElementById('size-name-label');
    if (sLabel) sLabel.textContent = '';
var activeSelectedSizes = [];
var catalogBaseMaxPrice = <?= $maxFilterPrice ?>;
var catalogTotalMaxPrice = catalogBaseMaxPrice;
var catalogCurrencyRate = 1.0;
var catalogCurrencyCode = 'INR';
var catalogCurrencySymbol = '₹';

function formatSidebarCompactPrice(inrAmount, currCode, rate, symbol) {
    if (currCode === 'INR') {
        if (inrAmount === 1000) return '₹1K';
        if (inrAmount === 1500) return '₹1.5K';
        return '₹' + inrAmount;
    }
    var conv = inrAmount * rate;
    var formatted;
    if (conv >= 100) {
        formatted = Math.round(conv).toLocaleString('en-US');
    } else if (conv % 1 === 0) {
        formatted = conv.toString();
    } else {
        formatted = conv.toFixed(2).replace(/\.00$/, '');
    }
    return symbol.trim() + formatted;
}

function syncCatalogCurrency(country) {
    if (!country) {
        try {
            var raw = localStorage.getItem('sri_user_country_currency');
            if (raw) country = JSON.parse(raw);
        } catch(e) {}
    }
    if (!country) {
        country = { currency: 'INR', symbol: '₹' };
    }

    var currCode = country.currency || 'INR';
    var rates = (window.CURRENCY_RATES || {
        'INR': { rate: 1.0, symbol: '₹' },
        'USD': { rate: 0.012, symbol: '$' },
        'EUR': { rate: 0.011, symbol: '€' },
        'GBP': { rate: 0.0095, symbol: '£' },
        'AED': { rate: 0.044, symbol: 'AED ' },
        'SGD': { rate: 0.016, symbol: 'S$' },
        'CAD': { rate: 0.0163, symbol: 'C$' },
        'AUD': { rate: 0.0182, symbol: 'A$' }
    });
    var currInfo = rates[currCode] || { rate: 1.0, symbol: country.symbol || '₹' };
    catalogCurrencyRate = currInfo.rate || 1.0;
    catalogCurrencyCode = currCode;
    catalogCurrencySymbol = (country.symbol || currInfo.symbol || '₹').trim();

    // 1. Update Quick Price Pill labels
    var pUnder = document.getElementById('quick-price-under');
    var pBetween = document.getElementById('quick-price-between');
    var pAbove = document.getElementById('quick-price-above');

    if (currCode === 'INR') {
        if (pUnder) pUnder.textContent = 'Under ₹1K';
        if (pBetween) pBetween.textContent = '₹1K - ₹1.5K';
        if (pAbove) pAbove.textContent = 'Above ₹1.5K';
    } else {
        var str1000 = formatSidebarCompactPrice(1000, currCode, catalogCurrencyRate, catalogCurrencySymbol);
        var str1500 = formatSidebarCompactPrice(1500, currCode, catalogCurrencyRate, catalogCurrencySymbol);
        if (pUnder) pUnder.textContent = 'Under ' + str1000;
        if (pBetween) pBetween.textContent = str1000 + ' - ' + str1500;
        if (pAbove) pAbove.textContent = 'Above ' + str1500;
    }

    // 2. Update currency symbol in input boxes
    document.querySelectorAll('.price-input-container span').forEach(function(span) {
        span.textContent = catalogCurrencySymbol;
    });

    // 3. Update slider and box max & step
    var newMax = catalogCurrencyRate >= 1 
        ? Math.ceil(catalogBaseMaxPrice * catalogCurrencyRate) 
        : Math.round(catalogBaseMaxPrice * catalogCurrencyRate * 10) / 10;
    catalogTotalMaxPrice = newMax;

    var step = catalogCurrencyRate < 0.1 ? 0.5 : (catalogCurrencyRate < 1 ? 1 : 10);

    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    var bMin = document.getElementById('price-box-min');
    var bMax = document.getElementById('price-box-max');

    if (sMin) { sMin.max = catalogTotalMaxPrice; sMin.step = step; }
    if (sMax) { sMax.max = catalogTotalMaxPrice; sMax.step = step; }
    if (bMin) { bMin.max = catalogTotalMaxPrice; bMin.step = step; }
    if (bMax) { bMax.max = catalogTotalMaxPrice; bMax.step = step; }

    var allPill = document.getElementById('quick-price-all');
    var currentAllActive = allPill && allPill.classList.contains('active');
    if (currentAllActive || !bMax || !bMax.value || parseFloat(bMax.value) > catalogTotalMaxPrice || parseFloat(bMax.value) >= catalogTotalMaxPrice * 0.95) {
        if (bMin && (currentAllActive || !bMin.value)) bMin.value = 0;
        if (bMax) bMax.value = catalogTotalMaxPrice;
        if (sMin && currentAllActive) sMin.value = 0;
        if (sMax) sMax.value = catalogTotalMaxPrice;
    }

    updatePriceHighlight();
}

function toggleSidebarSubMenu(subId, btnEl) {
    var subEl = document.getElementById(subId);
    var icon = btnEl.querySelector('.sub-arrow-icon');
    if (!subEl) return;
    if (subEl.style.display === 'none' || subEl.style.display === '') {
        subEl.style.display = 'flex';
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        subEl.style.display = 'none';
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}

function toggleCatalogFilter(bodyId, headerEl) {
    var body = document.getElementById(bodyId);
    var chevron = headerEl.querySelector('.filter-accordion-chevron');
    if (!body) return;
    if (body.style.display === 'none') {
        body.style.display = 'block';
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    } else {
        body.style.display = 'none';
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
}

var priceDebounceTimer = null;

// Comprehensive color synonyms and hex mappings to support all variant colors
var COLOR_SYNONYMS = {
    'pink': ['pink', 'rose', 'baby pink', 'rani', 'magenta', 'light pink', '#ffc0cb', '#ec4899', '#f472b6', '#db2777', '#fbcfe8'],
    'red': ['red', 'crimson', 'scarlet', 'dark red', '#dc2626', '#ef4444', '#b91c1c'],
    'maroon': ['maroon', 'wine', 'burgundy', 'rust', '#800020', '#e11d48', '#831843', '#500724'],
    'blue': ['blue', 'sky', 'royal blue', 'light blue', '#2563eb', '#3b82f6', '#124bce', '#0284c7', '#60a5fa'],
    'navy': ['navy', 'dark blue', 'midnight blue', '#1e3a8a', '#1e40af', '#172554'],
    'green': ['green', 'olive', 'emerald', 'mint', 'bottle green', 'pista', '#16a34a', '#22c55e', '#15803d'],
    'teal': ['teal', 'peacock', '#0d9488', '#14b8a6', '#115e59'],
    'yellow': ['yellow', 'mustard', 'lemon', '#eab308', '#facc15', '#fde047'],
    'orange': ['orange', 'peach', 'coral', '#ea580c', '#f97316', '#fb923c'],
    'gold': ['gold', 'golden', 'zari', '#d97706', '#d4af37', '#ffd700'],
    'purple': ['purple', 'violet', 'plum', '#9333ea', '#a855f7', '#7e22ce'],
    'lavender': ['lavender', 'lilac', '#c084fc', '#d8b4fe'],
    'black': ['black', '#18181b', '#000000', '#111827'],
    'cream': ['cream', 'beige', 'off-white', 'ivory', 'fawn', '#fef08a', '#fef9c3', '#faf5ef'],
    'white': ['white', '#ffffff', '#f8fafc', '#fff']
};

function colorMatchesText(cardText, term) {
    if (!term || !cardText) return false;
    term = term.trim().toLowerCase();
    if (term.charAt(0) === '#') {
        return cardText.indexOf(term) !== -1;
    }
    var escaped = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    return new RegExp('(?:^|[^a-z0-9])' + escaped + '(?:[^a-z0-9]|$)', 'i').test(cardText);
}

function updateSliderZIndexByPointer(clientX) {
    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    var wrap = document.getElementById('price-range-slider-wrap');
    if (!sMin || !sMax || !wrap) return;
    var rect = wrap.getBoundingClientRect();
    if (rect.width <= 0) return;
    var pct = (clientX - rect.left) / rect.width;
    pct = Math.max(0, Math.min(1, pct));
    var targetVal = pct * catalogTotalMaxPrice;
    var curMin = parseInt(sMin.value) || 0;
    var curMax = parseInt(sMax.value) || catalogTotalMaxPrice;
    if (Math.abs(targetVal - curMin) < Math.abs(targetVal - curMax)) {
        sMin.style.zIndex = '6';
        sMax.style.zIndex = '4';
    } else {
        sMax.style.zIndex = '6';
        sMin.style.zIndex = '4';
    }
}

function updatePriceHighlight() {
    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    if (!sMin || !sMax) return;
    var minVal = parseFloat(sMin.value) || 0;
    var maxVal = parseFloat(sMax.value) || catalogTotalMaxPrice;
    var highlight = document.getElementById('price-track-highlight');
    if (!highlight) return;
    var minPercent = Math.max(0, Math.min(100, (minVal / catalogTotalMaxPrice) * 100));
    var maxPercent = Math.max(0, Math.min(100, (maxVal / catalogTotalMaxPrice) * 100));
    highlight.style.left = minPercent + '%';
    highlight.style.right = (100 - maxPercent) + '%';
}

var catalogFilterDebounceTimer = null;
function runCatalogFilterDebounced(delay) {
    clearTimeout(catalogFilterDebounceTimer);
    catalogFilterDebounceTimer = setTimeout(function() {
        runCatalogFilter();
    }, delay || 40);
}

function onPriceSliderSync(changedThumb) {
    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    var bMin = document.getElementById('price-box-min');
    var bMax = document.getElementById('price-box-max');
    if (!sMin || !sMax) return;

    var valMin = parseFloat(sMin.value) || 0;
    var valMax = parseFloat(sMax.value) || catalogTotalMaxPrice;

    if (valMin > valMax) {
        if (changedThumb === 'min') {
            sMin.value = valMax;
            valMin = valMax;
        } else {
            sMax.value = valMin;
            valMax = valMin;
        }
    }

    if (changedThumb === 'min') {
        sMin.style.zIndex = '6';
        sMax.style.zIndex = '4';
    } else {
        sMax.style.zIndex = '6';
        sMin.style.zIndex = '4';
    }

    if (bMin) bMin.value = valMin;
    if (bMax) bMax.value = valMax;
    
    updateQuickPricePillsState(valMin, valMax);
    updatePriceHighlight();
    markFilterChanged();
    runCatalogFilterDebounced(40);
}

function onPriceBoxInputDebounced() {
    clearTimeout(priceDebounceTimer);
    priceDebounceTimer = setTimeout(function() {
        onPriceBoxSync();
    }, 250);
}

function onPriceBoxSync() {
    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    var bMin = document.getElementById('price-box-min');
    var bMax = document.getElementById('price-box-max');
    if (!bMin || !bMax) return;

    var rawMin = bMin.value.trim();
    var rawMax = bMax.value.trim();

    var valMin = rawMin === '' ? 0 : (parseFloat(rawMin) || 0);
    var valMax = rawMax === '' ? catalogTotalMaxPrice : (parseFloat(rawMax) || catalogTotalMaxPrice);

    if (valMin < 0) valMin = 0;
    if (valMax > catalogTotalMaxPrice) valMax = catalogTotalMaxPrice;
    if (valMin > valMax) valMin = valMax;

    if (sMin) sMin.value = valMin;
    if (sMax) sMax.value = valMax;

    updateQuickPricePillsState(valMin, valMax);
    updatePriceHighlight();
    markFilterChanged();
    runCatalogFilter();
}

function setQuickPrice(minInr, maxInr, pillBtn) {
    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    var bMin = document.getElementById('price-box-min');
    var bMax = document.getElementById('price-box-max');

    var minVal = 0;
    var maxVal = catalogTotalMaxPrice;

    if (minInr > 0) {
        minVal = Math.round(minInr * catalogCurrencyRate * 100) / 100;
    }
    if (maxInr < 999999 && maxInr < catalogBaseMaxPrice) {
        maxVal = Math.round(maxInr * catalogCurrencyRate * 100) / 100;
    } else {
        maxVal = catalogTotalMaxPrice;
    }

    if (sMin) sMin.value = minVal;
    if (sMax) sMax.value = maxVal;
    if (bMin) bMin.value = minVal;
    if (bMax) bMax.value = maxVal;

    document.querySelectorAll('.quick-price-pill').forEach(function(btn) {
        btn.classList.remove('active');
    });
    if (pillBtn) {
        pillBtn.classList.add('active');
    }

    updatePriceHighlight();
    markFilterChanged();
    runCatalogFilter();
}

function updateQuickPricePillsState(minVal, maxVal) {
    document.querySelectorAll('.quick-price-pill').forEach(function(btn) {
        btn.classList.remove('active');
    });

    var p1000 = Math.round(1000 * catalogCurrencyRate * 100) / 100;
    var p1500 = Math.round(1500 * catalogCurrencyRate * 100) / 100;
    var tolerance = catalogCurrencyRate >= 1 ? 5 : 0.6;

    if (minVal === 0 && maxVal >= catalogTotalMaxPrice - tolerance) {
        var allBtn = document.getElementById('quick-price-all');
        if (allBtn) allBtn.classList.add('active');
    } else if (minVal === 0 && Math.abs(maxVal - p1000) <= tolerance) {
        var underBtn = document.getElementById('quick-price-under');
        if (underBtn) underBtn.classList.add('active');
    } else if (Math.abs(minVal - p1000) <= tolerance && Math.abs(maxVal - p1500) <= tolerance) {
        var betBtn = document.getElementById('quick-price-between');
        if (betBtn) betBtn.classList.add('active');
    } else if (Math.abs(minVal - p1500) <= tolerance && maxVal >= catalogTotalMaxPrice - tolerance) {
        var abvBtn = document.getElementById('quick-price-above');
        if (abvBtn) abvBtn.classList.add('active');
    }
}

function toggleSizeFilter(sizeVal, btnEl) {
    var normSize = sizeVal.trim().toLowerCase();
    var idx = activeSelectedSizes.indexOf(normSize);
    if (idx > -1) {
        activeSelectedSizes.splice(idx, 1);
        btnEl.classList.remove('active');
        btnEl.style.backgroundColor = '#ffffff';
        btnEl.style.borderColor = '#cbd5e1';
        btnEl.style.color = '#334155';
    } else {
        activeSelectedSizes.push(normSize);
        btnEl.classList.add('active');
        btnEl.style.backgroundColor = '#2563eb';
        btnEl.style.borderColor = '#2563eb';
        btnEl.style.color = '#ffffff';
    }

    var counter = document.getElementById('selected-size-counter');
    var label = document.getElementById('size-name-label');
    if (counter) {
        if (activeSelectedSizes.length > 0) {
            counter.textContent = activeSelectedSizes.length;
            counter.style.display = 'inline-block';
        } else {
            counter.style.display = 'none';
        }
    }
    if (label) {
        label.textContent = activeSelectedSizes.length > 0
            ? 'Selected: ' + activeSelectedSizes.map(function(s) { return s.toUpperCase(); }).join(', ')
            : '';
    }

    markFilterChanged();
    runCatalogFilter();
}
window.toggleSizeFilter = toggleSizeFilter;

function toggleColorFilter(colorName, btnEl) {
    var normColor = colorName.trim().toLowerCase();
    var idx = activeSelectedColors.indexOf(normColor);
    if (idx > -1) {
        activeSelectedColors.splice(idx, 1);
        btnEl.classList.remove('active');
    } else {
        activeSelectedColors.push(normColor);
        btnEl.classList.add('active');
    }
    
    var counter = document.getElementById('selected-color-counter');
    var label = document.getElementById('color-name-label');
    if (counter) {
        if (activeSelectedColors.length > 0) {
            counter.textContent = activeSelectedColors.length;
            counter.style.display = 'inline-block';
        } else {
            counter.style.display = 'none';
        }
    }
    if (label) {
        label.textContent = activeSelectedColors.length > 0 
            ? 'Selected: ' + activeSelectedColors.map(function(c) { return c.charAt(0).toUpperCase() + c.slice(1); }).join(', ') 
            : '';
    }

    markFilterChanged();
    runCatalogFilter();
}

function onStockFilterToggle() {
    markFilterChanged();
    runCatalogFilter();
}

function markFilterChanged() {
    var inStockCb = document.getElementById('filter-in-stock-checkbox');
    var inStockOnly = inStockCb ? inStockCb.checked : false;
    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    var minPrice = sMin ? (parseInt(sMin.value) || 0) : 0;
    var maxPrice = sMax ? (parseInt(sMax.value) || catalogTotalMaxPrice) : catalogTotalMaxPrice;

    var priceActive = (minPrice > 0 || maxPrice < catalogTotalMaxPrice);
    var colorsCount = activeSelectedColors.length;
    var sizesCount = activeSelectedSizes.length;

    var activeCriteriaCount = (inStockOnly ? 1 : 0) + (priceActive ? 1 : 0) + colorsCount;

    var applyBtn = document.getElementById('btn-apply-catalog-filters');
    var badge = document.getElementById('apply-filter-count-badge');

    if (applyBtn) {
        if (activeCriteriaCount > 0) {
            applyBtn.classList.add('has-pending-changes');
            if (badge) {
                badge.textContent = activeCriteriaCount;
                badge.style.display = 'inline-block';
            }
        } else {
            applyBtn.classList.remove('has-pending-changes');
            if (badge) {
                badge.style.display = 'none';
            }
        }
    }
}

function applyCatalogFilter(isUserAction) {
    runCatalogFilter();

    var applyBtn = document.getElementById('btn-apply-catalog-filters');
    if (applyBtn) {
        applyBtn.classList.remove('has-pending-changes');
    }

    // If user clicked apply and is on mobile viewport, collapse drawer and scroll to results
    if (isUserAction && window.innerWidth <= 991) {
        var wrapper = document.getElementById('filter-sections-inner-wrapper');
        var toggleBtn = document.querySelector('.mobile-filter-drawer-toggle');
        if (wrapper && wrapper.classList.contains('mobile-expanded')) {
            wrapper.classList.remove('mobile-expanded');
            if (toggleBtn) {
                var chevron = toggleBtn.querySelector('.mobile-filter-chevron');
                if (chevron) chevron.style.transform = 'none';
                toggleBtn.style.borderColor = '#d4af37';
                toggleBtn.style.background = '#fffdfa';
            }
        }

        var targetGrid = document.getElementById('collections-grid') || 
                         document.getElementById('category-product-grid') || 
                         document.getElementById('product-grid');
        if (targetGrid) {
            var topOffset = targetGrid.getBoundingClientRect().top + window.pageYOffset - 85;
            window.scrollTo({ top: Math.max(0, topOffset), behavior: 'smooth' });
        }
    }
}

function resetAllCatalogFilters() {
    var inStockCb = document.getElementById('filter-in-stock-checkbox');
    if (inStockCb) inStockCb.checked = false;

    var sMin = document.getElementById('price-slider-min');
    var sMax = document.getElementById('price-slider-max');
    var bMin = document.getElementById('price-box-min');
    var bMax = document.getElementById('price-box-max');
    if (sMin) sMin.value = 0;
    if (sMax) sMax.value = catalogTotalMaxPrice;
    if (bMin) bMin.value = 0;
    if (bMax) bMax.value = catalogTotalMaxPrice;

    activeSelectedColors = [];
    activeSelectedSizes = [];
    document.querySelectorAll('.size-filter-pill').forEach(function(el) {
        el.classList.remove('active');
        el.style.backgroundColor = '#ffffff';
        el.style.borderColor = '#cbd5e1';
        el.style.color = '#334155';
    });
    var sCounter = document.getElementById('selected-size-counter');
    if (sCounter) sCounter.style.display = 'none';
    var sLabel = document.getElementById('size-name-label');
    if (sLabel) sLabel.textContent = '';
    document.querySelectorAll('.color-filter-swatch').forEach(function(el) {
        el.classList.remove('active');
    });
    var counter = document.getElementById('selected-color-counter');
    if (counter) counter.style.display = 'none';
    var label = document.getElementById('color-name-label');
    if (label) label.textContent = '';

    document.querySelectorAll('.quick-price-pill').forEach(function(btn) {
        btn.classList.remove('active');
    });
    var allBtn = document.getElementById('quick-price-all') || document.querySelector('.quick-price-pill');
    if (allBtn) allBtn.classList.add('active');

    updatePriceHighlight();
    runCatalogFilter();

    var applyBtn = document.getElementById('btn-apply-catalog-filters');
    var badge = document.getElementById('apply-filter-count-badge');
    if (applyBtn) applyBtn.classList.remove('has-pending-changes');
    if (badge) badge.style.display = 'none';
}

function getCatalogProductCards() {
    var rawList = document.querySelectorAll(
        '#collections-grid > .product-card, #collections-grid > .collection-item-card, ' +
        '#category-product-grid > .product-card, #category-product-grid > .collection-card, ' +
        '#product-grid > .product-card, #product-grid > .collection-card, ' +
        '.collection-product-grid > .product-card, .collections-right-content > .product-card, ' +
        '#collections-grid .product-card, #category-product-grid .product-card, #product-grid .product-card'
    );
    return Array.from(new Set(rawList));
}

function runCatalogFilter() {
    var inStockOnly = document.getElementById('filter-in-stock-checkbox') ? document.getElementById('filter-in-stock-checkbox').checked : false;
    var minPrice = parseFloat(document.getElementById('price-box-min') ? document.getElementById('price-box-min').value : 0) || 0;
    var maxPrice = parseFloat(document.getElementById('price-box-max') ? document.getElementById('price-box-max').value : catalogTotalMaxPrice) || catalogTotalMaxPrice;

    var isAnyFilterActive = inStockOnly || (minPrice > 0) || (maxPrice < catalogTotalMaxPrice) || (activeSelectedColors.length > 0) || (activeSelectedSizes.length > 0);
    var summaryEl = document.getElementById('filter-active-summary');
    if (summaryEl) {
        summaryEl.style.display = isAnyFilterActive ? 'flex' : 'none';
    }

    // Update mobile toggle label with active count
    var mobileToggle = document.querySelector('.mobile-filter-drawer-toggle');
    if (mobileToggle) {
        var titleSpan = mobileToggle.querySelector('span');
        var totalActive = (inStockOnly ? 1 : 0) + ((minPrice > 0 || maxPrice < catalogTotalMaxPrice) ? 1 : 0) + activeSelectedColors.length + activeSelectedSizes.length;
        if (titleSpan) {
            if (totalActive > 0) {
                titleSpan.innerHTML = '<i class="fa-solid fa-sliders" style="color: #d4af37;"></i> Filter Products <span style="background: #e11d48; color: #fff; font-size: 11px; padding: 1px 7px; border-radius: 10px; margin-left: 6px;">' + totalActive + ' active</span>';
            } else {
                titleSpan.innerHTML = '<i class="fa-solid fa-sliders" style="color: #d4af37;"></i> Filter Products (In Stock, Price, Color)';
            }
        }
    }

    var cards = getCatalogProductCards();
    var visibleCount = 0;

    cards.forEach(function(card) {
        var cardPrice = parseFloat(card.getAttribute('data-price'));
        if (isNaN(cardPrice) || cardPrice <= 0) {
            var sEl = card.querySelector('.card-sale-price');
            if (sEl && sEl.getAttribute('data-base-price')) {
                cardPrice = parseFloat(sEl.getAttribute('data-base-price'));
            }
        }
        if (isNaN(cardPrice)) cardPrice = 0;

        var cardMinPrice = parseFloat(card.getAttribute('data-min-price'));
        if (isNaN(cardMinPrice)) cardMinPrice = cardPrice;
        var cardMaxPrice = parseFloat(card.getAttribute('data-max-price'));
        if (isNaN(cardMaxPrice)) cardMaxPrice = cardPrice;
        var cardStock = card.getAttribute('data-stock');
        var cardColorData = (card.getAttribute('data-color') || card.innerText || '').toLowerCase();

        // 1. Availability Filter
        var matchStock = true;
        if (inStockOnly) {
            if (cardStock === '0' || card.innerText.includes('SOLD OUT')) {
                matchStock = false;
            }
        }

        // 2. Price Filter (matches based on active displayed selling price)
        var cardPriceConverted = Math.round(cardPrice * catalogCurrencyRate * 100) / 100;
        var matchPrice = (cardPriceConverted <= (maxPrice + 0.05) && cardPriceConverted >= (minPrice - 0.05));

        // 3. Color Filter (matches if any selected color synonym or code matches)
        var matchColor = true;
        if (activeSelectedColors.length > 0) {
            matchColor = activeSelectedColors.some(function(selColor) {
                var synonyms = COLOR_SYNONYMS[selColor] || [selColor];
                return synonyms.some(function(term) {
                    return colorMatchesText(cardColorData, term);
                });
            });
        }

                // 4. Size Filter (matches if any selected size matches product data-sizes or data-selected-size)
        var matchSize = true;
        if (activeSelectedSizes.length > 0) {
            var cardSizesData = (card.getAttribute('data-sizes') || card.getAttribute('data-selected-size') || '').toLowerCase();
            var cardNameDesc = ((card.getAttribute('data-name') || '') + ' ' + (card.innerText || '')).toLowerCase();
            matchSize = activeSelectedSizes.some(function(selSz) {
                return cardSizesData.includes(selSz) || cardNameDesc.includes(selSz);
            });
        }

        if (matchStock && matchPrice && matchColor && matchSize) {
            card.style.removeProperty('display');
            card.classList.remove('filter-hidden');
            visibleCount++;
        } else {
            card.style.setProperty('display', 'none', 'important');
            card.classList.add('filter-hidden');
        }
    });

    // Update Product Count label if present
    var countEl = document.querySelector('.product-count');
    if (countEl) {
        countEl.textContent = visibleCount === 1 ? '1 product' : visibleCount + ' products';
    }

    // Toggle No Results Notice inside active catalog grid
    var grid = document.getElementById('collections-grid') || 
               document.getElementById('category-product-grid') || 
               document.getElementById('product-grid');
    if (grid) {
        var existingNotice = document.getElementById('no-filter-results-alert');
        if (visibleCount === 0) {
            if (!existingNotice) {
                var alertDiv = document.createElement('div');
                alertDiv.id = 'no-filter-results-alert';
                alertDiv.style.gridColumn = '1 / -1';
                alertDiv.style.padding = '36px 20px';
                alertDiv.style.textAlign = 'center';
                alertDiv.style.background = '#fffdfa';
                alertDiv.style.border = '1.5px dashed #d4af37';
                alertDiv.style.borderRadius = '12px';
                alertDiv.style.margin = '20px 0';
                alertDiv.innerHTML = '<i class="fa-solid fa-filter-circle-xmark" style="font-size: 32px; color: #e11d48; margin-bottom: 12px; display: block;"></i>' +
                    '<h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0 0 6px;">No products match your filter</h4>' +
                    '<p style="font-size: 13.5px; color: #64748b; margin: 0 0 14px;">Try expanding your price range or clearing selected colors.</p>' +
                    '<button type="button" onclick="resetAllCatalogFilters()" style="background: #e11d48; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-weight: 700; font-size: 13px; cursor: pointer;">Reset all filters</button>';
                grid.appendChild(alertDiv);
            }
        } else {
            if (existingNotice) {
                existingNotice.remove();
            }
        }
    }
}

// Function to sort products in DOM
function runCatalogSort(sortVal) {
    var grid = document.getElementById('category-product-grid') || 
               document.getElementById('collections-grid') || 
               document.getElementById('product-grid');
    if (!grid) return;
    
    var cards = Array.from(grid.children).filter(function(el) {
        return el.classList.contains('product-card') || el.classList.contains('collection-card') || el.classList.contains('collection-item-card');
    });

    cards.sort(function(a, b) {
        var priceA = parseFloat(a.getAttribute('data-price')) || 0;
        var priceB = parseFloat(b.getAttribute('data-price')) || 0;
        var titleA = (a.getAttribute('data-name') || a.querySelector('h3, .card-title, h4')?.innerText || '').trim().toLowerCase();
        var titleB = (b.getAttribute('data-name') || b.querySelector('h3, .card-title, h4')?.innerText || '').trim().toLowerCase();
        var idA = parseInt(a.getAttribute('data-id')) || 0;
        var idB = parseInt(b.getAttribute('data-id')) || 0;

        if (sortVal === 'price-asc') {
            return priceA - priceB;
        } else if (sortVal === 'price-desc') {
            return priceB - priceA;
        } else if (sortVal === 'alpha-asc') {
            return titleA.localeCompare(titleB);
        } else if (sortVal === 'alpha-desc') {
            return titleB.localeCompare(titleA);
        } else if (sortVal === 'best-selling') {
            var isBsA = a.innerText.includes('HOT') || a.getAttribute('data-best') === '1' ? 1 : 0;
            var isBsB = b.innerText.includes('HOT') || b.getAttribute('data-best') === '1' ? 1 : 0;
            return isBsB - isBsA;
        } else if (sortVal === 'date-asc') {
            return idA - idB;
        } else {
            return idB - idA;
        }
    });

    cards.forEach(function(card) {
        grid.appendChild(card);
    });
}

// Universal Grid View Switcher
function switchCollectionGridCols(cols, btn) {
    var grid = document.getElementById('collections-grid') || 
               document.getElementById('category-product-grid') || 
               document.getElementById('product-grid');
    if (!grid) return;

    document.querySelectorAll('.view-switches .view-btn').forEach(function(b) {
        b.classList.remove('active');
        b.style.color = '#999';
    });
    if (btn) {
        btn.classList.add('active');
        btn.style.color = '#333';
    }

    grid.classList.remove('view-cols-1', 'view-cols-2', 'view-cols-3');

    if (cols === 1) {
        grid.classList.add('view-cols-1');
        grid.style.gridTemplateColumns = '1fr';
        grid.querySelectorAll('.product-card, .collection-card').forEach(function(card) {
            card.classList.add('product-card-row-view');
        });
    } else if (cols === 2) {
        grid.classList.add('view-cols-2');
        grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
        grid.querySelectorAll('.product-card, .collection-card').forEach(function(card) {
            card.classList.remove('product-card-row-view');
        });
    } else {
        grid.classList.add('view-cols-3');
        grid.style.gridTemplateColumns = 'repeat(3, 1fr)';
        grid.querySelectorAll('.product-card, .collection-card').forEach(function(card) {
            card.classList.remove('product-card-row-view');
        });
    }

    try {
        localStorage.setItem('sri_preferred_grid_cols', cols);
    } catch(e) {}
}
window.switchCategoryGridCols = switchCollectionGridCols;

// Auto-bind to #sort-by select element and initialise
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic price ceiling detection
    var detectedMax = 0;
    getCatalogProductCards().forEach(function(c) {
        var p = parseFloat(c.getAttribute('data-price')) || 0;
        if (p > detectedMax) detectedMax = p;
    });
    if (detectedMax > catalogBaseMaxPrice) {
        catalogBaseMaxPrice = Math.ceil(detectedMax);
    }
    syncCatalogCurrency();

    window.addEventListener('currencyChanged', function(e) {
        syncCatalogCurrency(e.detail);
    });

    try {
        var urlParams = new URLSearchParams(window.location.search);
        var hadParam = false;
        if (urlParams.has('min_price')) {
            var minP = parseInt(urlParams.get('min_price')) || 0;
            var sMin = document.getElementById('price-slider-min');
            var bMin = document.getElementById('price-box-min');
            if (sMin) sMin.value = minP;
            if (bMin) bMin.value = minP;
            hadParam = true;
        }
        if (urlParams.has('max_price')) {
            var maxP = parseInt(urlParams.get('max_price')) || catalogTotalMaxPrice;
            var sMax = document.getElementById('price-slider-max');
            var bMax = document.getElementById('price-box-max');
            if (sMax) sMax.value = maxP;
            if (bMax) bMax.value = maxP;
            hadParam = true;
        }
        if (hadParam) {
            updatePriceHighlight();
            runCatalogFilter();
        } else {
            updatePriceHighlight();
        }
    } catch(e) {
        updatePriceHighlight();
    }

    var sortSelect = document.getElementById('sort-by');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            runCatalogSort(this.value);
        });
    }

    // Touch and drag support directly on the price slider track
    initPriceTrackTouchDrag();

    // Initial check for filter pending changes
    markFilterChanged();

    // Close custom sort dropdown when clicking anywhere outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.sort-dropdown-custom-wrap')) {
            var menu = document.getElementById('sort-custom-menu');
            var btn = document.getElementById('sort-custom-btn');
            if (menu) menu.style.display = 'none';
            if (btn) {
                var icon = btn.querySelector('.sort-chevron');
                if (icon) icon.style.transform = 'none';
            }
        }
    });
});

function initPriceTrackTouchDrag() {
    var wrap = document.getElementById('price-range-slider-wrap');
    if (!wrap) return;

    // Dynamically promote the closer knob on touch or mouse move
    wrap.addEventListener('mousemove', function(e) {
        updateSliderZIndexByPointer(e.clientX);
    });
    wrap.addEventListener('touchstart', function(e) {
        if (e.touches && e.touches[0]) {
            updateSliderZIndexByPointer(e.touches[0].clientX);
        }
    }, { passive: true });

    function handleTrackPointer(e) {
        // If clicking directly on an input thumb, let native input handle it
        if (e.target.tagName === 'INPUT') return;

        var rect = wrap.getBoundingClientRect();
        var clientX = e.touches ? e.touches[0].clientX : e.clientX;
        var pct = (clientX - rect.left) / rect.width;
        pct = Math.max(0, Math.min(1, pct));
        var targetVal = catalogCurrencyRate < 0.1 ? Math.round(pct * catalogTotalMaxPrice * 2) / 2 : Math.round(pct * catalogTotalMaxPrice);

        var sMin = document.getElementById('price-slider-min');
        var sMax = document.getElementById('price-slider-max');
        if (!sMin || !sMax) return;

        var curMin = parseFloat(sMin.value) || 0;
        var curMax = parseFloat(sMax.value) || catalogTotalMaxPrice;

        var distMin = Math.abs(targetVal - curMin);
        var distMax = Math.abs(targetVal - curMax);

        if (distMin < distMax) {
            sMin.value = targetVal;
            onPriceSliderSync('min');
        } else {
            sMax.value = targetVal;
            onPriceSliderSync('max');
        }
    }

    wrap.addEventListener('click', handleTrackPointer);
}

window.applyCatalogFilter = applyCatalogFilter;
window.resetAllCatalogFilters = resetAllCatalogFilters;
window.markFilterChanged = markFilterChanged;
window.runCatalogFilter = runCatalogFilter;

function toggleMobileFilterSidebar(el) {
    var wrapper = document.getElementById('filter-sections-inner-wrapper');
    if (!wrapper) return;
    wrapper.classList.toggle('mobile-expanded');
    var isExp = wrapper.classList.contains('mobile-expanded');
    var chevron = el ? el.querySelector('.mobile-filter-chevron') : null;
    if (chevron) {
        chevron.style.transform = isExp ? 'rotate(180deg)' : 'none';
    }
    if (el) {
        el.style.borderColor = isExp ? '#e11d48' : '#d4af37';
        el.style.background = isExp ? '#fdf6ec' : '#fffdfa';
    }
}
window.toggleMobileFilterSidebar = toggleMobileFilterSidebar;

// Custom Sort Dropdown Handlers (ensures full responsiveness inside mobile viewport)
function toggleCustomSortMenu(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    var menu = document.getElementById('sort-custom-menu');
    var btn = document.getElementById('sort-custom-btn');
    if (!menu) return;
    var isOpen = menu.style.display === 'block';
    menu.style.display = isOpen ? 'none' : 'block';
    if (btn) {
        var icon = btn.querySelector('.sort-chevron');
        if (icon) icon.style.transform = isOpen ? 'none' : 'rotate(180deg)';
    }
}
window.toggleCustomSortMenu = toggleCustomSortMenu;

function selectCustomSort(val, labelText) {
    var sortSelect = document.getElementById('sort-by');
    if (sortSelect) {
        sortSelect.value = val;
    }
    var labelEl = document.getElementById('sort-selected-label');
    if (labelEl) {
        labelEl.textContent = labelText;
    }
    // Update active highlight in menu
    document.querySelectorAll('.sort-option-item').forEach(function(item) {
        item.classList.remove('active');
        item.style.fontWeight = '500';
        item.style.color = '#334155';
        item.style.background = '#ffffff';
        var chk = item.querySelector('.sort-check');
        if (chk) chk.style.display = 'none';
    });
    var activeItem = document.querySelector('.sort-option-item[data-value="' + val + '"]');
    if (activeItem) {
        activeItem.classList.add('active');
        activeItem.style.fontWeight = '700';
        activeItem.style.color = '#e11d48';
        activeItem.style.background = '#fdf6ec';
        var chk = activeItem.querySelector('.sort-check');
        if (chk) chk.style.display = 'inline-block';
    }

    var menu = document.getElementById('sort-custom-menu');
    if (menu) menu.style.display = 'none';
    var btn = document.getElementById('sort-custom-btn');
    if (btn) {
        var icon = btn.querySelector('.sort-chevron');
        if (icon) icon.style.transform = 'none';
    }

    runCatalogSort(val);
}
window.selectCustomSort = selectCustomSort;
</script>
