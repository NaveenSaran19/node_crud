<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-products.css') ?>?v=<?= time() ?>">
<?php
    $settingsModel = new \App\Models\SettingsModel();
    $rawAttrColors = $settingsModel->where('key', 'attribute_colors')->first();
    $configuredColors = (!empty($rawAttrColors['value'])) ? json_decode($rawAttrColors['value'], true) : [];
    $rawAttrSizes = $settingsModel->where('key', 'attribute_sizes')->first();
        $configuredSizes = (!empty($rawAttrSizes['value'])) ? json_decode($rawAttrSizes['value'], true) : [];

    if (empty($configuredColors)) {
        $configuredColors = [
            ['name' => 'Red', 'code' => '#dc2626', 'dark' => 1],
            ['name' => 'Maroon', 'code' => '#800020', 'dark' => 1],
            ['name' => 'Pink', 'code' => '#ec4899', 'dark' => 1],
            ['name' => 'Blue', 'code' => '#2563eb', 'dark' => 1],
            ['name' => 'Navy', 'code' => '#1e3a8a', 'dark' => 1],
            ['name' => 'Green', 'code' => '#16a34a', 'dark' => 1],
            ['name' => 'Teal', 'code' => '#0d9488', 'dark' => 1],
            ['name' => 'Yellow', 'code' => '#eab308', 'dark' => 0],
            ['name' => 'Orange', 'code' => '#ea580c', 'dark' => 1],
            ['name' => 'Gold', 'code' => '#d97706', 'dark' => 1],
            ['name' => 'Purple', 'code' => '#9333ea', 'dark' => 1],
            ['name' => 'Lavender', 'code' => '#c084fc', 'dark' => 0],
            ['name' => 'Black', 'code' => '#18181b', 'dark' => 1],
            ['name' => 'Cream', 'code' => '#fef08a', 'dark' => 0],
            ['name' => 'White', 'code' => '#ffffff', 'dark' => 0],
        ];
    }
    if (empty($configuredSizes)) {
        $configuredSizes = [
            ['name' => '0-3 Months', 'category' => 'Kids', 'order' => 1],
            ['name' => '3-6 Months', 'category' => 'Kids', 'order' => 2],
            ['name' => '6-12 Months', 'category' => 'Kids', 'order' => 3],
            ['name' => '1-2 Yrs', 'category' => 'Kids', 'order' => 4],
            ['name' => '2-3 Yrs', 'category' => 'Kids', 'order' => 5],
            ['name' => '3-4 Yrs', 'category' => 'Kids', 'order' => 6],
            ['name' => '4-5 Yrs', 'category' => 'Kids', 'order' => 7],
            ['name' => '5-6 Yrs', 'category' => 'Kids', 'order' => 8],
            ['name' => '6-7 Yrs', 'category' => 'Kids', 'order' => 9],
            ['name' => '7-8 Yrs', 'category' => 'Kids', 'order' => 10],
            ['name' => '8-9 Yrs', 'category' => 'Kids', 'order' => 11],
            ['name' => '9-10 Yrs', 'category' => 'Kids', 'order' => 12],
            ['name' => '10-12 Yrs', 'category' => 'Kids', 'order' => 13],
            ['name' => '12-14 Yrs', 'category' => 'Kids', 'order' => 14],
            ['name' => 'Free Size', 'category' => 'General', 'order' => 15],
            ['name' => 'S', 'category' => 'Apparel', 'order' => 16],
            ['name' => 'M', 'category' => 'Apparel', 'order' => 17],
            ['name' => 'L', 'category' => 'Apparel', 'order' => 18],
            ['name' => 'XL', 'category' => 'Apparel', 'order' => 19],
            ['name' => 'XXL', 'category' => 'Apparel', 'order' => 20],
            ['name' => '3XL', 'category' => 'Apparel', 'order' => 21],
            ['name' => '4 Muzham (Single Dhoti)', 'category' => 'Dhoti', 'order' => 22],
            ['name' => '8 Muzham (Double Dhoti)', 'category' => 'Dhoti', 'order' => 23],
            ['name' => '9x5 (Dhoti + Angavastram Set)', 'category' => 'Dhoti', 'order' => 24],
            ['name' => '10x6 (Pattu Dhoti + Shawl Set)', 'category' => 'Dhoti', 'order' => 25],
        ];
    }

    if (!isset($sku_enabled)) {
        $settingsModel = new \App\Models\SettingsModel();
        $skuSetting = $settingsModel->where('key', 'module_product_sku')->first();
        $sku_enabled = $skuSetting ? ($skuSetting['value'] === '1' || $skuSetting['value'] === 1) : true;
    }

    if (!isset($creation_type_enabled)) {
        if (!isset($settingsModel)) $settingsModel = new \App\Models\SettingsModel();
        $ctSetting = $settingsModel->where('key', 'module_creation_type')->first();
        $creation_type_enabled = $ctSetting ? ($ctSetting['value'] === '1' || $ctSetting['value'] === 1) : true;
    }

    if (!isset($measurements_enabled)) {
        if (!isset($settingsModel)) $settingsModel = new \App\Models\SettingsModel();
        $measSetting = $settingsModel->where('key', 'module_measurements_section')->first();
        $measurements_enabled = $measSetting ? ($measSetting['value'] === '1' || $measSetting['value'] === 1) : true;
    }
?>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fa-solid fa-pen-to-square" style="margin-right: 10px;"></i> Edit Product</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= base_url('product/' . esc($product['slug'])) ?>" target="_blank" class="btn-outline" style="background: #fdfbf7; border-color: #d4af37; color: #e11d48; font-weight: 700;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live on Website
            </a>
            <a href="<?= base_url('admin/products') . (!empty($product['is_combo']) ? '?tab=combos#combos' : '?tab=products#products') ?>" class="btn-outline" id="headerBackBtn">
                <i class="fa-solid fa-arrow-left"></i> Back to <?= !empty($product['is_combo']) ? 'Combos' : 'Products' ?>
            </a>
        </div>
    </div>

    <?php if(session()->getFlashdata('errors')): ?>
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px; animation: slideUp 0.4s ease-out;">
            <ul style="margin-left: 20px; font-size: 14px; margin-bottom: 0;">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; animation: slideUp 0.4s ease-out;">
            <i class="fa-solid fa-circle-check"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/products/update/'.$product['id']) ?>" method="POST" enctype="multipart/form-data">
        
        <?php $isCombo = (old('is_combo', !empty($product['is_combo'])) ? true : false); ?>

        <!-- Interactive Sliding Switch to Select Product Type ("Just Sliding to Create") -->
        <?php if ($creation_type_enabled): ?>
        <div class="product-type-slider-wrapper">
            <div class="product-type-slider-header">
                <div class="slider-main-title">
                    <i class="fa-solid fa-layer-group" style="color: #e11d48;"></i>
                    <span>Product Type</span>
                </div>
                <div class="slider-main-desc">Slide to switch whether this item is a Single Product or a Combo Pack</div>
            </div>

            <div class="product-type-swipe-switch <?= $isCombo ? 'is-combo' : '' ?>" id="productTypeSwitch" role="group" aria-label="Product Type Switcher">
                <div class="type-switch-glider"></div>
                <button type="button" class="type-switch-btn <?= !$isCombo ? 'active' : '' ?>" id="typeBtnSingle" onclick="setProductType(false)">
                    <i class="fa-solid fa-box"></i>
                    <span>Regular Product</span>
                </button>
                <button type="button" class="type-switch-btn <?= $isCombo ? 'active' : '' ?>" id="typeBtnCombo" onclick="setProductType(true)">
                    <i class="fa-solid fa-gifts"></i>
                    <span>Combo Pack</span>
                    <span class="combo-tag-mini" style="margin-left: 4px;">Offer</span>
                </button>
            </div>
            <input type="hidden" name="is_combo" id="is_combo_field" value="<?= $isCombo ? '1' : '0' ?>">
        </div>

        <!-- Dynamic Combo Callout Banner -->
        <div class="combo-callout-banner <?= $isCombo ? 'show' : '' ?>" id="comboCalloutBanner">
            <i class="fa-solid fa-circle-info" style="font-size: 18px; color: #d97706;"></i>
            <div>
                <strong>Combo Pack Mode Active:</strong> This item is treated as a bundled offer in the catalog with combo badges and savings calculations.
            </div>
        </div>
        <?php else: ?>
        <input type="hidden" name="is_combo" id="is_combo_field" value="<?= $isCombo ? '1' : '0' ?>">
        <?php endif; ?>
        
        <!-- Product Section Tabs Navigator -->
        <div class="product-form-tabs-bar">
            <button type="button" class="product-form-tab-btn active" data-target="section-basic" onclick="switchProductSection('section-basic', this)">
                <i class="fa-solid fa-circle-info"></i>
                <span>1. Basic Info</span>
            </button>
            <?php if ($measurements_enabled): ?>
            <button type="button" class="product-form-tab-btn" data-target="section-specs" onclick="switchProductSection('section-specs', this)">
                <i class="fa-solid fa-ruler-combined"></i>
                <span>2. Measurements</span>
            </button>
            <?php endif; ?>
            <button type="button" class="product-form-tab-btn" data-target="section-variants" onclick="switchProductSection('section-variants', this)">
                <i class="fa-solid fa-layer-group"></i>
                <span><?= $measurements_enabled ? '3. Variants' : '2. Variants' ?></span>
            </button>
            <button type="button" class="product-form-tab-btn" data-target="section-media" onclick="switchProductSection('section-media', this)">
                <i class="fa-solid fa-images"></i>
                <span><?= $measurements_enabled ? '4. Media & Videos' : '3. Media & Videos' ?></span>
            </button>
            <button type="button" class="product-form-tab-btn" data-target="section-display" onclick="switchProductSection('section-display', this)">
                <i class="fa-solid fa-display"></i>
                <span><?= $measurements_enabled ? '5. Display & Badges' : '4. Display & Badges' ?></span>
            </button>
            <button type="button" class="product-form-tab-btn" data-target="section-seo" onclick="switchProductSection('section-seo', this)">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span><?= $measurements_enabled ? '6. SEO' : '5. SEO' ?></span>
            </button>
        </div>

<div id="section-basic" class="product-form-pane active">
<div class="premium-card">
            <div class="card-section-header">
                <div class="card-section-title-wrap">
                    <span class="card-section-icon-badge">
                        <i class="fa-solid fa-circle-info"></i>
                    </span>
                    <div>
                        <h3>Basic Information</h3>
                        <p>Essential product identity, categorization, and core catalog settings</p>
                    </div>
                </div>
                <span class="required-indicator-pill">
                    <i class="fa-solid fa-asterisk"></i> Required Fields
                </span>
            </div>

            <!-- Product Identity Row -->
            <div style="display: grid; grid-template-columns: <?= $sku_enabled ? '1.4fr 1.2fr 0.9fr' : '1.4fr 1.2fr' ?>; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-shirt" style="color: #e11d48; font-size: 13px;"></i>
                        <span>Product Name</span>
                        <span style="color: #dc2626;">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-pen-nib input-icon-prefix"></i>
                        <input type="text" name="name" class="premium-input has-prefix" value="<?= old('name', $product['name']) ?>" required placeholder="e.g. Kanchipuram Pure Silk Saree">
                    </div>
                    <small class="field-hint">Primary title displayed on storefront, cart, and invoices</small>
                </div>
                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-layer-group" style="color: #e11d48; font-size: 13px;"></i>
                        <span>Subcategory</span>
                        <span style="color: #dc2626;">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-folder-tree input-icon-prefix"></i>
                        <select name="subcategory_id" class="premium-input has-prefix" required>
                            <option value="">Select Subcategory</option>
                            <?php 
                                $grouped = [];
                                foreach($subcategories as $s) {
                                    $mc = !empty($s['main_category']) ? $s['main_category'] : 'General';
                                    if (!isset($grouped[$mc])) $grouped[$mc] = [];
                                    array_push($grouped[$mc], $s);
                                }
                                foreach($grouped as $mainCat => $subs): 
                            ?>
                                <optgroup label="<?= esc($mainCat) ?>">
                                    <?php foreach($subs as $sub): ?>
                                        <option value="<?= $sub['id'] ?>" <?= old('subcategory_id', $product['subcategory_id']) == $sub['id'] ? 'selected' : '' ?>><?= esc($sub['name']) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <small class="field-hint">Assigns product to navigation categories &amp; filters</small>
                </div>
                <?php if ($sku_enabled): ?>
                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-barcode" style="color: #e11d48; font-size: 13px;"></i>
                        <span>SKU</span>
                        <small style="color: #94a3b8; font-weight: 500;">(Optional)</small>
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-hashtag input-icon-prefix"></i>
                        <input type="text" name="sku" class="premium-input has-prefix" value="<?= old('sku', $product['sku']) ?>" placeholder="e.g. SKU-12345">
                    </div>
                    <small class="field-hint">Inventory tracking code</small>
                </div>
                <?php endif; ?>
            </div>

            <!-- Pricing & Stock Inventory Box -->
            <div class="pricing-stock-card">
                <div class="pricing-stock-header">
                    <div class="pricing-stock-title">
                        <i class="fa-solid fa-coins" style="color: #d97706; font-size: 15px;"></i>
                        <span>Base Pricing &amp; Stock Inventory</span>
                    </div>
                    <div id="pricing-savings-badge" class="pricing-savings-badge" style="display: none;">
                        <i class="fa-solid fa-bolt"></i>
                        <span id="pricing-savings-text">20% OFF</span>
                    </div>
                </div>

                <div class="pricing-stock-grid">
                    <!-- Base Selling Price -->
                    <div class="pricing-field-col">
                        <div class="pricing-label-row">
                            <label class="form-label">
                                <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                                Base Selling Price <span style="color: #dc2626;">*</span>
                            </label>
                            <span class="price-chip-tag customer-pays">Customer Pays</span>
                        </div>
                        <div class="currency-input-group">
                            <span class="currency-addon">₹</span>
                            <input type="number" step="0.01" name="price" id="base_price_field" class="premium-input currency-input" value="<?= old('price', $product['price']) ?>" required placeholder="0.00" oninput="calculatePriceSavings()">
                        </div>
                        <small class="field-hint">Final checkout price paid by customer</small>
                    </div>

                    <!-- Base Original Price (MRP) -->
                    <div class="pricing-field-col">
                        <div class="pricing-label-row">
                            <label class="form-label">
                                <i class="fa-solid fa-tag" style="color: #64748b;"></i>
                                Original MRP <small style="color: #94a3b8; font-weight: 500;">(Optional)</small>
                            </label>
                            <span class="price-chip-tag strikethrough">Strikethrough</span>
                        </div>
                        <div class="currency-input-group">
                            <span class="currency-addon">₹</span>
                            <input type="number" step="0.01" name="original_price" id="original_price_field" class="premium-input currency-input" value="<?= old('original_price', $product['original_price']) ?>" placeholder="0.00" oninput="calculatePriceSavings()">
                        </div>
                        <small class="field-hint">Used to calculate discount % badge</small>
                    </div>

                    <?php if(isset($gst_enabled) && $gst_enabled): ?>
                    <!-- GST Rate -->
                    <div class="pricing-field-col">
                        <div class="pricing-label-row">
                            <label class="form-label">
                                <i class="fa-solid fa-receipt" style="color: #e11d48;"></i>
                                GST Rate
                            </label>
                            <span class="price-chip-tag tax-tag">Tax</span>
                        </div>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-percent input-icon-prefix"></i>
                            <select name="gst_rate" class="premium-input has-prefix">
                                <option value="">No GST</option>
                                <?php foreach($gst_rates as $rate): ?>
                                    <option value="<?= esc($rate['rate']) ?>" <?= old('gst_rate', $product['gst_rate']) == $rate['rate'] ? 'selected' : '' ?>><?= esc(floatval($rate['rate'])) ?>%</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <small class="field-hint">Applicable tax rate</small>
                    </div>
                    <?php endif; ?>

                    <!-- Stock Quantity -->
                    <div class="pricing-field-col">
                        <div class="pricing-label-row">
                            <label class="form-label">
                                <i class="fa-solid fa-cubes-stacked" style="color: #2563eb;"></i>
                                Stock Quantity <span style="color: #dc2626;">*</span>
                            </label>
                            <span id="stock-status-pill" class="stock-status-pill in-stock">In Stock</span>
                        </div>
                        <div class="stock-input-group">
                            <input type="number" name="stock_quantity" id="stock_quantity_field" class="premium-input" value="<?= old('stock_quantity', $product['stock_quantity'] ?? 10) ?>" required min="0" oninput="updateStockStatusPill(this.value)">
                            <div class="stock-quick-buttons">
                                <button type="button" class="btn-stock-quick" onclick="quickSetStock(5)" title="Set 5">5</button>
                                <button type="button" class="btn-stock-quick" onclick="quickSetStock(10)" title="Set 10">10</button>
                                <button type="button" class="btn-stock-quick" onclick="quickSetStock(25)" title="Set 25">25</button>
                                <button type="button" class="btn-stock-quick" onclick="quickSetStock(50)" title="Set 50">50</button>
                            </div>
                        </div>
                        <small class="field-hint">Initial inventory units available for sale</small>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label class="form-label" style="margin-bottom: 0;">Product Description</label>
                    <div style="display: flex; gap: 6px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertDescFormat('desc-textarea', '**', '**')" style="padding: 2px 8px; font-size: 11px; font-weight: 700; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer;"><strong>B</strong> Bold</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertDescFormat('desc-textarea', '- ', '')" style="padding: 2px 8px; font-size: 11px; font-weight: 700; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer;">• Bullet</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertDescExample('desc-textarea')" style="padding: 2px 8px; font-size: 11px; font-weight: 700; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; color: #e11d48;">+ Load Template</button>
                    </div>
                </div>
                <textarea id="desc-textarea" name="description" class="premium-input" rows="6" placeholder="Example format:&#10;**Special Discount Channel Offer on Trendy Designer Fancy Silk Saree**&#10;&#10;- Premium Quality Product&#10;- Fast Delivery Available&#10;- Blouse Attached (0.8 Meters)"><?= old('description', $product['description'] ?? '') ?></textarea>
                <small style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Supports **Bold Heading** and bullet points starting with - or •. Displayed cleanly on the product detail page.</small>
            </div>
        
                <!-- Step Navigation Footer -->
                <div class="section-nav-footer">
                    <span style="font-size: 12.5px; color: #64748b;"><i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> Step 1 of <?= $measurements_enabled ? '6' : '5' ?>: Basic details &amp; inventory</span>
                    <button type="button" class="btn-nav-step btn-nav-next" onclick="nextProductSection('section-basic')">
                        <span>Continue to <?= $measurements_enabled ? 'Measurements' : 'Variants' ?></span> <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
</div></div>

<div id="section-specs" class="product-form-pane" style="display: none; <?= !$measurements_enabled ? 'display: none !important;' : '' ?>">
<?php if ($measurements_enabled): ?>
<!-- Saree & Blouse Measurements & Specifications Card -->
        <div class="premium-card">
            <div class="card-section-header">
                <div class="card-section-title-wrap">
                    <span class="card-section-icon-badge" style="background: linear-gradient(135deg, #fdfbf7 0%, #fef3c7 100%); color: #e11d48; border-color: #f3ebd9;">
                        <i class="fa-solid fa-ruler-combined"></i>
                    </span>
                    <div>
                        <h3>Saree &amp; Blouse Measurements</h3>
                        <p>Drape dimensions, blouse attachment specifications, highlights, and size chart</p>
                    </div>
                </div>
                <span class="required-indicator-pill" style="color: #e11d48; background: #fff5f7; border-color: #fecdd3;">
                    <i class="fa-solid fa-circle-info" style="color: #e11d48;"></i> Product Detail Specs
                </span>
            </div>

            <!-- Dimensions & Blouse Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-bottom: 20px;">
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        <i class="fa-solid fa-arrows-left-right" style="color: #e11d48; margin-right: 4px;"></i> Saree Length
                    </label>
                    <input type="text" name="saree_length" class="premium-input" value="<?= old('saree_length', $product['saree_length'] ?? '6.30 Meters (Including Blouse)') ?>" placeholder="e.g. 6.30 Meters (Including Blouse)">
                    <small style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">e.g. 6.30 Meters (Including Blouse)</small>
                </div>

                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        <i class="fa-solid fa-arrows-up-down" style="color: #e11d48; margin-right: 4px;"></i> Height
                    </label>
                    <input type="text" name="saree_height" class="premium-input" value="<?= old('saree_height', $product['saree_height'] ?? '44 to 47 inches') ?>" placeholder="e.g. 44 to 47 inches">
                    <small style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">e.g. 44 to 47 inches</small>
                </div>

                <div id="dhoti_field_wrapper" style="display: <?= $isCombo ? 'block' : 'none' ?>;">
                    <label class="form-label" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        <i class="fa-solid fa-vest" style="color: #e11d48; margin-right: 4px;"></i> Dhoti Dimensions / Option
                        <span style="font-size: 10px; color: #d97706; background: #fef3c7; border: 1px solid #fde68a; padding: 1px 6px; border-radius: 4px; margin-left: 4px; font-weight: 700;">Combo Only</span>
                    </label>
                    <input type="text" name="dhoti_length" list="dhoti-preset-options" class="premium-input" value="<?= old('dhoti_length', $product['dhoti_length'] ?? '') ?>" placeholder="e.g. 8 Muzham (Double Dhoti)">
                    <small style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">e.g. 4 Muzham, 8 Muzham, 9x5 Set, 4.0M</small>
                    <datalist id="dhoti-preset-options">
                        <option value="4 Muzham (Single Dhoti)">
                        <option value="8 Muzham (Double Dhoti)">
                        <option value="9x5 (Dhoti + Angavastram Set)">
                        <option value="10x6 (Pattu Dhoti + Shawl Set)">
                        <option value="2.0 Meters (Single Dhoti)">
                        <option value="4.0 Meters (Double Dhoti)">
                        <option value="Readymade / Pocket Dhoti">
                    </datalist>
                </div>

                <?php
                    $currentBlouse = old('blouse_length', $product['blouse_length'] ?? '');
                    $isBlouseAttached = !empty($currentBlouse) && !in_array(strtolower(trim($currentBlouse)), ['no', 'none', 'without blouse', 'not attached', 'non-attached', '0', '0m', 'false']);
                    $isCustomBlouse = $isBlouseAttached && !in_array($currentBlouse, ['0.8 Meters', '1.0 Meter', '0.75 Meters']) && strpos(strtolower($currentBlouse), 'running') === false;
                ?>
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        <i class="fa-solid fa-scissors" style="color: #e11d48; margin-right: 4px;"></i> Blouse Attached Meter
                    </label>
                    <select id="blouse_dropdown_selector" class="premium-input" onchange="handleBlouseDropdownChange(this.value)" style="cursor: pointer; font-weight: 600; font-size: 13px;">
                        <option value="0.8 Meters" <?= ($currentBlouse === '0.8 Meters' || ($isBlouseAttached && strpos($currentBlouse, '0.8') !== false)) ? 'selected' : '' ?>>Attached (0.8 Meters)</option>
                        <option value="1.0 Meter" <?= ($currentBlouse === '1.0 Meter' || ($isBlouseAttached && strpos($currentBlouse, '1.0') !== false)) ? 'selected' : '' ?>>Attached (1.0 Meter)</option>
                        <option value="0.75 Meters" <?= ($currentBlouse === '0.75 Meters') ? 'selected' : '' ?>>Attached (0.75 Meters)</option>
                        <option value="Running Blouse" <?= (strpos(strtolower($currentBlouse), 'running') !== false) ? 'selected' : '' ?>>Attached (Running Blouse Piece)</option>
                        <option value="custom" <?= $isCustomBlouse ? 'selected' : '' ?>>Custom Meter / Text...</option>
                        <option value="none" <?= !$isBlouseAttached ? 'selected' : '' ?>>Non-Attached (Without Blouse)</option>
                    </select>
                    <input type="text" id="blouse_custom_input" class="premium-input" placeholder="Type custom blouse meter (e.g. 1.25 Meters)" value="<?= esc($currentBlouse) ?>" style="margin-top: 6px; display: <?= $isCustomBlouse ? 'block' : 'none' ?>;" oninput="document.getElementById('blouse_length_hidden').value = this.value">
                    <input type="hidden" name="blouse_length" id="blouse_length_hidden" value="<?= esc($isBlouseAttached ? $currentBlouse : '') ?>">
                    <input type="hidden" name="blouse_attached_status" id="blouse_attached_status" value="<?= $isBlouseAttached ? '1' : '0' ?>">
                    <small id="blouse_hint_text" style="font-size: 11px; color: <?= $isBlouseAttached ? '#16a34a' : '#ef4444' ?>; margin-top: 4px; display: block; font-weight: 600;">
                        <?= $isBlouseAttached ? '✓ Attached blouse active' : '✗ Non-Attached (Without Blouse)' ?>
                    </small>
                </div>
            </div>

            <!-- Highlights -->
            <div style="margin-bottom: 24px;">
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    <i class="fa-solid fa-star" style="color: #f59e0b; margin-right: 4px;"></i> Product Highlights / Key Features
                </label>
                <textarea name="highlights" class="premium-input" rows="3" placeholder="Enter key highlights (one per line or bullet point), e.g.:&#10;• Authentic Handloom &amp; Powerloom Weaving&#10;• High Quality Zari Border with Rich Pallu&#10;• Attached Running Blouse Piece&#10;• Lightweight and Comfortable Drape"><?= old('highlights', $product['highlights'] ?? '') ?></textarea>
                <small style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Each highlight point will be displayed prominently on the product detail page.</small>
            </div>

            <!-- Measurement Charts: Blouse & Dhoti -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                <!-- Blouse Measurement Chart -->
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 13px; font-weight: 700; color: #1e293b; display: flex; align-items: center; justify-content: space-between;">
                        <span>
                            <i class="fa-solid fa-scissors" style="color: #e11d48; margin-right: 6px;"></i> Blouse Measurement Chart
                        </span>
                    </label>
                    <?php if(!empty($product['blouse_measurement_image'])): ?>
                        <div style="margin-bottom: 10px; border-radius: 8px; overflow: hidden; display: inline-block; border: 1px solid #e2e8f0;">
                            <img src="<?= base_url($product['blouse_measurement_image']) ?>" style="height: 100px; object-fit: cover; display: block;">
                        </div>
                    <?php endif; ?>
                    <div class="premium-file-upload" style="padding: 16px;">
                        <div class="upload-icon" style="font-size: 20px;"><i class="fa-solid fa-ruler-combined"></i></div>
                        <div class="upload-text" style="font-size: 12px;"><?= !empty($product['blouse_measurement_image']) ? 'Upload new blouse chart to replace' : 'Upload Blouse size chart image' ?></div>
                        <div class="upload-subtext" style="font-size: 10px;">Click to browse image</div>
                        <input type="file" name="blouse_measurement_image" accept="image/*" onchange="handleFileSelectNotice(this)">
                    </div>
                </div>

                <!-- Dhoti Measurement / Size Chart Image -->
                <div class="form-group" id="dhoti_chart_wrapper" style="margin-bottom: 0; display: <?= $isCombo ? 'block' : 'none' ?>;">
                    <label class="form-label" style="font-size: 13px; font-weight: 700; color: #1e293b; display: flex; align-items: center; justify-content: space-between;">
                        <span>
                            <i class="fa-solid fa-vest" style="color: #e11d48; margin-right: 6px;"></i> Dhoti Size / Measurement Chart Image
                            <span style="font-size: 10px; color: #d97706; background: #fef3c7; border: 1px solid #fde68a; padding: 1px 6px; border-radius: 4px; margin-left: 4px; font-weight: 700;">Combo Only</span>
                        </span>
                    </label>
                    <?php if(!empty($product['dhoti_measurement_image'])): ?>
                        <div style="margin-bottom: 10px; border-radius: 8px; overflow: hidden; display: inline-block; border: 1px solid #e2e8f0;">
                            <img src="<?= base_url($product['dhoti_measurement_image']) ?>" style="height: 100px; object-fit: cover; display: block;">
                        </div>
                    <?php endif; ?>
                    <div class="premium-file-upload" style="padding: 16px;">
                        <div class="upload-icon" style="font-size: 20px;"><i class="fa-solid fa-ruler-combined"></i></div>
                        <div class="upload-text" style="font-size: 12px;"><?= !empty($product['dhoti_measurement_image']) ? 'Upload new Dhoti chart to replace' : 'Upload Dhoti measurement image / chart' ?></div>
                        <div class="upload-subtext" style="font-size: 10px;">Click to browse image</div>
                        <input type="file" name="dhoti_measurement_image" accept="image/*" onchange="handleFileSelectNotice(this)">
                    </div>
                </div>
            </div>
        
                <!-- Step Navigation Footer -->
                <div class="section-nav-footer">
                    <button type="button" class="btn-nav-step btn-nav-prev" onclick="prevProductSection('section-specs')">
                        <i class="fa-solid fa-arrow-left"></i> <span>Back: Basic Info</span>
                    </button>
                    <button type="button" class="btn-nav-step btn-nav-next" onclick="nextProductSection('section-specs')">
                        <span>Continue to Variants</span> <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
</div>
<?php endif; ?>
</div>

<div id="section-variants" class="product-form-pane" style="display: none;">
<!-- Product Variants Container -->
        <div class="premium-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <h3 class="section-title" style="margin-bottom: 0;"><i class="fa-solid fa-layer-group"></i> Product Variants</h3>
                <button type="button" id="add-variant-btn" class="btn-sm btn-add-variant-top">
                    <i class="fa-solid fa-plus"></i> Add Variant
                </button>
            </div>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 14px; line-height: 1.5;">Add variations like different Colors and Sizes (e.g. Dhoti 4M / 8M, Shirt 38-44, or Saree Free Size). If you specify variant prices here, they will override the base product price when the customer selects that option. Upload variant-specific images so the storefront switches photos when customer clicks that option.</p>
            
            <!-- Attribute Presets Quick Palette from Settings -->
            <div class="attribute-presets-palette" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; margin-bottom: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                    <span style="font-size: 12.5px; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-swatchbook" style="color: #e11d48;"></i> Color &amp; Size Attribute Presets
                    </span>
                    <a href="<?= base_url('admin/settings?section=attributes') ?>" target="_blank" style="font-size: 12px; color: #e11d48; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fa-solid fa-gear"></i> Manage Attributes in Settings <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                    </a>
                </div>

                <!-- Quick Color Chips -->
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 8px; text-transform: uppercase;">
                        Click any color below to quickly add a new variant with that shade:
                    </div>
                    <div style="display: flex; gap: 7px; flex-wrap: wrap;" id="attr-quick-color-chips">
                        <?php foreach($configuredColors as $cOpt): ?>
                            <button type="button" 
                                    class="btn-attr-color-chip" 
                                    onclick="addVariantRow('<?= esc($cOpt['name']) ?>', '<?= esc($cOpt['code']) ?>')"
                                    title="Add <?= esc($cOpt['name']) ?> variant"
                                    style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; background: #ffffff; border: 1.5px solid #cbd5e1; font-size: 12px; font-weight: 700; color: #1e293b; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <span style="width: 13px; height: 13px; border-radius: 50%; background-color: <?= esc($cOpt['code']) ?>; border: 1px solid rgba(0,0,0,0.2); display: inline-block;"></span>
                                <span><?= esc($cOpt['name']) ?></span>
                                <i class="fa-solid fa-plus" style="font-size: 9px; color: #e11d48;"></i>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Variant Cards Container -->
            <div class="variant-cards-container" id="variants-container">
                <?php if(!empty($variants)): ?>
                    <?php foreach($variants as $idx => $v): 
                        $cCode = !empty($v['color_code']) ? $v['color_code'] : '#e11d48';
                        $cName = $v['color_name'] ?? '';
                        $curSize = $v['size'] ?? '';
                        $vImages = !empty($v['images']) ? $v['images'] : [];
                        $serializedImages = json_encode(array_map(function($img) {
                            return ['id' => $img['id'], 'url' => base_url($img['media_url'])];
                        }, $vImages));
                    ?>
                        <div class="variant-card" data-index="<?= $idx ?>" data-existing-images='<?= esc($serializedImages) ?>'>
                            <!-- Card Header -->
                            <div class="variant-card-header">
                                <div class="variant-card-header-left">
                                    <div class="variant-num-badge">#<?= $idx + 1 ?></div>
                                    <div class="variant-header-swatch" style="background-color: <?= esc($cCode) ?>;"></div>
                                    <div class="variant-header-title-wrap">
                                        <div class="variant-header-title"><?= esc(!empty($v['name']) ? $v['name'] : (!empty($cName) ? $cName : 'Variant #' . ($idx + 1))) ?></div>
                                        <div class="variant-header-meta">
                                            <span class="meta-tag color-tag"><i class="fa-solid fa-palette"></i> <?= esc($cName ?: 'No Color') ?></span>
                                            <span class="meta-tag size-tag"><i class="fa-solid fa-ruler"></i> <?= esc($curSize ?: 'Free Size') ?></span>
                                            <span class="meta-tag stock-tag <?= ($v['stock'] ?? 0) <= 0 ? 'out' : '' ?>"><i class="fa-solid fa-boxes-stacked"></i> <?= esc($v['stock'] ?? ($v['stock_quantity'] ?? 0)) ?> in stock</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="variant-card-header-right">
                                    <button type="button" class="btn-variant-media" data-index="<?= $idx ?>">
                                        <i class="fa-solid fa-images"></i> Photos <span class="media-badge"><?= count($vImages) ?></span>
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
                                        <div class="color-picker-bubble" style="background-color: <?= esc($cCode) ?>;" title="Choose color">
                                            <input type="color" name="variant_color_codes[]" value="<?= esc($cCode) ?>" class="color-picker-input">
                                        </div>
                                        <input type="text" name="variant_color_names[]" list="variant-color-options" class="premium-input color-name-input" value="<?= esc($cName) ?>" placeholder="Color (e.g. Pink)" required>
                                        <span class="color-hex-badge"><?= esc($cCode) ?></span>
                                        <input type="hidden" name="variant_names[]" class="variant-combined-name" value="<?= esc($v['name'] ?? $cName) ?>">
                                        <input type="hidden" name="variant_ids[]" value="<?= esc($v['id']) ?>">
                                        <input type="hidden" name="variant_statuses[]" value="<?= esc($v['status'] ?? 1) ?>">
                                    </div>
                                </div>

                                <div class="variant-field-group variant-field-size">
                                    <label class="variant-field-label"><i class="fa-solid fa-ruler-combined"></i> Size / Dhoti Option</label>
                                    <div class="row-size-multi-wrap">
                                        <div class="row-size-multi-trigger" tabindex="0" role="button" aria-haspopup="true">
                                            <i class="fa-solid fa-ruler-horizontal size-icon"></i>
                                            <span class="row-size-text <?= empty($curSize) ? 'placeholder' : '' ?>"><?= esc(!empty($curSize) ? $curSize : 'Select Size(s)...') ?></span>
                                            <i class="fa-solid fa-chevron-down size-caret"></i>
                                        </div>
                                        <input type="hidden" name="variant_sizes[]" class="variant-size-input" value="<?= esc($curSize) ?>">
                                        
                                        <div class="row-size-multi-dropdown">
                                            <div class="row-size-dd-header">
                                                <label class="row-select-all-label">
                                                    <input type="checkbox" class="row-select-all-cb">
                                                    <span>Select All</span>
                                                </label>
                                                <button type="button" class="row-clear-btn">Clear</button>
                                            </div>
                                            
                                            <div class="row-size-dd-list">
                                                <?php if(!empty($configuredSizes)): ?>
                                                    <?php 
                                                        $grouped = [];
                                                        foreach($configuredSizes as $s) {
                                                            $grp = !empty($s['category']) ? $s['category'] : 'General';
                                                            if (!isset($grouped[$grp])) $grouped[$grp] = [];
                                                            array_push($grouped[$grp], $s['name']);
                                                        }
                                                        foreach($grouped as $grp => $items):
                                                            $icon = $grp === 'Apparel' ? '👔' : ($grp === 'Dhoti' ? '🥻' : ($grp === 'Saree' ? '📐' : '🏷️'));
                                                    ?>
                                                        <div class="dd-group-title"><?= $icon ?> <?= esc($grp) ?></div>
                                                        <?php foreach($items as $sz): ?>
                                                            <label class="dd-opt-label"><input type="checkbox" value="<?= esc($sz) ?>"> <span><?= esc($sz) ?></span></label>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="dd-group-title">👔 Clothing / Apparel</div>
                                                    <label class="dd-opt-label"><input type="checkbox" value="S"> <span>S</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="M"> <span>M</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="L"> <span>L</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="XL"> <span>XL</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="XXL"> <span>XXL</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="3XL"> <span>3XL</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="Free Size"> <span>Free Size</span></label>
                                                    <div class="dd-group-title">🥻 Dhoti Options</div>
                                                    <label class="dd-opt-label"><input type="checkbox" value="4 Muzham (Single Dhoti)"> <span>4 Muzham (Single Dhoti)</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="8 Muzham (Double Dhoti)"> <span>8 Muzham (Double Dhoti)</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="9x5 (Dhoti + Angavastram Set)"> <span>9x5 (Dhoti + Angavastram Set)</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="10x6 (Pattu Dhoti + Shawl Set)"> <span>10x6 (Pattu Dhoti + Shawl Set)</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="2.0 Meters (Single Dhoti)"> <span>2.0 Meters (Single Dhoti)</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="4.0 Meters (Double Dhoti)"> <span>4.0 Meters (Double Dhoti)</span></label>
                                                    <label class="dd-opt-label"><input type="checkbox" value="Readymade / Pocket Dhoti"> <span>Readymade / Pocket Dhoti</span></label>
                                                <?php endif; ?>
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
                                        <input type="number" step="0.01" name="variant_sale_prices[]" class="premium-input variant-price-input" value="<?= esc($v['sale_price']) ?>" placeholder="0.00" required>
                                    </div>
                                </div>

                                <div class="variant-field-group variant-field-mrp">
                                    <label class="variant-field-label"><i class="fa-solid fa-tags"></i> MRP (Strikethrough)</label>
                                    <div class="input-prefix-wrapper">
                                        <span class="input-prefix">₹</span>
                                        <input type="number" step="0.01" name="variant_original_prices[]" class="premium-input" value="<?= esc($v['original_price']) ?>" placeholder="0.00">
                                    </div>
                                </div>

                                <div class="variant-field-group variant-field-stock">
                                    <label class="variant-field-label"><i class="fa-solid fa-cubes"></i> Stock Qty</label>
                                    <input type="number" name="variant_stocks[]" class="premium-input variant-stock-input" value="<?= esc($v['stock'] ?? ($v['stock_quantity'] ?? 0)) ?>" min="0" placeholder="10">
                                </div>

                                <?php if ($sku_enabled): ?>
                                <div class="variant-field-group variant-field-sku">
                                    <label class="variant-field-label"><i class="fa-solid fa-barcode"></i> SKU</label>
                                    <input type="text" name="variant_skus[]" class="premium-input" value="<?= esc($v['sku'] ?? '') ?>" placeholder="e.g. SLV-PNK-01">
                                </div>
                                <?php else: ?>
                                    <input type="hidden" name="variant_skus[]" value="<?= esc($v['sku'] ?? '') ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Predefined Datalist for Size / Dhoti Suggestions -->
            <!-- Predefined Datalist for Color Suggestions -->
            <datalist id="variant-color-options">
                <?php if(!empty($configuredColors)): ?>
                    <?php foreach($configuredColors as $cOpt): ?>
                        <option value="<?= esc($cOpt['name']) ?>" data-hex="<?= esc($cOpt['code']) ?>"><?= esc($cOpt['name']) ?> (<?= esc($cOpt['code']) ?>)</option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="Red" data-hex="#dc2626">
                    <option value="Maroon" data-hex="#800020">
                    <option value="Pink" data-hex="#ec4899">
                    <option value="Blue" data-hex="#2563eb">
                    <option value="Navy" data-hex="#1e3a8a">
                    <option value="Green" data-hex="#16a34a">
                    <option value="Teal" data-hex="#0d9488">
                    <option value="Yellow" data-hex="#eab308">
                    <option value="Orange" data-hex="#ea580c">
                    <option value="Gold" data-hex="#d97706">
                    <option value="Purple" data-hex="#9333ea">
                    <option value="Lavender" data-hex="#c084fc">
                    <option value="Black" data-hex="#18181b">
                    <option value="Cream" data-hex="#fef08a">
                    <option value="White" data-hex="#ffffff">
                <?php endif; ?>
            </datalist>

            <script>
                window.CONFIGURED_ATTRIBUTE_COLORS = <?= json_encode(!empty($configuredColors) ? array_values($configuredColors) : []) ?>;
                window.CONFIGURED_ATTRIBUTE_SIZES = <?= json_encode(!empty($configuredSizes) ? array_values($configuredSizes) : []) ?>;
            </script>

            <datalist id="variant-size-options">
                <?php if(!empty($configuredSizes)): ?>
                    <?php foreach($configuredSizes as $sOpt): ?>
                        <option value="<?= esc($sOpt['name']) ?>">
                    <?php endforeach; ?>
                <?php else: ?>
                <option value="Free Size">
                <option value="4 Muzham (Single Dhoti)">
                <option value="8 Muzham (Double Dhoti)">
                <option value="9x5 (Dhoti + Angavastram Set)">
                <option value="10x6 (Pattu Dhoti + Shawl Set)">
                <option value="38 (S)">
                <option value="40 (M)">
                <option value="42 (L)">
                <option value="44 (XL)">
                <option value="46 (XXL)">
                <?php endif; ?>
            </datalist>
            
            <button type="button" id="add-variant-btn-bottom" class="btn-add-variant"><i class="fa-solid fa-plus"></i> Add Another Variant</button>
        </div>

                    <!-- Step Navigation Footer for Section Variants -->
            <div class="section-nav-footer">
                <button type="button" class="btn-nav-step btn-nav-prev" onclick="prevProductSection('section-variants')">
                    <i class="fa-solid fa-arrow-left"></i> <span>Back: <?= $measurements_enabled ? 'Measurements' : 'Basic Info' ?></span>
                </button>
                <button type="button" class="btn-nav-step btn-nav-next" onclick="nextProductSection('section-variants')">
                    <span>Continue to Media &amp; Videos</span> <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div></div>

<div id="section-media" class="product-form-pane" style="display: none;">
<input type="hidden" name="deleted_media_ids" id="deleted-media-ids" value="">
        <input type="hidden" name="primary_image_from_gallery" id="primary-image-from-gallery" value="">

        <div class="premium-card">
            <h3 class="section-title"><i class="fa-solid fa-images"></i> Product Media</h3>

            <!-- 1. Primary Image -->
            <div class="form-group media-upload-box">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Primary Image</span>
                    <small style="color: #64748b; font-weight: normal;">Main image displayed on catalog &amp; product page</small>
                </label>

                <?php 
                    $hasPrimaryImage = !empty($product['image_url']);
                    $primaryImgSrc = $hasPrimaryImage ? base_url($product['image_url']) : '';
                ?>
                <div id="primary-image-preview-card" class="primary-preview-card" style="<?= $hasPrimaryImage ? 'display: flex;' : 'display: none;' ?>">
                    <div class="preview-img-wrapper">
                        <img id="primary-image-preview" src="<?= esc($primaryImgSrc) ?>" alt="Primary Preview">
                    </div>
                    <div class="preview-actions">
                        <span class="preview-badge"><i class="fa-solid fa-star"></i> Primary Image</span>
                        <span id="primary-image-filename" class="preview-filename"><?= esc(basename($product['image_url'] ?? '')) ?></span>
                        <div class="action-btn-group">
                            <button type="button" class="btn-sm btn-outline-primary" id="btn-change-primary">
                                <i class="fa-solid fa-arrows-rotate"></i> Change Image
                            </button>
                            <button type="button" class="btn-sm btn-outline-danger" id="btn-remove-primary">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>

                <div class="premium-file-upload" id="primary-upload-dropzone" style="<?= $hasPrimaryImage ? 'display: none;' : 'display: block;' ?>">
                    <div class="upload-icon"><i class="fa-solid fa-image"></i></div>
                    <div class="upload-text">Upload primary product image</div>
                    <div class="upload-subtext">Click to browse or drag &amp; drop (JPG, PNG, WEBP — Max 10MB)</div>
                    <input type="file" name="image" id="primary-image-input" accept="image/jpeg,image/png,image/webp">
                </div>
            </div>

            <!-- 2. Additional Product Images / Gallery -->
            <div class="form-group media-upload-box" style="margin-top: 25px;">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Additional Images / Product Gallery</span>
                    <small style="color: #64748b; font-weight: normal;">Upload multiple images (JPG, PNG, WEBP)</small>
                </label>

                <!-- Existing Gallery Images -->
                <div id="gallery-preview-container" class="gallery-preview-grid" style="<?= !empty($galleryImages) ? 'display: grid;' : 'display: none;' ?>">
                    <?php if(!empty($galleryImages)): ?>
                        <?php foreach($galleryImages as $idx => $gImg): 
                            $isPrimary = ($product['image_url'] === $gImg['media_url']);
                        ?>
                            <div class="gallery-card existing-card <?= $isPrimary ? 'is-primary-card' : '' ?>" data-id="<?= $gImg['id'] ?>">
                                <div class="gallery-thumb-wrap">
                                    <span class="gallery-order-badge">#<?= $idx + 1 ?></span>
                                    <?php if($isPrimary): ?>
                                        <span class="gallery-primary-badge"><i class="fa-solid fa-star"></i> Primary</span>
                                    <?php endif; ?>
                                    <img src="<?= base_url($gImg['media_url']) ?>" alt="Gallery image">
                                </div>
                                <div class="gallery-card-actions">
                                    <button type="button" class="btn-card-action btn-existing-set-primary" data-id="<?= $gImg['id'] ?>" data-url="<?= base_url($gImg['media_url']) ?>" data-relative-url="<?= esc($gImg['media_url']) ?>" title="Set as Primary">
                                        <i class="fa-solid fa-star"></i> Primary
                                    </button>
                                    <button type="button" class="btn-card-action btn-del btn-existing-delete" data-id="<?= $gImg['id'] ?>" title="Delete image">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="premium-file-upload" id="gallery-upload-dropzone" style="margin-top: 15px;">
                    <div class="upload-icon"><i class="fa-solid fa-images"></i></div>
                    <div class="upload-text">Upload more additional images (Multiple selection allowed)</div>
                    <div class="upload-subtext">Drag &amp; drop images here or click to browse</div>
                    <input type="file" name="gallery_images[]" id="gallery-images-input" multiple accept="image/jpeg,image/png,image/webp">
                </div>
            </div>

            <!-- 3. Dedicated Product Video -->
            <div class="form-group media-upload-box" style="margin-top: 25px;">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Product Video (Optional)</span>
                    <small style="color: #64748b; font-weight: normal;">Supported: MP4, WEBM (Max 50MB) — Preview with controls</small>
                </label>

                <?php 
                    $hasVideo = !empty($productVideo);
                    $videoSrc = $hasVideo ? base_url($productVideo['media_url']) : '';
                ?>
                <div id="video-preview-card" class="video-preview-card" style="<?= $hasVideo ? 'display: flex;' : 'display: none;' ?>">
                    <video id="product-video-preview" controls muted playsinline class="video-preview-player" src="<?= esc($videoSrc) ?>"></video>
                    <div class="video-preview-actions">
                        <span class="preview-badge video-badge"><i class="fa-solid fa-video"></i> Product Video</span>
                        <span id="video-filename" class="preview-filename"><?= $hasVideo ? esc(basename($productVideo['media_url'])) : '' ?></span>
                        <div class="action-btn-group">
                            <button type="button" class="btn-sm btn-outline-primary" id="btn-change-video">
                                <i class="fa-solid fa-arrows-rotate"></i> Replace Video
                            </button>
                            <button type="button" class="btn-sm btn-outline-danger" id="btn-remove-video" data-existing-id="<?= $hasVideo ? $productVideo['id'] : '' ?>">
                                <i class="fa-solid fa-trash"></i> Remove Video
                            </button>
                        </div>
                    </div>
                </div>

                <div class="premium-file-upload" id="video-upload-dropzone" style="<?= $hasVideo ? 'display: none;' : 'display: block;' ?>">
                    <div class="upload-icon"><i class="fa-solid fa-film"></i></div>
                    <div class="upload-text">Upload product video file</div>
                    <div class="upload-subtext">Click to browse or drag &amp; drop MP4/WEBM video (No autoplay sound)</div>
                    <input type="file" name="product_video" id="product-video-input" accept="video/mp4,video/webm">
                </div>
            </div>

            <!-- YouTube Video & Shorts Showcase Uploader -->
            <div class="form-group" style="background: #fff8f8; border: 1.5px solid #fee2e2; border-radius: 12px; padding: 20px; margin-top: 20px;">
                <label class="form-label" style="font-weight: 800; color: #dc2626; font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-brands fa-youtube" style="font-size: 20px;"></i> YouTube Video &amp; Shorts Uploader
                </label>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">Select which YouTube Channel tab from "Save Channel Tabs" settings should showcase this product/video:</p>
                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="shorts_channel" style="font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-list-check" style="color: #ef4444;"></i> Select YouTube Channel Tab
                    </label>
                    <div style="position: relative;">
                        <select name="shorts_channel" id="shorts_channel" class="premium-input" style="padding-left: 40px; cursor: pointer; font-weight: 600; font-size: 14px;">
                            <option value="">None</option>
                            <?php 
                                $currentSelectedChannel = old('shorts_channel', $product['shorts_channel'] ?? '');
                                $channelFound = false;
                                if(!empty($shortsChannels)): 
                                    foreach($shortsChannels as $ch): 
                                        $chName = trim($ch['name'] ?? '');
                                        if (empty($chName)) continue;
                                        if ($currentSelectedChannel === $chName) {
                                            $channelFound = true;
                                        }
                            ?>
                                        <option value="<?= esc($chName) ?>" <?= $currentSelectedChannel === $chName ? 'selected' : '' ?>>
                                            <?= esc($chName) ?>
                                        </option>
                            <?php 
                                    endforeach; 
                                endif; 
                            ?>
                            <?php if(!empty($currentSelectedChannel) && !$channelFound): ?>
                                <!-- Safe fallback if associated channel was deleted or modified in settings -->
                                <option value="<?= esc($currentSelectedChannel) ?>" selected>
                                    <?= esc($currentSelectedChannel) ?> (Archived / Inactive)
                                </option>
                            <?php endif; ?>
                        </select>
                        <i class="fa-brands fa-youtube" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 17px; pointer-events: none;"></i>
                    </div>
                    <span style="font-size: 12px; color: #94a3b8; margin-top: 5px; display: block;">
                        Options are loaded dynamically from <a href="<?= base_url('admin/settings/channel-tabs') ?>" target="_blank" style="color: #e11d48; font-weight: 700; text-decoration: underline;">Save Channel Tabs</a> settings.
                    </span>
                </div>

                <div style="margin-bottom: 0;">
                    <label class="form-label" style="font-weight: 700; font-size: 13px;">YouTube Shorts / Video URL</label>
                    <div style="position: relative;">
                        <input type="url" name="shorts_url" id="admin_shorts_url" class="premium-input" value="<?= old('shorts_url', $product['shorts_url'] ?? '') ?>" placeholder="e.g. https://www.youtube.com/shorts/XXXXXXX or https://youtu.be/XXXXXXX" style="padding-left: 40px;" oninput="handleShortsPreview(this.value)">
                        <i class="fa-brands fa-youtube" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 18px;"></i>
                    </div>
                    <span style="font-size: 12px; color: #94a3b8; margin-top: 4px; display: block;">When provided, customers can watch the product video directly from the homepage Shorts showcase and catalog.</span>

                    <div id="youtube-shorts-preview-box" style="margin-top: 12px; display: none;">
                        <div style="font-size: 11.5px; font-weight: 700; color: #16a34a; margin-bottom: 6px; display: flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-circle-check"></i> Live YouTube Video Preview:
                        </div>
                        <div style="width: 340px; max-width: 100%; height: 580px; border-radius: 14px; overflow: hidden; border: 1.5px solid #bbf7d0; background: #000000; box-shadow: 0 8px 24px rgba(0,0,0,0.18);">
                            <iframe id="youtube-shorts-preview-iframe" style="width: 100%; height: 100%; border: 0;" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instagram Reels & Video Showcase Uploader -->
            <div class="form-group" style="background: #fdf2f8; border: 1.5px solid #fbcfe8; border-radius: 12px; padding: 20px; margin-top: 20px;">
                <label class="form-label" style="font-weight: 800; color: #be185d; font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-brands fa-instagram" style="font-size: 20px; color: #e1306c;"></i> Instagram Reels &amp; Video Uploader
                </label>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">Select which Instagram Account/Channel tab from "Save Channel Tabs" settings should showcase this product/reel:</p>
                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="instagram_channel" style="font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-list-check" style="color: #e1306c;"></i> Select Instagram Channel / Account Tab
                    </label>
                    <div style="position: relative;">
                        <select name="instagram_channel" id="instagram_channel" class="premium-input" style="padding-left: 40px; cursor: pointer; font-weight: 600; font-size: 14px;">
                            <option value="">None</option>
                            <?php 
                                $currentSelectedIgChannel = old('instagram_channel', $product['instagram_channel'] ?? '');
                                $igChannelFound = false;
                                $igTabs = !empty($instagramChannels) ? $instagramChannels : (!empty($allChannelTabs) ? $allChannelTabs : []);
                                if(!empty($igTabs)): 
                                    foreach($igTabs as $ch): 
                                        $chName = trim($ch['name'] ?? '');
                                        if (empty($chName)) continue;
                                        if ($currentSelectedIgChannel === $chName) {
                                            $igChannelFound = true;
                                        }
                            ?>
                                        <option value="<?= esc($chName) ?>" <?= $currentSelectedIgChannel === $chName ? 'selected' : '' ?>>
                                            <?= esc($chName) ?>
                                        </option>
                            <?php 
                                    endforeach; 
                                endif; 
                            ?>
                            <?php if(!empty($currentSelectedIgChannel) && !$igChannelFound): ?>
                                <option value="<?= esc($currentSelectedIgChannel) ?>" selected>
                                    <?= esc($currentSelectedIgChannel) ?> (Archived / Inactive)
                                </option>
                            <?php endif; ?>
                        </select>
                        <i class="fa-brands fa-instagram" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #e1306c; font-size: 18px; pointer-events: none;"></i>
                    </div>
                    <span style="font-size: 12px; color: #94a3b8; margin-top: 5px; display: block;">
                        Options are loaded dynamically from <a href="<?= base_url('admin/settings/channel-tabs') ?>" target="_blank" style="color: #e11d48; font-weight: 700; text-decoration: underline;">Save Channel Tabs</a> settings.
                    </span>
                </div>

                <div style="margin-bottom: 0;">
                    <label class="form-label" style="font-weight: 700; font-size: 13px;">Instagram Reel / Video URL</label>
                    <div style="position: relative;">
                        <input type="url" name="instagram_url" id="admin_instagram_url" class="premium-input" value="<?= old('instagram_url', $product['instagram_url'] ?? '') ?>" placeholder="e.g. https://www.instagram.com/reel/DFXXXXX/ or https://www.instagram.com/p/DFXXXXX/" style="padding-left: 40px;" oninput="handleInstagramPreview(this.value)">
                        <i class="fa-brands fa-instagram" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #e1306c; font-size: 18px;"></i>
                    </div>
                    <span style="font-size: 12px; color: #94a3b8; margin-top: 4px; display: block;">When provided, customers can tap to view the official Instagram Reel showcasing this saree.</span>
                    <div id="instagram-preview-hint" style="display: none; margin-top: 8px; font-size: 12px; color: #b91c1c; background: #fef2f2; border: 1px solid #fecaca; padding: 8px 12px; border-radius: 8px;"></div>

                    <div id="instagram-preview-box" style="margin-top: 12px; display: none;">
                        <div style="font-size: 11.5px; font-weight: 700; color: #db2777; margin-bottom: 6px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                            <span><i class="fa-solid fa-circle-check"></i> Live Instagram Reel / Video Preview:</span>
                            <span id="instagram-preview-account-badge" style="background: #fdf2f8; color: #be185d; border: 1px solid #fbcfe8; border-radius: 6px; padding: 2px 8px; font-size: 11px; font-weight: 700; display: none;"></span>
                        </div>
                        <div style="width: 440px; max-width: 100%; height: 720px; border-radius: 14px; overflow: hidden; border: 1.5px solid #fbcfe8; background: #ffffff; padding: 0; box-sizing: border-box; box-shadow: 0 8px 24px rgba(0,0,0,0.18);">
                            <iframe id="instagram-preview-iframe" style="width: 100%; height: 100%; border: 0;" scrolling="no" allowtransparency="true" allowfullscreen></iframe>
                        </div>
                        <div style="margin-top: 8px;">
                            <a href="#" id="instagram-preview-link" target="_blank" style="font-size: 12px; color: #be185d; font-weight: 600; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Reel in Instagram
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facebook Video & Reels Showcase Uploader -->
            <div class="form-group" style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 12px; padding: 20px; margin-top: 20px;">
                <label class="form-label" style="font-weight: 800; color: #1d4ed8; font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-brands fa-facebook" style="font-size: 20px; color: #1877f2;"></i> Facebook Video &amp; Reel Uploader
                </label>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">Select which Facebook Page/Channel tab from "Save Channel Tabs" settings should showcase this product/video:</p>
                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="facebook_channel" style="font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-list-check" style="color: #1877f2;"></i> Select Facebook Page / Channel Tab
                    </label>
                    <div style="position: relative;">
                        <select name="facebook_channel" id="facebook_channel" class="premium-input" style="padding-left: 40px; cursor: pointer; font-weight: 600; font-size: 14px;">
                            <option value="">None</option>
                            <?php 
                                $currentSelectedFbChannel = old('facebook_channel', $product['facebook_channel'] ?? '');
                                $fbChannelFound = false;
                                $fbTabs = !empty($facebookChannels) ? $facebookChannels : (!empty($allChannelTabs) ? $allChannelTabs : []);
                                if(!empty($fbTabs)): 
                                    foreach($fbTabs as $ch): 
                                        $chName = trim($ch['name'] ?? '');
                                        if (empty($chName)) continue;
                                        if ($currentSelectedFbChannel === $chName) {
                                            $fbChannelFound = true;
                                        }
                            ?>
                                        <option value="<?= esc($chName) ?>" <?= $currentSelectedFbChannel === $chName ? 'selected' : '' ?>>
                                            <?= esc($chName) ?>
                                        </option>
                            <?php 
                                    endforeach; 
                                endif; 
                            ?>
                            <?php if(!empty($currentSelectedFbChannel) && !$fbChannelFound): ?>
                                <option value="<?= esc($currentSelectedFbChannel) ?>" selected>
                                    <?= esc($currentSelectedFbChannel) ?> (Archived / Inactive)
                                </option>
                            <?php endif; ?>
                        </select>
                        <i class="fa-brands fa-facebook" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #1877f2; font-size: 18px; pointer-events: none;"></i>
                    </div>
                    <span style="font-size: 12px; color: #94a3b8; margin-top: 5px; display: block;">
                        Options are loaded dynamically from <a href="<?= base_url('admin/settings/channel-tabs') ?>" target="_blank" style="color: #e11d48; font-weight: 700; text-decoration: underline;">Save Channel Tabs</a> settings.
                    </span>
                </div>

                <div style="margin-bottom: 0;">
                    <label class="form-label" style="font-weight: 700; font-size: 13px;">Facebook Video / Reel URL</label>
                    <div style="position: relative;">
                        <input type="url" name="facebook_url" id="admin_facebook_url" class="premium-input" value="<?= old('facebook_url', $product['facebook_url'] ?? '') ?>" placeholder="e.g. https://www.facebook.com/watch/?v=XXXXXXX or https://fb.watch/XXXXXXX" style="padding-left: 40px;" oninput="handleFacebookPreview(this.value)">
                        <i class="fa-brands fa-facebook" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #1877f2; font-size: 18px;"></i>
                    </div>
                    <span style="font-size: 12px; color: #94a3b8; margin-top: 4px; display: block;">When provided, customers can tap to view the product video on your Facebook page.</span>
                    <span style="font-size: 11.5px; color: #334155; margin-top: 6px; display: block; background: #ffffff; padding: 6px 10px; border-radius: 6px; border: 1px dashed #bfdbfe;">
                        <i class="fa-solid fa-circle-info" style="color: #1877f2;"></i> <strong>Facebook Embed Requirement:</strong> The video must be posted as <strong>Public (🌍)</strong> on a Facebook Page. If the video is restricted, private, friends-only, or inside a private group, Facebook will block the embed with <em>"Video unavailable"</em>.
                    </span>
                    <div id="facebook-preview-hint" style="display: none; margin-top: 8px; font-size: 12px; padding: 8px 12px; border-radius: 8px; border: 1px solid;"></div>

                    <div id="facebook-preview-box" style="margin-top: 12px; display: none;">
                        <div style="font-size: 11.5px; font-weight: 700; color: #2563eb; margin-bottom: 6px; display: flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-circle-check"></i> Live Facebook Video / Reel Preview:
                        </div>
                        <div style="width: 340px; max-width: 100%; height: 580px; border-radius: 14px; overflow: hidden; border: 1.5px solid #bfdbfe; background: #000000; padding: 0; box-sizing: border-box; box-shadow: 0 8px 24px rgba(0,0,0,0.18);">
                            <iframe id="facebook-preview-iframe" style="width: 100%; height: 100%; border: 0;" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        </div>
                        <div style="margin-top: 8px;">
                            <a href="#" id="facebook-preview-link" target="_blank" style="font-size: 12px; color: #1d4ed8; font-weight: 600; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Open in Facebook
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        
                <!-- Step Navigation Footer -->
                <div class="section-nav-footer">
                    <button type="button" class="btn-nav-step btn-nav-prev" onclick="prevProductSection('section-media')">
                        <i class="fa-solid fa-arrow-left"></i> <span>Back: Variants</span>
                    </button>
                    <button type="button" class="btn-nav-step btn-nav-next" onclick="nextProductSection('section-media')">
                        <span>Continue to Display Settings</span> <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
</div></div>

<div id="section-display" class="product-form-pane" style="display: none;">
<div class="premium-card">
            <h3 class="section-title"><i class="fa-solid fa-display"></i> Display Settings</h3>
            
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_new_arrival" value="1" <?= old('is_new_arrival', $product['is_new_arrival']) ? 'checked' : '' ?>>
                    <span>Mark as New Arrival</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_best_seller" value="1" <?= old('is_best_seller', $product['is_best_seller']) ? 'checked' : '' ?>>
                    <span>Mark as Best Seller</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_live_collection" value="1" <?= old('is_live_collection', $product['is_live_collection']) ? 'checked' : '' ?>>
                    <span>Show in Live Collection (Home)</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_best_of_ssptex" value="1" <?= old('is_best_of_ssptex', $product['is_best_of_ssptex']) ? 'checked' : '' ?>>
                    <span>Best of Selvi Tex</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_must_visit" value="1" <?= old('is_must_visit', $product['is_must_visit']) ? 'checked' : '' ?>>
                    <span>Must Visit</span>
                </label>
            </div>


        
                <!-- Step Navigation Footer -->
                <div class="section-nav-footer">
                    <button type="button" class="btn-nav-step btn-nav-prev" onclick="prevProductSection('section-display')">
                        <i class="fa-solid fa-arrow-left"></i> <span>Back: Media</span>
                    </button>
                    <button type="button" class="btn-nav-step btn-nav-next" onclick="nextProductSection('section-display')">
                        <span>Continue to SEO</span> <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
</div></div>

<div id="section-seo" class="product-form-pane" style="display: none;">
<div class="premium-card">
            <h3 class="section-title"><i class="fa-solid fa-magnifying-glass"></i> SEO Settings</h3>
            
            <div class="form-group">
                <label class="form-label">SEO Title</label>
                <input type="text" name="seo_title" class="premium-input" value="<?= old('seo_title', $product['seo_title']) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">SEO Description</label>
                <textarea name="seo_description" class="premium-input" rows="3"><?= old('seo_description', $product['seo_description']) ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">SEO Keywords</label>
                <input type="text" name="seo_keywords" class="premium-input" value="<?= old('seo_keywords', $product['seo_keywords']) ?>">
            </div>
        
                <!-- Step Navigation Footer -->
                <div class="section-nav-footer">
                    <button type="button" class="btn-nav-step btn-nav-prev" onclick="prevProductSection('section-seo')">
                        <i class="fa-solid fa-arrow-left"></i> <span>Back: Display Settings</span>
                    </button>
                    <button type="submit" class="btn-premium" style="margin: 0; padding: 11px 28px;">
                        <i class="fa-solid fa-check"></i> Save Product
                    </button>
                </div>
</div></div>

<div style="display:none;">
    <button type="submit" class="btn-premium">
        <i class="fa-solid fa-check"></i> Update Product
    </button>
</div>
</form>

<!-- Redesigned Modern Variant Images Modal -->
<div id="variant-media-modal" class="variant-modal-backdrop" style="display: none;" aria-hidden="true">
    <div class="variant-modal-dialog modern-vmodal-dialog">
        <!-- Header -->
        <div class="vmodal-header">
            <div class="vmodal-header-left">
                <div class="vmodal-icon-badge">
                    <i class="fa-solid fa-images"></i>
                </div>
                <div class="vmodal-title-group">
                    <h3 class="vmodal-main-title">Variant Photos Showcase</h3>
                    <div class="vmodal-subtitle">
                        Managing photos for: 
                        <span class="vmodal-color-tag">
                            <span class="vmodal-swatch-dot" id="modal-swatch-dot" style="background-color: #e11d48;"></span>
                            <strong id="modal-variant-name">Color Option</strong>
                        </span>
                        <span class="vmodal-photo-counter" id="modal-photo-counter">0 photos</span>
                    </div>
                </div>
            </div>
            <button type="button" class="vmodal-close-btn" id="btn-close-variant-modal" title="Close Modal" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="vmodal-body">
            <!-- Informational Tip Banner -->
            <div class="vmodal-tip-banner">
                <i class="fa-solid fa-lightbulb"></i>
                <span>Upload specific saree drape &amp; close-up photos for this shade. When customers tap this color swatch on the store, these photos will automatically load.</span>
            </div>

            <!-- Modern Drag & Drop Zone -->
            <div class="vmodal-dropzone" id="modal-upload-dropzone">
                <div class="vmodal-dropzone-inner">
                    <div class="vmodal-upload-icon-circle">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div class="vmodal-dropzone-title">Drag &amp; drop photos here or click to browse</div>
                    <div class="vmodal-dropzone-sub">Upload high-resolution saree photos (JPG, PNG, WEBP — Max 10MB each)</div>
                    <button type="button" class="btn-vmodal-browse-trigger" onclick="document.getElementById('modal-variant-file-input').click()">
                        <i class="fa-solid fa-folder-open"></i> Choose Photos
                    </button>
                    <input type="file" id="modal-variant-file-input" multiple accept="image/jpeg,image/png,image/webp" style="display: none;">
                </div>
            </div>

            <!-- Uploaded Photos Grid Header -->
            <div class="vmodal-grid-header">
                <span class="vmodal-grid-title"><i class="fa-solid fa-film"></i> Uploaded Variant Photos</span>
                <span class="vmodal-grid-note">1st photo will serve as the primary swatch thumbnail</span>
            </div>

            <!-- Images Grid -->
            <div class="variant-images-grid modern-vimages-grid" id="modal-variant-images-grid">
                <!-- Dynamically populated -->
            </div>
        </div>

        <!-- Footer -->
        <div class="vmodal-footer">
            <div class="vmodal-footer-info">
                <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> Photos will be linked to this variant upon saving the product
            </div>
            <div class="vmodal-footer-actions">
                <button type="button" class="btn-vmodal-cancel" id="btn-cancel-variant-modal">
                    Cancel
                </button>
                <button type="button" class="btn-premium btn-vmodal-save" id="btn-save-variant-modal">
                    <i class="fa-solid fa-check"></i> Done &amp; Save
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    window.skuEnabled = <?= $sku_enabled ? 'true' : 'false' ?>;

    function updateTypeGlider(immediate = false) {
        const sw = document.getElementById('productTypeSwitch');
        if (!sw) return;
        const glider = sw.querySelector('.type-switch-glider');
        const activeBtn = sw.querySelector('.type-switch-btn.active');
        if (!glider || !activeBtn) return;

        if (immediate) {
            const prev = glider.style.transition;
            glider.style.transition = 'none';
            glider.style.width = activeBtn.offsetWidth + 'px';
            glider.style.transform = `translateX(${activeBtn.offsetLeft}px)`;
            glider.classList.add('initialized');
            void glider.offsetWidth;
            glider.style.transition = prev;
        } else {
            glider.style.width = activeBtn.offsetWidth + 'px';
            glider.style.transform = `translateX(${activeBtn.offsetLeft}px)`;
            glider.classList.add('initialized');
        }
    }

    function setProductType(isCombo) {
        const sw = document.getElementById('productTypeSwitch');
        const input = document.getElementById('is_combo_field');
        const btnSingle = document.getElementById('typeBtnSingle');
        const btnCombo = document.getElementById('typeBtnCombo');
        const callout = document.getElementById('comboCalloutBanner');
        const pageTitle = document.querySelector('.form-header h2');
        const backBtn = document.getElementById('headerBackBtn');

        if (isCombo) {
            sw.classList.add('is-combo');
            btnSingle.classList.remove('active');
            btnCombo.classList.add('active');
            input.value = '1';
            if (callout) callout.classList.add('show');
            if (pageTitle) pageTitle.innerHTML = '<i class="fa-solid fa-gifts" style="margin-right: 10px; color: #d4af37;"></i> Edit Combo Pack';
            if (backBtn) {
                backBtn.href = '<?= base_url('admin/products?tab=combos#combos') ?>';
                backBtn.innerHTML = '<i class="fa-solid fa-arrow-left"></i> Back to Combos';
            }
        } else {
            sw.classList.remove('is-combo');
            btnCombo.classList.remove('active');
            btnSingle.classList.add('active');
            input.value = '0';
            if (callout) callout.classList.remove('show');
            if (pageTitle) pageTitle.innerHTML = '<i class="fa-solid fa-pen-to-square" style="margin-right: 10px;"></i> Edit Product';
            if (backBtn) {
                backBtn.href = '<?= base_url('admin/products?tab=products#products') ?>';
                backBtn.innerHTML = '<i class="fa-solid fa-arrow-left"></i> Back to Products';
            }
        }
        const dhotiField = document.getElementById('dhoti_field_wrapper');
        const dhotiChart = document.getElementById('dhoti_chart_wrapper');
        if (dhotiField) dhotiField.style.display = isCombo ? 'block' : 'none';
        if (dhotiChart) dhotiChart.style.display = isCombo ? 'block' : 'none';
        updateTypeGlider(false);
    }

    // Initialize glider position
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($isCombo): ?>
        setProductType(true);
        <?php else: ?>
        updateTypeGlider(true);
        <?php endif; ?>
    });
    window.addEventListener('load', () => updateTypeGlider(true));
    window.addEventListener('resize', () => updateTypeGlider(true));
</script>
<script src="<?= base_url('assets/js/admin-products.js') ?>?v=<?= time() ?>"></script>

<?= $this->endSection() ?>


