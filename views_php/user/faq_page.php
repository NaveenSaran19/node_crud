<?= view('user/header', [
    'meta_title' => 'Frequently Asked Questions (FAQs) | ' . ($settings['short_name'] ?? 'Selvi Tex'),
    'meta_description' => 'Find answers about ordering, payments, international delivery, authenticity guarantee, and return policies at Selvi Tex Elampillai handlooms.',
    'meta_keywords' => 'Selvi Tex FAQs, handloom saree questions, order delivery Elampillai, saree payment methods'
]) ?>

<main style="flex-grow: 1; background-color: #fafafa; padding-bottom: 70px;">
    <!-- Page Hero Banner -->
    <section style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 60px 20px; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Breadcrumbs -->
            <div style="font-size: 13px; color: #94a3b8; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <a href="<?= base_url('/') ?>" style="color: #cbd5e1; text-decoration: none;">Home</a>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <span style="color: #f1f5f9;">FAQs</span>
            </div>

            <div class="faq-badge-pill" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.12); padding: 6px 16px; border-radius: 30px; font-size: 13px; color: #fbbf24; font-weight: 700; margin-bottom: 15px; letter-spacing: 1px;">
                <i class="fa-solid fa-circle-question"></i>
                <span>HELP &amp; SUPPORT</span>
            </div>
            <h1 style="font-size: 38px; font-weight: 800; margin: 0 0 12px; letter-spacing: -0.5px;">Frequently Asked Questions</h1>
            <p style="font-size: 17px; max-width: 720px; margin: 0 auto; color: #cbd5e1; line-height: 1.5;">
                Everything you need to know about our authentic Elampillai handlooms, order placement, payments, and express doorstep delivery.
            </p>
        </div>
    </section>

    <!-- FAQ Accordion Container -->
    <div style="max-width: 900px; margin: 30px auto 0; padding: 0 20px;">
        <?= view('user/faq_section', ['settings' => $settings, 'faqs' => $faqs]) ?>
    </div>
</main>

<?= view('user/footer') ?>
