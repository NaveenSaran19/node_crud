<?= view('user/header') ?>

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
    $bizAddress = !empty($settings['business_address']) ? $settings['business_address'] : 'Elampillai, Salem District, Tamil Nadu, India - 636502';
    $phoneNo = !empty($settings['phone_no']) ? $settings['phone_no'] : ($settings['about_store_phone'] ?? '');
    $contactEmail = !empty($settings['contact_email']) ? $settings['contact_email'] : (!empty($settings['support_email']) ? $settings['support_email'] : ($settings['admin_email'] ?? 'support@selvitex.com'));
    $whatsappNo = !empty($settings['whatsapp_no']) ? $settings['whatsapp_no'] : ($settings['about_store_whatsapp'] ?? '');
    $bizHours = !empty($settings['business_hours']) ? $settings['business_hours'] : 'Mon-Sat, 9:00 AM to 8:00 PM';
    $mapEmbed = $settings['google_map_embed'] ?? '';
    $mapLink = $settings['location_link'] ?? '';
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsappNo) ?: '919789296751';
    if (strlen($cleanWhatsapp) === 10) {
        $cleanWhatsapp = '91' . $cleanWhatsapp;
    } elseif (strlen($cleanWhatsapp) === 11 && strpos($cleanWhatsapp, '0') === 0) {
        $cleanWhatsapp = '91' . substr($cleanWhatsapp, 1);
    }
    $contactBanners = isset($settings['contact_us_banner']) && !empty($settings['contact_us_banner']) ? json_decode($settings['contact_us_banner'], true) : [];
    if (!is_array($contactBanners)) $contactBanners = !empty($settings['contact_us_banner']) ? [$settings['contact_us_banner']] : [];
    if (empty($contactBanners) || empty($contactBanners[0])) {
        $contactBanners = ['uploads/settings/1792258160_78817d9871c35cd12ad9.jpg'];
    }
    $showOverlay = ($settings['contact_us_text_overlay_enabled'] ?? '1') === '1';
    $quoteText = !empty($settings['contact_us_quote_text']) ? $settings['contact_us_quote_text'] : '"We Weave Authentic Relationships"';
    $headingText = !empty($settings['contact_us_heading_text']) ? $settings['contact_us_heading_text'] : 'Get in Touch with Selvi Tex';
    $subText = !empty($settings['contact_us_subtitle_text']) ? $settings['contact_us_subtitle_text'] : 'ELAMPILLAI WEAVING SHOWROOM & SUPPORT';
?>

<main style="flex-grow: 1; background: #ffffff;">
    <!-- Contact Us Luxury Hero Banner & Text Overlay -->
    <section class="collection-hero-banner" style="position: relative; width: 100%; height: 350px; overflow: hidden; background-color: #1e1e1e;">
        <div style="display: flex; width: <?= count($contactBanners) * 100 ?>%; height: 100%; <?= count($contactBanners) > 1 ? 'animation: slide' . min(count($contactBanners), 5) . ' 12s infinite ease-in-out;' : '' ?>">
            <?php foreach($contactBanners as $img): ?>
            <div style="width: <?= 100 / count($contactBanners) ?>%; height: 100%; background: url('<?= base_url($img) ?>') center/cover no-repeat;"></div>
            <?php endforeach; ?>
        </div>
        <?php if($showOverlay): ?>
        <div class="collection-hero-overlay"></div>
        <div class="collection-hero-content-flex">
            <?php if(!empty($quoteText)): ?>
            <div class="collection-hero-quote-box">
                <h1 class="collection-hero-quote"><?= esc($quoteText) ?></h1>
            </div>
            <?php endif; ?>
            <div class="collection-hero-title-box">
                <?php if(!empty($headingText)): ?>
                <h2 class="collection-hero-title"><?= esc($headingText) ?></h2>
                <?php endif; ?>
                <?php if(!empty($subText)): ?>
                <h3 class="collection-hero-subtitle"><?= esc($subText) ?></h3>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <section class="contact-container">
        <!-- Contact Information Column -->
        <div class="contact-info">
            <h3>Get In Touch</h3>
            <p style="color: #64748b; margin-bottom: 35px; line-height: 1.7; font-size: 15px;">
                Whether you have a question about our collections, need assistance with an order, or just want to say hello, our team is ready to answer all your questions.
            </p>
            
            <!-- Store Address -->
            <div class="info-item">
                <div class="info-icon" style="color: var(--primary-color, #e11d48);"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-content" style="flex: 1;">
                    <h4>Visit Our Store</h4>
                    <p style="font-weight: 700; color: #1e293b; margin-bottom: 4px;"><?= esc($bizName) ?></p>
                    <p><?= nl2br(esc($bizAddress)) ?></p>
                    <?php if(!empty($mapLink)): ?>
                        <div style="margin-top: 8px;">
                            <a href="<?= esc($mapLink) ?>" target="_blank" style="color: var(--primary-color, #e11d48); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fa-solid fa-diamond-turn-right"></i> Open in Google Maps
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Phone Support -->
            <?php if(!empty($phoneNo)): ?>
            <div class="info-item">
                <div class="info-icon" style="color: var(--primary-color, #e11d48);"><i class="fa-solid fa-phone"></i></div>
                <div class="info-content">
                    <h4>Call Us</h4>
                    <p>
                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phoneNo) ?>" style="color: #1e293b; text-decoration: none; font-weight: 800; font-size: 16px;">
                            <?= esc($phoneNo) ?>
                        </a>
                    </p>
                    <p style="font-size: 13px; color: #64748b; margin-top: 4px;"><?= esc($bizHours) ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Email Support -->
            <?php if(!empty($contactEmail)): ?>
            <div class="info-item">
                <div class="info-icon" style="color: var(--primary-color, #e11d48);"><i class="fa-solid fa-envelope"></i></div>
                <div class="info-content">
                    <h4>Email Us</h4>
                    <p>
                        <a href="mailto:<?= esc($contactEmail) ?>" style="color: #1e293b; text-decoration: none; font-weight: 700; font-size: 15px;">
                            <?= esc($contactEmail) ?>
                        </a>
                    </p>
                    <p style="font-size: 13px; color: #64748b; margin-top: 4px;">We reply within 24 hours</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- WhatsApp Support -->
            <?php if(!empty($cleanWhatsapp)): ?>
            <div class="info-item" style="border-left: 4px solid #25D366; background: #f0fdf4;">
                <div class="info-icon" style="color: #25D366; background: #dcfce7;"><i class="fa-brands fa-whatsapp"></i></div>
                <div class="info-content">
                    <h4 style="color: #166534;">WhatsApp Support</h4>
                    <p style="color: #15803d; font-size: 13.5px; margin-bottom: 8px;">Instant support for order status, video call saree selection &amp; enquiries.</p>
                    <a href="https://wa.me/<?= esc($cleanWhatsapp) ?>?text=<?= urlencode('Hello ' . $bizName . ', I have an inquiry about sarees.') ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #25D366; color: #ffffff; padding: 8px 18px; border-radius: 6px; font-weight: 700; font-size: 13px; text-decoration: none; box-shadow: 0 4px 10px rgba(37,211,102,0.25);">
                        <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Connect on Social Media -->
            <div class="info-item" style="border-left: 4px solid #e11d48; background: #fffcfd;">
                <div class="info-icon" style="color: #e11d48; background: #fdf2f4;"><i class="fa-solid fa-share-nodes"></i></div>
                <div class="info-content">
                    <h4 style="color: #e11d48;">Connect On Social Media</h4>
                    <p style="color: #64748b; font-size: 13.5px; margin-bottom: 10px;">Follow our daily saree videos, new Elampillai weaves, and festive offers.</p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="https://wa.me/<?= esc($cleanWhatsapp) ?>?text=<?= urlencode('Hello ' . $bizName . ', I have an inquiry about sarees.') ?>" target="_blank" style="width: 38px; height: 38px; border-radius: 8px; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 17px;" title="Chat on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="<?= esc(!empty($settings['youtube_link']) ? $settings['youtube_link'] : 'https://youtube.com/@srisakthiselvitex') ?>" target="_blank" style="width: 38px; height: 38px; border-radius: 8px; background: #FF0000; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 17px;" title="YouTube Channel"><i class="fa-brands fa-youtube"></i></a>
                        <a href="<?= esc(!empty($settings['instagram_link']) ? $settings['instagram_link'] : 'https://instagram.com/srisakthiselvitex') ?>" target="_blank" style="width: 38px; height: 38px; border-radius: 8px; background: #E1306C; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 17px;" title="Instagram Page"><i class="fa-brands fa-instagram"></i></a>
                        <a href="<?= esc(!empty($settings['facebook_link']) ? $settings['facebook_link'] : 'https://facebook.com/srisakthiselvitex') ?>" target="_blank" style="width: 38px; height: 38px; border-radius: 8px; background: #1877F2; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 17px;" title="Facebook Page"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="<?= esc(!empty($settings['twitter_link']) ? $settings['twitter_link'] : 'https://twitter.com/srisakthiselvitex') ?>" target="_blank" style="width: 38px; height: 38px; border-radius: 8px; background: #0f172a; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 17px;" title="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Message Form -->
        <div class="contact-form">
            <h3 style="font-size: 26px; margin-bottom: 8px; margin-top: 0; font-weight: 800; color: #1e293b;">Send a Message</h3>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 25px;">Fill out the form below and our customer support team will get in touch with you shortly.</p>
            
            <?php if(session()->getFlashdata('success')): ?>
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i> 
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 18px;"></i> 
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('contact/submit') ?>" method="POST">
                <div class="form-group">
                    <label for="name">Full Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address <span style="color: #ef4444;">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                    <label for="message">Your Message <span style="color: #ef4444;">*</span></label>
                    <textarea id="message" name="message" rows="5" placeholder="How can we help you?" required></textarea>
                </div>
                <button type="submit" class="submit-btn" style="background-color: var(--primary-color, #e11d48);">
                    <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i> Send Message
                </button>
            </form>
        </div>
    </section>

    <!-- Google Maps Location Section -->
    <section class="contact-map-section" style="max-width: 1200px; margin: 0 auto 70px; padding: 0 20px;">
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div style="padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-map-location-dot" style="color: var(--primary-color, #e11d48);"></i> Locate Our Store on Google Maps
                    </h3>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b;"><?= esc($bizName) ?> • <?= esc($bizAddress) ?></p>
                </div>
                <?php if(!empty($mapLink)): ?>
                    <a href="<?= esc($mapLink) ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: var(--primary-color, #e11d48); color: #fff; padding: 9px 18px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 700;">
                        <i class="fa-solid fa-diamond-turn-right"></i> Get Directions
                    </a>
                <?php endif; ?>
            </div>
            <div class="google-map-wrapper" style="width: 100%; height: 420px; position: relative; background: #e2e8f0;">
                <?php 
                    if (!empty($mapEmbed)) {
                        if (strpos($mapEmbed, '<iframe') !== false) {
                            $cleanIframe = preg_replace('/width=["\'][^"\']*["\']/', 'width="100%"', $mapEmbed);
                            $cleanIframe = preg_replace('/height=["\'][^"\']*["\']/', 'height="100%"', $cleanIframe);
                            if (strpos($cleanIframe, 'style=') === false) {
                                $cleanIframe = str_replace('<iframe', '<iframe style="width:100%; height:100%; border:0;"', $cleanIframe);
                            } else {
                                $cleanIframe = preg_replace('/style=["\']([^"\']*)["\']/', 'style="width:100%; height:100%; border:0; $1"', $cleanIframe);
                            }
                            echo $cleanIframe;
                        } else {
                            echo '<iframe src="' . esc($mapEmbed) . '" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
                        }
                    } else {
                        $defaultEmbedQuery = urlencode($bizName . ' Elampillai Salem Tamil Nadu');
                        echo '<iframe src="https://maps.google.com/maps?q=' . $defaultEmbedQuery . '&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
                    }
                ?>
            </div>
        </div>
    </section>
</main>

<?= view('user/footer') ?>
