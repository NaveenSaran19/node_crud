<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card" style="padding: 28px; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px;">Footer Link Management</h1>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Organize website footer links, menu sections, display ordering, and external page links.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('admin/footer-links/create') ?>" class="btn btn-primary" style="background: #7a1530; color: #fff; font-weight: 600; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; box-shadow: 0 4px 12px rgba(122,21,48,0.25);">
                <i class="fa-solid fa-plus"></i> Add New Footer Link
            </a>
        </div>
    </div>

    <!-- Section Filter Tabs -->
    <div style="display: flex; gap: 10px; margin-bottom: 22px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; flex-wrap: wrap;">
        <a href="<?= base_url('admin/footer-links') ?>" style="padding: 7px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; text-decoration: none; <?= ($currentSection === 'all') ? 'background: #7a1530; color: #fff;' : 'background: #f1f5f9; color: #475569;' ?>">
            All Links (<?= count($links) ?>)
        </a>
        <a href="<?= base_url('admin/footer-links?section=customer_care') ?>" style="padding: 7px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; text-decoration: none; <?= ($currentSection === 'customer_care') ? 'background: #7a1530; color: #fff;' : 'background: #f1f5f9; color: #475569;' ?>">
            <i class="fa-solid fa-headset" style="margin-right: 5px;"></i> Customer Care
        </a>
        <a href="<?= base_url('admin/footer-links?section=our_collections') ?>" style="padding: 7px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; text-decoration: none; <?= ($currentSection === 'our_collections') ? 'background: #7a1530; color: #fff;' : 'background: #f1f5f9; color: #475569;' ?>">
            <i class="fa-solid fa-gem" style="margin-right: 5px;"></i> Our Collections
        </a>
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
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 80px;">Order</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">Link Title</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">Destination URL</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 150px;">Section</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 120px;">Status</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 140px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($links)): ?>
                    <?php foreach($links as $link): 
                        $targetUrl = (str_starts_with($link['url'], 'http://') || str_starts_with($link['url'], 'https://')) ? $link['url'] : base_url(ltrim($link['url'], '/'));
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px; font-weight: 700; color: #0f172a;">
                            <span style="display: inline-block; width: 32px; height: 32px; line-height: 32px; text-align: center; background: #f1f5f9; border-radius: 6px; font-size: 13px; color: #334155;">
                                <?= (int)$link['display_order'] ?>
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 15px;">
                                <?= esc($link['title']) ?>
                            </div>
                            <?php if(!empty($link['open_in_new_tab'])): ?>
                                <span style="font-size: 11.5px; color: #0284c7; background: #e0f2fe; padding: 2px 7px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; margin-top: 3px;">
                                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i> Opens in New Tab
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 16px;">
                            <a href="<?= esc($targetUrl) ?>" target="_blank" style="color: #991b1b; text-decoration: underline; font-size: 13.5px; font-family: monospace;">
                                <?= esc($link['url']) ?>
                            </a>
                        </td>
                        <td style="padding: 16px;">
                            <?php
                                $secBadgeStyle = ($link['section'] === 'customer_care') 
                                    ? 'background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;' 
                                    : 'background: #fdf2f8; color: #9d174d; border: 1px solid #fbcfe8;';
                                $secLabel = ($link['section'] === 'customer_care') ? 'Customer Care' : (($link['section'] === 'our_collections') ? 'Our Collections' : ucwords(str_replace('_', ' ', $link['section'])));
                            ?>
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; <?= $secBadgeStyle ?>">
                                <?= esc($secLabel) ?>
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <form method="POST" action="<?= base_url('admin/footer-links/toggle/' . $link['id']) ?>" style="display: inline-block; margin: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" title="Click to toggle status">
                                    <?php if($link['is_active']): ?>
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                            <span style="width: 7px; height: 7px; background: #059669; border-radius: 50%;"></span> Active
                                        </span>
                                    <?php else: ?>
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                            <span style="width: 7px; height: 7px; background: #dc2626; border-radius: 50%;"></span> Inactive
                                        </span>
                                    <?php endif; ?>
                                </button>
                            </form>
                        </td>
                        <td style="padding: 16px; text-align: right; white-space: nowrap;">
                            <a href="<?= base_url('admin/footer-links/edit/' . $link['id']) ?>" class="btn btn-action" style="padding: 7px 11px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; text-decoration: none; margin-right: 5px; display: inline-block;" title="Edit Link">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="<?= base_url('admin/footer-links/delete/' . $link['id']) ?>" class="btn btn-action btn-danger" style="padding: 7px 11px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #dc2626; text-decoration: none; display: inline-block;" onclick="return confirm('Are you sure you want to delete this footer link?');" title="Delete Link">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                            <i class="fa-solid fa-link-slash" style="font-size: 40px; margin-bottom: 12px; color: #cbd5e1;"></i>
                            <p style="font-size: 16px; font-weight: 600; color: #334155; margin: 0;">No Footer Links in this Section</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
