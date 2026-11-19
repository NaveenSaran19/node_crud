<!-- ==========================================================================
     Slide-out Interactive Cart Drawer
     Displays instantly whenever an item is added to the cart,
     or when the customer clicks the header cart icon.
     ========================================================================== -->
<div id="cart-drawer-backdrop" class="cart-drawer-backdrop" aria-hidden="true"></div>

<aside id="cart-drawer" class="cart-drawer-container" aria-label="Shopping Cart Drawer" role="dialog" aria-modal="true" aria-hidden="true">
    <!-- Drawer Header -->
    <div class="cart-drawer-header">
        <div class="cart-drawer-title-wrap">
            <i class="fa-solid fa-bag-shopping" style="color: #e11d48; font-size: 19px;"></i>
            <h2 class="cart-drawer-title">Your Cart</h2>
            <span class="cart-drawer-count-badge" id="cart-drawer-count-badge">0</span>
        </div>
        <button type="button" class="cart-drawer-close-btn" id="cart-drawer-close-btn" aria-label="Close Cart Drawer">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Free Shipping Goal Progress Bar -->
    <div class="cart-drawer-shipping-bar" id="cart-drawer-shipping-bar">
        <div class="cart-drawer-shipping-label" id="cart-drawer-shipping-label">
            <i class="fa-solid fa-truck-fast"></i>
            <span>Add ₹1,000 more for FREE Express Shipping!</span>
        </div>
        <div class="cart-drawer-progress-track">
            <div class="cart-drawer-progress-fill" id="cart-drawer-progress-fill" style="width: 0%;"></div>
        </div>
    </div>

    <!-- Drawer Body: Items or Empty State -->
    <div class="cart-drawer-body" id="cart-drawer-body">
        <!-- Rendered dynamically via JavaScript from /cart/data -->
        <div class="cart-drawer-loading" id="cart-drawer-loading" style="display: none;">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <span>Updating your cart...</span>
        </div>

        <div class="cart-drawer-items-list" id="cart-drawer-items-list"></div>

        <div class="cart-drawer-empty" id="cart-drawer-empty" style="display: none;">
            <div class="cart-drawer-empty-icon">
                <i class="fa-solid fa-cart-arrow-down"></i>
            </div>
            <h3>Your Cart is Empty</h3>
            <p>Explore our exclusive collection of authentic sarees woven in Elampillai.</p>
            <a href="<?= base_url('collections') ?>" class="cart-drawer-shop-btn" onclick="closeCartDrawer()">
                Start Shopping
            </a>
        </div>
    </div>

    <!-- Drawer Footer: Subtotal & Checkout Action Buttons -->
    <div class="cart-drawer-footer" id="cart-drawer-footer">
        <div class="cart-drawer-subtotal-row">
            <span class="subtotal-label">Subtotal</span>
            <span class="subtotal-amount" id="cart-drawer-subtotal-val">₹0.00</span>
        </div>
        <div class="cart-drawer-tax-note">
            Taxes included. Direct dispatch from Elampillai handlooms.
        </div>

        <!-- Primary Action: Direct to Checkout Page -->
        <a href="<?= base_url('checkout') ?>" class="cart-drawer-checkout-btn" id="cart-drawer-checkout-btn">
            <i class="fa-solid fa-lock"></i>
            <span>Proceed to Checkout</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>

        <!-- Secondary Action: View Full Cart Page -->
        <div class="cart-drawer-secondary-actions">
            <a href="<?= base_url('cart') ?>" class="cart-drawer-view-cart-link">
                <i class="fa-solid fa-cart-shopping"></i> View Full Cart
            </a>
            <button type="button" class="cart-drawer-continue-link" onclick="closeCartDrawer()">
                Continue Shopping
            </button>
        </div>
    </div>
</aside>

<style>
/* ==========================================================================
   Cart Drawer Component Styling
   ========================================================================== */
.cart-drawer-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    z-index: 99998;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.cart-drawer-backdrop.active {
    opacity: 1;
    visibility: visible;
}

.cart-drawer-container {
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    max-width: 440px;
    height: 100%;
    height: 100dvh;
    background: #ffffff;
    z-index: 99999;
    box-shadow: -8px 0 30px rgba(0, 0, 0, 0.18);
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    visibility: hidden;
    pointer-events: none;
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.35s ease;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
}

.cart-drawer-container.active {
    transform: translateX(0);
    visibility: visible;
    pointer-events: auto;
}

/* Header */
.cart-drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1.5px solid #f1f5f9;
    background: #ffffff;
}

.cart-drawer-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-drawer-title {
    font-size: 18px;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
    letter-spacing: -0.2px;
}

.cart-drawer-count-badge {
    background: #e11d48;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
}

.cart-drawer-close-btn {
    background: none;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #64748b;
    font-size: 17px;
    transition: all 0.2s;
}

.cart-drawer-close-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
    transform: rotate(90deg);
}

/* Free Shipping Goal */
.cart-drawer-shipping-bar {
    padding: 12px 22px;
    background: #fdfbf7;
    border-bottom: 1px solid #ebdcc1;
}

.cart-drawer-shipping-label {
    font-size: 12px;
    font-weight: 700;
    color: #e11d48;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.cart-drawer-shipping-label.unlocked {
    color: #16a34a;
}

.cart-drawer-progress-track {
    width: 100%;
    height: 6px;
    background: #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}

.cart-drawer-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #d4af37 0%, #e11d48 100%);
    border-radius: 6px;
    transition: width 0.4s ease;
}

.cart-drawer-progress-fill.full {
    background: #22c55e;
}

/* Body & Items List */
.cart-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px 20px;
    position: relative;
    -webkit-overflow-scrolling: touch;
}

.cart-drawer-body::-webkit-scrollbar {
    width: 5px;
}
.cart-drawer-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.cart-drawer-item-card {
    display: grid;
    grid-template-columns: 74px 1fr;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #f1f5f9;
    align-items: start;
    position: relative;
    animation: drawerItemFade 0.25s ease;
}

@keyframes drawerItemFade {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.cart-drawer-item-card:last-child {
    border-bottom: none;
}

.cart-drawer-item-thumb {
    width: 74px;
    height: 74px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
}

.cart-drawer-item-content {
    min-width: 0;
}

.cart-drawer-item-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px 0;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cart-drawer-item-title a {
    text-decoration: none;
    color: inherit;
}
.cart-drawer-item-title a:hover {
    color: #e11d48;
}

.cart-drawer-item-variant {
    font-size: 12px;
    color: #e11d48;
    font-weight: 600;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.cart-drawer-item-price-row {
    display: flex;
    align-items: baseline;
    gap: 6px;
    margin-bottom: 8px;
}

.cart-drawer-item-sale {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
}

.cart-drawer-item-orig {
    font-size: 11px;
    color: #94a3b8;
    text-decoration: line-through;
}

.cart-drawer-item-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cart-drawer-qty-picker {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid #e2e8f0;
    border-radius: 6px;
    background: #ffffff;
    height: 30px;
}

.cart-drawer-qty-btn {
    background: none;
    border: none;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #475569;
    font-size: 10px;
    transition: background 0.15s;
}

.cart-drawer-qty-btn:hover {
    background: #f1f5f9;
    color: #e11d48;
}

.cart-drawer-qty-num {
    width: 28px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #1e293b;
}

.cart-drawer-item-remove-btn {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 6px;
    border-radius: 4px;
    transition: all 0.2s;
}

.cart-drawer-item-remove-btn:hover {
    color: #ef4444;
    background: #fee2e2;
}

/* Empty State */
.cart-drawer-empty {
    text-align: center;
    padding: 60px 20px;
}

.cart-drawer-empty-icon {
    width: 68px;
    height: 68px;
    background: #fdf2f4;
    color: #e11d48;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin: 0 auto 18px auto;
}

.cart-drawer-empty h3 {
    font-size: 18px;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.cart-drawer-empty p {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
    margin: 0 0 22px 0;
}

.cart-drawer-shop-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 26px;
    background: #e11d48;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: background 0.2s;
}

.cart-drawer-shop-btn:hover {
    background: #5d0f23;
}

/* Footer & Checkout Button */
.cart-drawer-footer {
    padding: 18px 22px 22px 22px;
    border-top: 1.5px solid #f1f5f9;
    background: #ffffff;
    box-shadow: 0 -4px 14px rgba(0, 0, 0, 0.04);
}

.cart-drawer-subtotal-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 4px;
}

.cart-drawer-subtotal-row .subtotal-label {
    font-size: 15px;
    font-weight: 700;
    color: #475569;
}

.cart-drawer-subtotal-row .subtotal-amount {
    font-size: 20px;
    font-weight: 900;
    color: #e11d48;
}

.cart-drawer-tax-note {
    font-size: 11px;
    color: #94a3b8;
    margin-bottom: 14px;
}

.cart-drawer-checkout-btn {
    width: 100%;
    height: 48px;
    background: #e11d48;
    color: #ffffff !important;
    text-decoration: none;
    font-size: 15px;
    font-weight: 800;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    box-shadow: 0 4px 14px rgba(225, 29, 72, 0.28);
    transition: all 0.2s ease;
    cursor: pointer;
    border: none;
}

.cart-drawer-checkout-btn:hover {
    background: #5d0f23;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(225, 29, 72, 0.35);
}

.cart-drawer-secondary-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 12px;
    font-size: 13px;
}

.cart-drawer-view-cart-link {
    color: #e11d48;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s;
}

.cart-drawer-view-cart-link:hover {
    text-decoration: underline;
}

.cart-drawer-continue-link {
    background: none;
    border: none;
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    transition: color 0.2s;
}

.cart-drawer-continue-link:hover {
    color: #0f172a;
}

@media (max-width: 480px) {
    .cart-drawer-container {
        max-width: 100%;
    }
}
</style>
