/**
 * Selvi Tex - Checkout & Razorpay Integration Engine
 * Handles address validation, dynamic tips, shipping recalculation, 
 * Razorpay Checkout modal, and server-side HMAC payment verification.
 */

function getAppBaseUrl() {
    var baseMeta = document.querySelector('base');
    if (baseMeta && baseMeta.href) {
        return baseMeta.href.replace(/\/$/, '');
    }
    var pathname = window.location.pathname;
    if (pathname.indexOf('/sri/public') !== -1) {
        return window.location.origin + '/sri/public';
    }
    return window.location.origin;
}

// Global checkout state
var currentTipRate = 0;
var currentTipChoice = 'none';

function setTipOption(rateStr, rateNum) {
    currentTipChoice = rateStr;
    currentTipRate = rateNum;

    var hiddenInput = document.getElementById('selected-tip-rate');
    if (hiddenInput) hiddenInput.value = rateStr;

    // Update button active states
    document.querySelectorAll('.tip-option-btn').forEach(function(btn) {
        if (btn.dataset.tip === rateStr) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    recalculateCheckoutSummary();
}

function selectPaymentOption(method) {
    var rzpCard = document.getElementById('card-rzp-option');
    var codCard = document.getElementById('card-cod-option');
    var rzpBody = document.getElementById('body-rzp-option');
    var codBody = document.getElementById('body-cod-option');

    var rzpRadio = document.querySelector('input[name="payment_method"][value="Razorpay Secure"]');
    var codRadio = document.querySelector('input[name="payment_method"][value="Cash on Delivery"]');

    if (method === 'Razorpay Secure') {
        if (rzpRadio) rzpRadio.checked = true;
        if (rzpCard) rzpCard.classList.add('selected');
        if (codCard) codCard.classList.remove('selected');
        if (rzpBody) rzpBody.style.display = 'block';
        if (codBody) codBody.style.display = 'none';
    } else {
        if (codRadio) codRadio.checked = true;
        if (codCard) codCard.classList.add('selected');
        if (rzpCard) rzpCard.classList.remove('selected');
        if (codBody) codBody.style.display = 'block';
        if (rzpBody) rzpBody.style.display = 'none';
    }
}

function toggleBillingAddressForm(isDifferent) {
    var diffForm = document.getElementById('different-billing-form');
    if (diffForm) {
        diffForm.style.display = isDifferent ? 'block' : 'none';
    }
}

function handleCountryChange(countryName) {
    var isIndia = (!countryName || countryName === 'India');
    var stateSelect = document.getElementById('checkout-state-select');
    var stateText = document.getElementById('checkout-state-text');
    var stateHidden = document.getElementById('checkout-state');
    var lblState = document.getElementById('lbl-checkout-state');
    var lblPin = document.getElementById('lbl-checkout-pincode');
    var pincodeInput = document.getElementById('checkout-pincode');
    var codCard = document.getElementById('card-cod-option');
    var codNotice = document.getElementById('cod-intl-notice');
    var phoneInput = document.getElementById('checkout-contact-phone');

    if (isIndia) {
        if (stateSelect) stateSelect.style.display = 'block';
        if (stateText) stateText.style.display = 'none';
        if (stateHidden && stateSelect) stateHidden.value = stateSelect.value;
        if (lblState) lblState.innerHTML = 'State <span class="required">*</span>';
        if (lblPin) lblPin.innerHTML = 'PIN Code <span class="required">*</span>';
        if (pincodeInput) {
            pincodeInput.placeholder = '6-digit PIN';
            pincodeInput.maxLength = 6;
        }
        if (phoneInput) {
            phoneInput.placeholder = '10-digit mobile number';
        }
        if (codCard) {
            codCard.style.opacity = '1';
            codCard.style.pointerEvents = 'auto';
        }
        if (codNotice) codNotice.style.display = 'none';
    } else {
        if (stateSelect) stateSelect.style.display = 'none';
        if (stateText) stateText.style.display = 'block';
        if (stateHidden && stateText) stateHidden.value = stateText.value;
        if (lblState) lblState.innerHTML = 'State / Province / Region <span class="required">*</span>';
        if (lblPin) lblPin.innerHTML = 'Postal / ZIP Code <span class="required">*</span>';
        if (pincodeInput) {
            pincodeInput.placeholder = 'e.g. 90210, SW1A 1AA, etc.';
            pincodeInput.maxLength = 12;
        }
        if (phoneInput) {
            phoneInput.placeholder = 'Phone with country code (e.g. +1 555...)';
        }
        // Auto-switch to Razorpay Secure for international
        selectPaymentOption('Razorpay Secure');
        if (codCard) {
            codCard.style.opacity = '0.5';
            codCard.style.pointerEvents = 'none';
        }
        if (codNotice) codNotice.style.display = 'block';
    }
}
window.handleCountryChange = handleCountryChange;

function syncCountryFromHeader() {
    var countrySelect = document.getElementById('checkout-country');
    if (!countrySelect) return;
    try {
        var saved = null;
        if (typeof window.getSavedCountry === 'function') {
            saved = window.getSavedCountry();
        } else {
            var raw = localStorage.getItem('sri_user_country_currency');
            if (raw) saved = JSON.parse(raw);
        }
        if (saved && saved.name) {
            for (var i = 0; i < countrySelect.options.length; i++) {
                if (countrySelect.options[i].value.toLowerCase() === saved.name.toLowerCase()) {
                    countrySelect.selectedIndex = i;
                    handleCountryChange(countrySelect.options[i].value);
                    break;
                }
            }
        }
    } catch(e) {}
}

function recalculateCheckoutSummary() {
    var subtotalEl = document.getElementById('checkout-subtotal-val');
    var shippingEl = document.getElementById('checkout-shipping-val');
    var discountEl = document.getElementById('checkout-discount-val');
    var tipRow = document.getElementById('summary-tip-row');
    var tipDisp = document.getElementById('summary-tip-disp');
    var grandTotalEl = document.getElementById('summary-grand-total');
    var totalLabelEl = document.getElementById('summary-total-label');
    var currNoteEl = document.getElementById('summary-currency-note');
    var inrSubtextEl = document.getElementById('summary-inr-subtext');
    var inrChargedNoteEl = document.getElementById('summary-inr-charged-note');
    var subtotalDispEl = document.getElementById('summary-subtotal-disp');
    var shippingDispEl = document.getElementById('summary-shipping-disp');
    var discountDispEl = document.getElementById('summary-discount-disp');

    if (!subtotalEl || !grandTotalEl) return;

    var subtotal = parseFloat(subtotalEl.value) || 0;
    var shipping = parseFloat(shippingEl.value) || 0;
    var discount = discountEl ? (parseFloat(discountEl.value) || 0) : 0;
    var tipAmount = Math.round(subtotal * currentTipRate * 100) / 100;
    var grandTotal = Math.round((subtotal + shipping + tipAmount) * 100) / 100;

    var savedCurr = 'INR';
    try {
        if (typeof window.getSavedCountry === 'function') {
            var c = window.getSavedCountry();
            if (c && c.currency) savedCurr = c.currency;
        } else {
            var raw = localStorage.getItem('sri_user_country_currency');
            if (raw) {
                var parsed = JSON.parse(raw);
                if (parsed && parsed.currency) savedCurr = parsed.currency;
            }
        }
    } catch(e) {}

    var formatPrice = function(amt) {
        if (window.formatCurrencyPrice) {
            return window.formatCurrencyPrice(amt, savedCurr);
        }
        return '₹' + amt.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    var isNonINR = (savedCurr !== 'INR');

    // 1. Update line item prices in summary
    document.querySelectorAll('.summary-item-price').forEach(function(itemPriceEl) {
        var base = parseFloat(itemPriceEl.getAttribute('data-base-price'));
        if (!isNaN(base)) {
            if (isNonINR) {
                itemPriceEl.innerHTML = '<span style="font-weight: 800; color: #1e293b;">' + formatPrice(base) + '</span>' +
                    '<div style="font-size: 11px; color: #64748b; font-weight: normal;">(₹' + base.toFixed(2) + ' INR)</div>';
            } else {
                itemPriceEl.textContent = '₹' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    });

    // 2. Update Subtotal
    if (subtotalDispEl) {
        if (isNonINR) {
            subtotalDispEl.innerHTML = '<span style="font-weight: 700;">' + formatPrice(subtotal) + '</span>' +
                '<span style="font-size: 11.5px; color: #64748b; margin-left: 6px;">(₹' + subtotal.toFixed(2) + ')</span>';
        } else {
            subtotalDispEl.textContent = '₹' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    // 3. Update Discount Saved
    if (discountDispEl && discount > 0) {
        if (isNonINR) {
            discountDispEl.innerHTML = '-' + formatPrice(discount) +
                '<span style="font-size: 11px; color: #16a34a; margin-left: 4px;">(-₹' + discount.toFixed(2) + ')</span>';
        } else {
            discountDispEl.textContent = '-₹' + discount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    // 4. Update Estimated Shipping
    if (shippingDispEl) {
        if (shipping === 0) {
            shippingDispEl.innerHTML = '<strong style="color: #16a34a;">FREE</strong>';
        } else if (isNonINR) {
            shippingDispEl.innerHTML = formatPrice(shipping) +
                '<span style="font-size: 11.5px; color: #64748b; margin-left: 4px;">(₹' + shipping.toFixed(2) + ')</span>';
        } else {
            shippingDispEl.textContent = '₹' + shipping.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    // 5. Update Tip Buttons Previews
    [1, 2, 3].forEach(function(pct) {
        var prevEl = document.getElementById('tip-preview-' + pct);
        if (prevEl) {
            var tipVal = subtotal * (pct / 100);
            if (isNonINR) {
                prevEl.textContent = formatPrice(tipVal);
            } else {
                prevEl.textContent = '₹' + tipVal.toFixed(2);
            }
        }
    });

    // 6. Update Artisan Tip Row in Summary
    if (tipAmount > 0) {
        if (tipRow) tipRow.style.display = 'flex';
        if (tipDisp) {
            if (isNonINR) {
                tipDisp.innerHTML = formatPrice(tipAmount) + ' <span style="font-size: 11px; color: #64748b;">(₹' + tipAmount.toFixed(2) + ')</span>';
            } else {
                tipDisp.textContent = '₹' + tipAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    } else {
        if (tipRow) tipRow.style.display = 'none';
    }

    // 7. Update Grand Total Row & Currency Note
    if (totalLabelEl) {
        totalLabelEl.textContent = isNonINR ? 'Total (' + savedCurr + ')' : 'Total (INR)';
    }

    var inrStr = '₹' + grandTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    if (isNonINR) {
        grandTotalEl.textContent = formatPrice(grandTotal);
        if (inrSubtextEl) {
            inrSubtextEl.textContent = '(' + inrStr + ' INR)';
            inrSubtextEl.style.display = 'block';
        }
        if (currNoteEl) {
            if (inrChargedNoteEl) inrChargedNoteEl.textContent = grandTotal.toFixed(2);
            currNoteEl.style.display = 'block';
        }
    } else {
        grandTotalEl.textContent = inrStr;
        if (inrSubtextEl) inrSubtextEl.style.display = 'none';
        if (currNoteEl) currNoteEl.style.display = 'none';
    }

    // 8. Update Complete Order Button Text
    var btnComplete = document.getElementById('btn-complete-order');
    if (btnComplete) {
        if (isNonINR) {
            btnComplete.innerHTML = '<i class="fa-solid fa-lock"></i> Pay ' + formatPrice(grandTotal) + ' (' + inrStr + ')';
        } else {
            btnComplete.innerHTML = '<i class="fa-solid fa-lock"></i> Pay ' + inrStr + ' / Complete Order';
        }
    }
}

// Global hook for live currency switcher
window.recalculateCheckoutSummary = recalculateCheckoutSummary;
window.addEventListener('currencyChanged', function() {
    recalculateCheckoutSummary();
});

document.addEventListener('DOMContentLoaded', function() {
    recalculateCheckoutSummary();

    // Auto-sync country with user's preferred country from header switcher
    syncCountryFromHeader();

    // Bind state inputs to hidden state field
    var stateSelectEl = document.getElementById('checkout-state-select');
    var stateTextEl = document.getElementById('checkout-state-text');
    var stateHiddenEl = document.getElementById('checkout-state');
    var countrySelectEl = document.getElementById('checkout-country');

    if (stateSelectEl && stateHiddenEl) {
        stateSelectEl.addEventListener('change', function() {
            stateHiddenEl.value = this.value;
        });
    }
    if (stateTextEl && stateHiddenEl) {
        stateTextEl.addEventListener('input', function() {
            stateHiddenEl.value = this.value.trim();
        });
    }
    if (countrySelectEl) {
        countrySelectEl.addEventListener('change', function() {
            handleCountryChange(this.value);
        });
    }

    var completeBtn = document.getElementById('btn-complete-order');
    var checkoutForm = document.getElementById('checkout-form');
    if (!completeBtn || !checkoutForm) return;

    completeBtn.addEventListener('click', function(e) {
        e.preventDefault();

        // 1. Client-Side Field Validations
        var isValid = true;
        clearValidationErrors();

        var emailInput = document.getElementById('checkout-email');
        var phoneInput = document.getElementById('checkout-contact-phone');
        var fullnameInput = document.getElementById('checkout-fullname');
        var address1Input = document.getElementById('checkout-address1');
        var cityInput = document.getElementById('checkout-city');
        var pincodeInput = document.getElementById('checkout-pincode');

        var countryVal = countrySelectEl ? countrySelectEl.value.trim() : 'India';
        var isIndia = (!countryVal || countryVal === 'India');

        var emailVal = emailInput ? emailInput.value.trim() : '';
        var phoneVal = phoneInput ? phoneInput.value.trim() : '';
        var fullnameVal = fullnameInput ? fullnameInput.value.trim() : '';
        var address1Val = address1Input ? address1Input.value.trim() : '';
        var cityVal = cityInput ? cityInput.value.trim() : '';
        var pincodeVal = pincodeInput ? pincodeInput.value.trim() : '';

        // Email regex
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailVal || !emailPattern.test(emailVal)) {
            showFieldError('err-email', emailInput, 'Please enter a valid email address.');
            isValid = false;
        }

        // Phone: 10 digits for India, international format for others
        if (isIndia) {
            var phonePattern = /^[0-9]{10}$/;
            if (!phoneVal || !phonePattern.test(phoneVal.replace(/\D/g, ''))) {
                showFieldError('err-phone', phoneInput, 'Please enter a valid 10-digit mobile number.');
                isValid = false;
            }
        } else {
            var phoneDigits = phoneVal.replace(/[^0-9]/g, '');
            if (!phoneDigits || phoneDigits.length < 7 || phoneDigits.length > 15) {
                showFieldError('err-phone', phoneInput, 'Please enter a valid phone number with country code (min 7 digits).');
                isValid = false;
            }
        }

        if (!fullnameVal) {
            showFieldError('err-fullname', fullnameInput, 'Please enter the recipient full name.');
            isValid = false;
        }

        if (!address1Val) {
            showFieldError('err-address1', address1Input, 'Please enter complete street address.');
            isValid = false;
        }

        if (!cityVal) {
            showFieldError('err-city', cityInput, 'Please enter city or district.');
            isValid = false;
        }

        // State validation: dropdown for India, text input for international
        if (isIndia) {
            var curSelectState = stateSelectEl ? stateSelectEl.value.trim() : '';
            if (stateHiddenEl) stateHiddenEl.value = curSelectState;
            if (!curSelectState) {
                showFieldError('err-state', stateSelectEl, 'Please select your delivery state.');
                isValid = false;
            }
        } else {
            var curTextState = stateTextEl ? stateTextEl.value.trim() : '';
            if (stateHiddenEl) stateHiddenEl.value = curTextState;
            if (!curTextState) {
                showFieldError('err-state', stateTextEl, 'Please enter your state / province / region.');
                isValid = false;
            }
        }

        // Pincode / Postal Code: 6 digits for India, 3-12 alphanumeric for international
        if (isIndia) {
            var pinPattern = /^[1-9][0-9]{5}$/;
            if (!pincodeVal || !pinPattern.test(pincodeVal.replace(/\D/g, ''))) {
                showFieldError('err-pincode', pincodeInput, 'Please enter a valid 6-digit Indian PIN code.');
                isValid = false;
            }
        } else {
            if (!pincodeVal || pincodeVal.trim().length < 3 || pincodeVal.trim().length > 12) {
                showFieldError('err-pincode', pincodeInput, 'Please enter a valid Postal / ZIP code (3 to 12 characters).');
                isValid = false;
            }
        }

        if (!isValid) {
            var firstError = document.querySelector('.field-error-msg[style*="display: block"]');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        // 2. Prepare Order Request
        completeBtn.disabled = true;
        var originalBtnHtml = completeBtn.innerHTML;
        completeBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Initializing Order...';

        var formData = new FormData(checkoutForm);
        var baseUrl = getAppBaseUrl();

        fetch(baseUrl + '/checkout/create-razorpay-order', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(function(res) {
            return res.json().then(function(data) {
                return { status: res.status, ok: res.ok, data: data };
            });
        })
        .then(function(result) {
            if (!result.ok || result.data.status !== 'success') {
                completeBtn.disabled = false;
                completeBtn.innerHTML = originalBtnHtml;
                alert(result.data.message || 'Unable to place order. Please check all details and try again.');
                return;
            }

            var orderData = result.data;

            // If Cash on Delivery was selected
            if (orderData.is_cod) {
                window.location.href = orderData.redirect_url;
                return;
            }

            // Razorpay Flow
            if (typeof Razorpay === 'undefined') {
                completeBtn.disabled = false;
                completeBtn.innerHTML = originalBtnHtml;
                alert('Razorpay payment library could not be loaded. Please check your network connection.');
                return;
            }

            var rzpOptions = {
                key: orderData.key_id,
                amount: orderData.amount,
                currency: orderData.currency || 'INR',
                name: orderData.business_name || 'Selvi Tex',
                description: 'Payment for Order #' + orderData.order_number,
                order_id: orderData.razorpay_order_id,
                prefill: {
                    name: orderData.customer_name,
                    email: orderData.customer_email,
                    contact: orderData.customer_phone
                },
                theme: {
                    color: '#7a1530'
                },
                modal: {
                    ondismiss: function() {
                        completeBtn.disabled = false;
                        completeBtn.innerHTML = originalBtnHtml;
                    }
                },
                handler: function(response) {
                    completeBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying Payment...';

                    // Server-side signature verification
                    var verifyParams = new URLSearchParams();
                    verifyParams.append('razorpay_order_id', response.razorpay_order_id);
                    verifyParams.append('razorpay_payment_id', response.razorpay_payment_id);
                    verifyParams.append('razorpay_signature', response.razorpay_signature);
                    verifyParams.append('order_number', orderData.order_number);

                    fetch(baseUrl + '/checkout/verify-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: verifyParams.toString()
                    })
                    .then(function(vRes) { return vRes.json(); })
                    .then(function(vData) {
                        if (vData.status === 'success') {
                            window.location.href = vData.redirect_url;
                        } else {
                            completeBtn.disabled = false;
                            completeBtn.innerHTML = originalBtnHtml;
                            alert(vData.message || 'Payment verification failed. Please contact customer support.');
                        }
                    })
                    .catch(function(vErr) {
                        completeBtn.disabled = false;
                        completeBtn.innerHTML = originalBtnHtml;
                        alert('Your payment was received by Razorpay, but verification encountered a connection issue. Your Order Number is: ' + orderData.order_number);
                    });
                }
            };

            var rzpInstance = new Razorpay(rzpOptions);
            rzpInstance.on('payment.failed', function(failureResp) {
                completeBtn.disabled = false;
                completeBtn.innerHTML = originalBtnHtml;
                var reason = (failureResp.error && failureResp.error.description) ? failureResp.error.description : 'Payment failed or was cancelled.';
                alert('Payment Transaction Error: ' + reason + '\nPlease try again or use another payment method.');
            });

            rzpInstance.open();
        })
        .catch(function(err) {
            completeBtn.disabled = false;
            completeBtn.innerHTML = originalBtnHtml;
            alert('A network error occurred while creating your order. Please check your connection and try again.');
        });
    });
});

function showFieldError(errorId, inputElement, message) {
    var errorEl = document.getElementById(errorId);
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.style.display = 'block';
    }
    if (inputElement) {
        inputElement.style.borderColor = '#ef4444';
    }
}

function clearValidationErrors() {
    document.querySelectorAll('.field-error-msg').forEach(function(el) {
        el.textContent = '';
        el.style.display = 'none';
    });
    document.querySelectorAll('.checkout-input, .checkout-select').forEach(function(el) {
        el.style.borderColor = '';
    });
}
