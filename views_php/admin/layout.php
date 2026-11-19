<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        // Anti-flicker: instantly apply persisted sidebar state
        (function() {
            try {
                if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>
    <?php 
        if (!isset($settingsModel)) {
            $settingsModel = new \App\Models\SettingsModel();
        }
        if (!isset($businessShortName)) {
            $shortNameSetting = $settingsModel->where('key', 'short_name')->first();
            $businessShortName = $shortNameSetting ? esc($shortNameSetting['value']) : 'SSPTEX';
        }
        $logoSetting = $settingsModel->where('key', 'business_logo')->first();
        $hasCustomLogo = false;
        $adminLogoUrl = base_url('assets/images/logo.png');
        if ($logoSetting && !empty($logoSetting['value']) && file_exists(FCPATH . $logoSetting['value'])) {
            $adminLogoUrl = base_url($logoSetting['value']);
            $hasCustomLogo = true;
        } elseif (file_exists(FCPATH . 'uploads/settings/selvi_tex_logo.jpg')) {
            $adminLogoUrl = base_url('uploads/settings/selvi_tex_logo.jpg');
            $hasCustomLogo = true;
        } elseif (file_exists(FCPATH . 'assets/images/logo.png')) {
            $adminLogoUrl = base_url('assets/images/logo.png');
            $hasCustomLogo = true;
        }

        $faviconSetting = $settingsModel->where('key', 'favicon')->first();
        $adminFavicon = $adminLogoUrl;
        if ($faviconSetting && !empty($faviconSetting['value']) && file_exists(FCPATH . $faviconSetting['value'])) {
            $adminFavicon = base_url($faviconSetting['value']);
        }

        $adminGlobalFontSetting = $settingsModel->where('key', 'global_font_family')->first();
        $adminGlobalFont = ($adminGlobalFontSetting && !empty($adminGlobalFontSetting['value'])) ? trim($adminGlobalFontSetting['value']) : 'Outfit';
        $adminHeadingFontSetting = $settingsModel->where('key', 'heading_font_family')->first();
        $adminHeadingFont = ($adminHeadingFontSetting && !empty($adminHeadingFontSetting['value'])) ? trim($adminHeadingFontSetting['value']) : 'inherit';
    ?>
    <title><?= $businessShortName ?> - Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $adminFavicon ?>?v=<?= time() ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $adminFavicon ?>?v=<?= time() ?>">
    <link rel="apple-touch-icon" href="<?= $adminFavicon ?>?v=<?= time() ?>">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php
        $fontsToLoad = array_unique(array_filter([$adminGlobalFont, $adminHeadingFont, 'Plus Jakarta Sans', 'Inter']));
        foreach ($fontsToLoad as $fName):
            if ($fName === 'inherit') continue;
            $fParam = str_replace(' ', '+', $fName);
    ?>
    <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($fParam) ?>:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap" rel="stylesheet">
    <?php endforeach; ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-global.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-datatable.css') ?>?v=<?= time() ?>">
    <!-- Global Dynamic Typography Styles for Admin Panel -->
    <style id="admin-dynamic-typography">
        :root {
            --font-family-base: '<?= esc($adminGlobalFont) ?>', 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-family-heading: <?= ($adminHeadingFont && $adminHeadingFont !== 'inherit') ? "'" . esc($adminHeadingFont) . "', serif" : "var(--font-family-base)" ?>;
        }
        html, body, button, input, select, textarea, .admin-main-content, .card, .table, .form-control, .form-label, .btn, .stat-value, .badge, .premium-card, .premium-input, .module-title, .module-desc, .sidebar-brand-text, .brand-title, .brand-tag, .menu-item-text, .submenu-item span, .sidebar-heading span {
            font-family: var(--font-family-base) !important;
        }
        <?php if (!empty($adminHeadingFont) && $adminHeadingFont !== 'inherit'): ?>
        h1, h2, h3, h4, h5, h6, .page-header h1, .card-title, .stat-title, .section-title, .admin-logo-text {
            font-family: var(--font-family-heading) !important;
        }
        <?php endif; ?>

        /* CRITICAL: Explicitly Protect Font Awesome Icons from font-family overrides */
        .fa, .fas, .far, .fal, .fad, .fab, 
        .fa-solid, .fa-regular, .fa-light, .fa-thin, .fa-duotone, .fa-brands, 
        i[class*="fa-"], span[class*="fa-"] {
            font-family: "Font Awesome 6 Free" !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
            display: inline-block;
            letter-spacing: normal !important;
            word-spacing: normal !important;
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
    </style>
    <style id="admin-critical-preloader-css">
        #adminPreloader.site-preloader {
            position: fixed !important;
            inset: 0 !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 2147483647 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.99) 0%, rgba(248, 245, 240, 0.98) 100%) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        #adminPreloader.site-preloader.preloader-hidden {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
        #adminPreloader .preloader-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px 40px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 20px;
            box-shadow: 0 16px 40px rgba(122, 21, 48, 0.12), 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 360px;
        }
        #adminPreloader .preloader-logo-wrap {
            position: relative;
            width: 84px;
            height: 84px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        #adminPreloader .preloader-halo-ring {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.16);
            box-shadow: 0 0 22px rgba(212, 175, 55, 0.3);
            animation: adminPreloaderHalo 2s ease-in-out infinite alternate;
        }
        #adminPreloader .preloader-spinner-orbit {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #7a1530;
            border-right-color: #d4af37;
            animation: adminPreloaderSpin 1.1s cubic-bezier(0.5, 0.1, 0.5, 0.9) infinite;
        }
        #adminPreloader .preloader-brand-img {
            width: 54px;
            height: 54px;
            object-fit: contain;
            position: relative;
            z-index: 2;
            animation: adminPreloaderPulse 1.8s ease-in-out infinite;
        }
        #adminPreloader .preloader-brand-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #7a1530;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        #adminPreloader .preloader-brand-sub {
            font-size: 11px;
            letter-spacing: 2px;
            font-weight: 700;
            color: #d4af37;
            text-transform: uppercase;
            margin-bottom: 18px;
        }
        #adminPreloader .preloader-meter {
            width: 130px;
            height: 4px;
            background: #ede6d8;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        #adminPreloader .preloader-meter-bar {
            position: absolute;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #d4af37 0%, #7a1530 50%, #d4af37 100%);
            border-radius: 4px;
            animation: adminPreloaderBar 1.25s ease-in-out infinite;
        }
        @keyframes adminPreloaderSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes adminPreloaderPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }
        @keyframes adminPreloaderHalo {
            0% { transform: scale(0.95); opacity: 0.4; }
            100% { transform: scale(1.15); opacity: 1; }
        }
        @keyframes adminPreloaderBar {
            0% { left: -100%; width: 35%; }
            50% { left: 30%; width: 70%; }
            100% { left: 100%; width: 35%; }
        }
    </style>
    <noscript><style>#adminPreloader { display: none !important; }</style></noscript>
</head>
<body>

    <!-- Admin Luxury Preloader -->
    <div id="adminPreloader" class="site-preloader">
        <div class="preloader-card">
            <div class="preloader-logo-wrap">
                <div class="preloader-halo-ring"></div>
                <div class="preloader-spinner-orbit"></div>
                <img src="<?= $adminLogoUrl ?>" alt="<?= $businessShortName ?>" class="preloader-brand-img" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
            </div>
            <div class="preloader-brand-title"><?= $businessShortName ?></div>
            <div class="preloader-brand-sub">Admin Portal</div>
            <div class="preloader-meter">
                <div class="preloader-meter-bar"></div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var preloader = document.getElementById('adminPreloader');
            if (!preloader) return;
            var startTime = Date.now();
            var minDisplayTime = 800; // 800ms minimum display time
            var isDismissed = false;

            function hideAdminPreloader() {
                if (isDismissed) return;
                var elapsed = Date.now() - startTime;
                var remaining = Math.max(0, minDisplayTime - elapsed);

                setTimeout(function() {
                    if (isDismissed) return;
                    isDismissed = true;
                    preloader.classList.add('preloader-hidden');
                    setTimeout(function() {
                        if (preloader) {
                            preloader.style.setProperty('display', 'none', 'important');
                        }
                    }, 400);
                }, remaining);
            }

            if (document.readyState === 'complete') {
                hideAdminPreloader();
            } else {
                window.addEventListener('load', hideAdminPreloader);
            }
            setTimeout(hideAdminPreloader, 2500);
        })();
    </script>
    <aside class="sidebar" id="adminSidebar">
        <!-- Sidebar Brand Header -->
        <div class="sidebar-header">
            <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-brand-link">
                <div class="sidebar-brand-icon <?= $hasCustomLogo ? 'has-logo' : '' ?>" id="sidebarBrandIconBox">
                    <?php if ($hasCustomLogo): ?>
                        <img src="<?= $adminLogoUrl ?>" alt="<?= esc($businessShortName) ?> Logo" class="sidebar-brand-img" onerror="this.remove(); var box=document.getElementById('sidebarBrandIconBox'); if(box){ box.classList.remove('has-logo'); box.innerHTML='<i class=\'fa-solid fa-crown\'></i>'; }">
                    <?php else: ?>
                        <i class="fa-solid fa-crown"></i>
                    <?php endif; ?>
                </div>
                <div class="sidebar-brand-text">
                    <span class="brand-title"><?= $businessShortName ?></span>
                    <span class="brand-tag">ADMIN</span>
                </div>
            </a>
            <button type="button" class="sidebar-collapse-btn" id="sidebarCollapseBtn" title="Collapse sidebar" aria-label="Toggle sidebar collapse">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="sidebar-mobile-close-btn" id="sidebarMobileCloseBtn" title="Close navigation" aria-label="Close navigation">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Menu -->
        <div class="sidebar-menu">
            <div class="sidebar-heading"><span>Overview</span></div>
            <a href="<?= base_url('admin/dashboard') ?>" class="menu-item <?= (current_url() == base_url('admin/dashboard')) ? 'active' : '' ?>" data-tooltip="Dashboard">
                <i class="fa-solid fa-chart-line"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>

            <div class="sidebar-heading"><span>Catalog & Sales</span></div>
            <a href="<?= base_url('admin/products') ?>" class="menu-item <?= (strpos(current_url(), 'admin/products') !== false) ? 'active' : '' ?>" data-tooltip="Products">
                <i class="fa-solid fa-box"></i>
                <span class="menu-item-text">Products</span>
            </a>
            <a href="<?= base_url('admin/categories') ?>" class="menu-item <?= (strpos(current_url(), 'admin/categories') !== false) ? 'active' : '' ?>" data-tooltip="Categories">
                <i class="fa-solid fa-tags"></i>
                <span class="menu-item-text">Categories</span>
            </a>
            <a href="<?= base_url('admin/orders') ?>" class="menu-item <?= (strpos(current_url(), 'admin/orders') !== false) ? 'active' : '' ?>" data-tooltip="Orders">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="menu-item-text">Orders</span>
            </a>

            <div class="sidebar-heading"><span>Customers & Reviews</span></div>
            <?php 
                $currUrl = current_url();
                $isCustomerSection = (strpos($currUrl, 'admin/customers') !== false || strpos($currUrl, 'admin/testimonials') !== false || strpos($currUrl, 'admin/enquiries') !== false || strpos($currUrl, 'admin/subscribers') !== false || strpos($currUrl, 'admin/reviews') !== false);
                $isTestimonialsActive = (strpos($currUrl, 'testimonials') !== false);
                $isEnquiriesActive = (strpos($currUrl, 'enquiries') !== false);
                $isSubscribersActive = (strpos($currUrl, 'subscribers') !== false);
                $isReviewsActive = (strpos($currUrl, 'reviews') !== false);
            ?>
            <div class="sidebar-dropdown <?= $isCustomerSection ? 'open' : '' ?>" id="customersDropdown" data-tooltip="Customers">
                <a href="javascript:void(0)" class="menu-item menu-dropdown-toggle <?= $isCustomerSection ? 'active-parent' : '' ?>" id="customersDropdownBtn" role="button" aria-expanded="<?= $isCustomerSection ? 'true' : 'false' ?>" aria-controls="customersSubmenu">
                    <span class="menu-item-content">
                        <i class="fa-solid fa-users"></i>
                        <span class="menu-item-text">Customers</span>
                    </span>
                    <i class="fa-solid fa-chevron-down dropdown-chevron"></i>
                </a>
                <div class="submenu" id="customersSubmenu" style="<?= $isCustomerSection ? 'display: block;' : 'display: none;' ?>">
                    <div class="flyout-heading">Customers</div>
                    <a href="<?= base_url('admin/customers/testimonials') ?>" class="submenu-item <?= $isTestimonialsActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-comment-dots"></i>
                        <span>Testimonials</span>
                    </a>
                    <a href="<?= base_url('admin/customers/enquiries') ?>" class="submenu-item <?= $isEnquiriesActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-envelope-open-text"></i>
                        <span>Enquiries</span>
                    </a>
                    <a href="<?= base_url('admin/customers/subscribers') ?>" class="submenu-item <?= $isSubscribersActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-bell"></i>
                        <span>Subscribers</span>
                    </a>
                    <a href="<?= base_url('admin/reviews') ?>" class="submenu-item <?= $isReviewsActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-star-half-stroke"></i>
                        <span>Reviews</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-heading"><span>Website Content Management</span></div>
            <?php 
                $currUrl = current_url();
                $isContentSection = (strpos($currUrl, 'admin/faqs') !== false || strpos($currUrl, 'admin/pages') !== false || strpos($currUrl, 'admin/footer-links') !== false || strpos($currUrl, 'admin/blogs') !== false);
                $isFaqActive = (strpos($currUrl, 'admin/faqs') !== false);
                $isPagesActive = (strpos($currUrl, 'admin/pages') !== false);
                $isFooterLinksActive = (strpos($currUrl, 'admin/footer-links') !== false);
                $isBlogsActive = (strpos($currUrl, 'admin/blogs') !== false);
            ?>
            <div class="sidebar-dropdown <?= $isContentSection ? 'open' : '' ?>" id="contentDropdown" data-tooltip="Content Management">
                <a href="javascript:void(0)" class="menu-item menu-dropdown-toggle <?= $isContentSection ? 'active-parent' : '' ?>" id="contentDropdownBtn" role="button" aria-expanded="<?= $isContentSection ? 'true' : 'false' ?>" aria-controls="contentSubmenu">
                    <span class="menu-item-content">
                        <i class="fa-solid fa-file-pen"></i>
                        <span class="menu-item-text">Website Content</span>
                    </span>
                    <i class="fa-solid fa-chevron-down dropdown-chevron"></i>
                </a>
                <div class="submenu" id="contentSubmenu" style="<?= $isContentSection ? 'display: block;' : 'display: none;' ?>">
                    <div class="flyout-heading">Website Content</div>
                    <a href="<?= base_url('admin/faqs') ?>" class="submenu-item <?= $isFaqActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-circle-question"></i>
                        <span>FAQ Management</span>
                    </a>
                    <a href="<?= base_url('admin/pages') ?>" class="submenu-item <?= $isPagesActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-lines"></i>
                        <span>Website Pages</span>
                    </a>
                    <a href="<?= base_url('admin/footer-links') ?>" class="submenu-item <?= $isFooterLinksActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-link"></i>
                        <span>Footer Links</span>
                    </a>
                    <a href="<?= base_url('admin/blogs') ?>" class="submenu-item <?= $isBlogsActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Blog Management</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-heading"><span>Storefront CMS</span></div>
            <a href="<?= base_url('admin/banners') ?>" class="menu-item <?= (strpos(current_url(), 'admin/banners') !== false) ? 'active' : '' ?>" data-tooltip="Banners & Sliders">
                <i class="fa-solid fa-images"></i>
                <span class="menu-item-text">Banners & Sliders</span>
            </a>

            <div class="sidebar-heading"><span>System Settings</span></div>
            <?php 
                $currUrl = current_url();
                $isSettingsSection = (strpos($currUrl, 'admin/settings') !== false);
                $isHomepageActive = (strpos($currUrl, 'admin/settings/homepage') !== false);
                $isModulesActive = (strpos($currUrl, 'admin/settings/modules') !== false);
                $isAttributesActive = (strpos($currUrl, 'admin/settings/attributes') !== false);
                $isPaymentActive = (strpos($currUrl, 'admin/settings/payment') !== false);
                $isGeneralActive = ($isSettingsSection && !$isHomepageActive && !$isModulesActive && !$isPaymentActive && !$isAttributesActive);
            ?>
            <div class="sidebar-dropdown <?= $isSettingsSection ? 'open' : '' ?>" id="settingsDropdown" data-tooltip="Settings">
                <a href="javascript:void(0)" class="menu-item menu-dropdown-toggle <?= $isSettingsSection ? 'active-parent' : '' ?>" id="settingsDropdownBtn" role="button" aria-expanded="<?= $isSettingsSection ? 'true' : 'false' ?>" aria-controls="settingsSubmenu">
                    <span class="menu-item-content">
                        <i class="fa-solid fa-gear"></i>
                        <span class="menu-item-text">Settings</span>
                    </span>
                    <i class="fa-solid fa-chevron-down dropdown-chevron"></i>
                </a>
                <div class="submenu" id="settingsSubmenu" style="<?= $isSettingsSection ? 'display: block;' : 'display: none;' ?>">
                    <div class="flyout-heading">Settings</div>
                    <a href="<?= base_url('admin/settings/general') ?>" class="submenu-item <?= $isGeneralActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-sliders"></i>
                        <span>General Settings</span>
                    </a>
                    <a href="<?= base_url('admin/settings/homepage') ?>" class="submenu-item <?= $isHomepageActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-house"></i>
                        <span>Home Page</span>
                    </a>
                    <a href="<?= base_url('admin/settings/modules') ?>" class="submenu-item <?= $isModulesActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-cubes"></i>
                        <span>Module Setting</span>
                    </a>
                    <a href="<?= base_url('admin/settings/attributes') ?>" class="submenu-item <?= $isAttributesActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-tags"></i>
                        <span>Attributes (Color & Size)</span>
                    </a>
                    <a href="<?= base_url('admin/settings/payment') ?>" class="submenu-item <?= $isPaymentActive ? 'active' : '' ?>">
                        <i class="fa-solid fa-credit-card"></i>
                        <span>Payment Gateway</span>
                    </a>
                    <a href="<?= base_url('admin/settings/ecart') ?>" class="submenu-item <?= (strpos(current_url(), 'admin/settings/ecart') !== false) ? 'active' : '' ?>">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>eCart Logistics</span>
                    </a>
                    <a href="<?= base_url('admin/settings/whatsapp') ?>" class="submenu-item <?= (strpos(current_url(), 'admin/settings/whatsapp') !== false) ? 'active' : '' ?>">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>WhatsApp API</span>
                    </a>
                </div>
            </div>
            <a href="<?= base_url('admin/gst-rates') ?>" class="menu-item <?= (strpos(current_url(), 'admin/gst-rates') !== false) ? 'active' : '' ?>" data-tooltip="GST Rates">
                <i class="fa-solid fa-percent"></i>
                <span class="menu-item-text">GST Settings</span>
            </a>
        </div>

        <?php 
            $adminDisplayName = session()->get('admin_name') ?? 'Administrator';
            $adminDisplayEmail = session()->get('admin_email') ?? 'selvitex@gmail.com';
            $adminInitials = strtoupper(substr($adminDisplayName, 0, 2));
        ?>

        <!-- Sidebar Profile Footer -->
        <div class="sidebar-footer">
            <div class="admin-profile-card">
                <a href="<?= base_url('admin/profile') ?>" class="admin-profile-clickable <?= (strpos(current_url(), 'admin/profile') !== false) ? 'active' : '' ?>" id="sidebarProfileTrigger" title="View & Edit Admin Profile">
                    <div class="admin-avatar">
                        <span id="sidebarAvatar"><?= esc($adminInitials) ?></span>
                        <span class="admin-status-dot" title="Online"></span>
                    </div>
                    <div class="admin-info">
                        <div class="admin-name" id="sidebarAdminName"><?= esc($adminDisplayName) ?></div>
                        <div class="admin-role">Super Admin <i class="fa-solid fa-key" style="font-size: 10px; margin-left: 3px; opacity: 0.7;"></i></div>
                    </div>
                </a>
                <a href="<?= base_url('admin/logout') ?>" class="admin-logout-btn" title="Sign Out" aria-label="Sign Out">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle-btn" id="sidebarMobileToggle" title="Toggle Mobile Navigation" aria-label="Toggle Navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <button class="topbar-desktop-collapse" id="sidebarDesktopToggle" title="Toggle Sidebar Width" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <div class="topbar-breadcrumb">
                    <span class="breadcrumb-root"><?= $businessShortName ?></span>
                    <i class="fa-solid fa-angle-right breadcrumb-separator"></i>
                    <span class="topbar-title"><?= isset($title) ? esc($title) : 'Dashboard' ?></span>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="<?= base_url('/') ?>" target="_blank" class="btn btn-store-view" title="View Storefront">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Live Store</span>
                </a>
                <a href="<?= base_url('admin/profile') ?>" class="topbar-user-pill <?= (strpos(current_url(), 'admin/profile') !== false) ? 'active' : '' ?>" id="topbarProfileTrigger" title="View & Edit Admin Profile">
                    <div class="user-avatar-sm" id="topbarAvatar"><?= esc($adminInitials) ?></div>
                    <span class="user-name-sm" id="topbarAdminName"><?= esc($adminDisplayName) ?></span>
                </a>
                <a href="<?= base_url('admin/logout') ?>" class="topbar-btn" title="Sign Out" aria-label="Sign Out">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
        </header>

        <div class="content-area">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Vendor Export Libraries (Excel & PDF) -->
    <script src="<?= base_url('assets/js/vendor/xlsx.full.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vendor/jspdf.umd.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vendor/jspdf.plugin.autotable.min.js') ?>"></script>
    <!-- SSPTEX Universal Data Table Engine (Pagination, Search, Sorting & Export) -->
    <script src="<?= base_url('assets/js/admin-datatable.js') ?>?v=<?= time() ?>"></script>
    <script src="<?= base_url('assets/js/admin-layout.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
