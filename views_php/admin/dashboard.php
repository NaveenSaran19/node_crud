<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Custom Styles for Outlines & Availability Grid -->
<style>
/* Distinct Card & Container Outlines requested by user */
.dashboard-hero-card {
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 16px !important;
    box-shadow: 0 6px 20px rgba(73, 7, 23, 0.12) !important;
}

.dashboard-card,
.stat-card,
.quick-action-card,
.availability-summary-container,
.availability-card {
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 14px !important;
    background: #ffffff !important;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04) !important;
    transition: all 0.25s ease-in-out !important;
}

.stat-card:hover,
.quick-action-card:hover,
.availability-card:hover {
    border-color: #e11d48 !important;
    box-shadow: 0 8px 24px rgba(225, 29, 72, 0.09) !important;
    transform: translateY(-2px);
}

/* Sales Grid & Availability Grid */
.sales-metric-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.availability-summary-container {
    padding: 24px 26px;
    margin-bottom: 28px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 16px !important;
}

.availability-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-top: 18px;
}

.availability-card {
    padding: 18px 20px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 12px !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}

.availability-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}

.avail-card-featured::before { background: #d4af37; }
.avail-card-popular::before { background: #f97316; }
.avail-card-trending::before { background: #10b981; }
.avail-card-new::before { background: #8b5cf6; }

.availability-card:hover {
    background: #ffffff;
    border-color: #e11d48 !important;
}

.avail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.avail-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-featured { background: #fef9c3; color: #854d0e; }
.badge-popular { background: #ffedd5; color: #9a3412; }
.badge-trending { background: #ecfdf5; color: #065f46; }
.badge-new { background: #f3e8ff; color: #6b21a8; }

.avail-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.icon-featured { background: rgba(212, 175, 55, 0.15); color: #b45309; }
.icon-popular { background: rgba(249, 115, 22, 0.15); color: #ea580c; }
.icon-trending { background: rgba(16, 185, 129, 0.15); color: #059669; }
.icon-new { background: rgba(139, 92, 246, 0.15); color: #7c3aed; }

.avail-count {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 4px;
}

.avail-label {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 2px;
}

.avail-desc {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 12px;
}

.avail-footer-link {
    font-size: 12px;
    font-weight: 700;
    color: #e11d48;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding-top: 10px;
    border-top: 1px dashed #cbd5e1;
}

@media (max-width: 1200px) {
    .sales-metric-grid, .availability-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .sales-metric-grid, .availability-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Welcome & Overview Header with Outline -->
<div class="dashboard-hero-card">
    <div class="hero-content">
        <div class="hero-badge">
            <span class="pulse-indicator"></span>
            <span>Live Store Operational</span>
        </div>
        <h1 class="hero-title">Welcome back, Administrator 👋</h1>
        <p class="hero-subtitle">Real-time sales, catalog availability summary and store performance overview.</p>
    </div>
    <div class="hero-meta">
        <div class="hero-date-badge">
            <i class="fa-regular fa-calendar-days"></i>
            <span><?= date('l, M d, Y') ?></span>
        </div>
    </div>
</div>

<!-- =========================================================================
     1. SALES PERFORMANCE SUMMARY (Daily, Weekly, Monthly, Yearly Sales)
     ========================================================================= -->
<div class="dashboard-section" style="margin-bottom: 28px;">
    <div class="section-title-wrap" style="margin-bottom: 16px;">
        <div class="title-left">
            <h2 class="section-heading" style="font-size: 17px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-chart-pie" style="color: #e11d48;"></i> Sales Revenue Breakdown
            </h2>
            <p class="section-subtext" style="font-size: 13px; color: #64748b; margin: 2px 0 0;">
                Track completed online &amp; COD revenue across daily, weekly, monthly and yearly intervals.
            </p>
        </div>
    </div>

    <div class="sales-metric-grid">
        <!-- Daily Sales (Today) -->
        <div class="stat-card" style="border-top: 4px solid #0284c7 !important;">
            <div class="stat-header">
                <span class="stat-label">Daily Sales</span>
                <div class="stat-icon-badge" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
            <div class="stat-value">₹ <?= number_format($dailySales ?? 0, 2) ?></div>
            <div class="stat-footer">
                <span class="stat-pill pill-info"><i class="fa-solid fa-clock"></i> Today</span>
                <span class="stat-meta-text" style="font-weight: 600; color: #475569;"><?= number_format($dailyOrdersCount ?? 0) ?> order(s)</span>
            </div>
        </div>

        <!-- Weekly Sales (Last 7 Days) -->
        <div class="stat-card" style="border-top: 4px solid #10b981 !important;">
            <div class="stat-header">
                <span class="stat-label">Weekly Sales</span>
                <div class="stat-icon-badge icon-emerald">
                    <i class="fa-solid fa-calendar-week"></i>
                </div>
            </div>
            <div class="stat-value">₹ <?= number_format($weeklySales ?? 0, 2) ?></div>
            <div class="stat-footer">
                <span class="stat-pill pill-success"><i class="fa-solid fa-arrow-trend-up"></i> Past 7 Days</span>
                <span class="stat-meta-text" style="font-weight: 600; color: #475569;"><?= number_format($weeklyOrdersCount ?? 0) ?> order(s)</span>
            </div>
        </div>

        <!-- Monthly Sales (Current Month) -->
        <div class="stat-card" style="border-top: 4px solid #e11d48 !important;">
            <div class="stat-header">
                <span class="stat-label">Monthly Sales</span>
                <div class="stat-icon-badge icon-maroon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">₹ <?= number_format($monthlySales ?? 0, 2) ?></div>
            <div class="stat-footer">
                <span class="stat-pill pill-neutral"><i class="fa-regular fa-calendar"></i> <?= date('F Y') ?></span>
                <span class="stat-meta-text" style="font-weight: 600; color: #475569;"><?= number_format($monthlyOrdersCount ?? 0) ?> order(s)</span>
            </div>
        </div>

        <!-- Yearly Sales (Current Year) -->
        <div class="stat-card" style="border-top: 4px solid #d4af37 !important;">
            <div class="stat-header">
                <span class="stat-label">Yearly Sales</span>
                <div class="stat-icon-badge icon-gold">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
            <div class="stat-value">₹ <?= number_format($yearlySales ?? 0, 2) ?></div>
            <div class="stat-footer">
                <span class="stat-pill pill-gold"><i class="fa-solid fa-trophy"></i> Year <?= date('Y') ?></span>
                <span class="stat-meta-text" style="font-weight: 600; color: #475569;"><?= number_format($yearlyOrdersCount ?? 0) ?> order(s)</span>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     2. STORE CATALOG & OPERATIONAL STATS (Total Product, Total Category, etc.)
     ========================================================================= -->
<div class="stats-grid" style="margin-bottom: 28px;">
    <!-- Total Products -->
    <div class="stat-card stat-card-maroon">
        <div class="stat-header">
            <span class="stat-label">Total Products</span>
            <div class="stat-icon-badge icon-maroon">
                <i class="fa-solid fa-box-open"></i>
            </div>
        </div>
        <div class="stat-value"><?= number_format($totalProducts ?? 0) ?></div>
        <div class="stat-footer">
            <span class="stat-pill pill-neutral"><i class="fa-solid fa-layer-group"></i> Active Catalog Items</span>
            <a href="<?= base_url('admin/products') ?>" class="stat-link" title="Manage products">Manage <i class="fa-solid fa-angle-right"></i></a>
        </div>
    </div>
    
    <!-- Total Categories -->
    <div class="stat-card stat-card-amber">
        <div class="stat-header">
            <span class="stat-label">Total Categories</span>
            <div class="stat-icon-badge icon-amber">
                <i class="fa-solid fa-tags"></i>
            </div>
        </div>
        <div class="stat-value"><?= number_format($totalCategories ?? 0) ?></div>
        <div class="stat-footer">
            <span class="stat-pill pill-info"><i class="fa-solid fa-folder-tree"></i> <?= number_format($totalSubcategories ?? 0) ?> Sub-categories</span>
            <a href="<?= base_url('admin/categories') ?>" class="stat-link" title="Manage Categories">View <i class="fa-solid fa-angle-right"></i></a>
        </div>
    </div>
    
    <!-- Active Orders -->
    <div class="stat-card stat-card-emerald">
        <div class="stat-header">
            <span class="stat-label">Active Orders</span>
            <div class="stat-icon-badge icon-emerald">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>
        <div class="stat-value"><?= number_format($activeOrders ?? 0) ?></div>
        <div class="stat-footer">
            <span class="stat-pill pill-warning"><i class="fa-solid fa-clock"></i> Processing &amp; Pending</span>
            <a href="<?= base_url('admin/orders') ?>" class="stat-link" title="View orders">View <i class="fa-solid fa-angle-right"></i></a>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="stat-card stat-card-gold">
        <div class="stat-header">
            <span class="stat-label">Registered Customers</span>
            <div class="stat-icon-badge icon-gold">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="stat-value"><?= number_format($totalCustomers ?? 0) ?></div>
        <div class="stat-footer">
            <span class="stat-pill pill-success"><i class="fa-solid fa-user-check"></i> Buyer Accounts</span>
            <a href="<?= base_url('admin/customers/enquiries') ?>" class="stat-link" title="View enquiries">Inquiries <i class="fa-solid fa-angle-right"></i></a>
        </div>
    </div>
</div>

<!-- =========================================================================
     3. PRODUCT AVAILABILITY SUMMARY (Featured, Popular, Trending, New Arrivals)
     ========================================================================= -->
<div class="availability-summary-container">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 16px;">
        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-cubes-stacked" style="color: #e11d48;"></i> Product Availability Summary
            </h2>
            <p style="font-size: 13px; color: #64748b; margin: 0;">
                Live distribution of catalog tags, featured collections, and special showcase badges across your storefront.
            </p>
        </div>
        <div>
            <a href="<?= base_url('admin/products') ?>" style="padding: 7px 14px; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 12.5px; font-weight: 700; color: #0f172a; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-sliders"></i> Filter Catalog
            </a>
        </div>
    </div>

    <div class="availability-grid">
        <!-- Card 1: Featured -->
        <a href="<?= base_url('admin/products') ?>" class="availability-card avail-card-featured">
            <div>
                <div class="avail-header">
                    <span class="avail-badge badge-featured">Live Showcase</span>
                    <div class="avail-icon icon-featured">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                </div>
                <div class="avail-count"><?= number_format($featuredProductsCount ?? 0) ?></div>
                <div class="avail-label">Featured</div>
                <div class="avail-desc">Showcased in Homepage Live Collection</div>
            </div>
            <div class="avail-footer-link">
                <span>View Products</span> <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- Card 2: Popular -->
        <a href="<?= base_url('admin/products') ?>" class="availability-card avail-card-popular">
            <div>
                <div class="avail-header">
                    <span class="avail-badge badge-popular">Best Sellers</span>
                    <div class="avail-icon icon-popular">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                </div>
                <div class="avail-count"><?= number_format($popularProductsCount ?? 0) ?></div>
                <div class="avail-label">Popular</div>
                <div class="avail-desc">Marked as high-converting Best Sellers</div>
            </div>
            <div class="avail-footer-link">
                <span>View Products</span> <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- Card 3: Trending -->
        <a href="<?= base_url('admin/products') ?>" class="availability-card avail-card-trending">
            <div>
                <div class="avail-header">
                    <span class="avail-badge badge-trending">Best of Selvi Tex</span>
                    <div class="avail-icon icon-trending">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                </div>
                <div class="avail-count"><?= number_format($trendingProductsCount ?? 0) ?></div>
                <div class="avail-label">Trending</div>
                <div class="avail-desc">Selected for Top Trending Weavers Saree Edit</div>
            </div>
            <div class="avail-footer-link">
                <span>View Products</span> <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- Card 4: New Arrivals -->
        <a href="<?= base_url('admin/products') ?>" class="availability-card avail-card-new">
            <div>
                <div class="avail-header">
                    <span class="avail-badge badge-new">New Loom Drops</span>
                    <div class="avail-icon icon-new">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                </div>
                <div class="avail-count"><?= number_format($newArrivalsCount ?? 0) ?></div>
                <div class="avail-label">New Arrivals</div>
                <div class="avail-desc">Newly added authentic handloom sarees</div>
            </div>
            <div class="avail-footer-link">
                <span>View Products</span> <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>
    </div>
</div>

<!-- =========================================================================
     4. QUICK ACTION SHORTCUTS
     ========================================================================= -->
<div class="dashboard-section" style="margin-bottom: 28px;">
    <div class="section-title-wrap">
        <div class="title-left">
            <h2 class="section-heading"><i class="fa-solid fa-bolt" style="color: var(--secondary);"></i> Quick Actions</h2>
            <p class="section-subtext">Direct shortcuts to common management tasks</p>
        </div>
    </div>
    <div class="quick-actions-grid">
        <a href="<?= base_url('admin/products/create') ?>" class="quick-action-card">
            <div class="qa-icon-wrap qa-maroon">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div class="qa-details">
                <div class="qa-title">Add New Product</div>
                <div class="qa-desc">Upload product, variants &amp; pricing</div>
            </div>
            <div class="qa-arrow-btn">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="<?= base_url('admin/orders') ?>" class="quick-action-card">
            <div class="qa-icon-wrap qa-emerald">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="qa-details">
                <div class="qa-title">Manage Orders</div>
                <div class="qa-desc">Fulfill shipments &amp; track status</div>
            </div>
            <div class="qa-arrow-btn">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="<?= base_url('admin/categories') ?>" class="quick-action-card">
            <div class="qa-icon-wrap qa-amber">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div class="qa-details">
                <div class="qa-title">Product Categories</div>
                <div class="qa-desc">Organize collections &amp; sub-cats</div>
            </div>
            <div class="qa-arrow-btn">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="<?= base_url('admin/banners') ?>" class="quick-action-card">
            <div class="qa-icon-wrap qa-indigo">
                <i class="fa-solid fa-images"></i>
            </div>
            <div class="qa-details">
                <div class="qa-title">Hero Banners</div>
                <div class="qa-desc">Update storefront slides &amp; promos</div>
            </div>
            <div class="qa-arrow-btn">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="<?= base_url('admin/customers/enquiries') ?>" class="quick-action-card">
            <div class="qa-icon-wrap qa-rose">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div class="qa-details">
                <div class="qa-title">Customer Enquiries</div>
                <div class="qa-desc">View contact forms &amp; leads</div>
            </div>
            <div class="qa-arrow-btn">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="<?= base_url('admin/settings/general') ?>" class="quick-action-card">
            <div class="qa-icon-wrap qa-slate">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div class="qa-details">
                <div class="qa-title">Store Settings</div>
                <div class="qa-desc">Business info, social &amp; SEO</div>
            </div>
            <div class="qa-arrow-btn">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>
    </div>
</div>

<!-- =========================================================================
     5. SALES ANALYTICS CHART CARD
     ========================================================================= -->
<div class="dashboard-card analytics-card" style="margin-bottom: 28px;">
    <div class="dashboard-card-header">
        <div class="header-info">
            <h2 class="card-title"><i class="fa-solid fa-chart-line" style="color: var(--primary);"></i> Sales Revenue Report</h2>
            <p class="card-subtitle">Real-time revenue performance and trend monitoring</p>
        </div>
        <div class="chart-controls">
            <select id="salesPeriod" class="chart-select" title="Select Reporting Period">
                <option value="daily">Daily (Last 7 Days)</option>
                <option value="weekly">Weekly (Last 4 Weeks)</option>
                <option value="monthly">Monthly (Last 6 Months)</option>
                <option value="yearly">Yearly (Last 5 Years)</option>
                <option value="financial_year">Financial Year (Apr - Mar)</option>
                <option value="custom">Custom Date Range</option>
            </select>
            <div id="customDateContainer" class="custom-date-box" style="display: none;">
                <input type="date" id="startDate" class="date-input">
                <span class="date-sep">to</span>
                <input type="date" id="endDate" class="date-input">
                <button type="button" id="btnApplyCustomRange" class="btn btn-chart-apply">Apply</button>
            </div>
            <div class="chart-export-btns">
                <button type="button" id="btnExportExcel" class="btn btn-export-excel" title="Export to Excel">
                    <i class="fa-solid fa-file-excel"></i> <span>Excel</span>
                </button>
                <button type="button" id="btnExportPdf" class="btn btn-export-pdf" title="Export to PDF">
                    <i class="fa-solid fa-file-pdf"></i> <span>PDF</span>
                </button>
            </div>
        </div>
    </div>
    <div class="chart-container-wrap">
        <canvas id="salesChart" data-labels='<?= esc($salesLabels, 'attr') ?>' data-values='<?= esc($salesData, 'attr') ?>' data-endpoint="<?= base_url('admin/dashboard/sales-data') ?>"></canvas>
    </div>
</div>

<!-- =========================================================================
     6. RECENT ORDERS CARD
     ========================================================================= -->
<div class="dashboard-card">
    <div class="dashboard-card-header">
        <div class="header-info">
            <h2 class="card-title"><i class="fa-solid fa-bag-shopping" style="color: var(--primary);"></i> Recent Orders</h2>
            <p class="card-subtitle">Latest storefront purchases placed by customers</p>
        </div>
        <a href="<?= base_url('admin/orders') ?>" class="btn btn-view-all">
            <span>View All Orders</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Order Ref</th>
                    <th>Contact Details</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Fulfillment Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                <tr>
                    <td colspan="6" class="table-empty-row">
                        <div class="empty-state">
                            <i class="fa-solid fa-cart-arrow-down"></i>
                            <p>No recent orders found</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td>
                        <span class="order-id-badge">#ORD-<?= esc($order['id']) ?></span>
                    </td>
                    <td>
                        <div class="customer-chip">
                            <div class="customer-avatar">
                                <?php
                                    $cName = trim($order['customer_name'] ?? '');
                                    $parts = preg_split('/\s+/', $cName);
                                    if (count($parts) >= 2 && !empty($parts[0]) && !empty($parts[1])) {
                                        $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
                                    } else {
                                        $initials = strtoupper(substr($cName ?: 'CU', 0, 2));
                                    }
                                    echo esc($initials);
                                ?>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span class="customer-name" style="font-weight: 700; color: #0f172a;"><?= esc($cName ?: 'Guest Shopper') ?></span>
                                <?php if (!empty($order['customer_email']) && $order['customer_email'] !== 'guest@ssptex.com'): ?>
                                    <span style="font-size: 11.5px; color: #0284c7; margin-top: 1px; font-weight: 500;">
                                        <i class="fa-solid fa-envelope" style="font-size: 9.5px;"></i> <?= esc($order['customer_email']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($order['customer_phone'])): ?>
                                    <span style="font-size: 11.5px; color: #475569; margin-top: 1px; font-weight: 500;">
                                        <i class="fa-solid fa-phone" style="font-size: 9.5px; color: #16a34a;"></i> <?= esc($order['customer_phone']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="date-text"><?= date('M d, Y', strtotime($order['created_at'])) ?></span>
                    </td>
                    <td>
                        <strong class="order-amount">₹ <?= number_format($order['total_amount'], 2) ?></strong>
                    </td>
                    <td>
                        <?php 
                        $statusClass = 'status-default';
                        $statusIcon = 'fa-circle-dot';
                        if ($order['status'] === 'Processing' || $order['status'] === 'Pending') { 
                            $statusClass = 'status-warning';
                            $statusIcon = 'fa-clock';
                        } elseif ($order['status'] === 'Shipped') { 
                            $statusClass = 'status-info';
                            $statusIcon = 'fa-truck-fast';
                        } elseif ($order['status'] === 'Delivered' || $order['status'] === 'Completed') {
                            $statusClass = 'status-success';
                            $statusIcon = 'fa-circle-check';
                        } elseif ($order['status'] === 'Cancelled') {
                            $statusClass = 'status-danger';
                            $statusIcon = 'fa-circle-xmark';
                        }
                        ?>
                        <span class="status-pill <?= $statusClass ?>">
                            <i class="fa-solid <?= $statusIcon ?>"></i>
                            <span><?= esc($order['status']) ?></span>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="<?= base_url('admin/orders/view/' . $order['id']) ?>" class="btn btn-order-view">
                            <span>Details</span>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="<?= base_url('assets/js/admin-dashboard.js') ?>"></script>

<?= $this->endSection() ?>
