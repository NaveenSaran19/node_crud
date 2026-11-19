<?= view('user/header') ?>

<style>
:root {
    --cust-primary: #e11d48;
    --cust-primary-dark: #5a0e22;
    --cust-gold: #c59b27;
    --cust-bg: #f8fafc;
    --cust-card-bg: #ffffff;
    --cust-border: #e2e8f0;
}

.cust-dashboard-wrap {
    background-color: var(--cust-bg);
    min-height: 85vh;
    padding: 40px 20px 80px 20px;
    font-family: 'Inter', -apple-system, sans-serif;
}

.cust-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Profile Welcome Banner */
.cust-welcome-banner {
    background: linear-gradient(135deg, #e11d48 0%, #4a0d1e 100%);
    border-radius: 16px;
    padding: 30px 35px;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 35px;
    box-shadow: 0 10px 25px rgba(225, 29, 72, 0.15);
    position: relative;
    overflow: hidden;
}

.cust-welcome-banner::after {
    content: '';
    position: absolute;
    right: -20px;
    bottom: -40px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(197, 155, 39, 0.2) 0%, transparent 70%);
    pointer-events: none;
}

.cust-avatar-row {
    display: flex;
    align-items: center;
    gap: 18px;
}

.cust-avatar-circle {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: #ffffff;
    color: var(--cust-primary);
    font-size: 26px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: 3px solid rgba(255,255,255,0.4);
}

.cust-welcome-text h1 {
    font-size: 24px;
    font-weight: 800;
    margin: 0 0 5px 0;
    font-family: 'Cinzel', 'Georgia', serif;
}

.cust-welcome-text p {
    font-size: 13.5px;
    color: #fdf5e6;
    margin: 0;
    opacity: 0.9;
}

.cust-banner-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cust-banner-btn {
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.cust-btn-outline {
    background: rgba(255,255,255,0.15);
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.3);
}
.cust-btn-outline:hover {
    background: rgba(255,255,255,0.25);
}

.cust-btn-cart {
    background: #c59b27;
    color: #1e1e1e;
    border: none;
}
.cust-btn-cart:hover {
    background: #dfb234;
}

/* Dashboard 2-column layout */
.cust-dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 30px;
    align-items: start;
}

/* Cards */
.cust-card {
    background: #ffffff;
    border: 1px solid var(--cust-border);
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}

.cust-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 18px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cust-card-title span {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Order item block */
.cust-order-card {
    border: 1px solid var(--cust-border);
    border-radius: 10px;
    margin-bottom: 16px;
    overflow: hidden;
    transition: border-color 0.2s;
}

.cust-order-card:hover {
    border-color: #cbd5e1;
}

.cust-order-head {
    background: #f8fafc;
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--cust-border);
    font-size: 13px;
    flex-wrap: wrap;
    gap: 10px;
}

.cust-order-body {
    padding: 16px 18px;
}

.cust-order-items-list {
    list-style: none;
    padding: 0;
    margin: 0 0 14px 0;
}

.cust-order-items-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
    padding: 8px 0;
    border-bottom: 1px dashed #f1f5f9;
}

.cust-order-items-list li:last-child {
    border-bottom: none;
}

.cust-order-foot {
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    padding-top: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cust-print-invoice-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fffdfa;
    border: 1.5px solid #d4af37;
    color: #e11d48;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(212, 175, 55, 0.15);
    transition: all 0.2s ease;
}
.cust-print-invoice-btn:hover {
    background: #e11d48;
    color: #ffffff;
    border-color: #e11d48;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
}
.cust-print-invoice-btn:hover i {
    color: #ffffff !important;
}

/* Status Badges */
.status-pill {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-block;
}

.status-pending { background: #fef3c7; color: #b45309; }
.status-processing { background: #e0f2fe; color: #0369a1; }
.status-completed { background: #dcfce7; color: #15803d; }
.status-shipped { background: #f3e8ff; color: #7e22ce; }

/* Customer Dashboard Orders Filter Tabs */
.cust-orders-filter-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--cust-border);
}
.cust-filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 7px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: inherit;
}
.cust-filter-tab:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #cbd5e1;
}
.cust-filter-tab.active {
    background: var(--cust-primary);
    color: #ffffff;
    border-color: var(--cust-primary);
    box-shadow: 0 2px 8px rgba(225, 29, 72, 0.25);
}
.cust-filter-tab.active i {
    color: #ffffff !important;
}

/* Payment Mode Badges */
.pay-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
}
.pay-cod {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.pay-gateway {
    background: #eff6ff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}
.pay-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
}
.pay-status-paid {
    background: #dcfce7;
    color: #15803d;
}
.pay-status-pending {
    background: #fef3c7;
    color: #b45309;
}

/* Active Cart Mini List */
.cust-cart-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}
.cust-cart-item:last-child { border-bottom: none; }

.cust-cart-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.cust-cart-details {
    flex: 1;
}
.cust-cart-details h4 {
    font-size: 13px;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: #1e293b;
}

.cust-cart-details span {
    font-size: 12px;
    color: #64748b;
}

/* Recommendation Grid */
.cust-rec-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 15px;
}

.cust-rec-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s, box-shadow 0.2s;
    display: block;
}

.cust-rec-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.06);
}

.cust-rec-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.cust-rec-item-info {
    padding: 12px;
}

.cust-rec-item-info h4 {
    font-size: 13px;
    font-weight: 700;
    margin: 0 0 6px 0;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 900px) {
    .cust-dashboard-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="cust-dashboard-wrap">
    <div class="cust-container">

        <!-- Customer Welcome Strip -->
        <div class="cust-welcome-banner">
            <div class="cust-avatar-row">
                <div class="cust-avatar-circle">
                    <?= strtoupper(substr($customer['name'] ?: 'C', 0, 1)) ?>
                </div>
                <div class="cust-welcome-text">
                    <h1>Vanakkam, <?= esc($customer['name']) ?></h1>
                    <p><i class="fa-solid fa-envelope"></i> <?= esc($customer['email']) ?> &bull; Elampillai Handloom Member</p>
                </div>
            </div>

            <div class="cust-banner-actions">
                <a href="<?= base_url('cart') ?>" class="cust-banner-btn cust-btn-cart">
                    <i class="fa-solid fa-bag-shopping"></i> View Cart (<?= count($cartItems) ?> items)
                </a>
                <a href="<?= base_url('customer/logout') ?>" class="cust-banner-btn cust-btn-outline" title="Sign Out">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                </a>
            </div>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 14px 18px; border-radius: 10px; margin-bottom: 25px; font-size: 14px; font-weight: 600;">
                <i class="fa-solid fa-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <div class="cust-dashboard-grid">
            
            <!-- Left Column: Orders History -->
            <div>
                <div class="cust-card">
                    <?php 
                        $totalOrders = count($orders);
                        $codOrdersCount = 0;
                        $gatewayOrdersCount = 0;
                        $paidOrdersCount = 0;
                        $pendingOrdersCount = 0;
                        foreach($orders as $oItem) {
                            $isCodItem = (stripos($oItem['payment_method'] ?? '', 'cash') !== false);
                            if ($isCodItem) { $codOrdersCount++; } else { $gatewayOrdersCount++; }
                            if (strtolower($oItem['payment_status'] ?? '') === 'paid') { $paidOrdersCount++; } else { $pendingOrdersCount++; }
                        }
                    ?>
                    <div class="cust-card-title">
                        <span><i class="fa-solid fa-box-archive" style="color: var(--cust-primary);"></i> My Orders & Deliveries</span>
                        <span style="font-size: 13px; font-weight: 600; color: #64748b;"><?= $totalOrders ?> Orders Placed</span>
                    </div>

                    <?php if(!empty($orders)): ?>
                        <!-- Quick Payment & Status Filter Tabs -->
                        <div class="cust-orders-filter-tabs">
                            <button type="button" class="cust-filter-tab active" data-cust-filter="all">
                                <i class="fa-solid fa-layer-group"></i> All (<?= $totalOrders ?>)
                            </button>
                            <button type="button" class="cust-filter-tab" data-cust-filter="cod">
                                <i class="fa-solid fa-money-bill-wave" style="color: #16a34a;"></i> Cash on Delivery (<?= $codOrdersCount ?>)
                            </button>
                            <button type="button" class="cust-filter-tab" data-cust-filter="gateway">
                                <i class="fa-solid fa-credit-card" style="color: #2563eb;"></i> Payment Gateway (<?= $gatewayOrdersCount ?>)
                            </button>
                            <button type="button" class="cust-filter-tab" data-cust-filter="paid">
                                <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Paid (<?= $paidOrdersCount ?>)
                            </button>
                            <button type="button" class="cust-filter-tab" data-cust-filter="pending">
                                <i class="fa-solid fa-clock" style="color: #f59e0b;"></i> Pending (<?= $pendingOrdersCount ?>)
                            </button>
                        </div>

                        <div id="custOrdersContainer">
                        <?php foreach($orders as $ord): 
                            $statusClass = 'status-' . strtolower($ord['status']);
                            $isCOD = (stripos($ord['payment_method'] ?? '', 'cash') !== false);
                            $payStatus = $ord['payment_status'] ?? 'Pending';
                            $isPaid = (strtolower($payStatus) === 'paid');
                            $payFilterVal = $isCOD ? 'cod' : 'gateway';
                            $statusFilterVal = $isPaid ? 'paid' : 'pending';
                        ?>
                        <div class="cust-order-card" data-payment-type="<?= $payFilterVal ?>" data-payment-status="<?= $statusFilterVal ?>">
                            <div class="cust-order-head">
                                <div>
                                    <strong>Order ID: #<?= esc($ord['id']) ?></strong>
                                    <span style="color: #64748b; margin-left: 8px;">(<?= esc(str_replace('SSP-', 'ST-', $ord['order_number'] ?? 'N/A')) ?>)</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span class="status-pill <?= $statusClass ?>"><?= esc($ord['status']) ?></span>
                                    <span style="color: #64748b; font-size: 12px;"><?= date('d M Y', strtotime($ord['created_at'])) ?></span>
                                </div>
                            </div>

                            <div class="cust-order-body">
                                <ul class="cust-order-items-list">
                                    <?php if(!empty($ord['items'])): ?>
                                        <?php foreach($ord['items'] as $it): ?>
                                            <li>
                                                <div>
                                                    <strong><?= esc($it['product_name']) ?></strong>
                                                    <?php 
                                                        $cleanVariant = !empty($it['variant_name']) ? trim(preg_replace('/(?:\s*\/\s*)?Free\s*Size/i', '', $it['variant_name'])) : '';
                                                        if(!empty($cleanVariant)): 
                                                    ?>
                                                        <span style="font-size: 11.5px; color: #64748b; margin-left: 6px;">[<?= esc($cleanVariant) ?>]</span>
                                                    <?php endif; ?>
                                                    <span style="color: #64748b; margin-left: 8px;">&times; <?= $it['quantity'] ?></span>
                                                </div>
                                                <div class="cust-order-item-price" data-base-price="<?= (float)$it['price'] * (int)$it['quantity'] ?>" style="font-weight: 600;">₹<?= number_format((float)$it['price'] * (int)$it['quantity'], 2) ?></div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #64748b;">Pure Handloom Saree</li>
                                    <?php endif; ?>
                                </ul>

                                <div class="cust-order-foot" style="flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between;">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px;">
                                            <?php if($isCOD): ?>
                                                <span class="pay-badge-pill pay-cod">
                                                    <i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery
                                                </span>
                                            <?php else: ?>
                                                <span class="pay-badge-pill pay-gateway">
                                                    <i class="fa-solid fa-credit-card"></i> Razorpay Online Gateway
                                                </span>
                                            <?php endif; ?>

                                            <span class="pay-status-pill <?= $isPaid ? 'pay-status-paid' : 'pay-status-pending' ?>">
                                                <i class="fa-solid <?= $isPaid ? 'fa-circle-check' : 'fa-clock' ?>"></i>
                                                <?= esc($payStatus) ?>
                                            </span>
                                        </div>

                                        <?php if(!empty($ord['razorpay_payment_id'])): ?>
                                            <div style="font-size: 11.5px; color: #0284c7; font-family: monospace; margin-bottom: 4px;">
                                                <i class="fa-solid fa-receipt"></i> Razorpay Payment ID: <strong><?= esc($ord['razorpay_payment_id']) ?></strong>
                                            </div>
                                        <?php endif; ?>

                                        <div class="cust-order-total-price" data-base-price="<?= (float)$ord['total_amount'] ?>" style="font-size: 16px; font-weight: 800; color: var(--cust-primary);">
                                            Total: ₹<?= number_format((float)$ord['total_amount'], 2) ?>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('order/invoice/' . (int)$ord['id']) ?>" target="_blank" class="cust-print-invoice-btn" title="View &amp; Print Official Tax Invoice">
                                            <i class="fa-solid fa-print"></i>
                                            <span>Print Bill A4</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                        <div id="custNoMatchMsg" style="display: none; text-align: center; padding: 35px 20px; color: #64748b; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                            <i class="fa-solid fa-filter-circle-xmark" style="font-size: 32px; color: #94a3b8; margin-bottom: 8px;"></i>
                            <div style="font-weight: 700; color: #334155;">No orders match this filter</div>
                            <div style="font-size: 12.5px; margin-top: 4px;">Try clicking "All" to view all your placed orders.</div>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 45px 20px; color: #64748b;">
                            <i class="fa-solid fa-box-open" style="font-size: 44px; color: #cbd5e1; margin-bottom: 12px;"></i>
                            <h3 style="font-size: 16px; font-weight: 700; color: #334155; margin: 0 0 6px 0;">No Orders Placed Yet</h3>
                            <p style="font-size: 13px; margin: 0 0 18px 0;">Explore our direct-from-weaver collections and place your first order.</p>
                            <a href="<?= base_url('collections') ?>" class="cust-banner-btn" style="background: var(--cust-primary); color: white;">
                                Start Shopping
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Explore All Collections Strip -->
                <div class="cust-card" style="margin-top: 30px;">
                    <div class="cust-card-title">
                        <span><i class="fa-solid fa-sparkles" style="color: var(--cust-gold);"></i> Handpicked Handlooms For You</span>
                        <a href="<?= base_url('collections') ?>" style="font-size: 13px; color: var(--cust-primary); text-decoration: none; font-weight: 700;">View All &rarr;</a>
                    </div>
                    
                    <div class="cust-rec-grid">
                        <?php if(!empty($recommendedProducts)): ?>
                            <?php foreach(array_slice($recommendedProducts, 0, 4) as $rp): ?>
                                <a href="<?= base_url('product/' . esc($rp['slug'])) ?>" class="cust-rec-item">
                                    <img src="<?= esc($rp['thumbnail']) ?>" alt="<?= esc($rp['name'] ?? $rp['title'] ?? 'Handloom Saree') ?>" loading="lazy">
                                    <div class="cust-rec-item-info">
                                        <h4><?= esc($rp['name'] ?? $rp['title'] ?? 'Handloom Saree') ?></h4>
                                        <div class="cust-rec-item-price" data-base-price="<?= (float)$rp['price'] ?>" style="font-size: 14px; font-weight: 800; color: var(--cust-primary);">
                                            ₹<?= number_format((float)$rp['price'], 2) ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Current Cart & Quick Details -->
            <div>
                <!-- Cart Preview -->
                <div class="cust-card">
                    <div class="cust-card-title">
                        <span><i class="fa-solid fa-cart-shopping" style="color: var(--cust-primary);"></i> Active Shopping Cart</span>
                        <span style="font-size: 12px; background: #f1f5f9; padding: 3px 8px; border-radius: 6px;"><?= count($cartItems) ?> Items</span>
                    </div>

                    <?php if(!empty($cartItems)): ?>
                        <?php foreach($cartItems as $ci): ?>
                            <div class="cust-cart-item">
                                <img src="<?= esc($ci['image_url']) ?>" alt="<?= esc($ci['product_name']) ?>" class="cust-cart-thumb">
                                <div class="cust-cart-details">
                                    <h4><?= esc($ci['product_name']) ?></h4>
                                    <span>Qty: <?= $ci['quantity'] ?> &bull; <span class="cust-cart-unit-price" data-base-price="<?= $ci['price'] ?>">₹<?= number_format($ci['price'], 2) ?></span></span>
                                </div>
                                <div class="cust-cart-line-total" data-base-price="<?= $ci['line_total'] ?>" style="font-weight: 700; font-size: 14px; color: #0f172a;">
                                    ₹<?= number_format($ci['line_total'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div style="border-top: 1.5px solid #e2e8f0; margin-top: 16px; padding-top: 16px;">
                            <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 16px; margin-bottom: 16px;">
                                <span>Cart Total</span>
                                <span class="cust-cart-total-price" data-base-price="<?= $cartSubtotal ?>" style="color: var(--cust-primary);">₹<?= number_format($cartSubtotal, 2) ?></span>
                            </div>

                            <a href="<?= base_url('checkout') ?>" class="cust-banner-btn cust-btn-cart" style="width: 100%; justify-content: center; padding: 12px; font-size: 14px; box-sizing: border-box;">
                                <i class="fa-solid fa-lock"></i> Proceed to Checkout
                            </a>
                            <a href="<?= base_url('cart') ?>" style="display: block; text-align: center; margin-top: 10px; font-size: 12px; color: #64748b; text-decoration: none; font-weight: 600;">
                                Edit cart items in full cart page
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 25px 15px; color: #64748b;">
                            <p style="margin: 0 0 14px 0; font-size: 13px;">Your shopping cart is currently empty.</p>
                            <a href="<?= base_url('collections') ?>" class="cust-banner-btn cust-btn-outline" style="background: var(--cust-primary); color: white; border: none; font-size: 12px;">
                                Browse Sarees
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Account Security & Store Help -->
                <div class="cust-card">
                    <h3 style="font-size: 15px; font-weight: 800; margin: 0 0 12px 0; color: #0f172a;">Need Help with an Order?</h3>
                    <p style="font-size: 13px; color: #64748b; line-height: 1.6; margin: 0 0 16px 0;">
                        Our weavers and customer support team are available directly via WhatsApp.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php 
                            $dashWa = preg_replace('/[^0-9]/', '', $settings['whatsapp_no'] ?? '919789296751');
                            if (strlen($dashWa) === 10) { $dashWa = '91' . $dashWa; }
                            elseif (strlen($dashWa) === 11 && strpos($dashWa, '0') === 0) { $dashWa = '91' . substr($dashWa, 1); }
                        ?>
                        <a href="https://wa.me/<?= esc($dashWa) ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 12px 14px; border-radius: 8px; background: #f0fdf4; color: #15803d; text-decoration: none; font-size: 13.5px; font-weight: 700; border: 1.5px solid #bbf7d0; transition: all 0.2s ease;">
                            <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i> Direct WhatsApp Support
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.cust-filter-tab');
    const orderCards = document.querySelectorAll('#custOrdersContainer .cust-order-card');
    const noMatchMsg = document.getElementById('custNoMatchMsg');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-cust-filter');
            let visibleCount = 0;

            orderCards.forEach(card => {
                const payType = card.getAttribute('data-payment-type');
                const payStatus = card.getAttribute('data-payment-status');

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

                if (show) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noMatchMsg) {
                noMatchMsg.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    });
});
</script>

<?= view('user/footer') ?>
