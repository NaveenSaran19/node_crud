<?= view('user/header', ['meta_title' => $title ?? 'Frequently Asked Questions (FAQ) | Selvi Tex']) ?>

<main style="flex-grow: 1; background-color: #fafafa; padding-bottom: 60px;">
    <!-- Breadcrumb Header -->
    <div style="background: linear-gradient(135deg, #1e050c 0%, #2b0812 100%); padding: 40px 20px 50px; text-align: center; color: #fff; border-bottom: 2px solid #d4af37;">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="font-size: 13px; color: #cbd5e1; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <a href="<?= base_url() ?>" style="color: #f1f5f9; text-decoration: none;">Home</a>
                <i class="fa-solid fa-angle-right" style="font-size: 10px; color: #d4af37;"></i>
                <span style="color: #ffd700;">FAQ</span>
            </div>
            <h1 style="font-size: 34px; font-weight: 800; margin: 0 0 10px; color: #fff;">Help &amp; Frequently Asked Questions</h1>
            <p style="font-size: 15px; color: #fce7f3; margin: 0; line-height: 1.5;">Have questions about our Elampillai pattu, authentic handlooms, orders or express deliveries? Find your answers here.</p>
        </div>
    </div>

    <!-- FAQ Accordion Component -->
    <div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        <?= view('user/faq_section', ['faqs' => $faqs ?? []]) ?>
    </div>
</main>

<?= view('user/footer') ?>
