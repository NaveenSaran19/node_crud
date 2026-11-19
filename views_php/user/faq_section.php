<?php
    $whatsappNum = !empty($settings['whatsapp_no']) ? preg_replace('/[^0-9]/', '', $settings['whatsapp_no']) : '919789296751';
    if (strlen($whatsappNum) === 10) {
        $whatsappNum = '91' . $whatsappNum;
    } elseif (strlen($whatsappNum) === 11 && strpos($whatsappNum, '0') === 0) {
        $whatsappNum = '91' . substr($whatsappNum, 1);
    }
    $phoneDisplay = !empty($settings['phone_number']) ? $settings['phone_number'] : (!empty($settings['phone_no']) ? $settings['phone_no'] : '+91 90875 90967');
    $phoneLink = preg_replace('/[^0-9+]/', '', $phoneDisplay);
    $bizName = !empty($settings['business_name']) ? $settings['business_name'] : 'Selvi Tex';

    if (!isset($faqs) || empty($faqs)) {
        $faqModel = new \App\Models\FaqModel();
        $faqs = $faqModel->where('is_active', 1)->orderBy('display_order', 'ASC')->orderBy('id', 'ASC')->findAll();
    }
?>

<section class="luxury-faq-section" id="faq-section" aria-label="Frequently Asked Questions">
    <div class="faq-inner-container">
        
        <!-- Header -->
        <div class="faq-header-wrapper">
            <div class="faq-badge-pill">
                <i class="fa-solid fa-circle-question"></i>
                <span>HELP &amp; SUPPORT</span>
            </div>
            <h2 class="faq-main-heading">Frequently Asked Questions</h2>
            <p class="faq-sub-heading">
                Everything you need to know about our authentic Elampillai handlooms, order placement, payments, and express doorstep delivery.
            </p>
        </div>

        <!-- Accordion Cards -->
        <div class="faq-accordion-list">
            <?php if (!empty($faqs)): ?>
                <?php 
                $iconMap = [
                    'order'    => 'fa-solid fa-bag-shopping',
                    'payment'  => 'fa-solid fa-credit-card',
                    'shipping' => 'fa-solid fa-truck-fast',
                    'deliver'  => 'fa-solid fa-plane-departure',
                    'authentic'=> 'fa-solid fa-award',
                    'quality'  => 'fa-solid fa-certificate',
                    'return'   => 'fa-solid fa-arrow-rotate-left',
                    'refund'   => 'fa-solid fa-hand-holding-dollar',
                ];
                ?>
                <?php foreach ($faqs as $index => $faq): 
                    $qLower = strtolower($faq['question']);
                    $catLower = strtolower($faq['category'] ?? '');
                    $iconClass = 'fa-solid fa-circle-question';
                    foreach ($iconMap as $key => $icon) {
                        if (strpos($qLower, $key) !== false || strpos($catLower, $key) !== false) {
                            $iconClass = $icon;
                            break;
                        }
                    }
                ?>
                <details class="faq-card" <?= $index === 0 ? 'open' : '' ?>>
                    <summary class="faq-card-header">
                        <span class="faq-icon-bubble">
                            <i class="<?= $iconClass ?>"></i>
                        </span>
                        <span class="faq-question-text"><?= esc($faq['question']) ?></span>
                        <span class="faq-toggle-arrow">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="faq-card-content">
                        <?php
                            $ans = $faq['answer'];
                            if (strip_tags($ans) === $ans) {
                                $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $ans));
                                $inList = false;
                                foreach ($lines as $line) {
                                    $trimmed = trim($line);
                                    if (empty($trimmed)) {
                                        if ($inList) { echo '</ul>'; $inList = false; }
                                        continue;
                                    }
                                    if (str_starts_with($trimmed, '•') || str_starts_with($trimmed, '-') || str_starts_with($trimmed, '*')) {
                                        if (!$inList) { echo '<ul class="faq-steps-list">'; $inList = true; }
                                        $itemText = ltrim($trimmed, "•-* \t");
                                        if (preg_match('/^([^:]+:)(.*)$/', $itemText, $m)) {
                                            echo '<li><strong>' . esc($m[1]) . '</strong>' . esc($m[2]) . '</li>';
                                        } else {
                                            echo '<li>' . esc($itemText) . '</li>';
                                        }
                                    } else {
                                        if ($inList) { echo '</ul>'; $inList = false; }
                                        echo '<p>' . nl2br(esc($trimmed)) . '</p>';
                                    }
                                }
                                if ($inList) { echo '</ul>'; }
                            } else {
                                echo $ans;
                            }
                        ?>
                    </div>
                </details>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #64748b;">
                    <p>No FAQs available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Help & Contact Card Footer -->
        <div class="faq-support-box">
            <div class="faq-support-left">
                <div class="faq-support-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h3 class="faq-support-title">Still have questions?</h3>
                    <p class="faq-support-text">Our dedicated Elampillai handloom specialists at <?= esc($bizName) ?> are available Mon–Sun (9 AM – 9 PM) to assist you with styling advice, bulk orders, or custom color requirements.</p>
                </div>
            </div>
            <div class="faq-support-actions">
                <a href="https://wa.me/<?= esc($whatsappNum) ?>?text=<?= urlencode('Hello ' . $bizName . '! I have a question regarding an order.') ?>" target="_blank" class="faq-whatsapp-btn" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
                </a>
                <a href="tel:<?= esc($phoneLink) ?>" class="faq-call-btn">
                    <i class="fa-solid fa-phone"></i> Call Support
                </a>
            </div>
        </div>

    </div>
</section>
