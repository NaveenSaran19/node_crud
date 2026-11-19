<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="content-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0 0 5px 0;">Customer Reviews Moderation</h1>
        <p style="color: #64748b; margin: 0; font-size: 14px;">Moderate, approve, reject, or edit reviews submitted by customers.</p>
    </div>
    
    <div style="display: flex; gap: 10px;">
        <span style="background: #fef3c7; color: #92400e; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-clock"></i> Pending: <?= $pending_count ?>
        </span>
        <span style="background: #dcfce7; color: #166534; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-check"></i> Approved: <?= $approved_count ?>
        </span>
        <span style="background: #fee2e2; color: #991b1b; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-xmark"></i> Rejected: <?= $rejected_count ?>
        </span>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-circle-exclamation"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Filter Bar -->
<div style="background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 16px 20px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <form action="<?= base_url('admin/reviews') ?>" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 2; min-width: 200px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 5px;">Filter by Product</label>
            <select name="product_id" class="premium-input" style="height: 40px; padding: 6px 12px; font-size: 13px;">
                <option value="">All Products</option>
                <?php foreach($products as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $selected_product == $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex: 1; min-width: 140px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 5px;">Filter by Status</label>
            <select name="status" class="premium-input" style="height: 40px; padding: 6px 12px; font-size: 13px;">
                <option value="">All Statuses</option>
                <option value="Pending" <?= $selected_status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $selected_status === 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= $selected_status === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>

        <div style="flex: 1; min-width: 130px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 5px;">Filter by Rating</label>
            <select name="rating" class="premium-input" style="height: 40px; padding: 6px 12px; font-size: 13px;">
                <option value="">All Ratings</option>
                <option value="5" <?= $selected_rating == '5' ? 'selected' : '' ?>>5 Stars ★★★★★</option>
                <option value="4" <?= $selected_rating == '4' ? 'selected' : '' ?>>4 Stars ★★★★</option>
                <option value="3" <?= $selected_rating == '3' ? 'selected' : '' ?>>3 Stars ★★★</option>
                <option value="2" <?= $selected_rating == '2' ? 'selected' : '' ?>>2 Stars ★★</option>
                <option value="1" <?= $selected_rating == '1' ? 'selected' : '' ?>>1 Star ★</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" style="height: 40px; padding: 0 18px; background: #7a1530; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-filter"></i> Apply
            </button>
            <a href="<?= base_url('admin/reviews') ?>" style="height: 40px; padding: 0 14px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: flex; align-items: center;">Reset</a>
        </div>
    </form>
</div>

<!-- Reviews Table -->
<div style="background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left; color: #475569;">
                    <th style="padding: 14px 16px;">Product</th>
                    <th style="padding: 14px 16px;">Customer</th>
                    <th style="padding: 14px 16px;">Rating</th>
                    <th style="padding: 14px 16px;">Review</th>
                    <th style="padding: 14px 16px;">Photo</th>
                    <th style="padding: 14px 16px;">Status</th>
                    <th style="padding: 14px 16px;">Date</th>
                    <th style="padding: 14px 16px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($reviews)): ?>
                    <?php foreach($reviews as $rev): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 16px; vertical-align: top; max-width: 200px;">
                                <div style="font-weight: 700; color: #1e293b; line-height: 1.3;">
                                    <?= esc($rev['product_name'] ?? 'Product #' . $rev['product_id']) ?>
                                </div>
                                <?php if(!empty($rev['product_slug'])): ?>
                                    <a href="<?= base_url('product/' . esc($rev['product_slug'])) ?>" target="_blank" style="font-size: 11px; color: #7a1530; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-top: 4px;">
                                        View Product <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 9px;"></i>
                                    </a>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-weight: 700; color: #1e293b;"><?= esc($rev['customer_name']) ?></div>
                                <?php if(!empty($rev['customer_email'])): ?>
                                    <div style="font-size: 11px; color: #64748b;"><?= esc($rev['customer_email']) ?></div>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 14px 16px; vertical-align: top; white-space: nowrap;">
                                <div style="color: #f59e0b; font-size: 14px;">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-<?= $i <= (int)$rev['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span style="font-size: 11px; color: #64748b; font-weight: 600;"><?= (int)$rev['rating'] ?> / 5</span>
                            </td>

                            <td style="padding: 14px 16px; vertical-align: top; max-width: 300px;">
                                <div style="font-weight: 700; color: #1e293b; margin-bottom: 4px;"><?= esc($rev['review_title']) ?></div>
                                <div style="color: #475569; font-size: 12px; line-height: 1.5;"><?= nl2br(esc($rev['review_text'])) ?></div>
                            </td>

                            <td style="padding: 14px 16px; vertical-align: top;">
                                <?php if(!empty($rev['review_image'])): ?>
                                    <a href="<?= base_url($rev['review_image']) ?>" target="_blank">
                                        <img src="<?= base_url($rev['review_image']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    </a>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-size: 11px;">None</span>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 14px 16px; vertical-align: top;">
                                <?php if($rev['status'] === 'Approved'): ?>
                                    <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-check"></i> Approved
                                    </span>
                                <?php elseif($rev['status'] === 'Pending'): ?>
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-clock"></i> Pending
                                    </span>
                                <?php else: ?>
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-xmark"></i> Rejected
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 14px 16px; vertical-align: top; white-space: nowrap; color: #64748b; font-size: 12px;">
                                <?= date('d M Y', strtotime($rev['created_at'])) ?>
                            </td>

                            <td style="padding: 14px 16px; vertical-align: top; text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <?php if($rev['status'] !== 'Approved'): ?>
                                        <a href="<?= base_url('admin/reviews/approve/' . $rev['id']) ?>" class="btn btn-sm" style="background: #16a34a; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-decoration: none;" title="Approve Review">
                                            <i class="fa-solid fa-check"></i> Approve
                                        </a>
                                    <?php endif; ?>

                                    <?php if($rev['status'] !== 'Rejected'): ?>
                                        <a href="<?= base_url('admin/reviews/reject/' . $rev['id']) ?>" class="btn btn-sm" style="background: #eab308; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-decoration: none;" title="Reject Review">
                                            <i class="fa-solid fa-ban"></i> Reject
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('admin/reviews/edit/' . $rev['id']) ?>" style="background: #f1f5f9; color: #334155; padding: 4px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; border: 1px solid #cbd5e1;" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <a href="<?= base_url('admin/reviews/delete/' . $rev['id']) ?>" onclick="return confirm('Are you sure you want to delete this review?');" style="background: #fee2e2; color: #ef4444; padding: 4px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; border: 1px solid #fecaca;" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #94a3b8;">
                            <i class="fa-solid fa-star-half-stroke" style="font-size: 36px; margin-bottom: 10px; color: #cbd5e1; display: block;"></i>
                            <div style="font-size: 15px; font-weight: 600; color: #475569;">No customer reviews found</div>
                            <p style="margin: 4px 0 0 0; font-size: 13px;">Customer reviews submitted from product detail pages will appear here for moderation.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
