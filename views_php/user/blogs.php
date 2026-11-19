<?= view('user/header', [
    'meta_title' => 'Saree Care Blogs & Handloom Stories | ' . ($settings['short_name'] ?? 'Selvi Tex'),
    'meta_description' => 'Stay updated with Elampillai weaving heritage, saree styling guides, care tips, and festive collections from Selvi Tex.',
    'meta_keywords' => 'saree care, Elampillai handlooms, saree blogs, silk saree maintenance, Selvi Tex'
]) ?>

<?php
    if (!isset($blogs) || empty($blogs)) {
        $blogModel = new \App\Models\BlogModel();
        $blogs = $blogModel->where('status', 'published')->orderBy('published_at', 'DESC')->orderBy('id', 'DESC')->findAll();
    }
?>

<main style="flex-grow: 1; background-color: #f8fafc; padding-bottom: 70px;">
    <section class="blog-hero" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 60px 20px; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="font-size: 13px; color: #94a3b8; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <a href="<?= base_url('/') ?>" style="color: #cbd5e1; text-decoration: none;">Home</a>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <span style="color: #f1f5f9;">Saree Care Blogs</span>
            </div>
            <h1 style="font-size: 38px; font-weight: 800; margin: 0 0 10px; letter-spacing: -0.5px;">Saree Care &amp; Handloom Stories</h1>
            <p style="font-size: 17px; color: #cbd5e1; margin: 0; line-height: 1.5;">Stay updated with the latest trends, weaving artistry, care tips, and festive stories from <?= esc($settings['business_name'] ?? 'Selvi Tex') ?>.</p>
        </div>
    </section>

    <div class="blog-container" style="max-width: 1200px; margin: 40px auto 0; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
        <?php if(!empty($blogs)): ?>
            <?php foreach($blogs as $blog): 
                $blogImg = !empty($blog['featured_image']) ? (str_starts_with($blog['featured_image'], 'http') ? $blog['featured_image'] : base_url($blog['featured_image'])) : base_url('assets/images/logo.png');
                $pubDate = !empty($blog['published_at']) ? date('M d, Y', strtotime($blog['published_at'])) : date('M d, Y', strtotime($blog['created_at']));
                $blogUrl = base_url('blogs/' . esc($blog['slug']));
            ?>
            <article class="blog-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <a href="<?= $blogUrl ?>" style="display: block; overflow: hidden; height: 230px; position: relative;">
                    <img src="<?= esc($blogImg) ?>" alt="<?= esc($blog['title']) ?>" class="blog-img" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
                </a>
                <div class="blog-content" style="padding: 24px; flex-grow: 1; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; font-size: 13px; color: #64748b;">
                        <span><i class="fa-regular fa-calendar-days" style="margin-right: 5px; color: #b45309;"></i><?= $pubDate ?></span>
                        <span><i class="fa-regular fa-user" style="margin-right: 5px;"></i><?= esc($blog['author'] ?? 'Selvi Tex') ?></span>
                    </div>
                    <h2 class="blog-title" style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 12px; line-height: 1.4;">
                        <a href="<?= $blogUrl ?>" style="color: inherit; text-decoration: none;"><?= esc($blog['title']) ?></a>
                    </h2>
                    <p class="blog-excerpt" style="font-size: 14px; color: #64748b; line-height: 1.6; margin: 0 0 20px; flex-grow: 1;">
                        <?= esc($blog['excerpt'] ?? substr(strip_tags($blog['content']), 0, 140) . '...') ?>
                    </p>
                    <div>
                        <a href="<?= $blogUrl ?>" class="read-more" style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #991b1b; text-decoration: none;">
                            <span>Read Full Article</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px;">
                <i class="fa-solid fa-newspaper" style="font-size: 40px; color: #94a3b8; margin-bottom: 15px;"></i>
                <h3 style="font-size: 20px; color: #1e293b;">No Blog Posts Published Yet</h3>
                <p style="color: #64748b; margin-top: 8px;">Check back soon for new articles and weaving stories.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.blog-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important;
}
.blog-card:hover img {
    transform: scale(1.05);
}
.blog-card:hover .blog-title a {
    color: #991b1b;
}
</style>

<?= view('user/footer') ?>
