<?= view('user/header', [
    'meta_title' => $blog['meta_title'] ?: ($blog['title'] . ' | ' . ($settings['short_name'] ?? 'Selvi Tex')),
    'meta_description' => $blog['meta_description'] ?: ($blog['excerpt'] ?? ''),
    'meta_keywords' => $blog['meta_keywords'] ?: ''
]) ?>

<?php 
    $blogImg = !empty($blog['featured_image']) ? (str_starts_with($blog['featured_image'], 'http') ? $blog['featured_image'] : base_url($blog['featured_image'])) : null;
    $pubDate = !empty($blog['published_at']) ? date('F d, Y', strtotime($blog['published_at'])) : date('F d, Y', strtotime($blog['created_at']));
?>

<main style="flex-grow: 1; background-color: #f8fafc; padding-bottom: 70px;">
    <!-- Article Header Banner -->
    <section style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 60px 20px 80px; text-align: center;">
        <div style="max-width: 850px; margin: 0 auto;">
            <!-- Breadcrumbs -->
            <div style="font-size: 13px; color: #94a3b8; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <a href="<?= base_url('/') ?>" style="color: #cbd5e1; text-decoration: none;">Home</a>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a href="<?= base_url('blogs/news') ?>" style="color: #cbd5e1; text-decoration: none;">Blogs</a>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <span style="color: #f1f5f9;"><?= esc($blog['title']) ?></span>
            </div>

            <div style="display: inline-flex; align-items: center; gap: 15px; background: rgba(255,255,255,0.1); border-radius: 30px; padding: 6px 18px; margin-bottom: 18px; font-size: 13px; color: #e2e8f0;">
                <span><i class="fa-regular fa-calendar-days" style="color: #fbbf24; margin-right: 6px;"></i><?= $pubDate ?></span>
                <span>&bull;</span>
                <span><i class="fa-regular fa-user" style="color: #fbbf24; margin-right: 6px;"></i>By <?= esc($blog['author'] ?? 'Selvi Tex') ?></span>
            </div>

            <h1 style="font-size: 36px; font-weight: 800; line-height: 1.3; margin: 0 0 15px; letter-spacing: -0.5px;"><?= esc($blog['title']) ?></h1>
            <?php if(!empty($blog['excerpt'])): ?>
                <p style="font-size: 18px; color: #cbd5e1; margin: 0; line-height: 1.6; max-width: 720px; margin: 0 auto;"><?= esc($blog['excerpt']) ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Article Body -->
    <div style="max-width: 900px; margin: -40px auto 0; padding: 0 20px; position: relative; z-index: 2;">
        <article style="background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; padding: 45px 50px; line-height: 1.8; color: #334155;">
            
            <?php if (!empty($blogImg)): ?>
                <div style="margin-bottom: 40px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <img src="<?= esc($blogImg) ?>" alt="<?= esc($blog['title']) ?>" style="width: 100%; height: auto; max-height: 500px; object-fit: cover; display: block;" onerror="this.onerror=null;this.style.display='none';">
                </div>
            <?php endif; ?>

            <div class="blog-html-content" style="font-size: 16.5px;">
                <?= $blog['content'] ?>
            </div>

            <!-- Author & Share Card -->
            <div style="margin-top: 50px; padding: 25px 30px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #991b1b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700;">
                        ST
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #0f172a; font-size: 15px;"><?= esc($blog['author'] ?? 'Selvi Tex') ?></div>
                        <div style="font-size: 13px; color: #64748b;">Elampillai Handloom Master Craftsmanship</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="<?= base_url('blogs/news') ?>" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; color: #1e293b; font-size: 13px; font-weight: 600; text-decoration: none;">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>All Blog Posts</span>
                    </a>
                    <a href="https://wa.me/?text=<?= urlencode($blog['title'] . ' ' . current_url()) ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; background: #25D366; color: #ffffff; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>Share</span>
                    </a>
                </div>
            </div>
        </article>

        <!-- Dynamic Related Blogs -->
        <?php if(!empty($relatedBlogs)): ?>
        <div style="margin-top: 50px;">
            <h3 style="font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 20px;">More Handloom Stories</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
                <?php foreach($relatedBlogs as $rel): 
                    $relImg = !empty($rel['featured_image']) ? (str_starts_with($rel['featured_image'], 'http') ? $rel['featured_image'] : base_url($rel['featured_image'])) : base_url('assets/images/logo.png');
                ?>
                <a href="<?= base_url('blogs/' . esc($rel['slug'])) ?>" style="background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; text-decoration: none; color: inherit; box-shadow: 0 2px 10px rgba(0,0,0,0.04); display: block;">
                    <div style="height: 140px; overflow: hidden;">
                        <img src="<?= esc($relImg) ?>" alt="<?= esc($rel['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 15px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 6px; line-height: 1.3;"><?= esc($rel['title']) ?></h4>
                        <span style="font-size: 12px; color: #94a3b8;"><?= date('M d, Y', strtotime($rel['published_at'] ?? $rel['created_at'])) ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<style>
.blog-html-content h2 {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    margin: 35px 0 15px;
    letter-spacing: -0.3px;
}
.blog-html-content h3 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin: 25px 0 12px;
}
.blog-html-content p {
    margin-bottom: 18px;
    color: #475569;
}
.blog-html-content ul, .blog-html-content ol {
    margin: 15px 0 25px 25px;
    color: #475569;
}
.blog-html-content li {
    margin-bottom: 8px;
}
.blog-html-content a {
    color: #991b1b;
    text-decoration: underline;
    font-weight: 600;
}
.blog-html-content blockquote {
    border-left: 4px solid #991b1b;
    padding: 15px 20px;
    margin: 25px 0;
    background-color: #fff1f2;
    color: #9f1239;
    font-style: italic;
    border-radius: 0 8px 8px 0;
}
@media (max-width: 768px) {
    article {
        padding: 25px 20px !important;
    }
}
</style>

<?= view('user/footer') ?>
