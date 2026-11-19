<?= view('user/header') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/checkout.css') ?>">

<?php
    $indianStates = [
        'Tamil Nadu', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 
        'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 
        'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Telangana', 'Tripura', 'Uttar Pradesh', 
        'Uttarakhand', 'West Bengal', 'Andaman and Nicobar Islands', 'Chandigarh', 
        'Dadra and Nagar Haveli and Daman and Diu', 'Delhi', 'Jammu and Kashmir', 
        'Ladakh', 'Lakshadweep', 'Puducherry'
    ];

    $hasItems = !empty($items);
    $subtotalVal = (float)($subtotal ?? 0);
    $shippingVal = (float)($shipping_fee ?? 0);
    $isFreeShipping = !empty($is_free_shipping);
    $freeThresholdVal = (float)($free_shipping_threshold ?? 1000);
    $rzpKey = esc($razorpay_key_id ?? '');
    $bizName = esc($business_name ?? 'Selvi Tex');
    $loggedCustomer = $customer_user ?? null;
    $prefillEmail = $loggedCustomer['email'] ?? session()->get('customer_email') ?? '';
    $prefillName = $loggedCustomer['name'] ?? session()->get('customer_name') ?? '';
    $prefillPhone = $loggedCustomer['phone'] ?? ($last_order['customer_phone'] ?? '');
    $prefillAddress1 = $last_order['shipping_address'] ?? '';
    $prefillCity = $last_order['shipping_city'] ?? '';
    $prefillState = !empty($last_order['shipping_state']) ? $last_order['shipping_state'] : 'Tamil Nadu';
    $prefillPincode = $last_order['shipping_pincode'] ?? '';
    $prefillCountry = $last_order['shipping_country'] ?? 'India';
    $isCustomerLoggedIn = session()->get('customer_logged_in') && !empty($prefillEmail);
?>

<div class="checkout-page-container">
    <div class="checkout-wrapper">
        
        <!-- Checkout Top Header -->
        <div class="checkout-header">
            <div>
                <h1 class="checkout-brand-title">
                    <i class="fa-solid fa-bag-shopping"></i> Checkout
                </h1>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;">Complete your purchase with fast delivery from Elampillai</div>
            </div>
            <div class="checkout-security-badge">
                <i class="fa-solid fa-shield-halved"></i> 100% Safe &amp; Secure Checkout
            </div>
        </div>

        <?php if(!$hasItems): ?>
            <!-- Empty Cart Warning -->
            <div style="text-align: center; background: #ffffff; padding: 60px 20px; border-radius: 12px; border: 1.5px dashed #cbd5e1; max-width: 600px; margin: 40px auto;">
                <i class="fa-solid fa-cart-arrow-down" style="font-size: 48px; color: #94a3b8; margin-bottom: 16px;"></i>
                <h2 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 8px 0;">Your Shopping Cart is Empty</h2>
                <p style="color: #64748b; font-size: 14px; margin: 0 0 24px 0;">Please add items or select a saree before proceeding to checkout.</p>
                <a href="<?= base_url('collections') ?>" class="buy-now-btn" style="display: inline-flex; padding: 0 30px; height: 44px; text-decoration: none;">
                    Explore Saree Collections
                </a>
            </div>
        <?php else: ?>

        <form id="checkout-form" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" id="checkout-subtotal-val" value="<?= $subtotalVal ?>">
            <input type="hidden" id="checkout-shipping-val" value="<?= $shippingVal ?>">
            <input type="hidden" id="checkout-free-threshold-val" value="<?= $freeThresholdVal ?>">
            <input type="hidden" id="checkout-discount-val" value="<?= $discount_amount ?? 0 ?>">
            <input type="hidden" id="selected-tip-rate" name="tip_option" value="none">

            <div class="checkout-layout">
                
                <!-- Left Column: Sections A through F -->
                <div class="checkout-form-column">
                    
                    <!-- SECTION A: Contact -->
                    <div class="checkout-section-card" id="section-contact">
                        <div class="checkout-section-header">
                            <h2 class="checkout-section-title">
                                <i class="fa-solid fa-user-check"></i> Contact Details
                            </h2>
                            <?php if($isCustomerLoggedIn): ?>
                                <div class="checkout-logged-user" style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                                    <span style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #047857; font-weight: 600; padding: 4px 12px; border-radius: 20px; border: 1px solid #a7f3d0;">
                                        <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Signed in as <?= esc($prefillName ?: $prefillEmail) ?>
                                    </span>
                                    <a href="<?= base_url('customer/logout') ?>" style="color: #64748b; font-size: 12px; text-decoration: underline;" title="Switch or log out">Switch account</a>
                                </div>
                            <?php else: ?>
                                <a href="<?= base_url('login/google?return=checkout') ?>" class="checkout-signin-link">
                                    Already have an account? Sign in
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="checkout-field-group">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <label class="checkout-label" for="checkout-email">
                                    Email Address <span class="required">*</span>
                                </label>
                                <?php if($isCustomerLoggedIn): ?>
                                    <span style="font-size: 11.5px; color: #16a34a; font-weight: 600;">
                                        <i class="fa-solid fa-check-double"></i> Verified Account Email
                                    </span>
                                <?php endif; ?>
                            </div>
                            <input type="email" id="checkout-email" name="email" class="checkout-input" placeholder="you@example.com" value="<?= esc($prefillEmail) ?>" required autocomplete="email">
                            <div style="font-size: 12px; color: #475569; margin-top: 6px; display: flex; align-items: center; gap: 6px; background: #f8fafc; padding: 7px 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                <i class="fa-solid fa-paper-plane" style="color: #0284c7; font-size: 13px;"></i>
                                <span>Order confirmation, digital tax invoice &amp; dispatch tracking will be sent to <strong>this email address</strong>.</span>
                            </div>
                            <div class="field-error-msg" id="err-email" style="display: none; color: #ef4444; font-size: 12px; margin-top: 4px;"></div>
                        </div>

                        <label class="checkout-checkbox-label">
                            <input type="checkbox" name="email_consent" value="1" checked>
                            <span>Email me with exclusive discounts, new arrival announcements, and festive offers</span>
                        </label>

                        <div class="checkout-field-group" style="margin-top: 16px;">
                            <label class="checkout-label" for="checkout-contact-phone">
                                Mobile Number for Order Tracking <span class="required">*</span>
                            </label>
                            <input type="tel" id="checkout-contact-phone" name="phone" class="checkout-input" placeholder="10-digit mobile number (e.g. 9876543210)" value="<?= esc($prefillPhone) ?>" required maxlength="10" autocomplete="tel">
                            <div class="field-error-msg" id="err-phone" style="display: none; color: #ef4444; font-size: 12px; margin-top: 4px;"></div>
                        </div>

                        <label class="checkout-checkbox-label">
                            <input type="checkbox" name="phone_consent" value="1">
                            <span>Text me (SMS / WhatsApp) with real-time order dispatch and delivery updates</span>
                        </label>
                    </div>

                    <!-- SECTION B: Delivery Address -->
                    <div class="checkout-section-card" id="section-delivery">
                        <div class="checkout-section-header">
                            <h2 class="checkout-section-title">
                                <i class="fa-solid fa-location-dot"></i> Delivery Address
                            </h2>
                        </div>

                        <div class="checkout-field-group">
                            <label class="checkout-label" for="checkout-country">Country / Region <span class="required">*</span></label>
                            <select id="checkout-country" name="country" class="checkout-select" required onchange="handleCountryChange(this.value)">
                                <option value="India" data-code="in" <?= $prefillCountry === 'India' ? 'selected' : '' ?>>India (Domestic Shipping)</option>
                                <optgroup label="Popular International Destinations (Direct Handloom Delivery)">
                                    <option value="United States" data-code="us" <?= $prefillCountry === 'United States' ? 'selected' : '' ?>>United States</option>
                                    <option value="United Kingdom" data-code="gb" <?= $prefillCountry === 'United Kingdom' ? 'selected' : '' ?>>United Kingdom</option>
                                    <option value="United Arab Emirates" data-code="ae" <?= $prefillCountry === 'United Arab Emirates' ? 'selected' : '' ?>>United Arab Emirates</option>
                                    <option value="Singapore" data-code="sg" <?= $prefillCountry === 'Singapore' ? 'selected' : '' ?>>Singapore</option>
                                    <option value="Malaysia" data-code="my" <?= $prefillCountry === 'Malaysia' ? 'selected' : '' ?>>Malaysia</option>
                                    <option value="Canada" data-code="ca" <?= $prefillCountry === 'Canada' ? 'selected' : '' ?>>Canada</option>
                                    <option value="Australia" data-code="au" <?= $prefillCountry === 'Australia' ? 'selected' : '' ?>>Australia</option>
                                    <option value="Germany" data-code="de" <?= $prefillCountry === 'Germany' ? 'selected' : '' ?>>Germany</option>
                                    <option value="France" data-code="fr" <?= $prefillCountry === 'France' ? 'selected' : '' ?>>France</option>
                                    <option value="Saudi Arabia" data-code="sa" <?= $prefillCountry === 'Saudi Arabia' ? 'selected' : '' ?>>Saudi Arabia</option>
                                    <option value="Qatar" data-code="qa" <?= $prefillCountry === 'Qatar' ? 'selected' : '' ?>>Qatar</option>
                                    <option value="Kuwait" data-code="kw" <?= $prefillCountry === 'Kuwait' ? 'selected' : '' ?>>Kuwait</option>
                                    <option value="Oman" data-code="om" <?= $prefillCountry === 'Oman' ? 'selected' : '' ?>>Oman</option>
                                    <option value="Bahrain" data-code="bh" <?= $prefillCountry === 'Bahrain' ? 'selected' : '' ?>>Bahrain</option>
                                    <option value="Sri Lanka" data-code="lk" <?= $prefillCountry === 'Sri Lanka' ? 'selected' : '' ?>>Sri Lanka</option>
                                    <option value="South Africa" data-code="za" <?= $prefillCountry === 'South Africa' ? 'selected' : '' ?>>South Africa</option>
                                    <option value="Switzerland" data-code="ch" <?= $prefillCountry === 'Switzerland' ? 'selected' : '' ?>>Switzerland</option>
                                    <option value="New Zealand" data-code="nz" <?= $prefillCountry === 'New Zealand' ? 'selected' : '' ?>>New Zealand</option>
                                    <option value="Netherlands" data-code="nl" <?= $prefillCountry === 'Netherlands' ? 'selected' : '' ?>>Netherlands</option>
                                    <option value="Italy" data-code="it" <?= $prefillCountry === 'Italy' ? 'selected' : '' ?>>Italy</option>
                                    <option value="Ireland" data-code="ie" <?= $prefillCountry === 'Ireland' ? 'selected' : '' ?>>Ireland</option>
                                    <option value="Sweden" data-code="se" <?= $prefillCountry === 'Sweden' ? 'selected' : '' ?>>Sweden</option>
                                    <option value="Japan" data-code="jp" <?= $prefillCountry === 'Japan' ? 'selected' : '' ?>>Japan</option>
                                    <option value="Mauritius" data-code="mu" <?= $prefillCountry === 'Mauritius' ? 'selected' : '' ?>>Mauritius</option>
                                </optgroup>
                                <option value="Other">Other International Country</option>
                            </select>
                        </div>

                        <div class="checkout-field-group">
                            <label class="checkout-label" for="checkout-fullname">
                                Full Name <span class="required">*</span>
                            </label>
                            <input type="text" id="checkout-fullname" name="full_name" class="checkout-input" placeholder="Recipient's Full Name" value="<?= esc($prefillName) ?>" required autocomplete="name">
                            <div class="field-error-msg" id="err-fullname" style="display: none; color: #ef4444; font-size: 12px; margin-top: 4px;"></div>
                        </div>

                        <div class="checkout-field-group">
                            <label class="checkout-label" for="checkout-address1">
                                Flat, House No., Building, Street Address <span class="required">*</span>
                            </label>
                            <input type="text" id="checkout-address1" name="address1" class="checkout-input" placeholder="e.g. 45/2, Gandhi Nagar, Main Road" value="<?= esc($prefillAddress1) ?>" required autocomplete="street-address">
                            <div class="field-error-msg" id="err-address1" style="display: none; color: #ef4444; font-size: 12px; margin-top: 4px;"></div>
                        </div>

                        <div class="checkout-field-group">
                            <label class="checkout-label" for="checkout-address2">
                                Apartment, Suite, Area (Optional)
                            </label>
                            <input type="text" id="checkout-address2" name="address2" class="checkout-input" placeholder="e.g. Near Landmark, Suite / Apt">
                        </div>

                        <div class="form-grid-3">
                            <div class="checkout-field-group">
                                <label class="checkout-label" for="checkout-city">
                                    City / Town <span class="required">*</span>
                                </label>
                                <input type="text" id="checkout-city" name="city" class="checkout-input" placeholder="City" value="<?= esc($prefillCity) ?>" required autocomplete="address-level2">
                                <div class="field-error-msg" id="err-city" style="display: none; color: #ef4444; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <div class="checkout-field-group">
                                <label class="checkout-label" for="checkout-state-select" id="lbl-checkout-state">
                                    State <span class="required">*</span>
                                </label>
                                <!-- India: State select -->
                                <select id="checkout-state-select" class="checkout-select" autocomplete="address-level1">
                                    <option value="">Select State</option>
                                    <?php foreach($indianStates as $st): ?>
                                        <option value="<?= esc($st) ?>" <?= $st === $prefillState ? 'selected' : '' ?>><?= esc($st) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- International: State / Province text input -->
                                <input type="text" id="checkout-state-text" class="checkout-input" placeholder="e.g. California, Ontario, Dubai" style="display: none;">
                                <!-- Hidden input for form submission -->
                                <input type="hidden" id="checkout-state" name="state" value="<?= esc($prefillState) ?>">
                                <div class="field-error-msg" id="err-state" style="display: none; color: #ef4444; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <div class="checkout-field-group">
                                <label class="checkout-label" for="checkout-pincode" id="lbl-checkout-pincode">
                                    PIN Code <span class="required">*</span>
                                </label>
                                <input type="text" id="checkout-pincode" name="pincode" class="checkout-input" placeholder="6-digit PIN" value="<?= esc($prefillPincode) ?>" required maxlength="12" autocomplete="postal-code">
                                <div class="field-error-msg" id="err-pincode" style="display: none; color: #ef4444; font-size: 12px; margin-top: 4px;"></div>
                            </div>
                        </div>

                        <label class="checkout-checkbox-label">
                            <input type="checkbox" name="save_info" value="1" checked>
                            <span>Save this delivery information for next time</span>
                        </label>
                    </div>

                    <!-- SECTION C: Shipping Method -->
                    <div class="checkout-section-card" id="section-shipping">
                        <div class="checkout-section-header">
                            <h2 class="checkout-section-title">
                                <i class="fa-solid fa-truck-fast"></i> Shipping Method
                            </h2>
                        </div>

                        <div class="shipping-method-option">
                            <div>
                                <div class="shipping-method-title">
                                    <i class="fa-solid fa-circle-check" style="color: #16a34a; margin-right: 6px;"></i>
                                    Standard Direct Express Delivery
                                </div>
                                <div class="shipping-method-desc">Dispatched directly from weaving center in Elampillai • Delivery in 2 - 5 business days</div>
                            </div>
                            <div class="shipping-method-price">
                                <?php if($isFreeShipping): ?>
                                    <span class="shipping-free-badge">FREE</span>
                                <?php else: ?>
                                    ₹<?= number_format($shippingVal, 2) ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if(!$isFreeShipping && $subtotalVal < $freeThresholdVal): ?>
                            <div style="font-size: 12px; color: #b45309; background: #fef3c7; padding: 8px 12px; border-radius: 6px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-circle-info"></i>
                                Add ₹<?= number_format($freeThresholdVal - $subtotalVal, 2) ?> more to your cart to unlock <strong>FREE Shipping</strong>!
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- SECTION D: Payment Options -->
                    <div class="checkout-section-card" id="section-payment">
                        <div class="checkout-section-header">
                            <h2 class="checkout-section-title">
                                <i class="fa-solid fa-credit-card"></i> Payment Method
                            </h2>
                        </div>

                        <div style="font-size: 13px; color: #64748b; margin-bottom: 14px;">
                            All transactions are secure and encrypted.
                        </div>

                        <!-- Razorpay Secure -->
                        <div class="payment-option-card selected" id="card-rzp-option">
                            <div class="payment-option-header" onclick="selectPaymentOption('Razorpay Secure')">
                                <label>
                                    <input type="radio" name="payment_method" value="Razorpay Secure" checked style="accent-color: #7a1530;">
                                    <span>Razorpay Secure</span>
                                </label>
                                <div class="payment-badge-icons">
                                    <i class="fa-brands fa-google-pay" title="Google Pay"></i>
                                    <i class="fa-solid fa-building-columns" title="Net Banking"></i>
                                    <i class="fa-brands fa-cc-visa" title="Visa"></i>
                                    <i class="fa-brands fa-cc-mastercard" title="Mastercard"></i>
                                </div>
                            </div>
                            <div class="payment-option-body" id="body-rzp-option">
                                <p style="margin: 0 0 10px 0;">Pay securely via <strong>UPI (PhonePe, Google Pay, Paytm), Credit &amp; Debit Cards, or Net Banking</strong>.</p>
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #16a34a;">
                                    <i class="fa-solid fa-lock"></i>
                                    <span>You will be seamlessly transferred to Razorpay Secure to complete payment with instant verification.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cash on Delivery (COD) - Commented out
                        <div class="payment-option-card" id="card-cod-option">
                            <div class="payment-option-header" onclick="selectPaymentOption('Cash on Delivery')">
                                <label>
                                    <input type="radio" name="payment_method" value="Cash on Delivery" style="accent-color: #7a1530;">
                                    <span>Cash on Delivery (COD)</span>
                                </label>
                                <i class="fa-solid fa-hand-holding-dollar" style="color: #64748b; font-size: 18px;"></i>
                            </div>
                            <div class="payment-option-body" id="body-cod-option" style="display: none;">
                                <p style="margin: 0;">Pay in cash when your saree is delivered to your doorstep. Please ensure exact change is kept ready.</p>
                            </div>
                        </div>
                        <div id="cod-intl-notice" style="display: none; font-size: 12px; color: #b45309; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 14px; margin-top: 10px; line-height: 1.5;">
                            <i class="fa-solid fa-circle-exclamation" style="color: #d97706; margin-right: 4px;"></i>
                            <strong>International Shipping:</strong> Cash on Delivery is available within India only. For international deliveries, please use <strong>Razorpay Secure</strong> (Credit/Debit Cards, Net Banking, International Gateways).
                        </div>
                        -->
                    </div>

                    <!-- SECTION E: Billing Address -->
                    <div class="checkout-section-card" id="section-billing">
                        <div class="checkout-section-header">
                            <h2 class="checkout-section-title">
                                <i class="fa-solid fa-receipt"></i> Billing Address
                            </h2>
                        </div>

                        <div style="border: 1.5px solid #cbd5e1; border-radius: 8px; overflow: hidden;">
                            <label style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: #ffffff; cursor: pointer; border-bottom: 1px solid #e2e8f0;">
                                <input type="radio" name="billing_same" value="1" checked onchange="toggleBillingAddressForm(false)">
                                <span style="font-size: 14px; font-weight: 600; color: #1e293b;">Same as shipping address</span>
                            </label>

                            <label style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: #ffffff; cursor: pointer;">
                                <input type="radio" name="billing_same" value="0" onchange="toggleBillingAddressForm(true)">
                                <span style="font-size: 14px; font-weight: 600; color: #1e293b;">Use a different billing address</span>
                            </label>
                        </div>

                        <!-- Collapsible Different Billing Address Form -->
                        <div id="different-billing-form" style="display: none; margin-top: 18px; padding-top: 18px; border-top: 1px dashed #cbd5e1;">
                            <div class="checkout-field-group">
                                <label class="checkout-label" for="bill-fullname">Billing Name</label>
                                <input type="text" id="bill-fullname" name="billing_fullname" class="checkout-input" placeholder="Billing Name">
                            </div>

                            <div class="checkout-field-group">
                                <label class="checkout-label" for="bill-address1">Billing Address Line 1</label>
                                <input type="text" id="bill-address1" name="billing_address1" class="checkout-input" placeholder="Building, Street Name">
                            </div>

                            <div class="form-grid-3">
                                <div class="checkout-field-group">
                                    <label class="checkout-label" for="bill-city">City</label>
                                    <input type="text" id="bill-city" name="billing_city" class="checkout-input" placeholder="City">
                                </div>
                                <div class="checkout-field-group">
                                    <label class="checkout-label" for="bill-state">State</label>
                                    <select id="bill-state" name="billing_state" class="checkout-select">
                                        <option value="">Select State</option>
                                        <?php foreach($indianStates as $st): ?>
                                            <option value="<?= esc($st) ?>"><?= esc($st) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="checkout-field-group">
                                    <label class="checkout-label" for="bill-pincode">PIN Code</label>
                                    <input type="text" id="bill-pincode" name="billing_pincode" class="checkout-input" placeholder="6-digit PIN" maxlength="6">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION F: Optional Tip -->
                    <div class="checkout-section-card" id="section-tip">
                        <div class="checkout-section-header">
                            <h2 class="checkout-section-title">
                                <i class="fa-solid fa-heart" style="color: #e11d48;"></i> Support Our Master Weavers (Optional Tip)
                            </h2>
                        </div>
                        <p style="font-size: 13px; color: #64748b; margin: 0 0 10px 0;">
                            100% of optional tips go directly to the artisan handloom weavers in Elampillai. Completely voluntary.
                        </p>

                        <div class="tip-selector-grid">
                            <button type="button" class="tip-option-btn active" data-tip="none" onclick="setTipOption('none', 0)">
                                None
                            </button>
                            <button type="button" class="tip-option-btn" data-tip="1%" onclick="setTipOption('1%', 0.01)">
                                1% (<span id="tip-preview-1" data-base-tip="<?= $subtotalVal * 0.01 ?>">₹<?= number_format($subtotalVal * 0.01, 2) ?></span>)
                            </button>
                            <button type="button" class="tip-option-btn" data-tip="2%" onclick="setTipOption('2%', 0.02)">
                                2% (<span id="tip-preview-2" data-base-tip="<?= $subtotalVal * 0.02 ?>">₹<?= number_format($subtotalVal * 0.02, 2) ?></span>)
                            </button>
                            <button type="button" class="tip-option-btn" data-tip="3%" onclick="setTipOption('3%', 0.03)">
                                3% (<span id="tip-preview-3" data-base-tip="<?= $subtotalVal * 0.03 ?>">₹<?= number_format($subtotalVal * 0.03, 2) ?></span>)
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Right Column: SECTION G: Order Summary -->
                <div class="checkout-summary-column">
                    <div class="summary-card">
                        <h3 class="summary-title">
                            Order Summary (<?= count($items) ?> item<?= count($items) === 1 ? '' : 's' ?>)
                        </h3>

                        <!-- Items List -->
                        <div class="summary-items-list">
                            <?php foreach($items as $item): ?>
                                <div class="summary-item-row">
                                    <img src="<?= esc($item['image_url']) ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/images/placeholder.jpg') ?>';" alt="<?= esc($item['product_name']) ?>" class="summary-item-img">
                                    <div class="summary-item-details">
                                        <div class="summary-item-name"><?= esc($item['product_name']) ?></div>
                                        <?php 
                                            $cleanVariant = !empty($item['variant_name']) ? trim(preg_replace('/(?:\s*\/\s*)?Free\s*Size/i', '', $item['variant_name'])) : '';
                                            if(!empty($cleanVariant)): 
                                        ?>
                                            <div class="summary-item-meta">
                                                <i class="fa-solid fa-palette" style="color: #7a1530; font-size: 11px;"></i> <?= esc($cleanVariant) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="summary-item-meta">Qty: <strong><?= (int)$item['quantity'] ?></strong></div>
                                    </div>
                                    <div class="summary-item-price" data-base-price="<?= $item['price'] * $item['quantity'] ?>">
                                        ₹<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Price Calculations -->
                        <div class="summary-line-item">
                            <span>Subtotal</span>
                            <span id="summary-subtotal-disp" data-base-price="<?= $subtotalVal ?>">₹<?= number_format($subtotalVal, 2) ?></span>
                        </div>

                        <?php if(!empty($discount_amount) && $discount_amount > 0): ?>
                            <div class="summary-line-item discount">
                                <span>Discount Saved</span>
                                <span id="summary-discount-disp" data-base-price="<?= $discount_amount ?>">-₹<?= number_format($discount_amount, 2) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="summary-line-item">
                            <span>Estimated Shipping</span>
                            <span id="summary-shipping-disp" data-base-price="<?= $shippingVal ?>">
                                <?= $isFreeShipping ? '<strong style="color: #16a34a;">FREE</strong>' : '₹' . number_format($shippingVal, 2) ?>
                            </span>
                        </div>

                        <div class="summary-line-item" id="summary-tip-row" style="display: none;">
                            <span>Artisan Tip</span>
                            <span id="summary-tip-disp">₹0.00</span>
                        </div>

                        <div class="summary-line-item">
                            <span>Estimated Taxes (GST)</span>
                            <span style="color: #64748b;">Included</span>
                        </div>

                        <!-- Grand Total -->
                        <div class="summary-line-item total" style="align-items: flex-start; padding: 16px 0; border-top: 2px solid #0f172a; margin-top: 14px;">
                            <div>
                                <span id="summary-total-label" style="font-size: 17px; font-weight: 800; color: #0f172a;">Total (INR)</span>
                                <div id="summary-currency-note" style="display: none; font-size: 11px; color: #475569; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 5px 8px; margin-top: 6px; line-height: 1.4;">
                                    <i class="fa-solid fa-circle-info" style="color: #0284c7;"></i> Charged as ₹<span id="summary-inr-charged-note"><?= number_format($subtotalVal + $shippingVal, 2) ?></span> INR at gateway.
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <span id="summary-grand-total" style="font-size: 22px; font-weight: 900; color: #7a1530;">₹<?= number_format($subtotalVal + $shippingVal, 2) ?></span>
                                <div id="summary-inr-subtext" style="display: none; font-size: 12px; color: #64748b; font-weight: 700; margin-top: 3px;"></div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button type="button" id="btn-complete-order" class="checkout-submit-btn" data-key="<?= $rzpKey ?>" data-name="<?= $bizName ?>">
                            <i class="fa-solid fa-lock"></i> Pay Now / Complete Order
                        </button>

                        <div class="summary-guarantee-box">
                            <i class="fa-solid fa-medal" style="font-size: 20px; color: #d97706; flex-shrink: 0;"></i>
                            <div>
                                <strong>Elampillai Handloom Assurance:</strong> 100% Genuine silk and cotton sarees direct from manufacturing hubs.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
        <?php endif; ?>

    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="<?= base_url('assets/js/checkout.js') ?>"></script>

<?= view('user/footer') ?>
