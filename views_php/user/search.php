<?= view('user/header') ?>

<main style="flex-grow: 1;">
    <!-- Search Results Header Banner -->
    <section class="search-hero-banner" style="position: relative; width: 100%; padding: 45px 20px; background: linear-gradient(135deg, #7a1530 0%, #4a0d1e 100%); color: #ffffff; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="font-size: 13px; text-transform: uppercase; letter-spacing: 2px; color: #ffd700; margin-bottom: 8px; font-weight: 700;">
                <i class="fa-solid fa-magnifying-glass"></i> Search Catalog
            </div>
            <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 10px 0; font-family: 'Times New Roman', serif;">
                <?= !empty($query) ? 'Search results for &ldquo;' . esc($query) . '&rdquo;' : 'All Saree Collections' ?>
            </h1>
            <p style="font-size: 15px; color: #f8fafc; opacity: 0.9; margin: 0;">
                Found <?= !empty($products) ? count($products) : 0 ?> matching pure Elampillai handloom products
            </p>
        </div>
    </section>

    <?php 
        $whatsappNo = !empty($settings['whatsapp_no']) ? $settings['whatsapp_no'] : '919789296751';
        $whatsappNo = preg_replace('/[^0-9]/', '', $whatsappNo);
        if (strlen($whatsappNo) === 10) {
            $whatsappNo = '91' . $whatsappNo;
        } elseif (strlen($whatsappNo) === 11 && strpos($whatsappNo, '0') === 0) {
            $whatsappNo = '91' . substr($whatsappNo, 1);
        }
    ?>
    <!-- Grid Control Toolbar -->
    <div class="collection-toolbar" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; border-bottom: 1px solid #eaeaea; background-color: #fff;">
        <div class="view-switches" style="display: flex; gap: 14px; align-items: center;">
            <!-- Box 1: 4 squares (2x2 grid) -->
            <button type="button" class="view-btn" onclick="switchCollectionGridCols(2, this)" title="2 Columns" style="background: none; border: none; cursor: pointer; color: #999; padding: 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; transition: color 0.2s;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <rect x="3" y="3" width="8" height="8" rx="1"></rect>
                    <rect x="13" y="3" width="8" height="8" rx="1"></rect>
                    <rect x="3" y="13" width="8" height="8" rx="1"></rect>
                    <rect x="13" y="13" width="8" height="8" rx="1"></rect>
                </svg>
            </button>
            <!-- Box 2: 9 squares (3x3 grid) -->
            <button type="button" class="view-btn active" onclick="switchCollectionGridCols(3, this)" title="Grid View" style="background: none; border: none; cursor: pointer; color: #333; padding: 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; transition: color 0.2s;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <rect x="2.5" y="2.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="9.5" y="2.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="16.5" y="2.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="2.5" y="9.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="9.5" y="9.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="16.5" y="9.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="2.5" y="16.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="9.5" y="16.5" width="5" height="5" rx="0.8"></rect>
                    <rect x="16.5" y="16.5" width="5" height="5" rx="0.8"></rect>
                </svg>
            </button>
            <!-- Box 3: Horizontal rows (1 column row adjustment) -->
            <button type="button" class="view-btn" onclick="switchCollectionGridCols(1, this)" title="Row List View" style="background: none; border: none; cursor: pointer; color: #999; padding: 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; transition: color 0.2s;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <rect x="3" y="4" width="18" height="2.5" rx="0.6"></rect>
                    <rect x="3" y="8.5" width="18" height="2.5" rx="0.6"></rect>
                    <rect x="3" y="13" width="18" height="2.5" rx="0.6"></rect>
                    <rect x="3" y="17.5" width="18" height="2.5" rx="0.6"></rect>
                </svg>
            </button>
        </div>
        
        <div class="product-count" style="font-size: 14px; color: #666; font-weight: 500;">
            <?= !empty($products) ? count($products) : 0 ?> products
        </div>
        
        <div class="sort-dropdown sort-dropdown-custom-wrap" style="position: relative; display: flex; align-items: center; gap: 8px;">
            <label for="sort-custom-btn" style="font-size: 13.5px; color: #333; font-weight: 600; white-space: nowrap;">Sort by:</label>
            <button type="button" id="sort-custom-btn" onclick="toggleCustomSortMenu(event)" style="display: inline-flex; align-items: center; justify-content: space-between; gap: 10px; background: #fff; border: 1.5px solid #d4af37; border-radius: 8px; padding: 7px 12px; font-size: 13.5px; font-weight: 600; color: #1a050b; cursor: pointer; min-width: 165px; max-width: 100%; box-sizing: border-box; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                <span id="sort-selected-label">Date, new to old</span>
                <i class="fa-solid fa-chevron-down sort-chevron" style="font-size: 11px; color: #7a1530; transition: transform 0.2s;"></i>
            </button>
            <select id="sort-by" style="display: none;">
                <option value="featured">Featured</option>
                <option value="best-selling">Best selling</option>
                <option value="alpha-asc">Alphabetically, A-Z</option>
                <option value="alpha-desc">Alphabetically, Z-A</option>
                <option value="price-asc">Price, low to high</option>
                <option value="price-desc">Price, high to low</option>
                <option value="date-desc" selected>Date, new to old</option>
                <option value="date-asc">Date, old to new</option>
            </select>
            <div id="sort-custom-menu" class="sort-custom-menu" style="display: none; position: absolute; top: calc(100% + 6px); right: 0; min-width: 210px; max-width: calc(100vw - 32px); background: #ffffff; border: 1.5px solid #d4af37; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); z-index: 99999; overflow: hidden; padding: 6px 0;">
                <div class="sort-option-item active" data-value="date-desc" onclick="selectCustomSort('date-desc', 'Date, new to old')" style="padding: 9px 16px; font-size: 13.5px; color: #7a1530; font-weight: 700; background: #fdf6ec; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Date, new to old</span>
                    <i class="fa-solid fa-check sort-check" style="font-size: 12px; color: #7a1530;"></i>
                </div>
                <div class="sort-option-item" data-value="date-asc" onclick="selectCustomSort('date-asc', 'Date, old to new')" style="padding: 9px 16px; font-size: 13.5px; color: #334155; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Date, old to new</span>
                    <i class="fa-solid fa-check sort-check" style="display: none; font-size: 12px; color: #7a1530;"></i>
                </div>
                <div class="sort-option-item" data-value="featured" onclick="selectCustomSort('featured', 'Featured')" style="padding: 9px 16px; font-size: 13.5px; color: #334155; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Featured</span>
                    <i class="fa-solid fa-check sort-check" style="display: none; font-size: 12px; color: #7a1530;"></i>
                </div>
                <div class="sort-option-item" data-value="best-selling" onclick="selectCustomSort('best-selling', 'Best selling')" style="padding: 9px 16px; font-size: 13.5px; color: #334155; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Best selling</span>
                    <i class="fa-solid fa-check sort-check" style="display: none; font-size: 12px; color: #7a1530;"></i>
                </div>
                <div class="sort-option-item" data-value="alpha-asc" onclick="selectCustomSort('alpha-asc', 'Alphabetically, A-Z')" style="padding: 9px 16px; font-size: 13.5px; color: #334155; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Alphabetically, A-Z</span>
                    <i class="fa-solid fa-check sort-check" style="display: none; font-size: 12px; color: #7a1530;"></i>
                </div>
                <div class="sort-option-item" data-value="alpha-desc" onclick="selectCustomSort('alpha-desc', 'Alphabetically, Z-A')" style="padding: 9px 16px; font-size: 13.5px; color: #334155; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Alphabetically, Z-A</span>
                    <i class="fa-solid fa-check sort-check" style="display: none; font-size: 12px; color: #7a1530;"></i>
                </div>
                <div class="sort-option-item" data-value="price-asc" onclick="selectCustomSort('price-asc', 'Price, low to high')" style="padding: 9px 16px; font-size: 13.5px; color: #334155; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Price, low to high</span>
                    <i class="fa-solid fa-check sort-check" style="display: none; font-size: 12px; color: #7a1530;"></i>
                </div>
                <div class="sort-option-item" data-value="price-desc" onclick="selectCustomSort('price-desc', 'Price, high to low')" style="padding: 9px 16px; font-size: 13.5px; color: #334155; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                    <span>Price, high to low</span>
                    <i class="fa-solid fa-check sort-check" style="display: none; font-size: 12px; color: #7a1530;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Page Layout with Left Sidebar -->
    <div class="collections-page-layout" style="max-width: 1400px; margin: 40px auto 60px; padding: 0 40px; display: flex; gap: 40px; align-items: flex-start;">
        
        <!-- Left Sidebar: Filter -->
        <?= view('user/sidebar_filter', ['showFilters' => true, 'showMainMenu' => true, 'maxPrice' => 3000]) ?>

        <!-- Right Content: Products Grid -->
        <div class="collections-right-content" style="flex-grow: 1; min-width: 0;">
            <div id="collections-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; transition: all 0.3s ease;">
                
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php 
                            $variantsList = !empty($product['variants']) ? $product['variants'] : [];
                            $isStockZero = (isset($product['stock_quantity']) && $product['stock_quantity'] !== null && (int)$product['stock_quantity'] <= 0);

                            // Collect all colors (names & codes) across product and variants
                            $colorKeywords = [$product['name']];
                            if (!empty($product['description'])) {
                                $colorKeywords[] = strip_tags($product['description']);
                            }
                            $allCardPrices = [(float)$product['price']];
                            foreach ($variantsList as $v) {
                                if (!empty($v['color_name'])) {
                                    $colorKeywords[] = $v['color_name'];
                                }
                                if (!empty($v['color_code'])) {
                                    $colorKeywords[] = $v['color_code'];
                                }
                                if (!empty($v['sale_price'])) {
                                    $allCardPrices[] = (float)$v['sale_price'];
                                }
                            }
                            $colorData = strtolower(implode(' ', $colorKeywords));
                            $cardMinPrice = min($allCardPrices);
                            $cardMaxPrice = max($allCardPrices);
                            
                            $colorVariants = [];
                            foreach ($variantsList as $v) {
                                $cCode = !empty($v['color_code']) ? $v['color_code'] : null;
                                $cName = !empty($v['color_name']) ? $v['color_name'] : null;
                                if ($cCode || $cName) {
                                    $k = strtolower(($cCode ?? '') . '_' . ($cName ?? ''));
                                    if (!isset($colorVariants[$k])) {
                                        $colorVariants[$k] = $v;
                                    }
                                }
                            }
                            $cardSizes = [];
                            if (!empty($variantsList)) {
                                foreach ($variantsList as $v) {
                                    $rawSize = trim($v['size'] ?? '');
                                    if ($rawSize !== '') {
                                        $parts = array_filter(array_map('trim', explode(',', $rawSize)));
                                        foreach ($parts as $p) {
                                            if ($p !== '' && !in_array($p, $cardSizes)) {
                                                $cardSizes[] = $p;
                                            }
                                        }
                                    }
                                }
                            }
                            if (empty($cardSizes) && !empty($product['size'])) {
                                $rawSize = trim($product['size']);
                                if ($rawSize !== '') {
                                    $parts = array_filter(array_map('trim', explode(',', $rawSize)));
                                    foreach ($parts as $p) {
                                        if ($p !== '' && !in_array($p, $cardSizes)) {
                                            $cardSizes[] = $p;
                                        }
                                    }
                                }
                            }
                            $displayPrice = (float)$product['price'];
                            $displayOrigPrice = !empty($product['original_price']) ? (float)$product['original_price'] : 0;
                            if (!empty($variantsList[0]['sale_price'])) {
                                $displayPrice = (float)$variantsList[0]['sale_price'];
                            }
                            if (!empty($variantsList[0]['original_price'])) {
                                $displayOrigPrice = (float)$variantsList[0]['original_price'];
                            }

                            $imgSrc = base_url('assets/images/placeholder.jpg');
                            if (!empty($product['image_url'])) {
                                if (strpos($product['image_url'], 'http') === 0) {
                                    $imgSrc = $product['image_url'];
                                } else {
                                    $imgSrc = base_url(ltrim($product['image_url'], '/'));
                                }
                            }
                            if ($imgSrc === base_url('assets/images/placeholder.jpg') && !empty($product['media'])) {
                                foreach ($product['media'] as $pm) {
                                    if ($pm['media_type'] === 'image' && !empty($pm['media_url'])) {
                                        $imgSrc = base_url(ltrim($pm['media_url'], '/'));
                                        break;
                                    }
                                }
                            }
                            if ($imgSrc === base_url('assets/images/placeholder.jpg') && !empty($variantsList)) {
                                foreach ($variantsList as $v) {
                                    if (!empty($v['images'][0]['media_url'])) {
                                        $imgSrc = base_url(ltrim($v['images'][0]['media_url'], '/'));
                                        break;
                                    }
                                }
                            }
                            $productEnquiryText = urlencode("Hello Selvi Tex, I am interested in product enquiry for " . $product['name'] . " (" . base_url('product/' . $product['slug']) . ")");
                        ?>
                        <div class="product-card" data-id="<?= (int)$product['id'] ?>" data-price="<?= $displayPrice ?>" data-original-price="<?= $displayOrigPrice ?>" data-min-price="<?= $cardMinPrice ?>" data-max-price="<?= $cardMaxPrice ?>" data-stock="<?= $isStockZero ? '0' : '1' ?>" data-color="<?= esc($colorData) ?>" data-best="<?= !empty($product['is_best_seller']) ? '1' : '0' ?>" data-selected-size="<?= !empty($cardSizes[0]) ? esc($cardSizes[0]) : '' ?>" data-sizes="<?= esc(implode(',', $cardSizes)) ?>" data-variants='<?= json_encode($variantsList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>' style="background: white; border-radius: 8px; position: relative; border: 1px solid #f1f1f1; padding: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); transition: all 0.3s; display: flex; flex-direction: column;">
                            <?php if ($isStockZero): ?>
                                <span style="position: absolute; top: 22px; left: 22px; background: black; color: white; padding: 4px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 4px;">SOLD OUT</span>
                            <?php elseif (!empty($product['is_new_arrival'])): ?>
                                <span style="position: absolute; top: 22px; left: 22px; background: #7a1530; color: white; padding: 4px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 4px;">NEW</span>
                            <?php elseif ($displayOrigPrice > $displayPrice): ?>
                                <span class="card-save-badge" data-base-save="<?= $displayOrigPrice - $displayPrice ?>" style="position: absolute; top: 22px; left: 22px; background: red; color: white; padding: 4px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 4px;">SAVE RS. <?= number_format($displayOrigPrice - $displayPrice, 0) ?></span>
                            <?php endif; ?>
                            <?php
                                $allCardGallery = [$imgSrc];
                                if (!empty($product['media'])) {
                                    foreach ($product['media'] as $pm) {
                                        if ($pm['media_type'] === 'image' && !empty($pm['media_url'])) {
                                            $mUrl = base_url(ltrim($pm['media_url'], '/'));
                                            if (!in_array($mUrl, $allCardGallery)) {
                                                $allCardGallery[] = $mUrl;
                                            }
                                        }
                                    }
                                }
                                if (!empty($variantsList)) {
                                    foreach ($variantsList as $v) {
                                        if (!empty($v['images'])) {
                                            foreach ($v['images'] as $vImg) {
                                                if (!empty($vImg['media_url'])) {
                                                    $mUrl = base_url(ltrim($vImg['media_url'], '/'));
                                                    if (!in_array($mUrl, $allCardGallery)) {
                                                        $allCardGallery[] = $mUrl;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            ?>
                            <?php
                                $primaryImg = $imgSrc;
                                $secondaryImg = (!empty($allCardGallery[1]) && $allCardGallery[1] !== $primaryImg) ? $allCardGallery[1] : null;
                            ?>
                            <div class="card-img-holder" data-gallery='<?= json_encode($allCardGallery, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'>
                                <a href="<?= base_url('product/' . esc($product['slug'])) ?>" class="card-img-link">
                                    <img src="<?= $primaryImg ?>" class="card-primary-img" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="<?= esc($product['name']) ?>" loading="lazy">
                                    <?php if (!empty($secondaryImg)): ?>
                                        <img src="<?= $secondaryImg ?>" class="card-secondary-img" onerror="this.onerror=null;this.style.display='none';" alt="<?= esc($product['name']) ?> preview" loading="lazy">
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="card-info-wrap">
                                <a href="<?= base_url('product/' . esc($product['slug'])) ?>" style="text-decoration: none; color: inherit; display: block;">
                                    <h3 style="font-size: 15px; font-weight: 600; color: #333; margin: 0 0 6px 0;"><?= esc($product['name']) ?></h3>
                                </a>

                                <?php if(!empty($colorVariants)): ?>
                                    <div class="product-card-swatches">
                                        <?php $cIdx = 0; foreach($colorVariants as $cv): 
                                            $cvImg = !empty($cv['images'][0]['media_url']) ? base_url(ltrim($cv['images'][0]['media_url'], '/')) : '';
                                            $cvHex = !empty($cv['color_code']) ? $cv['color_code'] : '#7a1530';
                                            $cvName = $cv['color_name'] ?: 'Option';
                                            $cvSale = !empty($cv['sale_price']) ? (float)$cv['sale_price'] : (float)$product['price'];
                                            $cvOrig = !empty($cv['original_price']) ? (float)$cv['original_price'] : (!empty($product['original_price']) ? (float)$product['original_price'] : 0);
                                            $cvStock = isset($cv['stock']) ? (int)$cv['stock'] : 1;
                                        ?>
                                            <button type="button" 
                                                    class="card-color-swatch <?= $cIdx === 0 ? 'active' : '' ?>" 
                                                    style="<?= !empty($cvImg) ? '' : 'background-color: ' . esc($cvHex) . ';' ?>" 
                                                    title="<?= esc($cvName) ?>"
                                                    data-variant-id="<?= (int)$cv['id'] ?>"
                                                    data-image="<?= esc($cvImg) ?>"
                                                    data-sale="<?= esc(number_format($cvSale, 2)) ?>"
                                                    data-orig="<?= esc($cvOrig > $cvSale ? number_format($cvOrig, 2) : '') ?>"
                                                    data-stock="<?= $cvStock ?>"
                                                    aria-label="<?= esc($cvName) ?>">
                                                <?php if(!empty($cvImg)): ?>
                                                    <img src="<?= esc($cvImg) ?>" alt="<?= esc($cvName) ?>" class="swatch-thumb-img">
                                                <?php else: ?>
                                                    <span class="swatch-color-box" style="background-color: <?= esc($cvHex) ?>;"></span>
                                                <?php endif; ?>
                                            </button>
                                        <?php $cIdx++; endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($cardSizes)): ?>
                                    <div class="product-card-sizes">
                                        <span class="card-sizes-label">Sizes:</span>
                                        <?php foreach($cardSizes as $sIdx => $sz): ?>
                                            <span class="card-size-pill <?= $sIdx === 0 ? 'active' : '' ?>" data-size="<?= esc($sz) ?>">
                                                <?= esc($sz) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div style="display: flex; gap: 10px; align-items: baseline; margin-bottom: 12px;">
                                    <span class="card-sale-price" data-base-price="<?= $displayPrice ?>" style="color: red; font-weight: 700; font-size: 18px;">Rs. <?= number_format($displayPrice, 2) ?></span>
                                    <?php if ($displayOrigPrice > $displayPrice): ?>
                                        <span class="card-original-price" data-base-price="<?= $displayOrigPrice ?>" style="text-decoration: line-through; color: #999; font-size: 14px;">Rs. <?= number_format($displayOrigPrice, 2) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-action-btns" style="display: flex; gap: 6px; margin-top: 6px; align-items: center; width: 100%;">
                                    <button type="button" 
                                            class="card-add-cart-btn" 
                                            data-product-id="<?= (int)$product['id'] ?>" 
                                            data-variant-id="<?= !empty($variantsList[0]['id']) ? (int)$variantsList[0]['id'] : '' ?>"
                                            title="Add to Shopping Cart"
                                            style="flex: 1; background: #ffffff; color: #7a1530; border: 1.5px solid #7a1530; padding: 8px 6px; border-radius: 6px; font-weight: 700; font-size: 12px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 4px; transition: all 0.2s;">
                                        <i class="fa-solid fa-cart-shopping"></i> <span class="btn-text">Add Cart</span>
                                    </button>
                                    <a href="<?= base_url('checkout?product_id=' . $product['id'] . (!empty($variantsList[0]['id']) ? '&variant_id=' . (int)$variantsList[0]['id'] : '')) ?>" 
                                       class="card-buy-now-btn"
                                       data-product-id="<?= (int)$product['id'] ?>"
                                       data-variant-id="<?= !empty($variantsList[0]['id']) ? (int)$variantsList[0]['id'] : '' ?>"
                                       title="Checkout this product individually"
                                       style="flex: 1; background: #7a1530; color: #ffffff; text-align: center; padding: 8px 6px; border-radius: 6px; font-weight: 700; text-decoration: none; font-size: 12px; display: flex; justify-content: center; align-items: center; gap: 4px; transition: all 0.2s;">
                                        <i class="fa-solid fa-bolt"></i> <span class="btn-text">Buy Now</span>
                                    </a>
                                    <a href="https://wa.me/<?= esc($whatsappNo) ?>?text=<?= $productEnquiryText ?>" target="_blank" class="card-whatsapp-btn" style="width: 36px; height: 36px; background: #25D366; color: white; border-radius: 6px; text-decoration: none; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(37,211,102,0.3);" title="Product Enquiry on WhatsApp">
                                        <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; padding: 80px 20px; text-align: center; color: #64748b; background: #ffffff; border-radius: 12px; border: 1.5px dashed #cbd5e1;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 48px; margin-bottom: 16px; color: #cbd5e1;"></i>
                        <h4 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">No matching sarees found</h4>
                        <p style="margin: 0 0 24px 0; font-size: 14px;">We couldn't find any products matching your search term. Try checking for typos or searching for a different color or style.</p>
                        <a href="<?= base_url('collections') ?>" style="display: inline-flex; align-items: center; gap: 8px; background: #7a1530; color: #ffffff; padding: 10px 22px; border-radius: 6px; font-weight: 700; text-decoration: none; font-size: 14px;">
                            <i class="fa-solid fa-bag-shopping"></i> View All Collections
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<!-- Redesigned Luxury FAQ Section -->
<?= view('user/faq_section') ?>

<style>
.product-card.product-card-row-view {
    display: flex !important;
    flex-direction: row !important;
    gap: 25px !important;
    align-items: center !important;
    padding: 20px !important;
    box-sizing: border-box !important;
    width: 100% !important;
}
.product-card.product-card-row-view .card-img-holder {
    width: 220px !important;
    height: 220px !important;
    margin-bottom: 0 !important;
    flex-shrink: 0 !important;
}
.product-card.product-card-row-view .card-img-holder img {
    height: 100% !important;
    width: 100% !important;
    object-fit: cover !important;
}
.product-card.product-card-row-view .card-info-wrap {
    flex-grow: 1 !important;
    min-width: 0 !important;
}
@media (max-width: 768px) {
    .product-card.product-card-row-view {
        gap: 14px !important;
        padding: 12px !important;
    }
    .product-card.product-card-row-view .card-img-holder {
        width: 130px !important;
        min-width: 130px !important;
        max-width: 130px !important;
        height: 155px !important;
    }
    .product-card.product-card-row-view .card-info-wrap h3 {
        font-size: 14px !important;
        margin-bottom: 4px !important;
    }
    .product-card.product-card-row-view .card-action-btns {
        margin-top: 8px !important;
    }
}
@media (max-width: 480px) {
    .product-card.product-card-row-view {
        gap: 10px !important;
        padding: 10px !important;
    }
    .product-card.product-card-row-view .card-img-holder {
        width: 105px !important;
        min-width: 105px !important;
        max-width: 105px !important;
        height: 130px !important;
    }
    .product-card.product-card-row-view .card-info-wrap h3 {
        font-size: 13px !important;
        line-height: 1.3 !important;
    }
    .product-card.product-card-row-view .card-sale-price {
        font-size: 14px !important;
    }
    .product-card.product-card-row-view .card-original-price {
        font-size: 11px !important;
    }
}
</style>

<?= view('user/footer') ?>
