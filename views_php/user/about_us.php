<?= view('user/header', [
    'meta_title' => $meta_title ?? 'About Selvi Tex | Authentic Handloom Sarees Elampillai',
    'meta_description' => $meta_description ?? 'Crafting tradition, weaving elegance. Authentic pure silk and cotton sarees direct from master weavers in Elampillai.',
    'meta_keywords' => $meta_keywords ?? 'Selvi Tex, Elampillai Sarees, Handloom Sarees, Pure Silk'
]) ?>

<?php
    if (!isset($settings)) {
        $settingsModel = new \App\Models\SettingsModel();
        $allSettings = $settingsModel->findAll();
        $settings = [];
        foreach ($allSettings as $row) {
            $settings[$row['key']] = $row['value'];
        }
    }

    $bizName = !empty($settings['business_name']) ? $settings['business_name'] : 'Sri Sakthi Selvi Tex';
    $contactEmail = !empty($settings['contact_email']) ? $settings['contact_email'] : 'chinnabotanyelm@gmail.com';
    $phoneNo = !empty($settings['phone_no']) ? $settings['phone_no'] : '9789296751';
    $whatsappNo = !empty($settings['whatsapp_no']) ? $settings['whatsapp_no'] : '919789296751';
    $formatWaNumber = function($num) use ($whatsappNo) {
        $c = preg_replace('/[^0-9]/', '', $num ?: $whatsappNo);
        if (strlen($c) === 10) return '91' . $c;
        if (strlen($c) === 11 && strpos($c, '0') === 0) return '91' . substr($c, 1);
        return $c ?: '919789296751';
    };
    $bizHours = !empty($settings['business_hours']) ? $settings['business_hours'] : 'Mon-Sat, 9:00 AM to 8:00 PM';

    // Multi-location support
    $storeLocations = [];
    if (!empty($settings['about_store_locations'])) {
        $decoded = json_decode($settings['about_store_locations'], true);
        if (is_array($decoded) && count($decoded) > 0) {
            $storeLocations = $decoded;
        }
    }
    if (empty($storeLocations)) {
        $storeLocations = [[
            'name'      => 'Flagship Showroom (Elampillai)',
            'address'   => $settings['about_store_address'] ?? "Sri Sakthi Selvi Tex\nMain Road, Elampillai,\nSalem District, Tamil Nadu - 637502",
            'timings'   => $settings['about_store_timings'] ?? 'Monday - Sunday: 9:00 AM - 9:00 PM',
            'map_link'  => $settings['about_store_map_link'] ?? 'https://maps.google.com/?q=Sri+Sakthi+Selvi+Tex+Elampillai',
            'map_embed' => $settings['about_store_map_embed'] ?? ($settings['google_map_embed'] ?? ''),
            'phone'     => $phoneNo,
            'whatsapp'  => $whatsappNo
        ]];
    }

    if (!function_exists('parseGoogleMapsIframeSrc')) {
        function parseGoogleMapsIframeSrc($embedInput, $fallbackAddress = '', $fallbackName = '') {
            $embedInput = trim($embedInput ?? '');
            if (!empty($embedInput)) {
                if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $embedInput, $matches)) {
                    return $matches[1];
                }
                if (str_starts_with($embedInput, 'http://') || str_starts_with($embedInput, 'https://')) {
                    return $embedInput;
                }
            }
            $query = trim(($fallbackName ? $fallbackName . ', ' : '') . $fallbackAddress);
            if (empty($query)) {
                $query = 'Sri Sakthi Selvi Tex, Main Road, Elampillai, Salem District, Tamil Nadu 637502';
            }
            return 'https://maps.google.com/maps?q=' . urlencode($query) . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
        }
    }

    // Attach processed embed URLs to locations
    foreach ($storeLocations as &$locItem) {
        $locItem['embed_src'] = parseGoogleMapsIframeSrc(
            $locItem['map_embed'] ?? ($settings['google_map_embed'] ?? ''),
            $locItem['address'] ?? '',
            $locItem['name'] ?? ''
        );
    }
    unset($locItem);

    $activeLoc = $storeLocations[0];
?>

<style>
/* ==========================================================================
   About Page Premium Styles
   ========================================================================== */
.about-page-wrap {
    background-color: #faf8f5;
    color: #1e293b;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    overflow-x: hidden;
}

/* Hero Banner */
.about-hero-section {
    position: relative;
    padding: 85px 24px 95px;
    background: linear-gradient(135deg, #1c050c 0%, #2e0915 50%, #150308 100%);
    color: #ffffff;
    text-align: center;
    border-bottom: 3px solid #d4af37;
    overflow: hidden;
}
.about-hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 50% 30%, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
    pointer-events: none;
}
.about-hero-container {
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}
.about-breadcrumb {
    font-size: 13.5px;
    color: #cbd5e1;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.about-breadcrumb a {
    color: #f1f5f9;
    text-decoration: none;
    font-weight: 500;
}
.about-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(212, 175, 55, 0.18);
    border: 1px solid rgba(212, 175, 55, 0.45);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    color: #ffd700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 16px;
}
.about-hero-title {
    font-size: 42px;
    font-weight: 800;
    margin: 0 0 16px;
    letter-spacing: -0.5px;
    color: #ffffff;
    text-shadow: 0 3px 12px rgba(0,0,0,0.5);
    line-height: 1.2;
}
.about-hero-subtitle {
    font-size: 17.5px;
    max-width: 720px;
    margin: 0 auto;
    color: #fce7f3;
    line-height: 1.65;
    font-weight: 400;
}

/* Heritage & Story Section */
.about-story-section {
    padding: 70px 24px;
    max-width: 1280px;
    margin: 0 auto;
}
.about-story-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 50px;
    align-items: center;
}
.about-story-text h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 20px;
    line-height: 1.25;
    position: relative;
    padding-left: 16px;
    border-left: 4px solid #e11d48;
}
.about-story-text p {
    font-size: 16px;
    line-height: 1.85;
    color: #475569;
    margin-bottom: 18px;
}
.about-story-quote {
    background: #fff8f0;
    border-left: 4px solid #d4af37;
    border-radius: 8px;
    padding: 16px 20px;
    margin: 24px 0;
    font-style: italic;
    color: #78350f;
    font-weight: 500;
    font-size: 15.5px;
    line-height: 1.65;
}
.about-story-media {
    position: relative;
}
.about-main-img-card {
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 18px 45px rgba(225, 29, 72, 0.12);
    border: 3px solid #ffffff;
    position: relative;
}
.about-main-img-card img {
    width: 100%;
    height: 480px;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.about-main-img-card:hover img {
    transform: scale(1.03);
}
.about-stat-badge {
    position: absolute;
    bottom: -20px;
    left: 20px;
    background: #ffffff;
    border-radius: 12px;
    padding: 14px 22px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    border: 1px solid #f1e7d8;
    display: flex;
    align-items: center;
    gap: 14px;
}
.about-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e11d48 0%, #5a0e23 100%);
    color: #ffd700;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.about-stat-text .stat-num {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.1;
}
.about-stat-text .stat-lbl {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
}

/* Values Grid */
.about-values-section {
    padding: 60px 24px;
    background: #ffffff;
    border-top: 1px solid #f1ece4;
    border-bottom: 1px solid #f1ece4;
}
.about-values-container {
    max-width: 1280px;
    margin: 0 auto;
}
.section-center-head {
    text-align: center;
    max-width: 650px;
    margin: 0 auto 45px;
}
.section-center-head .head-tag {
    font-size: 12.5px;
    font-weight: 800;
    color: #e11d48;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 8px;
    display: block;
}
.section-center-head h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px;
}
.section-center-head p {
    font-size: 15px;
    color: #64748b;
    line-height: 1.6;
}
.about-values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 24px;
}
.value-card-premium {
    background: #faf8f5;
    border: 1px solid #f0e6d8;
    border-radius: 14px;
    padding: 28px 24px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}
.value-card-premium:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(225, 29, 72,0.08);
    border-color: #d4af37;
    background: #ffffff;
}
.value-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: #fdf2f4;
    color: #e11d48;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 18px;
}
.value-card-premium h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 10px;
}
.value-card-premium p {
    font-size: 14px;
    color: #64748b;
    line-height: 1.65;
    margin: 0;
}

/* ==========================================================================
   Interactive Showroom & Embedded Google Maps Iframe Section
   ========================================================================== */
.about-showroom-section {
    padding: 80px 24px;
    background: linear-gradient(180deg, #f8f6f0 0%, #ffffff 50%, #faf8f5 100%);
    border-top: 1px solid #ede7de;
    border-bottom: 1px solid #ede7de;
}
.about-showroom-container {
    max-width: 1280px;
    margin: 0 auto;
}

/* Multi-location navigation tabs */
.about-loc-tabs-wrap {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 35px;
    flex-wrap: wrap;
}
.about-loc-tab {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    color: #334155;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 22px;
    border-radius: 30px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
.about-loc-tab:hover {
    border-color: #e11d48;
    color: #e11d48;
}
.about-loc-tab.active {
    background: linear-gradient(135deg, #e11d48 0%, #5a0e23 100%);
    border-color: #e11d48;
    color: #ffffff;
    box-shadow: 0 6px 18px rgba(225, 29, 72,0.25);
}

/* Showroom Content Grid: Details Card on Left + Interactive Map on Right */
.about-showroom-grid {
    display: grid;
    grid-template-columns: 1fr 1.15fr;
    gap: 35px;
    align-items: stretch;
}

/* Details Card */
.showroom-info-panel {
    background: #ffffff;
    border: 1.5px solid #e8e0d5;
    border-radius: 16px;
    padding: 34px 32px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.showroom-panel-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fdf2f4;
    color: #e11d48;
    font-size: 11.5px;
    font-weight: 800;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 12px;
    border: 1px solid rgba(225, 29, 72,0.15);
}
.showroom-panel-title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 16px;
}
.showroom-panel-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 26px;
}
.showroom-panel-row {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.showroom-row-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.showroom-row-icon.red { background: #fee2e2; color: #dc2626; }
.showroom-row-icon.teal { background: #ccfbf1; color: #0d9488; }
.showroom-row-icon.green { background: #dcfce7; color: #16a34a; }
.showroom-row-icon.maroon { background: #fdf2f4; color: #e11d48; }

.showroom-row-content {
    flex: 1;
}
.showroom-row-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}
.showroom-row-val {
    font-size: 14.5px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.5;
}
.showroom-row-val a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.2s;
}
.showroom-row-val a:hover {
    color: #e11d48;
}

/* Action Buttons */
.showroom-actions-row {
    display: flex;
    flex-wrap: nowrap;
    gap: 8px;
    padding-top: 18px;
    border-top: 1px dashed #e2e8f0;
    width: 100%;
}
.btn-action-directions {
    flex: 1 1 0;
    min-width: 0;
    background: #e11d48;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    padding: 11px 10px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(225, 29, 72,0.2);
    transition: all 0.2s ease;
    white-space: nowrap;
}
.btn-action-directions:hover {
    background: #5a0e23;
    transform: translateY(-2px);
    color: #ffffff;
}
.btn-action-whatsapp {
    flex: 1 1 0;
    min-width: 0;
    background: #25D366;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    padding: 11px 10px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(37,211,102,0.22);
    transition: all 0.2s ease;
    white-space: nowrap;
}
.btn-action-whatsapp:hover {
    background: #1eb854;
    transform: translateY(-2px);
    color: #ffffff;
}
.btn-action-call {
    flex: 1 1 0;
    min-width: 0;
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    color: #1e293b;
    font-size: 13px;
    font-weight: 700;
    padding: 11px 10px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.btn-action-call:hover {
    background: #ffffff;
    border-color: #94a3b8;
    transform: translateY(-2px);
}
@media (max-width: 640px) {
    .showroom-actions-row {
        flex-wrap: wrap;
    }
    .btn-action-directions,
    .btn-action-whatsapp,
    .btn-action-call {
        flex: 1 1 100%;
    }
}
.btn-action-call:hover {
    border-color: #e11d48;
    color: #e11d48;
}

/* Interactive Map Container */
.showroom-map-panel {
    background: #ffffff;
    border: 2px solid #e8e0d5;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    display: flex;
    flex-direction: column;
    min-height: 420px;
    position: relative;
}
.map-panel-header {
    background: #ffffff;
    padding: 12px 18px;
    border-bottom: 1px solid #e8e0d5;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12.5px;
    font-weight: 700;
    color: #475569;
}
.map-panel-header a {
    color: #e11d48;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.map-iframe-wrapper {
    flex: 1;
    position: relative;
    width: 100%;
    min-height: 380px;
    background: #f1f5f9;
}
.map-iframe-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
    border: 0 !important;
}

/* Callout Strip */
.about-visit-strip {
    background: linear-gradient(135deg, #e11d48 0%, #5a0e23 100%);
    border-radius: 14px;
    padding: 30px 36px;
    margin-top: 45px;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 10px 30px rgba(225, 29, 72,0.25);
}
.about-visit-strip h3 {
    font-size: 21px;
    font-weight: 800;
    margin: 0 0 6px;
    color: #ffffff;
}
.about-visit-strip p {
    font-size: 14.5px;
    margin: 0;
    color: #fce7f3;
}
.btn-video-call {
    background: #ffffff;
    color: #e11d48;
    font-size: 14px;
    font-weight: 800;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    transition: all 0.2s ease;
    white-space: nowrap;
}
.btn-video-call:hover {
    background: #ffd700;
    color: #0f172a;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 991px) {
    .about-story-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .about-showroom-grid {
        grid-template-columns: 1fr;
    }
    .about-hero-title {
        font-size: 32px;
    }
    .map-iframe-wrapper {
        min-height: 320px;
    }
}
</style>

<div class="about-page-wrap">
    <!-- 1. Hero Banner -->
    <section class="about-hero-section">
        <div class="about-hero-container">
            <div class="about-breadcrumb">
                <a href="<?= base_url('/') ?>"><i class="fa-solid fa-house" style="font-size: 11px;"></i> Home</a>
                <i class="fa-solid fa-angle-right" style="font-size: 10px; color: #d4af37;"></i>
                <span style="color: #ffd700; font-weight: 600;">About Us</span>
            </div>

            <div class="about-badge">
                <i class="fa-solid fa-crown" style="font-size: 11px;"></i> Authentic Master Weavers &bull; Elampillai
            </div>

            <h1 class="about-hero-title">About <?= esc($bizName) ?></h1>
            <p class="about-hero-subtitle">
                Crafting heritage, weaving elegance. Bringing you authentic pure silk, semi-silk, and designer handloom sarees directly from the traditional loom village of Elampillai, Salem.
            </p>
        </div>
    </section>

    <!-- 2. Heritage & Story Section -->
    <section class="about-story-section">
        <div class="about-story-grid">
            <div class="about-story-text">
                <div style="font-size: 12.5px; font-weight: 800; color: #d4af37; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 6px;">
                    OUR WEAVING HERITAGE
                </div>
                <h2>Weaving Dreams into Reality Since Decades</h2>
                <p>
                    Welcome to <strong><?= esc($bizName) ?></strong>, where centuries-old Tamil Nadu handloom craftsmanship converges with contemporary design and refined grace. Situated in the heart of <strong>Elampillai, Salem District</strong>—famed worldwide as the hub of intricate weaving—our looms have been weaving timeless drapes cherished by thousands of brides and families across the globe.
                </p>
                <p>
                    Our journey began with an unwavering dedication: to preserve and elevate the rich handloom heritage of Indian textiles while connecting discerning customers directly with master artisans. Every single saree in our showroom is an authentic testament to the artisan's precision, patience, and weaving mastery.
                </p>

                <div class="about-story-quote">
                    "Every warp and weft woven into our sarees carries generations of Elampillai artisanal dedication, natural dyes, and timeless Tamil Nadu weaving excellence."
                </div>

                <p>
                    By eliminating middlemen, we ensure that our artisan weavers receive honorable livelihoods, while you receive heirloom-quality sarees at direct loom-wholesale prices.
                </p>
            </div>

            <div class="about-story-media">
                <div class="about-main-img-card">
                    <img src="http://localhost/sri_sakthi/ssptex.com/cdn/shop/collections/boutique-collection-8142150_v=1783150389&width=600.jpg" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="Elampillai Weaving Heritage">
                </div>
                <div class="about-stat-badge">
                    <div class="about-stat-icon">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div class="about-stat-text">
                        <div class="stat-num">100% Genuine</div>
                        <div class="stat-lbl">Direct Loom Handlooms</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Pillars of Excellence -->
    <section class="about-values-section">
        <div class="about-values-container">
            <div class="section-center-head">
                <span class="head-tag">WHY CHOOSE US</span>
                <h2>The <?= esc($bizName) ?> Promise</h2>
                <p>Built upon the trust of our customers and the pride of our weaving families.</p>
            </div>

            <div class="about-values-grid">
                <div class="value-card-premium">
                    <div class="value-card-icon"><i class="fa-solid fa-gem"></i></div>
                    <h3>Certified Premium Threads</h3>
                    <p>We source only high-grade pure silk and combed cotton yarns to ensure unmatched durability, softness, and vibrant sheen.</p>
                </div>

                <div class="value-card-premium">
                    <div class="value-card-icon"><i class="fa-solid fa-handshake"></i></div>
                    <h3>Zero Middlemen Markup</h3>
                    <p>Direct from our looms in Elampillai to your wardrobe. Honest pricing without dealership or retail markups.</p>
                </div>

                <div class="value-card-premium">
                    <div class="value-card-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                    <h3>Exclusive Jacquard Motifs</h3>
                    <p>Unique traditional temple borders, floral jaal, zari pallu designs, and modern pastel color palettes tailored for every celebration.</p>
                </div>

                <div class="value-card-premium">
                    <div class="value-card-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <h3>Worldwide Express Delivery</h3>
                    <p>Carefully hand-folded in moisture-barrier packaging and shipped with express insured courier to India and international destinations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Interactive Showroom Locations & Embedded Google Maps Section -->
    <section class="about-showroom-section" id="showroom-section">
        <div class="about-showroom-container">
            <div class="section-center-head">
                <span class="head-tag"><i class="fa-solid fa-location-dot"></i> VISIT OUR SHOWROOM</span>
                <h2>Experience Our Sarees in Person</h2>
                <p>Feel the drape, admire the zari sheen, and meet our master weavers at our showroom in Elampillai. Toggle below for showroom details and live map directions.</p>
            </div>

            <!-- Multi-Location Tabs (if 2 or more locations configured) -->
            <?php if (count($storeLocations) > 1): ?>
            <div class="about-loc-tabs-wrap">
                <?php foreach($storeLocations as $idx => $loc): ?>
                    <button type="button" class="about-loc-tab <?= $idx === 0 ? 'active' : '' ?>" onclick="switchAboutLocation(<?= $idx ?>, this)">
                        <i class="fa-solid fa-map-pin"></i>
                        <span><?= esc($loc['name']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="about-showroom-grid">
                <!-- Left: Showroom Details Card -->
                <div class="showroom-info-panel">
                    <div>
                        <span class="showroom-panel-badge" id="about-loc-badge">
                            <i class="fa-solid fa-store"></i> <span id="about-badge-text">FLAGSHIP SHOWROOM</span>
                        </span>
                        <h3 class="showroom-panel-title" id="about-loc-title">
                            <?= esc($activeLoc['name'] ?? 'Flagship Showroom (Elampillai)') ?>
                        </h3>

                        <div class="showroom-panel-items">
                            <!-- Address -->
                            <div class="showroom-panel-row">
                                <div class="showroom-row-icon red">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="showroom-row-content">
                                    <div class="showroom-row-label">Physical Address</div>
                                    <div class="showroom-row-val" id="about-loc-address">
                                        <?= nl2br(esc($activeLoc['address'])) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Timings -->
                            <div class="showroom-panel-row">
                                <div class="showroom-row-icon teal">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div class="showroom-row-content">
                                    <div class="showroom-row-label">Operating Hours</div>
                                    <div class="showroom-row-val" id="about-loc-timings">
                                        <?= esc($activeLoc['timings']) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="showroom-panel-row">
                                <div class="showroom-row-icon maroon">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="showroom-row-content">
                                    <div class="showroom-row-label">Customer Care &amp; Inquiries</div>
                                    <div class="showroom-row-val">
                                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $activeLoc['phone'] ?? $phoneNo) ?>" id="about-loc-phone">
                                            <?= esc($activeLoc['phone'] ?? $phoneNo) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="showroom-panel-row">
                                <div class="showroom-row-icon green">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <div class="showroom-row-content">
                                    <div class="showroom-row-label">WhatsApp Desk</div>
                                    <div class="showroom-row-val">
                                        <a href="https://wa.me/<?= $formatWaNumber($activeLoc['whatsapp'] ?? $whatsappNo) ?>?text=<?= urlencode('Hello ' . $bizName . ', I would like to enquire about your saree collections.') ?>" target="_blank" id="about-loc-whatsapp">
                                            <?= esc($activeLoc['whatsapp'] ?? $whatsappNo) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Support Email -->
                            <div class="showroom-panel-row">
                                <div class="showroom-row-icon maroon">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="showroom-row-content">
                                    <div class="showroom-row-label">Official Support Email</div>
                                    <div class="showroom-row-val">
                                        <a href="mailto:<?= esc($contactEmail) ?>">
                                            <?= esc($contactEmail) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Channels Container -->
                            <div class="showroom-panel-row" style="background: #faf8f5; border: 1px solid #ebdcd0; border-radius: 10px; padding: 12px 14px; margin-top: 4px;">
                                <div class="showroom-row-icon maroon" style="background: #fdf2f4; color: #e11d48;">
                                    <i class="fa-solid fa-share-nodes"></i>
                                </div>
                                <div class="showroom-row-content">
                                    <div class="showroom-row-label" style="color: #e11d48;">Connect On Social Media</div>
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 6px;">
                                        <a href="https://wa.me/<?= $formatWaNumber($activeLoc['whatsapp'] ?? $whatsappNo) ?>" target="_blank" style="width: 34px; height: 34px; border-radius: 8px; background: #25D366; color: white; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 16px;" title="Chat on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                                        <a href="<?= esc(!empty($settings['youtube_link']) ? $settings['youtube_link'] : 'https://youtube.com/@srisakthiselvitex') ?>" target="_blank" style="width: 34px; height: 34px; border-radius: 8px; background: #FF0000; color: white; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 16px;" title="YouTube Channel"><i class="fa-brands fa-youtube"></i></a>
                                        <a href="<?= esc(!empty($settings['instagram_link']) ? $settings['instagram_link'] : 'https://instagram.com/srisakthiselvitex') ?>" target="_blank" style="width: 34px; height: 34px; border-radius: 8px; background: #E1306C; color: white; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 16px;" title="Instagram Page"><i class="fa-brands fa-instagram"></i></a>
                                        <a href="<?= esc(!empty($settings['facebook_link']) ? $settings['facebook_link'] : 'https://facebook.com/srisakthiselvitex') ?>" target="_blank" style="width: 34px; height: 34px; border-radius: 8px; background: #1877F2; color: white; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 16px;" title="Facebook Page"><i class="fa-brands fa-facebook-f"></i></a>
                                        <a href="<?= esc(!empty($settings['twitter_link']) ? $settings['twitter_link'] : 'https://twitter.com/srisakthiselvitex') ?>" target="_blank" style="width: 34px; height: 34px; border-radius: 8px; background: #0f172a; color: white; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 15px;" title="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="showroom-actions-row">
                        <a href="<?= esc($activeLoc['map_link'] ?? 'https://maps.google.com') ?>" target="_blank" id="about-btn-directions" class="btn-action-directions">
                            <i class="fa-solid fa-diamond-turn-right"></i>
                            <span>Get Driving Directions</span>
                        </a>

                        <a href="https://wa.me/<?= $formatWaNumber($activeLoc['whatsapp'] ?? $whatsappNo) ?>?text=<?= urlencode('Hello ' . $bizName . ', I am planning to visit your showroom.') ?>" target="_blank" id="about-btn-whatsapp" class="btn-action-whatsapp">
                            <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i>
                            <span>Chat on WhatsApp</span>
                        </a>

                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $activeLoc['phone'] ?? $phoneNo) ?>" id="about-btn-call" class="btn-action-call">
                            <i class="fa-solid fa-phone" style="color: #e11d48;"></i>
                            <span>Call Now</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Interactive Live Google Maps Iframe -->
                <div class="showroom-map-panel">
                    <div class="map-panel-header">
                        <span><i class="fa-solid fa-map" style="color: #e11d48; margin-right: 6px;"></i> Live Google Maps Location</span>
                        <a href="<?= esc($activeLoc['map_link'] ?? 'https://maps.google.com') ?>" target="_blank" id="about-map-external-link">
                            <span>Open in Google Maps</span> <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i>
                        </a>
                    </div>
                    <div class="map-iframe-wrapper">
                        <iframe id="about-google-maps-iframe" 
                                src="<?= esc($activeLoc['embed_src']) ?>" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade" 
                                title="Showroom Google Maps Location">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- 5. Video Call Saree Selection Strip -->
            <div class="about-visit-strip">
                <div>
                    <h3>Living Outside Salem or Overseas?</h3>
                    <p>Book a dedicated live video call with our weavers to inspect sarees, borders, and colors in natural daylight before ordering.</p>
                </div>
                <div>
                    <a href="https://wa.me/<?= $formatWaNumber($whatsappNo) ?>?text=<?= urlencode('Hello ' . $bizName . ', I would like to schedule a Live Video Call Saree Walkthrough.') ?>" target="_blank" class="btn-video-call">
                        <i class="fa-brands fa-whatsapp" style="color: #25D366; font-size: 18px;"></i>
                        <span>Book Live Video Call</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var aboutLocationsData = <?= json_encode($storeLocations) ?>;

function switchAboutLocation(idx, btn) {
    if (!aboutLocationsData || !aboutLocationsData[idx]) return;
    var loc = aboutLocationsData[idx];

    // Toggle active class on tab buttons
    document.querySelectorAll('.about-loc-tab').forEach(function(el) {
        el.classList.remove('active');
    });
    if (btn) btn.classList.add('active');

    // Update Text Elements
    var titleEl = document.getElementById('about-loc-title');
    if (titleEl) titleEl.textContent = loc.name || 'Showroom Location';

    var badgeText = document.getElementById('about-badge-text');
    if (badgeText) badgeText.textContent = (idx === 0) ? 'FLAGSHIP SHOWROOM' : ('BRANCH #' + (idx + 1));

    var addrEl = document.getElementById('about-loc-address');
    if (addrEl) addrEl.innerHTML = (loc.address || '').replace(/\n/g, '<br>');

    var timingsEl = document.getElementById('about-loc-timings');
    if (timingsEl) timingsEl.textContent = loc.timings || 'Monday - Sunday: 9:00 AM - 9:00 PM';

    var phoneEl = document.getElementById('about-loc-phone');
    if (phoneEl) {
        phoneEl.textContent = loc.phone || '<?= esc($phoneNo) ?>';
        var cleanPhone = (loc.phone || '<?= esc($phoneNo) ?>').replace(/[^0-9+]/g, '');
        phoneEl.href = 'tel:' + cleanPhone;
    }

    var waEl = document.getElementById('about-loc-whatsapp');
    if (waEl) {
        waEl.textContent = loc.whatsapp || '<?= esc($whatsappNo) ?>';
        var cleanWa = (loc.whatsapp || '<?= esc($whatsappNo) ?>').replace(/[^0-9]/g, '');
        if (cleanWa.length === 10) { cleanWa = '91' + cleanWa; }
        else if (cleanWa.length === 11 && cleanWa.startsWith('0')) { cleanWa = '91' + cleanWa.substring(1); }
        waEl.href = 'https://wa.me/' + cleanWa;
    }

    // Action button links
    var dirBtn = document.getElementById('about-btn-directions');
    if (dirBtn) dirBtn.href = loc.map_link || 'https://maps.google.com';

    var extMapLink = document.getElementById('about-map-external-link');
    if (extMapLink) extMapLink.href = loc.map_link || 'https://maps.google.com';

    var callBtn = document.getElementById('about-btn-call');
    if (callBtn) {
        var cleanPhone = (loc.phone || '<?= esc($phoneNo) ?>').replace(/[^0-9+]/g, '');
        callBtn.href = 'tel:' + cleanPhone;
    }

    var waBtn = document.getElementById('about-btn-whatsapp');
    if (waBtn) {
        var cleanWa = (loc.whatsapp || '<?= esc($whatsappNo) ?>').replace(/[^0-9]/g, '');
        if (cleanWa.length === 10) { cleanWa = '91' + cleanWa; }
        else if (cleanWa.length === 11 && cleanWa.startsWith('0')) { cleanWa = '91' + cleanWa.substring(1); }
        waBtn.href = 'https://wa.me/' + cleanWa + '?text=' + encodeURIComponent('Hello <?= addslashes($bizName) ?>, I am planning to visit your ' + (loc.name || 'showroom') + '.');
    }

    // Update Google Maps Iframe src
    var iframe = document.getElementById('about-google-maps-iframe');
    if (iframe && loc.embed_src) {
        iframe.src = loc.embed_src;
    }
}
</script>

<?= view('user/footer') ?>
