<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 6px;">Blog Management</h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">Create, edit, and publish saree care guides, weaver stories, and articles.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="<?= base_url('blogs/news') ?>" target="_blank" class="btn" style="padding: 10px 18px; background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 13.5px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Blogs
        </a>
        <a href="<?= base_url('admin/blogs/create') ?>" class="btn btn-primary" style="padding: 10px 20px; background: #e11d48; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(225, 29, 72,0.25);">
            <i class="fa-solid fa-plus"></i> Add New Blog Post
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: #f0fdf4; color: #166534; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div style="background: #fef2f2; color: #991b1b; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Filters -->
<div style="margin-bottom: 20px; display: flex; gap: 8px;">
    <a href="<?= base_url('admin/blogs') ?>" style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; <?= ($currentStatus === 'all') ? 'background: #e11d48; color: #fff;' : 'background: #f1f5f9; color: #475569;' ?>">
        All Posts (<?= count($blogs) ?>)
    </a>
    <a href="<?= base_url('admin/blogs?status=published') ?>" style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; <?= ($currentStatus === 'published') ? 'background: #e11d48; color: #fff;' : 'background: #f1f5f9; color: #475569;' ?>">
        Published
    </a>
    <a href="<?= base_url('admin/blogs?status=draft') ?>" style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; <?= ($currentStatus === 'draft') ? 'background: #e11d48; color: #fff;' : 'background: #f1f5f9; color: #475569;' ?>">
        Drafts
    </a>
</div>

<div class="card" style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                <th style="padding: 14px 20px; width: 60px;">Image</th>
                <th style="padding: 14px 20px;">Article Title &amp; Slug</th>
                <th style="padding: 14px 20px; width: 140px;">Author</th>
                <th style="padding: 14px 20px; width: 130px;">Date</th>
                <th style="padding: 14px 20px; width: 110px;">Status</th>
                <th style="padding: 14px 20px; width: 140px; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($blogs)): ?>
                <?php foreach($blogs as $b): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#fdfbfb'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 14px 20px;">
                            <?php if(!empty($b['featured_image'])): ?>
                                <img src="<?= (strpos($b['featured_image'], 'http') === 0) ? esc($b['featured_image']) : base_url(esc($b['featured_image'])) ?>" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <?php else: ?>
                                <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 18px;">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px 20px;">
                            <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px; font-size: 14.5px;">
                                <a href="<?= base_url('admin/blogs/edit/' . $b['id']) ?>" style="color: inherit; text-decoration: none;">
                                    <?= esc($b['title']) ?>
                                </a>
                            </div>
                            <div style="font-size: 12px; color: #64748b; font-family: monospace;">
                                /blogs/<?= esc($b['slug']) ?>
                            </div>
                        </td>
                        <td style="padding: 14px 20px; color: #475569; font-size: 13px;">
                            <i class="fa-solid fa-user-pen" style="color: #94a3b8; margin-right: 4px;"></i>
                            <?= esc($b['author'] ?: 'Selvi Tex') ?>
                        </td>
                        <td style="padding: 14px 20px; color: #64748b; font-size: 13px;">
                            <?= !empty($b['published_at']) ? date('d M Y', strtotime($b['published_at'])) : date('d M Y', strtotime($b['created_at'])) ?>
                        </td>
                        <td style="padding: 14px 20px;">
                            <form action="<?= base_url('admin/blogs/toggle/' . $b['id']) ?>" method="POST" style="margin: 0; display: inline;">
                                <?= csrf_field() ?>
                                <?php if($b['status'] === 'published'): ?>
                                    <button type="submit" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Click to unpublish (draft)">
                                        <i class="fa-solid fa-circle" style="font-size: 7px; color: #22c55e;"></i> Published
                                    </button>
                                <?php else: ?>
                                    <button type="submit" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Click to publish">
                                        <i class="fa-solid fa-clock" style="font-size: 10px;"></i> Draft
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td style="padding: 14px 20px; text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?= base_url('blogs/' . $b['slug']) ?>" target="_blank" title="View on Frontend" style="padding: 7px 10px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; color: #475569; text-decoration: none; font-size: 12.5px;">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <a href="<?= base_url('admin/blogs/edit/' . $b['id']) ?>" title="Edit" style="padding: 7px 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; color: #1d4ed8; text-decoration: none; font-size: 12.5px;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="<?= base_url('admin/blogs/delete/' . $b['id']) ?>" onclick="return confirm('Are you sure you want to delete this blog post?');" title="Delete" style="padding: 7px 10px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #dc2626; text-decoration: none; font-size: 12.5px;">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8;">
                        <i class="fa-solid fa-newspaper" style="font-size: 36px; margin-bottom: 12px; display: block; color: #cbd5e1;"></i>
                        No blog posts found. <a href="<?= base_url('admin/blogs/create') ?>" style="color: #e11d48; font-weight: 600;">Create your first post</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
