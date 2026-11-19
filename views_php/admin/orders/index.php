<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card">
    <?php 
        $totalOrders = count($orders ?? []);
        $codCount = 0;
        $gatewayCount = 0;
        $paidCount = 0;
        $pendingCount = 0;
        foreach($orders ?? [] as $oItem) {
            $isCod = (stripos($oItem['payment_method'] ?? '', 'cash') !== false);
            if ($isCod) { $codCount++; } else { $gatewayCount++; }
            if (strtolower($oItem['payment_status'] ?? '') === 'paid') { $paidCount++; } else { $pendingCount++; }
        }
    ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <h2 style="font-size: 18px; font-weight: 700; margin: 0;">Manage Orders</h2>
        <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;"><?= $totalOrders ?> Total Orders Recorded</span>
    </div>

    <!-- Quick Payment & Gateway Filter Pills -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
        <button type="button" class="admin-order-filter-btn active" data-admin-filter="all" style="padding: 7px 16px; border-radius: 20px; border: 1.5px solid var(--primary); background: var(--primary); color: #fff; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-list-check"></i> All (<?= $totalOrders ?>)
        </button>
        <button type="button" class="admin-order-filter-btn" data-admin-filter="cod" style="padding: 7px 16px; border-radius: 20px; border: 1.5px solid #bbf7d0; background: #f0fdf4; color: #166534; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery (<?= $codCount ?>)
        </button>
        <button type="button" class="admin-order-filter-btn" data-admin-filter="gateway" style="padding: 7px 16px; border-radius: 20px; border: 1.5px solid #bfdbfe; background: #eff6ff; color: #1e40af; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-credit-card"></i> Razorpay Gateway (<?= $gatewayCount ?>)
        </button>
        <button type="button" class="admin-order-filter-btn" data-admin-filter="paid" style="padding: 7px 16px; border-radius: 20px; border: 1.5px solid #86efac; background: #f0fdf4; color: #15803d; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-circle-check"></i> Paid (<?= $paidCount ?>)
        </button>
        <button type="button" class="admin-order-filter-btn" data-admin-filter="pending" style="padding: 7px 16px; border-radius: 20px; border: 1.5px solid #fed7aa; background: #fffaf0; color: #c2410c; font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-clock"></i> Payment Pending (<?= $pendingCount ?>)
        </button>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table id="adminOrdersTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Contact Details</th>
                    <th>Total Amount</th>
                    <th>Payment Method &amp; Status</th>
                    <th>Fulfillment Status</th>
                    <th>Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($orders)): ?>
                    <?php foreach($orders as $o): 
                        $isCOD = (stripos($o['payment_method'] ?? '', 'cash') !== false);
                        $isPaid = (strtolower($o['payment_status'] ?? '') === 'paid');
                        $payTypeAttr = $isCOD ? 'cod' : 'gateway';
                        $statusTypeAttr = $isPaid ? 'paid' : 'pending';
                    ?>
                    <tr class="admin-order-row" data-payment-type="<?= $payTypeAttr ?>" data-payment-status="<?= $statusTypeAttr ?>" style="border-bottom: 1px solid var(--border); transition: background 0.2s;">
                        <td style="padding: 15px 10px;">
                            <strong>#<?= esc($o['id']) ?></strong>
                            <?php if(!empty($o['order_number'])): ?>
                                <div style="font-size: 11.5px; color: var(--primary); font-weight: 700; margin-top: 2px;">
                                    <?= esc(str_replace('SSP-', 'ST-', $o['order_number'])) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px 10px;">
                            <div style="font-weight: 700; color: var(--text-dark); font-size: 13.5px; margin-bottom: 3px;">
                                <i class="fa-solid fa-user" style="color: var(--primary); font-size: 11px; margin-right: 4px;"></i>
                                <?= esc($o['customer_name'] ?? ('User #' . $o['user_id'])) ?>
                            </div>
                            <?php if(!empty($o['customer_email'])): ?>
                                <div style="font-size: 11.5px; color: #0284c7; margin-bottom: 2px;">
                                    <i class="fa-solid fa-envelope" style="font-size: 10px; margin-right: 4px;"></i>
                                    <a href="mailto:<?= esc($o['customer_email']) ?>" style="color: #0284c7; text-decoration: none; font-weight: 500;"><?= esc($o['customer_email']) ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($o['customer_phone'])): ?>
                                <div style="font-size: 11.5px; color: #334155; margin-bottom: 2px;">
                                    <i class="fa-solid fa-phone" style="font-size: 10px; margin-right: 4px; color: #16a34a;"></i>
                                    <a href="tel:<?= esc($o['customer_phone']) ?>" style="color: #334155; text-decoration: none; font-weight: 500;"><?= esc($o['customer_phone']) ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($o['shipping_city'])): ?>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    <i class="fa-solid fa-location-dot" style="font-size: 10px; margin-right: 4px;"></i>
                                    <?= esc($o['shipping_city']) ?><?= !empty($o['shipping_state']) ? ', ' . esc($o['shipping_state']) : '' ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px 10px;">
                            <div style="font-weight: 800; font-size: 15px; color: var(--primary);">
                                ₹<?= number_format((float)$o['total_amount'], 2) ?>
                            </div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                Subtotal: ₹<?= number_format((float)($o['subtotal'] ?? $o['total_amount']), 2) ?>
                                <?php if((float)($o['discount_amount'] ?? 0) > 0): ?>
                                    <br><span style="color: #16a34a; font-weight: 600;">Saved: -₹<?= number_format((float)$o['discount_amount'], 2) ?></span>
                                <?php endif; ?>
                                <?php if((float)($o['shipping_amount'] ?? 0) > 0): ?>
                                    <br><span>Shipping: ₹<?= number_format((float)$o['shipping_amount'], 2) ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding: 15px 10px;">
                            <?php if($isCOD): ?>
                                <div style="display: inline-flex; align-items: center; gap: 6px; font-weight: 700; color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 6px; font-size: 11.5px;">
                                    <i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery
                                </div>
                            <?php else: ?>
                                <div style="display: inline-flex; align-items: center; gap: 6px; font-weight: 700; color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; padding: 4px 10px; border-radius: 6px; font-size: 11.5px;">
                                    <i class="fa-solid fa-credit-card"></i> Razorpay Gateway
                                </div>
                            <?php endif; ?>
                            
                            <div style="margin-top: 4px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <span style="font-size: 11px; font-weight: 700; color: <?= $isPaid ? '#16a34a' : '#d97706' ?>; background: <?= $isPaid ? '#dcfce7' : '#fef3c7' ?>; padding: 2px 6px; border-radius: 4px;">
                                    <?= $isPaid ? 'Paid' : esc($o['payment_status'] ?? 'Pending') ?>
                                </span>
                                <?php if(!empty($o['razorpay_payment_id'])): ?>
                                    <span style="font-size: 10px; font-family: monospace; color: #0284c7; background: #f0f9ff; border: 1px solid #bae6fd; padding: 2px 5px; border-radius: 3px;" title="Razorpay Payment ID">
                                        <?= esc($o['razorpay_payment_id']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding: 15px 10px;">
                            <span style="background: <?= $o['status'] == 'Pending' ? '#f59e0b' : ($o['status'] == 'Completed' ? '#10b981' : ($o['status'] == 'Shipped' ? '#8b5cf6' : '#0284c7')) ?>; color: white; padding: 4px 9px; border-radius: 4px; font-size: 11.5px; font-weight: 700; text-transform: uppercase;">
                                <?= esc($o['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 15px 10px; font-size: 13px; color: var(--text-muted);"><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
                        <td style="padding: 15px 10px; text-align: right;">
                            <a href="<?= base_url('admin/orders/invoice/' . $o['id']) ?>" class="btn btn-action" style="padding: 6px 12px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-receipt"></i> View / Receipt
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="padding: 30px; text-align: center; color: var(--text-muted);">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.admin-order-filter-btn');
    const orderRows = document.querySelectorAll('.admin-order-row');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.style.background = '';
                b.style.color = '';
                b.classList.remove('active');
                if (b.getAttribute('data-admin-filter') === 'all') {
                    b.style.background = '#f8fafc';
                    b.style.color = '#475569';
                    b.style.borderColor = '#cbd5e1';
                } else if (b.getAttribute('data-admin-filter') === 'cod') {
                    b.style.background = '#f0fdf4';
                    b.style.color = '#166534';
                } else if (b.getAttribute('data-admin-filter') === 'gateway') {
                    b.style.background = '#eff6ff';
                    b.style.color = '#1e40af';
                } else if (b.getAttribute('data-admin-filter') === 'paid') {
                    b.style.background = '#f0fdf4';
                    b.style.color = '#15803d';
                } else if (b.getAttribute('data-admin-filter') === 'pending') {
                    b.style.background = '#fffaf0';
                    b.style.color = '#c2410c';
                }
            });

            this.classList.add('active');
            this.style.background = 'var(--primary)';
            this.style.color = '#ffffff';
            this.style.borderColor = 'var(--primary)';

            const filter = this.getAttribute('data-admin-filter');

            orderRows.forEach(row => {
                const payType = row.getAttribute('data-payment-type');
                const payStatus = row.getAttribute('data-payment-status');

                let show = false;
                if (filter === 'all') {
                    show = true;
                } else if (filter === 'cod' && payType === 'cod') {
                    show = true;
                } else if (filter === 'gateway' && payType === 'gateway') {
                    show = true;
                } else if (filter === 'paid' && payStatus === 'paid') {
                    show = true;
                } else if (filter === 'pending' && payStatus === 'pending') {
                    show = true;
                }

                row.style.display = show ? '' : 'none';
            });
        });
    });
});
</script>

<?= $this->endSection() ?>
