<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<style>
.customer-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 25px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 12px;
    overflow-x: auto;
}
.customer-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.2s ease;
}
.customer-tab-btn:hover {
    color: #e11d48;
    border-color: #cbd5e1;
    background: #ffffff;
}
.customer-tab-btn.active {
    color: #ffffff;
    background: #e11d48;
    border-color: #e11d48;
    box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2);
}
.customer-tab-btn .tab-badge {
    background: rgba(255, 255, 255, 0.25);
    color: inherit;
    font-size: 11px;
    padding: 2px 7px;
    border-radius: 12px;
    font-weight: 700;
}
.customer-tab-btn:not(.active) .tab-badge {
    background: #e2e8f0;
    color: #475569;
}
.enquiry-msg-box {
    max-width: 450px;
    line-height: 1.5;
    color: #334155;
    font-size: 13.5px;
    background: #f8fafc;
    padding: 10px 14px;
    border-radius: 6px;
    border: 1px solid #edf2f7;
}
</style>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #1e293b; margin: 0;">
            <i class="fa-solid fa-users" style="margin-right: 10px; color: #e11d48;"></i> Customers & Community
        </h2>
        <p style="font-size: 13px; color: #64748b; margin: 5px 0 0 0;">Manage customer testimonials, incoming contact enquiries, and newsletter subscribers.</p>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #166534; padding: 14px 20px; border-radius: 10px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Tabs Navigation -->
<div class="customer-tabs">
    <a href="<?= base_url('admin/customers/testimonials') ?>" class="customer-tab-btn <?= ($activeTab === 'testimonials' || $activeTab === 'all') ? 'active' : '' ?>">
        <i class="fa-solid fa-comment-dots"></i> 1. Testimonials
        <span class="tab-badge"><?= count($testimonials ?? []) ?></span>
    </a>
    <a href="<?= base_url('admin/customers/enquiries') ?>" class="customer-tab-btn <?= ($activeTab === 'enquiries') ? 'active' : '' ?>">
        <i class="fa-solid fa-envelope-open-text"></i> 2. Enquiries
        <span class="tab-badge"><?= count($enquiries ?? []) ?></span>
    </a>
    <a href="<?= base_url('admin/customers/subscribers') ?>" class="customer-tab-btn <?= ($activeTab === 'subscribers') ? 'active' : '' ?>">
        <i class="fa-solid fa-bell"></i> 3. Subscribers
        <span class="tab-badge"><?= count($subscribers ?? []) ?></span>
    </a>
</div>

<!-- ==================== TAB 1: TESTIMONIALS ==================== -->
<?php if ($activeTab === 'testimonials' || $activeTab === 'all'): ?>
    <div style="display: grid; grid-template-columns: 380px 1fr; gap: 25px; align-items: start;">
        <!-- Add Testimonial Card -->
        <div class="premium-card">
            <h3 class="section-title"><i class="fa-solid fa-plus-circle" style="color: #e11d48;"></i> Add New Testimonial</h3>
            <form action="<?= base_url('admin/testimonials/store') ?>" method="POST">
                <div class="form-group">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="name" class="premium-input" required placeholder="e.g. Priya Sharma, Chennai">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Star Rating (1 to 5)</label>
                    <select name="rating" class="premium-input" style="cursor: pointer;">
                        <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars - Outstanding)</option>
                        <option value="4">⭐⭐⭐⭐ (4 Stars - Great)</option>
                        <option value="3">⭐⭐⭐ (3 Stars - Good)</option>
                        <option value="2">⭐⭐ (2 Stars)</option>
                        <option value="1">⭐ (1 Star)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Review / Testimonial Content</label>
                    <textarea name="content" class="premium-input" rows="4" required placeholder="The pure silk saree quality was extraordinary and packaging was so lovely..."></textarea>
                </div>
                
                <button type="submit" class="btn-premium" style="width: 100%;">
                    <i class="fa-solid fa-paper-plane"></i> Publish Testimonial
                </button>
            </form>
        </div>

        <!-- Existing Testimonials List -->
        <div class="premium-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="section-title" style="margin-bottom: 0;">
                    <i class="fa-solid fa-star" style="color: #fbbf24;"></i> Customer Testimonials (<?= count($testimonials ?? []) ?>)
                </h3>
            </div>
            <?php if(empty($testimonials)): ?>
                <div style="text-align: center; padding: 40px; color: #64748b;">
                    <i class="fa-solid fa-comment-slash" style="font-size: 38px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                    <p style="font-size: 15px; margin: 0;">No testimonials found. Add your first customer review using the form on the left.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 170px;">Customer Name</th>
                                <th style="width: 110px;">Rating</th>
                                <th>Testimonial Review</th>
                                <th style="width: 130px;">Date</th>
                                <th style="width: 60px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($testimonials as $test): ?>
                            <tr>
                                <td>
                                    <strong style="color: #1e293b;"><?= esc($test['name']) ?></strong>
                                </td>
                                <td>
                                    <div style="color: #fbbf24; font-size: 12px;">
                                        <?php for($i=0; $i<$test['rating']; $i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?>
                                    </div>
                                    <span style="font-size: 11px; color: #64748b; font-weight: 600;"><?= (int)$test['rating'] ?> / 5</span>
                                </td>
                                <td>
                                    <div style="color: #334155; font-size: 13px; line-height: 1.5; font-style: italic;">
                                        "<?= esc($test['content']) ?>"
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: #64748b;">
                                        <?= !empty($test['created_at']) ? date('d M Y, h:i A', strtotime($test['created_at'])) : '—' ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <a href="<?= base_url('admin/testimonials/delete/'.$test['id']) ?>" class="btn-danger-icon" onclick="return confirm('Delete this testimonial?')" title="Delete Testimonial">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- ==================== TAB 2: ENQUIRIES ==================== -->
<?php if ($activeTab === 'enquiries'): ?>
    <div class="premium-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 class="section-title" style="margin-bottom: 0;">
                <i class="fa-solid fa-envelope-open-text" style="color: #e11d48;"></i> Customer Contact Enquiries (<?= count($enquiries ?? []) ?>)
            </h3>
        </div>

        <?php if(empty($enquiries)): ?>
            <div style="text-align: center; padding: 50px; color: #64748b;">
                <i class="fa-solid fa-inbox" style="font-size: 42px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                <p style="font-size: 15px; margin: 0;">No contact enquiries received yet.</p>
                <p style="font-size: 13px; color: #94a3b8; margin-top: 6px;">Messages submitted via your storefront Contact Us page will appear here automatically.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 180px;">Customer Name</th>
                            <th style="width: 220px;">Contact Details</th>
                            <th>Enquiry Message</th>
                            <th style="width: 140px;">Received Date</th>
                            <th style="width: 70px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($enquiries as $idx => $enq): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <strong style="color: #1e293b;"><?= esc($enq['name']) ?></strong>
                                </td>
                                <td>
                                    <div style="font-size: 13px; display: flex; flex-direction: column; gap: 6px;">
                                        <a href="mailto:<?= esc($enq['email']) ?>?subject=<?= urlencode('Reply from Selvi Tex - Regarding your enquiry') ?>" style="color: #e11d48; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fa-solid fa-envelope" style="font-size: 11px; color: #e11d48;"></i> <?= esc($enq['email']) ?>
                                        </a>
                                        <?php if(!empty($enq['phone'])): 
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $enq['phone']);
                                        ?>
                                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <a href="tel:<?= esc($enq['phone']) ?>" style="color: #64748b; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                                    <i class="fa-solid fa-phone" style="font-size: 11px;"></i> <?= esc($enq['phone']) ?>
                                                </a>
                                                <?php if(!empty($cleanPhone)): ?>
                                                    <a href="https://wa.me/<?= (strlen($cleanPhone) === 10 ? '91' . $cleanPhone : $cleanPhone) ?>?text=<?= urlencode('Hello ' . $enq['name'] . ', thank you for contacting Sri Sakthi Selvi Tex! We received your enquiry: "' . mb_substr($enq['message'], 0, 50) . '...". How can we assist you?') ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 4px; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-decoration: none; border: 1px solid #bbf7d0;" title="Reply directly on WhatsApp">
                                                        <i class="fa-brands fa-whatsapp" style="color: #25D366; font-size: 12px;"></i> Reply on WhatsApp
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="enquiry-msg-box" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; font-size: 13.5px; line-height: 1.5; color: #334155;">
                                        <?= nl2br(esc($enq['message'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 3px;">
                                        <span style="font-size: 12px; font-weight: 600; color: #1e293b;">
                                            <?= !empty($enq['created_at']) ? date('d M Y', strtotime($enq['created_at'])) : '—' ?>
                                        </span>
                                        <span style="font-size: 11px; color: #64748b;">
                                            <?= !empty($enq['created_at']) ? date('h:i A', strtotime($enq['created_at'])) : '' ?>
                                        </span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <a href="<?= base_url('admin/enquiries/delete/'.$enq['id']) ?>" class="btn-danger-icon" onclick="return confirm('Delete this enquiry from <?= esc(addslashes($enq['name'])) ?>?')" title="Delete Enquiry" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #fee2e2; color: #dc2626; text-decoration: none;">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- ==================== TAB 3: SUBSCRIBERS ==================== -->
<?php if ($activeTab === 'subscribers'): ?>
    <div class="premium-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 class="section-title" style="margin-bottom: 0;">
                <i class="fa-solid fa-bell" style="color: #e11d48;"></i> Newsletter Subscribers (<?= count($subscribers ?? []) ?>)
            </h3>
        </div>

        <?php if(empty($subscribers)): ?>
            <div style="text-align: center; padding: 50px; color: #64748b;">
                <i class="fa-solid fa-envelope-circle-check" style="font-size: 42px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                <p style="font-size: 15px; margin: 0;">No newsletter subscribers yet.</p>
                <p style="font-size: 13px; color: #94a3b8; margin-top: 6px;">Customers who subscribe via your storefront footer newsletter will appear here.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Subscriber Email</th>
                            <th style="width: 220px;">Subscribed Date</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($subscribers as $idx => $sub): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <strong style="color: #1e293b;"><?= esc($sub['email']) ?></strong>
                                </td>
                                <td><?= !empty($sub['created_at']) ? date('d M Y, h:i A', strtotime($sub['created_at'])) : '—' ?></td>
                                <td style="text-align: center;">
                                    <a href="<?= base_url('admin/subscribers/delete/'.$sub['id']) ?>" class="btn-danger-icon" onclick="return confirm('Remove this subscriber?')" title="Remove Subscriber">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
