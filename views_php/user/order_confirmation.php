<?= view('user/header') ?>

<div style="background-color: #f8fafc; min-height: 85vh; padding: 50px 20px 80px 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <div style="max-width: 860px; margin: 0 auto;">
        
        <?php
            $isPaid = stripos($order['payment_status'], 'Paid') !== false;
            $isCod = stripos($order['payment_method'], 'Cash on Delivery') !== false;
            $orderDate = !empty($order['created_at']) ? date('d M Y, h:i A', strtotime($order['created_at'])) : date('d M Y, h:i A');
        ?>

        <!-- Status Card -->
        <div style="background: #ffffff; border: 1.5px solid <?= $isPaid || $isCod ? '#e2e8f0' : '#fecaca' ?>; border-radius: 16px; padding: 36px 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); margin-bottom: 28px; text-align: center;">
            <?php if($isPaid): ?>
                <div style="width: 70px; height: 70px; background: #dcfce7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <i class="fa-solid fa-check" style="font-size: 34px; color: #16a34a;"></i>
                </div>
                <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">
                    Thank You! Your Order Has Been Placed Successfully.
                </h1>
                <p style="font-size: 15px; color: #64748b; margin: 0 0 20px 0;">
                    We have received your payment and our weavers in Elampillai are preparing your parcel for dispatch.
                </p>
            <?php elseif($isCod): ?>
                <div style="width: 70px; height: 70px; background: #fef3c7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <i class="fa-solid fa-box-open" style="font-size: 32px; color: #d97706;"></i>
                </div>
                <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">
                    Thank You! Your Cash on Delivery Order is Confirmed.
                </h1>
                <p style="font-size: 15px; color: #64748b; margin: 0 0 20px 0;">
                    Please keep exact cash ready upon delivery. Our team will verify your contact number before shipping.
                </p>
            <?php else: ?>
                <div style="width: 70px; height: 70px; background: #fee2e2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <i class="fa-solid fa-clock-rotate-left" style="font-size: 32px; color: #dc2626;"></i>
                </div>
                <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">
                    Order Status: <?= esc($order['payment_status']) ?>
                </h1>
                <p style="font-size: 15px; color: #64748b; margin: 0 0 20px 0;">
                    Your transaction status is currently pending verification. If money was debited, it will update shortly.
                </p>
            <?php endif; ?>

            <!-- Key Summary Pill Info -->
            <div style="display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; background: #f8fafc; border-radius: 12px; padding: 18px 24px; text-align: left; margin-top: 10px;">
                <div>
                    <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Order Number</div>
                    <div style="font-size: 16px; font-weight: 800; color: #7a1530; margin-top: 2px;">#<?= esc($order['order_number']) ?></div>
                </div>
                <div>
                    <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Order Date</div>
                    <div style="font-size: 14px; font-weight: 700; color: #1e293b; margin-top: 2px;"><?= $orderDate ?></div>
                </div>
                <div>
                    <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Payment Status</div>
                    <div style="font-size: 14px; font-weight: 800; color: <?= $isPaid ? '#16a34a' : ($isCod ? '#d97706' : '#dc2626') ?>; margin-top: 2px;">
                        <?= esc($order['payment_status']) ?>
                    </div>
                </div>
                <?php if(!empty($order['razorpay_payment_id'])): ?>
                <div>
                    <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Payment Reference</div>
                    <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-top: 2px; font-family: monospace;">
                        <?= esc($order['razorpay_payment_id']) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px;">
            
            <!-- Left: Purchased Items -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <h3 style="font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-bag-shopping" style="color: #7a1530;"></i> Purchased Items
                </h3>

                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 20px;">
                    <?php if(!empty($items)): foreach($items as $it): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px dashed #e2e8f0;">
                            <div>
                                <div style="font-size: 14px; font-weight: 700; color: #0f172a;"><?= esc($it['product_name']) ?></div>
                                <?php 
                                    $cleanVariant = !empty($it['variant_name']) ? trim(preg_replace('/(?:\s*\/\s*)?Free\s*Size/i', '', $it['variant_name'])) : '';
                                    if(!empty($cleanVariant)): 
                                ?>
                                    <div style="font-size: 12px; color: #64748b;">Color: <?= esc($cleanVariant) ?></div>
                                <?php endif; ?>
                                <div style="font-size: 12px; color: #64748b;">Qty: <?= (int)$it['quantity'] ?> × <span class="order-unit-price" data-base-price="<?= $it['price'] ?>">₹<?= number_format($it['price'], 2) ?></span></div>
                            </div>
                            <div class="order-item-total" data-base-price="<?= $it['price'] * $it['quantity'] ?>" style="font-size: 14px; font-weight: 800; color: #0f172a;">
                                ₹<?= number_format($it['price'] * $it['quantity'], 2) ?>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>

                <!-- Price Totals -->
                <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: #475569;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Subtotal</span>
                        <span class="order-summary-subtotal" data-base-price="<?= $order['subtotal'] ?>" style="font-weight: 600;">₹<?= number_format($order['subtotal'], 2) ?></span>
                    </div>
                    <?php if($order['discount_amount'] > 0): ?>
                    <div style="display: flex; justify-content: space-between; color: #16a34a;">
                        <span>Discount Saved</span>
                        <span class="order-summary-discount" data-base-price="<?= $order['discount_amount'] ?>" style="font-weight: 600;">-₹<?= number_format($order['discount_amount'], 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Shipping Fee</span>
                        <span class="order-summary-shipping" data-base-price="<?= $order['shipping_amount'] ?>" style="font-weight: 600;"><?= $order['shipping_amount'] == 0 ? '<span style="color: #16a34a;">FREE</span>' : '₹' . number_format($order['shipping_amount'], 2) ?></span>
                    </div>
                    <?php if($order['tip_amount'] > 0): ?>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Artisan Tip</span>
                        <span class="order-summary-tip" data-base-price="<?= $order['tip_amount'] ?>" style="font-weight: 600;">₹<?= number_format($order['tip_amount'], 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 2px solid #0f172a; font-size: 18px; font-weight: 900; color: #0f172a; margin-top: 4px;">
                        <span id="order-final-total-label">Final Total</span>
                        <span class="order-summary-final-total" data-base-price="<?= $order['total_amount'] ?>" style="color: #7a1530;">₹<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Right: Delivery & Customer Info -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-location-dot" style="color: #7a1530;"></i> Delivery Address
                    </h3>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a;"><?= esc($order['customer_name']) ?></div>
                    <div style="font-size: 13px; color: #475569; margin-top: 4px; line-height: 1.5;">
                        <?= esc($order['shipping_address']) ?><br>
                        <?= esc($order['shipping_city']) ?>, <?= esc($order['shipping_state']) ?> - <?= esc($order['shipping_pincode']) ?><br>
                        <?= esc($order['shipping_country']) ?>
                    </div>
                    <div style="font-size: 13px; color: #475569; margin-top: 8px;">
                        <i class="fa-solid fa-phone" style="font-size: 11px; margin-right: 4px;"></i> <?= esc($order['customer_phone']) ?><br>
                        <i class="fa-solid fa-envelope" style="font-size: 11px; margin-right: 4px;"></i> <?= esc($order['customer_email']) ?>
                    </div>
                </div>

                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-truck" style="color: #7a1530;"></i> Shipping &amp; Payment
                    </h3>
                    <div style="font-size: 13px; color: #475569; line-height: 1.6;">
                        <strong>Method:</strong> Standard Direct Express Delivery<br>
                        <strong>Payment:</strong> <?= esc($order['payment_method']) ?><br>
                        <strong>Estimated Delivery:</strong> 2 - 5 Business Days
                    </div>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 36px; text-align: center; display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="<?= base_url('collections') ?>" style="display: inline-flex; align-items: center; gap: 8px; background: #7a1530; color: #ffffff; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 10px rgba(122,21,48,0.25);">
                <i class="fa-solid fa-arrow-left"></i> Continue Shopping
            </a>
            <a href="<?= base_url('order/invoice/' . (int)$order['id']) ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #ffffff; color: #7a1530; border: 1.5px solid #d4af37; padding: 12px 22px; border-radius: 8px; font-size: 14px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 10px rgba(212,175,55,0.15);">
                <i class="fa-solid fa-print" style="color: #d4af37;"></i> Print Bill A4
            </a>
            <?php 
                $orderWa = preg_replace('/[^0-9]/', '', $settings['whatsapp_no'] ?? '919789296751');
                if (strlen($orderWa) === 10) { $orderWa = '91' . $orderWa; }
                elseif (strlen($orderWa) === 11 && strpos($orderWa, '0') === 0) { $orderWa = '91' . substr($orderWa, 1); }
            ?>
            <a href="https://wa.me/<?= esc($orderWa) ?>?text=<?= urlencode('Hello Selvi Tex, I have placed Order #' . $order['order_number'] . '. Please share dispatch details.') ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #25D366; color: #ffffff; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 10px rgba(37,211,102,0.25);">
                <i class="fa-brands fa-whatsapp" style="font-size: 18px;"></i> Track on WhatsApp
            </a>
        </div>

    </div>
</div>

<?= view('user/footer') ?>
