<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-products.css') ?>?v=<?= time() ?>">

<?php
    if (!isset($sku_enabled)) {
        $stModel = new \App\Models\SettingsModel();
        $skuSetting = $stModel->where('key', 'module_product_sku')->first();
        $sku_enabled = $skuSetting ? ($skuSetting['value'] === '1' || $skuSetting['value'] === 1) : true;
    }
    $regularCount = count($products ?? []);
    $comboCount = count($comboProducts ?? []);
?>

<div class="card">
    <!-- Luxury Swipe-Like Navigation & Header Bar -->
    <div class="products-nav-container">
        <div class="products-nav-left">
            <div class="products-header-title-wrap">
                <h2 id="activeTabHeading">
                    <i class="fa-solid fa-box-open" id="headingIcon" style="color: #7a1530;"></i>
                    <span id="headingTitle">All Products</span>
                </h2>
                <div class="subtitle" id="headingSubtitle">Manage your single catalog sarees and products (<?= $regularCount ?> items)</div>
            </div>

            <!-- Swipe Segmented Navigation Bar -->
            <div class="swipe-nav-bar" id="productsSwipeNavBar" role="tablist" aria-label="Catalog Navigation">
                <div class="swipe-nav-glider" id="productsNavGlider"></div>
                <button type="button" class="swipe-nav-item active" id="tabBtnProducts" role="tab" aria-selected="true" data-target="products" title="Switch to Single Products">
                    <i class="fa-solid fa-box"></i>
                    <span>Products</span>
                    <span class="swipe-nav-badge" id="badgeProductsCount"><?= $regularCount ?></span>
                </button>
                <button type="button" class="swipe-nav-item" id="tabBtnCombos" role="tab" aria-selected="false" data-target="combos" title="Switch to Combo Packs & Offers">
                    <i class="fa-solid fa-gifts"></i>
                    <span>Combos</span>
                    <span class="swipe-nav-badge" id="badgeCombosCount"><?= $comboCount ?></span>
                    <span class="combo-tag-mini">Offers</span>
                </button>
            </div>
        </div>

        <!-- Dynamic Action Button (Slides between Add Product & Add Combo) -->
        <div class="products-nav-actions">
            <a href="<?= base_url('admin/products/create') ?>" class="nav-add-btn" id="dynamicAddBtn">
                <i class="fa-solid fa-plus" id="dynamicAddIcon"></i>
                <span id="dynamicAddText">Add Product</span>
            </a>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #059669; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Swipe Views Viewport (Touch Swipe + Sliding Animation) -->
    <div class="swipe-views-viewport" id="swipeViewsViewport">
        <div class="swipe-views-track" id="swipeViewsTrack">

            <!-- ================= SLIDE 1: SINGLE PRODUCTS ================= -->
            <div class="swipe-slide active" id="slideProducts" role="tabpanel" aria-labelledby="tabBtnProducts">
                <div class="table-responsive">
                    <table class="table" id="productsTable">
                        <thead>
                            <tr>
                                <th style="width: 70px;">Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Badges</th>
                                <th style="width: 170px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($products)): foreach($products as $product): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $imgSrc = base_url('assets/images/placeholder.jpg');
                                        if (!empty($product['image_url'])) {
                                            if (filter_var($product['image_url'], FILTER_VALIDATE_URL)) {
                                                $imgSrc = $product['image_url'];
                                            } else {
                                                $imgSrc = base_url(ltrim($product['image_url'], '/'));
                                            }
                                        }
                                    ?>
                                    <img src="<?= esc($imgSrc) ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);">
                                </td>
                                <td style="font-weight: 600;">
                                    <?= esc($product['name']) ?>
                                    <?php if ($sku_enabled && !empty($product['sku'])): ?>
                                        <br><span style="display: inline-block; font-size: 11px; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 1px 6px; border-radius: 4px; margin-top: 3px; font-family: monospace;">SKU: <?= esc($product['sku']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: var(--secondary); font-weight: 600;"><?= esc($product['main_category_name'] ?? 'General') ?></span><br>
                                    <span style="font-size: 13px; color: #334155;"><?= esc($product['category_name'] ?? 'Uncategorized') ?></span>
                                </td>
                                <td>
                                    <strong style="color: #0f172a; font-size: 14.5px;">₹ <?= number_format($product['price'], 2) ?></strong>
                                    <?php if(!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                        <br><span style="text-decoration: line-through; font-size: 11.5px; color: #94a3b8;">₹ <?= number_format($product['original_price'], 2) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($product['shorts_channel'])): ?>
                                        <span style="background: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-right: 5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                                            <i class="fa-brands fa-youtube"></i> <?= esc($product['shorts_channel']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if(!empty($product['instagram_channel'])): ?>
                                        <span style="background: rgba(225, 48, 108, 0.15); color: #e1306c; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-right: 5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                                            <i class="fa-brands fa-instagram"></i> <?= esc($product['instagram_channel']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if(!empty($product['facebook_channel'])): ?>
                                        <span style="background: rgba(24, 119, 242, 0.15); color: #1877f2; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-right: 5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                                            <i class="fa-brands fa-facebook"></i> <?= esc($product['facebook_channel']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if($product['is_new_arrival']): ?>
                                        <span style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-right: 5px; font-weight: 600;">New</span>
                                    <?php endif; ?>
                                    <?php if($product['is_best_seller']): ?>
                                        <span style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Hot</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="<?= base_url('admin/products/edit/'.$product['id']) ?>" class="btn btn-action"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                        <a href="<?= base_url('admin/products/delete/'.$product['id']) ?>" class="btn btn-action btn-danger" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 35px;">
                                    <i class="fa-solid fa-box-open" style="font-size: 28px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                    No regular products found. Click <strong>+ Add Product</strong> to add one.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= SLIDE 2: COMBO PACKS & OFFERS ================= -->
            <div class="swipe-slide inactive" id="slideCombos" role="tabpanel" aria-labelledby="tabBtnCombos">
                <?php if(!empty($comboProducts)): ?>
                <div class="table-responsive">
                    <table class="table" id="combosTable">
                        <thead>
                            <tr>
                                <th style="width: 70px;">Image</th>
                                <th>Combo Name</th>
                                <th>Category</th>
                                <th>Combo Price</th>
                                <th>Badges & Offers</th>
                                <th style="width: 170px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($comboProducts as $combo): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $comboImgSrc = base_url('assets/images/placeholder.jpg');
                                        if (!empty($combo['image_url'])) {
                                            if (filter_var($combo['image_url'], FILTER_VALIDATE_URL)) {
                                                $comboImgSrc = $combo['image_url'];
                                            } else {
                                                $comboImgSrc = base_url(ltrim($combo['image_url'], '/'));
                                            }
                                        }
                                    ?>
                                    <div style="position: relative; display: inline-block;">
                                        <img src="<?= esc($comboImgSrc) ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="Combo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(212, 175, 55, 0.4);">
                                        <span style="position: absolute; bottom: -3px; right: -3px; background: #b45309; color: #fff; font-size: 9px; padding: 1px 4px; border-radius: 4px; font-weight: 800;"><i class="fa-solid fa-gift"></i></span>
                                    </div>
                                </td>
                                <td style="font-weight: 600;">
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                        <span class="badge-combo-pack"><i class="fa-solid fa-gifts"></i> COMBO</span>
                                        <span style="color: #0f172a; font-weight: 700;"><?= esc($combo['name']) ?></span>
                                    </div>
                                    <?php if ($sku_enabled && !empty($combo['sku'])): ?>
                                        <span style="display: inline-block; font-size: 11px; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 1px 6px; border-radius: 4px; font-family: monospace;">SKU: <?= esc($combo['sku']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: var(--secondary); font-weight: 600;"><?= esc($combo['main_category_name'] ?? 'General') ?></span><br>
                                    <span style="font-size: 13px; color: #334155;"><?= esc($combo['category_name'] ?? 'Uncategorized') ?></span>
                                </td>
                                <td>
                                    <strong style="color: #991b1b; font-size: 15px;">₹ <?= number_format($combo['price'], 2) ?></strong>
                                    <?php if(!empty($combo['original_price']) && $combo['original_price'] > $combo['price']): 
                                        $savings = $combo['original_price'] - $combo['price'];
                                        $savePercent = round(($savings / $combo['original_price']) * 100);
                                    ?>
                                        <br>
                                        <span style="text-decoration: line-through; font-size: 11.5px; color: #94a3b8;">₹ <?= number_format($combo['original_price'], 2) ?></span>
                                        <span style="font-size: 11px; color: #15803d; font-weight: 700; background: #dcfce7; padding: 1px 5px; border-radius: 3px; margin-left: 4px;">Save <?= $savePercent ?>%</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge-combo-pack"><i class="fa-solid fa-sparkles"></i> Combo Offer</span>
                                    <?php if($combo['is_new_arrival']): ?>
                                        <span style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-left: 4px; font-weight: 600;">New</span>
                                    <?php endif; ?>
                                    <?php if($combo['is_best_seller']): ?>
                                        <span style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-left: 4px; font-weight: 600;">Hot</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="<?= base_url('admin/products/edit/'.$combo['id']) ?>" class="btn btn-action"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                        <a href="<?= base_url('admin/products/delete/'.$combo['id']) ?>" class="btn btn-action btn-danger" onclick="return confirm('Are you sure you want to delete this combo pack?')"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <!-- Empty State for Combos with quick add trigger -->
                <div class="combo-empty-state">
                    <div class="combo-empty-icon">
                        <i class="fa-solid fa-gifts"></i>
                    </div>
                    <div class="combo-empty-title">No Combo Packs Created Yet</div>
                    <div class="combo-empty-desc">
                        Bundle 2 or more sarees or products together (e.g. "Festive Silk Saree + Matching Shawl", "Buy 2 Get Special Price") to boost average order value.
                    </div>
                    <a href="<?= base_url('admin/products/create?type=combo') ?>" class="nav-add-btn combo-btn" style="display: inline-flex;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Create Your First Combo Pack</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- ==========================================================================
     Swipe Navigation Bar & Track Controller Engine
     ========================================================================== -->
<script>
(function() {
    'use strict';

    const tabBtnProducts = document.getElementById('tabBtnProducts');
    const tabBtnCombos = document.getElementById('tabBtnCombos');
    const swipeNavBar = document.getElementById('productsSwipeNavBar');
    const swipeTrack = document.getElementById('swipeViewsTrack');
    const slideProducts = document.getElementById('slideProducts');
    const slideCombos = document.getElementById('slideCombos');
    const viewport = document.getElementById('swipeViewsViewport');

    const headingTitle = document.getElementById('headingTitle');
    const headingSubtitle = document.getElementById('headingSubtitle');
    const headingIcon = document.getElementById('headingIcon');
    const dynamicAddBtn = document.getElementById('dynamicAddBtn');
    const dynamicAddText = document.getElementById('dynamicAddText');
    const dynamicAddIcon = document.getElementById('dynamicAddIcon');

    const regularCount = <?= (int)$regularCount ?>;
    const comboCount = <?= (int)$comboCount ?>;

    let currentTab = 'products';

    const glider = document.getElementById('productsNavGlider');

    function updateGlider(immediate = false) {
        const activeBtn = currentTab === 'combos' ? tabBtnCombos : tabBtnProducts;
        if (!activeBtn || !glider) return;

        if (immediate) {
            const prevTransition = glider.style.transition;
            glider.style.transition = 'none';
            glider.style.width = activeBtn.offsetWidth + 'px';
            glider.style.transform = `translateX(${activeBtn.offsetLeft}px)`;
            glider.classList.add('initialized');
            void glider.offsetWidth;
            glider.style.transition = prevTransition;
        } else {
            glider.style.width = activeBtn.offsetWidth + 'px';
            glider.style.transform = `translateX(${activeBtn.offsetLeft}px)`;
            glider.classList.add('initialized');
        }
    }

    function switchTab(target, updateHash = true) {
        if (target === 'combos') {
            currentTab = 'combos';
            swipeNavBar.classList.add('combos-active');
            tabBtnProducts.classList.remove('active');
            tabBtnProducts.setAttribute('aria-selected', 'false');
            tabBtnCombos.classList.add('active');
            tabBtnCombos.setAttribute('aria-selected', 'true');

            // Slide content track to second slide
            swipeTrack.style.transform = 'translateX(-50%)';
            slideProducts.classList.remove('active');
            slideProducts.classList.add('inactive');
            slideCombos.classList.remove('inactive');
            slideCombos.classList.add('active');

            // Update Dynamic Headings
            headingTitle.textContent = 'All Combo Packs';
            headingSubtitle.textContent = 'Manage special bundled packages and combo offers (' + comboCount + ' combo' + (comboCount === 1 ? '' : 's') + ')';
            headingIcon.className = 'fa-solid fa-gifts';
            headingIcon.style.color = '#d4af37';

            // Update Dynamic Action Button
            dynamicAddBtn.href = '<?= base_url('admin/products/create?type=combo') ?>';
            dynamicAddText.textContent = 'Add Combo';
            dynamicAddIcon.className = 'fa-solid fa-wand-magic-sparkles';
            dynamicAddBtn.classList.add('combo-btn');

            if (updateHash) {
                history.replaceState(null, null, '#combos');
            }
        } else {
            currentTab = 'products';
            swipeNavBar.classList.remove('combos-active');
            tabBtnCombos.classList.remove('active');
            tabBtnCombos.setAttribute('aria-selected', 'false');
            tabBtnProducts.classList.add('active');
            tabBtnProducts.setAttribute('aria-selected', 'true');

            // Slide content track to first slide
            swipeTrack.style.transform = 'translateX(0%)';
            slideCombos.classList.remove('active');
            slideCombos.classList.add('inactive');
            slideProducts.classList.remove('inactive');
            slideProducts.classList.add('active');

            // Update Dynamic Headings
            headingTitle.textContent = 'All Products';
            headingSubtitle.textContent = 'Manage your single catalog sarees and products (' + regularCount + ' items)';
            headingIcon.className = 'fa-solid fa-box-open';
            headingIcon.style.color = '#7a1530';

            // Update Dynamic Action Button
            dynamicAddBtn.href = '<?= base_url('admin/products/create') ?>';
            dynamicAddText.textContent = 'Add Product';
            dynamicAddIcon.className = 'fa-solid fa-plus';
            dynamicAddBtn.classList.remove('combo-btn');

            if (updateHash) {
                history.replaceState(null, null, '#products');
            }
        }
        updateGlider(false);
    }

    // Click events
    tabBtnProducts.addEventListener('click', () => switchTab('products'));
    tabBtnCombos.addEventListener('click', () => switchTab('combos'));

    // Keyboard navigation (ArrowLeft & ArrowRight)
    swipeNavBar.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            switchTab('combos');
            tabBtnCombos.focus();
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            switchTab('products');
            tabBtnProducts.focus();
        }
    });

    // Touch Swipe Gestures on Mobile/Tablet
    let touchStartX = 0;
    let touchStartY = 0;
    let touchEndX = 0;
    let touchEndY = 0;

    viewport.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
        touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });

    viewport.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const deltaX = touchEndX - touchStartX;
        const deltaY = touchEndY - touchStartY;
        // Ensure horizontal swipe is dominant and exceeds minimum threshold (50px)
        if (Math.abs(deltaX) > 50 && Math.abs(deltaX) > Math.abs(deltaY) * 1.5) {
            if (deltaX < 0 && currentTab === 'products') {
                // Swiped Left -> Go to Combos
                switchTab('combos');
            } else if (deltaX > 0 && currentTab === 'combos') {
                // Swiped Right -> Go to Products
                switchTab('products');
            }
        }
    }

    // Initialize glider position
    updateGlider(true);
    window.addEventListener('resize', () => updateGlider(true));
    window.addEventListener('load', () => updateGlider(true));

    // Auto-activate tab from URL Hash (e.g. #combos) or Query Param
    window.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.toLowerCase();
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        if (hash === '#combos' || tabParam === 'combos') {
            switchTab('combos', false);
        } else {
            switchTab('products', false);
        }
        updateGlider(true);
    });

    // Listen for back/forward navigation
    window.addEventListener('hashchange', function() {
        if (window.location.hash.toLowerCase() === '#combos') {
            switchTab('combos', false);
        } else {
            switchTab('products', false);
        }
        updateGlider(true);
    });
})();
</script>

<?= $this->endSection() ?>
