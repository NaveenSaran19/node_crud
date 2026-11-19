<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card" style="padding: 28px; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px;">Frequently Asked Questions</h1>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Manage customer help questions, ordering details, and display orders for the storefront.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('faq') ?>" target="_blank" class="btn" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-weight: 600; padding: 10px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Preview Frontend FAQs
            </a>
            <a href="<?= base_url('admin/faqs/create') ?>" class="btn btn-primary" style="background: #e11d48; color: #fff; font-weight: 600; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; box-shadow: 0 4px 12px rgba(225, 29, 72,0.25);">
                <i class="fa-solid fa-plus"></i> Add New FAQ
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
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 80px;">Order</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase;">Question</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 140px;">Category</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 120px;">Status</th>
                    <th style="padding: 14px 16px; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; width: 130px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($faqs)): ?>
                    <?php foreach($faqs as $faq): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                        <td style="padding: 16px; font-weight: 700; color: #0f172a;">
                            <span style="display: inline-block; width: 32px; height: 32px; line-height: 32px; text-align: center; background: #f1f5f9; border-radius: 6px; font-size: 13px; color: #334155;">
                                <?= (int)$faq['display_order'] ?>
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 15px; margin-bottom: 4px;">
                                <?= esc($faq['question']) ?>
                            </div>
                            <div style="font-size: 13px; color: #64748b; max-height: 38px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 580px;">
                                <?= strip_tags($faq['answer']) ?>
                            </div>
                        </td>
                        <td style="padding: 16px;">
                            <span style="display: inline-block; padding: 4px 10px; background: #fdf2f8; color: #9d174d; border: 1px solid #fbcfe8; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                <?= esc($faq['category'] ?: 'General') ?>
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <form method="POST" action="<?= base_url('admin/faqs/toggle/' . $faq['id']) ?>" style="display: inline-block; margin: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" title="Click to toggle status">
                                    <?php if($faq['is_active']): ?>
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
                            <a href="<?= base_url('admin/faqs/edit/' . $faq['id']) ?>" class="btn btn-action" style="padding: 7px 12px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; text-decoration: none; margin-right: 6px; display: inline-block;" title="Edit FAQ">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="<?= base_url('admin/faqs/delete/' . $faq['id']) ?>" class="btn btn-action btn-danger" style="padding: 7px 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #dc2626; text-decoration: none; display: inline-block;" onclick="return confirm('Are you sure you want to permanently delete this FAQ question?');" title="Delete FAQ">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                            <i class="fa-solid fa-circle-question" style="font-size: 40px; margin-bottom: 12px; color: #cbd5e1;"></i>
                            <p style="font-size: 16px; font-weight: 600; color: #334155; margin: 0 0 6px;">No FAQs Found</p>
                            <p style="font-size: 14px; margin: 0;">Click the "Add New FAQ" button above to create your first question.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
