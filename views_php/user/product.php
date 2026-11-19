<?= view('user/header') ?>

<?php
    if (!function_exists('renderProductDescriptionSafe')) {
        function renderProductDescriptionSafe($text) {
            if (empty(trim($text ?? ''))) {
                return '<p class="text-muted" style="color: #64748b; font-size: 14px;">No description available for this product.</p>';
            }

            $lines = preg_split('/\r\n|\r|\n/', trim($text));
            $html = '';
            $inList = false;

            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '') {
                    if ($inList) {
                        $html .= '</ul>';
                        $inList = false;
                    }
                    continue;
                }

                if (preg_match('/^[-*•+]\s+(.*)$/u', $trimmed, $m)) {
                    if (!$inList) {
                        $html .= '<ul class="product-desc-bullets">';
                        $inList = true;
                    }
                    $content = esc($m[1]);
                    $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
                    $html .= '<li><i class="fa-solid fa-check"></i> <span>' . $content . '</span></li>';
                } else {
                    if ($inList) {
                        $html .= '</ul>';
                        $inList = false;
                    }
                    $content = esc($trimmed);
                    $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
                    $html .= '<p class="product-desc-para">' . $content . '</p>';
                }
            }

            if ($inList) {
                $html .= '</ul>';
            }

            return $html;
        }
    }

    $whatsappPhone = !empty($settings['whatsapp_no']) ? $settings['whatsapp_no'] : '919789296751';
    $whatsappPhone = preg_replace('/[^0-9]/', '', $whatsappPhone);
    if (strlen($whatsappPhone) === 10) {
        $whatsappPhone = '91' . $whatsappPhone;
    } elseif (strlen($whatsappPhone) === 11 && strpos($whatsappPhone, '0') === 0) {
        $whatsappPhone = '91' . substr($whatsappPhone, 1);
    }
    $productEnquiryText = urlencode("Hello Selvi Tex, I am interested in " . $product['name'] . " (" . base_url('product/' . $product['slug']) . ")");
    
    // Distinct colors & sizes
    $distinctColors = [];
    $distinctSizes = [];
    $preparedVariants = [];
    
    if (!empty($variants)) {
        foreach ($variants as $v) {
            $cName = !empty($v['color_name']) ? trim($v['color_name']) : null;
            $cCode = !empty($v['color_code']) ? trim($v['color_code']) : null;
            $sName = !empty($v['size']) ? trim($v['size']) : null;
            
            // Format images for JSON
            $vImages = [];
            if (!empty($v['images'])) {
                foreach ($v['images'] as $img) {
                    $vImages[] = [
                        'media_type' => 'image',
                        'media_url' => base_url(ltrim($img['media_url'], '/')),
                        'url' => base_url(ltrim($img['media_url'], '/'))
                    ];
                }
            }

            if ($cName || $cCode) {
                // Normalize by color name first (e.g. 'orange') to merge multiple price/size variants of the same color
                $colorKey = !empty($cName) ? strtolower(trim($cName)) : strtolower(trim($cCode ?? 'option'));
                if (!isset($distinctColors[$colorKey])) {
                    $distinctColors[$colorKey] = [
                        'color_name' => $cName ?: 'Option',
                        'color_code' => $cCode ?: '#7a1530',
                        'images' => $vImages
                    ];
                } else {
                    // Enrich existing swatch with non-empty code or images if missing
                    if ((empty($distinctColors[$colorKey]['color_code']) || $distinctColors[$colorKey]['color_code'] === '#7a1530') && !empty($cCode)) {
                        $distinctColors[$colorKey]['color_code'] = $cCode;
                    }
                    if (empty($distinctColors[$colorKey]['images']) && !empty($vImages)) {
                        $distinctColors[$colorKey]['images'] = $vImages;
                    }
                }
            }
            if ($sName) {
                $parts = array_filter(array_map('trim', explode(',', $sName)));
                foreach ($parts as $p) {
                    if (!in_array($p, $distinctSizes)) {
                        $distinctSizes[] = $p;
                    }
                }
            }
            
            $preparedVariants[] = [
                'id' => (int)$v['id'],
                'product_id' => (int)$v['product_id'],
                'color_name' => $v['color_name'] ?? '',
                'color_code' => $v['color_code'] ?? '',
                'size' => $v['size'] ?? '',
                'sku' => $v['sku'] ?? '',
                'sale_price' => !empty($v['sale_price']) ? (float)$v['sale_price'] : (float)$product['price'],
                'original_price' => !empty($v['original_price']) ? (float)$v['original_price'] : (!empty($product['original_price']) ? (float)$product['original_price'] : 0),
                'stock' => isset($v['stock']) ? (int)$v['stock'] : 0,
                'images' => $vImages
            ];
        }
    }
    $distinctColors = array_values($distinctColors);

    // Fallback media list
    $fallbackMedia = [];
    $primaryImgUrl = base_url('assets/images/placeholder.jpg');
    if (!empty($product['image_url'])) {
        if (strpos($product['image_url'], 'http') === 0) {
            $primaryImgUrl = $product['image_url'];
        } else {
            $primaryImgUrl = base_url(ltrim($product['image_url'], '/'));
        }
    }
    if ($primaryImgUrl === base_url('assets/images/placeholder.jpg') && !empty($general_gallery)) {
        foreach ($general_gallery as $g) {
            if ($g['media_type'] === 'image' && !empty($g['media_url'])) {
                $primaryImgUrl = base_url(ltrim($g['media_url'], '/'));
                break;
            }
        }
    }
    if ($primaryImgUrl === base_url('assets/images/placeholder.jpg') && !empty($preparedVariants)) {
        foreach ($preparedVariants as $pv) {
            if (!empty($pv['images'][0]['media_url'])) {
                $primaryImgUrl = $pv['images'][0]['media_url'];
                break;
            }
        }
    }
    $fallbackMedia[] = [
        'media_type' => 'image',
        'media_url' => $primaryImgUrl,
        'url' => $primaryImgUrl
    ];
    if (!empty($general_gallery)) {
        foreach ($general_gallery as $g) {
            $fallbackMedia[] = [
                'media_type' => 'image',
                'media_url' => base_url(ltrim($g['media_url'], '/')),
                'url' => base_url(ltrim($g['media_url'], '/'))
            ];
        }
    }
    if (!empty($product_video)) {
        $fallbackMedia[] = [
            'media_type' => 'video',
            'media_url' => base_url(ltrim($product_video['media_url'], '/')),
            'url' => base_url(ltrim($product_video['media_url'], '/'))
        ];
    }
    if (!empty($product['shorts_url'])) {
        $fallbackMedia[] = [
            'media_type' => 'youtube',
            'media_url' => $product['shorts_url'],
            'url' => $product['shorts_url']
        ];
    }

    // Video Detection for Live Stacked Showcase
    $hasUploadedVideo = !empty($product_video);
    $uploadedVideoUrl = $hasUploadedVideo ? base_url(ltrim($product_video['media_url'], '/')) : '';

    $ytVideoId = '';
    if (!empty($product['shorts_url'])) {
        if (preg_match('/(?:shorts\/|youtu\.be\/|v=|\/embed\/|\/v\/)([a-zA-Z0-9_-]{11})/', $product['shorts_url'], $matches)) {
            $ytVideoId = $matches[1];
        }
    }
    $hasYoutubeVideo = !empty($ytVideoId);

    // Instagram Reel / Video detection
    $igEmbedUrl = '';
    if (!empty($product['instagram_url'])) {
        if (preg_match('/(?:reel|reels|p|tv)\/([a-zA-Z0-9_-]+)/i', $product['instagram_url'], $matches)) {
            $igEmbedUrl = 'https://www.instagram.com/reel/' . $matches[1] . '/embed/';
            $fallbackMedia[] = [
                'media_type' => 'instagram',
                'media_url' => $product['instagram_url'],
                'url' => $product['instagram_url'],
                'embed_url' => $igEmbedUrl
            ];
        }
    }
    $hasInstagramVideo = !empty($igEmbedUrl);

    // Facebook Video / Reel detection
    $fbEmbedUrl = '';
    if (!empty($product['facebook_url'])) {
        $fbEmbedUrl = 'https://www.facebook.com/plugins/video.php?href=' . urlencode($product['facebook_url']) . '&show_text=0&width=360';
        $fallbackMedia[] = [
            'media_type' => 'facebook',
            'media_url' => $product['facebook_url'],
            'url' => $product['facebook_url'],
            'embed_url' => $fbEmbedUrl
        ];
    }
    $hasFacebookVideo = !empty($fbEmbedUrl);

    $hasAnyVideo = $hasUploadedVideo || $hasYoutubeVideo || $hasInstagramVideo || $hasFacebookVideo;

    $initialVideoType = $hasUploadedVideo ? 'video' : ($hasYoutubeVideo ? 'youtube' : ($hasInstagramVideo ? 'instagram' : ($hasFacebookVideo ? 'facebook' : '')));
    $initialYtEmbed = $hasYoutubeVideo ? "https://www.youtube.com/embed/{$ytVideoId}?autoplay=1&mute=1&loop=1&playlist={$ytVideoId}&playsinline=1&controls=1&enablejsapi=1&rel=0" : '';

    // Initial pricing & stock
    $initialPrice = (float)$product['price'];
    $initialOrigPrice = !empty($product['original_price']) ? (float)$product['original_price'] : 0;
    $initialStock = isset($product['stock_quantity']) ? (int)$product['stock_quantity'] : 1;

    if (!empty($preparedVariants)) {
        $initialPrice = (float)$preparedVariants[0]['sale_price'];
        if (!empty($preparedVariants[0]['original_price'])) {
            $initialOrigPrice = (float)$preparedVariants[0]['original_price'];
        }
        $initialStock = (int)$preparedVariants[0]['stock'];
    }
?>

<div class="product-detail-container" id="product-detail-view">
    <div class="product-images">
        <!-- Main Media Showcase Container (Top Image + Bottom Video Stacked) -->
        <div class="product-media-showcase-container" id="main-image-container">
            <!-- TOP: Main Product Image Display -->
            <div class="product-top-image-box <?= !$hasAnyVideo ? 'no-video-full-height' : '' ?>" id="product-top-image-box" title="Tap or click to zoom saree">
                <img src="<?= $primaryImgUrl ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="<?= esc($product['name']) ?>" class="main-image" id="main-image">
                <div class="image-zoom-badge" id="image-zoom-badge">
                    <i class="fa-solid fa-magnifying-glass-plus"></i> <span>Tap to Zoom</span>
                </div>
            </div>

            <!-- BOTTOM: Live Video Player Showcase (Plays automatically like hero section) -->
            <?php if($hasAnyVideo): ?>
            <div class="product-bottom-video-box" id="product-bottom-video-box">
                <div class="video-box-header">
                    <span class="video-live-pill" id="video-live-pill">
                        <span class="live-dot"></span> <i class="fa-solid fa-circle-play"></i> <span id="video-live-pill-text"><?php 
                            if ($initialVideoType === 'youtube') echo 'YouTube Shorts Preview' . (!empty($product['shorts_channel']) ? ' (@' . esc($product['shorts_channel']) . ')' : '');
                            elseif ($initialVideoType === 'instagram') echo 'Instagram Reel Preview' . (!empty($product['instagram_channel']) ? ' (@' . esc($product['instagram_channel']) . ')' : '');
                            elseif ($initialVideoType === 'facebook') echo 'Facebook Video Preview' . (!empty($product['facebook_channel']) ? ' (' . esc($product['facebook_channel']) . ')' : '');
                            else echo 'Saree Video Preview';
                        ?></span>
                    </span>

                    <!-- Quick Media Platform Switcher (Merged into single frame) -->
                    <?php 
                        $totalVideoSources = ($hasYoutubeVideo ? 1 : 0) + ($hasInstagramVideo ? 1 : 0) + ($hasFacebookVideo ? 1 : 0) + ($hasUploadedVideo ? 1 : 0);
                    ?>
                    <?php if($totalVideoSources > 1): ?>
                    <div class="video-media-switch-tabs" id="video-media-switch-tabs">
                        <?php if($hasYoutubeVideo): ?>
                            <button type="button" class="video-switch-tab <?= $initialVideoType === 'youtube' ? 'active' : '' ?>" data-type="youtube" title="Watch YouTube Short">
                                <i class="fa-brands fa-youtube"></i> <span>YouTube</span>
                            </button>
                        <?php endif; ?>
                        <?php if($hasInstagramVideo): ?>
                            <button type="button" class="video-switch-tab <?= $initialVideoType === 'instagram' ? 'active' : '' ?>" data-type="instagram" title="Watch Instagram Reel">
                                <i class="fa-brands fa-instagram"></i> <span>Instagram</span>
                            </button>
                        <?php endif; ?>
                        <?php if($hasFacebookVideo): ?>
                            <button type="button" class="video-switch-tab <?= $initialVideoType === 'facebook' ? 'active' : '' ?>" data-type="facebook" title="Watch Facebook Video">
                                <i class="fa-brands fa-facebook"></i> <span>Facebook</span>
                            </button>
                        <?php endif; ?>
                        <?php if($hasUploadedVideo): ?>
                            <button type="button" class="video-switch-tab <?= $initialVideoType === 'video' ? 'active' : '' ?>" data-type="video" title="Watch Saree Video">
                                <i class="fa-solid fa-play"></i> <span>Video</span>
                            </button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <a id="video-external-link" href="<?php 
                            if ($initialVideoType === 'youtube') echo esc($product['shorts_url'] ?? '#');
                            elseif ($initialVideoType === 'instagram') echo esc($product['instagram_url'] ?? '#');
                            elseif ($initialVideoType === 'facebook') echo esc($product['facebook_url'] ?? '#');
                            else echo '#';
                        ?>" target="_blank" style="<?= in_array($initialVideoType, ['youtube', 'instagram', 'facebook']) ? 'display: inline-flex;' : 'display: none;' ?> align-items: center; gap: 5px; font-size: 11.5px; font-weight: 700; color: #fff; text-decoration: none; padding: 4px 10px; border-radius: 20px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); transition: all 0.2s;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> <span id="video-external-text">Watch on <?php 
                                if ($initialVideoType === 'youtube') echo 'YouTube';
                                elseif ($initialVideoType === 'instagram') echo 'Instagram';
                                elseif ($initialVideoType === 'facebook') echo 'Facebook';
                            ?></span>
                        </a>
                        <button type="button" class="video-sound-btn" id="video-sound-toggle-btn" title="Toggle Sound" style="<?= in_array($initialVideoType, ['instagram', 'facebook']) ? 'display: none;' : 'display: inline-flex;' ?>">
                            <i class="fa-solid fa-volume-xmark" id="video-sound-icon"></i>
                            <span id="video-sound-text">Unmute Sound</span>
                        </button>
                    </div>
                </div>
                <div class="video-player-wrap <?= $initialVideoType === 'instagram' ? 'is-instagram' : '' ?>" id="video-player-wrap">
                    <video id="main-video" class="product-main-video product-media-frame" autoplay loop muted playsinline controls style="<?= $initialVideoType === 'video' ? 'display: block;' : 'display: none;' ?>">
                        <?php if($hasUploadedVideo): ?>
                            <source src="<?= $uploadedVideoUrl ?>" type="video/mp4">
                        <?php endif; ?>
                    </video>
                    <iframe id="main-youtube" class="product-main-youtube product-media-frame" style="<?= $initialVideoType === 'youtube' ? 'display: block;' : 'display: none;' ?>" src="<?= $initialYtEmbed ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <iframe id="main-instagram" class="product-main-instagram product-media-frame" style="<?= $initialVideoType === 'instagram' ? 'display: block;' : 'display: none;' ?>" src="<?= $initialVideoType === 'instagram' ? $igEmbedUrl : '' ?>" scrolling="no" allowtransparency="true" allowfullscreen></iframe>
                    <iframe id="main-facebook" class="product-main-facebook product-media-frame" style="<?= $initialVideoType === 'facebook' ? 'display: block;' : 'display: none;' ?>" src="<?= $initialVideoType === 'facebook' ? $fbEmbedUrl : '' ?>" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Thumbnails Strip / Carousel -->
        <div class="product-gallery-strip" id="product-gallery-strip">
            <div class="gallery-thumbnail-item active" data-type="image" data-url="<?= $primaryImgUrl ?>">
                <img src="<?= $primaryImgUrl ?>" alt="Primary Image">
            </div>
            <?php if(!empty($general_gallery)): foreach($general_gallery as $g): 
                $gUrl = base_url(ltrim($g['media_url'], '/'));
            ?>
                <div class="gallery-thumbnail-item" data-type="image" data-url="<?= $gUrl ?>">
                    <img src="<?= $gUrl ?>" alt="Gallery Image">
                </div>
            <?php endforeach; endif; ?>

            <?php if(!empty($product_video)): 
                $vUrl = base_url(ltrim($product_video['media_url'], '/'));
            ?>
                <div class="gallery-thumbnail-item" data-type="video" data-url="<?= $vUrl ?>">
                    <img src="<?= $primaryImgUrl ?>" alt="Product Video">
                    <div class="thumb-video-icon"><i class="fa-solid fa-play"></i></div>
                </div>
            <?php endif; ?>

            <?php if(!empty($product['shorts_url'])): ?>
                <div class="gallery-thumbnail-item" data-type="youtube" data-url="<?= esc($product['shorts_url']) ?>" title="Watch YouTube Short">
                    <img src="<?= $primaryImgUrl ?>" alt="YouTube Short">
                    <div class="thumb-video-icon" style="background: rgba(220, 38, 38, 0.95); color: #fff;"><i class="fa-brands fa-youtube"></i></div>
                </div>
            <?php endif; ?>

            <?php if(!empty($product['instagram_url'])): ?>
                <div class="gallery-thumbnail-item" data-type="instagram" data-url="<?= esc($product['instagram_url']) ?>" data-embed="<?= esc($igEmbedUrl) ?>" title="Watch Instagram Reel">
                    <img src="<?= $primaryImgUrl ?>" alt="Instagram Reel">
                    <div class="thumb-video-icon" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff;"><i class="fa-brands fa-instagram"></i></div>
                </div>
            <?php endif; ?>

            <?php if(!empty($product['facebook_url'])): ?>
                <div class="gallery-thumbnail-item" data-type="facebook" data-url="<?= esc($product['facebook_url']) ?>" data-embed="<?= esc($fbEmbedUrl) ?>" title="Watch Facebook Video">
                    <img src="<?= $primaryImgUrl ?>" alt="Facebook Video">
                    <div class="thumb-video-icon" style="background: #1877f2; color: #fff;"><i class="fa-brands fa-facebook"></i></div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if(!empty($product['blouse_measurement_image'])): ?>
            <div style="margin-top: 24px; padding: 15px; background: #fff; border: 1px solid #f1f5f9; border-radius: 8px;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px; font-weight: 700; color: #1e293b; text-transform: uppercase;">
                    <i class="fa-solid fa-file-image" style="color: #7a1530;"></i> Blouse Measurement Chart
                </h4>
                <a href="<?= base_url(ltrim($product['blouse_measurement_image'], '/')) ?>" target="_blank" style="display: inline-block;">
                    <img src="<?= base_url(ltrim($product['blouse_measurement_image'], '/')) ?>" style="max-width: 140px; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                </a>
            </div>
        <?php endif; ?>
    </div>
    
        <div class="product-info modern-product-info">
        <!-- Breadcrumb / Category Row -->
        <div class="product-breadcrumb-strip">
            <a href="<?= base_url() ?>"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right breadcrumb-sep"></i>
            <a href="<?= base_url('category/saree') ?>">Sarees</a>
            <i class="fa-solid fa-chevron-right breadcrumb-sep"></i>
            <span class="breadcrumb-current"><?= esc($product['name']) ?></span>
        </div>

        <!-- Badges & Social Channel Highlight -->
        <div class="product-badges-row">
            <?php if(!empty($product['shorts_channel'])): ?>
                <span class="product-channel-pill yt-pill">
                    <i class="fa-brands fa-youtube"></i> Featured on <?= esc($product['shorts_channel']) ?>
                </span>
            <?php endif; ?>
            <?php if(!empty($product['instagram_channel'])): ?>
                <span class="product-channel-pill ig-pill">
                    <i class="fa-brands fa-instagram"></i> <?= esc($product['instagram_channel']) ?>
                </span>
            <?php endif; ?>
            <?php if(!empty($product['facebook_channel'])): ?>
                <span class="product-channel-pill fb-pill">
                    <i class="fa-brands fa-facebook"></i> <?= esc($product['facebook_channel']) ?>
                </span>
            <?php endif; ?>
            <?php if(!empty($product['is_combo'])): ?>
                <span class="product-channel-pill combo-pill">
                    <i class="fa-solid fa-gifts"></i> COMBO PACK OFFER
                </span>
            <?php endif; ?>
            <span class="product-channel-pill weaver-pill">
                <i class="fa-solid fa-certificate"></i> Direct Weaver Price
            </span>
        </div>

        <!-- Product Title -->
        <h1 class="product-title modern-title"><?= esc($product['name']) ?></h1>
        
        <!-- Rating & Reviews Ribbon -->
        <div class="product-rating-ribbon">
            <?php 
                $displayRating = (!empty($avg_rating) && (float)$avg_rating > 0) ? (float)$avg_rating : 5.0;
                $displayReviewsCount = (!empty($total_reviews) && (int)$total_reviews > 0) ? (int)$total_reviews : 5;
                $avgStars = round($displayRating);
            ?>
            <div class="rating-stars-wrap">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-<?= ($i <= $avgStars) ? 'solid' : 'regular' ?> fa-star"></i>
                <?php endfor; ?>
            </div>
            <span class="rating-score-num"><?= number_format($displayRating, 1) ?></span>
            <span class="rating-reviews-count">(<?= $displayReviewsCount ?> Verified Reviews)</span>
            <span class="rating-sep">•</span>
            <a href="#customer-reviews-section" class="rating-read-link" onclick="if(typeof openReviewFormDirectly === 'function'){ openReviewFormDirectly(); }">
                <i class="fa-regular fa-comment-dots"></i> Read Reviews
            </a>
        </div>
        
        <!-- Pricing Showcase Card -->
        <div class="product-pricing-card">
            <div class="price-main-row">
                <span class="currency-symbol">₹</span>
                <span id="detail-product-price" class="main-price-val"><?= number_format($initialPrice, 2) ?></span>
                <span id="detail-product-original-price" class="product-original-price strike-price" style="<?= $initialOrigPrice > $initialPrice ? '' : 'display: none;' ?>">₹<?= number_format($initialOrigPrice, 2) ?></span>
                <span id="detail-discount-badge" class="product-discount-badge discount-pill-badge" style="<?= $initialOrigPrice > $initialPrice ? '' : 'display: none;' ?>">SAVE <?= $initialOrigPrice > $initialPrice ? round((($initialOrigPrice - $initialPrice) / $initialOrigPrice) * 100) : 0 ?>%</span>
            </div>
            <div class="price-sub-note">
                <i class="fa-solid fa-shield-check" style="color: #16a34a;"></i> Inclusive of all taxes • <strong>Free Delivery</strong> on prepaid orders across India
            </div>
        </div>

        <!-- Stock Status & SKU Row -->
        <?php 
            $stModel = new \App\Models\SettingsModel();
            $skuSetting = $stModel->where('key', 'module_product_sku')->first();
            $skuModuleEnabled = $skuSetting ? ($skuSetting['value'] === '1' || $skuSetting['value'] === 1) : true;
        ?>
        <div class="product-meta-row">
            <div id="detail-stock-status" class="modern-stock-pill <?= $initialStock > 0 ? 'in-stock' : 'out-of-stock' ?>">
                <span class="stock-pulse-dot"></span>
                <span class="stock-text-label"><?= $initialStock > 0 ? 'In Stock (' . $initialStock . ' Available)' : 'Out of Stock' ?></span>
            </div>
            <?php if($skuModuleEnabled && !empty($product['sku'])): ?>
                <div class="product-sku-badge">
                    <i class="fa-solid fa-barcode"></i> SKU: <strong id="product-sku-val"><?= esc($product['sku']) ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <!-- Color Variant Swatches (Full Redesign) -->
        <?php if(!empty($distinctColors)): 
            $distinctColorsList = array_values($distinctColors);
        ?>
            <div class="detail-option-group modern-option-group">
                <div class="option-header-row">
                    <span class="option-title">
                        <i class="fa-solid fa-palette" style="color: #7a1530;"></i> Color Shade: <strong id="selected-color-name"><?= esc($distinctColorsList[0]['color_name'] ?? '') ?></strong>
                    </span>
                    <span class="option-count-badge"><?= count($distinctColorsList) ?> Colors</span>
                </div>
                <div class="detail-color-swatches modern-swatches-grid">
                    <?php foreach($distinctColorsList as $i => $dc): 
                        $rawThumb = !empty($dc['images'][0]['media_url']) ? $dc['images'][0]['media_url'] : '';
                        $dcThumb = '';
                        if (!empty($rawThumb)) {
                            $dcThumb = (strpos($rawThumb, 'http://') === 0 || strpos($rawThumb, 'https://') === 0) ? $rawThumb : base_url(ltrim($rawThumb, '/'));
                        }
                    ?>
                        <button type="button" 
                                class="detail-color-swatch-btn modern-swatch-btn <?= $i === 0 ? 'active' : '' ?>"
                                data-color="<?= esc($dc['color_name']) ?>"
                                title="<?= esc($dc['color_name']) ?>"
                                aria-label="<?= esc($dc['color_name']) ?>">
                            <div class="swatch-circle-holder" style="background-color: <?= esc($dc['color_code']) ?>;">
                                <?php if(!empty($dcThumb)): ?>
                                    <img src="<?= esc($dcThumb) ?>" alt="<?= esc($dc['color_name']) ?>" class="detail-swatch-img" onerror="this.style.display='none';">
                                <?php endif; ?>
                                <i class="fa-solid fa-check swatch-check-icon"></i>
                            </div>
                            <span class="swatch-color-label"><?= esc($dc['color_name']) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Size / Dhoti Variant Selection (Full Redesign) -->
        <?php 
            $validSizes = array_values(array_filter($distinctSizes ?? [], function($s) { return !empty(trim($s)); }));
        ?>
        <?php if(!empty($validSizes)): ?>
            <div class="detail-option-group modern-option-group">
                <div class="option-header-row">
                    <span class="option-title">
                        <i class="fa-solid fa-ruler-horizontal" style="color: #7a1530;"></i> Size / Option: <strong id="selected-size-name"><?= esc($validSizes[0]) ?></strong>
                    </span>
                    <span class="option-count-badge"><?= count($validSizes) ?> Options</span>
                </div>
                <div class="detail-sizes-grid modern-sizes-grid">
                    <?php foreach($validSizes as $si => $sz): ?>
                        <button type="button" 
                                class="detail-size-btn modern-size-btn <?= $si === 0 ? 'active' : '' ?>"
                                data-size="<?= esc($sz) ?>">
                            <span class="size-btn-text"><?= esc($sz) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Saree & Blouse Measurements Specifications Card -->
        <?php 
            $specsSettingsModel = new \App\Models\SettingsModel();
            $measSettingRow = $specsSettingsModel->where('key', 'module_measurements_section')->first();
            $measSectionEnabled = !isset($measSettingRow['value']) || $measSettingRow['value'] !== '0';

            $rawBlouse = trim($product['blouse_length'] ?? '');
            $isBlouseAttached = !empty($rawBlouse) && !in_array(strtolower($rawBlouse), ['no', 'none', 'without blouse', 'not attached', 'non-attached', '0', '0m', 'false']);
            $blouseLength = $isBlouseAttached ? $rawBlouse : '';

            $isSaree = stripos($product['name'], 'saree') !== false;
            // Only assign default saree measurements if the product is explicitly a Saree
            $sareeLength = !empty($product['saree_length']) ? $product['saree_length'] : ($isSaree ? ($isBlouseAttached ? '6.30 Meters (Including Blouse)' : '5.50 Meters (Without Blouse)') : '');
            $sareeHeight = !empty($product['saree_height']) ? $product['saree_height'] : ($isSaree ? '44 to 47 inches' : '');
            
            $isCombo = !empty($product['is_combo']);
            $dhotiLength = ($isCombo && !empty($product['dhoti_length'])) ? trim($product['dhoti_length']) : '';
            $blouseChartImg = !empty($product['blouse_measurement_image']) ? base_url(ltrim($product['blouse_measurement_image'], '/')) : '';
            $dhotiChartImg = ($isCombo && !empty($product['dhoti_measurement_image'])) ? base_url(ltrim($product['dhoti_measurement_image'], '/')) : '';
            
            $hasMeasurements = $measSectionEnabled && (!empty($sareeLength) || !empty($sareeHeight) || !empty($dhotiLength) || $isBlouseAttached || !empty($blouseChartImg) || !empty($dhotiChartImg));
        ?>

        <?php if($hasMeasurements): ?>
        <div class="modern-specs-card">
            <div class="specs-card-header">
                <div class="specs-card-title">
                    <span class="specs-icon-circle"><i class="fa-solid fa-ruler-combined"></i></span>
                    <div>
                        <h4><?= ($isCombo && !empty($dhotiLength) && $isSaree) ? 'Saree, Dhoti &amp; Blouse Measurements' : ($isSaree ? 'Saree &amp; Blouse Specifications' : ($isCombo ? 'Combo Pack Measurements' : 'Product Specifications')) ?></h4>
                        <span class="specs-sub"><?= $isSaree ? 'Authentic Elampillai traditional draping dimensions' : 'Size and dimension specifications' ?></span>
                    </div>
                </div>
                <span class="specs-badge-pill"><?= $isCombo ? 'Combo Specs' : 'Detail Specs' ?></span>
            </div>
            
            <div class="specs-grid-container">
                <?php if(!empty($sareeLength)): ?>
                <div class="spec-stat-box">
                    <div class="spec-stat-label"><i class="fa-solid fa-arrows-left-right"></i> Saree Length</div>
                    <div class="spec-stat-val"><?= esc($sareeLength) ?></div>
                </div>
                <?php endif; ?>

                <?php if(!empty($sareeHeight)): ?>
                <div class="spec-stat-box">
                    <div class="spec-stat-label"><i class="fa-solid fa-arrows-up-down"></i> Saree Height</div>
                    <div class="spec-stat-val"><?= esc($sareeHeight) ?></div>
                </div>
                <?php endif; ?>

                <?php if(!empty($dhotiLength)): ?>
                <div class="spec-stat-box">
                    <div class="spec-stat-label"><i class="fa-solid fa-vest"></i> Dhoti Option</div>
                    <div class="spec-stat-val"><?= esc($dhotiLength) ?></div>
                </div>
                <?php endif; ?>

                <?php if($isBlouseAttached): ?>
                <div class="spec-stat-box highlight-stat">
                    <div class="spec-stat-label"><i class="fa-solid fa-scissors"></i> Blouse Piece</div>
                    <div class="spec-stat-val"><?= esc($blouseLength) ?> (Attached)</div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Size / Measurement Chart Buttons -->
            <?php if(!empty($blouseChartImg) || !empty($dhotiChartImg)): ?>
            <div class="specs-chart-buttons-row">
                <?php if(!empty($dhotiChartImg)): ?>
                    <button type="button" class="btn-specs-chart" onclick="openMeasurementChartModal('<?= esc($dhotiChartImg) ?>', 'Dhoti Size &amp; Measurement Chart')">
                        <i class="fa-solid fa-vest"></i>
                        <span>Dhoti Size Chart</span>
                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                    </button>
                <?php endif; ?>

                <?php if(!empty($blouseChartImg)): ?>
                    <button type="button" class="btn-specs-chart" onclick="openMeasurementChartModal('<?= esc($blouseChartImg) ?>', 'Blouse Measurement Chart')">
                        <i class="fa-solid fa-scissors"></i>
                        <span>Blouse Size Chart</span>
                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                    </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Highlights Bullet Points -->
        <?php if(!empty($product['highlights'])): 
            $highlightLines = array_filter(array_map('trim', preg_split('/[\r\n]+/', $product['highlights'])));
        ?>
            <div class="modern-highlights-box">
                <div class="highlights-title">
                    <i class="fa-solid fa-sparkles" style="color: #d97706;"></i> Product Highlights
                </div>
                <div class="highlights-list">
                    <?php foreach($highlightLines as $hl): 
                        $hlClean = ltrim($hl, "•-–* \t");
                        if(empty($hlClean)) continue;
                    ?>
                        <div class="highlight-item">
                            <span class="highlight-bullet-icon"><i class="fa-solid fa-check"></i></span>
                            <span class="highlight-item-text"><?= esc($hlClean) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Product Description -->
        <div class="modern-description-card">
            <div class="description-header">
                <i class="fa-solid fa-align-left" style="color: #7a1530;"></i> Product Description &amp; Details
            </div>
            <div class="description-body">
                <?= renderProductDescriptionSafe($product['description'] ?? '') ?>
            </div>
        </div>
        
        <input type="hidden" id="selected-variant-id-input" value="<?= !empty($preparedVariants[0]['id']) ? (int)$preparedVariants[0]['id'] : '' ?>">

        <!-- Action Row (Quantity + Add to Cart + Buy Now + WhatsApp) -->
        <div class="product-action-row modern-action-row">
            <div class="quantity-picker modern-qty-picker">
                <button type="button" class="qty-btn" id="qty-minus" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
                <input type="number" id="product-qty-input" class="qty-input" value="1" min="1" max="<?= $initialStock > 0 ? $initialStock : 1 ?>" readonly>
                <button type="button" class="qty-btn" id="qty-plus" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
            </div>

            <button type="button" 
                    id="detail-add-cart-btn" 
                    class="add-to-cart-btn modern-add-cart-btn"
                    data-product-id="<?= (int)$product['id'] ?>"
                    <?= $initialStock <= 0 ? 'disabled' : '' ?>>
                <i class="fa-solid fa-cart-shopping"></i> <span>Add to Cart</span>
            </button>

            <button type="button" 
                    id="detail-buy-now-btn" 
                    class="buy-now-btn modern-buy-now-btn"
                    data-product-id="<?= (int)$product['id'] ?>"
                    <?= $initialStock <= 0 ? 'disabled' : '' ?>>
                <i class="fa-solid fa-bolt"></i> <span>Buy Now</span>
            </button>

            <button type="button" class="detail-wishlist-btn modern-wishlist-btn" data-product-id="<?= (int)$product['id'] ?>" title="Save to Wishlist" aria-label="Save to Wishlist">
                <i class="fa-regular fa-heart"></i>
            </button>

            <a href="https://wa.me/<?= esc($whatsappPhone) ?>?text=<?= $productEnquiryText ?>" target="_blank" class="whatsapp-action-btn modern-wa-btn" title="Product Enquiry on WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
                <span class="wa-tooltip">Enquire on WhatsApp</span>
            </a>
        </div>

        <!-- Storefront Trust Strip (4 Reassurance Pillars) -->
        <div class="storefront-trust-strip">
            <div class="trust-pillar-item">
                <div class="trust-pillar-icon"><i class="fa-solid fa-certificate"></i></div>
                <div class="trust-pillar-text">
                    <strong>100% Authentic</strong>
                    <span>Elampillai Weave</span>
                </div>
            </div>
            <div class="trust-pillar-item">
                <div class="trust-pillar-icon"><i class="fa-solid fa-tags"></i></div>
                <div class="trust-pillar-text">
                    <strong>Direct Weaver</strong>
                    <span>No Middlemen Price</span>
                </div>
            </div>
            <div class="trust-pillar-item">
                <div class="trust-pillar-icon"><i class="fa-solid fa-truck-fast"></i></div>
                <div class="trust-pillar-text">
                    <strong>Express Delivery</strong>
                    <span>Safe All-India Shipping</span>
                </div>
            </div>
            <div class="trust-pillar-item">
                <div class="trust-pillar-icon"><i class="fa-solid fa-rotate-left"></i></div>
                <div class="trust-pillar-text">
                    <strong>Easy Exchange</strong>
                    <span>7-Day Return Support</span>
                </div>
            </div>
        </div>
    </div></div>
</div>

<!-- Customer Reviews Section -->
<div class="product-reviews-section" id="customer-reviews-section" style="margin-top: 50px; margin-bottom: 50px; border-top: 1.5px solid #e2e8f0; padding-top: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0 0 6px 0;">Customer Reviews</h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Verified customer feedback and experiences</p>
        </div>
        <button type="button" id="btn-toggle-review-form" class="buy-now-btn" style="flex: none; min-width: 170px; height: 42px; font-size: 14px;">
            <i class="fa-solid fa-pen-to-square"></i> Write a Review
        </button>
    </div>

    <!-- Rating Summary Box -->
    <div class="reviews-summary-card">
        <div style="text-align: center; min-width: 130px; border-right: 1.5px solid #ebdcc1; padding-right: 20px;">
            <?php 
                $cardRating = (!empty($avg_rating) && (float)$avg_rating > 0) ? (float)$avg_rating : 5.0;
                $cardReviewsCount = (!empty($total_reviews) && (int)$total_reviews > 0) ? (int)$total_reviews : 5;
                $cardStars = round($cardRating);
            ?>
            <div class="reviews-avg-score"><?= number_format($cardRating, 1) ?></div>
            <div class="reviews-avg-stars">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-<?= ($i <= $cardStars) ? 'solid' : 'regular' ?> fa-star"></i>
                <?php endfor; ?>
            </div>
            <div style="font-size: 12px; color: #64748b; font-weight: 600;">Based on <?= $cardReviewsCount ?> reviews</div>
        </div>

        <div style="flex: 1; padding-left: 10px;">
            <div style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Authentic Saree Quality Guaranteed</div>
            <p style="font-size: 13px; color: #64748b; margin: 0; line-height: 1.5;">Every saree is hand-inspected in Elampillai before dispatch. Share your review after purchase to help other customers choose!</p>
        </div>
    </div>

    <!-- Review Form Card (Toggled via button) -->
    <div id="review-submission-card" class="review-form-card" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0;">Share Your Experience</h3>
            <button type="button" id="btn-close-review-form" style="background: none; border: none; font-size: 18px; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>

        <form action="<?= base_url('product/submit-review') ?>" method="POST" enctype="multipart/form-data" id="customer-review-form">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <input type="hidden" name="rating" id="review-rating-value" value="5">

            <div class="form-group">
                <label>Overall Rating *</label>
                <div class="review-star-picker" id="interactive-star-picker">
                    <i class="fa-solid fa-star selected" data-value="1"></i>
                    <i class="fa-solid fa-star selected" data-value="2"></i>
                    <i class="fa-solid fa-star selected" data-value="3"></i>
                    <i class="fa-solid fa-star selected" data-value="4"></i>
                    <i class="fa-solid fa-star selected" data-value="5"></i>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <?php 
                    $custName = session()->get('customer_name') ?? '';
                    $custEmail = session()->get('customer_email') ?? '';
                ?>
                <div class="form-group">
                    <label for="rev-name">Your Full Name *</label>
                    <input type="text" id="rev-name" name="customer_name" value="<?= esc($custName) ?>" required placeholder="e.g. Priya Sundaram">
                </div>
                <div class="form-group">
                    <label for="rev-email">Email Address *</label>
                    <input type="email" id="rev-email" name="customer_email" value="<?= esc($custEmail) ?>" required placeholder="name@example.com">
                </div>
            </div>

            <div class="form-group">
                <label for="rev-title">Review Headline / Title *</label>
                <input type="text" id="rev-title" name="review_title" required placeholder="e.g. Beautiful Elampillai cotton saree &amp; rich zari pallu!">
            </div>

            <div class="form-group">
                <label for="rev-text">Detailed Review *</label>
                <textarea id="rev-text" name="review_text" rows="4" required placeholder="Tell us about the drape, soft cotton texture, color accuracy, packaging, and overall experience..."></textarea>
            </div>

            <div class="form-group">
                <label for="rev-image">Attach Photo of Saree (Optional)</label>
                <input type="file" id="rev-image" name="review_image" accept="image/*" style="font-size: 13px;">
                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Accepted formats: JPG, PNG, WEBP (Max 8MB)</div>
            </div>

            <div style="margin-top: 14px; margin-bottom: 8px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; cursor: pointer; user-select: none;">
                    <input type="checkbox" name="subscribe_newsletter" value="1" checked style="accent-color: #7a1530; width: 16px; height: 16px;">
                    <span>Add me to Selvi Tex family newsletter to receive early weaver drops &amp; festive discounts</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" class="buy-now-btn" style="flex: none; min-width: 170px; height: 44px; font-size: 14px;">
                    <i class="fa-solid fa-paper-plane"></i> Submit 5-Star Review
                </button>
                <button type="button" id="btn-cancel-review" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0 18px; font-weight: 600; font-size: 14px; color: #475569; cursor: pointer;">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Approved Reviews List -->
    <?php if(!empty($approved_reviews)): ?>
        <div class="reviews-list-wrap">
            <?php foreach($approved_reviews as $rev): ?>
                <div class="review-item-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="font-size: 15px; color: #0f172a;"><?= esc($rev['customer_name']) ?></strong>
                                <span style="background: #e6f4ea; color: #137333; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-circle-check" style="font-size: 10px;"></i> Verified Buyer
                                </span>
                            </div>
                            <div style="color: #f59e0b; font-size: 13px; margin-top: 4px;">
                                <?php for($s = 1; $s <= 5; $s++): ?>
                                    <i class="fa-<?= ($s <= (int)$rev['rating']) ? 'solid' : 'regular' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div style="font-size: 12px; color: #94a3b8;">
                            <?= date('M d, Y', strtotime($rev['created_at'])) ?>
                        </div>
                    </div>

                    <h4 style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 10px 0 6px 0;"><?= esc($rev['review_title']) ?></h4>
                    <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 12px 0;"><?= nl2br(esc($rev['review_text'])) ?></p>

                    <?php if(!empty($rev['review_image'])): ?>
                        <div style="margin-top: 10px;">
                            <a href="<?= base_url(ltrim($rev['review_image'], '/')) ?>" target="_blank" style="display: inline-block;">
                                <img src="<?= base_url(ltrim($rev['review_image'], '/')) ?>" alt="Customer Photo" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1.5px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="reviews-empty-state">
            <i class="fa-regular fa-comment-dots"></i>
            <h4 style="font-size: 16px; font-weight: 700; color: #334155; margin: 0 0 6px 0;">No Customer Reviews Yet</h4>
            <p style="font-size: 13px; color: #64748b; margin: 0 0 16px 0;">Be the first customer to review this saree after receiving your order!</p>
            <button type="button" onclick="document.getElementById('btn-toggle-review-form').click();" class="buy-now-btn" style="flex: none; display: inline-flex; height: 38px; font-size: 13px; padding: 0 20px;">
                <i class="fa-solid fa-pen"></i> Write the First Review
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- JSON Data Block for Product Variants, Media Gallery, and Dynamic Switching -->
<script type="application/json" id="product-variants-json">
<?= json_encode([
    'product' => $product,
    'variants' => $preparedVariants,
    'fallbackMedia' => $fallbackMedia,
    'primaryImgUrl' => $primaryImgUrl
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
</script>

<?php if(!empty($related_products)): ?>
<div class="related-products">
    <h2 class="section-title">You May Also Like</h2>
    <div class="product-grid">
        <?php foreach($related_products as $rp): ?>
            <?php 
                $rpVariants = !empty($rp['variants']) ? $rp['variants'] : [];
                $rpColorVariants = [];
                foreach ($rpVariants as $v) {
                    $cCode = !empty($v['color_code']) ? $v['color_code'] : null;
                    $cName = !empty($v['color_name']) ? $v['color_name'] : null;
                    if ($cCode || $cName) {
                        $k = !empty($cName) ? strtolower(trim($cName)) : strtolower(trim($cCode));
                        if (!isset($rpColorVariants[$k])) {
                            $rpColorVariants[$k] = $v;
                        }
                    }
                }
                $rpPrice = (float)$rp['price'];
                $rpOrigPrice = !empty($rp['original_price']) ? (float)$rp['original_price'] : 0;
                if (!empty($rpVariants[0]['sale_price'])) {
                    $rpPrice = (float)$rpVariants[0]['sale_price'];
                }
                if (!empty($rpVariants[0]['original_price'])) {
                    $rpOrigPrice = (float)$rpVariants[0]['original_price'];
                }
                $rpImg = base_url('assets/images/placeholder.jpg');
                if (!empty($rp['image_url'])) {
                    if (strpos($rp['image_url'], 'http') === 0) {
                        $rpImg = $rp['image_url'];
                    } elseif (file_exists(FCPATH . ltrim($rp['image_url'], '/'))) {
                        $rpImg = base_url(ltrim($rp['image_url'], '/'));
                    }
                }
                $allCardGallery = [$rpImg];
                if (!empty($rp['media'])) {
                    foreach ($rp['media'] as $pm) {
                        if ($pm['media_type'] === 'image' && !empty($pm['media_url'])) {
                            $mUrl = base_url(ltrim($pm['media_url'], '/'));
                            if (!in_array($mUrl, $allCardGallery)) {
                                $allCardGallery[] = $mUrl;
                            }
                        }
                    }
                }
                if (!empty($rpVariants)) {
                    foreach ($rpVariants as $v) {
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
                $rpSizes = [];
                if (!empty($rpVariants)) {
                    foreach ($rpVariants as $v) {
                        $rawSize = trim($v['size'] ?? '');
                        if ($rawSize !== '') {
                            $parts = array_filter(array_map('trim', explode(',', $rawSize)));
                            foreach ($parts as $p) {
                                if ($p !== '' && !in_array($p, $rpSizes)) {
                                    $rpSizes[] = $p;
                                }
                            }
                        }
                    }
                }
                if (empty($rpSizes) && !empty($rp['size'])) {
                    $rawSize = trim($rp['size']);
                    if ($rawSize !== '') {
                        $parts = array_filter(array_map('trim', explode(',', $rawSize)));
                        foreach ($parts as $p) {
                            if ($p !== '' && !in_array($p, $rpSizes)) {
                                $rpSizes[] = $p;
                            }
                        }
                    }
                }
                $rpPrimary = $rpImg;
                $rpSecondary = (!empty($allCardGallery[1]) && $allCardGallery[1] !== $rpPrimary) ? $allCardGallery[1] : null;
            ?>
            <div class="product-card" data-id="<?= (int)$rp['id'] ?>" data-variants='<?= json_encode($rpVariants, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>' data-price="<?= $rpPrice ?>" data-original-price="<?= $rpOrigPrice ?>" data-selected-size="<?= !empty($rpSizes[0]) ? esc($rpSizes[0]) : '' ?>">
                <div class="card-img-holder" data-gallery='<?= json_encode($allCardGallery, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'>
                    <a href="<?= base_url('product/' . esc($rp['slug'])) ?>" class="card-img-link" style="text-decoration: none; color: inherit; display: block;">
                        <img src="<?= $rpPrimary ?>" class="card-primary-img" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="<?= esc($rp['name']) ?>" loading="lazy">
                        <?php if (!empty($rpSecondary)): ?>
                            <img src="<?= $rpSecondary ?>" class="card-secondary-img" onerror="this.onerror=null;this.style.display='none';" alt="<?= esc($rp['name']) ?> preview" loading="lazy">
                        <?php endif; ?>
                    </a>
                </div>
                <div class="product-card-body">
                    <a href="<?= base_url('product/' . esc($rp['slug'])) ?>" style="text-decoration: none; color: inherit; display: block;">
                        <h3 class="product-card-title"><?= esc($rp['name']) ?></h3>
                    </a>

                    <?php if(!empty($rpColorVariants)): ?>
                        <div class="product-card-swatches">
                            <?php $cIdx = 0; foreach($rpColorVariants as $cv): 
                                $cvImg = !empty($cv['images'][0]['media_url']) ? base_url(ltrim($cv['images'][0]['media_url'], '/')) : '';
                                $cvHex = !empty($cv['color_code']) ? $cv['color_code'] : '#7a1530';
                                $cvName = $cv['color_name'] ?: 'Option';
                                $cvSale = !empty($cv['sale_price']) ? (float)$cv['sale_price'] : (float)$rp['price'];
                                $cvOrig = !empty($cv['original_price']) ? (float)$cv['original_price'] : (!empty($rp['original_price']) ? (float)$rp['original_price'] : 0);
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

                    <?php if(!empty($rpSizes)): ?>
                        <div class="product-card-sizes">
                            <span class="card-sizes-label">Sizes:</span>
                            <?php foreach($rpSizes as $sIdx => $sz): 
                                $szVariant = null;
                                if (!empty($rpVariants)) {
                                    foreach ($rpVariants as $v) {
                                        if (!empty($v['size'])) {
                                            $vSizes = array_filter(array_map('trim', explode(',', $v['size'])));
                                            if (in_array($sz, $vSizes)) {
                                                $szVariant = $v;
                                                break;
                                            }
                                        }
                                    }
                                }
                                $szSale = !empty($szVariant['sale_price']) ? (float)$szVariant['sale_price'] : (float)$rp['price'];
                                $szOrig = !empty($szVariant['original_price']) ? (float)$szVariant['original_price'] : (!empty($rp['original_price']) ? (float)$rp['original_price'] : 0);
                                $szVarId = !empty($szVariant['id']) ? (int)$szVariant['id'] : (!empty($rpVariants[0]['id']) ? (int)$rpVariants[0]['id'] : '');
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

                    <div class="product-card-price" style="margin-bottom: 15px;">
                        <span class="card-sale-price" data-base-price="<?= $rpPrice ?>">₹<?= number_format($rpPrice, 2) ?></span>
                        <?php if($rpOrigPrice > $rpPrice): ?>
                            <span class="card-original-price" data-base-price="<?= $rpOrigPrice ?>" style="text-decoration: line-through; color: #999; font-size: 13px; margin-left: 8px;">₹<?= number_format($rpOrigPrice, 2) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-action-btns" style="display: flex; gap: 6px; padding: 0 14px 14px 14px; align-items: center; width: 100%; box-sizing: border-box;">
                    <button type="button" 
                            class="card-add-cart-btn" 
                            data-product-id="<?= (int)$rp['id'] ?>" 
                            data-variant-id="<?= !empty($rpVariants[0]['id']) ? (int)$rpVariants[0]['id'] : '' ?>"
                            title="Add to Cart"
                            style="flex: 1; background: #ffffff; color: #7a1530; border: 1.5px solid #7a1530; padding: 8px 6px; border-radius: 6px; font-weight: 700; font-size: 12px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 4px; transition: all 0.2s;">
                        <i class="fa-solid fa-cart-shopping"></i> <span class="btn-text">Add Cart</span>
                    </button>
                    <a href="<?= base_url('checkout?product_id=' . $rp['id'] . (!empty($rpVariants[0]['id']) ? '&variant_id=' . (int)$rpVariants[0]['id'] : '')) ?>" 
                       class="card-buy-now-btn"
                       data-product-id="<?= (int)$rp['id'] ?>"
                       data-variant-id="<?= !empty($rpVariants[0]['id']) ? (int)$rpVariants[0]['id'] : '' ?>"
                       title="Checkout this product individually"
                       style="flex: 1; background: #7a1530; color: #ffffff; text-align: center; padding: 8px 6px; border-radius: 6px; font-weight: 700; text-decoration: none; font-size: 12px; display: flex; justify-content: center; align-items: center; gap: 4px; transition: all 0.2s;">
                        <i class="fa-solid fa-bolt"></i> <span class="btn-text">Buy Now</span>
                    </a>
                    <a href="https://wa.me/<?= esc($whatsappPhone) ?>?text=<?= urlencode("Hello Selvi Tex, I am interested in " . $rp['name'] . " (" . base_url('product/' . $rp['slug']) . ")") ?>" target="_blank" class="card-whatsapp-btn" style="width: 36px; height: 36px; background: #25D366; color: white; display: flex; justify-content: center; align-items: center; border-radius: 6px; text-decoration: none; flex-shrink: 0;" title="Product Enquiry on WhatsApp">
                        <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Fullscreen Mobile & Desktop Image Zoom Lightbox Modal -->
<div class="product-zoom-lightbox" id="product-zoom-lightbox" aria-hidden="true" style="display: none;">
    <div class="zoom-lightbox-backdrop" id="zoom-lightbox-backdrop"></div>
    <div class="zoom-lightbox-dialog">
        <!-- Top Toolbar -->
        <div class="zoom-lightbox-header">
            <div class="zoom-lightbox-title-wrap">
                <span class="zoom-lightbox-title"><?= esc($product['name']) ?></span>
                <span class="zoom-lightbox-counter" id="zoom-lightbox-counter">1 / 1</span>
            </div>
            <div class="zoom-lightbox-actions">
                <button type="button" class="zoom-action-btn" id="zoom-btn-out" title="Zoom Out" aria-label="Zoom Out">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <button type="button" class="zoom-action-btn zoom-level-badge" id="zoom-btn-reset" title="Reset Zoom" aria-label="Reset Zoom">
                    <span id="zoom-level-label">100%</span>
                </button>
                <button type="button" class="zoom-action-btn" id="zoom-btn-in" title="Zoom In" aria-label="Zoom In">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <button type="button" class="zoom-action-btn zoom-close-btn" id="zoom-btn-close" title="Close (Esc)" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Interactive Pan/Pinch Viewport -->
        <div class="zoom-lightbox-viewport" id="zoom-lightbox-viewport">
            <div class="zoom-lightbox-canvas" id="zoom-lightbox-canvas">
                <img src="<?= $primaryImgUrl ?>" alt="<?= esc($product['name']) ?>" id="zoom-lightbox-img" draggable="false">
            </div>
            <div class="zoom-touch-hint" id="zoom-touch-hint">
                <i class="fa-solid fa-hand-pointer"></i> Pinch or double-tap to inspect saree weave
            </div>
            <!-- Navigation arrows -->
            <button type="button" class="zoom-nav-arrow prev" id="zoom-nav-prev" title="Previous Image" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="zoom-nav-arrow next" id="zoom-nav-next" title="Next Image" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <!-- Bottom Thumbnails Strip -->
        <div class="zoom-lightbox-footer" id="zoom-lightbox-footer">
            <div class="zoom-lightbox-thumbs" id="zoom-lightbox-thumbs">
                <!-- Populated dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Measurement / Size Chart Lightbox Modal -->
<div id="measurement-chart-modal" style="display: none; position: fixed; inset: 0; z-index: 100000; background: rgba(0,0,0,0.85); align-items: center; justify-content: center; padding: 15px; backdrop-filter: blur(4px);">
    <div style="background: #ffffff; border-radius: 12px; max-width: 650px; width: 100%; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.35); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid #e2e8f0; background: #fafafa;">
            <div style="font-weight: 800; color: #7a1530; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-ruler-combined"></i>
                <span id="measurement-modal-title">Size &amp; Measurement Chart</span>
            </div>
            <button type="button" onclick="closeMeasurementChartModal()" style="border: none; background: #f1f5f9; color: #475569; width: 32px; height: 32px; border-radius: 50%; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div style="padding: 16px; overflow-y: auto; text-align: center; background: #ffffff;">
            <img id="measurement-modal-img" src="" alt="Size Chart" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        </div>
        <div style="padding: 10px 18px; border-top: 1px solid #f1f5f9; text-align: right; background: #fafafa;">
            <button type="button" onclick="closeMeasurementChartModal()" style="background: #7a1530; color: #ffffff; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer;">Close</button>
        </div>
    </div>
</div>
<script>
function openMeasurementChartModal(url, title) {
    var modal = document.getElementById('measurement-chart-modal');
    var img = document.getElementById('measurement-modal-img');
    var t = document.getElementById('measurement-modal-title');
    if (img) img.src = url;
    if (t) t.textContent = title || 'Size & Measurement Chart';
    if (modal) modal.style.display = 'flex';
}
function closeMeasurementChartModal() {
    var modal = document.getElementById('measurement-chart-modal');
    if (modal) modal.style.display = 'none';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMeasurementChartModal();
});
</script>


<!-- Dedicated Modern Luxury Storefront Scoped Styles -->
<style>
/* Modern Product Detail Container */
.product-detail-container {
    max-width: 1320px;
    margin: 30px auto 60px;
    padding: 0 24px;
    display: grid !important;
    grid-template-columns: minmax(380px, 540px) 1fr !important;
    gap: 46px !important;
    align-items: start !important;
}

/* Left Column - Sticky Media Showcase */
.product-images {
    position: sticky !important;
    top: 90px !important;
    width: 100% !important;
    max-width: 540px !important;
}

.product-media-showcase-container {
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    border: 1px solid #f1f5f9;
}

.product-top-image-box {
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    background: #fafaf9;
}

.product-top-image-box img.main-image {
    width: 100%;
    height: auto;
    max-height: 620px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.image-zoom-badge {
    position: absolute;
    bottom: 14px;
    right: 14px;
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    color: #1e293b;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    pointer-events: none;
}

/* Gallery Thumbnails */
.product-gallery-strip {
    display: flex;
    gap: 10px;
    margin-top: 14px;
    overflow-x: auto;
    padding: 4px 2px 8px;
    scrollbar-width: thin;
}

.gallery-thumbnail-item {
    width: 76px;
    height: 76px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    flex-shrink: 0;
    position: relative;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.gallery-thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-thumbnail-item:hover {
    border-color: #cbd5e1;
    transform: translateY(-2px);
}

.gallery-thumbnail-item.active {
    border-color: #7a1530;
    box-shadow: 0 0 0 2px rgba(122, 21, 48, 0.2), 0 4px 10px rgba(122, 21, 48, 0.25);
    transform: translateY(-2px);
}

/* Right Column - Modern Product Info */
.modern-product-info {
    display: flex;
    flex-direction: column;
    gap: 0;
}

/* Breadcrumb Strip */
.product-breadcrumb-strip {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: #64748b;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.product-breadcrumb-strip a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s;
}

.product-breadcrumb-strip a:hover {
    color: #7a1530;
}

.breadcrumb-sep {
    font-size: 9px;
    color: #cbd5e1;
}

.breadcrumb-current {
    color: #1e293b;
    font-weight: 600;
    max-width: 280px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Badges Row */
.product-badges-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.product-channel-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}

.product-channel-pill.yt-pill {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fee2e2;
}

.product-channel-pill.ig-pill {
    background: #fdf2f8;
    color: #e1306c;
    border: 1px solid #fbcfe8;
}

.product-channel-pill.fb-pill {
    background: #eff6ff;
    color: #1877f2;
    border: 1px solid #bfdbfe;
}

.product-channel-pill.combo-pill {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
}

.product-channel-pill.weaver-pill {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

/* Product Title */
.modern-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
    margin: 0 0 10px 0;
    letter-spacing: -0.3px;
}

/* Rating Ribbon */
.product-rating-ribbon {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}

.rating-stars-wrap {
    color: #f59e0b;
    font-size: 14px;
    display: inline-flex;
    gap: 2px;
}

.rating-score-num {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
}

.rating-reviews-count {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.rating-sep {
    color: #cbd5e1;
    font-size: 14px;
}

.rating-read-link {
    font-size: 13px;
    color: #7a1530;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: color 0.2s;
}

.rating-read-link:hover {
    color: #901939;
    text-decoration: underline;
}

/* Pricing Card */
.product-pricing-card {
    background: linear-gradient(135deg, #fdfbf7 0%, #ffffff 100%);
    border: 1.5px solid #f1e5cb;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(122, 21, 48, 0.04);
}

.price-main-row {
    display: flex;
    align-items: baseline;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 6px;
}

.currency-symbol {
    font-size: 24px;
    font-weight: 800;
    color: #7a1530;
}

.main-price-val {
    font-size: 32px;
    font-weight: 900;
    color: #7a1530;
    letter-spacing: -0.5px;
}

.strike-price {
    font-size: 18px;
    color: #94a3b8;
    text-decoration: line-through;
    font-weight: 600;
}

.discount-pill-badge {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #ffffff;
    font-size: 12px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
}

.price-sub-note {
    font-size: 12.5px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Stock & SKU Meta Row */
.product-meta-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
    flex-wrap: wrap;
}

.modern-stock-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
}

.modern-stock-pill.in-stock {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.modern-stock-pill.out-of-stock {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.stock-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #10b981;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulseStock 2s infinite;
}

@keyframes pulseStock {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 7px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.product-sku-badge {
    font-size: 12px;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 5px 12px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.product-sku-badge strong {
    color: #1e293b;
    font-family: 'Courier New', Courier, monospace;
}

/* Option Groups (Colors & Sizes) */
.modern-option-group {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}

.option-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.option-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.option-title strong {
    color: #7a1530;
}

.option-count-badge {
    font-size: 11.5px;
    background: #f1f5f9;
    color: #64748b;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 700;
}

/* Modern Circular Color Swatches */
.modern-swatches-grid {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.modern-swatch-btn {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    outline: none;
    transition: transform 0.2s ease;
}

.swatch-circle-holder {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    box-shadow: 0 0 0 1.5px #cbd5e1, 0 3px 8px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.detail-swatch-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    inset: 0;
}

.swatch-check-icon {
    font-size: 13px;
    color: #ffffff;
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.2s ease;
    text-shadow: 0 1px 3px rgba(0,0,0,0.6);
    z-index: 2;
}

.modern-swatch-btn:hover .swatch-circle-holder {
    transform: scale(1.1);
    box-shadow: 0 0 0 2px #7a1530, 0 5px 12px rgba(122, 21, 48, 0.2);
}

.modern-swatch-btn.active .swatch-circle-holder {
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4.5px #7a1530, 0 6px 14px rgba(122, 21, 48, 0.35);
    transform: scale(1.08);
}

.modern-swatch-btn.active .swatch-check-icon {
    opacity: 1;
    transform: scale(1);
}

.swatch-color-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
    max-width: 64px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-align: center;
}

.modern-swatch-btn.active .swatch-color-label {
    color: #7a1530;
    font-weight: 800;
}

/* Modern Size Buttons */
.modern-sizes-grid {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.modern-size-btn {
    min-width: 64px;
    padding: 10px 18px;
    font-size: 13.5px;
    font-weight: 700;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    color: #1e293b;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

.modern-size-btn:hover {
    border-color: #7a1530;
    color: #7a1530;
    background: #fff5f7;
    transform: translateY(-1px);
}

.modern-size-btn.active {
    border-color: #7a1530;
    background: #7a1530;
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(122, 21, 48, 0.3);
    transform: translateY(-1px);
}

/* Modern Specs Card */
.modern-specs-card {
    background: linear-gradient(135deg, #fdfbf7 0%, #fffcf5 100%);
    border: 1.5px solid #ebdcc1;
    border-radius: 14px;
    padding: 18px 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(122, 21, 48, 0.04);
}

.specs-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 8px;
}

.specs-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.specs-icon-circle {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #7a1530;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}

.specs-card-title h4 {
    margin: 0;
    font-size: 14px;
    font-weight: 800;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.specs-sub {
    font-size: 11.5px;
    color: #64748b;
    display: block;
    margin-top: 2px;
}

.specs-badge-pill {
    font-size: 11px;
    background: #fff5f7;
    color: #7a1530;
    border: 1px solid #fecdd3;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 700;
}

.specs-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 12px;
}

.spec-stat-box {
    background: #ffffff;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #ebdcc1;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

.spec-stat-box.highlight-stat {
    background: #fffbf0;
    border-color: #f6d899;
}

.spec-stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.spec-stat-label i {
    color: #7a1530;
}

.spec-stat-val {
    font-size: 13.5px;
    font-weight: 800;
    color: #1e293b;
}

.specs-chart-buttons-row {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px dashed #ebdcc1;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-specs-chart {
    background: #ffffff;
    border: 1.5px solid #7a1530;
    color: #7a1530;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 12.5px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.btn-specs-chart:hover {
    background: #7a1530;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(122, 21, 48, 0.25);
}

/* Highlights Box */
.modern-highlights-box {
    margin-bottom: 24px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
}

.highlights-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.highlights-list {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.highlight-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    color: #334155;
    line-height: 1.5;
}

.highlight-bullet-icon {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #ecfdf5;
    color: #16a34a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
    margin-top: 2px;
}

/* Description Card */
.modern-description-card {
    margin-bottom: 26px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.description-header {
    background: #f8fafc;
    padding: 12px 18px;
    font-size: 13.5px;
    font-weight: 800;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.description-body {
    padding: 18px 20px;
    background: #ffffff;
    font-size: 14px;
    color: #475569;
    line-height: 1.7;
}

/* Modern Action Row */
.modern-action-row {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.modern-qty-picker {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    height: 50px;
    padding: 0 4px;
}

.modern-qty-picker .qty-btn {
    width: 36px;
    height: 40px;
    border: none;
    background: none;
    cursor: pointer;
    color: #475569;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
}

.modern-qty-picker .qty-btn:hover {
    color: #7a1530;
}

.modern-qty-picker .qty-input {
    width: 42px;
    text-align: center;
    border: none;
    background: none;
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    outline: none;
}

.modern-add-cart-btn {
    flex: 1.2;
    min-width: 140px;
    height: 50px;
    background: #ffffff !important;
    color: #7a1530 !important;
    border: 2px solid #7a1530 !important;
    border-radius: 10px !important;
    font-size: 14.5px !important;
    font-weight: 800 !important;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(122, 21, 48, 0.12);
}

.modern-add-cart-btn:hover {
    background: #fff5f7 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(122, 21, 48, 0.2);
}

.modern-buy-now-btn {
    flex: 1.5;
    min-width: 150px;
    height: 50px;
    background: linear-gradient(135deg, #7a1530 0%, #9c1b3f 100%) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    font-size: 15px !important;
    font-weight: 800 !important;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 16px rgba(122, 21, 48, 0.35);
}

.modern-buy-now-btn:hover {
    background: linear-gradient(135deg, #8d1a37 0%, #b22047 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(122, 21, 48, 0.45);
}

.modern-wishlist-btn {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.modern-wishlist-btn:hover {
    border-color: #ec4899;
    color: #ec4899;
    background: #fdf2f8;
    transform: translateY(-2px);
}

.modern-wa-btn {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: #25d366;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(37, 211, 102, 0.3);
    position: relative;
}

.modern-wa-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 6px 16px rgba(37, 211, 102, 0.45);
}

.wa-tooltip {
    display: none;
}

/* Storefront Trust Strip */
.storefront-trust-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    padding: 18px 16px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    margin-top: 10px;
}

.trust-pillar-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.trust-pillar-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #7a1530;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.trust-pillar-text {
    display: flex;
    flex-direction: column;
    line-height: 1.25;
}

.trust-pillar-text strong {
    font-size: 12px;
    color: #1e293b;
    font-weight: 700;
}

.trust-pillar-text span {
    font-size: 11px;
    color: #64748b;
}

/* Responsive Overrides */
@media (max-width: 992px) {
    .product-detail-container {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
        padding: 0 16px;
    }
    .product-images {
        position: static !important;
        max-width: 100% !important;
    }
    .storefront-trust-strip {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
}

@media (max-width: 600px) {
    .modern-title {
        font-size: 22px;
    }
    .main-price-val {
        font-size: 26px;
    }
    .modern-action-row {
        gap: 8px;
    }
    .modern-add-cart-btn, .modern-buy-now-btn {
        font-size: 13.5px !important;
        height: 46px;
    }
    .storefront-trust-strip {
        grid-template-columns: 1fr;
    }
}
</style>

<?= view('user/footer') ?>
