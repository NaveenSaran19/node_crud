<?= view('user/header') ?>

<style>
.cart-page-wrap {
    background-color: #f8fafc;
    min-height: 80vh;
    padding: 40px 20px 80px 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.cart-inner-container {
    max-width: 1180px;
    margin: 0 auto;
}

.cart-header-title {
    font-size: 26px;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.cart-free-shipping-bar {
    background: #fdfbf7;
    border: 1.5px solid #ebdcc1;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.cart-layout-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 30px;
    align-items: start;
}

/* Individual Cart Item Card */
.cart-item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 20px;
    align-items: center;
    transition: box-shadow 0.2s;
}

.cart-item-card:hover {
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
}

.cart-item-thumb {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
}

.cart-item-info h3 {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 6px 0;
    line-height: 1.3;
}

.cart-item-variant {
    font-size: 13px;
    color: #7a1530;
    font-weight: 600;
    margin-bottom: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.cart-item-prices {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 12px;
}

.cart-unit-price {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}

.cart-unit-orig {
    font-size: 13px;
    color: #94a3b8;
    text-decoration: line-through;
}

/* Individual Card Controls & Actions */
.cart-card-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
}

.cart-item-subtotal-val {
    font-size: 18px;
    font-weight: 800;
    color: #7a1530;
}

.cart-btn-individual-checkout {
    background: #c59b27;
    background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%);
    color: #ffffff !important;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(184, 134, 11, 0.25);
    transition: all 0.2s;
}

.cart-btn-individual-checkout:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(184, 134, 11, 0.35);
}

.cart-remove-item-btn {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s;
}

.cart-remove-item-btn:hover {
    color: #ef4444;
}

/* Sidebar Order Summary */
.cart-summary-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    position: sticky;
    top: 20px;
}

.cart-summary-heading {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 18px 0;
    padding-bottom: 12px;
    border-bottom: 1.5px solid #f1f5f9;
}

.cart-checkout-all-btn {
    width: 100%;
    height: 48px;
    background: #7a1530;
    color: #ffffff !important;
    text-decoration: none;
    font-size: 15px;
    font-weight: 800;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    box-shadow: 0 4px 12px rgba(122,21,48,0.25);
    transition: all 0.25s;
}

.cart-checkout-all-btn:hover {
    background: #5d0f23;
    transform: translateY(-2px);
}

@media (max-width: 850px) {
    .cart-layout-grid {
        grid-template-columns: 1fr;
    }
    .cart-item-card {
        grid-template-columns: 80px 1fr;
        gap: 15px;
    }
    .cart-card-actions {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed #e2e8f0;
        padding-top: 12px;
    }
}
</style>

<div class="cart-page-wrap">
    <div class="cart-inner-container">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 class="cart-header-title">
                    <i class="fa-solid fa-cart-shopping" style="color: #7a1530;"></i>
                    Your Shopping Cart
                </h1>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Review your selected sarees or checkout each item individually</p>
            </div>
            <a href="<?= base_url('collections') ?>" style="font-size: 14px; font-weight: 700; color: #7a1530; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-arrow-left"></i> Continue Shopping
            </a>
        </div>

        <?php if(empty($items)): ?>
            <!-- Empty State -->
            <div style="text-align: center; background: #ffffff; padding: 70px 20px; border-radius: 16px; border: 1.5px dashed #cbd5e1; max-width: 550px; margin: 40px auto;">
                <i class="fa-solid fa-cart-arrow-down" style="font-size: 52px; color: #94a3b8; margin-bottom: 16px;"></i>
                <h2 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 8px 0;">Your Cart is Empty</h2>
                <p style="color: #64748b; font-size: 14px; margin: 0 0 24px 0;">You haven't added any sarees to your shopping cart yet.</p>
                <a href="<?= base_url('collections') ?>" class="cart-btn-individual-checkout" style="padding: 12px 28px; font-size: 14px;">
                    <i class="fa-solid fa-bag-shopping"></i> Explore Saree Collections
                </a>
            </div>
        <?php else: ?>

            <!-- Free Shipping Progress -->
            <div class="cart-free-shipping-bar">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-truck-fast" style="font-size: 20px; color: <?= $is_free_shipping ? '#16a34a' : '#c59b27' ?>;"></i>
                    <div>
                        <strong style="color: #0f172a; font-size: 14px;">
                            <?= $is_free_shipping ? 'You have unlocked FREE Shipping!' : 'Add ₹' . number_format($amount_needed_for_free_shipping, 2) . ' more for FREE Shipping!' ?>
                        </strong>
                        <div style="font-size: 12px; color: #64748b;">Orders above ₹<?= number_format($free_shipping_threshold) ?> qualify for free direct express shipping.</div>
                    </div>
                </div>
                <?php if($is_free_shipping): ?>
                    <span style="background: #dcfce7; color: #15803d; font-weight: 800; font-size: 12px; padding: 4px 12px; border-radius: 12px;">FREE SHIPPING APPLIED</span>
                <?php endif; ?>
            </div>

            <div class="cart-layout-grid">
                
                <!-- Left: Individual Cart Item Cards -->
                <div class="cart-items-column">
                    <?php foreach($items as $it): 
                        $itemTotal = (float)$it['price'] * (int)$it['quantity'];
                    ?>
                        <div class="cart-item-card" id="cart-card-<?= esc($it['key']) ?>">
                            <a href="<?= base_url('product/' . esc($it['product_slug'])) ?>">
                                <img src="<?= esc($it['image_url']) ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="<?= esc($it['product_name']) ?>" class="cart-item-thumb">
                            </a>

                            <div class="cart-item-info">
                                <a href="<?= base_url('product/' . esc($it['product_slug'])) ?>" style="text-decoration: none; color: inherit;">
                                    <h3><?= esc($it['product_name']) ?></h3>
                                </a>

                                <?php 
                                    $cleanVariant = !empty($it['variant_name']) ? trim(preg_replace('/(?:\s*\/\s*)?Free\s*Size/i', '', $it['variant_name'])) : '';
                                    if(!empty($cleanVariant)): 
                                ?>
                                    <div class="cart-item-variant">
                                        <i class="fa-solid fa-palette"></i> Color: <?= esc($cleanVariant) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="cart-item-prices">
                                    <span class="cart-unit-price" data-base-price="<?= $it['price'] ?>">₹<?= number_format($it['price'], 2) ?></span>
                                    <?php if(!empty($it['original_price']) && (float)$it['original_price'] > (float)$it['price']): ?>
                                        <span class="cart-unit-orig" data-base-price="<?= $it['original_price'] ?>">₹<?= number_format($it['original_price'], 2) ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Quantity Selector -->
                                <div class="quantity-picker" style="height: 38px;">
                                    <button type="button" class="qty-btn" onclick="updateCartItemQty('<?= esc($it['key']) ?>', <?= max(1, (int)$it['quantity'] - 1) ?>)">
                                        <i class="fa-solid fa-minus" style="font-size: 11px;"></i>
                                    </button>
                                    <input type="text" class="qty-input" value="<?= (int)$it['quantity'] ?>" readonly style="width: 38px; font-size: 13px;">
                                    <button type="button" class="qty-btn" onclick="updateCartItemQty('<?= esc($it['key']) ?>', <?= (int)$it['quantity'] + 1 ?>)">
                                        <i class="fa-solid fa-plus" style="font-size: 11px;"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Actions for Individual Item -->
                            <div class="cart-card-actions">
                                <div class="cart-item-subtotal-val" data-base-price="<?= $itemTotal ?>">
                                    ₹<?= number_format($itemTotal, 2) ?>
                                </div>

                                <!-- Individual Checkout Button -->
                                <a href="<?= base_url('checkout?item_key=' . urlencode($it['key'])) ?>" class="cart-btn-individual-checkout" title="Checkout only this item">
                                    <i class="fa-solid fa-bolt"></i> Checkout Individually
                                </a>

                                <button type="button" class="cart-remove-item-btn" onclick="removeCartItem('<?= esc($it['key']) ?>')">
                                    <i class="fa-regular fa-trash-can"></i> Remove
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Right: Summary Sidebar -->
                <div class="cart-summary-sidebar">
                    <div class="cart-summary-card">
                        <h2 class="cart-summary-heading">Cart Summary</h2>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #475569;">
                            <span>Subtotal (<?= $cart_count ?> items)</span>
                            <span class="cart-summary-subtotal" data-base-price="<?= $subtotal ?>" style="font-weight: 700; color: #0f172a;">₹<?= number_format($subtotal, 2) ?></span>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #475569;">
                            <span>Shipping</span>
                            <span class="cart-summary-shipping" data-base-price="<?= $shipping ?>" style="font-weight: 700; color: <?= $is_free_shipping ? '#16a34a' : '#0f172a' ?>;">
                                <?= $is_free_shipping ? 'FREE' : '₹' . number_format($shipping, 2) ?>
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; padding-top: 16px; border-top: 2px solid #0f172a; margin-top: 14px; font-size: 18px; font-weight: 900; color: #0f172a;">
                            <span class="cart-summary-total-label">Total</span>
                            <span class="cart-summary-total" data-base-price="<?= $total ?>" style="color: #7a1530; font-size: 20px;">₹<?= number_format($total, 2) ?></span>
                        </div>

                        <!-- Checkout Full Cart -->
                        <a href="<?= base_url('checkout') ?>" class="cart-checkout-all-btn">
                            <i class="fa-solid fa-lock"></i> Checkout All Items
                        </a>

                        <div style="margin-top: 18px; text-align: center; font-size: 12px; color: #64748b; line-height: 1.4;">
                            <i class="fa-solid fa-shield-halved" style="color: #16a34a; margin-right: 4px;"></i>
                            Safe &amp; Secure Checkout • Razorpay Verified
                        </div>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </div>
</div>

<script>
function updateCartItemQty(key, qty) {
    var baseUrl = '<?= base_url() ?>'.replace(/\/$/, '');
    fetch(baseUrl + '/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            key: key,
            quantity: qty
        })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'Could not update item quantity.');
        }
    })
    .catch(function() {
        alert('Failed to update quantity. Please try again.');
    });
}

function removeCartItem(key) {
    if (!confirm('Remove this saree from your shopping cart?')) return;
    var baseUrl = '<?= base_url() ?>'.replace(/\/$/, '');
    fetch(baseUrl + '/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({ key: key })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'Could not remove item.');
        }
    })
    .catch(function() {
        alert('Failed to remove item. Please try again.');
    });
}
</script>

<?= view('user/footer') ?>
