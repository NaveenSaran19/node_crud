<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Tax Invoice') ?></title>
    <?php
        $invSettingsModel = new \App\Models\SettingsModel();
        $invFavSetting = $invSettingsModel->where('key', 'favicon')->first();
        $invLogoSetting = $invSettingsModel->where('key', 'business_logo')->first();
        $invoiceFavicon = base_url('assets/images/logo.png');
        if ($invFavSetting && !empty($invFavSetting['value']) && file_exists(FCPATH . ltrim($invFavSetting['value'], '/'))) {
            $invoiceFavicon = base_url(ltrim($invFavSetting['value'], '/'));
        } elseif ($invLogoSetting && !empty($invLogoSetting['value']) && file_exists(FCPATH . ltrim($invLogoSetting['value'], '/'))) {
            $invoiceFavicon = base_url(ltrim($invLogoSetting['value'], '/'));
        }
    ?>
    <link rel="icon" type="image/png" href="<?= $invoiceFavicon ?>">
    <link rel="shortcut icon" href="<?= $invoiceFavicon ?>">
    <link rel="apple-touch-icon" href="<?= $invoiceFavicon ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #e11d48;
            --brand-gold: #c59b27;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            padding: 30px 15px;
        }

        /* Top Action Bar for Admin */
        .no-print-bar {
            max-width: 820px;
            margin: 0 auto 20px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border: 1px solid var(--border-color);
        }

        .no-print-bar .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .btn-back {
            background: #f8fafc;
            color: #475569;
            border-color: #cbd5e1;
        }
        .btn-back:hover { background: #e2e8f0; }

        .btn-print {
            background: var(--brand-primary);
            color: #ffffff;
        }
        .btn-print:hover { background: #5a0e22; }

        .order-status-quickform {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .order-status-quickform select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
            outline: none;
            background: #ffffff;
        }
        .btn-update {
            background: #0f172a;
            color: #ffffff;
            border: none;
        }
        .btn-update:hover { background: #1e293b; }

        /* A4 Invoice Paper Container */
        .invoice-sheet {
            background: #ffffff;
            max-width: 820px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.07);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        /* Header strip */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--brand-primary);
            padding-bottom: 24px;
            margin-bottom: 24px;
            gap: 20px;
        }

        .brand-block h1 {
            font-family: 'Cinzel', serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--brand-primary);
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .brand-meta {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 380px;
        }

        .invoice-badge-block {
            text-align: right;
        }

        .invoice-badge-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }

        .invoice-number {
            font-family: monospace;
            font-size: 15px;
            font-weight: 700;
            color: var(--brand-primary);
            background: #fdf2f4;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
            border: 1px solid #fbcfe8;
            margin-bottom: 6px;
        }

        .invoice-dates {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* 2-Column Addresses */
        .address-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            background: #f8fafc;
            padding: 18px 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 28px;
        }

        .address-box h3 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: var(--brand-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .address-box p {
            font-size: 12.5px;
            line-height: 1.55;
            color: #334155;
        }

        /* Itemized Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .items-table th {
            background: #f1f5f9;
            color: #334155;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 14px;
            border-bottom: 1.5px solid #cbd5e1;
            text-align: left;
        }

        .items-table td {
            padding: 12px 14px;
            font-size: 13px;
            color: #1e293b;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .items-table th.text-center, .items-table td.text-center { text-align: center; }
        .items-table th.text-right, .items-table td.text-right { text-align: right; }

        .item-thumb-box {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
        }

        .item-thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .item-name-cell strong {
            display: block;
            font-size: 13.5px;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .item-variant-tag {
            display: inline-block;
            font-size: 11px;
            background: #e2e8f0;
            color: #475569;
            padding: 2px 7px;
            border-radius: 3px;
            font-weight: 600;
        }

        /* Summary Block (Split Notes vs Totals) */
        .summary-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
            align-items: start;
            margin-bottom: 30px;
        }

        .payment-info-box {
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 16px;
        }

        .payment-info-box h4 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 8px;
        }

        .payment-info-box p {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 7px 10px;
            font-size: 13px;
        }

        .totals-table td.label-col {
            color: var(--text-muted);
            text-align: left;
        }

        .totals-table td.val-col {
            text-align: right;
            font-weight: 600;
            color: #1e293b;
        }

        .totals-table tr.grand-total-row td {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 12px 10px;
            font-size: 16px;
            font-weight: 800;
            color: var(--brand-primary);
        }

        /* Invoice Footer & Signatory */
        .invoice-footer {
            border-top: 1px solid var(--border-color);
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
        }

        .footer-terms {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 440px;
        }

        .signature-box {
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            width: 180px;
            border-bottom: 1.5px solid #0f172a;
            margin: 40px auto 8px auto;
        }

        .signature-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #475569;
            letter-spacing: 0.5px;
        }

        /* Printable A4 Specifications */
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print-bar {
                display: none !important;
            }

            .invoice-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                margin: 0 !important;
            }

            .address-grid {
                background: #fdfdfd !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .items-table th {
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-number {
                background: #fdf2f4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .item-thumb-box {
                border: 1px solid #cbd5e1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .item-thumb-box img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Non-printable Controls Bar -->
    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <?php if(!empty($isCustomerView)): ?>
                <a href="<?= base_url('customer/dashboard') ?>" class="btn btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back to My Orders
                </a>
            <?php else: ?>
                <a href="<?= base_url('admin/orders') ?>" class="btn btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
            <?php endif; ?>
            <span style="font-size: 13px; color: #64748b; font-weight: 500;">
                Order #<?= esc($order['id']) ?> (<?= esc(str_replace('SSP-', 'ST-', $order['order_number'] ?? 'N/A')) ?>)
            </span>
        </div>

        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <?php if(empty($isCustomerView)): ?>
            <form action="<?= base_url('admin/orders/update-status/' . $order['id']) ?>" method="POST" class="order-status-quickform">
                <select name="status" title="Change Order Fulfillment Status">
                    <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Status: Pending</option>
                    <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Status: Processing</option>
                    <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Status: Shipped</option>
                    <option value="Completed" <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Status: Completed</option>
                    <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Status: Cancelled</option>
                </select>
                <select name="payment_status" title="Change Payment Status">
                    <option value="Pending (COD)" <?= ($order['payment_status'] ?? '') == 'Pending (COD)' ? 'selected' : '' ?>>Payment: Pending (COD)</option>
                    <option value="Paid" <?= ($order['payment_status'] ?? '') == 'Paid' ? 'selected' : '' ?>>Payment: Paid</option>
                    <option value="Failed" <?= ($order['payment_status'] ?? '') == 'Failed' ? 'selected' : '' ?>>Payment: Failed</option>
                    <option value="Refunded" <?= ($order['payment_status'] ?? '') == 'Refunded' ? 'selected' : '' ?>>Payment: Refunded</option>
                </select>
                <button type="submit" class="btn btn-update">
                    <i class="fa-solid fa-check"></i> Update
                </button>
            </form>
            <?php endif; ?>

            <button type="button" onclick="window.print()" class="btn btn-print">
                <i class="fa-solid fa-print"></i> Print A4 Invoice / Receipt
            </button>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
    <div style="max-width: 820px; margin: 0 auto 15px auto; background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; padding: 12px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 8px;" class="no-print">
        <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i>
        <span><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
    <?php endif; ?>

    <!-- Printable A4 Invoice Sheet -->
    <div class="invoice-sheet">
        
        <!-- Header -->
        <div class="invoice-header">
            <div class="brand-block">
                <h1><?= esc($settings['business_name'] ?? 'Sri Sakthi Selvi Tex') ?></h1>
                <div class="brand-meta">
                    <strong>Authentic Elampillai Handloom Sarees</strong><br>
                    <?= esc($settings['business_address'] ?? 'Elampillai, Salem District, Tamil Nadu, India - 637502') ?><br>
                    <span><strong>Phone:</strong> <?= esc($settings['phone_no'] ?? '+91 98765 43210') ?></span> &bull; 
                    <span><strong>Email:</strong> <?= esc($settings['contact_email'] ?? 'support@selvitex.com') ?></span><br>
                    <?php if(!empty($settings['gst_number'])): ?>
                        <span><strong>GSTIN:</strong> <?= esc($settings['gst_number']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="invoice-badge-block">
                <div class="invoice-badge-title">TAX INVOICE / RECEIPT</div>
                <?php 
                    $orderNumRaw = $order['order_number'] ?? ('ST-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT));
                    $orderNumST = str_replace('SSP-', 'ST-', $orderNumRaw);
                    $invFormatted = (strpos($orderNumST, 'INV-') === 0) ? $orderNumST : ('INV-' . $orderNumST);
                ?>
                <div class="invoice-number"><?= esc($invFormatted) ?></div>
                <div class="invoice-dates">
                    <strong>Invoice Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?><br>
                    <strong>Payment Mode:</strong> 
                    <?php if(stripos($order['payment_method'] ?? '', 'cash') !== false): ?>
                        <span style="color: #166534; font-weight: 700;">Cash on Delivery (COD)</span>
                    <?php else: ?>
                        <span style="color: #1e40af; font-weight: 700;">Razorpay Online Gateway</span>
                    <?php endif; ?><br>
                    <strong>Payment Status:</strong> 
                    <span style="color: <?= strtolower($order['payment_status'] ?? '') === 'paid' ? '#16a34a' : '#d97706' ?>; font-weight: 700;">
                        <?= esc($order['payment_status'] ?? 'Pending') ?>
                    </span>
                    <?php if(!empty($order['razorpay_payment_id'])): ?>
                        <br><strong>Razorpay Payment ID:</strong> <span style="font-family: monospace; color: #0284c7; font-weight: 700;"><?= esc($order['razorpay_payment_id']) ?></span>
                    <?php elseif(!empty($order['razorpay_order_id'])): ?>
                        <br><strong>Razorpay Order ID:</strong> <span style="font-family: monospace; color: #64748b;"><?= esc($order['razorpay_order_id']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Addresses Grid -->
        <div class="address-grid">
            <div class="address-box">
                <h3><i class="fa-solid fa-truck"></i> Shipping Destination</h3>
                <p>
                    <strong><?= esc($order['customer_name'] ?? 'Valued Customer') ?></strong><br>
                    <?= esc($order['shipping_address'] ?? 'N/A') ?><br>
                    <?= esc($order['shipping_city'] ?? '') ?><?= !empty($order['shipping_state']) ? ', ' . esc($order['shipping_state']) : '' ?><br>
                    <strong>PIN Code:</strong> <?= esc($order['shipping_pincode'] ?? 'N/A') ?>, <?= esc($order['shipping_country'] ?? 'India') ?><br>
                    <strong>Phone:</strong> <?= esc($order['customer_phone'] ?? 'N/A') ?><br>
                    <strong>Email:</strong> <?= esc($order['customer_email'] ?? 'N/A') ?>
                </p>
            </div>

            <div class="address-box">
                <h3><i class="fa-solid fa-receipt"></i> Billed To</h3>
                <p>
                    <strong><?= esc($order['customer_name'] ?? 'Valued Customer') ?></strong><br>
                    <?= esc($order['billing_address'] ?: $order['shipping_address'] ?: 'Same as Shipping Address') ?><br>
                    <?= esc($order['shipping_city'] ?? '') ?><?= !empty($order['shipping_state']) ? ', ' . esc($order['shipping_state']) : '' ?><br>
                    <strong>PIN Code:</strong> <?= esc($order['shipping_pincode'] ?? 'N/A') ?><br>
                    <strong>Order Status:</strong> <?= esc($order['status'] ?? 'Processing') ?>
                </p>
            </div>
        </div>

        <!-- Order Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 10%;" class="text-center">Image</th>
                    <th style="width: 43%;">Item Description</th>
                    <th style="width: 14%;" class="text-center">Rate (₹)</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 18%;" class="text-right">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($items)): ?>
                    <?php 
                        $fallbackImg = base_url('assets/images/placeholder.jpg');
                        $i = 1; 
                        foreach($items as $item): 
                            $rate = (float)($item['price'] ?? 0);
                            $qty = (int)($item['quantity'] ?? 1);
                            $itemTotal = $rate * $qty;
                            $imgSrc = !empty($item['image_url']) ? $item['image_url'] : $fallbackImg;
                    ?>
                    <tr>
                        <td class="text-center" style="color: var(--text-muted);"><?= $i++ ?></td>
                        <td class="text-center">
                            <div class="item-thumb-box">
                                <img src="<?= esc($imgSrc) ?>" alt="<?= esc($item['product_name']) ?>" onerror="this.src='<?= $fallbackImg ?>'">
                            </div>
                        </td>
                        <td class="item-name-cell">
                            <strong><?= esc($item['product_name']) ?></strong>
                            <?php if(!empty($item['variant_name'])): ?>
                                <?php
                                    $vNameDisplay = trim($item['variant_name']);
                                    if (strpos($vNameDisplay, ',') !== false) {
                                        $parts = array_filter(array_map('trim', explode(',', $vNameDisplay)));
                                        $vNameDisplay = reset($parts);
                                    }
                                ?>
                                <span class="item-variant-tag">Variant: <?= esc($vNameDisplay) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">₹<?= number_format($rate, 2) ?></td>
                        <td class="text-center"><strong><?= $qty ?></strong></td>
                        <td class="text-right">₹<?= number_format($itemTotal, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">No items found for this order.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Summary & Totals -->
        <div class="summary-grid">
            <div class="payment-info-box">
                <h4>Payment & Delivery Remarks</h4>
                <p>
                    <strong>Payment Method:</strong> <?= esc($order['payment_method']) ?><br>
                    <?php if(!empty($order['razorpay_payment_id'])): ?>
                        <strong>Razorpay Txn ID:</strong> <?= esc($order['razorpay_payment_id']) ?><br>
                    <?php endif; ?>
                    <strong>Delivery Service:</strong> Express Postal / Courier Delivery<br>
                    <strong>Origin:</strong> Direct from Salem Elampillai Master Weavers.
                </p>
            </div>

            <div>
                <table class="totals-table">
                    <tr>
                        <td class="label-col">Subtotal</td>
                        <td class="val-col">₹<?= number_format((float)($order['subtotal'] ?? $order['total_amount']), 2) ?></td>
                    </tr>

                    <?php if((float)($order['discount_amount'] ?? 0) > 0): ?>
                    <tr>
                        <td class="label-col" style="color: #16a34a;">Promotional Discount</td>
                        <td class="val-col" style="color: #16a34a;">- ₹<?= number_format((float)$order['discount_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>

                    <tr>
                        <td class="label-col">Shipping & Handling</td>
                        <td class="val-col">
                            <?= (float)($order['shipping_amount'] ?? 0) > 0 ? ('₹' . number_format((float)$order['shipping_amount'], 2)) : '<span style="color: #16a34a;">FREE</span>' ?>
                        </td>
                    </tr>

                    <?php if((float)($order['tip_amount'] ?? 0) > 0): ?>
                    <tr>
                        <td class="label-col">Weaver Support Tip</td>
                        <td class="val-col">₹<?= number_format((float)$order['tip_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if((float)($order['tax_amount'] ?? 0) > 0): ?>
                    <tr>
                        <td class="label-col">GST / Tax</td>
                        <td class="val-col">₹<?= number_format((float)$order['tax_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>

                    <tr class="grand-total-row">
                        <td class="label-col" style="color: var(--brand-primary);">Grand Total</td>
                        <td class="val-col" style="color: var(--brand-primary); font-size: 18px;">₹<?= number_format((float)$order['total_amount'], 2) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer & Signature -->
        <div class="invoice-footer">
            <div class="footer-terms">
                <strong>Terms & Conditions:</strong><br>
                1. Authentic Elampillai Handlooms inspected and certified before shipment.<br>
                2. Goods once sold can be exchanged within 7 days in original condition with tags.<br>
                3. This is a computer-generated tax receipt and invoice.
            </div>

            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Authorized Signatory</div>
                <div style="font-size: 10px; color: var(--text-muted);"><?= esc($settings['business_name'] ?? 'Sri Sakthi Selvi Tex') ?></div>
            </div>
        </div>

    </div>

</body>
</html>
