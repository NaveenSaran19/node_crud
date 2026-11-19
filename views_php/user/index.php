<?= view('user/header') ?>

    <main style="flex-grow: 1;">
        <!-- Hero Section with Multi-Banner Carousel & Centered Explore Button -->
        <section class="hero-section" id="hero-slider-section">
            <div class="hero-slides-wrapper" id="heroSlidesWrapper">
                <?php if(!empty($hero_banners)): ?>
                    <?php foreach($hero_banners as $bIdx => $b): 
                        $bMediaUrl = base_url(ltrim($b['media_url'], '/'));
                        $bMediaType = $b['media_type'] ?? 'image';
                        $bTitle = !empty($b['title']) ? $b['title'] : 'Pure Silk & Handloom Sarees';
                        $bLink = !empty($b['link_url']) ? $b['link_url'] : (!empty($b['link']) ? $b['link'] : base_url('collections/new-arrivals'));
                    ?>
                        <div class="hero-slide <?= $bIdx === 0 ? 'active' : '' ?>" data-index="<?= $bIdx ?>">
                            <div class="hero-media-box">
                                <?php if($bMediaType === 'video'): ?>
                                    <video autoplay loop muted playsinline class="hero-slide-media hero-video">
                                        <source src="<?= $bMediaUrl ?>" type="video/mp4">
                                    </video>
                                <?php else: ?>
                                    <img src="<?= $bMediaUrl ?>" alt="<?= esc($bTitle) ?>" class="hero-slide-media" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';">
                                <?php endif; ?>
                                <div class="hero-overlay-scrim"></div>
                            </div>
                            <div class="hero-content">
                                <span class="hero-tagline"><i class="fa-solid fa-sparkles"></i> Radiate Elegance Every Day</span>
                                <h1 class="hero-title"><?= esc($bTitle) ?></h1>
                                <p class="hero-subtitle">Discover handcrafted pure silk, soft cotton &amp; bridal handloom sarees crafted with timeless artistry.</p>
                                <div class="hero-btn-wrap">
                                    <a href="<?= esc($bLink) ?>" class="hero-btn hero-explore-btn">
                                        Shop Now <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                    <a href="<?= base_url('collections') ?>" class="hero-secondary-btn">
                                        Explore Categories <i class="fa-solid fa-table-cells-large"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="hero-floating-badge">
                                <div class="badge-val">100%</div>
                                <div class="badge-label">Original Weaves</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="hero-slide active" data-index="0">
                        <div class="hero-media-box">
                            <video autoplay loop muted playsinline class="hero-slide-media hero-video">
                                <source src="https://static.videezy.com/system/resources/previews/000/041/192/original/4K_Silk_Fabric_Background_1.mp4" type="video/mp4">
                            </video>
                            <div class="hero-overlay-scrim"></div>
                        </div>
                        <div class="hero-content">
                            <span class="hero-tagline"><i class="fa-solid fa-sparkles"></i> Radiate Elegance Every Day</span>
                            <h1 class="hero-title">Timeless Handloom Sarees for Every Occasion</h1>
                            <p class="hero-subtitle">Discover handcrafted pure silk, soft cotton &amp; bridal handlooms direct from Elampillai master weavers.</p>
                            <div class="hero-btn-wrap">
                                <a href="<?= base_url('collections/new-arrivals') ?>" class="hero-btn hero-explore-btn">
                                    Shop Now <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <a href="<?= base_url('collections') ?>" class="hero-secondary-btn">
                                    Explore Categories <i class="fa-solid fa-table-cells-large"></i>
                                </a>
                            </div>
                        </div>
                        <div class="hero-floating-badge">
                            <div class="badge-val">100%</div>
                            <div class="badge-label">Original Weaves</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if(!empty($hero_banners) && count($hero_banners) > 1): ?>
                <!-- Hero Slider Arrows -->
                <button type="button" class="hero-slider-arrow hero-prev-btn" id="heroPrevBtn" aria-label="Previous Slide">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="hero-slider-arrow hero-next-btn" id="heroNextBtn" aria-label="Next Slide">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <!-- Hero Indicator Dots -->
                <div class="hero-slider-dots" id="heroSliderDots">
                    <?php foreach($hero_banners as $dIdx => $dBanner): ?>
                        <button type="button" class="hero-dot <?= $dIdx === 0 ? 'active' : '' ?>" data-slide="<?= $dIdx ?>" aria-label="Go to slide <?= $dIdx + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>

                <!-- Hero Carousel Auto-Play & Navigation Script -->
                <script>
                (function() {
                    var slides = document.querySelectorAll('.hero-slide');
                    if (!slides || slides.length <= 1) return;

                    var currentIdx = 0;
                    var total = slides.length;
                    var dots = document.querySelectorAll('.hero-dot');
                    var timer = null;

                    function goToSlide(index) {
                        slides.forEach(function(s) { s.classList.remove('active'); });
                        dots.forEach(function(d) { d.classList.remove('active'); });

                        currentIdx = (index + total) % total;
                        slides[currentIdx].classList.add('active');
                        if (dots[currentIdx]) dots[currentIdx].classList.add('active');
                    }

                    function nextSlide() {
                        goToSlide(currentIdx + 1);
                    }

                    function prevSlide() {
                        goToSlide(currentIdx - 1);
                    }

                    function startAutoSlide() {
                        stopAutoSlide();
                        timer = setInterval(nextSlide, 5000);
                    }

                    function stopAutoSlide() {
                        if (timer) {
                            clearInterval(timer);
                            timer = null;
                        }
                    }

                    var nextBtn = document.getElementById('heroNextBtn');
                    var prevBtn = document.getElementById('heroPrevBtn');
                    if (nextBtn) {
                        nextBtn.addEventListener('click', function() {
                            nextSlide();
                            startAutoSlide();
                        });
                    }
                    if (prevBtn) {
                        prevBtn.addEventListener('click', function() {
                            prevSlide();
                            startAutoSlide();
                        });
                    }

                    dots.forEach(function(dot) {
                        dot.addEventListener('click', function() {
                            var target = parseInt(this.getAttribute('data-slide'), 10);
                            if (!isNaN(target)) {
                                goToSlide(target);
                                startAutoSlide();
                            }
                        });
                    });

                    var heroSection = document.getElementById('hero-slider-section');
                    if (heroSection) {
                        heroSection.addEventListener('mouseenter', stopAutoSlide);
                        heroSection.addEventListener('mouseleave', startAutoSlide);

                        // Mobile Touch swipe
                        var touchStartX = 0;
                        var touchEndX = 0;
                        heroSection.addEventListener('touchstart', function(e) {
                            if (e.changedTouches && e.changedTouches[0]) {
                                touchStartX = e.changedTouches[0].screenX;
                            }
                        }, { passive: true });
                        heroSection.addEventListener('touchend', function(e) {
                            if (e.changedTouches && e.changedTouches[0]) {
                                touchEndX = e.changedTouches[0].screenX;
                                if (touchEndX < touchStartX - 40) {
                                    nextSlide();
                                    startAutoSlide();
                                } else if (touchEndX > touchStartX + 40) {
                                    prevSlide();
                                    startAutoSlide();
                                }
                            }
                        }, { passive: true });
                    }

                    startAutoSlide();
                })();
                </script>
            <?php endif; ?>
        </section>

        <!-- Establishment and Trust Highlights Section -->
        <?php
            $trustCardsVisible = ($settings['trust_cards_enabled'] ?? '1') === '1';
            if ($trustCardsVisible):
                $defaultTrustCards = [
                    [
                        'icon' => 'fa-solid fa-award',
                        'title' => '100% Original',
                        'desc' => 'Authentic & trusted weaves',
                        'order' => 1,
                        'visible' => 1
                    ],
                    [
                        'icon' => 'fa-solid fa-certificate',
                        'title' => 'Expert Approved',
                        'desc' => 'Silk Mark verified weaves',
                        'order' => 2,
                        'visible' => 1
                    ],
                    [
                        'icon' => 'fa-solid fa-truck-fast',
                        'title' => 'Fast Delivery',
                        'desc' => 'Express delivery to doorstep',
                        'order' => 3,
                        'visible' => 1
                    ],
                    [
                        'icon' => 'fa-solid fa-arrows-rotate',
                        'title' => 'Easy Returns',
                        'desc' => 'Hassle-free exchanges',
                        'order' => 4,
                        'visible' => 1
                    ]
                ];

                $trustCards = [];
                if (!empty($settings['trust_cards_data'])) {
                    $decoded = json_decode($settings['trust_cards_data'], true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $trustCards = $decoded;
                    }
                }
                if (empty($trustCards)) {
                    $trustCards = $defaultTrustCards;
                }

                // Filter by visibility and sort by order
                $activeTrustCards = array_filter($trustCards, function($c) {
                    return !isset($c['visible']) || $c['visible'] == 1 || $c['visible'] === '1';
                });

                usort($activeTrustCards, function($a, $b) {
                    return (int)($a['order'] ?? 0) <=> (int)($b['order'] ?? 0);
                });

                if (!empty($activeTrustCards)):
        ?>
        <section class="trust-highlights-section" aria-label="Establishment and Trust Highlights">
            <div class="trust-highlights-container">
                <div class="trust-cards-grid">
                    <?php foreach($activeTrustCards as $card): ?>
                        <div class="trust-card">
                            <div class="trust-icon-wrapper">
                                <i class="<?= esc($card['icon'] ?? 'fa-solid fa-certificate') ?>"></i>
                            </div>
                            <div class="trust-card-text">
                                <h3 class="trust-card-title"><?= esc($card['title'] ?? '') ?></h3>
                                <p class="trust-card-desc"><?= esc($card['desc'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php 
                endif;
            endif; 
        ?>

        <!-- Shop by Category Section (Matching Glow Modern Theme) -->
        <?php if(!empty($categories)): ?>
        <section class="shop-by-category-section" style="margin: 30px auto 45px auto; max-width: 1380px;">
            <div class="section-header-modern">
                <h2 class="section-title-modern">Shop by Category</h2>
                <a href="<?= base_url('collections') ?>" class="section-view-all-link">
                    View All Categories <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="category-showcase-grid">
                <?php foreach($categories as $cat): 
                    $catImg = !empty($cat['image_url']) ? base_url($cat['image_url']) : base_url('assets/images/placeholder.jpg');
                    $catSlug = !empty($cat['slug']) ? $cat['slug'] : url_title($cat['name'], '-', true);
                ?>
                    <a href="<?= base_url('category/' . esc($catSlug)) ?>" class="category-modern-card">
                        <div class="category-img-wrapper">
                            <img src="<?= $catImg ?>" alt="<?= esc($cat['name']) ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';">
                        </div>
                        <h3 class="category-card-name"><?= esc($cat['name']) ?></h3>
                        <span class="category-card-count">Explore Weaves &rarr;</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Limited Time Festive Offer Banner (Matching Reference Design) -->
        <section class="promo-festive-banner">
            <div class="promo-banner-inner">
                <div>
                    <span class="promo-banner-badge"><i class="fa-solid fa-clock"></i> Limited Time Festive Offer</span>
                    <h2 class="promo-banner-title">Up to 30% OFF</h2>
                    <p class="promo-banner-subtitle">On Pure Silk, Soft Silk &amp; Festive Wedding Sarees</p>
                </div>
                <a href="<?= base_url('collections/new-arrivals') ?>" class="promo-banner-btn">
                    Shop the Sale <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <!-- Best Sellers Section (Modern Grid matching Reference Image) -->
        <section class="collection-section best-sellers-section" style="margin-bottom: 50px; background: transparent;">
            <div class="section-header-modern">
                <h2 class="section-title-modern">Best Sellers</h2>
                <a href="<?= base_url('collections/best-sellers') ?>" class="section-view-all-link">
                    View All Products <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="product-grid-modern">
                <?php if(!empty($best_sellers)): foreach($best_sellers as $item): 
                    $imgUrl = base_url('assets/images/placeholder.jpg');
                    if(!empty($item['image_url'])) {
                        $imgUrl = (strpos($item['image_url'], 'http') === 0) ? $item['image_url'] : base_url(ltrim($item['image_url'], '/'));
                    }
                    $salePrice = Number($item['price'] || 0);
                    $origPrice = Number($item['original_price'] || 0);
                    if ($origPrice <= $salePrice) $origPrice = round($salePrice * 1.3);
                    $discountPct = round((($origPrice - $salePrice) / $origPrice) * 100);
                ?>
                    <div class="product-card-modern">
                        <div class="product-img-wrap">
                            <a href="<?= base_url('product/' . esc($item['slug'])) ?>">
                                <img src="<?= $imgUrl ?>" alt="<?= esc($item['name']) ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';">
                            </a>
                            <?php if($discountPct > 0): ?>
                                <span class="product-badge-discount"><?= $discountPct ?>% OFF</span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('product/' . esc($item['slug'])) ?>" class="product-card-title">
                            <?= esc($item['name']) ?>
                        </a>
                        <div class="product-card-rating">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                            <span>(<?= rand(80, 320) ?>)</span>
                        </div>
                        <div class="product-card-prices">
                            <span class="product-price-current">₹<?= number_format($salePrice, 2) ?></span>
                            <?php if($origPrice > $salePrice): ?>
                                <span class="product-price-original">₹<?= number_format($origPrice, 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn-add-cart-pill" onclick="quickAddToCart(<?= (int)$item['id'] ?>)">
                            <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                        </button>
                    </div>
                <?php endforeach; else: ?>
                    <p style="text-align: center; grid-column: 1/-1; color: var(--theme-text-muted);">No best sellers found.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Dynamic Dots Controller Script for Mobile Swipeable Circular Lists -->
        <script>
        (function() {
            function setupCircularCarousel(listId, dotsId) {
                var list = document.getElementById(listId);
                var dotsContainer = document.getElementById(dotsId);
                if (!list || !dotsContainer) return;

                var items = list.querySelectorAll('.circle-item');
                if (items.length <= 1) return;

                var totalDots = Math.min(items.length, 5);
                dotsContainer.innerHTML = '';
                for (var i = 0; i < totalDots; i++) {
                    var dot = document.createElement('span');
                    dot.className = 'dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('data-idx', i);
                    dotsContainer.appendChild(dot);
                }

                var dots = dotsContainer.querySelectorAll('.dot');
                list.addEventListener('scroll', function() {
                    var maxScroll = list.scrollWidth - list.clientWidth;
                    if (maxScroll <= 0) return;
                    var scrollFraction = list.scrollLeft / maxScroll;
                    var activeIdx = Math.min(totalDots - 1, Math.max(0, Math.round(scrollFraction * (totalDots - 1))));
                    dots.forEach(function(d, idx) {
                        if (idx === activeIdx) {
                            d.classList.add('active');
                        } else {
                            d.classList.remove('active');
                        }
                    });
                }, { passive: true });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setupCircularCarousel('liveCollectionsList', 'liveCollectionsDots');
                    setupCircularCarousel('bestSellersList', 'bestSellersDots');
                });
            } else {
                setupCircularCarousel('liveCollectionsList', 'liveCollectionsDots');
                setupCircularCarousel('bestSellersList', 'bestSellersDots');
            }
        })();
        </script>

        <!-- Shop By Budget Section -->
        <?php
            $isBudgetEnabled = ($settings['home_budget_enabled'] ?? ($settings['module_budget'] ?? '1')) === '1';
            if ($isBudgetEnabled):
                $defaultBudgetTiers = [
                    [
                        'badge' => 'BELOW',
                        'price' => 'Rs.500/-',
                        'title' => 'Below Rs 499/- Collection',
                        'link' => 'collections?max_price=499',
                        'visible' => 1
                    ],
                    [
                        'badge' => 'BETWEEN',
                        'price' => 'Rs.501 to 1000/-',
                        'title' => 'From Rs 500 To Rs 999/-',
                        'link' => 'collections?min_price=500&max_price=999',
                        'visible' => 1
                    ],
                    [
                        'badge' => 'BETWEEN',
                        'price' => 'Rs.1001 to 2000/-',
                        'title' => 'From Rs 1001 To Rs 2000/-',
                        'link' => 'collections?min_price=1001&max_price=2000',
                        'visible' => 1
                    ],
                    [
                        'badge' => 'ABOVE',
                        'price' => 'Rs.2000/-',
                        'title' => 'Above Rs 2000/- Collection',
                        'link' => 'collections?min_price=2000',
                        'visible' => 1
                    ]
                ];

                $activeBudgetTiers = [];
                if (!empty($settings['home_budget_tiers'])) {
                    $decodedTiers = json_decode($settings['home_budget_tiers'], true);
                    if (is_array($decodedTiers) && !empty($decodedTiers)) {
                        $activeBudgetTiers = $decodedTiers;
                    }
                }
                if (empty($activeBudgetTiers)) {
                    $activeBudgetTiers = $defaultBudgetTiers;
                }

                $activeBudgetTiers = array_filter($activeBudgetTiers, function($t) {
                    return !isset($t['visible']) || $t['visible'] == 1 || $t['visible'] === '1';
                });

                if (!empty($activeBudgetTiers)):
        ?>
        <section class="budget-section" aria-label="Shop by Budget">
            <h2 class="section-title" style="color: #1a237e; margin-bottom: 8px;">
                <i class="fa-solid fa-wallet"></i> <?= esc($settings['home_budget_title'] ?? 'Shop by Budget') ?>
            </h2>
            <?php if (!empty($settings['home_budget_subtitle'])): ?>
                <p style="color: #64748b; font-size: 14px; margin-top: 0; margin-bottom: 25px; font-weight: 500;">
                    <?= esc($settings['home_budget_subtitle']) ?>
                </p>
            <?php endif; ?>
            <div class="budget-container">
                <?php foreach($activeBudgetTiers as $tier): ?>
                    <?php
                        $tierLink = !empty($tier['link']) ? $tier['link'] : 'collections';
                        if (strpos($tierLink, 'http') !== 0 && strpos($tierLink, '/') !== 0) {
                            $tierUrl = base_url($tierLink);
                        } elseif (strpos($tierLink, '/') === 0) {
                            $tierUrl = base_url(ltrim($tierLink, '/'));
                        } else {
                            $tierUrl = $tierLink;
                        }
                    ?>
                    <a href="<?= esc($tierUrl) ?>" style="text-decoration: none; color: inherit;" title="<?= esc($tier['title'] ?? '') ?>">
                        <div class="budget-item">
                            <div class="budget-badge">
                                <div class="budget-diamond">
                                    <div class="budget-text"><?= esc($tier['badge'] ?? '') ?></div>
                                </div>
                                <div class="budget-ribbon"><?= esc($tier['price'] ?? '') ?></div>
                            </div>
                            <p><?= esc($tier['title'] ?? '') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php 
                endif;
            endif; 
        ?>

        <!-- Second Banner Carousel -->
        <section class="secondary-banner-carousel">
            <div class="carousel-container">
                <div class="carousel-track">
                    <?php if(!empty($secondary_banners)): ?>
                        <?php foreach($secondary_banners as $banner): ?>
                            <div class="carousel-slide" style="position: relative;">
                                <?php if($banner['media_type'] == 'video'): ?>
                                    <video autoplay loop muted playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;">
                                        <source src="<?= base_url($banner['media_url']) ?>" type="video/mp4">
                                    </video>
                                <?php else: ?>
                                    <img src="<?= base_url($banner['media_url']) ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" alt="Banner">
                                <?php endif; ?>
                                <div class="slide-content" style="position: relative; z-index: 2; padding: 20px 40px;">
                                    <h2 style="font-size: 50px; font-weight: 900; margin: 0; color: white; text-shadow: 2px 2px 12px rgba(0,0,0,0.8), 0 0 20px rgba(0,0,0,0.6);"><?= esc($banner['title']) ?></h2>
                                    <?php if(!empty($banner['link'])): ?>
                                        <a href="<?= esc($banner['link']) ?>" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">Explore</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback Placeholder Banner -->
                        <div class="carousel-slide" style="background-color: var(--primary-color);">
                            <div class="slide-content">
                                <h2 style="font-size: 50px; font-weight: 900; margin: 0; color: white;">FESTIVAL COLLECTION</h2>
                                <p style="font-size: 20px; letter-spacing: 2px; color: #f9d976;">EXCLUSIVE SAREES</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-btn prev-btn"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="carousel-btn next-btn"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </section>

        <!-- Diwali Saree Banner Section with Scrolling Images -->
        <?php
            // Left column scrolling images (Scroll Up)
            $diwaliLeftImgs = json_decode($settings['home_diwali_images_left'] ?? '[]', true);
            if (!is_array($diwaliLeftImgs)) $diwaliLeftImgs = [];
            $diwaliLeftImgs = array_values(array_filter($diwaliLeftImgs));
            if (empty($diwaliLeftImgs) && !empty($diwali_video_banners)) {
                $diwaliLeftImgs = array_column($diwali_video_banners, 'media_url');
            }
            // Duplicate array for smooth continuous translateY(-50%) scrolling loop
            $diwaliLeftLoop = !empty($diwaliLeftImgs) ? array_merge($diwaliLeftImgs, $diwaliLeftImgs) : [];

            // Right column scrolling images (Scroll Down)
            $diwaliRightImgs = json_decode($settings['home_diwali_images_right'] ?? '[]', true);
            if (!is_array($diwaliRightImgs)) $diwaliRightImgs = [];
            $diwaliRightImgs = array_values(array_filter($diwaliRightImgs));
            if (empty($diwaliRightImgs) && !empty($diwali_video_banners)) {
                $diwaliRightImgs = array_column($diwali_video_banners, 'media_url');
            }
            // Duplicate array for smooth continuous translateY(-50%) scrolling loop
            $diwaliRightLoop = !empty($diwaliRightImgs) ? array_merge($diwaliRightImgs, $diwaliRightImgs) : [];
        ?>
        <section class="diwali-video-banner">
            <div class="bg-image-collage">
                <div class="scrolling-column scroll-up">
                    <?php if(!empty($diwaliLeftLoop)): ?>
                        <?php foreach($diwaliLeftLoop as $imgSrc): 
                            $leftImgUrl = (strpos($imgSrc, 'http') === 0) ? $imgSrc : base_url(ltrim($imgSrc, '/'));
                        ?>
                            <img src="<?= esc($leftImgUrl) ?>" alt="Diwali Saree Collection">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="scrolling-column scroll-down">
                    <?php if(!empty($diwaliRightLoop)): ?>
                        <?php foreach($diwaliRightLoop as $imgSrc): 
                            $rightImgUrl = (strpos($imgSrc, 'http') === 0) ? $imgSrc : base_url(ltrim($imgSrc, '/'));
                        ?>
                            <img src="<?= esc($rightImgUrl) ?>" alt="Diwali Saree Collection">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="video-overlay"></div>
            <div class="banner-content">
                <p class="small-title">ARE YOU LOOKING FOR</p>
                <h2 class="main-title"><?= esc($settings['home_diwali_title'] ?? "THE BEST DIWALI SAREE'S") ?></h2>
                <p class="subtitle"><?= esc($settings['home_diwali_subtitle'] ?? 'Presenting Timeless Crafts') ?></p>
                <a href="<?= esc($settings['home_diwali_link'] ?? '#') ?>" class="explore-btn">EXPLORE</a>
            </div>
        </section>

        <!-- Best of Selvi Tex Section (Dynamic - Moving Left to Right) -->
        <?php if(!empty($best_of_ssptex)): ?>
        <section class="collection-section best-of-section" style="background-color: #ffffff; border-top: 1px solid #eaeaea; padding: 45px 0; overflow: hidden; position: relative;">
            <div class="best-of-header-wrap" style="max-width: 1400px; margin: 0 auto 24px; padding: 0 40px; position: relative; text-align: center;">
                <div style="display: inline-block; text-align: center;">
                    <h2 class="section-title" style="color: #1e293b; font-size: 26px; font-weight: 800; margin: 0; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fa-solid fa-crown" style="color: #d4af37; font-size: 22px;"></i> Best of Selvi Tex
                    </h2>
                    <p style="margin: 6px auto 0; font-size: 14px; color: #64748b; font-weight: 500; text-align: center;">Handpicked signature designs directly from our master looms</p>
                </div>
                <div class="best-of-nav-btns" style="position: absolute; right: 40px; top: 50%; transform: translateY(-50%); display: flex; gap: 8px; align-items: center;">
                    <button type="button" class="best-of-nav-btn" onclick="shiftBestOfMarquee(-220)" aria-label="Previous" title="Scroll Left" style="background: #f8fafc; border: 1.5px solid #e2e8f0; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #334155; transition: all 0.2s;">
                        <i class="fa-solid fa-chevron-left" style="font-size: 13px;"></i>
                    </button>
                    <button type="button" class="best-of-nav-btn" onclick="shiftBestOfMarquee(220)" aria-label="Next" title="Scroll Right" style="background: #f8fafc; border: 1.5px solid #e2e8f0; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #334155; transition: all 0.2s;">
                        <i class="fa-solid fa-chevron-right" style="font-size: 13px;"></i>
                    </button>
                </div>
            </div>

            <!-- Continuous Infinite Left-to-Right Moving Track -->
            <div class="best-of-marquee-wrapper" id="bestOfMarqueeWrap">
                <div class="best-of-marquee-track" id="bestOfMarqueeTrack">
                    <?php 
                        // Repeat 3 times to ensure a completely seamless continuous left-to-right flow
                        $bestOfLoop = array_merge($best_of_ssptex, $best_of_ssptex, $best_of_ssptex);
                        foreach($bestOfLoop as $item): 
                            $itemImg = !empty($item['image_url']) ? $item['image_url'] : ($item['image'] ?? '');
                            if (empty($itemImg)) continue;
                            $itemImgUrl = (strpos($itemImg, 'http') === 0) ? $itemImg : base_url(ltrim($itemImg, '/'));
                            $itemLink = !empty($item['slug']) ? base_url('product/' . esc($item['slug'])) : (!empty($item['name']) ? base_url('category/' . url_title($item['name'], '-', true)) : '#');
                    ?>
                    <a href="<?= $itemLink ?>" class="best-of-card-link" style="text-decoration: none; color: inherit; flex-shrink: 0; display: inline-block;">
                        <div class="circle-item" style="animation: none; margin: 0 10px;">
                            <div class="circle-img-wrapper" style="border: 2.5px solid #fff; box-shadow: 0 6px 18px rgba(122, 21, 48, 0.12); width: 155px; height: 155px; border-radius: 50%; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative;">
                                <img src="<?= esc($itemImgUrl) ?>" alt="<?= esc($item['name']) ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <p class="circle-title" style="font-size: 13.5px; font-weight: 700; color: #1e293b; text-align: center; margin: 10px 0 0; white-space: nowrap; max-width: 155px; overflow: hidden; text-overflow: ellipsis;"><?= esc($item['name']) ?></p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <script>
            function shiftBestOfMarquee(amount) {
                var wrap = document.getElementById('bestOfMarqueeWrap');
                if (wrap) {
                    wrap.scrollBy({ left: amount, behavior: 'smooth' });
                }
            }
        </script>
        <?php endif; ?>

        <!-- Must Visit Section (Dynamic) -->
        <?php if(($settings['module_must_visit'] ?? '1') === '1' && !empty($must_visit_products)): ?>
        <section class="must-visit-section" style="padding: 60px 40px; background-color: #fff;">
            <div class="must-visit-header" style="text-align: center; margin-bottom: 40px;">
                <p style="font-size: 14px; font-weight: 700; color: #666; letter-spacing: 2px; text-transform: uppercase; margin: 0 0 10px 0;"><?= esc($settings['home_must_visit_subtitle'] ?? 'Everyone') ?></p>
                <h2 style="font-size: 32px; font-weight: 800; color: #222; margin: 0;"><?= esc($settings['home_must_visit_title'] ?? 'Must Visit') ?></h2>
            </div>
            <div class="must-visit-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; max-width: 1400px; margin: 0 auto;">
                <?php foreach($must_visit_products as $mvItem): 
                    $mvImg = !empty($mvItem['image_url']) ? $mvItem['image_url'] : ($mvItem['image'] ?? '');
                    if (empty($mvImg)) continue;
                    $mvImgUrl = (strpos($mvImg, 'http') === 0) ? $mvImg : base_url(ltrim($mvImg, '/'));
                    $mvLink = !empty($mvItem['slug']) ? base_url('product/' . esc($mvItem['slug'])) : '#';
                ?>
                <a href="<?= $mvLink ?>" style="text-decoration: none; color: inherit; display: block;">
                    <div class="must-visit-card" style="position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 6px 18px rgba(0,0,0,0.08);">
                        <img src="<?= esc($mvImgUrl) ?>" alt="<?= esc($mvItem['name']) ?>" class="must-visit-card-img" style="width: 100%; height: 500px; object-fit: cover; display: block; transition: transform 0.5s;">
                        <div class="card-logo must-visit-card-logo" style="position: absolute; top: 15px; left: 15px; width: 60px; height: 60px; background-color: rgba(255,255,255,0.85); border-radius: 50%; display: flex; justify-content: center; align-items: center; border: 2px solid #d4af37; backdrop-filter: blur(4px);">
                            <div style="text-align: center; color: var(--primary-color); line-height: 1;">
                                <span style="font-size: 18px;"><i class="fa-solid fa-seedling"></i></span><br>
                                <span style="font-size: 10px; font-weight: bold;">ST</span>
                            </div>
                        </div>
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); padding: 25px 20px; color: #fff;">
                            <h3 style="font-size: 18px; font-weight: 700; margin: 0 0 4px 0; color: #fff;"><?= esc($mvItem['name']) ?></h3>
                            <?php if(isset($mvItem['price'])): ?>
                                <p style="margin: 0; font-size: 15px; color: #f9d976; font-weight: 700;">₹<?= number_format($mvItem['price']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- About SSSTex / Visit Showroom Section -->
        <?php 
            $aboutEnabled = ($settings['about_store_enabled'] ?? '1') === '1';
            $aboutTitle = $settings['about_store_title'] ?? 'About Sri Sakthi Selvi Tex';
            $aboutSubtitle = $settings['about_store_subtitle'] ?? 'VISIT OUR SHOWROOM';
            $aboutDesc = $settings['about_store_desc'] ?? 'Experience the finest Elampillai pattu, traditional pure silk, and authentic handloom sarees directly from the master weavers of Tamil Nadu. Visit our exclusive showroom for an unforgettable saree shopping experience with personalized assistance.';

            // Multi-location support
            $storeLocations = [];
            if (!empty($settings['about_store_locations'])) {
                $decodedLocs = json_decode($settings['about_store_locations'], true);
                if (is_array($decodedLocs) && count($decodedLocs) > 0) {
                    $storeLocations = $decodedLocs;
                }
            }
            if (empty($storeLocations)) {
                $storeLocations = [[
                    'name'     => 'Flagship Showroom (Elampillai)',
                    'address'  => $settings['about_store_address'] ?? "Sri Sakthi Selvi Tex\nMain Road, Elampillai,\nSalem District, Tamil Nadu - 637502",
                    'timings'  => $settings['about_store_timings'] ?? 'Monday - Sunday: 9:00 AM - 9:00 PM',
                    'map_link' => $settings['about_store_map_link'] ?? 'https://maps.google.com',
                    'phone'    => !empty($settings['phone_no']) ? $settings['phone_no'] : ($settings['about_store_phone'] ?? '9789296751'),
                    'whatsapp' => !empty($settings['whatsapp_no']) ? $settings['whatsapp_no'] : ($settings['about_store_whatsapp'] ?? '9789296751')
                ]];
            }
            $activeLoc = $storeLocations[0];

            $rawAboutImgs = $settings['about_store_images'] ?? '';
            $aboutImgs = !empty($rawAboutImgs) ? json_decode($rawAboutImgs, true) : [];
            if (!is_array($aboutImgs)) $aboutImgs = [];
            $aboutImgs = array_values(array_filter($aboutImgs));
        ?>
        <?php if($aboutEnabled): ?>
        <style>
            .luxury-showroom-section {
                padding: 85px 40px;
                background: linear-gradient(180deg, #faf7f2 0%, #ffffff 40%, #faf7f2 100%);
                position: relative;
                overflow: hidden;
                border-top: 1px solid #ede7de;
                border-bottom: 1px solid #ede7de;
            }
            .luxury-showroom-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                background: linear-gradient(90deg, #d4af37, #7a1530, #eab308, #7a1530, #d4af37);
                background-size: 200% 100%;
                animation: shimmerGold 8s linear infinite;
            }
            @keyframes shimmerGold {
                0% { background-position: 0% 50%; }
                100% { background-position: 200% 50%; }
            }
            .showroom-container {
                max-width: 1400px;
                margin: 0 auto;
            }
            .showroom-grid {
                display: grid;
                grid-template-columns: 1.05fr 1fr;
                gap: 55px;
                align-items: center;
            }
            .showroom-gallery-wrapper {
                position: relative;
            }
            .showroom-main-display {
                position: relative;
                width: 100%;
                height: 480px;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 20px 45px rgba(30, 20, 25, 0.14);
                border: 4px solid #ffffff;
                background: #111;
            }
            .showroom-main-display img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .showroom-main-display:hover img {
                transform: scale(1.04);
            }
            .showroom-top-pill {
                position: absolute;
                top: 18px;
                left: 18px;
                background: rgba(15, 23, 42, 0.75);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(212, 175, 55, 0.4);
                color: #fef08a;
                padding: 7px 16px;
                border-radius: 30px;
                font-size: 11.5px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 7px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.25);
            }
            .showroom-bottom-caption {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(to top, rgba(10, 5, 8, 0.92) 0%, rgba(10, 5, 8, 0.6) 65%, transparent 100%);
                padding: 30px 24px 22px 24px;
                color: #fff;
            }
            .showroom-caption-title {
                font-size: 18px;
                font-weight: 800;
                margin: 0 0 6px 0;
                letter-spacing: 0.3px;
                color: #ffffff;
                text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            }
            .showroom-caption-sub {
                font-size: 13px;
                color: #e2e8f0;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .showroom-thumbs-track {
                display: flex;
                gap: 12px;
                margin-top: 16px;
                overflow-x: auto;
                padding: 4px 2px 10px 2px;
                scrollbar-width: thin;
            }
            .showroom-thumb-card {
                position: relative;
                width: 96px;
                height: 72px;
                border-radius: 12px;
                overflow: hidden;
                cursor: pointer;
                flex-shrink: 0;
                border: 2.5px solid transparent;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                transition: all 0.25s ease;
                background: #f1f5f9;
            }
            .showroom-thumb-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .showroom-thumb-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 18px rgba(122, 21, 48, 0.2);
            }
            .showroom-thumb-card.active {
                border-color: #d4af37;
                box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.4), 0 8px 18px rgba(122, 21, 48, 0.25);
            }
            .showroom-feature-pills {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                margin-top: 16px;
            }
            .showroom-feature-pill-item {
                background: #ffffff;
                border: 1px solid #e7ded0;
                border-radius: 10px;
                padding: 10px 12px;
                display: flex;
                align-items: center;
                gap: 8px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            }
            .showroom-feature-pill-item i {
                font-size: 15px;
                color: #7a1530;
                flex-shrink: 0;
            }
            .showroom-feature-pill-item span {
                font-size: 11.5px;
                font-weight: 700;
                color: #334155;
                line-height: 1.3;
            }
            .showroom-details-wrap {
                padding-left: 5px;
            }
            .showroom-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(122, 21, 48, 0.08);
                border: 1px solid rgba(122, 21, 48, 0.18);
                color: #7a1530;
                font-size: 11.5px;
                font-weight: 800;
                padding: 6px 16px;
                border-radius: 30px;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                margin-bottom: 14px;
            }
            .showroom-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: linear-gradient(135deg, rgba(122, 21, 48, 0.08) 0%, rgba(212, 175, 55, 0.12) 100%);
                border: 1px solid rgba(122, 21, 48, 0.2);
                color: #7a1530;
                font-size: 11px;
                font-weight: 800;
                padding: 6px 18px;
                border-radius: 30px;
                letter-spacing: 2px;
                text-transform: uppercase;
                margin-bottom: 14px;
                box-shadow: 0 2px 8px rgba(122, 21, 48, 0.04);
            }
            .showroom-heading {
                font-size: 34px;
                font-weight: 900;
                color: #1a080e;
                margin: 0 0 16px 0;
                line-height: 1.25;
                font-family: 'Playfair Display', Georgia, serif;
                letter-spacing: -0.5px;
            }
            .showroom-desc-text {
                font-size: 15px;
                color: #475569;
                line-height: 1.8;
                margin: 0 0 24px 0;
            }
            .showroom-info-cards-grid {
                display: flex;
                flex-direction: column;
                gap: 14px;
                margin-bottom: 26px;
            }
            .showroom-info-card {
                background: #ffffff;
                border: 1.5px solid #ece4d8;
                border-radius: 16px;
                padding: 16px 20px;
                display: flex;
                gap: 16px;
                align-items: flex-start;
                box-shadow: 0 4px 16px rgba(40, 10, 20, 0.04);
                transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
                position: relative;
            }
            .showroom-info-card:hover {
                border-color: #d4af37;
                transform: translateY(-2px);
                box-shadow: 0 10px 24px rgba(122, 21, 48, 0.08);
            }
            .showroom-card-icon {
                width: 46px;
                height: 46px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                flex-shrink: 0;
            }
            .showroom-card-icon.red {
                background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
                color: #e11d48;
                border: 1px solid #fecdd3;
            }
            .showroom-card-icon.maroon {
                background: linear-gradient(135deg, #fdf2f4 0%, #fbe8ec 100%);
                color: #7a1530;
                border: 1px solid #f7d2da;
            }
            .showroom-card-icon.green {
                background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                color: #16a34a;
                border: 1px solid #bbf7d0;
            }
            .showroom-card-label {
                font-size: 11px;
                font-weight: 700;
                color: #8c766b;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                margin-bottom: 4px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .showroom-card-val {
                font-size: 14px;
                font-weight: 600;
                color: #1e293b;
                line-height: 1.5;
            }
            .showroom-sub-cards-row {
                display: grid;
                grid-template-columns: 1.15fr 0.85fr;
                gap: 14px;
            }
            .showroom-live-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: #15803d;
                background: #ecfdf5;
                border: 1px solid #a7f3d0;
                font-size: 10.5px;
                font-weight: 800;
                padding: 2px 8px;
                border-radius: 20px;
                letter-spacing: 0.5px;
            }
            .pulsing-live-dot {
                width: 6.5px;
                height: 6.5px;
                background-color: #16a34a;
                border-radius: 50%;
                display: inline-block;
                box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
                animation: pulseLiveDot 2s infinite;
            }
            @keyframes pulseLiveDot {
                0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
                70% { box-shadow: 0 0 0 6px rgba(22, 163, 74, 0); }
                100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
            }
            .showroom-quick-link {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                font-size: 11.5px;
                font-weight: 700;
                color: #7a1530;
                text-decoration: none;
                background: rgba(122, 21, 48, 0.06);
                padding: 3px 9px;
                border-radius: 6px;
                transition: all 0.2s ease;
            }
            .showroom-quick-link:hover {
                background: #7a1530;
                color: #ffffff;
            }
            .showroom-phone-touch-link {
                color: #1e293b;
                text-decoration: none;
                font-size: 13.5px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: color 0.2s;
            }
            .showroom-phone-touch-link:hover {
                color: #16a34a;
            }
            .showroom-cta-row {
                display: flex;
                gap: 10px;
                flex-wrap: nowrap;
                align-items: stretch;
                margin-top: 24px;
                width: 100%;
            }
            .btn-showroom-directions {
                flex: 1 1 0;
                min-width: 0;
                background: linear-gradient(135deg, #7a1530 0%, #4e0b1c 100%);
                color: #ffffff !important;
                padding: 13px 14px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 800;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                box-shadow: 0 6px 18px rgba(122, 21, 48, 0.3);
                transition: all 0.25s ease;
                border: 1px solid rgba(255,255,255,0.15);
                white-space: nowrap;
            }
            .btn-showroom-directions:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 24px rgba(122, 21, 48, 0.4);
                background: linear-gradient(135deg, #8f1938 0%, #5d0d22 100%);
            }
            .btn-showroom-whatsapp {
                flex: 1 1 0;
                min-width: 0;
                background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
                color: #ffffff !important;
                padding: 13px 14px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 800;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                box-shadow: 0 6px 18px rgba(37, 211, 102, 0.3);
                transition: all 0.25s ease;
                white-space: nowrap;
            }
            .btn-showroom-whatsapp:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 24px rgba(37, 211, 102, 0.45);
            }
            .btn-showroom-call {
                flex: 1 1 0;
                min-width: 0;
                background: #ffffff;
                color: #1e293b !important;
                padding: 13px 14px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                border: 1.5px solid #cbd5e1;
                transition: all 0.2s ease;
                white-space: nowrap;
            }
            .btn-showroom-call:hover {
                background: #f8fafc;
                border-color: #94a3b8;
                transform: translateY(-2px);
            }
            @media (max-width: 768px) {
                .showroom-cta-row {
                    flex-wrap: wrap;
                }
                .btn-showroom-directions,
                .btn-showroom-whatsapp,
                .btn-showroom-call {
                    flex: 1 1 100%;
                }
            }
            @media (max-width: 991px) {
                .luxury-showroom-section {
                    padding: 50px 20px !important;
                }
                .showroom-grid {
                    grid-template-columns: 1fr !important;
                    gap: 36px !important;
                }
                .showroom-main-display {
                    height: 320px !important;
                }
                .showroom-heading {
                    font-size: 26px !important;
                }
                .showroom-feature-pills {
                    grid-template-columns: 1fr !important;
                }
            }
            .showroom-locations-nav {
                display: flex;
                gap: 10px;
                margin-bottom: 22px;
                flex-wrap: wrap;
            }
            .showroom-loc-tab {
                background: #ffffff;
                border: 1.5px solid #cbd5e1;
                color: #475569;
                padding: 9px 18px;
                border-radius: 30px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.25s ease;
                box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            }
            .showroom-loc-tab:hover {
                border-color: #7a1530;
                color: #7a1530;
                transform: translateY(-1px);
            }
            .showroom-loc-tab.active {
                background: linear-gradient(135deg, #7a1530 0%, #4a0d1d 100%);
                border-color: #7a1530;
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(122, 21, 48, 0.25);
            }
            .showroom-loc-tab.active i {
                color: #facc15;
            }
            @media (max-width: 640px) {
                .showroom-sub-cards-row {
                    grid-template-columns: 1fr !important;
                    gap: 12px !important;
                }
                .showroom-info-card {
                    padding: 14px 16px !important;
                }
            }
            @media (max-width: 576px) {
                .showroom-cta-row {
                    flex-direction: column !important;
                    gap: 10px !important;
                }
                .showroom-cta-row a {
                    width: 100% !important;
                    justify-content: center !important;
                }
            }
        </style>
        <section class="luxury-showroom-section" id="showroom">
            <div class="showroom-container">
                <div class="showroom-grid">
                    <!-- Left Column: Interactive Luxury Showcase Gallery -->
                    <div class="showroom-gallery-wrapper">
                        <?php if(!empty($aboutImgs)): 
                            $mainImgSrc = (strpos($aboutImgs[0], 'http') === 0) ? $aboutImgs[0] : base_url(ltrim($aboutImgs[0], '/'));
                        ?>
                            <!-- Main Visual Showcase Display -->
                            <div class="showroom-main-display">
                                <img id="showroom-active-photo" src="<?= esc($mainImgSrc) ?>" alt="<?= esc($aboutTitle) ?>">

                                <div class="showroom-bottom-caption">
                                    <div class="showroom-caption-title"><?= esc($aboutTitle) ?></div>
                                    <p class="showroom-caption-sub">
                                        <i class="fa-solid fa-location-dot" style="color: #f87171;"></i>
                                        <span id="showroom-active-city-label"><?= esc($activeLoc['name'] ?? 'Salem District, Tamil Nadu') ?></span>
                                        <span style="opacity: 0.6;">•</span>
                                        <span id="showroom-slide-counter">Photo 1 of <?= count($aboutImgs) ?></span>
                                    </p>
                                </div>
                            </div>

                            <!-- Thumbnails Strip (if multiple photos uploaded) -->
                            <?php if(count($aboutImgs) > 1): ?>
                            <div class="showroom-thumbs-track">
                                <?php foreach($aboutImgs as $idx => $imgItem): 
                                    $imgSrc = (strpos($imgItem, 'http') === 0) ? $imgItem : base_url(ltrim($imgItem, '/'));
                                ?>
                                <div onclick="document.getElementById('showroom-active-photo').src = '<?= esc($imgSrc) ?>'; document.querySelectorAll('.showroom-thumb-card').forEach(el => el.classList.remove('active')); this.classList.add('active'); document.getElementById('showroom-slide-counter').textContent = 'Photo <?= $idx+1 ?> of <?= count($aboutImgs) ?>';" class="showroom-thumb-card <?= $idx === 0 ? 'active' : '' ?>">
                                    <img src="<?= esc($imgSrc) ?>" alt="Showroom View <?= $idx+1 ?>">
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <!-- Fallback Luxury Card when no images uploaded yet -->
                            <div class="showroom-main-display" style="background: linear-gradient(135deg, #7a1530 0%, #3e0714 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; padding: 32px; text-align: center;">
                                <div style="width: 76px; height: 76px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 16px; border: 2px solid rgba(212, 175, 55, 0.4); box-shadow: 0 8px 24px rgba(0,0,0,0.25);">
                                    <i class="fa-solid fa-store" style="color: #facc15;"></i>
                                </div>
                                <div style="background: rgba(212, 175, 55, 0.2); color: #fef08a; font-size: 11px; font-weight: 800; padding: 4px 16px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; border: 1px solid rgba(212, 175, 55, 0.35);">FLAGSHIP WEAVING SHOWROOM</div>
                                <h3 style="font-size: 24px; font-weight: 800; margin: 0 0 10px 0; color: #fff; font-family: 'Playfair Display', serif;">Sri Sakthi Selvi Tex</h3>
                                <p style="font-size: 13.5px; color: rgba(255,255,255,0.85); max-width: 380px; margin: 0; line-height: 1.6;">Main Road, Elampillai, Salem District, Tamil Nadu - 637502.<br><span style="font-size: 11.5px; color: rgba(255,255,255,0.6);">(Upload showroom gallery photos in Admin Settings &gt; Homepage)</span></p>
                            </div>
                        <?php endif; ?>

                        <!-- 3 Luxury Feature Highlights -->
                        <div class="showroom-feature-pills">
                            <div class="showroom-feature-pill-item">
                                <i class="fa-solid fa-gem"></i>
                                <span>100% Authentic Handloom Pattu</span>
                            </div>
                            <div class="showroom-feature-pill-item">
                                <i class="fa-solid fa-tags"></i>
                                <span>Direct Weaver Loom Prices</span>
                            </div>
                            <div class="showroom-feature-pill-item">
                                <i class="fa-solid fa-video"></i>
                                <span>Live Video Shopping Call</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Showroom Narrative & Live Details -->
                    <div class="showroom-details-wrap">
                        <div class="showroom-eyebrow">
                            <i class="fa-solid fa-sparkles" style="color: #d4af37;"></i>
                            <span><?= esc($aboutSubtitle) ?></span>
                        </div>

                        <h2 class="showroom-heading">
                            <?= esc($aboutTitle) ?>
                        </h2>

                        <p class="showroom-desc-text">
                            <?= nl2br(esc($aboutDesc)) ?>
                        </p>

                        <!-- Multi-Location Selection Tabs (if 2 or more locations configured) -->
                        <?php if (count($storeLocations) > 1): ?>
                        <div class="showroom-locations-nav">
                            <?php foreach($storeLocations as $lIdx => $loc): ?>
                            <button type="button" class="showroom-loc-tab <?= $lIdx === 0 ? 'active' : '' ?>" onclick="switchShowroomLocation(<?= $lIdx ?>, this)">
                                <i class="fa-solid fa-location-dot"></i>
                                <span><?= esc($loc['name']) ?></span>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <?php 
                            $mapLink = !empty($activeLoc['map_link']) ? $activeLoc['map_link'] : 'https://maps.google.com';
                            $waNo = !empty($activeLoc['whatsapp']) ? preg_replace('/[^0-9]/', '', $activeLoc['whatsapp']) : '919789296751';
                            if (strlen($waNo) === 10) { $waNo = '91' . $waNo; }
                            elseif (strlen($waNo) === 11 && strpos($waNo, '0') === 0) { $waNo = '91' . substr($waNo, 1); }
                            $phoneNo = !empty($activeLoc['phone']) ? preg_replace('/[^0-9+]/', '', $activeLoc['phone']) : '+919789296751';
                        ?>

                        <!-- Information Cards Grid -->
                        <div class="showroom-info-cards-grid">
                            <!-- Showroom Address Card -->
                            <div class="showroom-info-card">
                                <div class="showroom-card-icon red">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; flex-wrap: wrap; gap: 6px;">
                                        <div class="showroom-card-label" style="margin-bottom: 0;">
                                            <span id="showroom-active-loc-title"><?= esc($activeLoc['name'] ?? 'Showroom Address') ?></span>
                                        </div>
                                        <a href="<?= esc($mapLink) ?>" id="showroom-quick-map-link" target="_blank" class="showroom-quick-link" title="Open in Google Maps">
                                            <span>Map</span> <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                    <div class="showroom-card-val" id="showroom-active-address">
                                        <?= nl2br(esc($activeLoc['address'])) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Working Hours Card & Contact (Responsive Grid: side-by-side on desktop, stacked comfortably on mobile) -->
                            <div class="showroom-sub-cards-row">
                                <div class="showroom-info-card">
                                    <div class="showroom-card-icon maroon">
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div class="showroom-card-label" style="margin-bottom: 4px;">
                                            <span class="showroom-live-pill"><span class="pulsing-live-dot"></span> OPEN DAILY</span>
                                        </div>
                                        <div class="showroom-card-val" id="showroom-active-timings" style="font-size: 13.5px; word-break: normal;">
                                            <?= esc($activeLoc['timings']) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="showroom-info-card">
                                    <div class="showroom-card-icon green">
                                        <i class="fa-solid fa-headset"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div class="showroom-card-label" style="margin-bottom: 4px;">
                                            <span>INQUIRIES</span>
                                        </div>
                                        <div class="showroom-card-val">
                                            <a href="tel:<?= esc($phoneNo) ?>" id="showroom-active-phone-anchor" class="showroom-phone-touch-link">
                                                <span id="showroom-active-phone"><?= esc($activeLoc['phone']) ?></span>
                                                <i class="fa-solid fa-phone" style="font-size: 11px; opacity: 0.6;"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Call To Action Buttons -->
                        <div class="showroom-cta-row">
                            <a href="<?= esc($mapLink) ?>" id="showroom-btn-directions" target="_blank" class="btn-showroom-directions">
                                <i class="fa-solid fa-diamond-turn-right"></i>
                                <span>Get Driving Directions</span>
                            </a>

                            <a href="https://wa.me/<?= esc($waNo) ?>?text=<?= urlencode('Hello Selvi Tex, I would like to visit your showroom (' . ($activeLoc['name'] ?? '') . ') and enquire about exclusive saree collections.') ?>" id="showroom-btn-whatsapp" target="_blank" class="btn-showroom-whatsapp">
                                <i class="fa-brands fa-whatsapp" style="font-size: 17px;"></i>
                                <span>Chat on WhatsApp</span>
                            </a>

                            <a href="tel:<?= esc($phoneNo) ?>" id="showroom-btn-call" class="btn-showroom-call">
                                <i class="fa-solid fa-phone" style="color: #7a1530;"></i>
                                <span>Call Showroom</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
        var storefrontShowroomLocations = <?= json_encode($storeLocations) ?>;

        function switchShowroomLocation(idx, btn) {
            if (!storefrontShowroomLocations || !storefrontShowroomLocations[idx]) return;
            var loc = storefrontShowroomLocations[idx];

            document.querySelectorAll('.showroom-loc-tab').forEach(function(el) {
                el.classList.remove('active');
            });
            if (btn) btn.classList.add('active');

            var titleEl = document.getElementById('showroom-active-loc-title');
            if (titleEl) titleEl.textContent = loc.name || 'Showroom Address';

            var cityLabel = document.getElementById('showroom-active-city-label');
            if (cityLabel) cityLabel.textContent = loc.name || 'Showroom';

            var pillText = document.getElementById('showroom-top-pill-text');
            if (pillText) pillText.textContent = (loc.name || 'SHOWROOM').toUpperCase();

            var addrEl = document.getElementById('showroom-active-address');
            if (addrEl) addrEl.innerHTML = (loc.address || '').replace(/\n/g, '<br>');

            var timeEl = document.getElementById('showroom-active-timings');
            if (timeEl) timeEl.textContent = loc.timings || 'Monday - Sunday: 9:00 AM - 9:00 PM';

            var phoneEl = document.getElementById('showroom-active-phone');
            if (phoneEl) phoneEl.textContent = loc.phone || '';

            var phoneAnchor = document.getElementById('showroom-active-phone-anchor');
            if (phoneAnchor) {
                var cleanPhone = (loc.phone || '').replace(/[^0-9+]/g, '') || '+919789296751';
                phoneAnchor.href = 'tel:' + cleanPhone;
            }

            var quickMap = document.getElementById('showroom-quick-map-link');
            if (quickMap) quickMap.href = loc.map_link || 'https://maps.google.com';

            var dirBtn = document.getElementById('showroom-btn-directions');
            if (dirBtn) dirBtn.href = loc.map_link || 'https://maps.google.com';

            var waBtn = document.getElementById('showroom-btn-whatsapp');
            if (waBtn) {
                var cleanWa = (loc.whatsapp || '').replace(/[^0-9]/g, '') || '919789296751';
                if (cleanWa.length === 10) { cleanWa = '91' + cleanWa; }
                else if (cleanWa.length === 11 && cleanWa.startsWith('0')) { cleanWa = '91' + cleanWa.substring(1); }
                waBtn.href = 'https://wa.me/' + cleanWa + '?text=' + encodeURIComponent('Hello Selvi Tex, I would like to visit your ' + (loc.name || 'showroom') + ' and enquire about exclusive saree collections.');
            }

            var callBtn = document.getElementById('showroom-btn-call');
            if (callBtn) {
                var cleanPhone = (loc.phone || '').replace(/[^0-9+]/g, '') || '+919789296751';
                callBtn.href = 'tel:' + cleanPhone;
            }
        }
        </script>
        <?php endif; ?>

        <!-- Shorts Products Section -->
        <?php
            $rawChannels = json_decode($settings['youtube_channel_tabs'] ?? ($settings['home_shorts_channels'] ?? '[]'), true);
            $activeChannels = [];
            if (!empty($rawChannels) && is_array($rawChannels)) {
                foreach ($rawChannels as $c) {
                    if (!isset($c['visible']) || $c['visible'] == 1) {
                        $activeChannels[] = $c;
                    }
                }
            }
            if (empty($activeChannels)) {
                $activeChannels = [
                    ['name' => 'SRI SAKTHI Selvi', 'target' => 'sri_sakthi_selvi', 'platform' => 'youtube'],
                    ['name' => 'OFFER CHANNEL', 'target' => 'offer_channel', 'platform' => 'youtube'],
                    ['name' => 'ELITE C', 'target' => 'elite_c', 'platform' => 'youtube'],
                ];
            }
            $defaultChannel = $activeChannels[0]['name'] ?? 'SRI SAKTHI Selvi';
        ?>
        <section class="shorts-products-section" style="padding: 65px 24px; background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%); border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
            <style>
                .shorts-platform-filter-bar {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 12px;
                    margin: 24px auto 35px auto;
                    flex-wrap: wrap;
                }
                .shorts-platform-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 9px 22px;
                    border-radius: 30px;
                    font-size: 13.5px;
                    font-weight: 700;
                    border: 1.5px solid #e2e8f0;
                    background: #ffffff;
                    color: #475569;
                    cursor: pointer;
                    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
                    outline: none;
                }
                .shorts-platform-btn:hover {
                    border-color: #cbd5e1;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
                }
                .shorts-platform-btn.active {
                    background: #1e293b;
                    color: #ffffff;
                    border-color: #1e293b;
                    box-shadow: 0 4px 14px rgba(30, 41, 59, 0.25);
                }
                .shorts-platform-btn.platform-yt.active {
                    background: #ef4444;
                    border-color: #ef4444;
                    color: #ffffff;
                    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
                }
                .shorts-platform-btn.platform-yt.active i {
                    color: #ffffff !important;
                }
                .shorts-platform-btn.platform-ig.active {
                    background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
                    border-color: #dc2743;
                    color: #ffffff;
                    box-shadow: 0 4px 14px rgba(220, 39, 67, 0.35);
                }
                .shorts-platform-btn.platform-ig.active i {
                    color: #ffffff !important;
                }
                .shorts-platform-btn.platform-fb.active {
                    background: #1877f2;
                    border-color: #1877f2;
                    color: #ffffff;
                    box-shadow: 0 4px 14px rgba(24, 119, 242, 0.35);
                }
                .shorts-platform-btn.platform-fb.active i {
                    color: #ffffff !important;
                }

                .shorts-subcat-container {
                    display: flex;
                    justify-content: center;
                    gap: 30px;
                    margin-bottom: 45px;
                    flex-wrap: wrap;
                }
                .shorts-subcat-card {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    cursor: pointer;
                    text-align: center;
                    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
                    background: transparent;
                    border: none;
                    padding: 6px 10px;
                    max-width: 210px;
                    outline: none;
                }
                .shorts-subcat-card:hover {
                    transform: translateY(-5px);
                }
                .shorts-subcat-img-wrap {
                    position: relative;
                    width: 140px;
                    height: 140px;
                    border-radius: 50%;
                    padding: 4px;
                    background: #ffffff;
                    box-shadow: 0 6px 20px rgba(0,0,0,0.07);
                    border: 3px solid #e2e8f0;
                    transition: all 0.3s ease;
                    margin-bottom: 12px;
                }
                .shorts-subcat-img-inner {
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    overflow: hidden;
                    background: #f1f5f9;
                }
                .shorts-subcat-img-inner img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.4s ease;
                }
                .shorts-subcat-card:hover .shorts-subcat-img-inner img {
                    transform: scale(1.08);
                }
                .shorts-subcat-card.active .shorts-subcat-img-wrap {
                    border-color: var(--primary-color, #7a1530);
                    box-shadow: 0 0 0 4px rgba(122, 21, 48, 0.22), 0 10px 25px rgba(122, 21, 48, 0.28);
                    transform: scale(1.05);
                }
                .shorts-subcat-yt-badge {
                    position: absolute;
                    bottom: 4px;
                    right: 4px;
                    width: 32px;
                    height: 32px;
                    background: #ffffff;
                    border: 2px solid #ef4444;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #ef4444;
                    box-shadow: 0 3px 8px rgba(0,0,0,0.18);
                    font-size: 14px;
                    transition: transform 0.2s ease;
                }
                .shorts-subcat-card:hover .shorts-subcat-yt-badge {
                    transform: scale(1.1);
                }
                .shorts-subcat-title {
                    font-size: 13px;
                    font-weight: 700;
                    color: #334155;
                    letter-spacing: 0.5px;
                    text-transform: uppercase;
                    transition: color 0.2s ease;
                    line-height: 1.35;
                    margin: 0;
                    max-width: 170px;
                }
                .shorts-subcat-card.active .shorts-subcat-title {
                    color: var(--primary-color, #7a1530);
                    font-weight: 800;
                }
                .shorts-subcat-active-pill {
                    margin-top: 6px;
                    font-size: 10px;
                    font-weight: 800;
                    padding: 3px 12px;
                    border-radius: 12px;
                    background: var(--primary-color, #7a1530);
                    color: #ffffff;
                    letter-spacing: 0.5px;
                    display: none;
                    text-transform: uppercase;
                    box-shadow: 0 2px 6px rgba(122, 21, 48, 0.3);
                }
                .shorts-subcat-card.active .shorts-subcat-active-pill {
                    display: inline-block;
                }
                .shorts-product-card {
                    background: #ffffff;
                    padding: 16px;
                    border-radius: 12px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
                    border: 1px solid #f1f5f9;
                    position: relative;
                    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                    display: flex;
                    flex-direction: column;
                }
                .shorts-product-card:hover {
                    transform: translateY(-6px);
                    box-shadow: 0 16px 32px rgba(0,0,0,0.1);
                    border-color: #e2e8f0;
                }
                .shorts-img-box {
                    position: relative;
                    width: 100%;
                    height: 350px;
                    border-radius: 8px;
                    overflow: hidden;
                    background: #f1f5f9;
                    margin-bottom: 14px;
                }
                .shorts-img-box img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.5s ease;
                }
                .shorts-product-card:hover .shorts-img-box img {
                    transform: scale(1.06);
                }
                .shorts-yt-badge {
                    position: absolute;
                    bottom: 10px;
                    left: 10px;
                    background: rgba(15, 23, 42, 0.88);
                    backdrop-filter: blur(4px);
                    color: #ffffff;
                    font-size: 11px;
                    font-weight: 700;
                    padding: 4px 10px;
                    border-radius: 20px;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    letter-spacing: 0.3px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
                }
                @keyframes shortsFadeIn {
                    from { opacity: 0; transform: translateY(8px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                @media (max-width: 1100px) {
                    .shorts-grid { grid-template-columns: repeat(2, 1fr) !important; }
                    .shorts-subcat-container { gap: 18px; }
                    .shorts-subcat-img-wrap { width: 110px; height: 110px; }
                }
                @media (max-width: 768px) {
                    .shorts-products-section { padding: 30px 12px !important; }
                    .shorts-platform-filter-bar { gap: 8px !important; margin: 16px auto 20px auto !important; }
                    .shorts-platform-btn { padding: 6px 12px !important; font-size: 11.5px !important; }
                    .shorts-subcat-container {
                        display: flex !important;
                        flex-wrap: nowrap !important;
                        overflow-x: auto !important;
                        scroll-snap-type: x mandatory !important;
                        gap: 12px !important;
                        padding: 4px 6px 14px 6px !important;
                        justify-content: flex-start !important;
                        margin-bottom: 22px !important;
                        scrollbar-width: none !important;
                        -webkit-overflow-scrolling: touch !important;
                    }
                    .shorts-subcat-container::-webkit-scrollbar { display: none !important; }
                    .shorts-subcat-card {
                        flex: 0 0 auto !important;
                        width: 82px !important;
                        padding: 2px !important;
                        scroll-snap-align: start !important;
                    }
                    .shorts-subcat-img-wrap { width: 72px !important; height: 72px !important; margin-bottom: 6px !important; }
                    .shorts-subcat-title { font-size: 10px !important; max-width: 82px !important; }
                    .shorts-subcat-yt-badge { width: 22px !important; height: 22px !important; font-size: 10px !important; }
                    .shorts-subcat-active-pill { font-size: 8px !important; padding: 2px 6px !important; }
                    .shorts-grid {
                        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                        gap: 12px 8px !important;
                    }
                    .shorts-product-card {
                        min-width: 0 !important;
                        width: 100% !important;
                        box-sizing: border-box !important;
                        padding: 10px !important;
                        border-radius: 10px !important;
                        display: flex !important;
                        flex-direction: column !important;
                    }
                    .shorts-img-box {
                        height: 210px !important;
                        width: 100% !important;
                        margin-bottom: 10px !important;
                    }
                    .shorts-product-card h3 {
                        font-size: 12px !important;
                        line-height: 1.3 !important;
                        white-space: nowrap !important;
                        overflow: hidden !important;
                        text-overflow: ellipsis !important;
                    }
                    .card-action-btns {
                        display: grid !important;
                        grid-template-columns: repeat(4, 1fr) !important;
                        gap: 4px !important;
                        width: 100% !important;
                        margin-top: auto !important;
                        box-sizing: border-box !important;
                    }
                    .card-action-btns > * {
                        width: 100% !important;
                        height: 34px !important;
                        min-width: 0 !important;
                        padding: 0 !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        border-radius: 6px !important;
                        flex: none !important;
                        box-sizing: border-box !important;
                    }
                    .card-add-cart-btn .btn-text,
                    .card-buy-now-btn .btn-text {
                        display: none !important;
                    }
                }
                .shorts-play-overlay-btn {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 52px;
                    height: 52px;
                    border-radius: 50%;
                    background: rgba(220, 38, 38, 0.92);
                    color: #ffffff;
                    border: 3px solid #ffffff;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    padding-left: 4px;
                    z-index: 4;
                    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                }
                .shorts-play-overlay-btn:hover {
                    transform: translate(-50%, -50%) scale(1.15);
                    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.6);
                }
                .shorts-modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(15, 23, 42, 0.85);
                    backdrop-filter: blur(8px);
                    z-index: 999999;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }
                .shorts-modal-overlay.active {
                    display: flex !important;
                }
                .shorts-modal-dialog {
                    background: #ffffff;
                    border-radius: 16px;
                    overflow: hidden;
                    width: 100%;
                    max-width: 440px;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    max-height: 92vh;
                }
                .shorts-modal-close-btn {
                    position: absolute;
                    top: 12px;
                    right: 12px;
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    background: rgba(15, 23, 42, 0.7);
                    color: #ffffff;
                    border: none;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 16px;
                    z-index: 10;
                    transition: background 0.2s;
                }
                .shorts-modal-close-btn:hover {
                    background: #ef4444;
                }
                .shorts-modal-player-wrap {
                    width: 100%;
                    height: 520px;
                    background: #000000;
                    position: relative;
                }
                .shorts-modal-player-wrap iframe,
                .shorts-modal-player-wrap video {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    border: none;
                }
                .shorts-modal-footer {
                    padding: 14px 18px;
                    background: #ffffff;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 10px;
                    border-top: 1px solid #f1f5f9;
                }
            </style>

            <div style="text-align: center; margin-bottom: 25px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #fff1f2 0%, #fdf2f8 50%, #eff6ff 100%); border: 1px solid #fbcfe8; color: #be185d; padding: 6px 18px; border-radius: 20px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(190, 24, 93, 0.08);">
                    <i class="fa-solid fa-photo-film" style="font-size: 14px; color: #e11d48;"></i> Social Media Channel Showcase
                </div>
                <h2 style="font-size: 30px; font-weight: 800; color: #1e293b; margin: 0 0 10px 0; letter-spacing: 0.5px;"><?= esc($settings['home_shorts_title'] ?? 'SHORTS PRODUCTS') ?></h2>
                <p style="font-size: 15px; color: #64748b; max-width: 650px; margin: 0 auto; line-height: 1.6;"><?= esc($settings['home_shorts_subtitle'] ?? 'From our various social media channels we upload multiple products daily...') ?></p>
            </div>

            <!-- Social Media Platforms Filter: All, YouTube, Instagram, Facebook -->
            <div class="shorts-platform-filter-bar">
                <button type="button" class="shorts-platform-btn active" data-platform="all" title="View all social platforms and channels">
                    <i class="fa-solid fa-layer-group"></i> All Channels
                </button>
                <button type="button" class="shorts-platform-btn platform-yt" data-platform="youtube" title="Filter by YouTube Channels">
                    <i class="fa-brands fa-youtube" style="color: #ef4444;"></i> YouTube
                </button>
                <button type="button" class="shorts-platform-btn platform-ig" data-platform="instagram" title="Filter by Instagram Reels">
                    <i class="fa-brands fa-instagram" style="color: #e1306c;"></i> Instagram Reels
                </button>
                <button type="button" class="shorts-platform-btn platform-fb" data-platform="facebook" title="Filter by Facebook Videos">
                    <i class="fa-brands fa-facebook" style="color: #1877f2;"></i> Facebook
                </button>
            </div>

            <?php
                $channelImages = [];
                if (!empty($channel_subcategories)) {
                    foreach ($channel_subcategories as $csub) {
                        if (!empty($csub['image'])) {
                            $channelImages[$csub['name']] = (strpos($csub['image'], 'http') === 0) ? $csub['image'] : base_url(ltrim($csub['image'], '/'));
                        }
                    }
                }
                // Fallback dynamically to product images uploaded for that channel
                if (!empty($shorts_products)) {
                    foreach ($shorts_products as $sp) {
                        $chName = $sp['shorts_channel'] ?? ($sp['instagram_channel'] ?? ($sp['facebook_channel'] ?? ''));
                        if (!empty($chName) && empty($channelImages[$chName]) && !empty($sp['image_url'])) {
                            $channelImages[$chName] = (strpos($sp['image_url'], 'http') === 0) ? $sp['image_url'] : base_url(ltrim($sp['image_url'], '/'));
                        }
                    }
                }
            ?>
            
            <!-- Channel Subcategories Showcase Display with Related Image Photos & Platform Badges -->
            <div class="shorts-subcat-container" id="shorts-subcat-container">
                <?php foreach($activeChannels as $index => $ch): 
                    $chImg = !empty($ch['image']) ? ((strpos($ch['image'], 'http') === 0) ? $ch['image'] : base_url(ltrim($ch['image'], '/'))) : ($channelImages[$ch['name']] ?? ($defaultChannelImages[$index % count($defaultChannelImages)] ?? base_url('assets/images/placeholder.jpg')));
                    $chPlatform = strtolower($ch['platform'] ?? 'youtube');
                    $badgeIcon = ($chPlatform === 'instagram') ? 'fa-brands fa-instagram' : (($chPlatform === 'facebook') ? 'fa-brands fa-facebook' : 'fa-brands fa-youtube');
                    $badgeColor = ($chPlatform === 'instagram') ? '#e1306c' : (($chPlatform === 'facebook') ? '#1877f2' : '#ef4444');
                ?>
                    <button type="button" 
                            class="shorts-subcat-card <?= $index === 0 ? 'active' : '' ?>" 
                            data-channel="<?= esc($ch['name']) ?>"
                            data-platform="<?= esc($chPlatform) ?>"
                            title="<?= esc($ch['name']) ?> (<?= ucfirst($chPlatform) ?>)">
                        <div class="shorts-subcat-img-wrap">
                            <div class="shorts-subcat-img-inner">
                                <img src="<?= esc($chImg) ?>" 
                                     onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" 
                                     alt="<?= esc($ch['name']) ?>">
                            </div>
                            <div class="shorts-subcat-yt-badge" style="border-color: <?= $badgeColor ?>; color: <?= $badgeColor ?>;">
                                <i class="<?= $badgeIcon ?>"></i>
                            </div>
                        </div>
                        <h4 class="shorts-subcat-title"><?= esc($ch['name']) ?></h4>
                        <span class="shorts-subcat-active-pill">Selected</span>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Product Showcase Grid -->
            <div id="shorts-product-grid" class="shorts-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; max-width: 1400px; margin: 0 auto;">
                <?php if(!empty($shorts_products)): ?>
                    <?php foreach($shorts_products as $sp): 
                        $imgSrc = base_url('assets/images/placeholder.jpg');
                        if (!empty($sp['image_url'])) {
                            if (filter_var($sp['image_url'], FILTER_VALIDATE_URL)) {
                                $imgSrc = $sp['image_url'];
                            } else {
                                $imgSrc = base_url(ltrim($sp['image_url'], '/'));
                            }
                        }

                        // Collect channel affiliations
                        $pYtChannel = trim($sp['shorts_channel'] ?? '');
                        $pIgChannel = trim($sp['instagram_channel'] ?? '');
                        $pFbChannel = trim($sp['facebook_channel'] ?? '');
                        
                        $pChannels = array_filter(array_unique([$pYtChannel, $pIgChannel, $pFbChannel]));
                        $pChannelsList = array_map('strtolower', $pChannels);
                        $pChannelsStr = implode('||', $pChannelsList);

                        // Collect platform affiliations
                        $pPlatforms = [];
                        if (!empty($sp['shorts_url']) || !empty($pYtChannel)) $pPlatforms[] = 'youtube';
                        if (!empty($sp['instagram_url']) || !empty($pIgChannel)) $pPlatforms[] = 'instagram';
                        if (!empty($sp['facebook_url']) || !empty($pFbChannel)) $pPlatforms[] = 'facebook';
                        if (!empty($sp['uploaded_video_url'])) $pPlatforms[] = 'video';
                        if (empty($pPlatforms)) $pPlatforms[] = 'youtube';
                        $pPlatformsStr = implode(' ', $pPlatforms);

                        // Determine primary video details for card click & preview
                        $primaryPlatform = 'youtube';
                        $primaryVideoUrl = '';
                        $primaryChannelName = $pYtChannel;
                        $isUploadedFile = (!empty($sp['uploaded_video_url']) && empty($sp['shorts_url']) && empty($sp['instagram_url']) && empty($sp['facebook_url'])) ? '1' : '0';

                        if (!empty($sp['shorts_url'])) {
                            $primaryVideoUrl = $sp['shorts_url'];
                            $primaryPlatform = 'youtube';
                            $primaryChannelName = !empty($pYtChannel) ? $pYtChannel : 'YouTube Shorts';
                        } elseif (!empty($sp['instagram_url'])) {
                            $primaryVideoUrl = $sp['instagram_url'];
                            $primaryPlatform = 'instagram';
                            $primaryChannelName = !empty($pIgChannel) ? $pIgChannel : 'Instagram Reel';
                        } elseif (!empty($sp['facebook_url'])) {
                            $primaryVideoUrl = $sp['facebook_url'];
                            $primaryPlatform = 'facebook';
                            $primaryChannelName = !empty($pFbChannel) ? $pFbChannel : 'Facebook Watch';
                        } elseif (!empty($sp['uploaded_video_url'])) {
                            $primaryVideoUrl = $sp['uploaded_video_url'];
                            $primaryPlatform = 'video';
                            $primaryChannelName = 'Featured Video';
                        }

                        $hasVideo = !empty($primaryVideoUrl);
                        $cleanWANum = !empty($settings['whatsapp_no']) ? preg_replace('/[^0-9]/', '', $settings['whatsapp_no']) : '919789296751';
                        if (strlen($cleanWANum) === 10) { $cleanWANum = '91' . $cleanWANum; }
                        elseif (strlen($cleanWANum) === 11 && strpos($cleanWANum, '0') === 0) { $cleanWANum = '91' . substr($cleanWANum, 1); }

                        $spVariants = !empty($sp['variants']) ? $sp['variants'] : [];
                        $spSizes = [];
                        foreach ($spVariants as $v) {
                            $rawSize = trim($v['size'] ?? '');
                            if ($rawSize !== '') {
                                $parts = array_filter(array_map('trim', explode(',', $rawSize)));
                                foreach ($parts as $p) {
                                    if ($p !== '' && !in_array($p, $spSizes)) {
                                        $spSizes[] = $p;
                                    }
                                }
                            }
                        }
                        if (empty($spSizes) && !empty($sp['size'])) {
                            $rawSize = trim($sp['size']);
                            if ($rawSize !== '') {
                                $parts = array_filter(array_map('trim', explode(',', $rawSize)));
                                foreach ($parts as $p) {
                                    if ($p !== '' && !in_array($p, $spSizes)) {
                                        $spSizes[] = $p;
                                    }
                                }
                            }
                        }
                    ?>
                    <div class="shorts-product-card shorts-product-item" 
                         data-channel="<?= esc(!empty($pChannels) ? reset($pChannels) : '') ?>"
                         data-channels="<?= esc($pChannelsStr) ?>"
                         data-platforms="<?= esc($pPlatformsStr) ?>"
                         data-yt-channel="<?= esc(strtolower($pYtChannel)) ?>"
                         data-ig-channel="<?= esc(strtolower($pIgChannel)) ?>"
                         data-fb-channel="<?= esc(strtolower($pFbChannel)) ?>"
                         data-yt-url="<?= esc($sp['shorts_url'] ?? '') ?>"
                         data-ig-url="<?= esc($sp['instagram_url'] ?? '') ?>"
                         data-fb-url="<?= esc($sp['facebook_url'] ?? '') ?>"
                         data-uploaded-url="<?= esc($sp['uploaded_video_url'] ?? '') ?>"
                         data-yt-name="<?= esc(!empty($pYtChannel) ? $pYtChannel : 'YT Shorts') ?>"
                         data-ig-name="<?= esc(!empty($pIgChannel) ? $pIgChannel : 'Instagram Reel') ?>"
                         data-fb-name="<?= esc(!empty($pFbChannel) ? $pFbChannel : 'Facebook Watch') ?>"
                         data-primary-platform="<?= esc($primaryPlatform) ?>"
                         data-price="<?= (float)$sp['price'] ?>"
                         data-original-price="<?= !empty($sp['original_price']) ? (float)$sp['original_price'] : 0 ?>"
                         data-variants='<?= json_encode(!empty($sp['variants']) ? $sp['variants'] : [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'
                         data-selected-size="<?= !empty($spSizes[0]) ? esc($spSizes[0]) : '' ?>">
                        
                        <!-- Badges -->
                        <?php if(!empty($sp['is_new_arrival'])): ?>
                            <span style="position: absolute; top: 25px; left: 25px; background: #8b5cf6; color: white; padding: 4px 10px; font-size: 11px; font-weight: 800; z-index: 3; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">NEW</span>
                        <?php elseif(!empty($sp['is_best_seller'])): ?>
                            <span style="position: absolute; top: 25px; left: 25px; background: #ef4444; color: white; padding: 4px 10px; font-size: 11px; font-weight: 800; z-index: 3; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">HOT</span>
                        <?php endif; ?>
                        
                        <div class="shorts-img-box">
                            <a href="<?= base_url('product/' . esc($sp['slug'])) ?>" style="display: block; width: 100%; height: 100%;">
                                <img src="<?= esc($imgSrc) ?>" 
                                     onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" 
                                     alt="<?= esc($sp['name']) ?>">
                            </a>

                            <?php if($hasVideo): ?>
                                <button type="button" 
                                        class="shorts-play-overlay-btn" 
                                        data-video-url="<?= esc($primaryVideoUrl) ?>"
                                        data-is-file="<?= $isUploadedFile ?>"
                                        data-platform="<?= esc($primaryPlatform) ?>"
                                        data-title="<?= esc($sp['name']) ?>"
                                        data-price="₹ <?= number_format($sp['price'], 2) ?>"
                                        data-product-url="<?= base_url('product/' . esc($sp['slug'])) ?>"
                                        data-checkout-url="<?= base_url('checkout?product_id=' . $sp['id']) ?>"
                                        title="Watch Video / Reel"
                                        style="<?= $primaryPlatform === 'instagram' ? 'background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);' : ($primaryPlatform === 'facebook' ? 'background: #1877f2;' : 'background: rgba(220, 38, 38, 0.92);') ?>">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                            <?php endif; ?>

                            <?php if($primaryPlatform === 'instagram'): ?>
                                <div class="shorts-yt-badge" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);">
                                    <i class="fa-brands fa-instagram"></i>
                                    <span><?= esc(!empty($primaryChannelName) ? $primaryChannelName : 'Instagram Reel') ?></span>
                                </div>
                            <?php elseif($primaryPlatform === 'facebook'): ?>
                                <div class="shorts-yt-badge" style="background: #1877f2;">
                                    <i class="fa-brands fa-facebook"></i>
                                    <span><?= esc(!empty($primaryChannelName) ? $primaryChannelName : 'Facebook Watch') ?></span>
                                </div>
                            <?php else: ?>
                                <div class="shorts-yt-badge" style="background: rgba(15, 23, 42, 0.88);">
                                    <i class="fa-brands fa-youtube" style="color: #ef4444;"></i>
                                    <span><?= esc(!empty($primaryChannelName) ? $primaryChannelName : 'YT Shorts') ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="flex-grow: 1; display: flex; flex-direction: column;">
                            <a href="<?= base_url('product/' . esc($sp['slug'])) ?>" style="text-decoration: none; color: inherit;">
                                <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($sp['name']) ?>"><?= esc($sp['name']) ?></h3>
                            </a>
                            
                            <?php if(!empty($spSizes)): ?>
                                <div class="product-card-sizes" style="margin: 0 0 10px 0;">
                                    <span class="card-sizes-label">Sizes:</span>
                                    <?php foreach($spSizes as $sIdx => $sz): 
                                        $szVariant = null;
                                        if (!empty($sp['variants'])) {
                                            foreach ($sp['variants'] as $v) {
                                                if (!empty($v['size'])) {
                                                    $vSizes = array_filter(array_map('trim', explode(',', $v['size'])));
                                                    if (in_array($sz, $vSizes)) {
                                                        $szVariant = $v;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        $szSale = !empty($szVariant['sale_price']) ? (float)$szVariant['sale_price'] : (float)$sp['price'];
                                        $szOrig = !empty($szVariant['original_price']) ? (float)$szVariant['original_price'] : (!empty($sp['original_price']) ? (float)$sp['original_price'] : 0);
                                        $szVarId = !empty($szVariant['id']) ? (int)$szVariant['id'] : '';
                                        $szStock = isset($szVariant['stock']) ? (int)$szVariant['stock'] : 1;
                                    ?>
                                        <span class="card-size-pill <?= $sIdx === 0 ? 'active' : '' ?>" 
                                              data-size="<?= esc($sz) ?>"
                                              data-price="<?= esc(number_format($szSale, 2, '.', '')) ?>"
                                              data-original-price="<?= esc($szOrig > $szSale ? number_format($szOrig, 2, '.', '') : '') ?>"
                                              data-variant-id="<?= $szVarId ?>"
                                              data-stock="<?= $szStock ?>">
                                            <?= esc($sz) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div style="display: flex; gap: 10px; align-items: baseline; margin-bottom: 15px;">
                                <span class="card-sale-price" data-base-price="<?= $sp['price'] ?>" style="color: var(--primary-color, #7a1530); font-weight: 800; font-size: 19px;">₹ <?= number_format($sp['price'], 2) ?></span>
                                <?php if(!empty($sp['original_price']) && $sp['original_price'] > $sp['price']): ?>
                                    <span class="card-original-price" data-base-price="<?= $sp['original_price'] ?>" style="text-decoration: line-through; color: #94a3b8; font-size: 13px; font-weight: 600;">₹ <?= number_format($sp['original_price'], 2) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="card-action-btns" style="margin-top: auto; display: flex; gap: 6px;">
                                <button type="button" 
                                        class="card-add-cart-btn" 
                                        data-product-id="<?= (int)$sp['id'] ?>"
                                        title="Add to Shopping Cart"
                                        style="flex: 1; background: #ffffff; color: #7a1530; border: 1.5px solid #7a1530; padding: 8px 6px; border-radius: 6px; font-weight: 700; font-size: 12px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 4px; transition: all 0.2s;">
                                    <i class="fa-solid fa-cart-shopping"></i> <span class="btn-text">Add Cart</span>
                                </button>
                                <a href="<?= base_url('checkout?product_id=' . $sp['id']) ?>" 
                                   class="card-buy-now-btn"
                                   data-product-id="<?= (int)$sp['id'] ?>"
                                   title="Checkout this product individually"
                                   style="flex: 1; background: #7a1530; color: #ffffff; text-align: center; padding: 8px 6px; border-radius: 6px; font-weight: 700; text-decoration: none; font-size: 12px; display: flex; justify-content: center; align-items: center; gap: 4px; transition: all 0.2s;">
                                    <i class="fa-solid fa-bolt"></i> <span class="btn-text">Buy Now</span>
                                </a>
                                <?php if($hasVideo): ?>
                                    <button type="button" 
                                            class="shorts-video-trigger-btn"
                                            data-video-url="<?= esc($primaryVideoUrl) ?>"
                                            data-is-file="<?= $isUploadedFile ?>"
                                            data-platform="<?= esc($primaryPlatform) ?>"
                                            data-title="<?= esc($sp['name']) ?>"
                                            data-price="₹ <?= number_format($sp['price'], 2) ?>"
                                            data-product-url="<?= base_url('product/' . esc($sp['slug'])) ?>"
                                            data-checkout-url="<?= base_url('checkout?product_id=' . $sp['id']) ?>"
                                            title="Watch Video / Reel" 
                                            style="width: 36px; height: 36px; <?= $primaryPlatform === 'instagram' ? 'background: #fdf2f8; color: #e1306c;' : ($primaryPlatform === 'facebook' ? 'background: #eff6ff; color: #1877f2;' : 'background: #fee2e2; color: #ef4444;') ?> display: flex; justify-content: center; align-items: center; border-radius: 6px; border: none; cursor: pointer; transition: 0.2s; flex-shrink: 0;" 
                                            onmouseover="this.style.filter='brightness(0.92)';" 
                                            onmouseout="this.style.filter='none';">
                                        <i class="<?= $primaryPlatform === 'instagram' ? 'fa-brands fa-instagram' : ($primaryPlatform === 'facebook' ? 'fa-brands fa-facebook' : 'fa-brands fa-youtube') ?>" style="font-size: 16px;"></i>
                                    </button>
                                <?php endif; ?>
                                <?php if(!empty($cleanWANum)): ?>
                                    <a href="https://wa.me/<?= esc($cleanWANum) ?>?text=Hello,%20I%20am%20interested%20in%20<?= urlencode($sp['name']) ?>%20(<?= base_url('product/'.$sp['slug']) ?>)" target="_blank" class="card-whatsapp-btn" title="Inquire on WhatsApp" style="width: 36px; height: 36px; background: #25D366; color: white; display: flex; justify-content: center; align-items: center; border-radius: 6px; text-decoration: none; transition: 0.2s; flex-shrink: 0;">
                                        <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Empty State for Channels with no products yet -->
                <div id="shorts-empty-state" style="display: none; grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1.5px dashed #cbd5e1;">
                    <div id="empty-state-icon-wrap" style="width: 70px; height: 70px; margin: 0 auto 16px auto; border-radius: 50%; background: #fee2e2; display: flex; align-items: center; justify-content: center;">
                        <i id="empty-state-icon" class="fa-brands fa-youtube" style="font-size: 34px; color: #ef4444;"></i>
                    </div>
                    <h3 id="empty-channel-title" style="font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">New Videos Coming Soon</h3>
                    <p style="font-size: 14px; color: #64748b; max-width: 520px; margin: 0 auto 20px auto; line-height: 1.6;">We upload fresh product videos and reels daily from our weaving center in Elampillai. Stay tuned or browse our exclusive collection catalog!</p>
                    <a href="<?= base_url('collections') ?>" style="display: inline-block; padding: 11px 26px; background: var(--primary-color, #7a1530); color: white; border-radius: 6px; font-weight: 700; text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Browse Collections</a>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 45px;">
                <a href="<?= esc($settings['home_shorts_btn_link'] ?? base_url('collections')) ?>" style="display: inline-block; padding: 13px 38px; background-color: var(--primary-color, #7a1530); color: white; font-weight: 700; text-decoration: none; border-radius: 6px; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.8px; box-shadow: 0 4px 14px rgba(122, 21, 48, 0.25);"><?= esc($settings['home_shorts_btn_text'] ?? 'VIEW ALL') ?></a>
            </div>

            <!-- Lightbox Video, YouTube Shorts & Instagram/Facebook Modal Player -->
            <div id="shortsVideoModal" class="shorts-modal-overlay" style="display: none;">
                <div class="shorts-modal-dialog">
                    <button type="button" class="shorts-modal-close-btn" id="shortsModalCloseBtn" aria-label="Close Video Player">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="shorts-modal-player-wrap" id="shortsPlayerContainer">
                        <!-- Dynamic iframe or video element injected here -->
                    </div>
                    <div class="shorts-modal-footer">
                        <div style="flex: 1; overflow: hidden; padding-right: 10px;">
                            <h4 id="shortsModalTitle" style="margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Product Name</h4>
                            <div id="shortsModalPrice" style="font-size: 16px; font-weight: 800; color: var(--primary-color, #7a1530);">₹ 0.00</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a id="shortsModalProductLink" href="#" style="background: #ffffff; color: var(--primary-color, #7a1530); border: 1.5px solid var(--primary-color, #7a1530); padding: 8px 12px; border-radius: 6px; font-weight: 700; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                Details
                            </a>
                            <a id="shortsModalBuyBtn" href="#" style="background: var(--primary-color, #7a1530); color: #ffffff; padding: 8px 14px; border-radius: 6px; font-weight: 700; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-bolt"></i> Buy Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Social Media Showcase & Channel Filtering Script -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const platformBtns = document.querySelectorAll('.shorts-platform-btn');
            const subcatCards = document.querySelectorAll('.shorts-subcat-card');
            const productCards = document.querySelectorAll('.shorts-product-item');
            const emptyState = document.getElementById('shorts-empty-state');
            const emptyTitle = document.getElementById('empty-channel-title');
            const emptyIcon = document.getElementById('empty-state-icon');
            const emptyIconWrap = document.getElementById('empty-state-icon-wrap');

            let activePlatform = 'all';
            let activeChannel = '';

            // Set initial active channel from default selected card
            const initActiveCard = document.querySelector('.shorts-subcat-card.active');
            if (initActiveCard) {
                activeChannel = (initActiveCard.getAttribute('data-channel') || '').trim();
            }

            function applyFilters() {
                let matchCount = 0;
                const channelTarget = (activeChannel || '').trim().toLowerCase();
                const platformTarget = (activePlatform || 'all').trim().toLowerCase();

                // 1. Filter channel cards based on selected platform
                let firstVisibleCard = null;
                let activeCardStillVisible = false;

                subcatCards.forEach(card => {
                    const cardPlatform = (card.getAttribute('data-platform') || 'youtube').trim().toLowerCase();
                    const cardChannel = (card.getAttribute('data-channel') || '').trim().toLowerCase();
                    const isVisible = (platformTarget === 'all' || cardPlatform === platformTarget);

                    if (isVisible) {
                        card.style.display = 'flex';
                        if (!firstVisibleCard) firstVisibleCard = card;
                        if (cardChannel === channelTarget) activeCardStillVisible = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // If currently active channel card is hidden, select first visible channel card
                if (!activeCardStillVisible && firstVisibleCard) {
                    subcatCards.forEach(c => c.classList.remove('active'));
                    firstVisibleCard.classList.add('active');
                    activeChannel = (firstVisibleCard.getAttribute('data-channel') || '').trim();
                } else if (activeCardStillVisible) {
                    subcatCards.forEach(c => {
                        const cName = (c.getAttribute('data-channel') || '').trim().toLowerCase();
                        if (cName === (activeChannel || '').trim().toLowerCase()) {
                            c.classList.add('active');
                        } else {
                            c.classList.remove('active');
                        }
                    });
                } else {
                    subcatCards.forEach(c => c.classList.remove('active'));
                }

                const finalChannelTarget = (activeChannel || '').trim().toLowerCase();

                // 2. Filter products by channel & platform
                productCards.forEach(card => {
                    const cardChannelsStr = (card.getAttribute('data-channels') || '').toLowerCase();
                    const cardPrimaryChannel = (card.getAttribute('data-channel') || '').trim().toLowerCase();
                    const cardPlatforms = (card.getAttribute('data-platforms') || '').toLowerCase().split(' ');

                    const matchesPlatform = (platformTarget === 'all' || cardPlatforms.includes(platformTarget));
                    
                    // Channel match check: checks channels string or primary channel
                    let matchesChannel = false;
                    if (!finalChannelTarget) {
                        matchesChannel = true;
                    } else {
                        const channelList = cardChannelsStr ? cardChannelsStr.split('||') : [];
                        matchesChannel = (cardPrimaryChannel === finalChannelTarget) || channelList.includes(finalChannelTarget);
                    }

                    if (matchesPlatform && matchesChannel) {
                        card.style.display = 'flex';
                        card.style.animation = 'shortsFadeIn 0.35s ease forwards';
                        matchCount++;

                        // Dynamically update card video button, play overlay, and badge to match active platform
                        const ytUrl = card.getAttribute('data-yt-url') || '';
                        const igUrl = card.getAttribute('data-ig-url') || '';
                        const fbUrl = card.getAttribute('data-fb-url') || '';
                        const uploadedUrl = card.getAttribute('data-uploaded-url') || '';
                        const primaryPlatform = card.getAttribute('data-primary-platform') || 'youtube';

                        // Check active channel card's platform
                        let channelPlatform = '';
                        const activeChCard = document.querySelector('.shorts-subcat-card.active');
                        if (activeChCard) {
                            channelPlatform = (activeChCard.getAttribute('data-platform') || '').trim().toLowerCase();
                        }

                        let effectivePlatform = 'youtube';
                        if (platformTarget === 'facebook') {
                            effectivePlatform = fbUrl ? 'facebook' : (igUrl ? 'instagram' : (ytUrl ? 'youtube' : primaryPlatform));
                        } else if (platformTarget === 'instagram') {
                            effectivePlatform = igUrl ? 'instagram' : (ytUrl ? 'youtube' : (fbUrl ? 'facebook' : primaryPlatform));
                        } else if (platformTarget === 'youtube') {
                            effectivePlatform = ytUrl ? 'youtube' : (igUrl ? 'instagram' : (fbUrl ? 'facebook' : primaryPlatform));
                        } else if (channelPlatform) {
                            if (channelPlatform === 'facebook' && fbUrl) effectivePlatform = 'facebook';
                            else if (channelPlatform === 'instagram' && igUrl) effectivePlatform = 'instagram';
                            else if (channelPlatform === 'youtube' && ytUrl) effectivePlatform = 'youtube';
                            else effectivePlatform = primaryPlatform;
                        } else {
                            effectivePlatform = primaryPlatform;
                        }

                        let effectiveUrl = '';
                        let effectiveName = '';
                        if (effectivePlatform === 'facebook') {
                            effectiveUrl = fbUrl || igUrl || ytUrl || uploadedUrl;
                            effectiveName = card.getAttribute('data-fb-name') || 'Facebook Watch';
                        } else if (effectivePlatform === 'instagram') {
                            effectiveUrl = igUrl || ytUrl || fbUrl || uploadedUrl;
                            effectiveName = card.getAttribute('data-ig-name') || 'Instagram Reel';
                        } else if (effectivePlatform === 'video') {
                            effectiveUrl = uploadedUrl;
                            effectiveName = 'Featured Video';
                        } else {
                            effectivePlatform = 'youtube';
                            effectiveUrl = ytUrl || igUrl || fbUrl || uploadedUrl;
                            effectiveName = card.getAttribute('data-yt-name') || 'YT Shorts';
                        }

                        // 1. Update Video Trigger Button (.shorts-video-trigger-btn)
                        const triggerBtn = card.querySelector('.shorts-video-trigger-btn');
                        if (triggerBtn) {
                            triggerBtn.setAttribute('data-platform', effectivePlatform);
                            triggerBtn.setAttribute('data-video-url', effectiveUrl);
                            if (effectivePlatform === 'facebook') {
                                triggerBtn.innerHTML = '<i class="fa-brands fa-facebook" style="font-size: 16px;"></i>';
                                triggerBtn.style.background = '#eff6ff';
                                triggerBtn.style.color = '#1877f2';
                                triggerBtn.title = 'Watch Facebook Video';
                            } else if (effectivePlatform === 'instagram') {
                                triggerBtn.innerHTML = '<i class="fa-brands fa-instagram" style="font-size: 16px;"></i>';
                                triggerBtn.style.background = '#fdf2f8';
                                triggerBtn.style.color = '#e1306c';
                                triggerBtn.title = 'Watch Instagram Reel';
                            } else if (effectivePlatform === 'youtube') {
                                triggerBtn.innerHTML = '<i class="fa-brands fa-youtube" style="font-size: 16px;"></i>';
                                triggerBtn.style.background = '#fee2e2';
                                triggerBtn.style.color = '#ef4444';
                                triggerBtn.title = 'Watch YouTube Short';
                            } else {
                                triggerBtn.innerHTML = '<i class="fa-solid fa-play" style="font-size: 14px;"></i>';
                                triggerBtn.style.background = '#f1f5f9';
                                triggerBtn.style.color = '#475569';
                                triggerBtn.title = 'Watch Video';
                            }
                        }

                        // 2. Update Play Overlay Button (.shorts-play-overlay-btn)
                        const playOverlayBtn = card.querySelector('.shorts-play-overlay-btn');
                        if (playOverlayBtn) {
                            playOverlayBtn.setAttribute('data-platform', effectivePlatform);
                            playOverlayBtn.setAttribute('data-video-url', effectiveUrl);
                            if (effectivePlatform === 'facebook') {
                                playOverlayBtn.style.background = '#1877f2';
                                playOverlayBtn.title = 'Watch Facebook Video';
                            } else if (effectivePlatform === 'instagram') {
                                playOverlayBtn.style.background = 'linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888)';
                                playOverlayBtn.title = 'Watch Instagram Reel';
                            } else if (effectivePlatform === 'youtube') {
                                playOverlayBtn.style.background = 'rgba(220, 38, 38, 0.92)';
                                playOverlayBtn.title = 'Watch YouTube Short';
                            } else {
                                playOverlayBtn.style.background = 'rgba(15, 23, 42, 0.88)';
                                playOverlayBtn.title = 'Watch Video';
                            }
                        }

                        // 3. Update Video Channel Badge (.shorts-yt-badge)
                        const ytBadge = card.querySelector('.shorts-yt-badge');
                        if (ytBadge) {
                            if (effectivePlatform === 'facebook') {
                                ytBadge.style.background = '#1877f2';
                                ytBadge.innerHTML = '<i class="fa-brands fa-facebook"></i> <span>' + (effectiveName || 'Facebook Watch') + '</span>';
                            } else if (effectivePlatform === 'instagram') {
                                ytBadge.style.background = 'linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888)';
                                ytBadge.innerHTML = '<i class="fa-brands fa-instagram"></i> <span>' + (effectiveName || 'Instagram Reel') + '</span>';
                            } else if (effectivePlatform === 'youtube') {
                                ytBadge.style.background = 'rgba(15, 23, 42, 0.88)';
                                ytBadge.innerHTML = '<i class="fa-brands fa-youtube" style="color: #ef4444;"></i> <span>' + (effectiveName || 'YT Shorts') + '</span>';
                            } else {
                                ytBadge.style.background = 'rgba(15, 23, 42, 0.88)';
                                ytBadge.innerHTML = '<i class="fa-solid fa-play"></i> <span>Featured Video</span>';
                            }
                        }
                    } else {
                        card.style.display = 'none';
                    }
                });

                // 3. Update empty state if 0 matches
                if (matchCount === 0) {
                    if (emptyState) {
                        emptyState.style.display = 'block';
                        const label = activeChannel ? activeChannel : (activePlatform !== 'all' ? activePlatform.toUpperCase() : 'Social');
                        if (emptyTitle) {
                            emptyTitle.textContent = label + ' — Products Coming Soon';
                        }
                        if (emptyIcon && emptyIconWrap) {
                            if (platformTarget === 'instagram') {
                                emptyIcon.className = 'fa-brands fa-instagram';
                                emptyIcon.style.color = '#e1306c';
                                emptyIconWrap.style.background = '#fdf2f8';
                            } else if (platformTarget === 'facebook') {
                                emptyIcon.className = 'fa-brands fa-facebook';
                                emptyIcon.style.color = '#1877f2';
                                emptyIconWrap.style.background = '#eff6ff';
                            } else {
                                emptyIcon.className = 'fa-brands fa-youtube';
                                emptyIcon.style.color = '#ef4444';
                                emptyIconWrap.style.background = '#fee2e2';
                            }
                        }
                    }
                } else {
                    if (emptyState) {
                        emptyState.style.display = 'none';
                    }
                }
            }

            // Platform Filter Button Clicks
            platformBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    platformBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    activePlatform = this.getAttribute('data-platform') || 'all';
                    applyFilters();
                });
            });

            // Channel Subcategory Card Clicks
            subcatCards.forEach(card => {
                card.addEventListener('click', function() {
                    subcatCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    activeChannel = (this.getAttribute('data-channel') || '').trim();
                    applyFilters();
                });
            });

            // Initial Filter Run
            applyFilters();

            // Video & Social Reels Lightbox Modal Logic
            const videoModal = document.getElementById('shortsVideoModal');
            const modalCloseBtn = document.getElementById('shortsModalCloseBtn');
            const playerContainer = document.getElementById('shortsPlayerContainer');
            const modalTitle = document.getElementById('shortsModalTitle');
            const modalPrice = document.getElementById('shortsModalPrice');
            const modalProductLink = document.getElementById('shortsModalProductLink');
            const modalBuyBtn = document.getElementById('shortsModalBuyBtn');

            function openVideoModal(url, isFile, platform, title, price, productUrl, checkoutUrl) {
                if (!url || !videoModal) return;
                if (modalTitle) modalTitle.textContent = title || '';
                if (modalPrice) modalPrice.textContent = price || '';
                if (modalProductLink) modalProductLink.href = productUrl || '#';
                if (modalBuyBtn) modalBuyBtn.href = checkoutUrl || '#';
                if (playerContainer) playerContainer.innerHTML = '';

                if (isFile === '1') {
                    const videoEl = document.createElement('video');
                    videoEl.src = url;
                    videoEl.controls = true;
                    videoEl.autoplay = true;
                    videoEl.playsInline = true;
                    videoEl.style.width = '100%';
                    videoEl.style.height = '100%';
                    playerContainer.appendChild(videoEl);
                } else {
                    let embedUrl = url;
                    
                    // Check if Instagram reel/post
                    const igMatch = url.match(/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/i);
                    if (igMatch || platform === 'instagram' || url.includes('instagram.com')) {
                        const igCode = igMatch ? igMatch[1] : '';
                        if (igCode) {
                            embedUrl = 'https://www.instagram.com/reel/' + igCode + '/embed/';
                        } else if (!url.includes('/embed')) {
                            embedUrl = url.replace(/\/?$/, '/embed/');
                        }
                    } 
                    // Check if Facebook video
                    else if (url.includes('facebook.com') || url.includes('fb.watch') || platform === 'facebook') {
                        embedUrl = 'https://www.facebook.com/plugins/video.php?href=' + encodeURIComponent(url) + '&show_text=0&autoplay=1';
                    } 
                    // Check if YouTube shorts/video
                    else {
                        let ytId = '';
                        const shortsMatch = url.match(/shorts\/([a-zA-Z0-9_-]{11})/);
                        if (shortsMatch) {
                            ytId = shortsMatch[1];
                        } else {
                            const youtuBeMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
                            if (youtuBeMatch) {
                                ytId = youtuBeMatch[1];
                            } else {
                                const watchMatch = url.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
                                if (watchMatch) {
                                    ytId = watchMatch[1];
                                }
                            }
                        }
                        if (ytId) {
                            embedUrl = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1&mute=1&loop=1&playlist=' + ytId + '&playsinline=1&controls=1&enablejsapi=1&rel=0&modestbranding=1';
                        }
                    }

                    const iframe = document.createElement('iframe');
                    iframe.src = embedUrl;
                    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                    iframe.allowFullscreen = true;
                    iframe.style.width = '100%';
                    iframe.style.height = '100%';
                    iframe.style.border = '0';
                    playerContainer.appendChild(iframe);
                }

                videoModal.classList.add('active');
                videoModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function closeVideoModal() {
                if (!videoModal) return;
                videoModal.classList.remove('active');
                videoModal.style.display = 'none';
                if (playerContainer) playerContainer.innerHTML = '';
                document.body.style.overflow = '';
            }

            if (modalCloseBtn) {
                modalCloseBtn.addEventListener('click', closeVideoModal);
            }
            if (videoModal) {
                videoModal.addEventListener('click', function(e) {
                    if (e.target === videoModal) closeVideoModal();
                });
            }
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && videoModal && videoModal.classList.contains('active')) {
                    closeVideoModal();
                }
            });

            document.querySelectorAll('.shorts-play-overlay-btn, .shorts-video-trigger-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const url = this.getAttribute('data-video-url');
                    const isFile = this.getAttribute('data-is-file');
                    const platform = this.getAttribute('data-platform') || '';
                    const title = this.getAttribute('data-title');
                    const price = this.getAttribute('data-price');
                    const productUrl = this.getAttribute('data-product-url');
                    const checkoutUrl = this.getAttribute('data-checkout-url');
                    openVideoModal(url, isFile, platform, title, price, productUrl, checkoutUrl);
                });
            });
        });
        </script>

        <!-- Why Choose SSPTex Section -->
        <?php if(($settings['home_why_choose_enabled'] ?? '1') === '1'): ?>
        <section class="why-choose-ssptex-section" style="padding: 70px 40px; background: linear-gradient(180deg, #ffffff 0%, #fffbf6 100%); border-top: 1px solid #f1e6da; border-bottom: 1px solid #f1e6da;">
            <div style="max-width: 1350px; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 45px;">
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: #fff5f0; border: 1px solid #fed7aa; color: #c2410c; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px;">
                        <i class="fa-solid fa-crown" style="color: #d4af37;"></i> Direct From The Weavers of Elampillai
                    </div>
                    <h2 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0 0 12px 0; letter-spacing: 0.5px;">
                        <?= esc($settings['home_why_choose_title'] ?? 'Why Choose Selvi Tex?') ?>
                    </h2>
                    <p style="font-size: 16px; color: #64748b; max-width: 750px; margin: 0 auto; line-height: 1.6;">
                        <?= esc($settings['home_why_choose_subtitle'] ?? 'Authentic Weaving Heritage • Direct Factory Prices • Global Doorstep Delivery') ?>
                    </p>
                </div>

                <div class="why-choose-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 40px;">
                    <!-- Card 1 -->
                    <div class="why-choose-card" style="background: #ffffff; border: 1px solid #f1e6da; border-radius: 14px; padding: 30px 24px; text-align: center; box-shadow: 0 4px 20px rgba(122, 21, 48, 0.04); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div style="width: 65px; height: 65px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, #fdf2f4 0%, #fae8eb 100%); border: 2px solid #fbcfe8; display: flex; align-items: center; justify-content: center; color: #7a1530; font-size: 24px;">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">
                            <?= esc($settings['why_choose_title_1'] ?? 'Direct From Master Weavers') ?>
                        </h3>
                        <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
                            <?= esc($settings['why_choose_desc_1'] ?? 'Woven directly on authentic Elampillai looms, preserving century-old silk and cotton craftsmanship without middleman commissions.') ?>
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="why-choose-card" style="background: #ffffff; border: 1px solid #f1e6da; border-radius: 14px; padding: 30px 24px; text-align: center; box-shadow: 0 4px 20px rgba(122, 21, 48, 0.04); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div style="width: 65px; height: 65px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%); border: 2px solid #fef08a; display: flex; align-items: center; justify-content: center; color: #b45309; font-size: 24px;">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">
                            <?= esc($settings['why_choose_title_2'] ?? 'Wholesale Price for Everyone') ?>
                        </h3>
                        <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
                            <?= esc($settings['why_choose_desc_2'] ?? 'Direct manufacturer rates whether purchasing a single celebratory saree or bulk bridal and uniform orders.') ?>
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="why-choose-card" style="background: #ffffff; border: 1px solid #f1e6da; border-radius: 14px; padding: 30px 24px; text-align: center; box-shadow: 0 4px 20px rgba(122, 21, 48, 0.04); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div style="width: 65px; height: 65px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #bbf7d0; display: flex; align-items: center; justify-content: center; color: #15803d; font-size: 24px;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">
                            <?= esc($settings['why_choose_title_3'] ?? '100% Quality & Zari Guarantee') ?>
                        </h3>
                        <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
                            <?= esc($settings['why_choose_desc_3'] ?? 'Every piece undergoes multi-point inspection for authentic zari shine, soft drape, and durable texture.') ?>
                        </p>
                    </div>

                    <!-- Card 4 -->
                    <div class="why-choose-card" style="background: #ffffff; border: 1px solid #f1e6da; border-radius: 14px; padding: 30px 24px; text-align: center; box-shadow: 0 4px 20px rgba(122, 21, 48, 0.04); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div style="width: 65px; height: 65px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #bfdbfe; display: flex; align-items: center; justify-content: center; color: #1d4ed8; font-size: 24px;">
                            <i class="fa-solid fa-plane-departure"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">
                            <?= esc($settings['why_choose_title_4'] ?? 'Worldwide Express Delivery') ?>
                        </h3>
                        <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
                            <?= esc($settings['why_choose_desc_4'] ?? 'Fast, reliable door delivery across India and internationally with secure protective packaging and full tracking.') ?>
                        </p>
                    </div>
                </div>

                <!-- Trust Strip Ribbon -->
                <div style="background: linear-gradient(135deg, #7a1530 0%, #4a0d1d 100%); border-radius: 14px; padding: 25px 35px; color: white; display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; gap: 20px; box-shadow: 0 10px 25px rgba(122, 21, 48, 0.2);">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <i class="fa-solid fa-award" style="font-size: 28px; color: #d4af37;"></i>
                        <div>
                            <div style="font-size: 18px; font-weight: 800;">15+ Years Legacy</div>
                            <div style="font-size: 12px; opacity: 0.85;">Pioneering Elampillai Weaving</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <i class="fa-solid fa-users-rays" style="font-size: 28px; color: #d4af37;"></i>
                        <div>
                            <div style="font-size: 18px; font-weight: 800;">50,000+ Happy Customers</div>
                            <div style="font-size: 12px; opacity: 0.85;">Worldwide Saree Enthusiasts</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <i class="fa-brands fa-youtube" style="font-size: 28px; color: #ef4444;"></i>
                        <div>
                            <div style="font-size: 18px; font-weight: 800;">Daily YouTube Live</div>
                            <div style="font-size: 12px; opacity: 0.85;">Fresh Products Added Daily</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <i class="fa-solid fa-headset" style="font-size: 28px; color: #d4af37;"></i>
                        <div>
                            <div style="font-size: 18px; font-weight: 800;">WhatsApp Support</div>
                            <div style="font-size: 12px; opacity: 0.85;">Instant Video & Order Help</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Testimonials Section -->
        <?php if(!empty($testimonials)): ?>
        <section class="testimonials-section" style="padding: 60px 40px; background-color: #fcfcfc; text-align: center; border-top: 1px solid #eaeaea;">
            <h2 style="font-size: 28px; font-weight: 800; color: #222; margin-bottom: 40px; letter-spacing: 1px;">WHAT OUR CUSTOMERS SAY</h2>
            
            <div id="testimonial-slider" style="max-width: 800px; margin: 0 auto; min-height: 150px; position: relative;">
                <?php foreach($testimonials as $index => $test): ?>
                    <div class="testimonial-slide" id="test-slide-<?= $index ?>" style="position: absolute; top: 0; left: 0; width: 100%; opacity: <?= $index === 0 ? '1' : '0' ?>; transition: opacity 1s ease-in-out; pointer-events: <?= $index === 0 ? 'auto' : 'none' ?>;">
                        <div style="font-size: 24px; color: #fbbf24; margin-bottom: 15px;">
                            <?php for($i=0; $i<$test['rating']; $i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?>
                        </div>
                        <p style="font-size: 20px; font-style: italic; color: #475569; line-height: 1.6; margin-bottom: 20px;">"<?= esc($test['content']) ?>"</p>
                        <h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">- <?= esc($test['name']) ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Dynamic Frequently Asked Questions Section -->
        <?= view('user/faq_section', ['settings' => $settings]) ?>
    </main>

    <script>
    async function quickAddToCart(productId) {
        try {
            var btn = (window.event && window.event.currentTarget) ? window.event.currentTarget : null;
            if (btn) {
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
                btn.disabled = true;
            }
            var res = await fetch('<?= base_url('cart/add') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            });
            var data = await res.json();
            if (data.status === 'success') {
                var badges = document.querySelectorAll('.cart-count-badge, .header-cart-badge, .cart-badge');
                badges.forEach(function(b) {
                    b.innerText = data.summary?.totalItems || (parseInt(b.innerText || '0') + 1);
                });
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Added!';
                    btn.style.background = '#059669';
                    setTimeout(function() {
                        btn.innerHTML = '<i class="fa-solid fa-bag-shopping"></i> Add to Cart';
                        btn.style.background = '';
                        btn.disabled = false;
                    }, 2000);
                }
            }
        } catch (err) {
            console.error('Add to cart error:', err);
        }
    }
    </script>

<?= view('user/footer') ?>
