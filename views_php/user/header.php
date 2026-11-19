<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover, shrink-to-fit=no">
    <?php 
        $settingsModel = new \App\Models\SettingsModel();
        $seoSetting = $settingsModel->where('key', 'seo_pages')->first();
        $gaSetting = $settingsModel->where('key', 'google_analytics')->first();
        $shortNameSetting = $settingsModel->where('key', 'short_name')->first();
        $businessShortName = $shortNameSetting ? esc($shortNameSetting['value']) : 'Selvi Tex';
        
        $seoData = [];
        $faviconSetting = $settingsModel->where('key', 'favicon')->first();
        $logoSetting = $settingsModel->where('key', 'business_logo')->first();
        $bizNameSetting = $settingsModel->where('key', 'business_name')->first();
        $rawBizName = ($bizNameSetting && !empty($bizNameSetting['value'])) ? $bizNameSetting['value'] : 'Selvi Tex';
        $headerBizName = trim(preg_replace('/\s*est\.?\s*\d{4}/i', '', $rawBizName)) ?: 'Selvi Tex';
        $currentLogoUrl = ($logoSetting && !empty($logoSetting['value'])) ? base_url($logoSetting['value']) : base_url('assets/images/logo.png');

        $globalFontSetting = $settingsModel->where('key', 'global_font_family')->first();
        $globalFont = ($globalFontSetting && !empty($globalFontSetting['value'])) ? trim($globalFontSetting['value']) : 'Outfit';
        $headingFontSetting = $settingsModel->where('key', 'heading_font_family')->first();
        $headingFont = ($headingFontSetting && !empty($headingFontSetting['value'])) ? trim($headingFontSetting['value']) : 'Cinzel';
        
        $favicon = base_url('assets/images/logo.png');
        if ($faviconSetting && !empty($faviconSetting['value'])) {
            $favicon = base_url($faviconSetting['value']);
        } elseif ($logoSetting && !empty($logoSetting['value'])) {
            $favicon = base_url($logoSetting['value']);
        }
        
        if ($seoSetting && !empty($seoSetting['value'])) {
            $seoData = json_decode($seoSetting['value'], true);
            if (!is_array($seoData)) $seoData = [];
        }

        $uri = service('uri');
        $pageKey = 'home';
        $defaultPageTitle = 'Home';
        if ($uri->getTotalSegments() > 0) {
            $lastSegment = $uri->getSegment($uri->getTotalSegments());
            if ($lastSegment === 'new-arrivals') {
                $pageKey = 'new_arrivals';
                $defaultPageTitle = 'New Arrivals';
            } elseif ($lastSegment === 'best-sellers') {
                $pageKey = 'best_sellers';
                $defaultPageTitle = 'Best Sellers';
            } elseif ($lastSegment === 'collections' || $uri->getSegment(1) === 'collections') {
                $pageKey = 'collections';
                $defaultPageTitle = 'All Collections';
            } else {
                $pageKey = 'other';
                $defaultPageTitle = ucwords(str_replace('-', ' ', $lastSegment));
            }
        }

        if (!empty($meta_title)) {
            $pageTitle = $meta_title;
        } elseif (!empty($page_title)) {
            $pageTitle = $page_title . ' | ' . $businessShortName;
        } elseif (!empty($seoData[$pageKey]['title'])) {
            $pageTitle = $seoData[$pageKey]['title'] . ' | ' . $businessShortName;
        } else {
            $pageTitle = $defaultPageTitle . ' | ' . $businessShortName;
        }

        if (!empty($meta_description)) {
            $pageDesc = $meta_description;
        } elseif (!empty($seoData[$pageKey]['description'])) {
            $pageDesc = $seoData[$pageKey]['description'];
        } else {
            $pageDesc = '';
        }

        if (!empty($meta_keywords)) {
            $pageKeywords = $meta_keywords;
        } elseif (!empty($seoData[$pageKey]['keywords'])) {
            $pageKeywords = $seoData[$pageKey]['keywords'];
        } else {
            $pageKeywords = '';
        }

        $ogImageUrl = $favicon;
        if (!empty($product) && is_array($product)) {
            if (!empty($product['image'])) {
                $ogImageUrl = base_url($product['image']);
            } elseif (!empty($product['main_image'])) {
                $ogImageUrl = base_url($product['main_image']);
            } elseif (!empty($product['image_url'])) {
                $ogImageUrl = base_url($product['image_url']);
            }
        } elseif (!empty($currentLogoUrl)) {
            $ogImageUrl = $currentLogoUrl;
        }
    ?>
    <title><?= esc($pageTitle) ?></title>
    <link rel="icon" type="image/png" href="<?= $favicon ?>">
    <link rel="shortcut icon" href="<?= $favicon ?>">
    <link rel="apple-touch-icon" href="<?= $favicon ?>">

    <!-- Open Graph / Facebook / WhatsApp Preview -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= esc($businessShortName) ?>">
    <meta property="og:title" content="<?= esc($pageTitle) ?>">
    <meta property="og:description" content="<?= esc($pageDesc ?: ($businessShortName . ' - Exclusive Saree Collections & Bridal Wear')) ?>">
    <meta property="og:image" content="<?= esc($ogImageUrl) ?>">
    <meta property="og:image:secure_url" content="<?= esc($ogImageUrl) ?>">
    <meta property="og:url" content="<?= current_url() ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($pageTitle) ?>">
    <meta name="twitter:description" content="<?= esc($pageDesc ?: ($businessShortName . ' - Exclusive Saree Collections & Bridal Wear')) ?>">
    <meta name="twitter:image" content="<?= esc($ogImageUrl) ?>">

    <?php if(!empty($pageDesc)): ?>
        <meta name="description" content="<?= esc($pageDesc) ?>">
    <?php endif; ?>
    <?php if(!empty($pageKeywords)): ?>
        <meta name="keywords" content="<?= esc($pageKeywords) ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php
        $fontsToLoad = array_unique(array_filter([$globalFont, $headingFont, 'Cinzel', 'Playfair Display']));
        foreach ($fontsToLoad as $fName):
            if ($fName === 'inherit') continue;
            $fParam = str_replace(' ', '+', $fName);
    ?>
    <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($fParam) ?>:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap" rel="stylesheet">
    <?php endforeach; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/trust-highlights.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/product-variants.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/saree-modern.css') ?>?v=<?= time() ?>">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Global Dynamic Typography Styles -->
    <style>
        :root {
            --font-global: '<?= esc($globalFont) ?>', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-heading: <?= ($headingFont && $headingFont !== 'inherit') ? "'" . esc($headingFont) . "', Georgia, serif" : "var(--font-global)" ?>;
        }
        body, p, span, a, input, select, textarea, button, .product-title, .product-price, .nav-item, .breadcrumb, .faq-item, .footer-col, .footer-text, .search-bar input, .nav-link, .topbar-text, .announcement-bar, .collection-card-desc, .category-card-name, .hero-tagline, .hero-story {
            font-family: var(--font-global) !important;
        }
        <?php if (!empty($headingFont) && $headingFont !== 'inherit'): ?>
        h1, h2, h3, h4, h5, h6, .brand-title, .section-title, .hero-title, .category-title, .collection-card-title, .banner-title, .about-title, .section-heading {
            font-family: var(--font-heading) !important;
        }
        <?php endif; ?>

        /* CRITICAL: Explicitly Protect Font Awesome Icons from text typography */
        .fa, .fas, .far, .fal, .fad, .fab, 
        .fa-solid, .fa-regular, .fa-light, .fa-thin, .fa-duotone, .fa-brands, 
        i[class*="fa-"], span[class*="fa-"] {
            font-family: "Font Awesome 6 Free" !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
            display: inline-block !important;
        }
        .fa-brands, .fab, i.fa-brands, i.fab {
            font-family: "Font Awesome 6 Brands" !important;
        }
        .fa-solid, .fas, i.fa-solid, i.fas {
            font-weight: 900 !important;
        }
        .fa-regular, .far, i.fa-regular, i.far {
            font-weight: 400 !important;
        }

        /* Category Navigation & Icon Badge Design */
        .nav-cat-icon-badge {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #fdf5e6;
            border: 1px solid rgba(122, 21, 48, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            transition: all 0.22s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            vertical-align: middle;
        }
        .nav-cat-icon-badge .nav-cat-icon-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            margin: 0 !important;
            border-radius: inherit;
        }
        .nav-cat-icon-badge .nav-item-icon {
            font-size: 12px;
            width: auto;
            margin: 0 !important;
        }
        .nav-category-link:hover .nav-cat-icon-badge {
            border-color: #7a1530;
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(122, 21, 48, 0.22);
            transform: translateY(-1px) scale(1.08);
        }
        .nav-cat-name {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .nav-cat-caret {
            font-size: 10px;
            color: #8c7178;
            margin-left: 3px;
            transition: transform 0.25s ease, color 0.2s ease;
        }
        .nav-menu-categories > li.has-dropdown:hover .nav-cat-caret {
            transform: rotate(180deg);
            color: #7a1530;
        }

        /* Desktop Dropdown Hover Visibility */
        @media (min-width: 992px) {
            .main-nav {
                overflow: visible !important;
            }
            .nav-row-categories {
                overflow: visible !important;
                position: relative !important;
                z-index: 9999 !important;
            }
            .nav-categories-scroll-track {
                overflow: visible !important;
            }
            .nav-menu-categories {
                overflow: visible !important;
                position: relative !important;
            }
            .nav-menu-categories > li {
                position: relative !important;
            }
            .nav-menu-categories > li.has-dropdown:hover > .dropdown-menu,
            .nav-menu-categories > li.has-dropdown:focus-within > .dropdown-menu,
            .nav-item.has-dropdown:hover > .dropdown-menu {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                pointer-events: auto !important;
                transform: translateY(0) !important;
            }
            .dropdown-menu {
                top: 100% !important;
                margin-top: 0 !important;
                z-index: 100000 !important;
            }
            .dropdown-menu::before {
                content: '' !important;
                position: absolute !important;
                top: -14px !important;
                left: 0 !important;
                right: 0 !important;
                height: 14px !important;
                background: transparent !important;
            }
        }
    </style>
    
    <?php if($gaSetting && !empty($gaSetting['value'])): ?>
        <!-- Google Analytics -->
        <?= $gaSetting['value'] ?>
    <?php endif; ?>
    <script>
        window.appBaseUrl = "<?= rtrim(base_url(), '/') ?>";
        window.openCartDrawer = window.openCartDrawer || function(e) {
            if (e && e.preventDefault) e.preventDefault();
            var d = document.getElementById('cart-drawer');
            var b = document.getElementById('cart-drawer-backdrop');
            if (d) { d.classList.add('active'); d.setAttribute('aria-hidden', 'false'); }
            if (b) { b.classList.add('active'); b.setAttribute('aria-hidden', 'false'); }
            document.body.style.overflow = 'hidden';
            if (typeof fetchAndRenderCartDrawer === 'function') {
                fetchAndRenderCartDrawer();
            }
        };
        window.closeCartDrawer = window.closeCartDrawer || function() {
            var d = document.getElementById('cart-drawer');
            var b = document.getElementById('cart-drawer-backdrop');
            if (d) { d.classList.remove('active'); d.setAttribute('aria-hidden', 'true'); }
            if (b) { b.classList.remove('active'); b.setAttribute('aria-hidden', 'true'); }
            document.body.style.overflow = '';
        };
    </script>
    <style id="site-critical-preloader-css">
        html {
            background-color: #FFF4E0;
            scroll-behavior: smooth;
        }
        body.preloader-active-body {
            overflow: hidden !important;
        }
        
        /* Critical Preloader Full-Viewport Rules (Guaranteed centered, viewport-bound) */
        #sitePreloader.site-preloader {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            z-index: 2147483647 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.99) 0%, rgba(253, 248, 240, 0.98) 100%) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            margin: 0 !important;
            padding: 20px !important;
            box-sizing: border-box !important;
            transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.35s ease;
        }
        #sitePreloader.site-preloader.preloader-hidden {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            display: none !important;
        }
        #sitePreloader .preloader-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 34px 44px;
            background: rgba(255, 255, 255, 0.96);
            border: 1.5px solid rgba(212, 175, 55, 0.5);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(122, 21, 48, 0.16), 0 4px 14px rgba(0, 0, 0, 0.06);
            max-width: 380px;
            width: 100%;
            margin: auto !important;
            transform: scale(1);
            box-sizing: border-box !important;
        }
        #sitePreloader .preloader-logo-wrap {
            position: relative;
            width: 96px;
            height: 96px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }
        #sitePreloader .preloader-halo-ring {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.16);
            box-shadow: 0 0 26px rgba(212, 175, 55, 0.35);
            animation: preloaderHalo 2s ease-in-out infinite alternate;
        }
        #sitePreloader .preloader-spinner-orbit {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 3.5px solid transparent;
            border-top-color: #7a1530;
            border-right-color: #d4af37;
            animation: preloaderSpin 1.05s cubic-bezier(0.5, 0.1, 0.5, 0.9) infinite;
        }
        #sitePreloader .preloader-brand-img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            position: relative;
            z-index: 2;
            animation: preloaderPulse 1.8s ease-in-out infinite;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.15));
        }
        #sitePreloader .preloader-brand-title {
            font-family: 'Cinzel', 'Playfair Display', Georgia, serif;
            font-size: 22px;
            font-weight: 800;
            color: #7a1530;
            letter-spacing: 2.2px;
            text-transform: uppercase;
            margin-bottom: 5px;
            text-shadow: 0 1px 3px rgba(122, 21, 48, 0.12);
        }
        #sitePreloader .preloader-brand-sub {
            font-size: 11.5px;
            letter-spacing: 2.5px;
            font-weight: 700;
            color: #d4af37;
            text-transform: uppercase;
            margin-bottom: 22px;
        }
        #sitePreloader .preloader-meter {
            width: 150px;
            height: 4.5px;
            background: #f4e8d8;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        #sitePreloader .preloader-meter-bar {
            position: absolute;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #d4af37 0%, #7a1530 50%, #d4af37 100%);
            border-radius: 4px;
            animation: preloaderBar 1.25s ease-in-out infinite;
        }
        @keyframes preloaderSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes preloaderPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }
        @keyframes preloaderHalo {
            0% { transform: scale(0.95); opacity: 0.45; }
            100% { transform: scale(1.18); opacity: 1; }
        }
        @keyframes preloaderBar {
            0% { left: -100%; width: 35%; }
            50% { left: 30%; width: 70%; }
            100% { left: 100%; width: 35%; }
        }
    </style>
    <noscript><style>#sitePreloader { display: none !important; } body { opacity: 1 !important; transform: none !important; animation: none !important; }</style></noscript>
</head>
<body>

    <!-- ==========================================================================
         STOREFRONT LUXURY BRAND PRELOADER
         ========================================================================== -->
    <div id="sitePreloader" class="site-preloader">
        <div class="preloader-card">
            <div class="preloader-logo-wrap">
                <div class="preloader-halo-ring"></div>
                <div class="preloader-spinner-orbit"></div>
                <img src="<?= ($logoSetting && !empty($logoSetting['value'])) ? base_url($logoSetting['value']) : base_url('assets/images/logo.png') ?>" alt="<?= esc($headerBizName) ?>" class="preloader-brand-img" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
            </div>
            <div class="preloader-brand-title"><?= esc($headerBizName) ?></div>
            <div class="preloader-brand-sub">Pure Silk &amp; Handloom Sarees</div>
            <div class="preloader-meter">
                <div class="preloader-meter-bar"></div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         STOREFRONT GLOBAL PRELOADER CONTROLLER (Auto Dismiss)
         ========================================================================== -->
    <script>
        (function() {
            var preloader = document.getElementById('sitePreloader');
            if (!preloader) return;

            var startTime = Date.now();
            var minDisplayTime = 600; // Fast 600ms branded experience
            var isDismissed = false;

            document.body.classList.add('preloader-active-body');

            function hidePreloader() {
                if (isDismissed) return;
                var elapsed = Date.now() - startTime;
                var remaining = Math.max(0, minDisplayTime - elapsed);

                setTimeout(function() {
                    if (isDismissed) return;
                    isDismissed = true;
                    preloader.classList.add('preloader-hidden');
                    document.body.classList.remove('preloader-active-body');
                    setTimeout(function() {
                        if (preloader) {
                            preloader.style.setProperty('display', 'none', 'important');
                        }
                    }, 400);
                }, remaining);
            }

            if (document.readyState === 'complete') {
                hidePreloader();
            } else {
                window.addEventListener('load', hidePreloader);
            }

            // Failsafe timeout fallback (max 1.5s) so page is never blocked
            setTimeout(hidePreloader, 1500);

            // Re-hide on browser back/forward cache restore
            window.addEventListener('pageshow', function(e) {
                if (e.persisted) {
                    hidePreloader();
                }
            });
        })();
    </script>

    <?php
        $headerPhoneSetting = $settingsModel->where('key', 'phone_no')->first();
        if (!$headerPhoneSetting || empty($headerPhoneSetting['value'])) {
            $headerPhoneSetting = $settingsModel->where('key', 'about_store_phone')->first();
        }
        $headerWhatsappSetting = $settingsModel->where('key', 'whatsapp_no')->first();
        if (!$headerWhatsappSetting || empty($headerWhatsappSetting['value'])) {
            $headerWhatsappSetting = $settingsModel->where('key', 'about_store_whatsapp')->first();
        }
        $headerPhone = ($headerPhoneSetting && !empty($headerPhoneSetting['value'])) ? $headerPhoneSetting['value'] : '';
        $headerWhatsapp = ($headerWhatsappSetting && !empty($headerWhatsappSetting['value'])) ? $headerWhatsappSetting['value'] : ($headerPhone ?: '');
        $currentFullUrl = current_url();
    ?>
    <header class="main-header">
        <div class="header-top">
            <div class="header-left">
                <button type="button" class="mobile-nav-toggle-btn" id="mobileNavToggleBtn" aria-label="Open Navigation Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div class="logo-container">
                <a href="<?= base_url('/') ?>" class="logo-brand-wrap">
                    <img src="<?= ($logoSetting && !empty($logoSetting['value'])) ? base_url($logoSetting['value']) : base_url('assets/images/logo.png') ?>" alt="<?= esc($headerBizName) ?>" class="logo-container-img" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
                    <span class="logo-container-link"><?= esc($headerBizName) ?></span>
                </a>
            </div>

            <div class="header-actions">
                <?php if(!empty($headerWhatsapp) || !empty($headerPhone)): 
                    $waTarget = !empty($headerWhatsapp) ? preg_replace('/[^0-9]/', '', $headerWhatsapp) : preg_replace('/[^0-9]/', '', $headerPhone);
                    if (strlen($waTarget) === 10) {
                        $waTarget = '91' . $waTarget;
                    } elseif (strlen($waTarget) === 11 && strpos($waTarget, '0') === 0) {
                        $waTarget = '91' . substr($waTarget, 1);
                    }
                ?>
                <a href="https://wa.me/<?= esc($waTarget) ?>?text=<?= urlencode('Hello ' . $headerBizName . ', I have an inquiry about sarees.') ?>" target="_blank" class="header-contact-btn" title="Contact Us on WhatsApp">
                    <i class="fa-brands fa-whatsapp header-contact-icon"></i>
                    <div class="header-contact-text">
                        <span class="header-contact-label">Help & Support</span>
                        <span class="header-contact-val"><?= esc($headerPhone ?: ('+' . $waTarget)) ?></span>
                    </div>
                </a>
                <?php endif; ?>

                <div class="currency-selector" id="countryCurrencyToggle" role="button" tabindex="0" title="Select Country & Currency" style="cursor: pointer;">
                    <img src="https://flagcdn.com/w40/in.png" alt="India" class="india-flag" id="headerSelectedFlag" style="width: 20px; height: 14px; object-fit: cover; border-radius: 2px;">
                    <span class="currency-text" id="headerSelectedCurrency">INR ₹</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <a href="#" id="auth-icon-toggle" class="icon-link" aria-label="Account" title="Login with Google">
                    <!-- User Icon -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </a>
                <a href="#" id="search-icon-toggle" class="icon-link" aria-label="Search" title="Search">
                    <!-- Search Icon -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </a>
                <?php
                    $headerCartCount = 0;
                    $sessCart = session()->get('cart') ?? [];
                    foreach ($sessCart as $sc) {
                        $headerCartCount += (int)($sc['quantity'] ?? 1);
                    }
                ?>
                <a href="<?= base_url('cart') ?>" id="header-cart-toggle" class="icon-link" aria-label="Shopping Cart" title="Shopping Cart" style="position: relative;" onclick="openCartDrawer(event);">
                    <!-- Cart Icon -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span class="header-cart-badge" id="header-cart-badge" style="<?= $headerCartCount > 0 ? '' : 'display: none;' ?>"><?= $headerCartCount ?></span>
                </a>

                <div class="search-dropdown" id="search-dropdown">
                    <form action="<?= base_url('search') ?>" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Search for sarees, cottons..." required>
                        <button type="submit" aria-label="Search Submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>

                <!-- Auth Dropdown Modal -->
                <div class="auth-dropdown" id="auth-dropdown">
                    <div class="auth-header-top">
                        <h4><?= session()->get('customer_logged_in') ? 'My Customer Account' : 'Sign in or create account' ?></h4>
                        <button class="auth-close" id="auth-close-btn">&times;</button>
                    </div>
                    <div class="auth-body">
                        <?php if(session()->get('customer_logged_in')): ?>
                            <div style="background: rgba(255,255,255,0.1); padding: 12px 14px; border-radius: 6px; margin-bottom: 10px;">
                                <div style="font-weight: 700; font-size: 14px; color: #fef08a;"><?= esc(session()->get('customer_name')) ?></div>
                                <div style="font-size: 12px; color: #e2e8f0;"><?= esc(session()->get('customer_email')) ?></div>
                            </div>
                            <a href="<?= base_url('customer/dashboard') ?>" class="btn-shop-pay" style="background-color: #c59b27; color: #1e1e1e; font-weight: 800; text-align: center; margin-bottom: 8px;">
                                <i class="fa-solid fa-gauge-high"></i> Customer Dashboard &amp; Orders
                            </a>
                            <a href="<?= base_url('cart') ?>" class="btn-half" style="width: 100%; box-sizing: border-box; text-align: center; margin-bottom: 8px;">
                                <i class="fa-solid fa-bag-shopping"></i> View Active Cart
                            </a>
                            <a href="<?= base_url('customer/logout') ?>" class="btn-half" style="width: 100%; box-sizing: border-box; text-align: center; background: rgba(239, 68, 68, 0.2); color: #fca5a5;">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('login/google?action=redirect') ?>" class="btn-google">
                                <i class="fa-brands fa-google" style="color: #ea4335; font-size: 16px; margin-right: 6px;"></i> Continue with Google
                            </a>
                            
                            <div class="auth-divider"><span>OR</span></div>
                            
                            <form action="<?= base_url('login/process') ?>" method="POST" class="auth-email-form">
                                <div class="auth-input-group">
                                    <input type="email" name="email" placeholder="Enter your email" required>
                                    <button type="submit"><i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                                <label class="auth-checkbox">
                                    <input type="checkbox" checked> Email me with offers
                                </label>
                            </form>
                            
                            <div class="auth-footer-btns">
                                <a href="<?= base_url('login/google') ?>" class="btn-half"><i class="fa-solid fa-box"></i> Orders</a>
                                <a href="<?= base_url('login/google') ?>" class="btn-half"><i class="fa-regular fa-user"></i> Sign In</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-nav-backdrop" id="mobileNavBackdrop"></div>

        <nav class="main-nav" id="storefrontMainNav">
            <div class="mobile-nav-drawer-header">
                <div class="mobile-nav-brand-wrap">
                    <div class="mobile-nav-crown-badge"><i class="fa-solid fa-crown"></i></div>
                    <div>
                        <div class="mobile-nav-drawer-title"><?= esc($headerBizName) ?></div>
                        <div class="mobile-nav-drawer-sub">Pure Silk &amp; Handlooms</div>
                    </div>
                </div>
                <button type="button" class="mobile-nav-close-btn" id="mobileNavCloseBtn" aria-label="Close Navigation Menu">&times;</button>
            </div>
            <?php
                $isHomeActive = ($currentFullUrl == base_url('/') || $currentFullUrl == base_url('index.php') || $currentFullUrl == base_url('home'));
                $isNewArrivalsActive = (strpos($currentFullUrl, 'collections/new-arrivals') !== false);
                $isCollectionsActive = ($currentFullUrl == base_url('collections') || $currentFullUrl == base_url('collections/index.php') || $currentFullUrl == base_url('collections/'));
                $isBestSellerActive = (strpos($currentFullUrl, 'collections/best-sellers') !== false || strpos($currentFullUrl, 'collections/best-seller') !== false);
                $isContactUsActive = (strpos($currentFullUrl, 'contact-us') !== false);

                // Fetch header categories from settings
                $categoryModel = new \App\Models\CategoryModel();
                $settingsModel = new \App\Models\SettingsModel();
                $headerCatSetting = $settingsModel->where('key', 'header_categories')->first();
                
                $topCategories = [];
                if ($headerCatSetting && !empty($headerCatSetting['value'])) {
                    $selectedCatData = json_decode($headerCatSetting['value'], true);
                    if (is_array($selectedCatData) && count($selectedCatData) > 0) {
                        foreach ($selectedCatData as $catData) {
                            $name = is_array($catData) ? ($catData['name'] ?? '') : $catData;
                            $customImg = is_array($catData) ? ($catData['image'] ?? '') : '';
                            $name = trim($name);
                            
                            if (!empty($name)) {
                                $dbCat = $categoryModel->where('LOWER(TRIM(name))', strtolower($name))->first();
                                if (!$dbCat) {
                                    $dbCat = $categoryModel->like('name', $name, 'both')->first();
                                }
                                
                                if ($dbCat) {
                                    $dbCat['name'] = $name;
                                    $dbCat['header_icon'] = !empty($customImg) ? $customImg : (!empty($dbCat['image']) ? $dbCat['image'] : '');
                                    $topCategories[] = $dbCat;
                                } else {
                                    $topCategories[] = [
                                        'id' => null,
                                        'name' => ucfirst($name),
                                        'slug' => url_title($name, '-', true),
                                        'header_icon' => $customImg
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    $topCategories = $categoryModel->findAll();
                }

                $subcategoryModel = new \App\Models\SubcategoryModel();
            ?>

            <!-- ROW 1: Default Navigation Links -->
            <div class="nav-row-primary">
                <ul class="nav-menu-default">
                    <li class="nav-item <?= $isHomeActive ? 'active' : '' ?>">
                        <a href="<?= base_url('/') ?>" class="nav-main-link <?= $isHomeActive ? 'active' : '' ?>">
                            <i class="fa-solid fa-house nav-item-icon"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item <?= $isNewArrivalsActive ? 'active' : '' ?>">
                        <a href="<?= base_url('collections/new-arrivals') ?>" id="nav-cat-new-arrivals" class="nav-main-link <?= $isNewArrivalsActive ? 'active' : '' ?>">
                            <i class="fa-solid fa-sparkles nav-item-icon" style="color: #eab308;"></i>
                            <span>New Arrivals</span>
                            <span class="nav-pill-badge new">NEW</span>
                        </a>
                    </li>
                    <li class="nav-item <?= $isCollectionsActive ? 'active' : '' ?>">
                        <a href="<?= base_url('collections') ?>" id="nav-cat-all-collections" class="nav-main-link <?= $isCollectionsActive ? 'active' : '' ?>">
                            <i class="fa-solid fa-border-all nav-item-icon"></i>
                            <span>All collections</span>
                        </a>
                    </li>
                    <li class="nav-item <?= $isBestSellerActive ? 'active' : '' ?>">
                        <a href="<?= base_url('collections/best-sellers') ?>" id="nav-cat-best-seller" class="nav-main-link <?= $isBestSellerActive ? 'active' : '' ?>">
                            <i class="fa-solid fa-fire nav-item-icon" style="color: #ef4444;"></i>
                            <span>Best seller</span>
                            <span class="nav-pill-badge hot">HOT</span>
                        </a>
                    </li>
                    <li class="nav-item <?= $isContactUsActive ? 'active' : '' ?>">
                        <a href="<?= base_url('contact-us') ?>" id="nav-contact-us" class="nav-main-link <?= $isContactUsActive ? 'active' : '' ?>">
                            <i class="fa-solid fa-envelope-open-text nav-item-icon" style="color: #7a1530;"></i>
                            <span>Contact Us</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ROW 2: Dynamically Created Categories Strip -->
            <?php if(!empty($topCategories)): ?>
            <div class="nav-row-categories">
                <div class="nav-mobile-section-heading">
                    <i class="fa-solid fa-layer-group" style="color: #7a1530; margin-right: 4px;"></i> Shop Categories
                </div>
                <div class="nav-categories-scroll-track">
                    <ul class="nav-menu-categories">
                        <?php foreach($topCategories as $topCat): 
                            $subCategories = [];
                            if (!empty($topCat['id'])) {
                                $subCategories = $subcategoryModel->where('parent_category_id', $topCat['id'])->findAll();
                            }
                            $hasSubs = count($subCategories) > 0;
                            
                            $isCatActive = (strpos($currentFullUrl, 'category/' . $topCat['slug']) !== false);
                            if (!$isCatActive && $hasSubs) {
                                foreach ($subCategories as $subCheck) {
                                    if (!empty($subCheck['slug']) && strpos($currentFullUrl, 'category/' . $subCheck['slug']) !== false) {
                                        $isCatActive = true;
                                        break;
                                    }
                                }
                            }

                            // Smart icon fallback if no custom image uploaded
                            $catLower = strtolower($topCat['name'] ?? '');
                            if (strpos($catLower, 'cotton') !== false) {
                                $catIconClass = 'fa-solid fa-seedling';
                                $catIconColor = '#15803d';
                            } elseif (strpos($catLower, 'silk') !== false) {
                                $catIconClass = 'fa-solid fa-gem';
                                $catIconColor = '#b45309';
                            } elseif (strpos($catLower, 'dhoti') !== false || strpos($catLower, 'men') !== false) {
                                $catIconClass = 'fa-solid fa-person';
                                $catIconColor = '#0284c7';
                            } elseif (strpos($catLower, 'kurti') !== false || strpos($catLower, 'women') !== false || strpos($catLower, 'dress') !== false) {
                                $catIconClass = 'fa-solid fa-person-dress';
                                $catIconColor = '#db2777';
                            } else {
                                $catIconClass = 'fa-solid fa-layer-group';
                                $catIconColor = '#7a1530';
                            }
                        ?>
                        <li class="nav-item <?= $hasSubs ? 'has-dropdown' : '' ?> <?= $isCatActive ? 'active' : '' ?>">
                            <div class="nav-link-wrapper">
                                <a href="<?= base_url('category/' . $topCat['slug']) ?>" id="nav-cat-<?= $topCat['slug'] ?>" class="nav-main-link nav-category-link <?= $isCatActive ? 'active' : '' ?>">
                                    <span class="nav-cat-icon-badge">
                                        <?php if(!empty($topCat['header_icon'])): ?>
                                            <img src="<?= base_url(ltrim($topCat['header_icon'], '/')) ?>" alt="<?= esc($topCat['name']) ?>" class="nav-cat-icon-img">
                                        <?php else: ?>
                                            <i class="<?= $catIconClass ?> nav-item-icon" style="color: <?= $catIconColor ?>;"></i>
                                        <?php endif; ?>
                                    </span>
                                    <span class="nav-cat-name"><?= esc($topCat['name']) ?></span>
                                    <?php if($hasSubs): ?>
                                        <i class="fa-solid fa-chevron-down nav-cat-caret"></i>
                                    <?php endif; ?>
                                </a>
                                <?php if($hasSubs): ?>
                                    <button type="button" class="mobile-submenu-toggle" aria-label="Toggle subcategories">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php if($hasSubs): ?>
                            <div class="dropdown-menu">
                                <ul class="dropdown-subcategories-list">
                                    <?php foreach($subCategories as $subCat): 
                                        $subImg = '';
                                        if (!empty($subCat['image'])) {
                                            $subImg = (strpos($subCat['image'], 'http') === 0) ? $subCat['image'] : base_url(ltrim($subCat['image'], '/'));
                                        }
                                        if (empty($subImg)) {
                                            $subImg = base_url('assets/images/placeholder.jpg');
                                        }
                                        $isSubActive = (strpos($currentFullUrl, 'category/' . $subCat['slug']) !== false);
                                    ?>
                                    <li>
                                        <a href="<?= base_url('category/' . $subCat['slug']) ?>" id="nav-sub-<?= $subCat['slug'] ?>" class="dropdown-sub-link <?= $isSubActive ? 'active' : '' ?>">
                                            <img src="<?= $subImg ?>" alt="<?= esc($subCat['name']) ?>" class="dropdown-sub-thumb" loading="lazy">
                                            <span class="dropdown-sub-text"><?= esc($subCat['name']) ?></span>
                                            <i class="fa-solid fa-angle-right dropdown-sub-arrow"></i>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            <div class="mobile-nav-drawer-footer">
                <div class="mobile-nav-footer-quick">
                    <a href="<?= base_url('login/google') ?>" class="mobile-nav-footer-btn">
                        <i class="fa-regular fa-user"></i>
                        <span><?= session()->get('customer_logged_in') ? 'My Account' : 'Sign In' ?></span>
                    </a>
                    <a href="<?= base_url('cart') ?>" class="mobile-nav-footer-btn" onclick="closeMobileNav(); openCartDrawer(event);">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span>My Cart</span>
                    </a>
                </div>
                <?php if(!empty($headerWhatsapp) || !empty($headerPhone)): 
                    $waClean = !empty($headerWhatsapp) ? preg_replace('/[^0-9]/', '', $headerWhatsapp) : preg_replace('/[^0-9]/', '', $headerPhone);
                    if (strlen($waClean) === 10) {
                        $waClean = '91' . $waClean;
                    } elseif (strlen($waClean) === 11 && strpos($waClean, '0') === 0) {
                        $waClean = '91' . substr($waClean, 1);
                    }
                ?>
                <a href="https://wa.me/<?= esc($waClean) ?>?text=<?= urlencode('Hello ' . $headerBizName . ', I have an inquiry about saree collections.') ?>" target="_blank" class="mobile-nav-wa-btn">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>Chat with Weaver Support</span>
                </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- ==========================================================================
         TOP 25 COUNTRIES & CURRENCY SELECTOR MODAL (Top-level Overlay)
         ========================================================================== -->
    <div class="country-currency-modal" id="countryCurrencyModal">
        <div class="country-currency-card">
            <div class="country-currency-header">
                <div class="country-currency-title-wrap">
                    <i class="fa-solid fa-earth-americas" style="color: #7a1530; font-size: 19px;"></i>
                    <span class="country-currency-title">Select Country & Currency</span>
                </div>
                <button type="button" class="country-currency-close" id="countryCurrencyCloseBtn" aria-label="Close Country Selector">&times;</button>
            </div>
            <p class="country-currency-sub" style="margin-bottom: 18px;">Choose your shopping destination country. Prices and worldwide express delivery will be tailored to your location.</p>
            
            <div class="country-currency-search-wrap">
                <i class="fa-solid fa-magnifying-glass country-currency-search-icon"></i>
                <input type="text" id="countryCurrencySearchInput" placeholder="Search country name or currency code (e.g. US, EUR, Dubai)..." autocomplete="off">
                <button type="button" id="countryCurrencySearchClear" class="country-currency-search-clear" style="display:none;" aria-label="Clear Search">&times;</button>
            </div>

            <div class="country-currency-list" id="countryCurrencyList">
                <!-- Populated with Top 25 Countries -->
            </div>

            <div class="country-currency-footer">
                <span class="country-currency-footer-info">
                    <i class="fa-solid fa-shield-halved" style="color: #10b981; margin-right: 6px;"></i> Safe &amp; Secure Worldwide Delivery • 100% Authentic Handlooms
                </span>
            </div>
        </div>
    </div>

    <!-- Global Currency Switched Toast Notification -->
    <div id="currencyChangeToast" class="currency-change-toast">
        <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 16px;"></i>
        <span id="currencyChangeToastText">Currency updated</span>
    </div>
    <?php 
        $announcementSetting = $settingsModel->where('key', 'announcement_bar_text')->first();
        $announcementEnabledSetting = $settingsModel->where('key', 'announcement_bar_enabled')->first();
        $announcementBoldSetting = $settingsModel->where('key', 'announcement_bar_bold')->first();
        $announcementFontSizeSetting = $settingsModel->where('key', 'announcement_bar_font_size')->first();
        $announcementTextColorSetting = $settingsModel->where('key', 'announcement_bar_text_color')->first();
        $announcementBgColorSetting = $settingsModel->where('key', 'announcement_bar_bg_color')->first();

        $announcementEnabled = $announcementEnabledSetting ? (($announcementEnabledSetting['value'] ?? '1') === '1') : true;
        $announcementBold = $announcementBoldSetting ? (($announcementBoldSetting['value'] ?? '1') === '1') : true;
        $announcementFontSize = ($announcementFontSizeSetting && !empty($announcementFontSizeSetting['value'])) ? $announcementFontSizeSetting['value'] : '13px';
        $announcementTextColor = ($announcementTextColorSetting && !empty($announcementTextColorSetting['value'])) ? $announcementTextColorSetting['value'] : '#444444';
        $announcementBgColor = ($announcementBgColorSetting && !empty($announcementBgColorSetting['value'])) ? $announcementBgColorSetting['value'] : '#e2e2e2';

        $defaultAnnouncement = 'Free Shipping above Rs.1000/- Purchases.       Hurry Most Beautiful collections On its way!       Diwali Sale is Live       Free Shipping above Rs.1000/- Purchases.';
        $rawAnnouncement = $announcementSetting && !empty($announcementSetting['value']) ? $announcementSetting['value'] : $defaultAnnouncement;
    ?>
    <?php if($announcementEnabled && !empty($rawAnnouncement)): ?>
    <div class="announcement-bar" style="background-color: <?= esc($announcementBgColor) ?>; color: <?= esc($announcementTextColor) ?>;">
        <div class="marquee" style="font-size: <?= esc($announcementFontSize) ?>; font-weight: <?= $announcementBold ? '700' : '500' ?>; color: <?= esc($announcementTextColor) ?>;">
            <span>
                <?php 
                    $parts = preg_split('/(\s{3,}|\s*•\s*|\s*\|\s*)/', $rawAnnouncement);
                    $parts = array_filter(array_map('trim', $parts));
                    if(empty($parts)) $parts = [$rawAnnouncement];
                    $formattedTicker = [];
                    foreach($parts as $part) {
                        $formattedTicker[] = '<i class="fa-solid fa-star" style="color: #ffd700; margin-right: 6px; font-size: 11px;"></i> ' . esc($part);
                    }
                    echo implode(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', $formattedTicker) . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . implode(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', $formattedTicker);
                ?>
            </span>
        </div>
    </div>
    <?php endif; ?>

    <!-- ==========================================================================
         TOP 25 COUNTRIES & CURRENCY SELECTOR CONTROLLER SCRIPT
         ========================================================================== -->
    <style>
    .currency-change-toast {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(30px);
        background: #0f172a;
        color: #ffffff;
        padding: 12px 22px;
        border-radius: 30px;
        font-size: 13.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        z-index: 999999999;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .currency-change-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    </style>

    <script>
    (function() {
        const top25Countries = [
            { code: 'in', name: 'India', currency: 'INR', symbol: '₹' },
            { code: 'us', name: 'United States', currency: 'USD', symbol: '$' },
            { code: 'gb', name: 'United Kingdom', currency: 'GBP', symbol: '£' },
            { code: 'ae', name: 'United Arab Emirates', currency: 'AED', symbol: 'AED ' },
            { code: 'sg', name: 'Singapore', currency: 'SGD', symbol: 'S$' },
            { code: 'my', name: 'Malaysia', currency: 'MYR', symbol: 'RM ' },
            { code: 'ca', name: 'Canada', currency: 'CAD', symbol: 'C$' },
            { code: 'au', name: 'Australia', currency: 'AUD', symbol: 'A$' },
            { code: 'de', name: 'Germany', currency: 'EUR', symbol: '€' },
            { code: 'fr', name: 'France', currency: 'EUR', symbol: '€' },
            { code: 'sa', name: 'Saudi Arabia', currency: 'SAR', symbol: 'SAR ' },
            { code: 'qa', name: 'Qatar', currency: 'QAR', symbol: 'QAR ' },
            { code: 'kw', name: 'Kuwait', currency: 'KWD', symbol: 'KD ' },
            { code: 'om', name: 'Oman', currency: 'OMR', symbol: 'OMR ' },
            { code: 'bh', name: 'Bahrain', currency: 'BHD', symbol: 'BD ' },
            { code: 'lk', name: 'Sri Lanka', currency: 'LKR', symbol: 'Rs ' },
            { code: 'za', name: 'South Africa', currency: 'ZAR', symbol: 'R ' },
            { code: 'ch', name: 'Switzerland', currency: 'CHF', symbol: 'CHF ' },
            { code: 'nz', name: 'New Zealand', currency: 'NZD', symbol: 'NZ$' },
            { code: 'nl', name: 'Netherlands', currency: 'EUR', symbol: '€' },
            { code: 'it', name: 'Italy', currency: 'EUR', symbol: '€' },
            { code: 'ie', name: 'Ireland', currency: 'EUR', symbol: '€' },
            { code: 'se', name: 'Sweden', currency: 'SEK', symbol: 'kr ' },
            { code: 'jp', name: 'Japan', currency: 'JPY', symbol: '¥' },
            { code: 'mu', name: 'Mauritius', currency: 'MUR', symbol: 'Rs ' }
        ];

        // Currency Exchange Rates relative to base INR
        const CURRENCY_RATES = {
            'INR': { rate: 1.0, symbol: '₹', code: 'INR', name: 'Indian Rupee' },
            'USD': { rate: 0.012, symbol: '$', code: 'USD', name: 'US Dollar' },
            'EUR': { rate: 0.011, symbol: '€', code: 'EUR', name: 'Euro' },
            'GBP': { rate: 0.0095, symbol: '£', code: 'GBP', name: 'British Pound' },
            'AED': { rate: 0.044, symbol: 'AED ', code: 'AED', name: 'UAE Dirham' },
            'SGD': { rate: 0.016, symbol: 'S$', code: 'SGD', name: 'Singapore Dollar' },
            'MYR': { rate: 0.056, symbol: 'RM ', code: 'MYR', name: 'Malaysian Ringgit' },
            'CAD': { rate: 0.0163, symbol: 'C$', code: 'CAD', name: 'Canadian Dollar' },
            'AUD': { rate: 0.0182, symbol: 'A$', code: 'AUD', name: 'Australian Dollar' },
            'SAR': { rate: 0.045, symbol: 'SAR ', code: 'SAR', name: 'Saudi Riyal' },
            'QAR': { rate: 0.044, symbol: 'QAR ', code: 'QAR', name: 'Qatari Riyal' },
            'KWD': { rate: 0.0037, symbol: 'KD ', code: 'KWD', name: 'Kuwaiti Dinar' },
            'OMR': { rate: 0.0046, symbol: 'OMR ', code: 'OMR', name: 'Omani Rial' },
            'BHD': { rate: 0.0045, symbol: 'BD ', code: 'BHD', name: 'Bahraini Dinar' },
            'LKR': { rate: 3.55, symbol: 'Rs ', code: 'LKR', name: 'Sri Lankan Rupee' },
            'ZAR': { rate: 0.22, symbol: 'R ', code: 'ZAR', name: 'South African Rand' },
            'CHF': { rate: 0.0108, symbol: 'CHF ', code: 'CHF', name: 'Swiss Franc' },
            'NZD': { rate: 0.020, symbol: 'NZ$', code: 'NZD', name: 'New Zealand Dollar' },
            'SEK': { rate: 0.13, symbol: 'kr ', code: 'SEK', name: 'Swedish Krona' },
            'JPY': { rate: 1.82, symbol: '¥', code: 'JPY', name: 'Japanese Yen' },
            'MUR': { rate: 0.56, symbol: 'Rs ', code: 'MUR', name: 'Mauritian Rupee' }
        };
        window.CURRENCY_RATES = CURRENCY_RATES;

        function formatCompactCurrencyPrice(inrAmount, targetCurrencyCode) {
            if (targetCurrencyCode === 'INR') {
                if (inrAmount === 1000) return '₹1K';
                if (inrAmount === 1500) return '₹1.5K';
                return '₹' + inrAmount;
            }
            const curr = CURRENCY_RATES[targetCurrencyCode] || CURRENCY_RATES['INR'];
            const converted = inrAmount * curr.rate;
            let formatted;
            if (converted >= 100) {
                formatted = Math.round(converted).toLocaleString('en-US');
            } else if (converted % 1 === 0) {
                formatted = converted.toString();
            } else {
                formatted = converted.toFixed(2).replace(/\.00$/, '');
            }
            return curr.symbol.trim() + formatted;
        }
        window.formatCompactCurrencyPrice = formatCompactCurrencyPrice;

        function parseCleanPriceNumber(val) {
            if (val === null || val === undefined) return NaN;
            if (typeof val === 'number') return isNaN(val) ? NaN : val;
            let s = String(val).trim();
            // Remove currency words, symbols, and prefix abbreviations
            s = s.replace(/^(Rs\.|INR|₹|\$|€|£|AED|RM|C\$|A\$|S\$|SAR|QAR|KD|OMR|BD|R|CHF|NZ\$|kr|¥|SAVE\s*(RS\.|INR|₹)?)/i, '').trim();
            s = s.replace(/^[^\d.]*/, '');
            s = s.replace(/,/g, '');
            return parseFloat(s);
        }

        function formatCurrencyPrice(inrAmount, targetCurrencyCode) {
            if (inrAmount === null || inrAmount === undefined) return '';
            const num = parseCleanPriceNumber(inrAmount);
            if (isNaN(num)) return '';
            if (!targetCurrencyCode) {
                try {
                    const saved = getSavedCountry();
                    targetCurrencyCode = saved ? saved.currency : 'INR';
                } catch(e) {
                    targetCurrencyCode = 'INR';
                }
            }
            const curr = CURRENCY_RATES[targetCurrencyCode] || CURRENCY_RATES['INR'];
            const converted = num * curr.rate;
            const decimals = (curr.code === 'JPY') ? 0 : 2;
            const formatted = converted.toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
            return curr.symbol + formatted;
        }

        function getSavedCountry() {
            try {
                const saved = localStorage.getItem('sri_user_country_currency');
                if (saved) return JSON.parse(saved);
            } catch(e) {}
            return top25Countries[0]; // Default India
        }

        let toastTimer = null;
        function showCurrencyToast(country) {
            const toast = document.getElementById('currencyChangeToast');
            const toastText = document.getElementById('currencyChangeToastText');
            if (!toast || !toastText) return;
            const sym = country.symbol ? country.symbol.trim() : '';
            toastText.innerHTML = 'Prices updated to <strong>' + country.currency + ' (' + sym + ')</strong> for ' + country.name;
            toast.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function setSavedCountry(country, isUserAction) {
            try {
                localStorage.setItem('sri_user_country_currency', JSON.stringify(country));
                document.cookie = "sri_currency=" + encodeURIComponent(country.currency) + ";path=/;max-age=31536000";
            } catch(e) {}
            updateHeaderDisplay(country);
            applyCurrencyToPage(country);
            if (isUserAction) {
                showCurrencyToast(country);
            }
        }

        function updateHeaderDisplay(country) {
            const flagEl = document.getElementById('headerSelectedFlag');
            const currEl = document.getElementById('headerSelectedCurrency');
            if (flagEl) {
                flagEl.src = 'https://flagcdn.com/w40/' + country.code + '.png';
                flagEl.alt = country.name;
            }
            if (currEl) {
                currEl.textContent = country.currency + ' ' + country.symbol;
            }
        }

        function applyCurrencyToPage(country) {
            if (!country) country = getSavedCountry();
            const currCode = country.currency || 'INR';

            // 1. Convert all Product Card sale prices
            document.querySelectorAll('.card-sale-price').forEach(function(el) {
                let base = el.getAttribute('data-base-price');
                if (!base) {
                    const card = el.closest('.product-card');
                    if (card && card.getAttribute('data-price')) {
                        base = card.getAttribute('data-price');
                    } else {
                        base = parseCleanPriceNumber(el.innerText);
                    }
                    if (base && !isNaN(base)) el.setAttribute('data-base-price', base);
                }
                const num = parseCleanPriceNumber(base);
                if (!isNaN(num) && num > 0) {
                    el.textContent = formatCurrencyPrice(num, currCode);
                }
            });

            // 2. Convert all Product Card original strikethrough prices
            document.querySelectorAll('.card-original-price').forEach(function(el) {
                const card = el.closest('.product-card');
                let base = el.getAttribute('data-base-price');
                if (!base) {
                    if (card && card.getAttribute('data-original-price')) {
                        base = card.getAttribute('data-original-price');
                    } else {
                        base = parseCleanPriceNumber(el.innerText);
                    }
                    if (base && !isNaN(base)) el.setAttribute('data-base-price', base);
                }
                const num = parseCleanPriceNumber(base);
                let saleBase = card ? (card.getAttribute('data-price') || (card.querySelector('.card-sale-price') ? card.querySelector('.card-sale-price').getAttribute('data-base-price') : null)) : null;
                const saleNum = parseCleanPriceNumber(saleBase);
                if (!isNaN(num) && num > 0 && (isNaN(saleNum) || num > saleNum)) {
                    el.textContent = formatCurrencyPrice(num, currCode);
                    el.style.display = 'inline';
                } else {
                    el.style.display = 'none';
                }
            });

            // 3. Convert Product Detail Page prices
            const detailPriceEl = document.getElementById('detail-product-price');
            if (detailPriceEl) {
                let base = detailPriceEl.getAttribute('data-base-price');
                if (!base) {
                    base = parseCleanPriceNumber(detailPriceEl.innerText);
                    if (base && !isNaN(base)) detailPriceEl.setAttribute('data-base-price', base);
                }
                const num = parseCleanPriceNumber(base);
                if (!isNaN(num) && num > 0) {
                    detailPriceEl.textContent = formatCurrencyPrice(num, currCode);
                }
            }

            const detailOrigPriceEl = document.getElementById('detail-product-original-price');
            if (detailOrigPriceEl) {
                let base = detailOrigPriceEl.getAttribute('data-base-price');
                if (!base) {
                    base = parseCleanPriceNumber(detailOrigPriceEl.innerText);
                    if (base && !isNaN(base)) detailOrigPriceEl.setAttribute('data-base-price', base);
                }
                const num = parseCleanPriceNumber(base);
                let detailSaleNum = detailPriceEl ? parseCleanPriceNumber(detailPriceEl.getAttribute('data-base-price')) : NaN;
                if (!isNaN(num) && num > 0 && (isNaN(detailSaleNum) || num > detailSaleNum)) {
                    detailOrigPriceEl.textContent = formatCurrencyPrice(num, currCode);
                    detailOrigPriceEl.style.display = 'inline';
                } else {
                    detailOrigPriceEl.style.display = 'none';
                }
            }

            // 4. Convert Save Badges on Product Cards
            document.querySelectorAll('.product-card span').forEach(function(span) {
                const txt = span.innerText || '';
                if (txt.includes('SAVE RS.') || txt.includes('SAVE ₹') || span.getAttribute('data-base-save')) {
                    let baseSave = span.getAttribute('data-base-save');
                    if (!baseSave) {
                        const card = span.closest('.product-card');
                        let origP = card ? parseCleanPriceNumber(card.getAttribute('data-original-price')) : NaN;
                        let saleP = card ? parseCleanPriceNumber(card.getAttribute('data-price')) : NaN;
                        if (!isNaN(origP) && !isNaN(saleP) && origP > saleP) {
                            baseSave = origP - saleP;
                        } else {
                            baseSave = parseCleanPriceNumber(txt);
                        }
                        if (baseSave) span.setAttribute('data-base-save', baseSave);
                    }
                    const numSave = parseCleanPriceNumber(baseSave);
                    if (!isNaN(numSave) && numSave > 0) {
                        span.textContent = 'SAVE ' + formatCurrencyPrice(numSave, currCode);
                        span.style.display = '';
                    } else {
                        span.style.display = 'none';
                    }
                }
            });

            // 5. Convert Cart Page items & subtotal prices if present
            document.querySelectorAll('.cart-unit-price, .cart-unit-orig, .cart-item-subtotal-val').forEach(function(el) {
                let base = el.getAttribute('data-base-price');
                if (!base) {
                    base = el.innerText.replace(/[^0-9.]/g, '');
                    if (base) el.setAttribute('data-base-price', base);
                }
                if (base && !isNaN(parseFloat(base))) {
                    el.textContent = formatCurrencyPrice(base, currCode);
                }
            });

            // 5b. Convert Cart Summary Sidebar totals
            document.querySelectorAll('.cart-summary-subtotal').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) el.textContent = formatCurrencyPrice(base, currCode);
            });
            document.querySelectorAll('.cart-summary-shipping').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base) && base > 0) el.textContent = formatCurrencyPrice(base, currCode);
            });
            document.querySelectorAll('.cart-summary-total').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = formatCurrencyPrice(base, currCode) + ' <span style="font-size: 13px; color: #64748b; font-weight: 700;">(₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.cart-summary-total-label').forEach(function(el) {
                el.textContent = currCode !== 'INR' ? 'Total (' + currCode + ')' : 'Total';
            });

            // 6. Convert Cart Drawer subtotal if present
            const drawerSubtotal = document.getElementById('cart-drawer-subtotal-val');
            if (drawerSubtotal) {
                let base = drawerSubtotal.getAttribute('data-base-price');
                if (!base) {
                    base = drawerSubtotal.innerText.replace(/[^0-9.]/g, '');
                    if (base) drawerSubtotal.setAttribute('data-base-price', base);
                }
                if (base && !isNaN(parseFloat(base))) {
                    drawerSubtotal.textContent = formatCurrencyPrice(base, currCode);
                }
            }

            // 7. Update Sidebar Filter price prefix symbol if present
            document.querySelectorAll('.price-input-container span').forEach(function(span) {
                if (span.textContent.trim() === '₹' || span.classList.contains('curr-sym')) {
                    span.classList.add('curr-sym');
                    span.textContent = country.symbol ? country.symbol.trim() : '₹';
                }
            });

            // 8. Update Sidebar Filter Quick Price Chips (All, Under, Range, Above)
            const pUnder = document.getElementById('quick-price-under');
            const pBetween = document.getElementById('quick-price-between');
            const pAbove = document.getElementById('quick-price-above');
            if (pUnder && pBetween && pAbove) {
                if (currCode === 'INR') {
                    pUnder.textContent = 'Under ₹1K';
                    pBetween.textContent = '₹1K - ₹1.5K';
                    pAbove.textContent = 'Above ₹1.5K';
                } else {
                    const str1000 = formatCompactCurrencyPrice(1000, currCode);
                    const str1500 = formatCompactCurrencyPrice(1500, currCode);
                    pUnder.textContent = 'Under ' + str1000;
                    pBetween.textContent = str1000 + ' - ' + str1500;
                    pAbove.textContent = 'Above ' + str1500;
                }
            }

            // 9. Update Quick Currency Pills Active state
            document.querySelectorAll('.quick-curr-pill').forEach(function(btn) {
                const bCurr = btn.getAttribute('data-curr');
                const bCode = btn.getAttribute('data-code');
                if (bCurr === country.currency || bCode === country.code) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // 10. Convert Checkout Page summary if present
            if (typeof window.recalculateCheckoutSummary === 'function') {
                window.recalculateCheckoutSummary();
            }

            // 11. Convert Order Confirmation Page totals & items if present
            document.querySelectorAll('.order-unit-price').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) el.textContent = formatCurrencyPrice(base, currCode);
            });
            document.querySelectorAll('.order-item-total').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = formatCurrencyPrice(base, currCode) + ' <span style="font-size: 11px; color: #64748b; font-weight: normal;">(₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.order-summary-subtotal').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = '<span style="font-weight: 700;">' + formatCurrencyPrice(base, currCode) + '</span> <span style="font-size: 11.5px; color: #64748b;">(₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.order-summary-discount').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = '-' + formatCurrencyPrice(base, currCode) + ' <span style="font-size: 11px; color: #16a34a;">(-₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '-₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.order-summary-shipping').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (base === 0) {
                        el.innerHTML = '<span style="color: #16a34a;">FREE</span>';
                    } else if (currCode !== 'INR') {
                        el.innerHTML = formatCurrencyPrice(base, currCode) + ' <span style="font-size: 11.5px; color: #64748b;">(₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.order-summary-tip').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = formatCurrencyPrice(base, currCode) + ' <span style="font-size: 11px; color: #64748b;">(₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.order-summary-final-total').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = formatCurrencyPrice(base, currCode) + ' <span style="font-size: 13px; color: #64748b; font-weight: 700;">(₹' + base.toFixed(2) + ' INR)</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            const finalTotalLbl = document.getElementById('order-final-total-label');
            if (finalTotalLbl) {
                finalTotalLbl.textContent = currCode !== 'INR' ? 'Final Total (' + currCode + ')' : 'Final Total';
            }

            // 12. Convert Customer Dashboard orders & cart
            document.querySelectorAll('.cust-order-item-price, .cust-rec-item-price, .cust-cart-line-total').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = formatCurrencyPrice(base, currCode) + ' <span style="font-size: 11px; color: #64748b; font-weight: normal;">(₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.cust-order-total-price').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = 'Total: ' + formatCurrencyPrice(base, currCode) + ' <span style="font-size: 12px; color: #64748b; font-weight: 700;">(₹' + base.toFixed(2) + ' INR)</span>';
                    } else {
                        el.textContent = 'Total: ₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });
            document.querySelectorAll('.cust-cart-unit-price').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) el.textContent = formatCurrencyPrice(base, currCode);
            });
            document.querySelectorAll('.cust-cart-total-price').forEach(function(el) {
                const base = parseFloat(el.getAttribute('data-base-price'));
                if (!isNaN(base)) {
                    if (currCode !== 'INR') {
                        el.innerHTML = formatCurrencyPrice(base, currCode) + ' <span style="font-size: 12px; color: #64748b; font-weight: 700;">(₹' + base.toFixed(2) + ')</span>';
                    } else {
                        el.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            });

            // Dispatch custom event for any other listeners
            try {
                window.dispatchEvent(new CustomEvent('currencyChanged', { detail: country }));
            } catch(e) {}
        }

        function renderCountryList(filterQuery) {
            const listEl = document.getElementById('countryCurrencyList');
            if (!listEl) return;
            const current = getSavedCountry();
            const q = (filterQuery || '').trim().toLowerCase();

            const filtered = top25Countries.filter(c => {
                if (!q) return true;
                const isEuroQuery = (q === 'eur' || q === 'euro' || q === 'euros');
                const isUsQuery = (q === 'us' || q === 'usa' || q === 'united states' || q === 'dollar' || q === 'usd');
                if (isEuroQuery && c.currency === 'EUR') return true;
                if (isUsQuery && (c.code === 'us' || c.currency === 'USD')) return true;

                return c.name.toLowerCase().includes(q) || 
                       c.currency.toLowerCase().includes(q) || 
                       c.code.toLowerCase().includes(q) ||
                       (c.name.toLowerCase() === 'united arab emirates' && (q.includes('dubai') || q.includes('uae')));
            });

            if (filtered.length === 0) {
                listEl.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 25px; color: #94a3b8; font-size: 13.5px;"><i class="fa-solid fa-magnifying-glass" style="margin-bottom: 8px; font-size: 20px; display: block;"></i>No matching country or currency found</div>';
                return;
            }

            listEl.innerHTML = filtered.map(c => {
                const isActive = (c.code === current.code || c.currency === current.currency && c.code === current.code);
                return `
                    <div class="country-item ${isActive ? 'active' : ''}" data-code="${c.code}" role="button" tabindex="0">
                        <div class="country-item-left">
                            <img src="https://flagcdn.com/w40/${c.code}.png" alt="${c.name}" class="country-item-flag" loading="lazy">
                            <div class="country-item-info">
                                <span class="country-item-name">${c.name}</span>
                                <span class="country-item-curr">${c.currency} (${c.symbol})</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-circle-check country-item-check"></i>
                    </div>
                `;
            }).join('');

            listEl.querySelectorAll('.country-item').forEach(el => {
                el.addEventListener('click', function() {
                    const code = this.getAttribute('data-code');
                    const country = top25Countries.find(x => x.code === code);
                    if (country) {
                        setSavedCountry(country, true);
                        renderCountryList('');
                        closeModal();
                    }
                });
            });
        }

        function openModal() {
            const modal = document.getElementById('countryCurrencyModal');
            if (modal) {
                renderCountryList('');
                modal.classList.add('active');
                const searchInp = document.getElementById('countryCurrencySearchInput');
                if (searchInp) {
                    searchInp.value = '';
                    setTimeout(() => searchInp.focus(), 80);
                }
            }
        }

        function closeModal() {
            const modal = document.getElementById('countryCurrencyModal');
            if (modal) {
                modal.classList.remove('active');
            }
        }

        // Initialize header display and live page currency conversion on load
        document.addEventListener('DOMContentLoaded', function() {
            const saved = getSavedCountry();
            updateHeaderDisplay(saved);
            applyCurrencyToPage(saved);

            const toggleBtn = document.getElementById('countryCurrencyToggle');
            const closeBtn = document.getElementById('countryCurrencyCloseBtn');
            const modal = document.getElementById('countryCurrencyModal');
            const searchInp = document.getElementById('countryCurrencySearchInput');
            const clearBtn = document.getElementById('countryCurrencySearchClear');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openModal();
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeModal();
                });
            }

            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            if (searchInp) {
                searchInp.addEventListener('input', function() {
                    const q = this.value;
                    if (clearBtn) clearBtn.style.display = q ? 'block' : 'none';
                    renderCountryList(q);
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    if (searchInp) {
                        searchInp.value = '';
                        this.style.display = 'none';
                        searchInp.focus();
                        renderCountryList('');
                    }
                });
            }


            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
                    closeModal();
                }
            });
        });

        // Globally export functions
        window.getSavedCountry = getSavedCountry;
        window.setSavedCountry = setSavedCountry;
        window.applyCurrencyToPage = applyCurrencyToPage;
        window.formatCurrencyPrice = function(amount, targetCurr) {
            if (!targetCurr) {
                const cur = getSavedCountry();
                targetCurr = cur ? cur.currency : 'INR';
            }
            return formatCurrencyPrice(amount, targetCurr);
        };
    })();
    </script>
