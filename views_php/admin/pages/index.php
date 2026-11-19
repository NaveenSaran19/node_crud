<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card" style="padding: 28px; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px;">Website Pages</h1>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Create and customize website pages, about story, company policies, and SEO metadata.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('admin/pages/create') ?>" class="btn btn-primary" style="background: #e11d48; color: #fff; font-weight: 600; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; box-shadow: 0 4px 12px rgba(225, 29, 72,0.25);">
                <i class="fa-solid fa-plus"></i> Create New Page
            </a>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: #ecfdf5; color: #065f46; padding: 14px 18px; border-radius: 8px; margin-bottom: 22px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check" style="font-size: 16px;"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div style="background: #fef2f2; color: #991b1b; padding: 14px 18px; border-radius: 8px; margin-bottom: 22px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 16px;"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="data-table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">Page Title</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">URL Slug</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">Status</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">Updated On</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 170px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($pages)): ?>
                    <?php foreach($pages as $page): 
                        $viewUrl = ($page['slug'] === 'about-selvi-tex') ? base_url('pages/about-selvi-tex') : (in_array($page['slug'], ['contact-information', 'privacy-policy', 'refund-policy', 'shipping-policy', 'terms-of-service']) ? base_url('policies/' . $page['slug']) : base_url('pages/' . $page['slug']));
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 15px;">
                                <a href="<?= base_url('admin/pages/edit/' . $page['id']) ?>" style="color: inherit; text-decoration: none;">
                                    <?= esc($page['title']) ?>
                                </a>
                            </div>
                            <?php if(!empty($page['meta_title'])): ?>
                                <div style="font-size: 12.5px; color: #64748b; margin-top: 3px;">
                                    <span style="font-weight: 600; color: #94a3b8;">SEO:</span> <?= esc($page['meta_title']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 16px;">
                            <code style="padding: 4px 8px; background: #f1f5f9; border-radius: 4px; font-size: 12.5px; color: #475569;">
                                /<?= esc($page['slug']) ?>
                            </code>
                        </td>
                        <td style="padding: 16px;">
                            <?php if($page['status'] === 'published'): ?>
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                    <span style="width: 7px; height: 7px; background: #059669; border-radius: 50%;"></span> Published
                                </span>
                            <?php else: ?>
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: #f8fafc; color: #64748b; border: 1px solid #cbd5e1; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                    <span style="width: 7px; height: 7px; background: #94a3b8; border-radius: 50%;"></span> Draft
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 16px; font-size: 13.5px; color: #64748b;">
                            <?= !empty($page['updated_at']) ? date('M d, Y', strtotime($page['updated_at'])) : date('M d, Y', strtotime($page['created_at'])) ?>
                        </td>
                        <td style="padding: 16px; text-align: right; white-space: nowrap;">
                            <a href="<?= $viewUrl ?>" target="_blank" class="btn btn-action" style="padding: 7px 11px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; color: #0f172a; text-decoration: none; margin-right: 5px; display: inline-block;" title="View Live Page">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="<?= base_url('admin/pages/edit/' . $page['id']) ?>" class="btn btn-action" style="padding: 7px 11px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; text-decoration: none; margin-right: 5px; display: inline-block;" title="Edit Page Content">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="<?= base_url('admin/pages/delete/' . $page['id']) ?>" class="btn btn-action btn-danger" style="padding: 7px 11px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #dc2626; text-decoration: none; display: inline-block;" onclick="return confirm('Are you sure you want to delete this page?');" title="Delete Page">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                            <i class="fa-solid fa-file-lines" style="font-size: 40px; margin-bottom: 12px; color: #cbd5e1;"></i>
                            <p style="font-size: 16px; font-weight: 600; color: #334155; margin: 0;">No Website Pages Created</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
