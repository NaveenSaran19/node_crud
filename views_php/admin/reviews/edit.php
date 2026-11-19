<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="content-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0 0 5px 0;">Edit Customer Review</h1>
        <p style="color: #64748b; margin: 0; font-size: 14px;">Review for: <strong><?= esc($product['name'] ?? 'Product #' . $review['product_id']) ?></strong></p>
    </div>
    <a href="<?= base_url('admin/reviews') ?>" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Back to Reviews
    </a>
</div>

<?php if(session()->getFlashdata('errors')): ?>
    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach(session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="premium-card" style="max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.04);">
    <form action="<?= base_url('admin/reviews/update/' . $review['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Customer Name *</label>
                <input type="text" name="customer_name" class="premium-input" value="<?= old('customer_name', $review['customer_name']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Customer Email</label>
                <input type="email" name="customer_email" class="premium-input" value="<?= old('customer_email', $review['customer_email']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Rating (1 to 5 Stars) *</label>
                <select name="rating" class="premium-input" required>
                    <?php for($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>" <?= old('rating', $review['rating']) == $i ? 'selected' : '' ?>><?= $i ?> Stars (<?= str_repeat('★', $i) ?>)</option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Approval Status *</label>
                <select name="status" class="premium-input" required>
                    <option value="Pending" <?= old('status', $review['status']) === 'Pending' ? 'selected' : '' ?>>Pending Moderation</option>
                    <option value="Approved" <?= old('status', $review['status']) === 'Approved' ? 'selected' : '' ?>>Approved (Publicly Visible)</option>
                    <option value="Rejected" <?= old('status', $review['status']) === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Review Title *</label>
            <input type="text" name="review_title" class="premium-input" value="<?= old('review_title', $review['review_title']) ?>" required>
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label class="form-label" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Review Content / Comments *</label>
            <textarea name="review_text" class="premium-input" rows="5" required><?= old('review_text', $review['review_text']) ?></textarea>
        </div>

        <?php if(!empty($review['review_image'])): ?>
            <div style="margin-bottom: 25px; padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px;">
                <img src="<?= base_url($review['review_image']) ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: #1e293b;">Uploaded Review Photo</div>
                    <a href="<?= base_url($review['review_image']) ?>" target="_blank" style="font-size: 12px; color: #7a1530; text-decoration: none;">View Full Resolution</a>
                </div>
            </div>
        <?php endif; ?>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
            <a href="<?= base_url('admin/reviews') ?>" class="btn" style="padding: 10px 20px; background: #f1f5f9; color: #475569; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">Cancel</a>
            <button type="submit" class="btn" style="padding: 10px 24px; background: #7a1530; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer;">Save Changes</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
