<?php 
    $settingsModel = new \App\Models\SettingsModel();
    $allSettings = $settingsModel->findAll();
    $settings = [];
    foreach ($allSettings as $row) {
        $settings[$row['key']] = $row['value'];
    }

    $rawBizName = !empty($settings['business_name']) ? $settings['business_name'] : 'Selvi Tex';
    $bizName = trim(preg_replace('/\s*est\.?\s*\d{4}/i', '', $rawBizName)) ?: 'Selvi Tex';
    $bizAddress = !empty($settings['business_address']) ? $settings['business_address'] : (!empty($settings['about_store_address']) ? $settings['about_store_address'] : 'D/No.13/10, Periya Mammu St, Elampillai to Chinnapampattii, Main Road, Near Bavana Ganapathi Kovil, Elampillai - 637502');
    $phoneNo = !empty($settings['phone_no']) ? $settings['phone_no'] : (!empty($settings['about_store_phone']) ? $settings['about_store_phone'] : '9789296751');
    $whatsappNo = !empty($settings['whatsapp_no']) ? $settings['whatsapp_no'] : (!empty($settings['about_store_whatsapp']) ? $settings['about_store_whatsapp'] : (!empty($settings['social_whatsapp']) ? $settings['social_whatsapp'] : '919789296751'));
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsappNo) ?: '919789296751';
    if (strlen($cleanWhatsapp) === 10) {
        $cleanWhatsapp = '91' . $cleanWhatsapp;
    } elseif (strlen($cleanWhatsapp) === 11 && strpos($cleanWhatsapp, '0') === 0) {
        $cleanWhatsapp = '91' . substr($cleanWhatsapp, 1);
    }
    $gstNo = $settings['gst_number'] ?? '';
    $logoUrl = !empty($settings['business_logo']) ? base_url($settings['business_logo']) : base_url('assets/images/logo.png');
    $footerEmail = !empty($settings['contact_email']) ? $settings['contact_email'] : (!empty($settings['support_email']) ? $settings['support_email'] : 'support@selvitex.com');

    // Cart Count for Mobile Bar
    $footerCartCount = 0;
    $cartSession = session()->get('cart') ?? [];
    foreach ($cartSession as $cItem) {
        $footerCartCount += (int)($cItem['quantity'] ?? 1);
    }

    // Dynamic Footer Links
    $footerLinkModel = new \App\Models\FooterLinkModel();
    $customerCareLinks = $footerLinkModel->where('section', 'customer_care')->where('is_active', 1)->orderBy('display_order', 'ASC')->findAll();
    $collectionsLinks = $footerLinkModel->where('section', 'our_collections')->where('is_active', 1)->orderBy('display_order', 'ASC')->findAll();
?>

<!-- ==========================================================================
     Modern Redesigned Storefront Footer
     ========================================================================== -->
<footer class="storefront-modern-footer">
    <!-- 1. Heritage Trust & Highlights Bar -->
    <div class="footer-trust-strip">
        <div class="footer-container">
            <div class="footer-trust-grid">
                <div class="trust-strip-item">
                    <div class="trust-strip-icon"><i class="fa-solid fa-certificate"></i></div>
                    <div class="trust-strip-text">
                        <strong>100% Authentic Handloom</strong>
                        <span>Directly woven in Elampillai</span>
                    </div>
                </div>
                <div class="trust-strip-item">
                    <div class="trust-strip-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="trust-strip-text">
                        <strong>Free Express Shipping</strong>
                        <span>On all orders above ₹1,000</span>
                    </div>
                </div>
                <div class="trust-strip-item">
                    <div class="trust-strip-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="trust-strip-text">
                        <strong>100% Secure Payment</strong>
                        <span>Razorpay &amp; Cash on Delivery</span>
                    </div>
                </div>
                <div class="trust-strip-item">
                    <div class="trust-strip-icon"><i class="fa-solid fa-handshake-angle"></i></div>
                    <div class="trust-strip-text">
                        <strong>Direct Weaver Pricing</strong>
                        <span>Zero middlemen markup</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Main 4-Column Footer Content -->
    <div class="footer-container main-footer-body">
        <div class="footer-main-grid">
            
            <!-- Column 1: Brand & Heritage Details -->
            <div class="footer-col footer-col-brand">
                <a href="<?= base_url('/') ?>" class="footer-brand-logo-link">
                    <img src="<?= $logoUrl ?>" alt="<?= esc($bizName) ?>" class="footer-brand-logo" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
                    <span class="footer-brand-name"><?= esc($bizName) ?></span>
                </a>
                <p class="footer-brand-desc">
                    Preserving centuries of Elampillai handloom heritage. We craft exquisite pure silk, fancy cotton, and wedding festive sarees directly from our master weavers to your wardrobe.
                </p>

                <div class="footer-contact-details">
                    <div class="footer-contact-line">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?= esc($bizAddress) ?></span>
                    </div>
                    <?php if(!empty($phoneNo)): ?>
                    <div class="footer-contact-line">
                        <i class="fa-solid fa-phone"></i>
                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phoneNo) ?>"><?= esc($phoneNo) ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($footerEmail)): ?>
                    <div class="footer-contact-line">
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:<?= esc($footerEmail) ?>"><?= esc($footerEmail) ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($cleanWhatsapp)): ?>
                    <div class="footer-contact-line">
                        <i class="fa-brands fa-whatsapp" style="color: #25D366;"></i>
                        <a href="https://wa.me/<?= esc($cleanWhatsapp) ?>?text=<?= urlencode('Hello ' . $bizName . ', I have an inquiry about sarees.') ?>" target="_blank">Chat with us on WhatsApp</a>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($gstNo)): ?>
                    <div class="footer-contact-line footer-gst-tag">
                        <i class="fa-solid fa-receipt"></i>
                        <span>GSTIN: <strong><?= esc($gstNo) ?></strong></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="footer-social-strip">
                    <a href="https://wa.me/<?= esc($cleanWhatsapp) ?>?text=<?= urlencode('Hello ' . $bizName . ', I have an inquiry about sarees.') ?>" target="_blank" aria-label="WhatsApp" class="social-icon-btn" title="Chat on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="<?= esc(!empty($settings['youtube_link']) ? $settings['youtube_link'] : 'https://youtube.com/@srisakthiselvitex') ?>" target="_blank" aria-label="YouTube" class="social-icon-btn" title="Watch on YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="<?= esc(!empty($settings['instagram_link']) ? $settings['instagram_link'] : 'https://instagram.com/srisakthiselvitex') ?>" target="_blank" aria-label="Instagram" class="social-icon-btn" title="Follow on Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="<?= esc(!empty($settings['facebook_link']) ? $settings['facebook_link'] : 'https://facebook.com/srisakthiselvitex') ?>" target="_blank" aria-label="Facebook" class="social-icon-btn" title="Follow on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="<?= esc(!empty($settings['twitter_link']) ? $settings['twitter_link'] : 'https://twitter.com/srisakthiselvitex') ?>" target="_blank" aria-label="Twitter / X" class="social-icon-btn" title="Follow on Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>

            <!-- Column 2: Exclusive Collections -->
            <div class="footer-col">
                <h4 class="footer-col-title">Our Collections</h4>
                <ul class="footer-nav-list">
                    <?php if(!empty($collectionsLinks)): ?>
                        <?php foreach($collectionsLinks as $fl): 
                            $linkTarget = !empty($fl['open_in_new_tab']) ? ' target="_blank" rel="noopener noreferrer"' : '';
                            $rawUrl = trim($fl['url']);
                            $linkHref = (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) ? esc($rawUrl) : base_url(ltrim($rawUrl, '/'));
                        ?>
                            <li><a href="<?= $linkHref ?>"<?= $linkTarget ?>><i class="fa-solid fa-angle-right"></i> <?= esc($fl['title']) ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a href="<?= base_url('category/silk') ?>"><i class="fa-solid fa-angle-right"></i> Pure Silk Sarees</a></li>
                        <li><a href="<?= base_url('category/cotton') ?>"><i class="fa-solid fa-angle-right"></i> Cotton Sarees</a></li>
                        <li><a href="<?= base_url('category/sarees') ?>"><i class="fa-solid fa-angle-right"></i> Elampillai Sarees</a></li>
                        <li><a href="<?= base_url('collections/new-arrivals') ?>"><i class="fa-solid fa-angle-right"></i> New Arrivals</a></li>
                        <li><a href="<?= base_url('collections/best-sellers') ?>"><i class="fa-solid fa-angle-right"></i> Best Sellers</a></li>
                        <li><a href="<?= base_url('collections') ?>"><i class="fa-solid fa-angle-right"></i> All Saree Collections</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 3: Customer Care & Policies -->
            <div class="footer-col">
                <h4 class="footer-col-title">Customer Care</h4>
                <ul class="footer-nav-list">
                    <?php if(!empty($customerCareLinks)): ?>
                        <?php foreach($customerCareLinks as $fl): 
                            $linkTarget = !empty($fl['open_in_new_tab']) ? ' target="_blank" rel="noopener noreferrer"' : '';
                            $rawUrl = trim($fl['url']);
                            $linkHref = (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) ? esc($rawUrl) : base_url(ltrim($rawUrl, '/'));
                        ?>
                            <li><a href="<?= $linkHref ?>"<?= $linkTarget ?>><i class="fa-solid fa-angle-right"></i> <?= esc($fl['title']) ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a href="<?= base_url('pages/about-selvi-tex') ?>"><i class="fa-solid fa-angle-right"></i> About Our Story</a></li>
                        <li><a href="<?= base_url('policies/contact-information') ?>"><i class="fa-solid fa-angle-right"></i> Contact &amp; Support</a></li>
                        <li><a href="<?= base_url('policies/shipping-policy') ?>"><i class="fa-solid fa-angle-right"></i> Shipping &amp; Delivery</a></li>
                        <li><a href="<?= base_url('policies/refund-policy') ?>"><i class="fa-solid fa-angle-right"></i> Returns &amp; Refund Policy</a></li>
                        <li><a href="<?= base_url('policies/privacy-policy') ?>"><i class="fa-solid fa-angle-right"></i> Privacy Policy</a></li>
                        <li><a href="<?= base_url('policies/terms-of-service') ?>"><i class="fa-solid fa-angle-right"></i> Terms of Service</a></li>
                        <li><a href="<?= base_url('blogs/news') ?>"><i class="fa-solid fa-angle-right"></i> Saree Care Blogs</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 4: Newsletter & Exclusive Perks -->
            <div class="footer-col footer-col-newsletter">
                <h4 class="footer-col-title">Join The <?= esc($settings['short_name'] ?? 'Selvi Tex') ?> Family</h4>
                <p class="footer-newsletter-text">
                    Subscribe to receive early access to festive saree drops, Elampillai weaver stories, and exclusive secret discount vouchers.
                </p>

                <form id="footer-newsletter-form" class="footer-newsletter-box" onsubmit="handleFooterNewsletterSubmit(event, this)">
                    <div class="footer-input-wrap">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" id="footer-sub-email" placeholder="Enter your email address" required autocomplete="email">
                    </div>
                    <button type="submit" id="footer-sub-btn" class="footer-subscribe-btn">
                        <span>Subscribe</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="footer-secure-guarantee">
                    <i class="fa-solid fa-lock"></i>
                    <span>Your privacy is 100% protected. No spam, ever.</span>
                </div>
            </div>

        </div>

        <!-- 3. Bottom Copyright & Payment Strip -->
        <div class="footer-bottom-strip">
            <div class="footer-copyright-text">
                &copy; <?= date('Y') ?> <strong><?= esc($bizName) ?></strong>. Authentic Elampillai Handloom Sarees. All Rights Reserved.
            </div>
            
            <div class="footer-payment-badges">
                <span class="payment-shield-label"><i class="fa-solid fa-shield-halved"></i> 100% Secure Checkout:</span>
                <div class="payment-icon-pill" title="Visa Card"><i class="fa-brands fa-cc-visa"></i></div>
                <div class="payment-icon-pill" title="Mastercard"><i class="fa-brands fa-cc-mastercard"></i></div>
                <div class="payment-icon-pill" title="UPI & QR Payments"><i class="fa-solid fa-qrcode"></i> UPI</div>
                <div class="payment-icon-pill" title="Cash on Delivery Available"><i class="fa-solid fa-money-bill-wave"></i> COD</div>
            </div>
        </div>
    </div>
</footer>

<!-- ==========================================================================
     Mobile Bottom App Navigation Bar (Shown on Mobile screens <= 768px only)
     Streamlined 4-tab bar: Home, Collections, Phone Call, WhatsApp Chat
     (Cart & Account profile are conveniently accessible in the top header)
     ========================================================================== -->
<?php 
    $callPhone = !empty($phoneNo) ? $phoneNo : (!empty($settings['about_store_phone']) ? $settings['about_store_phone'] : '9789296751');
    $cleanCallPhone = preg_replace('/[^0-9+]/', '', $callPhone);
?>
<nav class="mobile-bottom-app-bar" aria-label="Mobile Navigation">
    <a href="<?= base_url('/') ?>" class="mobile-bar-tab <?= (current_url() == base_url('/') || current_url() == base_url('index.php')) ? 'active' : '' ?>">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
    </a>
    <a href="<?= base_url('collections') ?>" class="mobile-bar-tab <?= (strpos(current_url(), 'collections') !== false || strpos(current_url(), 'category') !== false) ? 'active' : '' ?>">
        <i class="fa-solid fa-border-all"></i>
        <span>Collections</span>
    </a>
    <?php if(!empty($cleanCallPhone)): ?>
    <a href="tel:<?= esc($cleanCallPhone) ?>" class="mobile-bar-tab mobile-bar-call-tab" aria-label="Call Store" title="Call Us: <?= esc($callPhone) ?>">
        <i class="fa-solid fa-phone"></i>
        <span>Call</span>
    </a>
    <?php endif; ?>
    <?php if(!empty($cleanWhatsapp)): ?>
    <a href="https://wa.me/<?= esc($cleanWhatsapp) ?>?text=<?= urlencode('Hello ' . $bizName . ', I am interested in sarees.') ?>" target="_blank" class="mobile-bar-tab mobile-bar-chat-tab" aria-label="Chat on WhatsApp" title="WhatsApp Chat">
        <i class="fa-brands fa-whatsapp"></i>
        <span>WhatsApp</span>
    </a>
    <?php endif; ?>
</nav>

<!-- Cart Drawer Component -->
<?= view('user/cart_drawer') ?>

<!-- Core JavaScript Assets -->
<script src="<?= base_url('assets/js/script.js') ?>?v=<?= time() ?>"></script>
<script src="<?= base_url('assets/js/trust-highlights.js') ?>?v=<?= time() ?>"></script>
<script src="<?= base_url('assets/js/product-variants.js') ?>?v=<?= time() ?>"></script>

<!-- Floating Action Buttons -->
<?php 
    $callPhone = !empty($phoneNo) ? $phoneNo : (!empty($settings['about_store_phone']) ? $settings['about_store_phone'] : '9789296751');
    $cleanCallPhone = preg_replace('/[^0-9+]/', '', $callPhone);
?>
<div class="floating-contact-container">
    <?php if(!empty($cleanCallPhone)): ?>
    <a href="tel:<?= esc($cleanCallPhone) ?>" class="floating-call" aria-label="Make Phone Call" title="Call Us: <?= esc($callPhone) ?>">
        <i class="fa-solid fa-phone"></i>
    </a>
    <?php endif; ?>

    <?php if(!empty($whatsappNo)): ?>
    <a href="https://wa.me/<?= esc($cleanWhatsapp) ?>" target="_blank" class="floating-whatsapp" aria-label="Chat on WhatsApp" title="Chat on WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
    <?php endif; ?>
</div>

<button id="scrollToTopBtn" class="floating-scroll-top" aria-label="Scroll to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<style>
/* ==========================================================================
   Storefront Modern Redesigned Footer Styling
   ========================================================================== */
.storefront-modern-footer {
    background: #1a050b;
    color: #e2e8f0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    position: relative;
    border-top: 3px solid #d4af37;
    margin-top: auto;
}

/* 1. Heritage Trust Strip */
.footer-trust-strip {
    background: #2b0812;
    border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    padding: 24px 0;
}

.footer-trust-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.trust-strip-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 0 10px;
}

.trust-strip-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.12);
    border: 1.5px solid #d4af37;
    color: #ffd700;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.trust-strip-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.trust-strip-text strong {
    font-size: 13.5px;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: -0.1px;
}

.trust-strip-text span {
    font-size: 12px;
    color: #cbd5e1;
}

/* 2. Main Footer Body */
.main-footer-body {
    padding-top: 50px;
    padding-bottom: 30px;
}

.footer-main-grid {
    display: grid;
    grid-template-columns: 2fr 1.1fr 1.1fr 1.6fr;
    gap: 40px;
    margin-bottom: 45px;
}

/* Brand Column */
.footer-brand-logo-link {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    margin-bottom: 16px;
}

.footer-brand-logo {
    height: 52px;
    width: auto;
    object-fit: contain;
    border-radius: 6px;
}

.footer-brand-name {
    font-family: 'Times New Roman', serif;
    font-size: 24px;
    font-weight: 800;
    color: #ffd700;
    letter-spacing: 0.5px;
}

.footer-brand-desc {
    font-size: 13.5px;
    line-height: 1.7;
    color: #cbd5e1;
    margin: 0 0 20px 0;
    max-width: 420px;
}

.footer-contact-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 22px;
}

.footer-contact-line {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 13px;
    color: #cbd5e1;
    line-height: 1.6;
}

.footer-contact-line i {
    color: #d4af37;
    font-size: 15px;
    margin-top: 3px;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
}

.footer-contact-line a {
    color: #cbd5e1;
    text-decoration: none;
    transition: color 0.2s;
}

.footer-contact-line a:hover {
    color: #ffd700;
}

.footer-gst-tag {
    background: rgba(255, 255, 255, 0.05);
    padding: 4px 10px;
    border-radius: 4px;
    border-left: 3px solid #d4af37;
    width: fit-content;
}

.footer-social-strip {
    display: flex;
    gap: 10px;
}

.social-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.25s ease;
}

.social-icon-btn:hover {
    background: #d4af37;
    color: #1a050b;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.35);
}

/* Nav Columns */
.footer-col-title {
    font-size: 16px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 20px 0;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    position: relative;
    padding-bottom: 10px;
}

.footer-col-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 35px;
    height: 2px;
    background: #d4af37;
    border-radius: 2px;
}

.footer-nav-list {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex;
    flex-direction: column;
    gap: 11px;
}

.footer-nav-list li {
    margin: 0 !important;
    padding: 0 !important;
}

.footer-nav-list a {
    color: #cbd5e1;
    text-decoration: none;
    font-size: 13.5px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.footer-nav-list a i {
    font-size: 10px;
    color: #d4af37;
    transition: transform 0.2s;
}

.footer-nav-list a:hover {
    color: #ffd700;
    transform: translateX(4px);
}

.footer-nav-list a:hover i {
    transform: translateX(2px);
}

/* Newsletter Column */
.footer-newsletter-text {
    font-size: 13.5px;
    color: #cbd5e1;
    line-height: 1.6;
    margin: 0 0 16px 0;
}

.footer-newsletter-box {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 14px;
}

.footer-input-wrap {
    position: relative;
    width: 100%;
}

.footer-input-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
}

.footer-input-wrap input {
    width: 100%;
    height: 44px;
    padding: 0 16px 0 42px;
    background: rgba(255, 255, 255, 0.08);
    border: 1.5px solid rgba(255, 255, 255, 0.16);
    border-radius: 6px;
    color: #ffffff;
    font-size: 13.5px;
    outline: none;
    box-sizing: border-box;
    transition: all 0.2s ease;
}

.footer-input-wrap input:focus {
    background: rgba(255, 255, 255, 0.14);
    border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.18);
}

.footer-subscribe-btn {
    width: 100%;
    height: 44px;
    background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%);
    color: #1a050b;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.28);
    transition: all 0.25s ease;
}

.footer-subscribe-btn:hover {
    background: linear-gradient(135deg, #ffd700 0%, #d4af37 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(212, 175, 55, 0.4);
}

.footer-secure-guarantee {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11.5px;
    color: #94a3b8;
}

.footer-secure-guarantee i {
    color: #22c55e;
}

/* 3. Bottom Strip */
.footer-bottom-strip {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.footer-copyright-text {
    font-size: 13px;
    color: #94a3b8;
}

.footer-copyright-text strong {
    color: #ffd700;
}

.footer-payment-badges {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.payment-shield-label {
    font-size: 12px;
    color: #cbd5e1;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-right: 4px;
}

.payment-icon-pill {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.14);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.payment-icon-pill i {
    font-size: 16px;
    color: #ffd700;
}

/* ==========================================================================
   Mobile Bottom App Navigation Bar (STRICTLY MOBILE & TABLET RESPONSIVE)
   ========================================================================== */
.mobile-bottom-app-bar {
    display: none !important; /* STRICTLY HIDDEN ON DESKTOP SCREENS */
}

@media (max-width: 991px) {
    /* Reveal sticky fixed bottom app bar on mobile & tablet screens */
    .mobile-bottom-app-bar {
        display: flex !important;
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        height: 60px !important;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border-top: 1.5px solid rgba(212, 175, 55, 0.4) !important;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1) !important;
        z-index: 999999 !important;
        justify-content: space-around !important;
        align-items: center !important;
        padding: 0 4px !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        transform: translateZ(0) !important;
        padding-bottom: env(safe-area-inset-bottom, 0px) !important;
    }

    .mobile-bar-tab {
        flex: 1 1 25% !important;
        max-width: 25% !important;
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 3px !important;
        text-decoration: none !important;
        color: #64748b !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        border: none !important;
        background: transparent !important;
        padding: 4px 0 !important;
    }

    .mobile-bar-tab i {
        font-size: 18px !important;
        transition: transform 0.2s ease !important;
    }

    .mobile-bar-tab.active,
    .mobile-bar-tab:hover {
        color: #7a1530 !important;
    }

    .mobile-bar-call-tab i {
        color: #0284c7 !important;
        font-size: 18px !important;
    }

    .mobile-bar-call-tab:hover {
        color: #0369a1 !important;
    }

    .mobile-bar-chat-tab i {
        color: #25D366 !important;
        font-size: 20px !important;
    }

    .mobile-bar-chat-tab:hover {
        color: #128C7E !important;
    }

    /* Hide obstructive floating contact container on mobile to prevent blocking content */
    .floating-contact-container {
        display: none !important;
    }

    .mobile-bar-tab.active i {
        transform: scale(1.1) !important;
    }

    .mobile-bar-cart-icon-wrap {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .mobile-bar-cart-badge {
        position: absolute !important;
        top: -6px !important;
        right: -10px !important;
        background: #7a1530 !important;
        color: #ffffff !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        min-width: 16px !important;
        height: 16px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 3px !important;
        border: 1.5px solid #ffffff !important;
    }

    /* Add padding to page bottom so sticky bottom bar doesn't overlay content */
    body {
        padding-bottom: 74px !important;
    }

    .site-footer {
        padding-bottom: 25px !important;
    }

    /* Responsive footer grid on tablet / mobile */
    .footer-trust-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 16px !important;
    }

    .footer-main-grid {
        grid-template-columns: 1fr !important;
        gap: 32px !important;
    }

    .footer-bottom-strip {
        flex-direction: column !important;
        text-align: center !important;
        gap: 14px !important;
    }

    .footer-payment-badges {
        justify-content: center !important;
    }
}

@media (max-width: 480px) {
    .footer-trust-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function handleFooterNewsletterSubmit(e, form) {
    e.preventDefault();
    var emailInput = form.querySelector('input[name="email"]');
    var btn = form.querySelector('button[type="submit"]');
    var email = (emailInput ? emailInput.value : '').trim();
    if (!email) return;

    var origHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    btn.disabled = true;

    var formData = new URLSearchParams();
    formData.append('email', email);

    fetch('<?= base_url('newsletter/subscribe') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData.toString()
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        btn.innerHTML = origHtml;
        btn.disabled = false;
        if (res.status === 'success' || res.status === 'info') {
            if (typeof showStoreToast === 'function') {
                showStoreToast(res.message, res.status === 'info' ? 'info' : 'success');
            } else {
                alert(res.message);
            }
            form.reset();
        } else {
            if (typeof showStoreToast === 'function') {
                showStoreToast(res.message || 'Unable to subscribe. Please try again.', 'error');
            } else {
                alert(res.message);
            }
        }
    })
    .catch(function(err) {
        btn.innerHTML = origHtml;
        btn.disabled = false;
        if (typeof showStoreToast === 'function') {
            showStoreToast('Thank you for subscribing! Welcome to Selvi Tex.', 'success');
        } else {
            alert('Thank you for subscribing! Welcome to Selvi Tex.');
        }
        form.reset();
    });
}
</script>
