<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-settings.css') ?>?v=<?= time() ?>">

<script>
    window.STORE_CATEGORIES = <?= json_encode(array_values(array_map(function($c) {
        return [
            'id' => (int)($c['id'] ?? 0),
            'name' => (string)($c['name'] ?? ''),
            'slug' => (string)(!empty($c['slug']) ? $c['slug'] : url_title($c['name'] ?? '', '-', true))
        ];
    }, $categories ?? []))) ?>;
</script>

<?php 
    $currentSection = $section ?? 'general';
    if (strpos(current_url(), 'admin/settings/homepage') !== false) {
        $currentSection = 'homepage';
    } elseif (strpos(current_url(), 'admin/settings/modules') !== false) {
        $currentSection = 'modules';
    } elseif (strpos(current_url(), 'admin/settings/payment') !== false) {
        $currentSection = 'payment';
    } elseif (strpos(current_url(), 'admin/settings/attributes') !== false) {
        $currentSection = 'attributes';
    }
?>

<div class="settings-container">
    <div class="settings-header">
        <h2>
            <?php if($currentSection === 'general'): ?>
                <i class="fa-solid fa-sliders" style="margin-right: 10px; color: #e11d48;"></i> General Settings
            <?php elseif($currentSection === 'homepage'): ?>
                <i class="fa-solid fa-house" style="margin-right: 10px; color: #e11d48;"></i> Home Page Settings
            <?php elseif($currentSection === 'payment'): ?>
                <i class="fa-solid fa-credit-card" style="margin-right: 10px; color: #e11d48;"></i> Payment Gateway Settings
            <?php elseif($currentSection === 'attributes'): ?>
                <i class="fa-solid fa-tags" style="margin-right: 10px; color: #e11d48;"></i> Attribute Settings (Color &amp; Size)
            <?php else: ?>
                <i class="fa-solid fa-cubes" style="margin-right: 10px; color: #e11d48;"></i> Module Settings
            <?php endif; ?>
        </h2>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #166534; padding: 16px 20px; border-radius: 10px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; animation: slideUp 0.4s ease-out;">
            <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="premium-card">
        <?php if ($currentSection !== 'modules' && $currentSection !== 'payment' && $currentSection !== 'attributes'): ?>
        <div class="settings-tabs" style="overflow-x: auto;">
            <?php if ($currentSection === 'general'): ?>
                <div class="settings-tab" data-tab="business" onclick="showTab('business', this)"><i class="fa-solid fa-building"></i> 1. Business Info</div>
                <div class="settings-tab" data-tab="social" onclick="showTab('social', this)"><i class="fa-solid fa-hashtag"></i> 2. Social Links</div>
                <div class="settings-tab" data-tab="seo" onclick="showTab('seo', this)"><i class="fa-solid fa-chart-line"></i> SEO & Analytics</div>
            <?php elseif ($currentSection === 'homepage'): ?>
                <div class="settings-tab" data-tab="display" onclick="showTab('display', this)"><i class="fa-solid fa-display"></i> 1. Header Display</div>
                <div class="settings-tab" data-tab="homepage" onclick="showTab('homepage', this)"><i class="fa-solid fa-house"></i> 2. Home Page</div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <input type="hidden" name="active_tab_redirect" id="active_tab_redirect" value="<?= esc($active_tab ?? 'business') ?>">
        
        <!-- Business Tab -->
        <div id="tab-business" class="settings-section">
            <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                <input type="hidden" name="section_name" value="business">
                <input type="hidden" name="active_tab_redirect" value="business">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="business_name" class="premium-input" value="<?= esc($settings['business_name'] ?? 'Sri Sakthi Selvi Tex') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Short Name</label>
                        <input type="text" name="short_name" class="premium-input" value="<?= esc($settings['short_name'] ?? 'SSPTEX') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="premium-input" value="<?= esc($settings['gst_number'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-location-arrow" style="color: #e11d48; margin-right: 5px;"></i> Google Maps Navigation Link</label>
                        <input type="text" name="location_link" class="premium-input" placeholder="e.g. https://maps.app.goo.gl/..." value="<?= esc($settings['location_link'] ?? '') ?>">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-envelope" style="color: #e11d48; margin-right: 5px;"></i> Contact / Support Email</label>
                        <input type="email" name="contact_email" class="premium-input" placeholder="e.g. support@ssptex.com" value="<?= esc($settings['contact_email'] ?? ($settings['support_email'] ?? '')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-phone" style="color: #e11d48; margin-right: 5px;"></i> Customer Care Phone</label>
                        <input type="text" name="phone_no" class="premium-input" placeholder="e.g. +91 98765 43210" value="<?= esc($settings['phone_no'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fa-brands fa-whatsapp" style="color: #25D366; margin-right: 5px;"></i> WhatsApp Support Number</label>
                        <input type="text" name="whatsapp_no" class="premium-input" placeholder="e.g. 919876543210" value="<?= esc($settings['whatsapp_no'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fa-regular fa-clock" style="color: #e11d48; margin-right: 5px;"></i> Business Working Hours</label>
                        <input type="text" name="business_hours" class="premium-input" placeholder="e.g. Mon-Sat, 9:00 AM to 8:00 PM" value="<?= esc($settings['business_hours'] ?? 'Mon-Sat, 9:00 AM to 8:00 PM') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-map-location-dot" style="color: #e11d48; margin-right: 5px;"></i> Google Maps Location Embed (Iframe or Embed URL)</label>
                    <textarea name="google_map_embed" class="premium-input" rows="3" placeholder="Paste Google Maps embed <iframe> code or embed link to display interactive map on Contact Us page..."><?= esc($settings['google_map_embed'] ?? '') ?></textarea>
                    <small style="color: #64748b; font-size: 12px; margin-top: 4px; display: block;">Displays interactive Google Map on the Contact Us page. You can copy embed code directly from Google Maps &gt; Share &gt; Embed a map.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Address</label>
                    <textarea name="business_address" class="premium-input" rows="3" placeholder="Enter physical address..."><?= esc($settings['business_address'] ?? '') ?></textarea>
                </div>

                <?php
                    $curLogoUrl = (!empty($settings['business_logo'])) ? base_url($settings['business_logo']) : base_url('assets/images/logo.png');
                    $curFaviconUrl = (!empty($settings['favicon'])) ? base_url($settings['favicon']) : ((!empty($settings['business_logo'])) ? base_url($settings['business_logo']) : base_url('assets/images/logo.png'));
                    $bizDisplayName = (!empty($settings['business_name'])) ? $settings['business_name'] : 'Selvi Tex';
                ?>
                <div class="branding-media-grid">
                    <!-- 1. Storefront Business Logo Card with Live Simulator -->
                    <div class="branding-upload-card">
                        <div class="branding-card-header">
                            <span class="branding-card-title">
                                <i class="fa-solid fa-gem" style="color: #e11d48;"></i> Storefront Business Logo
                            </span>
                            <span class="branding-badge" id="logoStatusBadge">Current Saved Logo</span>
                        </div>

                        <!-- Dropzone Uploader -->
                        <div class="branding-dropzone" onclick="document.getElementById('businessLogoInput').click();">
                            <input type="file" id="businessLogoInput" name="business_logo" accept="image/png, image/jpeg, image/webp, image/svg+xml" onchange="previewBusinessLogo(this);" onclick="event.stopPropagation();">
                            <div class="branding-dropzone-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                            <div class="branding-dropzone-title">Click to Upload New Logo</div>
                            <div class="branding-dropzone-sub">PNG, JPG, WebP, or SVG (Transparent background recommended)</div>
                        </div>

                        <!-- File Selected Pill Info -->
                        <div class="branding-file-selected-info" id="logoFileSelectedInfo">
                            <span class="branding-file-name">
                                <i class="fa-solid fa-circle-check"></i> <span id="logoSelectedFileName">filename.png</span>
                            </span>
                            <button type="button" class="btn-remove-selected-file" onclick="clearBusinessLogoSelection();" title="Remove selected file">&times; Cancel</button>
                        </div>

                        <!-- Live Header Simulator Preview -->
                        <div class="branding-preview-section">
                            <div class="branding-preview-label">
                                <span><i class="fa-solid fa-eye" style="margin-right: 4px;"></i> Live Header Preview</span>
                                <span style="font-weight: 500; font-size: 10.5px; color: #a8a29e;">How it appears on website</span>
                            </div>
                            <div class="store-header-simulator">
                                <img src="<?= $curLogoUrl ?>" data-original-src="<?= $curLogoUrl ?>" alt="Logo" class="sim-logo-img" id="simLogoImg" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
                                <span class="sim-logo-text" id="simLogoText"><?= esc($bizDisplayName) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Favicon (Browser Tab Icon) Card with Browser Tab Mockup -->
                    <div class="branding-upload-card">
                        <div class="branding-card-header">
                            <span class="branding-card-title">
                                <i class="fa-solid fa-window-maximize" style="color: #e11d48;"></i> Favicon (Browser Tab Icon)
                            </span>
                            <span class="branding-badge" id="faviconStatusBadge">Current Saved Favicon</span>
                        </div>

                        <!-- Dropzone Uploader -->
                        <div class="branding-dropzone" onclick="document.getElementById('faviconInput').click();">
                            <input type="file" id="faviconInput" name="favicon" accept="image/png, image/x-icon, image/ico, image/jpeg, image/webp, image/svg+xml" onchange="previewFavicon(this);" onclick="event.stopPropagation();">
                            <div class="branding-dropzone-icon"><i class="fa-solid fa-image"></i></div>
                            <div class="branding-dropzone-title">Click to Upload New Favicon</div>
                            <div class="branding-dropzone-sub">Square icon (ICO, PNG, or SVG — ideally 32x32 or 64x64 px)</div>
                        </div>

                        <!-- File Selected Pill Info -->
                        <div class="branding-file-selected-info" id="faviconFileSelectedInfo">
                            <span class="branding-file-name">
                                <i class="fa-solid fa-circle-check"></i> <span id="faviconSelectedFileName">favicon.png</span>
                            </span>
                            <button type="button" class="btn-remove-selected-file" onclick="clearFaviconSelection();" title="Remove selected file">&times; Cancel</button>
                        </div>

                        <!-- Live Browser Tab Mockup -->
                        <div class="branding-preview-section">
                            <div class="branding-preview-label">
                                <span><i class="fa-solid fa-compass" style="margin-right: 4px;"></i> Browser Tab Mockup</span>
                                <span style="font-weight: 500; font-size: 10.5px; color: #a8a29e;">Google Chrome Simulation</span>
                            </div>
                            <div class="browser-tab-mockup-wrapper">
                                <div class="browser-tab-window-bar">
                                    <span class="browser-dot red"></span>
                                    <span class="browser-dot yellow"></span>
                                    <span class="browser-dot green"></span>
                                </div>
                                <div class="browser-tab-mockup">
                                    <img src="<?= $curFaviconUrl ?>" data-original-src="<?= $curFaviconUrl ?>" alt="Favicon" class="browser-tab-favicon" id="browserTabFavicon" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
                                    <span class="browser-tab-title"><?= esc($bizDisplayName) ?> | Pure Silk Sarees</span>
                                    <span class="browser-tab-close">&times;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Global Website Typography & Trending Font Selector Card -->
                <?php
                    $curGlobalFont = $settings['global_font_family'] ?? 'Outfit';
                    $curHeadingFont = $settings['heading_font_family'] ?? 'Cinzel';

                    $trendingFontsList = [
                        'Trending Modern Sans-Serif (High Legibility & Modern E-Commerce)' => [
                            'Outfit' => 'Outfit (Clean Geometric & Modern E-Commerce — Highly Recommended)',
                            'Plus Jakarta Sans' => 'Plus Jakarta Sans (Contemporary Luxury UI)',
                            'Inter' => 'Inter (Universal High-Legibility & Clean)',
                            'Poppins' => 'Poppins (Vibrant, Geometric & Friendly)',
                            'Montserrat' => 'Montserrat (Bold Architectural Modern)',
                            'Urbanist' => 'Urbanist (Minimalist Sleek High-Fashion)',
                            'DM Sans' => 'DM Sans (Contemporary Sophisticated)',
                            'Nunito Sans' => 'Nunito Sans (Soft Balanced Clarity)',
                            'Lato' => 'Lato (Warm Versatile Corporate)',
                            'Roboto' => 'Roboto (Neutral Classic Standard)',
                        ],
                        'Royal Luxury & Editorial Serif (Ideal for Silk, Sarees & Ethnic Wear)' => [
                            'Cinzel' => 'Cinzel (Royal Classical Stone Luxury — Regal Sarees)',
                            'Playfair Display' => 'Playfair Display (High Fashion Editorial Heritage)',
                            'Cormorant Garamond' => 'Cormorant Garamond (Graceful Master Handloom Serif)',
                            'Prata' => 'Prata (Teardrop Luxury Fashion)',
                            'Marcellus' => 'Marcellus (Artisanal Traditional Handloom Flare)',
                            'Bodoni Moda' => 'Bodoni Moda (Vogue Haute Couture Elegance)',
                            'Lora' => 'Lora (Poetic Warm Contemporary Serif)',
                        ]
                    ];
                ?>
                <div class="branding-upload-card" style="margin-top: 20px; margin-bottom: 25px; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 24px; background: #ffffff;">
                    <div class="branding-card-header" style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 18px;">
                        <span class="branding-card-title" style="font-size: 15px; font-weight: 800; color: #0f172a;">
                            <i class="fa-solid fa-font" style="color: #e11d48; font-size: 17px;"></i> Global Website Typography &amp; Trending Font Selection
                        </span>
                        <span class="branding-badge" id="fontBadgeDisplay" style="background: #fdf2f4; color: #e11d48; font-weight: 700; border: 1px solid rgba(225, 29, 72,0.2);">
                            <?= esc($curGlobalFont) ?> + <?= esc($curHeadingFont) ?>
                        </span>
                    </div>

                    <p style="font-size: 13px; color: #64748b; margin: 0 0 18px; line-height: 1.5;">
                        Select trending Google Fonts applied globally across your entire website storefront (navigation, titles, product cards, prices, and paragraphs).
                    </p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px;">
                        <!-- Global Body / UI Font -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700; font-size: 13.5px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-globe" style="color: #0284c7;"></i> Global Website &amp; Admin Panel Font
                            </label>
                            <select name="global_font_family" id="global_font_family" class="premium-input" style="font-weight: 600; cursor: pointer;" onchange="updateDynamicFontPreview()">
                                <?php foreach($trendingFontsList as $groupLabel => $fonts): ?>
                                    <optgroup label="<?= esc($groupLabel) ?>">
                                        <?php foreach($fonts as $fontKey => $fontName): ?>
                                            <option value="<?= esc($fontKey) ?>" <?= ($curGlobalFont === $fontKey) ? 'selected' : '' ?>>
                                                <?= esc($fontName) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <small style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">
                                Controls typography across Admin Panel, storefront body text, cards, prices, and navigation.
                            </small>
                        </div>

                        <!-- Headings & Titles Font -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700; font-size: 13.5px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-heading" style="color: #e11d48;"></i> Headings &amp; Section Titles Font
                            </label>
                            <select name="heading_font_family" id="heading_font_family" class="premium-input" style="font-weight: 600; cursor: pointer;" onchange="updateDynamicFontPreview()">
                                <option value="inherit" <?= ($curHeadingFont === 'inherit') ? 'selected' : '' ?>>Same as Global Font (Unified Modern Style)</option>
                                <?php foreach($trendingFontsList as $groupLabel => $fonts): ?>
                                    <optgroup label="<?= esc($groupLabel) ?>">
                                        <?php foreach($fonts as $fontKey => $fontName): ?>
                                            <option value="<?= esc($fontKey) ?>" <?= ($curHeadingFont === $fontKey) ? 'selected' : '' ?>>
                                                <?= esc($fontName) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <small style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">
                                Controls brand headings (H1, H2, H3), section titles, and collection banners.
                            </small>
                        </div>
                    </div>

                    <!-- Live Interactive Typography Simulator -->
                    <div id="fontLivePreviewBox" style="background: #fdfbf7; border: 1.5px solid #d4af37; border-radius: 12px; padding: 22px 24px; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-size: 11px; font-weight: 800; color: #e11d48; text-transform: uppercase; letter-spacing: 0.8px;">
                                <i class="fa-solid fa-eye"></i> Live Interactive Typography Simulator
                            </span>
                            <span id="activeFontPreviewPill" style="font-size: 11px; background: #ffffff; border: 1px solid #d4af37; color: #e11d48; padding: 2px 8px; border-radius: 6px; font-weight: 700;">
                                Previewing: <?= esc($curGlobalFont) ?>
                            </span>
                        </div>

                        <div id="simSampleHeading" style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 6px; line-height: 1.3;">
                            Sri Sakthi Selvi Tex — Master Weavers of Salem
                        </div>
                        <div id="simSampleSubheading" style="font-size: 14.5px; font-weight: 600; color: #b45309; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Authentic Pure Silk &amp; Elampillai Handloom Sarees
                        </div>
                        <div id="simSampleBody" style="font-size: 14.5px; color: #475569; line-height: 1.7; margin-bottom: 14px;">
                            Experience the timeless art of handloom weaving directly from master artisans. Every weave tells an authentic story of heritage, culture, direct loom pricing, and unmatched elegance.
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 14px; align-items: center; padding-top: 14px; border-top: 1px dashed #cbd5e1; font-size: 13px; color: #64748b;">
                            <span id="simSampleBadge" style="background: #e11d48; color: #ffffff; padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-bag-shopping"></i> Shop Now &bull; ₹ 4,599
                            </span>
                            <span id="simSampleAlphabet" style="font-size: 12.5px; font-weight: 500; color: #334155;">
                                Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj &bull; 0 1 2 3 4 5 6 7 8 9
                            </span>
                        </div>
                    </div>
                </div>

                <div class="section-card-footer">
                    <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates store branding, global typography & physical address</span>
                    <button type="submit" class="btn-section-save">
                        <i class="fa-solid fa-floppy-disk"></i> Save Business Info
                    </button>
                </div>
            </form>
        </div>

        <!-- Social Tab -->
        <div id="tab-social" class="settings-section">
            <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                <input type="hidden" name="section_name" value="social">
                <input type="hidden" name="active_tab_redirect" value="social">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label"><i class="fa-brands fa-whatsapp" style="color: #25D366; margin-right: 5px;"></i> WhatsApp Number</label>
                        <input type="text" name="whatsapp_no" class="premium-input" placeholder="e.g. 919876543210" value="<?= esc($settings['whatsapp_no'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><i class="fa-brands fa-youtube" style="color: #FF0000; margin-right: 5px;"></i> YouTube Channel Link</label>
                        <input type="text" name="youtube_link" class="premium-input" placeholder="e.g. https://youtube.com/@srisakthiselvitex" value="<?= esc($settings['youtube_link'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fa-brands fa-facebook" style="color: #1877F2; margin-right: 5px;"></i> Facebook Link</label>
                        <input type="text" name="facebook_link" class="premium-input" placeholder="e.g. https://facebook.com/srisakthiselvitex" value="<?= esc($settings['facebook_link'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><i class="fa-brands fa-instagram" style="color: #E4405F; margin-right: 5px;"></i> Instagram Link</label>
                        <input type="text" name="instagram_link" class="premium-input" placeholder="e.g. https://instagram.com/srisakthiselvitex" value="<?= esc($settings['instagram_link'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><i class="fa-brands fa-twitter" style="color: #1DA1F2; margin-right: 5px;"></i> Twitter / X Link</label>
                        <input type="text" name="twitter_link" class="premium-input" placeholder="e.g. https://twitter.com/srisakthiselvi" value="<?= esc($settings['twitter_link'] ?? '') ?>">
                    </div>
                </div>

                <div class="section-card-footer">
                    <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates WhatsApp, YouTube, Instagram, Facebook & Twitter links across storefront</span>
                    <button type="submit" class="btn-section-save">
                        <i class="fa-solid fa-floppy-disk"></i> Save Social Links
                    </button>
                </div>
            </form>
        </div>

        <!-- Payment Gateway Tab -->
        <div id="tab-payment" class="settings-section">
            <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form" id="razorpay-settings-form">
                <input type="hidden" name="section_name" value="payment">
                <input type="hidden" name="active_tab_redirect" value="payment">

                <!-- Gateway Status & Environment Card -->
                <div class="settings-section-card" style="margin-bottom: 25px;">
                    <div class="section-card-header">
                        <div class="section-card-header-left">
                            <div class="section-icon-badge maroon-gradient">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <div>
                                <h3 class="section-card-title">
                                    Razorpay Secure Payment Gateway
                                    <span class="badge-pill-header" style="background: #e0f2fe; color: #0369a1;">Instant UPI, Cards &amp; Netbanking</span>
                                </h3>
                                <p class="section-card-desc">Accept online payments from customers via UPI (Google Pay, PhonePe, Paytm), Credit/Debit Cards, and Net Banking using official Razorpay APIs.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <span class="badge-live-status <?= ($settings['razorpay_enabled'] ?? '0') === '1' ? 'active' : 'disabled' ?>" id="razorpay-status-badge">
                                <?php if(($settings['razorpay_enabled'] ?? '0') === '1'): ?>
                                    <i class="fa-solid fa-circle-check"></i> Gateway Active
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-xmark"></i> Gateway Disabled
                                <?php endif; ?>
                            </span>
                            <label class="switch" title="Enable or Disable Razorpay Gateway">
                                <input type="hidden" name="razorpay_enabled" value="0">
                                <input type="checkbox" id="razorpay-enabled-toggle" name="razorpay_enabled" value="1" <?= ($settings['razorpay_enabled'] ?? '0') === '1' ? 'checked' : '' ?> onchange="updateRzpStatusBadge(this)">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Environment Selector -->
                    <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <label class="form-label" style="font-size: 13px; font-weight: 800; color: #1e293b; margin-bottom: 10px; display: block;">
                            <i class="fa-solid fa-toggle-on" style="color: #e11d48; margin-right: 5px;"></i> Active Environment / Gateway Mode
                        </label>
                        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 18px; background: #ffffff; border: 1.5px solid <?= ($settings['razorpay_env'] ?? 'test') === 'test' ? '#e11d48' : '#cbd5e1' ?>; border-radius: 8px; font-size: 13.5px; font-weight: 700;">
                                <input type="radio" name="razorpay_env" value="test" <?= ($settings['razorpay_env'] ?? 'test') === 'test' ? 'checked' : '' ?> onchange="switchRzpEnv('test')" style="accent-color: #e11d48;">
                                <i class="fa-solid fa-vial" style="color: #f59e0b;"></i> Test Mode (Sandbox Simulation)
                            </label>

                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 18px; background: #ffffff; border: 1.5px solid <?= ($settings['razorpay_env'] ?? 'test') === 'live' ? '#e11d48' : '#cbd5e1' ?>; border-radius: 8px; font-size: 13.5px; font-weight: 700;">
                                <input type="radio" name="razorpay_env" value="live" <?= ($settings['razorpay_env'] ?? 'test') === 'live' ? 'checked' : '' ?> onchange="switchRzpEnv('live')" style="accent-color: #e11d48;">
                                <i class="fa-solid fa-shield-halved" style="color: #10b981;"></i> Live Mode (Real Customer Payments)
                            </label>
                        </div>
                        <small style="display: block; margin-top: 8px; color: #64748b; font-size: 12px;">
                            Test Mode allows testing transactions without deducting real money. Live Mode should be enabled only after configuring valid production credentials.
                        </small>
                    </div>

                    <!-- Connection Testing Box -->
                    <div style="padding: 18px 24px; background: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: gap: 15px;">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 3px;">
                                <i class="fa-solid fa-network-wired" style="color: #0284c7; margin-right: 6px;"></i> Server-Side Connection Verification
                            </div>
                            <div style="font-size: 12px; color: #64748b;" id="rzp-test-result-text">
                                Verify server-to-server connectivity and credential authentication with Razorpay API.
                            </div>
                        </div>
                        <button type="button" id="btn-test-razorpay" onclick="testRazorpayConnection()" style="padding: 9px 20px; background: #0f172a; color: #ffffff; border: none; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-bolt" id="test-rzp-icon"></i> <span id="test-rzp-btn-text">Test Connection</span>
                        </button>
                    </div>

                    <!-- Test Mode Credentials Panel -->
                    <div id="rzp-panel-test" style="padding: 24px; <?= ($settings['razorpay_env'] ?? 'test') === 'test' ? '' : 'display: none;' ?>">
                        <div style="font-size: 13px; font-weight: 800; color: #b45309; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-flask"></i> Razorpay Test Credentials (Sandbox)
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Test Key ID <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="rzp_test_key_id" name="razorpay_test_key_id" class="premium-input" placeholder="rzp_test_XXXXXXXXXXXX" value="<?= esc($settings['razorpay_test_key_id'] ?? '') ?>">
                                <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Public test identifier provided in Razorpay Dashboard → Settings → API Keys.</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Test Key Secret <span style="color: #ef4444;">*</span></label>
                                <div style="position: relative;">
                                    <input type="password" id="rzp_test_key_secret" name="razorpay_test_key_secret" class="premium-input" placeholder="<?= !empty($settings['razorpay_test_key_secret']) ? '•••••••••••••••• (Saved on Server)' : 'Enter Test Key Secret' ?>" style="padding-right: 40px;">
                                    <button type="button" onclick="togglePasswordVisibility('rzp_test_key_secret', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 4px;">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Kept private on the server for HMAC signature verification. Leave empty to keep existing.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Live Mode Credentials Panel -->
                    <div id="rzp-panel-live" style="padding: 24px; <?= ($settings['razorpay_env'] ?? 'test') === 'live' ? '' : 'display: none;' ?>">
                        <div style="font-size: 13px; font-weight: 800; color: #15803d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-shield-check"></i> Razorpay Live Credentials (Production)
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Live Key ID <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="rzp_live_key_id" name="razorpay_live_key_id" class="premium-input" placeholder="rzp_live_XXXXXXXXXXXX" value="<?= esc($settings['razorpay_live_key_id'] ?? '') ?>">
                                <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Production merchant key for actual transactions.</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Live Key Secret <span style="color: #ef4444;">*</span></label>
                                <div style="position: relative;">
                                    <input type="password" id="rzp_live_key_secret" name="razorpay_live_key_secret" class="premium-input" placeholder="<?= !empty($settings['razorpay_live_key_secret']) ? '•••••••••••••••• (Saved on Server)' : 'Enter Live Key Secret' ?>" style="padding-right: 40px;">
                                    <button type="button" onclick="togglePasswordVisibility('rzp_live_key_secret', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 4px;">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Kept securely on the server. Never exposed in HTML or client APIs. Leave empty to preserve.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping & Checkout Calculation Settings -->
                <div class="settings-section-card" style="margin-bottom: 25px;">
                    <div class="section-card-header">
                        <div class="section-card-header-left">
                            <div class="section-icon-badge maroon-gradient">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                            </div>
                            <div>
                                <h3 class="section-card-title">Shipping &amp; Order Calculation Rules</h3>
                                <p class="section-card-desc">Set the minimum cart value required for free shipping and the fallback shipping fee applied at checkout.</p>
                            </div>
                        </div>
                    </div>

                    <div style="padding: 24px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Free Shipping Threshold (₹)</label>
                                <input type="number" step="0.01" name="free_shipping_threshold" class="premium-input" value="<?= esc($settings['free_shipping_threshold'] ?? '1000.00') ?>" placeholder="1000.00">
                                <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Orders above or equal to this subtotal will receive free shipping automatically.</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Standard Shipping Charge (₹)</label>
                                <input type="number" step="0.01" name="standard_shipping_charge" class="premium-input" value="<?= esc($settings['standard_shipping_charge'] ?? '50.00') ?>" placeholder="50.00">
                                <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Applied when the cart subtotal is less than the free shipping threshold.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card-footer">
                    <span class="section-footer-info"><i class="fa-solid fa-shield-halved" style="color: #22c55e;"></i> Credentials and secrets are stored securely and verified server-side.</span>
                    <button type="submit" class="btn-section-save">
                        <i class="fa-solid fa-floppy-disk"></i> Save Payment Settings
                    </button>
                </div>
            </form>
        </div>

            <!-- Display Tab -->
            <div id="tab-display" class="settings-section">

                <!-- ==========================================================================
                     HEADER DISPLAY SECTION SWITCHER DROPDOWN & QUICK NAVIGATION
                     ========================================================================== -->
                <div class="display-section-filter-card">
                    <!-- Top Header Row -->
                    <div class="filter-card-header-row">
                        <div class="filter-card-content">
                            <div class="filter-card-icon">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div>
                                <h3 class="filter-card-title">
                                    Header &amp; Navigation Display Manager
                                    <span class="filter-card-tag">Storefront Header</span>
                                </h3>
                                <p class="filter-card-desc">
                                    Configure marquee announcement, hero page banners, and header categories.
                                </p>
                            </div>
                        </div>

                        <div class="filter-dropdown-wrapper">
                            <i class="fa-solid fa-sliders filter-dropdown-icon"></i>
                            <select id="header-display-section-select" class="header-display-select-dropdown" onchange="switchHeaderDisplaySection(this.value)">
                                <option value="all">🌐 Show All 3 Header Sections</option>
                                <option value="announcement_bar" selected>📢 1. Top Announcement Bar (Header Marquee)</option>
                                <option value="page_banners">🖼️ 2. Page Banners &amp; Text Overlays</option>
                                <option value="header_categories">📑 3. Categories in Header Navigation</option>
                            </select>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="filter-card-divider"></div>

                    <!-- Bottom Quick Pills Bar -->
                    <div class="display-section-pills-bar">
                        <span class="pills-bar-label"><i class="fa-solid fa-compass" style="margin-right: 4px; color: #e11d48;"></i> Quick Jump:</span>
                        <div class="display-section-pills">
                            <button type="button" class="display-section-pill" data-section="all" onclick="switchHeaderDisplaySection('all')">
                                <i class="fa-solid fa-border-all"></i> All Sections
                            </button>
                            <button type="button" class="display-section-pill active" data-section="announcement_bar" onclick="switchHeaderDisplaySection('announcement_bar')">
                                <i class="fa-solid fa-bullhorn"></i> 1. Announcement Marquee
                            </button>
                            <button type="button" class="display-section-pill" data-section="page_banners" onclick="switchHeaderDisplaySection('page_banners')">
                                <i class="fa-solid fa-images"></i> 2. Page Banners
                            </button>
                            <button type="button" class="display-section-pill" data-section="header_categories" onclick="switchHeaderDisplaySection('header_categories')">
                                <i class="fa-solid fa-bars-staggered"></i> 3. Navigation Categories
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ==========================================================================
                     SECTION 1: TOP ANNOUNCEMENT BAR (HEADER MARQUEE)
                     ========================================================================== -->
                <div class="settings-section-card" id="card-announcement-bar">
                    <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                        <input type="hidden" name="section_name" value="announcement_bar">
                        <input type="hidden" name="active_tab_redirect" value="display">

                        <!-- Section Header -->
                        <div class="section-card-header">
                            <div class="section-card-header-left">
                                <div class="section-icon-badge maroon-gradient">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </div>
                                <div>
                                    <h3 class="section-card-title">
                                        Top Announcement Bar (Header Marquee)
                                        <span class="badge-pill-header">Storefront Ticker</span>
                                    </h3>
                                    <p class="section-card-desc">Display a continuous scrolling marquee banner with promotional offers, shipping alerts, and festival announcements at the very top of your storefront.</p>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <span class="badge-live-status <?= ($settings['announcement_bar_enabled'] ?? '1') === '1' ? 'active' : 'disabled' ?>" id="announcement-status-badge">
                                    <?php if(($settings['announcement_bar_enabled'] ?? '1') === '1'): ?>
                                        <i class="fa-solid fa-circle-dot"></i> Live on Storefront
                                    <?php else: ?>
                                        <i class="fa-solid fa-eye-slash"></i> Disabled / Hidden
                                    <?php endif; ?>
                                </span>
                                <label class="switch" title="Enable or Disable Top Announcement Bar">
                                    <input type="hidden" name="announcement_bar_enabled" value="0">
                                    <input type="checkbox" id="announcement-bar-enabled-toggle" name="announcement_bar_enabled" value="1" <?= ($settings['announcement_bar_enabled'] ?? '1') === '1' ? 'checked' : '' ?> onchange="updateAnnouncementPreview()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Announcement Text / Offers Input -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label class="form-label" style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: flex; align-items: center; gap: 7px;">
                                <i class="fa-solid fa-message" style="color: #e11d48;"></i> Announcement Text / Storefront Offers
                            </label>
                            <textarea id="announcement-bar-text-input" name="announcement_bar_text" class="premium-input" rows="3" oninput="updateAnnouncementPreview()" style="width: 100%; resize: vertical; line-height: 1.6; font-size: 13.5px; padding: 12px 14px;" placeholder="Free Shipping above Rs.1000/- Purchases.       Hurry Most Beautiful collections On its way!       Diwali Sale is Live       Free Shipping above Rs.1000/- Purchases."><?= esc($settings['announcement_bar_text'] ?? 'Free Shipping above Rs.1000/- Purchases.       Hurry Most Beautiful collections On its way!       Diwali Sale is Live       Free Shipping above Rs.1000/- Purchases.') ?></textarea>
                            
                            <!-- Quick Offer Preset Chips -->
                            <div style="margin-top: 10px;">
                                <div style="font-size: 11.5px; font-weight: 700; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-wand-magic-sparkles" style="color: #e11d48; margin-right: 4px;"></i> Quick-Add Offer Presets (Click to Insert)
                                </div>
                                <div class="quick-preset-chips">
                                    <span class="quick-preset-chip" onclick="insertOfferPreset('Free Shipping above Rs.1000/- Purchases.')">
                                        <i class="fa-solid fa-truck-fast" style="color: #10b981;"></i> Free Shipping above Rs.1000/-
                                    </span>
                                    <span class="quick-preset-chip" onclick="insertOfferPreset('Hurry Most Beautiful collections On its way!')">
                                        <i class="fa-solid fa-sparkles" style="color: #f59e0b;"></i> Beautiful Collections On The Way!
                                    </span>
                                    <span class="quick-preset-chip" onclick="insertOfferPreset('Diwali Sale is Live')">
                                        <i class="fa-solid fa-fire" style="color: #ef4444;"></i> Diwali Sale is Live
                                    </span>
                                    <span class="quick-preset-chip" onclick="insertOfferPreset('Limited Time Offer: Extra 10% Off on Pure Silk Sarees')">
                                        <i class="fa-solid fa-tag" style="color: #e11d48;"></i> Extra 10% Off Pure Silk
                                    </span>
                                    <span class="quick-preset-chip" onclick="insertOfferPreset(' • ')">
                                        <i class="fa-solid fa-star" style="color: #eab308;"></i> Star Divider ( • )
                                    </span>
                                </div>
                            </div>

                            <p style="font-size: 11.5px; color: #64748b; margin-top: 10px; line-height: 1.5; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px;">
                                <i class="fa-solid fa-circle-info" style="color: #0284c7; margin-right: 5px;"></i> Multiple announcement phrases separated by multiple spaces, bullets (•), or vertical bars (|) will automatically display golden star dividers across the ticker.
                            </p>
                        </div>

                        <!-- Styling Controls Grid: Bold, Font Size, Text Color, Background Color -->
                        <div class="style-control-grid">
                            <!-- 1. Font Weight / Bold Selection -->
                            <div class="style-control-card">
                                <div class="style-card-header">
                                    <i class="fa-solid fa-bold"></i> Font Weight / Bold
                                </div>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 12px; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-weight: 700; color: #1e293b; user-select: none;">
                                    <input type="hidden" name="announcement_bar_bold" value="0">
                                    <input type="checkbox" id="announcement-bold-toggle" name="announcement_bar_bold" value="1" <?= ($settings['announcement_bar_bold'] ?? '1') === '1' ? 'checked' : '' ?> onchange="updateAnnouncementPreview()" style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                                    <span>Make Text Bold (700)</span>
                                </label>
                            </div>

                            <!-- 2. Font Size Selection -->
                            <div class="style-control-card">
                                <div class="style-card-header">
                                    <i class="fa-solid fa-text-height"></i> Font Size
                                </div>
                                <?php $currFontSize = $settings['announcement_bar_font_size'] ?? '13px'; ?>
                                <select id="announcement-font-size-select" name="announcement_bar_font_size" class="premium-input" onchange="updateAnnouncementPreview()" style="padding: 10px 12px; font-size: 13px; font-weight: 700; cursor: pointer; background: #ffffff;">
                                    <option value="11px" <?= $currFontSize === '11px' ? 'selected' : '' ?>>11px (Compact)</option>
                                    <option value="12px" <?= $currFontSize === '12px' ? 'selected' : '' ?>>12px (Small)</option>
                                    <option value="13px" <?= $currFontSize === '13px' ? 'selected' : '' ?>>13px (Default Standard)</option>
                                    <option value="14px" <?= $currFontSize === '14px' ? 'selected' : '' ?>>14px (Medium)</option>
                                    <option value="15px" <?= $currFontSize === '15px' ? 'selected' : '' ?>>15px (Large)</option>
                                    <option value="16px" <?= $currFontSize === '16px' ? 'selected' : '' ?>>16px (Extra Large)</option>
                                    <option value="18px" <?= $currFontSize === '18px' ? 'selected' : '' ?>>18px (Prominent)</option>
                                </select>
                            </div>

                            <!-- 3. Text Color Selection -->
                            <div class="style-control-card">
                                <div class="style-card-header">
                                    <i class="fa-solid fa-palette"></i> Text Color
                                </div>
                                <?php $currTextColor = $settings['announcement_bar_text_color'] ?? '#444444'; ?>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="color" id="announcement-text-color-picker" value="<?= esc($currTextColor) ?>" oninput="syncColorInput(this, 'announcement-text-color-input'); updateAnnouncementPreview();" style="width: 42px; height: 38px; border: 1.5px solid #cbd5e1; border-radius: 8px; cursor: pointer; padding: 2px; background: #fff;">
                                    <input type="text" id="announcement-text-color-input" name="announcement_bar_text_color" value="<?= esc($currTextColor) ?>" class="premium-input" oninput="syncColorPicker(this, 'announcement-text-color-picker'); updateAnnouncementPreview();" style="flex: 1; padding: 8px 10px; font-size: 13px; font-family: monospace; font-weight: 700;">
                                </div>
                                <div class="color-swatches-row">
                                    <span class="color-swatch-dot" style="background: #444444;" title="Default Charcoal (#444444)" onclick="setAnnouncementColor('#444444', 'text')"></span>
                                    <span class="color-swatch-dot" style="background: #ffffff;" title="Pure White (#ffffff)" onclick="setAnnouncementColor('#ffffff', 'text')"></span>
                                    <span class="color-swatch-dot" style="background: #e11d48;" title="Royal Maroon (#e11d48)" onclick="setAnnouncementColor('#e11d48', 'text')"></span>
                                    <span class="color-swatch-dot" style="background: #d97706;" title="Warm Amber (#d97706)" onclick="setAnnouncementColor('#d97706', 'text')"></span>
                                    <span class="color-swatch-dot" style="background: #0f172a;" title="Slate Navy (#0f172a)" onclick="setAnnouncementColor('#0f172a', 'text')"></span>
                                </div>
                            </div>

                            <!-- 4. Background Color Selection -->
                            <div class="style-control-card">
                                <div class="style-card-header">
                                    <i class="fa-solid fa-fill-drip"></i> Background Color
                                </div>
                                <?php $currBgColor = $settings['announcement_bar_bg_color'] ?? '#e2e2e2'; ?>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="color" id="announcement-bg-color-picker" value="<?= esc($currBgColor) ?>" oninput="syncColorInput(this, 'announcement-bg-color-input'); updateAnnouncementPreview();" style="width: 42px; height: 38px; border: 1.5px solid #cbd5e1; border-radius: 8px; cursor: pointer; padding: 2px; background: #fff;">
                                    <input type="text" id="announcement-bg-color-input" name="announcement_bar_bg_color" value="<?= esc($currBgColor) ?>" class="premium-input" oninput="syncColorPicker(this, 'announcement-bg-color-picker'); updateAnnouncementPreview();" style="flex: 1; padding: 8px 10px; font-size: 13px; font-family: monospace; font-weight: 700;">
                                </div>
                                <div class="color-swatches-row">
                                    <span class="color-swatch-dot" style="background: #e2e2e2;" title="Default Light Gray (#e2e2e2)" onclick="setAnnouncementColor('#e2e2e2', 'bg')"></span>
                                    <span class="color-swatch-dot" style="background: #e11d48;" title="Royal Maroon (#e11d48)" onclick="setAnnouncementColor('#e11d48', 'bg')"></span>
                                    <span class="color-swatch-dot" style="background: #fff5f7;" title="Pastel Rose (#fff5f7)" onclick="setAnnouncementColor('#fff5f7', 'bg')"></span>
                                    <span class="color-swatch-dot" style="background: #1e293b;" title="Midnight Slate (#1e293b)" onclick="setAnnouncementColor('#1e293b', 'bg')"></span>
                                    <span class="color-swatch-dot" style="background: #fef3c7;" title="Festive Gold (#fef3c7)" onclick="setAnnouncementColor('#fef3c7', 'bg')"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Storefront Mockup Live Preview -->
                        <div>
                            <label class="form-label" style="font-size: 12px; font-weight: 800; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.6px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-desktop" style="color: #e11d48;"></i> Real-Time Storefront Header Preview
                            </label>
                            
                            <div class="storefront-preview-frame">
                                <div class="preview-browser-bar">
                                    <div class="preview-browser-dots">
                                        <span class="preview-dot red"></span>
                                        <span class="preview-dot yellow"></span>
                                        <span class="preview-dot green"></span>
                                    </div>
                                    <span class="preview-browser-url"><i class="fa-solid fa-lock" style="font-size: 9px; margin-right: 5px;"></i> yourstore.com</span>
                                    <button type="button" id="btn-pause-marquee-preview" onclick="togglePreviewMarqueePause()" style="background: #334155; color: #e2e8f0; border: none; font-size: 11px; padding: 3px 10px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-pause"></i> Pause Marquee
                                    </button>
                                </div>

                                <div id="announcement-live-preview-box" class="preview-marquee-wrapper" style="background-color: <?= esc($currBgColor) ?>; color: <?= esc($currTextColor) ?>; font-size: <?= esc($currFontSize) ?>; font-weight: <?= ($settings['announcement_bar_bold'] ?? '1') === '1' ? '700' : '500' ?>;">
                                    <div id="announcement-live-preview-text" class="preview-marquee-track">
                                        <?= esc($settings['announcement_bar_text'] ?? 'Free Shipping above Rs.1000/- Purchases.       Hurry Most Beautiful collections On its way!       Diwali Sale is Live       Free Shipping above Rs.1000/- Purchases.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Section Save Footer -->
                        <div class="section-card-footer">
                            <span class="section-footer-info">
                                <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Changes apply instantly to top header of landing page & storefront
                            </span>
                            <button type="submit" class="btn-section-save">
                                <i class="fa-solid fa-floppy-disk"></i> Save Announcement Bar
                            </button>
                        </div>
                    </form>
                </div>


                <!-- ==========================================================================
                     SECTION 2: PAGE BANNERS & TEXT OVERLAYS
                     ========================================================================== -->
                <div class="settings-section-card" id="card-page-banners">
                    <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                        <input type="hidden" name="section_name" value="page_banners">
                        <input type="hidden" name="banner_section_submitted" value="1">
                        <input type="hidden" name="active_tab_redirect" value="display">

                        <!-- Section Header -->
                        <div class="section-card-header">
                            <div class="section-card-header-left">
                                <div class="section-icon-badge indigo-gradient">
                                    <i class="fa-solid fa-images"></i>
                                </div>
                                <div>
                                    <h3 class="section-card-title">
                                        Page Banners & Text Overlays
                                        <span class="badge-pill-header">Multi-Page Manager</span>
                                    </h3>
                                    <p class="section-card-desc">Upload high-resolution header banners and configure luxury quote overlays, headings, and highlight subtitles for your collection & category pages.</p>
                                </div>
                            </div>
                        </div>

                        <?php 
                            $allHeaderCats = isset($settings['header_categories']) ? json_decode($settings['header_categories'], true) : [];
                            if (!is_array($allHeaderCats)) $allHeaderCats = [];
                            
                            $combinedCats = [];
                            foreach ($allHeaderCats as $hcat) {
                                $hName = is_array($hcat) ? ($hcat['name'] ?? '') : $hcat;
                                $hName = trim($hName);
                                if (!empty($hName)) {
                                    $combinedCats[url_title($hName, '-', true)] = $hName;
                                }
                            }
                        ?>

                        <!-- Interactive Segmented Page Switcher Navigation -->
                        <div class="page-switcher-nav">
                            <button type="button" class="page-switcher-btn active" onclick="switchBannerPageTab('new_arrivals', this)">
                                <span>✨ New Arrivals</span>
                            </button>
                            <button type="button" class="page-switcher-btn" onclick="switchBannerPageTab('best_sellers', this)">
                                <span>🔥 Best Sellers</span>
                            </button>
                            <button type="button" class="page-switcher-btn" onclick="switchBannerPageTab('all_collections', this)">
                                <span>🗂️ All Collections</span>
                            </button>
                            <button type="button" class="page-switcher-btn" onclick="switchBannerPageTab('contact_us', this)">
                                <span>📞 Contact Us</span>
                            </button>
                            <?php if(!empty($combinedCats)): ?>
                            <div style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
                                <label style="font-size: 12px; font-weight: 700; color: #64748b;"><i class="fa-solid fa-tags" style="color: #e11d48;"></i> Category Banners:</label>
                                <select class="premium-input" onchange="switchBannerPageTab(this.value, null)" style="width: auto; padding: 7px 12px; font-size: 12.5px; font-weight: 700; background: #fff; cursor: pointer;">
                                    <option value="">-- Choose Category --</option>
                                    <?php foreach($combinedCats as $cSlug => $cName): ?>
                                        <option value="cat_<?= esc($cSlug) ?>">🏷️ <?= esc($cName) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Hidden synced select element for JS backwards-compatibility -->
                        <select id="banner-page-selector" style="display: none;" onchange="switchBannerPageConfig(this.value)">
                            <optgroup label="Main Collection Pages">
                                <option value="new_arrivals">✨ New Arrivals</option>
                                <option value="best_sellers">🔥 Best Sellers</option>
                                <option value="all_collections">🗂️ All Collections</option>
                                <option value="contact_us">📞 Contact Us</option>
                            </optgroup>
                            <?php if(!empty($combinedCats)): ?>
                            <optgroup label="Categories">
                                <?php foreach($combinedCats as $cSlug => $cName): ?>
                                    <option value="cat_<?= esc($cSlug) ?>">🏷️ <?= esc($cName) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                        </select>

                        <!-- 1. NEW ARRIVALS CONFIG PANEL -->
                        <div id="banner-config-new_arrivals" class="banner-page-panel" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 25px;">
                            <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <h3 style="font-size: 16px; font-weight: 800; color: #e11d48; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-sparkles"></i> New Arrivals Page Banners & Text Overlay
                                </h3>
                                <span class="page-panel-badge-url">URL: /collections/new-arrivals</span>
                            </div>

                            <!-- Image Uploader Section -->
                            <div class="form-group" style="margin-bottom: 22px;">
                                <label class="form-label" style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                    <i class="fa-regular fa-images" style="color: #e11d48; margin-right: 6px;"></i> Upload Banner Images
                                </label>
                                <div class="premium-file-upload" style="padding: 18px;">
                                    <div class="upload-icon" style="font-size: 24px; margin-bottom: 6px;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                    <div class="upload-text" style="font-size: 13px; font-weight: 600;">Choose / Drop Multiple Images (JPG, PNG, WEBP)</div>
                                    <input type="file" name="new_arrivals_banner[]" accept="image/*" multiple onchange="previewNewBannerUploads(this, 'preview-new-arrivals')">
                                </div>
                                <div id="preview-new-arrivals" style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;"></div>
                                
                                <?php if(!empty($settings['new_arrivals_banner'])): 
                                    $banners = json_decode($settings['new_arrivals_banner'], true);
                                    if(!is_array($banners)) $banners = [$settings['new_arrivals_banner']];
                                ?>
                                    <div style="display: flex; gap: 12px; margin-top: 14px; flex-wrap: wrap;">
                                        <?php foreach($banners as $bIndex => $b): if(empty($b)) continue; ?>
                                        <div class="banner-preview-box" style="position: relative; width: 120px; height: 75px; border-radius: 10px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                                            <input type="hidden" name="existing_new_arrivals_banner[]" value="<?= esc($b) ?>">
                                            <img src="<?= base_url($b) ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                                            <span style="position: absolute; bottom: 3px; left: 4px; background: rgba(15, 23, 42, 0.75); color: #fff; font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">#<?= $bIndex + 1 ?></span>
                                            <button type="button" onclick="this.closest('.banner-preview-box').remove()" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.95); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <p style="font-size: 11.5px; color: #64748b; margin-top: 8px;">Upload additional images or click <i class="fa-solid fa-xmark" style="color: #dc2626;"></i> to remove individual banners.</p>
                            </div>

                            <!-- Text Overlay Section -->
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                                    <div>
                                        <label class="form-label" style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 2px;">
                                            <i class="fa-solid fa-quote-left" style="color: #e11d48; margin-right: 6px;"></i> Banner Text Overlay
                                        </label>
                                        <p style="font-size: 12px; color: #64748b; margin: 0;">Show quote & heading over this page's banner. Toggle off for clean images only.</p>
                                    </div>
                                    <label class="switch">
                                        <input type="hidden" name="banner_text_overlay_enabled" value="0">
                                        <input type="checkbox" name="banner_text_overlay_enabled" value="1" <?= ($settings['banner_text_overlay_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 14px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Left Quote Text</label>
                                        <input type="text" name="banner_quote_text" class="premium-input" placeholder='e.g. "A Good life is a Collection of HAPPY MEMORIES"' value="<?= esc($settings['banner_quote_text'] ?? '"A Good life is a Collection of HAPPY MEMORIES"') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Heading Text</label>
                                        <input type="text" name="banner_heading_text" class="premium-input" placeholder="e.g. Best Collection For Ever" value="<?= esc($settings['banner_heading_text'] ?? 'Best Collection For Ever') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0; grid-column: span 2;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Subtitle / Highlight</label>
                                        <input type="text" name="banner_subtitle_text" class="premium-input" placeholder="e.g. ELAMPILLAI PATTU" value="<?= esc($settings['banner_subtitle_text'] ?? 'ELAMPILLAI PATTU') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. BEST SELLERS CONFIG PANEL -->
                        <div id="banner-config-best_sellers" class="banner-page-panel" style="display: none; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 25px;">
                            <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <h3 style="font-size: 16px; font-weight: 800; color: #e11d48; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-fire"></i> Best Sellers Page Banners & Text Overlay
                                </h3>
                                <span class="page-panel-badge-url">URL: /collections/best-sellers</span>
                            </div>

                            <!-- Image Uploader Section -->
                            <div class="form-group" style="margin-bottom: 22px;">
                                <label class="form-label" style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                    <i class="fa-regular fa-images" style="color: #e11d48; margin-right: 6px;"></i> Upload Banner Images
                                </label>
                                <div class="premium-file-upload" style="padding: 18px;">
                                    <div class="upload-icon" style="font-size: 24px; margin-bottom: 6px;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                    <div class="upload-text" style="font-size: 13px; font-weight: 600;">Choose / Drop Multiple Images (JPG, PNG, WEBP)</div>
                                    <input type="file" name="best_sellers_banner[]" accept="image/*" multiple onchange="previewNewBannerUploads(this, 'preview-best-sellers')">
                                </div>
                                <div id="preview-best-sellers" style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;"></div>
                                
                                <?php if(!empty($settings['best_sellers_banner'])): 
                                    $banners = json_decode($settings['best_sellers_banner'], true);
                                    if(!is_array($banners)) $banners = [$settings['best_sellers_banner']];
                                ?>
                                    <div style="display: flex; gap: 12px; margin-top: 14px; flex-wrap: wrap;">
                                        <?php foreach($banners as $bIndex => $b): if(empty($b)) continue; ?>
                                        <div class="banner-preview-box" style="position: relative; width: 120px; height: 75px; border-radius: 10px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                                            <input type="hidden" name="existing_best_sellers_banner[]" value="<?= esc($b) ?>">
                                            <img src="<?= base_url($b) ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                                            <span style="position: absolute; bottom: 3px; left: 4px; background: rgba(15, 23, 42, 0.75); color: #fff; font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">#<?= $bIndex + 1 ?></span>
                                            <button type="button" onclick="this.closest('.banner-preview-box').remove()" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.95); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <p style="font-size: 11.5px; color: #64748b; margin-top: 8px;">Upload additional images or click <i class="fa-solid fa-xmark" style="color: #dc2626;"></i> to remove individual banners.</p>
                            </div>

                            <!-- Text Overlay Section -->
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                                    <div>
                                        <label class="form-label" style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 2px;">
                                            <i class="fa-solid fa-quote-left" style="color: #e11d48; margin-right: 6px;"></i> Banner Text Overlay
                                        </label>
                                        <p style="font-size: 12px; color: #64748b; margin: 0;">Show quote & heading over this page's banner. Toggle off for clean images only.</p>
                                    </div>
                                    <label class="switch">
                                        <input type="hidden" name="bestsellers_text_overlay_enabled" value="0">
                                        <input type="checkbox" name="bestsellers_text_overlay_enabled" value="1" <?= ($settings['bestsellers_text_overlay_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 14px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Left Quote Text</label>
                                        <input type="text" name="bestsellers_quote_text" class="premium-input" placeholder='e.g. "Timeless Elegance Loved by Thousands"' value="<?= esc($settings['bestsellers_quote_text'] ?? '') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Heading Text</label>
                                        <input type="text" name="bestsellers_heading_text" class="premium-input" placeholder="e.g. Most Popular Picks" value="<?= esc($settings['bestsellers_heading_text'] ?? '') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0; grid-column: span 2;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Subtitle / Highlight</label>
                                        <input type="text" name="bestsellers_subtitle_text" class="premium-input" placeholder="e.g. TOP RATED SAREES" value="<?= esc($settings['bestsellers_subtitle_text'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. ALL COLLECTIONS CONFIG PANEL -->
                        <div id="banner-config-all_collections" class="banner-page-panel" style="display: none; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 25px;">
                            <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <h3 style="font-size: 16px; font-weight: 800; color: #e11d48; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-layer-group"></i> All Collections Page Banners & Text Overlay
                                </h3>
                                <span class="page-panel-badge-url">URL: /collections</span>
                            </div>

                            <!-- Image Uploader Section -->
                            <div class="form-group" style="margin-bottom: 22px;">
                                <label class="form-label" style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                    <i class="fa-regular fa-images" style="color: #e11d48; margin-right: 6px;"></i> Upload Banner Images
                                </label>
                                <div class="premium-file-upload" style="padding: 18px;">
                                    <div class="upload-icon" style="font-size: 24px; margin-bottom: 6px;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                    <div class="upload-text" style="font-size: 13px; font-weight: 600;">Choose / Drop Multiple Images (JPG, PNG, WEBP)</div>
                                    <input type="file" name="collections_banner[]" accept="image/*" multiple onchange="previewNewBannerUploads(this, 'preview-collections')">
                                </div>
                                <div id="preview-collections" style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;"></div>
                                
                                <?php if(!empty($settings['collections_banner'])): 
                                    $banners = json_decode($settings['collections_banner'], true);
                                    if(!is_array($banners)) $banners = [$settings['collections_banner']];
                                ?>
                                    <div style="display: flex; gap: 12px; margin-top: 14px; flex-wrap: wrap;">
                                        <?php foreach($banners as $bIndex => $b): if(empty($b)) continue; ?>
                                        <div class="banner-preview-box" style="position: relative; width: 120px; height: 75px; border-radius: 10px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                                            <input type="hidden" name="existing_collections_banner[]" value="<?= esc($b) ?>">
                                            <img src="<?= base_url($b) ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                                            <span style="position: absolute; bottom: 3px; left: 4px; background: rgba(15, 23, 42, 0.75); color: #fff; font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">#<?= $bIndex + 1 ?></span>
                                            <button type="button" onclick="this.closest('.banner-preview-box').remove()" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.95); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <p style="font-size: 11.5px; color: #64748b; margin-top: 8px;">Upload additional images or click <i class="fa-solid fa-xmark" style="color: #dc2626;"></i> to remove individual banners.</p>
                            </div>

                            <!-- Text Overlay Section -->
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                                    <div>
                                        <label class="form-label" style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 2px;">
                                            <i class="fa-solid fa-quote-left" style="color: #e11d48; margin-right: 6px;"></i> Banner Text Overlay
                                        </label>
                                        <p style="font-size: 12px; color: #64748b; margin: 0;">Show quote & heading over this page's banner. Toggle off for clean images only.</p>
                                    </div>
                                    <label class="switch">
                                        <input type="hidden" name="collections_text_overlay_enabled" value="0">
                                        <input type="checkbox" name="collections_text_overlay_enabled" value="1" <?= ($settings['collections_text_overlay_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 14px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Left Quote Text</label>
                                        <input type="text" name="collections_quote_text" class="premium-input" placeholder='e.g. "Discover Handcrafted Saree Traditions"' value="<?= esc($settings['collections_quote_text'] ?? '') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Heading Text</label>
                                        <input type="text" name="collections_heading_text" class="premium-input" placeholder="e.g. Complete Wardrobe" value="<?= esc($settings['collections_heading_text'] ?? '') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0; grid-column: span 2;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Subtitle / Highlight</label>
                                        <input type="text" name="collections_subtitle_text" class="premium-input" placeholder="e.g. EXPLORE ALL" value="<?= esc($settings['collections_subtitle_text'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. CONTACT US CONFIG PANEL -->
                        <div id="banner-config-contact_us" class="banner-page-panel" style="display: none; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 25px;">
                            <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <h3 style="font-size: 16px; font-weight: 800; color: #e11d48; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-envelope-open-text"></i> Contact Us Page Banners &amp; Text Overlay
                                </h3>
                                <span class="page-panel-badge-url">URL: /contact-us</span>
                            </div>

                            <!-- Image Uploader Section -->
                            <div class="form-group" style="margin-bottom: 22px;">
                                <label class="form-label" style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                    <i class="fa-regular fa-images" style="color: #e11d48; margin-right: 6px;"></i> Upload Banner Images for Contact Us
                                </label>
                                <div class="premium-file-upload" style="padding: 18px;">
                                    <div class="upload-icon" style="font-size: 24px; margin-bottom: 6px;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                    <div class="upload-text" style="font-size: 13px; font-weight: 600;">Choose / Drop Multiple Images (JPG, PNG, WEBP)</div>
                                    <input type="file" name="contact_us_banner[]" accept="image/*" multiple onchange="previewNewBannerUploads(this, 'preview-contact-us')">
                                </div>
                                <div id="preview-contact-us" style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;"></div>
                                
                                <?php if(!empty($settings['contact_us_banner'])): 
                                    $contactBanners = json_decode($settings['contact_us_banner'], true);
                                    if(!is_array($contactBanners)) $contactBanners = [$settings['contact_us_banner']];
                                ?>
                                    <div style="display: flex; gap: 12px; margin-top: 14px; flex-wrap: wrap;">
                                        <?php foreach($contactBanners as $bIndex => $b): if(empty($b)) continue; ?>
                                        <div class="banner-preview-box" style="position: relative; width: 120px; height: 75px; border-radius: 10px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                                            <input type="hidden" name="existing_contact_us_banner[]" value="<?= esc($b) ?>">
                                            <img src="<?= base_url($b) ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                                            <span style="position: absolute; bottom: 3px; left: 4px; background: rgba(15, 23, 42, 0.75); color: #fff; font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">#<?= $bIndex + 1 ?></span>
                                            <button type="button" onclick="this.closest('.banner-preview-box').remove()" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.95); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <p style="font-size: 11.5px; color: #64748b; margin-top: 8px;">Upload additional images or click <i class="fa-solid fa-xmark" style="color: #dc2626;"></i> to remove individual banners.</p>
                            </div>

                            <!-- Text Overlay Section -->
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                                    <div>
                                        <label class="form-label" style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 2px;">
                                            <i class="fa-solid fa-quote-left" style="color: #e11d48; margin-right: 6px;"></i> Banner Text Overlay
                                        </label>
                                        <p style="font-size: 12px; color: #64748b; margin: 0;">Show quote &amp; heading over this page's banner. Toggle off for clean images only.</p>
                                    </div>
                                    <label class="switch">
                                        <input type="hidden" name="contact_us_text_overlay_enabled" value="0">
                                        <input type="checkbox" name="contact_us_text_overlay_enabled" value="1" <?= ($settings['contact_us_text_overlay_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 14px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Left Quote Text</label>
                                        <input type="text" name="contact_us_quote_text" class="premium-input" placeholder='e.g. "We Weave Authentic Relationships"' value="<?= esc($settings['contact_us_quote_text'] ?? '"We Weave Authentic Relationships"') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Heading Text</label>
                                        <input type="text" name="contact_us_heading_text" class="premium-input" placeholder="e.g. Get in Touch with Selvi Tex" value="<?= esc($settings['contact_us_heading_text'] ?? 'Get in Touch with Selvi Tex') ?>">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0; grid-column: span 2;">
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Subtitle / Highlight</label>
                                        <input type="text" name="contact_us_subtitle_text" class="premium-input" placeholder="e.g. ELAMPILLAI WEAVING SHOWROOM & SUPPORT" value="<?= esc($settings['contact_us_subtitle_text'] ?? 'ELAMPILLAI WEAVING SHOWROOM & SUPPORT') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. DYNAMIC CATEGORY CONFIG PANELS -->
                        <?php if(!empty($combinedCats)): ?>
                            <?php foreach($combinedCats as $catSlug => $catName): 
                                $catField = 'category_banner_' . $catSlug;
                                $catBanners = isset($settings[$catField]) ? json_decode($settings[$catField], true) : [];
                                if (!is_array($catBanners)) $catBanners = !empty($settings[$catField]) ? [$settings[$catField]] : [];
                            ?>
                            <div id="banner-config-cat_<?= esc($catSlug) ?>" class="banner-page-panel" style="display: none; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 25px;">
                                <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                    <h3 style="font-size: 16px; font-weight: 800; color: #e11d48; margin: 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="fa-solid fa-tag"></i> <?= esc($catName) ?> Page Banners & Text Overlay
                                    </h3>
                                    <span class="page-panel-badge-url">URL: /category/<?= esc($catSlug) ?></span>
                                </div>

                                <!-- Image Uploader Section -->
                                <div class="form-group" style="margin-bottom: 22px;">
                                    <label class="form-label" style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                        <i class="fa-regular fa-images" style="color: #e11d48; margin-right: 6px;"></i> Upload Banner Images for <?= esc($catName) ?>
                                    </label>
                                    <div class="premium-file-upload" style="padding: 18px;">
                                        <div class="upload-icon" style="font-size: 24px; margin-bottom: 6px;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                        <div class="upload-text" style="font-size: 13px; font-weight: 600;">Choose / Drop Multiple Images (JPG, PNG, WEBP)</div>
                                        <input type="file" name="<?= esc($catField) ?>[]" accept="image/*" multiple onchange="previewNewBannerUploads(this, 'preview-cat-<?= esc($catSlug) ?>')">
                                    </div>
                                    <div id="preview-cat-<?= esc($catSlug) ?>" style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;"></div>
                                    
                                    <?php if(!empty($catBanners)): ?>
                                        <div style="display: flex; gap: 12px; margin-top: 14px; flex-wrap: wrap;">
                                            <?php foreach($catBanners as $bIndex => $b): if(empty($b)) continue; ?>
                                            <div class="banner-preview-box" style="position: relative; width: 120px; height: 75px; border-radius: 10px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                                                <input type="hidden" name="existing_<?= esc($catField) ?>[]" value="<?= esc($b) ?>">
                                                <img src="<?= base_url($b) ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                                                <span style="position: absolute; bottom: 3px; left: 4px; background: rgba(15, 23, 42, 0.75); color: #fff; font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">#<?= $bIndex + 1 ?></span>
                                                <button type="button" onclick="this.closest('.banner-preview-box').remove()" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.95); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <p style="font-size: 11.5px; color: #64748b; margin-top: 8px;">Upload additional images or click <i class="fa-solid fa-xmark" style="color: #dc2626;"></i> to remove individual banners.</p>
                                </div>

                                <!-- Text Overlay Section -->
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                                        <div>
                                            <label class="form-label" style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 2px;">
                                                <i class="fa-solid fa-quote-left" style="color: #e11d48; margin-right: 6px;"></i> Banner Text Overlay for <?= esc($catName) ?>
                                            </label>
                                            <p style="font-size: 12px; color: #64748b; margin: 0;">Show quote & heading over <?= esc($catName) ?>'s banner. Toggle off for clean images only.</p>
                                        </div>
                                        <label class="switch">
                                            <input type="hidden" name="category_text_overlay_enabled_<?= esc($catSlug) ?>" value="0">
                                            <input type="checkbox" name="category_text_overlay_enabled_<?= esc($catSlug) ?>" value="1" <?= ($settings['category_text_overlay_enabled_' . $catSlug] ?? '1') === '1' ? 'checked' : '' ?>>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 14px;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 12px; font-weight: 600;">Left Quote Text</label>
                                            <input type="text" name="category_quote_text_<?= esc($catSlug) ?>" class="premium-input" placeholder='e.g. "A Good life is a Collection of HAPPY MEMORIES"' value="<?= esc($settings['category_quote_text_' . $catSlug] ?? '"A Good life is a Collection of HAPPY MEMORIES"') ?>">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Heading Text</label>
                                            <input type="text" name="category_heading_text_<?= esc($catSlug) ?>" class="premium-input" placeholder="e.g. <?= esc($catName) ?>" value="<?= esc($settings['category_heading_text_' . $catSlug] ?? $catName) ?>">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0; grid-column: span 2;">
                                            <label class="form-label" style="font-size: 12px; font-weight: 600;">Right Subtitle / Highlight</label>
                                            <input type="text" name="category_subtitle_text_<?= esc($catSlug) ?>" class="premium-input" placeholder="e.g. Exclusive Collection" value="<?= esc($settings['category_subtitle_text_' . $catSlug] ?? 'Exclusive Collection') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Individual Section Save Footer -->
                        <div class="section-card-footer">
                            <span class="section-footer-info">
                                <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates banner carousel images and quote overlays across collection pages
                            </span>
                            <button type="submit" class="btn-section-save">
                                <i class="fa-solid fa-floppy-disk"></i> Save Page Banners
                            </button>
                        </div>
                    </form>
                </div>


                <!-- ==========================================================================
                     SECTION 3: CATEGORIES IN HEADER NAVIGATION
                     ========================================================================== -->
                <div class="settings-section-card" id="card-header-categories">
                    <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                        <input type="hidden" name="section_name" value="header_categories">
                        <input type="hidden" name="header_categories_submitted" value="1">
                        <input type="hidden" name="active_tab_redirect" value="display">

                        <?php 
                            $selectedCats = isset($settings['header_categories']) ? json_decode($settings['header_categories'], true) : [];
                            if (!is_array($selectedCats)) $selectedCats = [];
                        ?>

                        <!-- Section Header -->
                        <div class="section-card-header">
                            <div class="section-card-header-left">
                                <div class="section-icon-badge teal-gradient">
                                    <i class="fa-solid fa-bars-staggered"></i>
                                </div>
                                <div>
                                    <h3 class="section-card-title">
                                        Categories in Header Navigation
                                        <span class="badge-pill-header">Top Navigation Bar</span>
                                    </h3>
                                    <p class="section-card-desc">Define and order the main category links displayed in your storefront top navigation bar. Each row allows selecting an existing store category or entering a custom name.</p>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                <span class="badge-count" id="header-cat-count-badge"><?= count($selectedCats) ?> Categories</span>
                                <button type="button" id="add-header-cat-btn" class="btn-add" style="background: #0d9488; color: #ffffff; border: none; font-weight: 700; padding: 9px 16px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(13, 148, 136, 0.25);">
                                    <i class="fa-solid fa-plus"></i> Add Category
                                </button>
                                <input type="file" id="bulk-category-upload" multiple accept="image/*" style="display: none;" onchange="handleBulkCategoryUpload(this)">
                                <button type="button" class="btn-add" onclick="document.getElementById('bulk-category-upload').click()" style="background: #fff5f7; border: 1.5px solid #e11d48; color: #e11d48; font-weight: 700; padding: 9px 16px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-images"></i> Bulk Images
                                </button>
                            </div>
                        </div>
                        
                        <!-- Categories Table -->
                        <div class="cat-manager-table-wrapper" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #ffffff; margin-bottom: 20px;">
                            <table class="cat-manager-table" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0;">
                                        <th style="width: 70px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 10px;">Order</th>
                                        <th style="width: 105px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 10px;">Icon / Image</th>
                                        <th style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 16px;">Header Category Name</th>
                                        <th style="width: 80px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 10px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="header-categories-container">
                                    <?php foreach($selectedCats as $index => $catData): 
                                        $catName = is_array($catData) ? ($catData['name'] ?? '') : $catData;
                                        $catImage = is_array($catData) ? ($catData['image'] ?? '') : '';
                                    ?>
                                        <tr class="header-cat-row">
                                            <td style="text-align: center; vertical-align: middle;">
                                                <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                                    <i class="fa-solid fa-grip-vertical cat-row-handle" title="Category Position" style="color: #94a3b8; cursor: grab;"></i>
                                                    <span class="cat-row-index" style="font-weight: 700; font-size: 13px; color: #e11d48; background: #fff5f7; width: 28px; height: 28px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;"><?= $index + 1 ?></span>
                                                </div>
                                            </td>
                                            <td style="text-align: center; vertical-align: middle; padding: 8px 10px;">
                                                <div class="cat-icon-uploader-cell" style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                                    <div class="cat-icon-preview-box" onclick="this.nextElementSibling.click()" style="width: 44px; height: 44px; border-radius: 8px; border: 1.5px dashed #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; position: relative;" title="Click to upload/change icon">
                                                        <?php if(!empty($catImage)): ?>
                                                            <img src="<?= base_url(ltrim($catImage, '/')) ?>" alt="Icon" class="cat-thumb-preview" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php else: ?>
                                                            <i class="fa-solid fa-image" style="color: #94a3b8; font-size: 18px;"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <input type="file" name="header_categories_image[]" accept="image/*" style="display: none;" onchange="previewCatRowIcon(this)">
                                                    <span style="font-size: 10px; color: #64748b; font-weight: 600; cursor: pointer;" onclick="this.previousElementSibling.click()">Upload</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align: middle; padding: 10px 16px;">
                                                <div style="position: relative; width: 100%;">
                                                    <input type="text" name="header_categories_name[]" class="premium-input header-cat-text-input" list="store-categories-list" value="<?= esc($catName) ?>" placeholder="Enter category name (e.g. Silk Sarees, Cotton, Pudaivai...)" style="width: 100%; font-size: 14px; font-weight: 600; padding-left: 38px;">
                                                    <i class="fa-solid fa-tag" style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #0d9488; font-size: 14px; pointer-events: none;"></i>
                                                </div>
                                                <input type="hidden" name="header_categories_original_name[]" value="<?= esc($catName) ?>">
                                                <input type="hidden" name="header_categories_old_image[]" class="cat-old-image-input" value="<?= esc($catImage) ?>">
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <button type="button" class="btn-danger-icon remove-header-cat-btn" title="Remove Category">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Datalist providing suggestions from store categories as admin types -->
                        <datalist id="store-categories-list">
                            <?php if(!empty($categories)): foreach($categories as $c): ?>
                                <option value="<?= esc($c['name']) ?>"></option>
                            <?php endforeach; endif; ?>
                        </datalist>

                        <!-- Individual Section Save Footer -->
                        <div class="section-card-footer">
                            <span class="section-footer-info">
                                <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates header navbar links & auto-creates missing store categories
                            </span>
                            <button type="submit" class="btn-section-save">
                                <i class="fa-solid fa-floppy-disk"></i> Save Header Categories
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SEO & Analytics Tab -->
            <div id="tab-seo" class="settings-section">
                <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                    <input type="hidden" name="section_name" value="seo">
                    <input type="hidden" name="active_tab_redirect" value="seo">

                    <?php 
                        $seoSettings = isset($settings['seo_pages']) ? json_decode($settings['seo_pages'], true) : [];
                        if (!is_array($seoSettings)) $seoSettings = [];
                    ?>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 16px; color: #1e293b; margin-bottom: 20px;">Google Analytics</label>
                        <p style="font-size: 13px; color: #64748b; margin-bottom: 10px; line-height: 1.5;">Enter your Google Analytics Measurement ID (e.g. G-XXXXXXXXXX) or the full tracking snippet.</p>
                        <textarea name="google_analytics" class="premium-input" rows="4" placeholder="Paste Google Analytics code here..."><?= esc($settings['google_analytics'] ?? '') ?></textarea>
                    </div>

                    <div class="section-divider"></div>

                    <div class="form-group">
                        <label class="form-label" style="font-size: 16px; color: #1e293b; margin-bottom: 20px;">Page-wise SEO Settings</label>
                        <p style="font-size: 13px; color: #64748b; margin-bottom: 20px; line-height: 1.5;">Select a page below to configure its SEO metadata. This includes your custom categories.</p>
                        
                        <?php 
                            $pagesList = [
                                'home' => 'Home Page',
                                'new_arrivals' => 'New Arrivals',
                                'best_sellers' => 'Best Sellers',
                                'collections' => 'All Collections',
                            ];
                            if (isset($categories) && is_array($categories)) {
                                foreach($categories as $cat) {
                                    $pagesList[$cat['slug']] = $cat['name'];
                                }
                            }
                        ?>
                        
                        <div class="form-group">
                            <select id="seo-page-selector" class="premium-input" onchange="showSeoPage(this.value)" style="cursor: pointer; background-color: #fff;">
                                <?php foreach($pagesList as $key => $name): ?>
                                    <option value="<?= $key ?>"><?= esc($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                            <?php foreach($pagesList as $key => $name): ?>
                                <div class="seo-page-config" id="seo-config-<?= $key ?>" style="display: <?= $key === 'home' ? 'block' : 'none' ?>;">
                                    <h4 style="font-size: 15px; color: #e11d48; margin-top: 0; margin-bottom: 15px;">Configure SEO for: <?= esc($name) ?></h4>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" name="seo_pages[<?= $key ?>][title]" class="premium-input" value="<?= esc($seoSettings[$key]['title'] ?? '') ?>">
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Meta Description</label>
                                            <textarea name="seo_pages[<?= $key ?>][description]" class="premium-input" rows="3"><?= esc($seoSettings[$key]['description'] ?? '') ?></textarea>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Meta Keywords (comma separated)</label>
                                            <textarea name="seo_pages[<?= $key ?>][keywords]" class="premium-input" rows="3"><?= esc($seoSettings[$key]['keywords'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="section-card-footer">
                        <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates meta tags, Google Analytics and page-wise SEO</span>
                        <button type="submit" class="btn-section-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save SEO & Analytics
                        </button>
                    </div>
                </form>
            </div>

                <!-- Homepage Tab -->
                <div id="tab-homepage" class="settings-section">

                    <!-- ==========================================================================
                         HOMEPAGE SECTION SWITCHER DROPDOWN & QUICK NAVIGATION
                         ========================================================================== -->
                    <div class="homepage-section-filter-card">
                        <!-- Top Header Row -->
                        <div class="filter-card-header-row">
                            <div class="filter-card-content">
                                <div class="filter-card-icon">
                                    <i class="fa-solid fa-house-chimney"></i>
                                </div>
                                <div>
                                    <h3 class="filter-card-title">
                                        Home Page Section Manager
                                        <span class="filter-card-tag">9 Active Sections</span>
                                    </h3>
                                    <p class="filter-card-desc">
                                        Quickly jump to configure any storefront section or view all 9 sections together.
                                    </p>
                                </div>
                            </div>

                            <div class="filter-dropdown-wrapper">
                                <i class="fa-solid fa-sliders filter-dropdown-icon"></i>
                                <select id="homepage-section-select" class="homepage-select-dropdown" onchange="switchHomepageSection(this.value)">
                                    <option value="all">🌐 Show All 9 Homepage Sections</option>
                                    <option value="homepage_trust" selected>🏛️ 1. Establishment &amp; Trust Highlights</option>
                                    <option value="homepage_live">🔴 2. Live Collections Section</option>
                                    <option value="homepage_hero">🖼️ 3. Hero Banners &amp; Sliders</option>
                                    <option value="homepage_budget">💰 4. Shop by Budget Section</option>
                                    <option value="homepage_diwali">✨ 5. Diwali Festive Section</option>
                                    <option value="homepage_shorts">🎬 6. Social Media &amp; Channel Tabs</option>
                                    <option value="homepage_why">👑 7. Why Choose SSPTex Section</option>
                                    <option value="homepage_must_visit">🧭 8. Must Visit Section</option>
                                    <option value="homepage_about">🏬 9. About SSSTex &amp; Visit Showroom</option>
                                </select>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="filter-card-divider"></div>

                        <!-- Bottom Quick Pills Bar -->
                        <div class="homepage-section-pills-bar">
                            <span class="pills-bar-label"><i class="fa-solid fa-compass" style="margin-right: 4px; color: #e11d48;"></i> Quick Jump:</span>
                            <div class="homepage-section-pills">
                                <button type="button" class="homepage-section-pill" data-section="all" onclick="switchHomepageSection('all')">
                                    <i class="fa-solid fa-border-all"></i> All Sections
                                </button>
                                <button type="button" class="homepage-section-pill active" data-section="homepage_trust" onclick="switchHomepageSection('homepage_trust')">
                                    <i class="fa-solid fa-award"></i> 1. Trust
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_live" onclick="switchHomepageSection('homepage_live')">
                                    <i class="fa-solid fa-circle" style="color: #ef4444; font-size: 8px;"></i> 2. Live Collections
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_hero" onclick="switchHomepageSection('homepage_hero')">
                                    <i class="fa-solid fa-images"></i> 3. Hero Banners
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_budget" onclick="switchHomepageSection('homepage_budget')">
                                    <i class="fa-solid fa-wallet"></i> 4. Budget Tiers
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_diwali" onclick="switchHomepageSection('homepage_diwali')">
                                    <i class="fa-solid fa-wand-magic-sparkles" style="color: #eab308;"></i> 5. Diwali Special
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_shorts" onclick="switchHomepageSection('homepage_shorts')">
                                    <i class="fa-solid fa-photo-film" style="color: #e11d48;"></i> 6. Social Channels
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_why" onclick="switchHomepageSection('homepage_why')">
                                    <i class="fa-solid fa-crown" style="color: #d4af37;"></i> 7. Why Choose
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_must_visit" onclick="switchHomepageSection('homepage_must_visit')">
                                    <i class="fa-solid fa-compass"></i> 8. Must Visit
                                </button>
                                <button type="button" class="homepage-section-pill" data-section="homepage_about" onclick="switchHomepageSection('homepage_about')">
                                    <i class="fa-solid fa-store"></i> 9. Showroom &amp; Story
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 1. Establishment and Trust Highlights Settings -->
                    <div class="homepage-config-card settings-section-card" id="card-home-trust">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_trust">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-award"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Establishment and Trust Highlights (Home Page)</h3>
                                        <p class="homepage-config-desc">Manage the trust cards displayed immediately before "Live Collections". Edit headings, descriptions, icons, display order, and visibility.</p>
                                    </div>
                                </div>
                                <div class="homepage-config-header-right">
                                    <label class="switch" title="Enable or Disable Trust Cards">
                                        <input type="hidden" name="trust_cards_enabled" value="0">
                                        <input type="checkbox" id="trust_cards_enabled_toggle" name="trust_cards_enabled" value="1" <?= ($settings['trust_cards_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <label class="toggle-label" for="trust_cards_enabled_toggle">Section Enabled</label>
                                </div>
                            </div>

                            <?php
                                $defaultAdminTrustCards = [
                                    [
                                        'icon' => 'fa-solid fa-landmark',
                                        'title' => 'Established in 2010',
                                        'desc' => 'Over a decade of cherished heritage, delivering authentic sarees with enduring craftsmanship and tradition.',
                                        'order' => 1,
                                        'visible' => 1
                                    ],
                                    [
                                        'icon' => 'fa-solid fa-award',
                                        'title' => '15+ Years of Experience',
                                        'desc' => 'Masterful weaving expertise, unmatched quality standards, and dedicated commitment to our valued patrons.',
                                        'order' => 2,
                                        'visible' => 1
                                    ],
                                    [
                                        'icon' => 'fa-solid fa-earth-americas',
                                        'title' => 'Worldwide Customers',
                                        'desc' => 'Bridging distances with worldwide shipping, bringing beloved Indian weaves to saree lovers across the globe.',
                                        'order' => 3,
                                        'visible' => 1
                                    ],
                                    [
                                        'icon' => 'fa-solid fa-star',
                                        'title' => '5-Star Customer Experience',
                                        'desc' => 'Loved by thousands of happy customers for seamless service, pristine packaging, and exceptional satisfaction.',
                                        'order' => 4,
                                        'visible' => 1
                                    ]
                                ];

                                $currentTrustCards = [];
                                if (!empty($settings['trust_cards_data'])) {
                                    $decodedCards = json_decode($settings['trust_cards_data'], true);
                                    if (is_array($decodedCards) && !empty($decodedCards)) {
                                        $currentTrustCards = $decodedCards;
                                    }
                                }
                                if (empty($currentTrustCards)) {
                                    $currentTrustCards = $defaultAdminTrustCards;
                                }
                            ?>

                            <div id="trust-cards-container" style="display: flex; flex-direction: column; gap: 14px;">
                                <?php foreach($currentTrustCards as $idx => $tCard): ?>
                                    <div class="trust-card-admin-row homepage-sub-card">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <span style="font-weight: 700; color: #e11d48; font-size: 13px;"><i class="fa-solid fa-layer-group"></i> Card #<span class="card-num"><?= $idx + 1 ?></span></span>
                                                <div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 10px;">
                                                    <label style="font-size: 12px; color: #64748b; font-weight: 600;">Visible:</label>
                                                    <input type="checkbox" name="trust_cards[<?= $idx ?>][visible]" value="1" <?= (!isset($tCard['visible']) || $tCard['visible'] == 1 || $tCard['visible'] === '1') ? 'checked' : '' ?> style="accent-color: #e11d48; cursor: pointer;">
                                                </div>
                                            </div>
                                            <button type="button" class="btn-danger-icon" onclick="this.closest('.trust-card-admin-row').remove(); renumberTrustCards();" title="Remove Card">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                        <div style="display: grid; grid-template-columns: 1fr 2fr 100px; gap: 12px; margin-bottom: 12px;">
                                            <div>
                                                <label class="form-label" style="font-size: 11px;">Font Awesome Icon</label>
                                                <div style="display: flex; gap: 8px; align-items: center;">
                                                    <input type="text" name="trust_cards[<?= $idx ?>][icon]" class="premium-input trust-icon-input" value="<?= esc($tCard['icon'] ?? 'fa-solid fa-star') ?>" placeholder="e.g. fa-solid fa-landmark" oninput="updateTrustIconPreview(this)" style="padding: 10px 12px; font-size: 13px;">
                                                    <span class="trust-icon-preview" style="width: 38px; height: 38px; border-radius: 8px; background: #fff; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; color: #e11d48; font-size: 16px; flex-shrink: 0;">
                                                        <i class="<?= esc($tCard['icon'] ?? 'fa-solid fa-star') ?>"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="form-label" style="font-size: 11px;">Heading / Title</label>
                                                <input type="text" name="trust_cards[<?= $idx ?>][title]" class="premium-input" value="<?= esc($tCard['title'] ?? '') ?>" placeholder="e.g. Established in 2010" style="padding: 10px 12px; font-size: 13px;" required>
                                            </div>
                                            <div>
                                                <label class="form-label" style="font-size: 11px;">Order</label>
                                                <input type="number" name="trust_cards[<?= $idx ?>][order]" class="premium-input" value="<?= esc($tCard['order'] ?? ($idx + 1)) ?>" min="1" max="99" style="padding: 10px 12px; font-size: 13px; text-align: center;">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label" style="font-size: 11px;">Description</label>
                                            <textarea name="trust_cards[<?= $idx ?>][desc]" class="premium-input" rows="2" placeholder="Short description text..." style="padding: 10px 12px; font-size: 13px;"><?= esc($tCard['desc'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" id="add-trust-card-btn" class="btn-add" style="margin-top: 15px;">
                                <i class="fa-solid fa-plus"></i> Add New Trust Card
                            </button>

                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates trust highlight cards on the home page storefront</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Trust Highlights
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Live Collections Section Settings -->
                    <div class="homepage-config-card settings-section-card" id="card-home-live">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_live">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-circle" style="color: #ef4444; font-size: 12px;"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Live Collections Section</h3>
                                        <p class="homepage-config-desc">Manage the circular Live Collections showcase on the homepage. Edit title and toggle visibility dynamically.</p>
                                    </div>
                                </div>
                                <div class="homepage-config-header-right">
                                    <label class="switch" title="Toggle Live Collections">
                                        <input type="hidden" name="module_live_collections" value="0">
                                        <input type="checkbox" id="module_live_collections_toggle" name="module_live_collections" value="1" <?= ($settings['module_live_collections'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <label class="toggle-label" for="module_live_collections_toggle">Section Enabled</label>
                                </div>
                            </div>

                            <div class="homepage-config-grid-2" style="align-items: flex-end; margin-bottom: 0;">
                                <div>
                                    <label class="form-label">Section Heading</label>
                                    <input type="text" name="home_live_collections_title" class="premium-input" value="<?= esc($settings['home_live_collections_title'] ?? 'Live Collections') ?>" placeholder="e.g. Live Collections">
                                </div>
                                <div style="padding-bottom: 2px;">
                                    <a href="<?= base_url('admin/products') ?>" class="btn-add" style="margin-top: 0; text-decoration: none; font-size: 13px; height: 46px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fa-solid fa-shirt"></i> Manage Products
                                    </a>
                                </div>
                            </div>

                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates circular live collection showcase heading & visibility</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Live Collections
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 3. Hero Banners & Sliders Quick Link -->
                    <div class="homepage-config-card settings-section-card" id="card-home-hero">
                        <div class="homepage-config-header" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
                            <div class="homepage-config-header-left">
                                <div class="homepage-config-icon"><i class="fa-solid fa-images"></i></div>
                                <div>
                                    <h3 class="homepage-config-title">Hero Banners & Sliders</h3>
                                    <p class="homepage-config-desc">Top full-width hero slides, festival banners, and video slides.</p>
                                </div>
                            </div>
                            <div class="homepage-config-header-right">
                                <a href="<?= base_url('admin/banners') ?>" class="btn-add" style="margin-top: 0; text-decoration: none; font-size: 13px; padding: 10px 20px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-sliders"></i> Go to Banner Manager
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Shop by Budget Section Settings -->
                    <div class="homepage-config-card settings-section-card" id="card-home-budget">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_budget">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-wallet"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Shop by Budget Section (Home Page)</h3>
                                        <p class="homepage-config-desc">Customize the budget tier cards displayed on the homepage. Edit badges (BELOW, BETWEEN, ABOVE), price ribbons, collection titles, target links, and visibility.</p>
                                    </div>
                                </div>
                                <div class="homepage-config-header-right">
                                    <label class="switch" title="Toggle Shop by Budget">
                                        <input type="hidden" name="home_budget_enabled" value="0">
                                        <input type="checkbox" id="home_budget_enabled_toggle" name="home_budget_enabled" value="1" <?= ($settings['home_budget_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <label class="toggle-label" for="home_budget_enabled_toggle">Section Enabled</label>
                                </div>
                            </div>

                            <div class="homepage-config-grid-2">
                                <div>
                                    <label class="form-label" style="font-size: 13px; font-weight: 600;">Section Heading</label>
                                    <input type="text" name="home_budget_title" class="premium-input" value="<?= esc($settings['home_budget_title'] ?? 'Shop by Budget') ?>" placeholder="e.g. Shop by Budget">
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 13px; font-weight: 600;">Section Subtitle / Tagline</label>
                                    <input type="text" name="home_budget_subtitle" class="premium-input" value="<?= esc($settings['home_budget_subtitle'] ?? 'Handpicked Sarees Tailored to Your Budget') ?>" placeholder="e.g. Handpicked Sarees Tailored to Your Budget">
                                </div>
                            </div>

                            <?php
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

                                $currentBudgetTiers = [];
                                if (!empty($settings['home_budget_tiers'])) {
                                    $decodedTiers = json_decode($settings['home_budget_tiers'], true);
                                    if (is_array($decodedTiers) && !empty($decodedTiers)) {
                                        $currentBudgetTiers = $decodedTiers;
                                    }
                                }
                                if (empty($currentBudgetTiers)) {
                                    $currentBudgetTiers = $defaultBudgetTiers;
                                }
                            ?>

                            <label class="form-label" style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin: 16px 0 12px 0; display: block;">
                                <i class="fa-solid fa-tags" style="color: #e11d48; margin-right: 6px;"></i> Budget Tiers & Price Brackets
                            </label>
                            
                            <div id="budget-tiers-container" style="display: flex; flex-direction: column; gap: 14px;">
                                <?php foreach($currentBudgetTiers as $bIdx => $bTier): ?>
                                    <div class="budget-tier-admin-row homepage-sub-card">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <span style="font-weight: 700; color: #e11d48; font-size: 13px;"><i class="fa-solid fa-wallet"></i> Tier #<span class="tier-num"><?= $bIdx + 1 ?></span></span>
                                                <div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 10px;">
                                                    <label style="font-size: 12px; color: #64748b; font-weight: 600;">Visible:</label>
                                                    <input type="checkbox" name="budget_tiers[<?= $bIdx ?>][visible]" value="1" <?= (!isset($bTier['visible']) || $bTier['visible'] == 1 || $bTier['visible'] === '1') ? 'checked' : '' ?> style="accent-color: #e11d48; cursor: pointer;">
                                                </div>
                                            </div>
                                            <button type="button" class="btn-danger-icon" onclick="this.closest('.budget-tier-admin-row').remove(); renumberBudgetTiers();" title="Remove Tier">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                        <div style="display: grid; grid-template-columns: 140px 180px 1fr 1fr; gap: 14px;">
                                            <div>
                                                <label class="form-label" style="font-size: 11px;">Diamond Badge</label>
                                                <input type="text" name="budget_tiers[<?= $bIdx ?>][badge]" class="premium-input" value="<?= esc($bTier['badge'] ?? 'BELOW') ?>" placeholder="e.g. BELOW, BETWEEN, ABOVE" style="padding: 9px 12px; font-size: 13px; font-weight: 700; text-transform: uppercase;" required>
                                            </div>
                                            <div>
                                                <label class="form-label" style="font-size: 11px;">Price Ribbon Text</label>
                                                <input type="text" name="budget_tiers[<?= $bIdx ?>][price]" class="premium-input" value="<?= esc($bTier['price'] ?? '') ?>" placeholder="e.g. Rs.500/-" style="padding: 9px 12px; font-size: 13px; font-weight: 700;" required>
                                            </div>
                                            <div>
                                                <label class="form-label" style="font-size: 11px;">Collection Title</label>
                                                <input type="text" name="budget_tiers[<?= $bIdx ?>][title]" class="premium-input" value="<?= esc($bTier['title'] ?? '') ?>" placeholder="e.g. Below Rs 499/- Collection" style="padding: 9px 12px; font-size: 13px;" required>
                                            </div>
                                            <div>
                                                <label class="form-label" style="font-size: 11px;">Link URL</label>
                                                <input type="text" name="budget_tiers[<?= $bIdx ?>][link]" class="premium-input" value="<?= esc($bTier['link'] ?? 'collections') ?>" placeholder="e.g. collections?max_price=499" style="padding: 9px 12px; font-size: 13px;">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" id="add-budget-tier-btn" onclick="addNewBudgetTier()" class="btn-add" style="margin-top: 15px;">
                                <i class="fa-solid fa-plus"></i> Add New Budget Tier
                            </button>

                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates budget tier ribbons and price bracket links</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Budget Tiers
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 5. Diwali Section -->
                    <div class="homepage-config-card settings-section-card" id="card-home-diwali">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_diwali">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            <input type="hidden" name="diwali_section_submitted" value="1">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Diwali Section</h3>
                                        <p class="homepage-config-desc">Spotlight festive banners, promotional slogans, and quick navigation link for Diwali collections.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="homepage-config-grid-2">
                                <div>
                                    <label class="form-label">Title</label>
                                    <input type="text" name="home_diwali_title" class="premium-input" value="<?= esc($settings['home_diwali_title'] ?? "THE BEST DIWALI SAREE'S") ?>">
                                </div>
                                <div>
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" name="home_diwali_subtitle" class="premium-input" value="<?= esc($settings['home_diwali_subtitle'] ?? 'Presenting Timeless Crafts') ?>">
                                </div>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">Button Link</label>
                                <input type="text" name="home_diwali_link" class="premium-input" value="<?= esc($settings['home_diwali_link'] ?? '#') ?>">
                            </div>

                            <!-- Multiple Image Uploaders: Left & Right Scrolling Columns -->
                            <?php
                                $rawLeft = $settings['home_diwali_images_left'] ?? '[]';
                                $diwaliLeftImgs = json_decode($rawLeft, true);
                                if (!is_array($diwaliLeftImgs)) $diwaliLeftImgs = [];

                                $rawRight = $settings['home_diwali_images_right'] ?? '[]';
                                $diwaliRightImgs = json_decode($rawRight, true);
                                if (!is_array($diwaliRightImgs)) $diwaliRightImgs = [];
                            ?>
                            <div style="margin-top: 15px; padding-top: 20px; border-top: 1.5px solid #e2e8f0;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                                    <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="fa-solid fa-images" style="color: #e11d48;"></i> Background Scrolling Collage Images (Left &amp; Right)
                                    </h4>
                                    <span style="font-size: 12px; color: #64748b;">
                                        Left: <strong><?= count($diwaliLeftImgs) ?></strong> images | Right: <strong><?= count($diwaliRightImgs) ?></strong> images
                                    </span>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                    <!-- Left Column Uploader -->
                                    <div class="homepage-sub-card" style="background: #fafafa; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                            <label class="form-label" style="font-weight: 700; color: #0284c7; margin: 0; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                                                <i class="fa-solid fa-arrow-up-long"></i> Left Column Images (Scrolls Up)
                                            </label>
                                            <span style="font-size: 11px; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                                                <?= count($diwaliLeftImgs) ?> uploaded
                                            </span>
                                        </div>
                                        <input type="file" id="diwali_input_left" name="diwali_images_left[]" multiple accept="image/*" class="premium-input" style="padding: 8px 10px; font-size: 12px; background: #fff;" onchange="previewDiwaliFiles(this, 'diwali_new_preview_left')">
                                        <p style="font-size: 11px; color: #64748b; margin: 6px 0 10px 0;">Select saree photos to add to the left scrolling strip.</p>

                                        <!-- New files preview container (before saving) -->
                                        <div id="diwali_new_preview_left" style="display: none; margin-bottom: 10px; padding: 8px; background: #f0f9ff; border: 1px dashed #38bdf8; border-radius: 8px;">
                                            <p style="font-size: 11px; font-weight: 700; color: #0284c7; margin: 0 0 6px 0;"><i class="fa-solid fa-cloud-arrow-up"></i> Newly Selected (Click Save to Upload):</p>
                                            <div class="diwali-new-thumbs" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(55px, 1fr)); gap: 6px;"></div>
                                        </div>

                                        <!-- Existing Left Images - Display all without scrollbar clipping -->
                                        <div style="margin-top: 6px;">
                                            <p style="font-size: 11px; font-weight: 600; color: #475569; margin: 0 0 6px 0;">Active Images in Left Strip (<?= count($diwaliLeftImgs) ?>):</p>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(58px, 1fr)); gap: 8px; background: #fff; padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <?php if(!empty($diwaliLeftImgs)): ?>
                                                    <?php foreach($diwaliLeftImgs as $lIdx => $lImg): 
                                                        $lUrl = (strpos($lImg, 'http') === 0) ? $lImg : base_url(ltrim($lImg, '/'));
                                                    ?>
                                                        <div class="diwali-thumb-item" style="position: relative; aspect-ratio: 3/4; border-radius: 6px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.08); background: #f8fafc;" title="Image #<?= $lIdx+1 ?>">
                                                            <img src="<?= esc($lUrl) ?>" alt="Left Banner <?= $lIdx+1 ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                                            <input type="hidden" name="existing_home_diwali_images_left[]" value="<?= esc($lImg) ?>">
                                                            <button type="button" onclick="this.closest('.diwali-thumb-item').remove()" title="Remove Image" style="position: absolute; top: 2px; right: 2px; width: 18px; height: 18px; border-radius: 50%; background: rgba(220,38,38,0.92); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 10px; padding: 0;">
                                                                <i class="fa-solid fa-xmark"></i>
                                                            </button>
                                                            <span style="position: absolute; bottom: 2px; left: 2px; font-size: 9px; background: rgba(0,0,0,0.65); color: #fff; padding: 1px 4px; border-radius: 3px; font-weight: 600;"><?= $lIdx+1 ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span style="font-size: 12px; color: #94a3b8; font-style: italic; grid-column: 1 / -1; padding: 10px 0; text-align: center;">No custom images uploaded yet (using default sarees).</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column Uploader -->
                                    <div class="homepage-sub-card" style="background: #fafafa; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                            <label class="form-label" style="font-weight: 700; color: #7c3aed; margin: 0; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                                                <i class="fa-solid fa-arrow-down-long"></i> Right Column Images (Scrolls Down)
                                            </label>
                                            <span style="font-size: 11px; background: #ede9fe; color: #6d28d9; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                                                <?= count($diwaliRightImgs) ?> uploaded
                                            </span>
                                        </div>
                                        <input type="file" id="diwali_input_right" name="diwali_images_right[]" multiple accept="image/*" class="premium-input" style="padding: 8px 10px; font-size: 12px; background: #fff;" onchange="previewDiwaliFiles(this, 'diwali_new_preview_right')">
                                        <p style="font-size: 11px; color: #64748b; margin: 6px 0 10px 0;">Select saree photos to add to the right scrolling strip.</p>

                                        <!-- New files preview container (before saving) -->
                                        <div id="diwali_new_preview_right" style="display: none; margin-bottom: 10px; padding: 8px; background: #faf5ff; border: 1px dashed #c084fc; border-radius: 8px;">
                                            <p style="font-size: 11px; font-weight: 700; color: #7c3aed; margin: 0 0 6px 0;"><i class="fa-solid fa-cloud-arrow-up"></i> Newly Selected (Click Save to Upload):</p>
                                            <div class="diwali-new-thumbs" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(55px, 1fr)); gap: 6px;"></div>
                                        </div>

                                        <!-- Existing Right Images - Display all without scrollbar clipping -->
                                        <div style="margin-top: 6px;">
                                            <p style="font-size: 11px; font-weight: 600; color: #475569; margin: 0 0 6px 0;">Active Images in Right Strip (<?= count($diwaliRightImgs) ?>):</p>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(58px, 1fr)); gap: 8px; background: #fff; padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <?php if(!empty($diwaliRightImgs)): ?>
                                                    <?php foreach($diwaliRightImgs as $rIdx => $rImg): 
                                                        $rUrl = (strpos($rImg, 'http') === 0) ? $rImg : base_url(ltrim($rImg, '/'));
                                                    ?>
                                                        <div class="diwali-thumb-item" style="position: relative; aspect-ratio: 3/4; border-radius: 6px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.08); background: #f8fafc;" title="Image #<?= $rIdx+1 ?>">
                                                            <img src="<?= esc($rUrl) ?>" alt="Right Banner <?= $rIdx+1 ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                                            <input type="hidden" name="existing_home_diwali_images_right[]" value="<?= esc($rImg) ?>">
                                                            <button type="button" onclick="this.closest('.diwali-thumb-item').remove()" title="Remove Image" style="position: absolute; top: 2px; right: 2px; width: 18px; height: 18px; border-radius: 50%; background: rgba(220,38,38,0.92); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 10px; padding: 0;">
                                                                <i class="fa-solid fa-xmark"></i>
                                                            </button>
                                                            <span style="position: absolute; bottom: 2px; left: 2px; font-size: 9px; background: rgba(0,0,0,0.65); color: #fff; padding: 1px 4px; border-radius: 3px; font-weight: 600;"><?= $rIdx+1 ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span style="font-size: 12px; color: #94a3b8; font-style: italic; grid-column: 1 / -1; padding: 10px 0; text-align: center;">No custom images uploaded yet (using default sarees).</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates Diwali festive titles, button link, and left/right scrolling collage photos</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Diwali Section
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 6. Social Media Showcase & Shorts Section -->
                    <div class="homepage-config-card settings-section-card" id="card-home-shorts">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_shorts">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-photo-film" style="color: #e11d48;"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Social Media Showcase &amp; Shorts Section</h3>
                                        <p class="homepage-config-desc">Engage shoppers with daily video showcases and highlight teasers across YouTube, Instagram &amp; Facebook.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="homepage-config-grid-2">
                                <div>
                                    <label class="form-label">Title</label>
                                    <input type="text" name="home_shorts_title" class="premium-input" value="<?= esc($settings['home_shorts_title'] ?? 'SHORTS PRODUCTS') ?>">
                                </div>
                                <div>
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" name="home_shorts_subtitle" class="premium-input" value="<?= esc($settings['home_shorts_subtitle'] ?? 'From our various social media channels we upload multiple products daily...') ?>">
                                </div>
                            </div>
                            <div class="homepage-config-grid-2" style="margin-bottom: 0;">
                                <div>
                                    <label class="form-label">Button Text</label>
                                    <input type="text" name="home_shorts_btn_text" class="premium-input" value="<?= esc($settings['home_shorts_btn_text'] ?? 'VIEW ALL') ?>">
                                </div>
                                <div>
                                    <label class="form-label">Button Link</label>
                                    <input type="text" name="home_shorts_btn_link" class="premium-input" value="<?= esc($settings['home_shorts_btn_link'] ?? '#') ?>">
                                </div>
                            </div>
                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates social media showcase headers and action button</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Showcase Section
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 7. Social Media Channel Tabs Configuration -->
                    <div class="homepage-config-card settings-section-card" id="settings-channel-tabs">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_shorts">
                            <input type="hidden" name="active_tab_redirect" value="channel_tabs">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-photo-film" style="color: #e11d48;"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Social Media Channel Tabs (Save Channel Tabs)</h3>
                                        <p class="homepage-config-desc">Add, edit, or delete the YouTube, Instagram &amp; Facebook channel tab names &amp; logo images displayed dynamically in the Add/Edit Product form and home page Shorts filter.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php 
                                $configuredChannels = json_decode($settings['youtube_channel_tabs'] ?? ($settings['home_shorts_channels'] ?? '[]'), true);
                                if (empty($configuredChannels)) {
                                    $configuredChannels = [
                                        ['name' => 'SRI SAKTHI Selvi', 'target' => 'sri_sakthi_selvi', 'image' => '', 'link' => '', 'platform' => 'youtube', 'visible' => 1],
                                        ['name' => 'OFFER CHANNEL', 'target' => 'offer_channel', 'image' => '', 'link' => '', 'platform' => 'youtube', 'visible' => 1],
                                        ['name' => 'ELITE C', 'target' => 'elite_c', 'image' => '', 'link' => '', 'platform' => 'youtube', 'visible' => 1],
                                    ];
                                }
                            ?>

                            <div id="shorts-channels-container" style="display: flex; flex-direction: column; gap: 14px;">
                                <?php foreach($configuredChannels as $idx => $ch): ?>
                                    <div class="homepage-sub-card shorts-channel-card" style="display: grid; grid-template-columns: auto 1fr 140px auto auto; gap: 16px; align-items: center; padding: 14px 18px; border-radius: 10px; background: #ffffff; border: 1px solid #e2e8f0;">
                                        <!-- Channel Logo Image Column -->
                                        <div style="display: flex; align-items: center; gap: 12px; min-width: 170px;">
                                            <div style="position: relative; width: 48px; height: 48px; border-radius: 50%; overflow: hidden; border: 2px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.06);">
                                                <?php 
                                                    $hasImg = !empty($ch['image']);
                                                    $imgDisplay = $hasImg ? ((strpos($ch['image'], 'http') === 0) ? $ch['image'] : base_url(ltrim($ch['image'], '/'))) : '';
                                                    $chPlatform = strtolower($ch['platform'] ?? 'youtube');
                                                    $platformIcon = ($chPlatform === 'instagram') ? 'fa-brands fa-instagram' : (($chPlatform === 'facebook') ? 'fa-brands fa-facebook' : 'fa-brands fa-youtube');
                                                    $platformColor = ($chPlatform === 'instagram') ? '#e1306c' : (($chPlatform === 'facebook') ? '#1877f2' : '#ef4444');
                                                ?>
                                                <img class="channel-preview-img" src="<?= esc($imgDisplay) ?>" alt="Channel Logo" style="width: 100%; height: 100%; object-fit: cover; <?= !$hasImg ? 'display: none;' : '' ?>">
                                                <i class="<?= $platformIcon ?> channel-preview-icon" style="font-size: 22px; color: <?= $platformColor ?>; <?= $hasImg ? 'display: none;' : '' ?>"></i>
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px; display: block;">Channel Logo</label>
                                                <input type="hidden" name="shorts_channels[<?= $idx ?>][image]" value="<?= esc($ch['image'] ?? '') ?>" class="channel-hidden-image">
                                                <label style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 6px; padding: 5px 10px; font-size: 11.5px; font-weight: 700; color: #334155; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f8fafc'">
                                                    <i class="fa-solid fa-cloud-arrow-up" style="color: #e11d48;"></i> Upload Logo
                                                    <input type="file" name="shorts_channels_image[<?= $idx ?>]" accept="image/*" style="display: none;" onchange="previewShortsChannelThumb(this)" class="channel-file-input">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Channel Tab Name -->
                                        <div>
                                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; display: block;">Channel Tab Name</label>
                                            <input type="hidden" name="shorts_channels[<?= $idx ?>][original_name]" value="<?= esc($ch['name']) ?>" class="channel-orig-name">
                                            <input type="text" name="shorts_channels[<?= $idx ?>][name]" value="<?= esc($ch['name']) ?>" class="premium-input channel-name-input" style="padding: 10px 14px; font-size: 14px; font-weight: 600;" placeholder="e.g. SRI SAKTHI Selvi" required>
                                        </div>

                                        <!-- Platform Selector -->
                                        <div>
                                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; display: block;">Platform</label>
                                            <select name="shorts_channels[<?= $idx ?>][platform]" class="premium-input channel-platform-select" onchange="updateChannelPlatformIcon(this)" style="padding: 9px 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                                                <option value="youtube" <?= $chPlatform === 'youtube' ? 'selected' : '' ?>>🔴 YouTube</option>
                                                <option value="instagram" <?= $chPlatform === 'instagram' ? 'selected' : '' ?>>🟣 Instagram</option>
                                                <option value="facebook" <?= $chPlatform === 'facebook' ? 'selected' : '' ?>>🔵 Facebook</option>
                                            </select>
                                        </div>

                                        <!-- Visible Toggle Switch -->
                                        <div style="text-align: center; padding-left: 10px; display: flex; flex-direction: column; align-items: center;">
                                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">Visible</label>
                                            <label class="switch" style="margin: 0;">
                                                <input type="hidden" name="shorts_channels[<?= $idx ?>][visible]" value="0" class="channel-vis-hidden">
                                                <input type="checkbox" name="shorts_channels[<?= $idx ?>][visible]" value="1" <?= (!isset($ch['visible']) || $ch['visible'] == 1) ? 'checked' : '' ?> class="channel-vis-check">
                                                <span class="slider"></span>
                                            </label>
                                        </div>

                                        <!-- Delete Button -->
                                        <div style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; padding-top: 18px;">
                                            <button type="button" class="btn-danger-icon" onclick="removeShortsChannelRow(this)" style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; border-radius: 6px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;" title="Remove Channel Tab">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div style="margin-top: 14px; display: flex; justify-content: flex-start;">
                                <button type="button" onclick="addShortsChannelRow()" style="background: #f1f5f9; color: #475569; border: 1.5px dashed #cbd5e1; border-radius: 8px; padding: 10px 18px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.background='#e2e8f0';this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#f1f5f9';this.style.borderColor='#cbd5e1';">
                                    <i class="fa-solid fa-plus" style="color: var(--primary-color, #e11d48);"></i> Add Another Channel Tab
                                </button>
                            </div>

                            <div class="section-card-footer" style="margin-top: 20px;">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates YouTube, Instagram &amp; Facebook channel tabs &amp; logo images dynamically</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Channel Tabs
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                    function updateChannelPlatformIcon(select) {
                        const card = select.closest('.shorts-channel-card');
                        if (!card) return;
                        const icon = card.querySelector('.channel-preview-icon');
                        if (!icon) return;
                        const val = select.value;
                        if (val === 'instagram') {
                            icon.className = 'fa-brands fa-instagram channel-preview-icon';
                            icon.style.color = '#e1306c';
                        } else if (val === 'facebook') {
                            icon.className = 'fa-brands fa-facebook channel-preview-icon';
                            icon.style.color = '#1877f2';
                        } else {
                            icon.className = 'fa-brands fa-youtube channel-preview-icon';
                            icon.style.color = '#ef4444';
                        }
                    }

                    function previewShortsChannelThumb(input) {
                        const card = input.closest('.shorts-channel-card');
                        if (!card || !input.files || !input.files[0]) return;
                        const file = input.files[0];
                        const imgEl = card.querySelector('.channel-preview-img');
                        const iconEl = card.querySelector('.channel-preview-icon');
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (imgEl) {
                                imgEl.src = e.target.result;
                                imgEl.style.display = 'block';
                            }
                            if (iconEl) {
                                iconEl.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    }

                    function addShortsChannelRow() {
                        const container = document.getElementById('shorts-channels-container');
                        if (!container) return;
                        const cards = container.querySelectorAll('.shorts-channel-card');
                        const newIdx = cards.length;
                        const card = document.createElement('div');
                        card.className = 'homepage-sub-card shorts-channel-card';
                        card.style.cssText = 'display: grid; grid-template-columns: auto 1fr 140px auto auto; gap: 16px; align-items: center; padding: 14px 18px; margin-top: 10px; border-radius: 10px; background: #ffffff; border: 1px solid #e2e8f0;';
                        card.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 12px; min-width: 170px;">
                                <div style="position: relative; width: 48px; height: 48px; border-radius: 50%; overflow: hidden; border: 2px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.06);">
                                    <img class="channel-preview-img" src="" alt="Channel Logo" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <i class="fa-brands fa-youtube channel-preview-icon" style="font-size: 22px; color: #ef4444;"></i>
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px; display: block;">Channel Logo</label>
                                    <input type="hidden" name="shorts_channels[${newIdx}][image]" value="" class="channel-hidden-image">
                                    <label style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 6px; padding: 5px 10px; font-size: 11.5px; font-weight: 700; color: #334155; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f8fafc'">
                                        <i class="fa-solid fa-cloud-arrow-up" style="color: #e11d48;"></i> Upload Logo
                                        <input type="file" name="shorts_channels_image[${newIdx}]" accept="image/*" style="display: none;" onchange="previewShortsChannelThumb(this)" class="channel-file-input">
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; display: block;">Channel Tab Name</label>
                                <input type="hidden" name="shorts_channels[${newIdx}][original_name]" value="" class="channel-orig-name">
                                <input type="text" name="shorts_channels[${newIdx}][name]" value="" placeholder="e.g. SPECIAL CHANNEL" class="premium-input channel-name-input" style="padding: 10px 14px; font-size: 14px; font-weight: 600;" required>
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; display: block;">Platform</label>
                                <select name="shorts_channels[${newIdx}][platform]" class="premium-input channel-platform-select" onchange="updateChannelPlatformIcon(this)" style="padding: 9px 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                                    <option value="youtube">🔴 YouTube</option>
                                    <option value="instagram">🟣 Instagram</option>
                                    <option value="facebook">🔵 Facebook</option>
                                </select>
                            </div>
                            <div style="text-align: center; padding-left: 10px; display: flex; flex-direction: column; align-items: center;">
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">Visible</label>
                                <label class="switch" style="margin: 0;">
                                    <input type="hidden" name="shorts_channels[${newIdx}][visible]" value="0" class="channel-vis-hidden">
                                    <input type="checkbox" name="shorts_channels[${newIdx}][visible]" value="1" checked class="channel-vis-check">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; padding-top: 18px;">
                                <button type="button" class="btn-danger-icon" onclick="removeShortsChannelRow(this)" style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; border-radius: 6px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;" title="Remove Channel Tab">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        `;
                        container.appendChild(card);
                    }

                    function removeShortsChannelRow(btn) {
                        const container = document.getElementById('shorts-channels-container');
                        if (!container) return;
                        const cards = container.querySelectorAll('.shorts-channel-card');
                        if (cards.length <= 1) {
                            alert('At least one channel tab is required.');
                            return;
                        }
                        btn.closest('.shorts-channel-card').remove();
                        const updatedCards = container.querySelectorAll('.shorts-channel-card');
                        updatedCards.forEach((c, i) => {
                            const origInp = c.querySelector('.channel-orig-name');
                            const nameInp = c.querySelector('.channel-name-input');
                            const platformSel = c.querySelector('.channel-platform-select');
                            const imgHidden = c.querySelector('.channel-hidden-image');
                            const fileInp = c.querySelector('.channel-file-input');
                            const visHidden = c.querySelector('.channel-vis-hidden');
                            const visCheck = c.querySelector('.channel-vis-check');
                            if (origInp) origInp.name = `shorts_channels[${i}][original_name]`;
                            if (nameInp) nameInp.name = `shorts_channels[${i}][name]`;
                            if (platformSel) platformSel.name = `shorts_channels[${i}][platform]`;
                            if (imgHidden) imgHidden.name = `shorts_channels[${i}][image]`;
                            if (fileInp) fileInp.name = `shorts_channels_image[${i}]`;
                            if (visHidden) visHidden.name = `shorts_channels[${i}][visible]`;
                            if (visCheck) visCheck.name = `shorts_channels[${i}][visible]`;
                        });
                    }
                    </script>

                    <!-- 8. Why Choose SSPTex Section Settings -->
                    <div class="homepage-config-card settings-section-card" id="card-home-why">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_why">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-crown" style="color: #d4af37;"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Why Choose Selvi Tex Section</h3>
                                        <p class="homepage-config-desc">Showcase why customers should choose Selvi Tex directly below the YouTube Shorts showcase on your home page.</p>
                                    </div>
                                </div>
                                <div class="homepage-config-header-right">
                                    <label class="switch" title="Toggle Why Choose SSPTex">
                                        <input type="hidden" name="home_why_choose_enabled" value="0">
                                        <input type="checkbox" id="home_why_choose_enabled_toggle" name="home_why_choose_enabled" value="1" <?= ($settings['home_why_choose_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <label class="toggle-label" for="home_why_choose_enabled_toggle">Section Enabled</label>
                                </div>
                            </div>

                            <div class="homepage-config-grid-2">
                                <div>
                                    <label class="form-label">Main Heading</label>
                                    <input type="text" name="home_why_choose_title" class="premium-input" value="<?= esc($settings['home_why_choose_title'] ?? 'Why Choose SSPTex?') ?>" placeholder="e.g. Why Choose SSPTex?">
                                </div>
                                <div>
                                    <label class="form-label">Subtitle / Highlight Tagline</label>
                                    <input type="text" name="home_why_choose_subtitle" class="premium-input" value="<?= esc($settings['home_why_choose_subtitle'] ?? 'Authentic Weaving Heritage • Direct Factory Prices • Global Doorstep Delivery') ?>" placeholder="Tagline...">
                                </div>
                            </div>

                            <div class="homepage-config-grid-4" style="margin-top: 14px;">
                                <!-- Pillar 1 -->
                                <div class="homepage-sub-card">
                                    <label class="form-label" style="font-size: 12px; color: #e11d48; font-weight: 700;"><i class="fa-solid fa-gem" style="margin-right: 4px;"></i> 1. Direct From Loom</label>
                                    <input type="text" name="why_choose_title_1" class="premium-input" value="<?= esc($settings['why_choose_title_1'] ?? 'Direct From Master Weavers') ?>" style="margin-bottom: 8px; font-size: 13px;" placeholder="Title">
                                    <textarea name="why_choose_desc_1" class="premium-input" rows="3" style="font-size: 12px; padding: 8px;" placeholder="Description"><?= esc($settings['why_choose_desc_1'] ?? 'Woven directly on authentic Elampillai looms, preserving century-old silk and cotton craftsmanship without middleman commissions.') ?></textarea>
                                </div>
                                <!-- Pillar 2 -->
                                <div class="homepage-sub-card">
                                    <label class="form-label" style="font-size: 12px; color: #e11d48; font-weight: 700;"><i class="fa-solid fa-tags" style="margin-right: 4px;"></i> 2. Wholesale Pricing</label>
                                    <input type="text" name="why_choose_title_2" class="premium-input" value="<?= esc($settings['why_choose_title_2'] ?? 'Wholesale Price for Everyone') ?>" style="margin-bottom: 8px; font-size: 13px;" placeholder="Title">
                                    <textarea name="why_choose_desc_2" class="premium-input" rows="3" style="font-size: 12px; padding: 8px;" placeholder="Description"><?= esc($settings['why_choose_desc_2'] ?? 'Direct manufacturer rates whether purchasing a single celebratory saree or bulk bridal and uniform orders.') ?></textarea>
                                </div>
                                <!-- Pillar 3 -->
                                <div class="homepage-sub-card">
                                    <label class="form-label" style="font-size: 12px; color: #e11d48; font-weight: 700;"><i class="fa-solid fa-shield-halved" style="margin-right: 4px;"></i> 3. Quality Assurance</label>
                                    <input type="text" name="why_choose_title_3" class="premium-input" value="<?= esc($settings['why_choose_title_3'] ?? '100% Quality & Zari Guarantee') ?>" style="margin-bottom: 8px; font-size: 13px;" placeholder="Title">
                                    <textarea name="why_choose_desc_3" class="premium-input" rows="3" style="font-size: 12px; padding: 8px;" placeholder="Description"><?= esc($settings['why_choose_desc_3'] ?? 'Every piece undergoes multi-point inspection for authentic zari shine, soft drape, and durable texture.') ?></textarea>
                                </div>
                                <!-- Pillar 4 -->
                                <div class="homepage-sub-card">
                                    <label class="form-label" style="font-size: 12px; color: #e11d48; font-weight: 700;"><i class="fa-solid fa-plane-departure" style="margin-right: 4px;"></i> 4. Worldwide Shipping</label>
                                    <input type="text" name="why_choose_title_4" class="premium-input" value="<?= esc($settings['why_choose_title_4'] ?? 'Worldwide Express Delivery') ?>" style="margin-bottom: 8px; font-size: 13px;" placeholder="Title">
                                    <textarea name="why_choose_desc_4" class="premium-input" rows="3" style="font-size: 12px; padding: 8px;" placeholder="Description"><?= esc($settings['why_choose_desc_4'] ?? 'Fast, reliable door delivery across India and internationally with secure protective packaging and full tracking.') ?></textarea>
                                </div>
                            </div>

                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates Why Choose SSPTex 4 trust highlight cards</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Why Choose SSPTex
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 9. Must Visit Section -->
                    <div class="homepage-config-card settings-section-card" id="card-home-must-visit">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_must_visit">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon"><i class="fa-solid fa-compass" style="color: #0ea5e9;"></i></div>
                                    <div>
                                        <h3 class="homepage-config-title">Must Visit Section</h3>
                                        <p class="homepage-config-desc">Guide customers to explore our must-visit designer saree collections and top recommendations.</p>
                                    </div>
                                </div>
                                <div class="homepage-config-header-right">
                                    <label class="switch" title="Toggle Must Visit Section">
                                        <input type="hidden" name="module_must_visit" value="0">
                                        <input type="checkbox" id="module_must_visit_toggle" name="module_must_visit" value="1" <?= ($settings['module_must_visit'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <label class="toggle-label" for="module_must_visit_toggle">Section Enabled</label>
                                </div>
                            </div>
                            <div class="homepage-config-grid-2">
                                <div>
                                    <label class="form-label">Title</label>
                                    <input type="text" name="home_must_visit_title" class="premium-input" value="<?= esc($settings['home_must_visit_title'] ?? 'Must Visit') ?>">
                                </div>
                                <div>
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" name="home_must_visit_subtitle" class="premium-input" value="<?= esc($settings['home_must_visit_subtitle'] ?? 'Everyone') ?>">
                                </div>
                            </div>

                            <div class="homepage-config-grid-2" style="margin-top: 15px; margin-bottom: 0;">
                                <div>
                                    <label class="form-label"><i class="fa-solid fa-list-check" style="color: #0ea5e9;"></i> Select Must Visit Category (Dropdown Select)</label>
                                    <select name="home_must_visit_category_id" class="premium-input" style="font-weight: 600;">
                                        <option value="">⭐ Default: Products tagged as 'is_must_visit' in Product Admin</option>
                                        <?php if (!empty($categories)): ?>
                                            <optgroup label="Or Showcase Products From Specific Category:">
                                            <?php foreach ($categories as $catItem): ?>
                                                <option value="<?= esc($catItem['id']) ?>" <?= (($settings['home_must_visit_category_id'] ?? '') == $catItem['id']) ? 'selected' : '' ?>>
                                                    📁 <?= esc($catItem['name']) ?> (ID #<?= $catItem['id'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                    </select>
                                    <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Pick a category from the dropdown to automatically show its latest showcase sarees, or leave default for items flagged with 'Must Visit' tag.</small>
                                </div>
                                <div>
                                    <label class="form-label"><i class="fa-solid fa-layer-group" style="color: #0ea5e9;"></i> Max Products Display Count</label>
                                    <select name="home_must_visit_limit" class="premium-input" style="font-weight: 600;">
                                        <option value="3" <?= (($settings['home_must_visit_limit'] ?? '3') == '3') ? 'selected' : '' ?>>3 Products (Standard 3-Card Grid)</option>
                                        <option value="4" <?= (($settings['home_must_visit_limit'] ?? '3') == '4') ? 'selected' : '' ?>>4 Products</option>
                                        <option value="6" <?= (($settings['home_must_visit_limit'] ?? '3') == '6') ? 'selected' : '' ?>>6 Products (2 Rows)</option>
                                    </select>
                                    <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Controls how many showcase cards appear in the Must Visit row.</small>
                                </div>
                            </div>

                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates Must Visit category source, title, subtitle &amp; display limit</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Must Visit
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 10. About SSSTex & Visit Showroom Section -->
                    <div class="homepage-config-card settings-section-card" id="card-home-about">
                        <form action="<?= base_url('admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="settings-section-form">
                            <input type="hidden" name="section_name" value="homepage_about">
                            <input type="hidden" name="active_tab_redirect" value="homepage">
                            
                            <div class="homepage-config-header">
                                <div class="homepage-config-header-left">
                                    <div class="homepage-config-icon" style="background: linear-gradient(135deg, #e11d48 0%, #4a0d1d 100%); color: #facc15;"><i class="fa-solid fa-store"></i></div>
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <h3 class="homepage-config-title">About SSSTex &amp; Visit Showroom Section</h3>
                                            <span style="background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 12px; border: 1px solid #fde68a;">Flagship Store</span>
                                        </div>
                                        <p class="homepage-config-desc">Manage your flagship weaving showroom gallery, physical address, business hours, and instant direction links displayed on the homepage.</p>
                                    </div>
                                </div>
                                <div class="homepage-config-header-right">
                                    <label class="switch" title="Enable or Disable About Store Section">
                                        <input type="hidden" name="about_store_enabled" value="0">
                                        <input type="checkbox" id="about_store_enabled_toggle" name="about_store_enabled" value="1" <?= ($settings['about_store_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <label class="toggle-label" for="about_store_enabled_toggle">Section Enabled</label>
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 24px; margin-bottom: 20px;">
                                <!-- 1. Section Title & Subtitle / Tagline & Description -->
                                <div class="homepage-sub-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px;">
                                    <div class="homepage-config-grid-2" style="margin-bottom: 16px;">
                                        <div>
                                            <label class="form-label" style="font-size: 13px; font-weight: 700; color: #1e293b;"><i class="fa-solid fa-heading" style="color: #e11d48; margin-right: 6px;"></i> Section Title</label>
                                            <input type="text" name="about_store_title" class="premium-input" value="<?= esc($settings['about_store_title'] ?? 'About Selvi Tex') ?>" placeholder="e.g. About Selvi Tex">
                                        </div>
                                        <div>
                                            <label class="form-label" style="font-size: 13px; font-weight: 700; color: #1e293b;"><i class="fa-solid fa-tag" style="color: #d4af37; margin-right: 6px;"></i> Subtitle / Tagline</label>
                                            <input type="text" name="about_store_subtitle" class="premium-input" value="<?= esc($settings['about_store_subtitle'] ?? 'VISIT OUR SHOWROOM') ?>" placeholder="e.g. VISIT OUR SHOWROOM">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-label" style="font-size: 13px; font-weight: 700; color: #1e293b;"><i class="fa-solid fa-align-left" style="color: #4f46e5; margin-right: 6px;"></i> Showroom Story &amp; Welcome Message</label>
                                        <textarea name="about_store_desc" class="premium-input" rows="3" style="width: 100%; resize: vertical; line-height: 1.6; font-size: 13px;" placeholder="Describe your showroom, master weavers, and trial experience..."><?= esc($settings['about_store_desc'] ?? 'Experience the finest Elampillai pattu, traditional pure silk, and authentic handloom sarees directly from the master weavers of Tamil Nadu. Visit our exclusive showroom for an unforgettable saree shopping experience with personalized assistance.') ?></textarea>
                                    </div>
                                </div>

                                <!-- 2. Showroom Locations (Full Width) -->
                                <?php 
                                    $storeLocations = [];
                                    if (!empty($settings['about_store_locations'])) {
                                        $decodedLocs = json_decode($settings['about_store_locations'], true);
                                        if (is_array($decodedLocs) && count($decodedLocs) > 0) {
                                            $storeLocations = $decodedLocs;
                                        }
                                    }
                                    if (empty($storeLocations)) {
                                        $storeLocations = [[
                                            'name'      => 'Flagship Showroom (Elampillai)',
                                            'address'   => $settings['about_store_address'] ?? "Sri Sakthi Selvi Tex\nMain Road, Elampillai,\nSalem District, Tamil Nadu - 637502",
                                            'timings'   => $settings['about_store_timings'] ?? 'Monday - Sunday: 9:00 AM - 9:00 PM',
                                            'map_link'  => $settings['about_store_map_link'] ?? 'https://maps.google.com/?q=Sri+Sakthi+Selvi+Tex+Elampillai',
                                            'map_embed' => $settings['about_store_map_embed'] ?? ($settings['google_map_embed'] ?? ''),
                                            'phone'     => $settings['about_store_phone'] ?? '9789296751',
                                            'whatsapp'  => $settings['about_store_whatsapp'] ?? '9789296751'
                                        ]];
                                    }
                                ?>
                                <div class="homepage-sub-card" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                                        <div>
                                            <label class="form-label" style="font-weight: 800; color: #1e293b; margin: 0; font-size: 14.5px; display: flex; align-items: center; gap: 8px;">
                                                <i class="fa-solid fa-map-location-dot" style="color: #e11d48;"></i> Showroom Locations
                                            </label>
                                            <p style="font-size: 12.5px; color: #64748b; margin: 3px 0 0 0;">Add multiple store branches or showroom addresses. Customers can toggle between them on the homepage and about page.</p>
                                        </div>
                                        <button type="button" onclick="addShowroomLocationRow()" style="background: linear-gradient(135deg, #e11d48 0%, #5a0e23 100%); color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; box-shadow: 0 2px 8px rgba(225, 29, 72,0.25); transition: all 0.2s;">
                                            <i class="fa-solid fa-plus"></i> Add Another Location
                                        </button>
                                    </div>

                                    <div id="showroom-locations-container" style="display: flex; flex-direction: column; gap: 16px;">
                                        <?php foreach ($storeLocations as $locIdx => $loc): ?>
                                        <div class="showroom-location-card" style="background: #ffffff; border: 1.5px solid <?= $locIdx === 0 ? '#cbd5e1' : '#e2e8f0' ?>; border-radius: 12px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <span class="location-badge" style="background: <?= $locIdx === 0 ? '#e11d48' : '#0284c7' ?>; color: #fff; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 6px;">
                                                        <?= $locIdx === 0 ? 'Location #1 (Flagship)' : 'Location #' . ($locIdx + 1) ?>
                                                    </span>
                                                    <input type="text" name="about_store_locations[<?= $locIdx ?>][name]" value="<?= esc($loc['name'] ?? ($locIdx === 0 ? 'Flagship Showroom (Elampillai)' : 'Showroom Branch #' . ($locIdx + 1))) ?>" class="premium-input" style="font-weight: 700; font-size: 13.5px; padding: 6px 12px; width: 280px;" placeholder="Location Name (e.g. Flagship Store / Salem Branch)">
                                                </div>
                                                <?php if ($locIdx > 0): ?>
                                                <button type="button" onclick="this.closest('.showroom-location-card').remove(); reindexShowroomLocations();" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; padding: 5px 10px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                                                    <i class="fa-solid fa-trash-can"></i> Delete Location
                                                </button>
                                                <?php endif; ?>
                                            </div>

                                            <div style="margin-bottom: 12px;">
                                                <label class="form-label" style="font-size: 12px; margin-bottom: 4px;"><i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> Physical Address</label>
                                                <textarea name="about_store_locations[<?= $locIdx ?>][address]" class="premium-input" rows="2" style="width: 100%; font-size: 13px;" placeholder="Full address with town and pincode"><?= esc($loc['address'] ?? '') ?></textarea>
                                            </div>

                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px;">
                                                <div>
                                                    <label class="form-label" style="font-size: 12px; margin-bottom: 4px;"><i class="fa-regular fa-clock" style="color: #0d9488;"></i> Working Hours / Timings</label>
                                                    <input type="text" name="about_store_locations[<?= $locIdx ?>][timings]" value="<?= esc($loc['timings'] ?? 'Monday - Sunday: 9:00 AM - 9:00 PM') ?>" class="premium-input" style="font-size: 13px;">
                                                </div>
                                                <div>
                                                    <label class="form-label" style="font-size: 12px; margin-bottom: 4px;"><i class="fa-solid fa-map-location-dot" style="color: #0284c7;"></i> Google Maps URL (Directions)</label>
                                                    <input type="text" name="about_store_locations[<?= $locIdx ?>][map_link]" value="<?= esc($loc['map_link'] ?? 'https://maps.google.com') ?>" class="premium-input" style="font-size: 13px;" placeholder="https://maps.google.com/...">
                                                </div>
                                            </div>

                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px;">
                                                <div>
                                                    <label class="form-label" style="font-size: 12px; margin-bottom: 4px;"><i class="fa-solid fa-phone" style="color: #10b981;"></i> Customer Care / Phone</label>
                                                    <input type="text" name="about_store_locations[<?= $locIdx ?>][phone]" value="<?= esc($loc['phone'] ?? '9789296751') ?>" class="premium-input" style="font-size: 13px;">
                                                </div>
                                                <div>
                                                    <label class="form-label" style="font-size: 12px; margin-bottom: 4px;"><i class="fa-brands fa-whatsapp" style="color: #25D366;"></i> WhatsApp Inquiries Number</label>
                                                    <input type="text" name="about_store_locations[<?= $locIdx ?>][whatsapp]" value="<?= esc($loc['whatsapp'] ?? '9789296751') ?>" class="premium-input" style="font-size: 13px;">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="form-label" style="font-size: 12px; margin-bottom: 4px;"><i class="fa-solid fa-map-pin" style="color: #e11d48;"></i> Google Maps Location Embed (Iframe or Embed URL)</label>
                                                <textarea name="about_store_locations[<?= $locIdx ?>][map_embed]" class="premium-input" rows="2" style="width: 100%; font-size: 12px; font-family: monospace;" placeholder="Paste Google Maps <iframe> code or embed link (e.g. https://www.google.com/maps/embed?pb=...)"><?= esc($loc['map_embed'] ?? '') ?></textarea>
                                                <small style="color: #64748b; font-size: 11.5px; display: block; margin-top: 3px;">Embeds an interactive Google Map on the About page and Homepage for this branch. If left blank, an interactive map is auto-generated from the physical address.</small>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- 3. Showroom & Loom Gallery Photos (Full Width, One by One) -->
                                <div class="homepage-sub-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                                        <div>
                                            <label class="form-label" style="font-weight: 800; color: #e11d48; margin: 0; font-size: 14.5px; display: flex; align-items: center; gap: 8px;">
                                                <i class="fa-solid fa-images"></i> Showroom &amp; Loom Gallery Photos
                                            </label>
                                            <p style="font-size: 12.5px; color: #64748b; margin: 3px 0 0 0;">Upload high-definition photos of your showroom, weaving looms, and trial area. Displays on homepage and about page.</p>
                                        </div>
                                        <?php 
                                            $aboutImgsCount = 0;
                                            if(!empty($settings['about_store_images'])) {
                                                $parsed = json_decode($settings['about_store_images'], true);
                                                if(is_array($parsed)) $aboutImgsCount = count(array_filter($parsed));
                                            }
                                        ?>
                                        <span style="font-size: 11.5px; background: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 14px; font-weight: 700;">
                                            <?= $aboutImgsCount ?> Active Photos
                                        </span>
                                    </div>

                                    <!-- Upload Dropzone -->
                                    <div class="premium-file-upload" style="padding: 24px 20px; border: 2px dashed #cbd5e1; border-radius: 12px; background: #fafafa; text-align: center; cursor: pointer; transition: all 0.2s ease;">
                                        <div class="upload-icon" style="font-size: 32px; color: #e11d48; margin-bottom: 8px;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                        <div class="upload-text" style="font-size: 14px; font-weight: 700; color: #1e293b;">Click or Drag Photos of Showroom / Looms</div>
                                        <small style="color: #64748b; font-size: 12px;">Supports multiple JPG, PNG, WEBP high-resolution photos</small>
                                        <input type="file" name="about_store_images[]" accept="image/*" multiple onchange="previewNewBannerUploads(this, 'preview-about-store-images')">
                                    </div>

                                    <!-- Newly selected images preview before saving -->
                                    <div id="preview-about-store-images" style="display: flex; gap: 12px; margin-top: 16px; flex-wrap: wrap;"></div>

                                    <!-- Active existing showroom gallery -->
                                    <div style="margin-top: 20px;">
                                        <div style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                                            Active Showroom Photos:
                                        </div>
                                        <?php if(!empty($settings['about_store_images'])): 
                                            $aboutImgs = json_decode($settings['about_store_images'], true);
                                            if(!is_array($aboutImgs)) $aboutImgs = [$settings['about_store_images']];
                                            $aboutImgs = array_values(array_filter($aboutImgs));
                                        ?>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 14px;">
                                                <?php foreach($aboutImgs as $abIdx => $abImg): ?>
                                                <div class="banner-preview-box" style="position: relative; aspect-ratio: 4/3; border-radius: 10px; overflow: hidden; border: 1.5px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.06); background: #fff;" title="Showroom Photo #<?= $abIdx+1 ?>">
                                                    <input type="hidden" name="existing_about_store_images[]" value="<?= esc($abImg) ?>">
                                                    <img src="<?= base_url($abImg) ?>" alt="Store Image" style="width: 100%; height: 100%; object-fit: cover;">
                                                    <button type="button" onclick="this.closest('.banner-preview-box').remove()" title="Remove photo" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.92); color: #fff; border: none; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                    <span style="position: absolute; bottom: 4px; left: 4px; font-size: 10px; background: rgba(0,0,0,0.7); color: #fff; padding: 2px 6px; border-radius: 3px; font-weight: 700;">#<?= $abIdx+1 ?></span>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div style="padding: 22px; background: #fafafa; border: 1px dashed #cbd5e1; border-radius: 10px; text-align: center; color: #94a3b8; font-size: 13px;">
                                                <i class="fa-regular fa-image" style="font-size: 24px; display: block; margin-bottom: 6px;"></i>
                                                No showroom photos uploaded yet. Using default branded card.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <small style="color: #64748b; font-size: 11.5px; margin-top: 12px; display: block;">
                                        <i class="fa-solid fa-circle-info" style="color: #0284c7;"></i> The first photo will be showcased as the main large featured display on the homepage.
                                    </small>
                                </div>
                            </div>

                            <div class="section-card-footer">
                                <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates showroom gallery, timings, address and contact details</span>
                                <button type="submit" class="btn-section-save">
                                    <i class="fa-solid fa-floppy-disk"></i> Save About Showroom
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <!-- Module Settings Tab -->
            <div id="tab-modules" class="settings-section <?= ($currentSection === 'modules') ? 'active' : '' ?>">
                <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form">
                    <input type="hidden" name="section_name" value="modules">
                    <input type="hidden" name="active_tab_redirect" value="modules">
                    <div style="margin-bottom: 25px;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Module & Feature Visibility Manager</h3>
                        <p style="font-size: 13.5px; color: #64748b; margin: 0;">Enable or disable individual website sections and core features. Changes take effect across the storefront dynamically without altering design integrity.</p>
                    </div>

                    <div class="modules-grid">
                        <!-- Module: Product Creation Type (Single / Combo Slider) -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-layer-group" style="color: #e11d48;"></i></div>
                                <div>
                                    <div class="module-title">Creation Type Switcher &mdash; Single / Combo Slider</div>
                                    <p class="module-desc">Show or hide the "Creation Type (Regular Product / Combo Pack)" swipe switcher on Add &amp; Edit Product pages.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_creation_type" value="0">
                                    <input type="checkbox" name="module_creation_type" value="1" <?= ($settings['module_creation_type'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Saree & Blouse Measurements Section -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-ruler-combined" style="color: #d97706;"></i></div>
                                <div>
                                    <div class="module-title">Measurements Section &mdash; Saree &amp; Blouse Specs (Tab 2)</div>
                                    <p class="module-desc">Enable or disable Section 2 (Saree Length, Height, Blouse Attachment, Size Chart &amp; Highlights) on product forms.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_measurements_section" value="0">
                                    <input type="checkbox" name="module_measurements_section" value="1" <?= ($settings['module_measurements_section'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Product SKU Code -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-barcode" style="color: #0d9488;"></i></div>
                                <div>
                                    <div class="module-title">Product SKU Code &mdash; SKU (Optional)</div>
                                    <p class="module-desc">Enable or disable the SKU (Stock Keeping Unit) optional input fields across admin product forms and product variants.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_product_sku" value="0">
                                    <input type="checkbox" name="module_product_sku" value="1" <?= ($settings['module_product_sku'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Live Collections -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-circle" style="color: #ef4444; font-size: 14px;"></i></div>
                                <div>
                                    <div class="module-title">Live Collections Showcase</div>
                                    <p class="module-desc">Display animated circular product highlights of fresh live releases on the homepage.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_live_collections" value="0">
                                    <input type="checkbox" name="module_live_collections" value="1" <?= ($settings['module_live_collections'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Establishment & Trust Highlights -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-shield-halved" style="color: #d4af37;"></i></div>
                                <div>
                                    <div class="module-title">Establishment & Trust Highlights</div>
                                    <p class="module-desc">Display the four premium experience & trust information cards immediately before Live Collections.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="trust_cards_enabled" value="0">
                                    <input type="checkbox" name="trust_cards_enabled" value="1" <?= ($settings['trust_cards_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Hero Banner Sliders -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-images"></i></div>
                                <div>
                                    <div class="module-title">Hero Banner Slider</div>
                                    <p class="module-desc">Full-width rotating hero banner carousel showcasing featured saree collections at the top.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_hero_banners" value="0">
                                    <input type="checkbox" name="module_hero_banners" value="1" <?= ($settings['module_hero_banners'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Diwali Festive Feature Banner -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-wand-magic-sparkles" style="color: #f59e0b;"></i></div>
                                <div>
                                    <div class="module-title">Diwali Festive Promotional Banner</div>
                                    <p class="module-desc">Vertical scrolling visual collage banner with headline for festival saree collections.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_diwali" value="0">
                                    <input type="checkbox" name="module_diwali" value="1" <?= ($settings['module_diwali'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Video Shorts & Reels -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-play" style="color: #ef4444;"></i></div>
                                <div>
                                    <div class="module-title">Video Shorts & Social Reels</div>
                                    <p class="module-desc">Engaging short video reels section showcasing daily YouTube/Instagram product reviews.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_shorts" value="0">
                                    <input type="checkbox" name="module_shorts" value="1" <?= ($settings['module_shorts'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Must Visit Section -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-map-pin" style="color: #0ea5e9;"></i></div>
                                <div>
                                    <div class="module-title">Must Visit Categories Section</div>
                                    <p class="module-desc">Dedicated curated categories highlighting essential saree collections for every shopper.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_must_visit" value="0">
                                    <input type="checkbox" name="module_must_visit" value="1" <?= ($settings['module_must_visit'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: Shop by Budget Badges -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-wallet" style="color: #6366f1;"></i></div>
                                <div>
                                    <div class="module-title">Shop by Budget Badges</div>
                                    <p class="module-desc">Diamond badges filtering sarees by price tiers (&lt;Rs.500, Rs.501-1000, Rs.1001-2000, &gt;Rs.2000).</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_budget" value="0">
                                    <input type="checkbox" name="module_budget" value="1" <?= ($settings['module_budget'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: WhatsApp Quick Chat -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-brands fa-whatsapp" style="color: #22c55e;"></i></div>
                                <div>
                                    <div class="module-title">Floating WhatsApp Support</div>
                                    <p class="module-desc">Floating WhatsApp direct chat button for fast customer enquiries and order assistance.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_whatsapp" value="0">
                                    <input type="checkbox" name="module_whatsapp" value="1" <?= ($settings['module_whatsapp'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Module: GST Calculation -->
                        <div class="module-card">
                            <div class="module-left">
                                <div class="module-icon"><i class="fa-solid fa-percent" style="color: #8b5cf6;"></i></div>
                                <div>
                                    <div class="module-title">GST Tax Calculation Engine</div>
                                    <p class="module-desc">Automatic HSN code tax calculations on checkout and official GST invoices.</p>
                                </div>
                            </div>
                            <div class="module-right">
                                <label class="switch">
                                    <input type="hidden" name="module_gst" value="0">
                                    <input type="checkbox" name="module_gst" value="1" <?= ($settings['module_gst'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="section-card-footer">
                        <span class="section-footer-info"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Updates storefront module and feature toggles dynamically</span>
                        <button type="submit" class="btn-section-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Module Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Attribute Settings (Color & Size) Tab -->
            <?= view('admin/settings/partials/attributes', ['settings' => $settings, 'currentSection' => $currentSection]) ?>
    </div>
</div>

<script src="<?= base_url('assets/js/admin-settings.js') ?>?v=<?= time() ?>"></script>

<script>
function loadGoogleFontDynamic(fontName) {
    if (!fontName || fontName === 'inherit') return;
    var linkId = 'gfont-preview-' + fontName.replace(/\s+/g, '-').toLowerCase();
    if (!document.getElementById(linkId)) {
        var link = document.createElement('link');
        link.id = linkId;
        link.rel = 'stylesheet';
        link.href = 'https://fonts.googleapis.com/css2?family=' + encodeURIComponent(fontName.replace(/ /g, '+')) + ':ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap';
        document.head.appendChild(link);
    }
}

function updateDynamicFontPreview() {
    var globalFontSelect = document.getElementById('global_font_family');
    var headingFontSelect = document.getElementById('heading_font_family');
    if (!globalFontSelect || !headingFontSelect) return;

    var globalFont = globalFontSelect.value;
    var headingFont = headingFontSelect.value;
    if (headingFont === 'inherit') {
        headingFont = globalFont;
    }

    loadGoogleFontDynamic(globalFont);
    loadGoogleFontDynamic(headingFont);

    var simHeading = document.getElementById('simSampleHeading');
    var simSubheading = document.getElementById('simSampleSubheading');
    var simBody = document.getElementById('simSampleBody');
    var simBadge = document.getElementById('simSampleBadge');
    var simAlphabet = document.getElementById('simSampleAlphabet');
    var pill = document.getElementById('activeFontPreviewPill');
    var badgeDisplay = document.getElementById('fontBadgeDisplay');

    if (simHeading) simHeading.style.fontFamily = "'" + headingFont + "', serif";
    if (simSubheading) simSubheading.style.fontFamily = "'" + headingFont + "', serif";
    if (simBody) simBody.style.fontFamily = "'" + globalFont + "', sans-serif";
    if (simBadge) simBadge.style.fontFamily = "'" + globalFont + "', sans-serif";
    if (simAlphabet) simAlphabet.style.fontFamily = "'" + globalFont + "', sans-serif";
    if (pill) pill.textContent = 'Previewing: ' + globalFont + ' + ' + headingFont;
    if (badgeDisplay) badgeDisplay.textContent = globalFont + ' + ' + headingFont;

    // Instantly update the entire admin panel typography style
    var dynamicStyle = document.getElementById('admin-dynamic-typography');
    if (dynamicStyle) {
        dynamicStyle.innerHTML = `
            :root {
                --font-family-base: '${globalFont}', 'Plus Jakarta Sans', sans-serif;
                --font-family-heading: '${headingFont}', 'Plus Jakarta Sans', sans-serif;
            }
            html, body, button, input, select, textarea, .admin-main-content, .card, .table, .form-control, .form-label, .btn, .stat-value, .badge, .premium-card, .premium-input, .module-title, .module-desc, .sidebar-brand-text, .brand-title, .brand-tag, .menu-item-text, .submenu-item span, .sidebar-heading span {
                font-family: var(--font-family-base) !important;
            }
            h1, h2, h3, h4, h5, h6, .page-header h1, .card-title, .stat-title, .section-title, .admin-logo-text {
                font-family: var(--font-family-heading) !important;
            }
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
        `;
    }
}

// Initial preview on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateDynamicFontPreview);
} else {
    updateDynamicFontPreview();
}
</script>

<?= $this->endSection() ?>
