<?= view('user/header', [
    'meta_title' => $meta_title ?? ($page['meta_title'] ?? $page['title']),
    'meta_description' => $meta_description ?? ($page['meta_description'] ?? ''),
    'meta_keywords' => $meta_keywords ?? ($page['meta_keywords'] ?? '')
]) ?>

<?php
    $bgImgPath = !empty($page['banner_image']) ? $page['banner_image'] : ($page['featured_image'] ?? '');
    $hasHeroBg = !empty($bgImgPath);
    $heroBgUrl = $hasHeroBg ? (str_starts_with($bgImgPath, 'http') ? $bgImgPath : base_url($bgImgPath)) : '';
    $heroBgStyle = $hasHeroBg 
        ? "background: linear-gradient(rgba(15, 5, 8, 0.72), rgba(25, 7, 13, 0.88)), url('" . esc($heroBgUrl) . "') center/cover no-repeat;"
        : "background: linear-gradient(135deg, #1a050b 0%, #2e0915 50%, #150308 100%);";

    $hasFeaturedImg = !empty($page['featured_image']);
    $featuredImgUrl = $hasFeaturedImg ? (str_starts_with($page['featured_image'], 'http') ? $page['featured_image'] : base_url($page['featured_image'])) : '';
?>

<main style="flex-grow: 1; background-color: #faf8f5; padding-bottom: 70px;">
    <!-- Page Hero Banner -->
    <section class="about-hero" style="<?= $heroBgStyle ?> color: #fff; padding: 75px 20px 85px; text-align: center; position: relative; border-bottom: 3px solid #d4af37;">
        <div style="max-width: 900px; margin: 0 auto; position: relative; z-index: 2;">
            <!-- Breadcrumbs -->
            <div style="font-size: 13.5px; color: #cbd5e1; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <a href="<?= base_url('/') ?>" style="color: #f1f5f9; text-decoration: none; font-weight: 500;">Home</a>
                <i class="fa-solid fa-angle-right" style="font-size: 10px; color: #d4af37;"></i>
                <span style="color: #ffd700; font-weight: 600;"><?= esc($page['title']) ?></span>
            </div>

            <div style="display: inline-flex; align-items: center; gap: 7px; background: rgba(212, 175, 55, 0.18); border: 1px solid rgba(212, 175, 55, 0.4); padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; color: #ffd700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 14px;">
                <i class="fa-solid fa-crown" style="font-size: 11px;"></i> Authentic Heritage
            </div>

            <h1 style="font-size: 38px; font-weight: 800; margin: 0 0 14px; letter-spacing: -0.5px; color: #ffffff; text-shadow: 0 2px 8px rgba(0,0,0,0.4);"><?= esc($page['title']) ?></h1>
            <?php if(!empty($page['meta_description'])): ?>
                <p style="font-size: 16.5px; max-width: 720px; margin: 0 auto; color: #fce7f3; line-height: 1.6; text-shadow: 0 1px 4px rgba(0,0,0,0.5);"><?= esc($page['meta_description']) ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Page Content Container -->
    <div style="max-width: 1000px; margin: -35px auto 0; padding: 0 20px; position: relative; z-index: 3;">
        <article style="background: #ffffff; border-radius: 16px; box-shadow: 0 12px 35px rgba(26,5,11,0.08); border: 1px solid #f1ece4; padding: 45px 50px; line-height: 1.85; color: #334155;">
            
            <?php if ($hasFeaturedImg): ?>
                <div style="margin-bottom: 35px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.08); border: 1px solid #f0e6d8;">
                    <img src="<?= esc($featuredImgUrl) ?>" alt="<?= esc($page['title']) ?>" style="width: 100%; height: auto; max-height: 440px; object-fit: cover; display: block;">
                </div>
            <?php endif; ?>

            <div class="dynamic-page-html" style="font-size: 16px;">
                <?= $page['content'] ?>
            </div>

            <!-- Page Trust Footer Strip -->
            <div style="margin-top: 50px; padding-top: 28px; border-top: 1px solid #f1e7d8; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px; font-size: 14px; color: #64748b;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-award" style="color: #b45309; font-size: 18px;"></i>
                    <span style="font-weight: 600; color: #475569;">100% Authentic Elampillai Handloom &bull; Direct Loom Pricing</span>
                </div>
                <div>
                    <a href="<?= base_url('contact-us') ?>" style="color: #7a1530; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        Need Assistance? Talk to Weavers <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
                    </a>
                </div>
            </div>
        </article>
    </div>

    <!-- Embedded FAQ Section for Story and Customer Questions -->
    <?php if (!empty($faqs)): ?>
    <div style="max-width: 1000px; margin: 40px auto 0; padding: 0 20px; position: relative; z-index: 2;">
        <?= view('user/faq_section', ['faqs' => $faqs, 'settings' => $settings ?? []]) ?>
    </div>
    <?php endif; ?>
</main>

<style>
.dynamic-page-html h2 {
    font-size: 26px;
    font-weight: 800;
    color: #1a050b;
    margin: 30px 0 16px;
    letter-spacing: -0.3px;
    border-left: 4px solid #7a1530;
    padding-left: 14px;
}
.dynamic-page-html h3 {
    font-size: 20px;
    font-weight: 700;
    color: #7a1530;
    margin: 25px 0 12px;
}
.dynamic-page-html p {
    margin-bottom: 18px;
    color: #475569;
    line-height: 1.8;
}
.dynamic-page-html ul, .dynamic-page-html ol {
    margin: 15px 0 25px 25px;
    color: #475569;
}
.dynamic-page-html li {
    margin-bottom: 10px;
    line-height: 1.7;
}
.dynamic-page-html a {
    color: #7a1530;
    text-decoration: underline;
    font-weight: 600;
}
.dynamic-page-html a:hover {
    color: #9c1d40;
}
.dynamic-page-html img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}
.dynamic-page-html blockquote {
    border-left: 4px solid #d4af37;
    padding: 14px 22px;
    margin: 24px 0;
    background-color: #fffdf5;
    color: #78350f;
    font-style: italic;
    border-radius: 0 10px 10px 0;
    border: 1px solid #fae8b4;
    border-left-width: 4px;
}
@media (max-width: 768px) {
    .about-hero {
        padding: 50px 16px 65px !important;
    }
    .about-hero h1 {
        font-size: 28px !important;
    }
    .dynamic-page-html {
        font-size: 15px;
    }
    article {
        padding: 28px 18px !important;
    }
}
</style>

<?= view('user/footer') ?>
