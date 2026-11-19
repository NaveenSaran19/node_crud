/**
 * Frontend Product Variants & Interactive Media Gallery
 * Powers color swatch switching on product cards and the full
 * variant matrix + gallery + video engine on the product detail page.
 */

document.addEventListener('DOMContentLoaded', function() {
    initProductCardSwatches();
    initProductCardSizes();
    initProductCardGalleryHover();
    initProductDetailPage();
    initCardAddToCartButtons();
    initCartDrawer();
    initWishlistButtons();
});

/* ==========================================================================
   1. Product Card Gallery Hover Slideshow & Swatches
   ========================================================================== */
function initProductCardGalleryHover() {
    // Initialize existing cards on page
    document.querySelectorAll('.card-img-holder[data-gallery]').forEach(function(holder) {
        setupCardHoverSlideshow(holder);
    });

    // Also attach to any dynamically loaded or rendered cards
    document.addEventListener('mouseover', function(e) {
        var holder = e.target.closest('.card-img-holder');
        if (holder && holder.dataset.gallery && !holder.dataset.hoverInit) {
            setupCardHoverSlideshow(holder);
            if (holder.matches(':hover') && typeof holder._startHoverCycle === 'function') {
                holder._startHoverCycle();
            }
        }
    });
}

function setupCardHoverSlideshow(holder) {
    if (holder.dataset.hoverInit) return;
    holder.dataset.hoverInit = 'true';
    var galleryRaw = holder.dataset.gallery;
    if (!galleryRaw) return;

    var gallery = [];
    try {
        gallery = JSON.parse(galleryRaw);
    } catch(err) {
        return;
    }

    if (!Array.isArray(gallery) || gallery.length <= 1) return;

    var img = holder.querySelector('img');
    if (!img) return;

    var originalSrc = gallery[0] || img.src;
    var currentIndex = 0;
    var intervalId = null;
    var initialTimer = null;

    // Preload gallery images for smooth, flicker-free transitions
    gallery.forEach(function(url) {
        if (url) {
            var pre = new Image();
            pre.src = url;
        }
    });

    // Create dot indicators
    var dotsContainer = holder.querySelector('.card-hover-dots');
    if (!dotsContainer) {
        dotsContainer = document.createElement('div');
        dotsContainer.className = 'card-hover-dots';
        gallery.forEach(function(_, idx) {
            var dot = document.createElement('span');
            dot.className = 'card-hover-dot' + (idx === 0 ? ' active' : '');
            dotsContainer.appendChild(dot);
        });
        holder.appendChild(dotsContainer);
    }

    function updateDots(idx) {
        if (!dotsContainer) return;
        var dots = dotsContainer.querySelectorAll('.card-hover-dot');
        dots.forEach(function(d, i) {
            if (i === idx) d.classList.add('active');
            else d.classList.remove('active');
        });
    }

    function showNextImage() {
        currentIndex = (currentIndex + 1) % gallery.length;
        var nextSrc = gallery[currentIndex];
        if (!nextSrc) return;
        img.style.transition = 'opacity 0.32s ease, transform 0.32s ease';
        img.style.opacity = '0';
        setTimeout(function() {
            img.src = nextSrc;
            img.onload = function() {
                img.style.opacity = '1';
            };
            setTimeout(function() { img.style.opacity = '1'; }, 40);
            updateDots(currentIndex);
        }, 300);
    }

    function startCycle() {
        stopCycle();
        // Show next image after 500ms, then cycle every 1200ms
        initialTimer = setTimeout(function() {
            showNextImage();
            intervalId = setInterval(showNextImage, 1200);
        }, 500);
    }

    function stopCycle() {
        if (initialTimer) {
            clearTimeout(initialTimer);
            initialTimer = null;
        }
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    }

    holder._startHoverCycle = startCycle;

    holder.addEventListener('mouseenter', startCycle);

    holder.addEventListener('mouseleave', function() {
        stopCycle();
        currentIndex = 0;
        img.style.transition = 'opacity 0.32s ease, transform 0.32s ease';
        img.style.opacity = '0';
        setTimeout(function() {
            img.src = originalSrc;
            setTimeout(function() { img.style.opacity = '1'; }, 40);
            updateDots(0);
        }, 280);
    });
}

function initProductCardSwatches() {
    // Attach event delegation for any dynamically loaded or rendered cards
    document.addEventListener('click', function(e) {
        var swatch = e.target.closest('.card-color-swatch');
        if (swatch) {
            handleCardSwatchClick(swatch, e);
        }
    });
}

function initProductCardSizes() {
    // Attach delegated click listener for card size pills
    document.addEventListener('click', function(e) {
        var pill = e.target.closest('.card-size-pill');
        if (pill) {
            handleCardSizeClick(pill, e);
        }
    });
}

function sizeVariantMatches(vSize, target) {
    if (!vSize || !target) return false;
    var parts = vSize.split(',').map(function(s) { return s.trim().toLowerCase(); });
    return parts.includes(target.toLowerCase());
}

function handleCardSizeClick(pill, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    var card = pill.closest('.product-card') || pill.closest('.shorts-card');
    if (!card) return;

    // Toggle active class on size pills within this card
    var pills = card.querySelectorAll('.card-size-pill');
    pills.forEach(function(p) { p.classList.remove('active'); });
    pill.classList.add('active');

    var selectedSize = pill.dataset.size ? pill.dataset.size.trim() : '';
    card.setAttribute('data-selected-size', selectedSize);

    var matchedVariant = null;

    // 1. Try finding matched variant from card's data-variants attribute
    var rawVariants = card.getAttribute('data-variants');
    if (rawVariants) {
        try {
            var variants = JSON.parse(rawVariants);
            if (Array.isArray(variants) && variants.length > 0) {
                var activeSwatch = card.querySelector('.card-color-swatch.active');
                var activeColor = activeSwatch ? (activeSwatch.getAttribute('title') || activeSwatch.getAttribute('aria-label') || '').trim() : '';

                if (activeColor && selectedSize) {
                    matchedVariant = variants.find(function(v) {
                        return (v.color_name || '').trim().toLowerCase() === activeColor.toLowerCase() &&
                               sizeVariantMatches(v.size, selectedSize);
                    });
                }
                if (!matchedVariant && selectedSize) {
                    matchedVariant = variants.find(function(v) {
                        return sizeVariantMatches(v.size, selectedSize);
                    });
                }
            }
        } catch(e) {}
    }

    var saleVal = null;
    var origVal = null;
    var variantId = null;
    var stockCount = null;
    var variantImg = null;

    if (matchedVariant) {
        saleVal = matchedVariant.sale_price !== undefined ? parseFloat(matchedVariant.sale_price) : null;
        origVal = matchedVariant.original_price !== undefined ? parseFloat(matchedVariant.original_price) : null;
        variantId = matchedVariant.id;
        stockCount = matchedVariant.stock !== undefined ? parseInt(matchedVariant.stock, 10) : null;
        if (matchedVariant.images && matchedVariant.images.length > 0) {
            variantImg = matchedVariant.images[0].media_url || matchedVariant.images[0].url || matchedVariant.images[0];
        }
    } else {
        if (pill.dataset.price && !isNaN(parseFloat(pill.dataset.price))) {
            saleVal = parseFloat(pill.dataset.price);
        }
        if (pill.dataset.originalPrice && !isNaN(parseFloat(pill.dataset.originalPrice))) {
            origVal = parseFloat(pill.dataset.originalPrice);
        }
        if (pill.dataset.variantId) {
            variantId = pill.dataset.variantId;
        }
        if (pill.dataset.stock !== undefined && pill.dataset.stock !== '') {
            stockCount = parseInt(pill.dataset.stock, 10);
        }
        if (pill.dataset.image) {
            variantImg = pill.dataset.image;
        }
    }

    // Update Selling Price ("rate") dynamically
    if (saleVal !== null && !isNaN(saleVal)) {
        card.setAttribute('data-price', saleVal);
        var saleEl = card.querySelector('.card-sale-price') || card.querySelector('span[style*="font-weight: 700"][style*="color: red"]') || card.querySelector('span[style*="font-weight: 800"]');
        if (saleEl) {
            saleEl.setAttribute('data-base-price', saleVal);
            if (typeof window.formatCurrencyPrice === 'function') {
                saleEl.textContent = window.formatCurrencyPrice(saleVal);
            } else {
                saleEl.textContent = '₹' + saleVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    }

    // Update Original Price (MRP Strikethrough)
    var origEl = card.querySelector('.card-original-price') || card.querySelector('span[style*="line-through"]');
    if (origEl) {
        if (origVal !== null && !isNaN(origVal) && saleVal !== null && origVal > saleVal) {
            card.setAttribute('data-original-price', origVal);
            origEl.setAttribute('data-base-price', origVal);
            if (typeof window.formatCurrencyPrice === 'function') {
                origEl.textContent = window.formatCurrencyPrice(origVal);
            } else {
                origEl.textContent = '₹' + origVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
            origEl.style.display = 'inline';
        } else {
            origEl.style.display = 'none';
        }
    }

    // Update Discount / Save Badge
    var saveBadge = card.querySelector('.card-save-badge');
    if (saveBadge) {
        if (origVal !== null && saleVal !== null && origVal > saleVal) {
            var diff = origVal - saleVal;
            saveBadge.setAttribute('data-base-save', diff);
            saveBadge.textContent = 'SAVE RS. ' + Math.round(diff);
            saveBadge.style.display = 'inline-block';
        } else {
            saveBadge.style.display = 'none';
        }
    }

    // Update Active Variant ID & Buttons
    if (variantId) {
        card.dataset.activeVariantId = variantId;
        var addBtn = card.querySelector('.card-add-cart-btn');
        var buyBtn = card.querySelector('.card-buy-now-btn, a[href*="checkout"]');
        if (addBtn) {
            addBtn.dataset.variantId = variantId;
            addBtn.dataset.selectedSize = selectedSize;
        }
        if (buyBtn) {
            buyBtn.dataset.variantId = variantId;
            buyBtn.dataset.selectedSize = selectedSize;
            try {
                var url = new URL(buyBtn.href, window.location.origin);
                url.searchParams.set('variant_id', variantId);
                if (selectedSize) url.searchParams.set('size', selectedSize);
                buyBtn.href = url.pathname + url.search;
            } catch(e) {}
        }
    }

    // Smooth image transition if variant has dedicated image
    if (variantImg && variantImg.trim() !== '') {
        var cardImg = card.querySelector('.card-img-holder img, .card-primary-img');
        if (cardImg && cardImg.src !== variantImg) {
            cardImg.style.transition = 'opacity 0.28s ease';
            cardImg.style.opacity = '0';
            setTimeout(function() {
                cardImg.src = variantImg;
                cardImg.style.opacity = '1';
            }, 260);
        }
    }

    // Update Stock Status
    if (stockCount !== null) {
        var soldBadge = card.querySelector('.stock-sold-out-badge, span[style*="background: black"]');
        if (stockCount === 0) {
            if (!soldBadge) {
                soldBadge = document.createElement('span');
                soldBadge.className = 'stock-sold-out-badge';
                soldBadge.style.cssText = 'position: absolute; top: 22px; left: 22px; background: black; color: white; padding: 4px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 4px;';
                soldBadge.textContent = 'SOLD OUT';
                card.appendChild(soldBadge);
            } else {
                soldBadge.style.display = 'inline-block';
            }
        } else {
            if (soldBadge) soldBadge.style.display = 'none';
        }
    }
}

function handleCardSwatchClick(swatch, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    var card = swatch.closest('.product-card') || swatch.closest('.shorts-card');
    if (!card) return;

    // Remove active class from sibling swatches in the same card
    var swatches = card.querySelectorAll('.card-color-swatch');
    swatches.forEach(function(s) { s.classList.remove('active'); });
    swatch.classList.add('active');

    // Smooth fade in / out image swap when variant has a designated image
    var swatchImg = swatch.dataset.image;
    if (swatchImg && swatchImg.trim() !== '') {
        var cardImg = card.querySelector('.card-img-holder img, .card-primary-img');
        if (cardImg && cardImg.src !== swatchImg) {
            cardImg.style.transition = 'opacity 0.28s ease';
            cardImg.style.opacity = '0';
            setTimeout(function() {
                cardImg.src = swatchImg;
                cardImg.style.opacity = '1';
            }, 260);
        }
    }

    // Check if card has active size selected to find combined variant
    var activeSizePill = card.querySelector('.card-size-pill.active');
    var activeSize = activeSizePill ? (activeSizePill.dataset.size || '').trim() : '';
    var swatchColor = (swatch.getAttribute('title') || swatch.getAttribute('aria-label') || '').trim();

    var matchedVariant = null;
    var rawVariants = card.getAttribute('data-variants');
    if (rawVariants && (activeSize || swatchColor)) {
        try {
            var variants = JSON.parse(rawVariants);
            if (Array.isArray(variants)) {
                if (swatchColor && activeSize) {
                    matchedVariant = variants.find(function(v) {
                        return (v.color_name || '').trim().toLowerCase() === swatchColor.toLowerCase() &&
                               sizeVariantMatches(v.size, activeSize);
                    });
                }
                if (!matchedVariant && swatchColor) {
                    matchedVariant = variants.find(function(v) {
                        return (v.color_name || '').trim().toLowerCase() === swatchColor.toLowerCase();
                    });
                }
            }
        } catch(e) {}
    }

    // Update Checkout link and active variant ID
    var variantId = matchedVariant ? matchedVariant.id : swatch.dataset.variantId;
    if (variantId) {
        card.dataset.activeVariantId = variantId;
        var addBtn = card.querySelector('.card-add-cart-btn');
        var buyBtn = card.querySelector('.card-buy-now-btn, a[href*="checkout"]');
        if (addBtn) addBtn.dataset.variantId = variantId;
        if (buyBtn) {
            buyBtn.dataset.variantId = variantId;
            try {
                var url = new URL(buyBtn.href, window.location.origin);
                url.searchParams.set('variant_id', variantId);
                buyBtn.href = url.pathname + url.search;
            } catch(e) {}
        }
    }

    // 2. Update Selling Price
    var saleVal = matchedVariant && matchedVariant.sale_price !== undefined 
        ? parseFloat(matchedVariant.sale_price) 
        : (swatch.dataset.sale ? parseFloat(String(swatch.dataset.sale).replace(/,/g, '')) : null);

    if (saleVal !== null && !isNaN(saleVal)) {
        card.setAttribute('data-price', saleVal);
        var saleEl = card.querySelector('.card-sale-price') || card.querySelector('span[style*="font-weight: 700"][style*="color: red"]') || card.querySelector('span[style*="font-weight: 800"]');
        if (saleEl) {
            saleEl.setAttribute('data-base-price', saleVal);
            if (typeof window.formatCurrencyPrice === 'function') {
                saleEl.textContent = window.formatCurrencyPrice(saleVal);
            } else {
                saleEl.textContent = '₹' + saleVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    }

    // 3. Update Original Price / Strikethrough
    var origVal = matchedVariant && matchedVariant.original_price !== undefined 
        ? parseFloat(matchedVariant.original_price) 
        : (swatch.dataset.orig ? parseFloat(String(swatch.dataset.orig).replace(/,/g, '')) : null);

    var origEl = card.querySelector('.card-original-price') || card.querySelector('span[style*="line-through"]');
    if (origEl) {
        if (origVal !== null && !isNaN(origVal) && saleVal !== null && origVal > saleVal) {
            card.setAttribute('data-original-price', origVal);
            origEl.setAttribute('data-base-price', origVal);
            if (typeof window.formatCurrencyPrice === 'function') {
                origEl.textContent = window.formatCurrencyPrice(origVal);
            } else {
                origEl.textContent = '₹' + origVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
            origEl.style.display = 'inline';
        } else {
            origEl.style.display = 'none';
        }
    }

    // 4. Update Stock Badge
    var stockCount = matchedVariant && matchedVariant.stock !== undefined 
        ? parseInt(matchedVariant.stock, 10) 
        : parseInt(swatch.dataset.stock, 10);

    var soldBadge = card.querySelector('.stock-sold-out-badge, span[style*="background: black"]');
    if (stockCount === 0) {
        if (!soldBadge) {
            soldBadge = document.createElement('span');
            soldBadge.className = 'stock-sold-out-badge';
            soldBadge.style.cssText = 'position: absolute; top: 22px; left: 22px; background: black; color: white; padding: 4px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 4px;';
            soldBadge.textContent = 'SOLD OUT';
            card.appendChild(soldBadge);
        } else {
            soldBadge.style.display = 'inline-block';
        }
    } else {
        if (soldBadge) soldBadge.style.display = 'none';
    }
}

/* ==========================================================================
   2. Product Detail Page Engine
   ========================================================================== */
var currentProductData = null;
var activeColor = null;
var activeSize = null;

function initProductDetailPage() {
    var detailContainer = document.getElementById('product-detail-view');
    if (!detailContainer) return;

    // Parse product variants JSON data
    var dataEl = document.getElementById('product-variants-json');
    if (dataEl) {
        try {
            currentProductData = JSON.parse(dataEl.textContent);
        } catch(err) {
            console.error('Error parsing product variants JSON:', err);
        }
    }

    initMediaGalleryInteractions();
    initProductImageZoomLightbox();
    initColorVariantSelection();
    initSizeSelection();
    initQuantityPicker();
    initAddToCartAction();
    initBuyNowAction();
    initCustomerReviewsInteraction();
}

function initMediaGalleryInteractions() {
    var mainImg = document.getElementById('main-image');
    var mainVideo = document.getElementById('main-video');
    var mainYt = document.getElementById('main-youtube');
    var mainIg = document.getElementById('main-instagram');
    var mainFb = document.getElementById('main-facebook');
    var videoBox = document.getElementById('product-bottom-video-box');
    var videoWrap = document.getElementById('video-player-wrap');
    var livePillText = document.getElementById('video-live-pill-text');
    var extLink = document.getElementById('video-external-link');
    var extText = document.getElementById('video-external-text');
    var soundBtn = document.getElementById('video-sound-toggle-btn');
    var isVideoMuted = true;

    // Helper to extract 11-char YouTube Video ID
    function extractYtId(url) {
        if (!url) return '';
        var m = url.match(/(?:shorts\/|youtu\.be\/|v=|\/embed\/|\/v\/)([a-zA-Z0-9_-]{11})/);
        return m ? m[1] : '';
    }

    // Helper to extract Instagram embed url
    function extractIgEmbedUrl(url) {
        if (!url) return '';
        url = url.trim();
        var m = url.match(/(?:reel|reels|p|tv)\/([a-zA-Z0-9_-]+)/i);
        if (m && m[1]) {
            return 'https://www.instagram.com/reel/' + m[1] + '/embed/';
        }
        var cleanUrl = url.split('?')[0].replace(/\/+$/, '');
        return cleanUrl + '/embed/';
    }

    // Helper to extract Facebook embed url
    function extractFbEmbedUrl(url) {
        if (!url) return '';
        return 'https://www.facebook.com/plugins/video.php?href=' + encodeURIComponent(url.trim()) + '&show_text=0&width=320';
    }

    // Sound toggle handler for Hero-style autoplaying video
    if (soundBtn) {
        soundBtn.addEventListener('click', function() {
            isVideoMuted = !isVideoMuted;
            var soundIcon = document.getElementById('video-sound-icon');
            var soundText = document.getElementById('video-sound-text');

            if (soundIcon) {
                soundIcon.className = isVideoMuted ? 'fa-solid fa-volume-xmark' : 'fa-solid fa-volume-high';
            }
            if (soundText) {
                soundText.textContent = isVideoMuted ? 'Unmute Sound' : 'Mute Sound';
            }

            // For HTML5 Video
            if (mainVideo && mainVideo.style.display !== 'none') {
                mainVideo.muted = isVideoMuted;
            }

            // For YouTube iframe (postMessage API)
            if (mainYt && mainYt.style.display !== 'none' && mainYt.contentWindow) {
                var cmd = isVideoMuted ? 'mute' : 'unMute';
                mainYt.contentWindow.postMessage(JSON.stringify({ event: 'command', func: cmd, args: '' }), '*');
            }
        });
    }

    // Unified Switch Video Source Function (Merged into single frame)
    function switchVideoSource(mediaType, mediaUrl, customEmbed) {
        if (!mediaUrl) {
            var matchingThumb = document.querySelector('.gallery-thumbnail-item[data-type="' + mediaType + '"]');
            if (matchingThumb) {
                mediaUrl = matchingThumb.dataset.url;
                customEmbed = matchingThumb.dataset.embed;
            }
        }

        // Keep header switcher tabs in sync
        var switchTabs = document.querySelectorAll('.video-switch-tab');
        switchTabs.forEach(function(st) {
            if (st.dataset.type === mediaType) {
                st.classList.add('active');
            } else {
                st.classList.remove('active');
            }
        });

        // Keep thumbnail strip in sync
        var strip = document.getElementById('product-gallery-strip');
        if (strip) {
            strip.querySelectorAll('.gallery-thumbnail-item').forEach(function(t) {
                if (t.dataset.type === mediaType) {
                    t.classList.add('active');
                } else {
                    t.classList.remove('active');
                }
            });
        }

        if (mediaType === 'youtube') {
            if (videoWrap) videoWrap.classList.remove('is-instagram');
            var rawYt = mediaUrl || (currentProductData && currentProductData.product ? currentProductData.product.shorts_url : '');
            var ytId = extractYtId(rawYt);
            if (ytId && mainYt) {
                var embedUrl = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1&mute=1&loop=1&playlist=' + ytId + '&playsinline=1&controls=1&enablejsapi=1&rel=0';
                mainYt.src = embedUrl;
                mainYt.style.display = 'block';
                if (mainVideo) {
                    mainVideo.pause();
                    mainVideo.style.display = 'none';
                }
                if (mainIg) {
                    mainIg.src = '';
                    mainIg.style.display = 'none';
                }
                if (mainFb) {
                    mainFb.src = '';
                    mainFb.style.display = 'none';
                }
                if (videoBox) {
                    videoBox.style.display = 'flex';
                }
                var ytCh = currentProductData && currentProductData.product ? (currentProductData.product.shorts_channel || '') : '';
                if (livePillText) livePillText.textContent = 'YouTube Shorts Preview' + (ytCh ? ' (@' + ytCh + ')' : '');
                if (extLink) {
                    extLink.href = rawYt || '#';
                    extLink.style.display = 'inline-flex';
                    if (extText) extText.textContent = 'Watch on YouTube';
                }
                if (soundBtn) soundBtn.style.display = 'inline-flex';
                isVideoMuted = true;
                var soundIcon = document.getElementById('video-sound-icon');
                var soundText = document.getElementById('video-sound-text');
                if (soundIcon) soundIcon.className = 'fa-solid fa-volume-xmark';
                if (soundText) soundText.textContent = 'Unmute Sound';
            }
        } else if (mediaType === 'instagram') {
            if (videoWrap) videoWrap.classList.add('is-instagram');
            var rawIg = mediaUrl || (currentProductData && currentProductData.product ? currentProductData.product.instagram_url : '');
            var igEmbed = customEmbed || extractIgEmbedUrl(rawIg);
            if (igEmbed && mainIg) {
                mainIg.src = igEmbed;
                mainIg.style.display = 'block';
                if (mainVideo) {
                    mainVideo.pause();
                    mainVideo.style.display = 'none';
                }
                if (mainYt) {
                    mainYt.src = '';
                    mainYt.style.display = 'none';
                }
                if (mainFb) {
                    mainFb.src = '';
                    mainFb.style.display = 'none';
                }
                if (videoBox) {
                    videoBox.style.display = 'flex';
                }
                var igCh = currentProductData && currentProductData.product ? (currentProductData.product.instagram_channel || '') : '';
                if (livePillText) livePillText.textContent = 'Instagram Reel Preview' + (igCh ? ' (@' + igCh + ')' : '');
                if (extLink) {
                    extLink.href = rawIg || '#';
                    extLink.style.display = 'inline-flex';
                    if (extText) extText.textContent = 'Watch on Instagram';
                }
                if (soundBtn) soundBtn.style.display = 'none';
            }
        } else if (mediaType === 'facebook') {
            if (videoWrap) videoWrap.classList.remove('is-instagram');
            var rawFb = mediaUrl || (currentProductData && currentProductData.product ? currentProductData.product.facebook_url : '');
            var fbEmbed = customEmbed || extractFbEmbedUrl(rawFb);
            if (fbEmbed && mainFb) {
                mainFb.src = fbEmbed;
                mainFb.style.display = 'block';
                if (mainVideo) {
                    mainVideo.pause();
                    mainVideo.style.display = 'none';
                }
                if (mainYt) {
                    mainYt.src = '';
                    mainYt.style.display = 'none';
                }
                if (mainIg) {
                    mainIg.src = '';
                    mainIg.style.display = 'none';
                }
                if (videoBox) {
                    videoBox.style.display = 'flex';
                }
                var fbCh = currentProductData && currentProductData.product ? (currentProductData.product.facebook_channel || '') : '';
                if (livePillText) livePillText.textContent = 'Facebook Video Preview' + (fbCh ? ' (' + fbCh + ')' : '');
                if (extLink) {
                    extLink.href = rawFb || '#';
                    extLink.style.display = 'inline-flex';
                    if (extText) extText.textContent = 'Watch on Facebook';
                }
                if (soundBtn) soundBtn.style.display = 'none';
            }
        } else if (mediaType === 'video') {
            if (videoWrap) videoWrap.classList.remove('is-instagram');
            if (mainYt) {
                mainYt.src = '';
                mainYt.style.display = 'none';
            }
            if (mainIg) {
                mainIg.src = '';
                mainIg.style.display = 'none';
            }
            if (mainFb) {
                mainFb.src = '';
                mainFb.style.display = 'none';
            }
            if (mainVideo) {
                if (mediaUrl) mainVideo.src = mediaUrl;
                mainVideo.style.display = 'block';
                mainVideo.muted = true;
                mainVideo.play().catch(function() {});
                if (videoBox) {
                    videoBox.style.display = 'flex';
                }
                if (livePillText) livePillText.textContent = 'Saree Video Preview';
                if (extLink) extLink.style.display = 'none';
                if (soundBtn) soundBtn.style.display = 'inline-flex';
                isVideoMuted = true;
                var soundIcon = document.getElementById('video-sound-icon');
                var soundText = document.getElementById('video-sound-text');
                if (soundIcon) soundIcon.className = 'fa-solid fa-volume-xmark';
                if (soundText) soundText.textContent = 'Unmute Sound';
            }
        }
    }

    // Media and Platform Switch Handling
    document.addEventListener('click', function(e) {
        // 1. Direct Platform Switch Tab clicked (e.g. YouTube / Instagram tab button)
        var tab = e.target.closest('.video-switch-tab');
        if (tab) {
            var tabType = tab.dataset.type;
            switchVideoSource(tabType);
            if (videoBox) {
                videoBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            return;
        }

        // 2. Thumbnail clicked in gallery strip
        var thumb = e.target.closest('.gallery-thumbnail-item');
        if (!thumb) return;

        var mediaType = thumb.dataset.type || 'image';
        var mediaUrl = thumb.dataset.url;
        var customEmbed = thumb.dataset.embed;

        if (mediaType === 'image') {
            var strip = thumb.closest('.product-gallery-strip');
            if (strip) {
                strip.querySelectorAll('.gallery-thumbnail-item').forEach(function(t) {
                    t.classList.remove('active');
                });
                thumb.classList.add('active');
            }
            // Image clicked -> updates the TOP image with smooth transition
            if (mainImg) {
                if (mainImg.src !== mediaUrl) {
                    mainImg.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
                    mainImg.style.opacity = '0';
                    setTimeout(function() {
                        mainImg.src = mediaUrl;
                        mainImg.style.display = 'block';
                        setTimeout(function() {
                            mainImg.style.opacity = '1';
                        }, 30);
                    }, 240);
                } else {
                    mainImg.style.display = 'block';
                    mainImg.style.opacity = '1';
                }
                var topBox = mainImg.closest('.product-top-image-box');
                if (topBox) {
                    topBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        } else {
            // Video, YouTube, Instagram or Facebook media clicked
            switchVideoSource(mediaType, mediaUrl, customEmbed);
            if (videoBox) {
                videoBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    });
}

/* ==========================================================================
   Mobile & Desktop Product Image Zoom Lightbox Engine
   ========================================================================== */
function initProductImageZoomLightbox() {
    var lightbox = document.getElementById('product-zoom-lightbox');
    var backdrop = document.getElementById('zoom-lightbox-backdrop');
    var canvas = document.getElementById('zoom-lightbox-canvas');
    var viewport = document.getElementById('zoom-lightbox-viewport');
    var lightboxImg = document.getElementById('zoom-lightbox-img');
    var closeBtn = document.getElementById('zoom-btn-close');
    var zoomInBtn = document.getElementById('zoom-btn-in');
    var zoomOutBtn = document.getElementById('zoom-btn-out');
    var zoomResetBtn = document.getElementById('zoom-btn-reset');
    var zoomLevelLabel = document.getElementById('zoom-level-label');
    var counterEl = document.getElementById('zoom-lightbox-counter');
    var thumbsContainer = document.getElementById('zoom-lightbox-thumbs');
    var prevBtn = document.getElementById('zoom-nav-prev');
    var nextBtn = document.getElementById('zoom-nav-next');
    var touchHint = document.getElementById('zoom-touch-hint');

    var mainImageBox = document.getElementById('product-top-image-box');
    var mainImage = document.getElementById('main-image');

    if (!lightbox || !lightboxImg || !mainImageBox) return;

    var scale = 1;
    var minScale = 1;
    var maxScale = 4.5;
    var translateX = 0;
    var translateY = 0;
    var isDragging = false;
    var startPanX = 0;
    var startPanY = 0;
    var initialPinchDist = null;
    var initialPinchScale = 1;
    var lastTapTime = 0;
    var currentImgIndex = 0;
    var imageList = [];

    // Helper: collect all active images from thumbnail strip or variant data
    function refreshImageList() {
        var list = [];
        var strip = document.getElementById('product-gallery-strip');
        if (strip) {
            var items = strip.querySelectorAll('.gallery-thumbnail-item[data-type="image"]');
            items.forEach(function(item) {
                var url = item.dataset.url;
                if (url && list.indexOf(url) === -1) {
                    list.push(url);
                }
            });
        }
        if (list.length === 0 && mainImage && mainImage.src) {
            list.push(mainImage.src);
        }
        imageList = list;
        return imageList;
    }

    // Expose for external calls on variant update
    window.refreshProductZoomImages = function() {
        refreshImageList();
        renderLightboxThumbs();
        updateCounter();
    };

    // Helper: render bottom thumbnails in lightbox
    function renderLightboxThumbs() {
        if (!thumbsContainer) return;
        thumbsContainer.innerHTML = '';
        if (imageList.length <= 1) {
            thumbsContainer.style.display = 'none';
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
            return;
        }
        thumbsContainer.style.display = 'flex';
        if (prevBtn) prevBtn.style.display = 'flex';
        if (nextBtn) nextBtn.style.display = 'flex';

        imageList.forEach(function(url, idx) {
            var thumb = document.createElement('div');
            thumb.className = 'zoom-thumb-item' + (idx === currentImgIndex ? ' active' : '');
            thumb.dataset.index = idx;
            var img = document.createElement('img');
            img.src = url;
            img.alt = 'Thumbnail ' + (idx + 1);
            thumb.appendChild(img);

            thumb.addEventListener('click', function(e) {
                e.stopPropagation();
                switchToImage(idx);
            });
            thumbsContainer.appendChild(thumb);
        });
    }

    function updateCounter() {
        if (counterEl) {
            counterEl.textContent = (currentImgIndex + 1) + ' / ' + (imageList.length || 1);
        }
    }

    function applyTransform(smooth) {
        if (!canvas) return;
        if (smooth) {
            canvas.classList.add('smooth-transition');
        } else {
            canvas.classList.remove('smooth-transition');
        }

        // Clamp translation based on current scale and dimensions
        if (scale <= 1) {
            translateX = 0;
            translateY = 0;
            scale = 1;
        } else if (viewport) {
            var rect = viewport.getBoundingClientRect();
            var maxTranslateX = (rect.width * (scale - 1)) / 2;
            var maxTranslateY = (rect.height * (scale - 1)) / 2;
            translateX = Math.max(Math.min(translateX, maxTranslateX), -maxTranslateX);
            translateY = Math.max(Math.min(translateY, maxTranslateY), -maxTranslateY);
        }

        canvas.style.transform = 'translate3d(' + translateX + 'px, ' + translateY + 'px, 0) scale(' + scale + ')';
        if (zoomLevelLabel) {
            zoomLevelLabel.textContent = Math.round(scale * 100) + '%';
        }
    }

    function resetZoom(smooth) {
        scale = 1;
        translateX = 0;
        translateY = 0;
        applyTransform(smooth !== false);
    }

    function switchToImage(index) {
        if (index < 0) index = imageList.length - 1;
        if (index >= imageList.length) index = 0;
        currentImgIndex = index;

        var newUrl = imageList[currentImgIndex];
        if (newUrl) {
            lightboxImg.src = newUrl;
            resetZoom(false);
            updateCounter();

            // Highlight active thumb
            if (thumbsContainer) {
                var thumbs = thumbsContainer.querySelectorAll('.zoom-thumb-item');
                thumbs.forEach(function(th, i) {
                    if (i === currentImgIndex) {
                        th.classList.add('active');
                        th.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    } else {
                        th.classList.remove('active');
                    }
                });
            }
        }
    }

    function openLightbox(initialScale) {
        refreshImageList();
        var currentSrc = mainImage ? mainImage.src : '';
        var idx = imageList.indexOf(currentSrc);
        currentImgIndex = idx >= 0 ? idx : 0;

        lightboxImg.src = imageList[currentImgIndex] || currentSrc;

        renderLightboxThumbs();
        updateCounter();

        scale = initialScale && initialScale > 1 ? initialScale : 1;
        translateX = 0;
        translateY = 0;
        applyTransform(false);

        lightbox.style.display = 'flex';
        // Force reflow
        void lightbox.offsetWidth;
        lightbox.classList.add('active');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.classList.add('zoom-modal-open');
        var mobileNav = document.querySelector('.mobile-bottom-app-bar');
        if (mobileNav) mobileNav.style.setProperty('display', 'none', 'important');

        // Show touch hint for 3 seconds then fade out
        if (touchHint) {
            touchHint.classList.remove('fade-out');
            setTimeout(function() {
                touchHint.classList.add('fade-out');
            }, 2800);
        }
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        lightbox.setAttribute('aria-hidden', 'true');
        setTimeout(function() {
            lightbox.style.display = 'none';
            resetZoom(false);
            document.body.classList.remove('zoom-modal-open');
            var mobileNav = document.querySelector('.mobile-bottom-app-bar');
            if (mobileNav) mobileNav.style.removeProperty('display');
        }, 250);
    }

    // Direct event bindings on main image box
    mainImageBox.addEventListener('click', function(e) {
        e.preventDefault();
        openLightbox(1);
    });

    // Touch event on main image box: Catch 2-finger pinch & double-tap before browser zooms whole page!
    var lastMainBoxTap = 0;
    mainImageBox.addEventListener('touchstart', function(e) {
        if (e.touches.length >= 2) {
            e.preventDefault(); // CRITICAL: Stop mobile browser from zooming entire website!
            openLightbox(1.5);
            return;
        }
        var now = Date.now();
        if (now - lastMainBoxTap < 320) {
            e.preventDefault(); // Stop mobile browser double-tap whole-page zoom!
            openLightbox(2.0);
            lastMainBoxTap = 0;
            return;
        }
        lastMainBoxTap = now;
    }, { passive: false });

    mainImageBox.addEventListener('touchmove', function(e) {
        if (e.touches.length >= 2) {
            e.preventDefault(); // Stop mobile browser pinch-zoom
        }
    }, { passive: false });

    // Inside Lightbox Event Bindings
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (backdrop) backdrop.addEventListener('click', closeLightbox);

    if (zoomInBtn) {
        zoomInBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            scale = Math.min(scale + 0.5, maxScale);
            applyTransform(true);
        });
    }

    if (zoomOutBtn) {
        zoomOutBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            scale = Math.max(scale - 0.5, minScale);
            if (scale === 1) { translateX = 0; translateY = 0; }
            applyTransform(true);
        });
    }

    if (zoomResetBtn) {
        zoomResetBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            resetZoom(true);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            switchToImage(currentImgIndex - 1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            switchToImage(currentImgIndex + 1);
        });
    }

    // Keyboard support (Escape to close, Left/Right arrow to switch)
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        if (e.key === 'Escape' || e.keyCode === 27) {
            closeLightbox();
        } else if (e.key === 'ArrowLeft' || e.keyCode === 37) {
            switchToImage(currentImgIndex - 1);
        } else if (e.key === 'ArrowRight' || e.keyCode === 39) {
            switchToImage(currentImgIndex + 1);
        }
    });

    // Touch Pinch & Pan Gestures inside Viewport
    if (viewport) {
        viewport.addEventListener('touchstart', function(e) {
            if (e.touches.length === 2) {
                // 2 fingers -> Pinch zoom
                e.preventDefault();
                initialPinchDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                initialPinchScale = scale;
            } else if (e.touches.length === 1) {
                // 1 finger -> Check double tap or start pan
                var now = Date.now();
                if (now - lastTapTime < 300) {
                    // Double tap detected!
                    e.preventDefault();
                    if (scale > 1.2) {
                        resetZoom(true);
                    } else {
                        scale = 2.5;
                        var rect = viewport.getBoundingClientRect();
                        var tapX = e.touches[0].clientX - rect.left - rect.width / 2;
                        var tapY = e.touches[0].clientY - rect.top - rect.height / 2;
                        translateX = -tapX * 0.8;
                        translateY = -tapY * 0.8;
                        applyTransform(true);
                    }
                    lastTapTime = 0;
                    return;
                }
                lastTapTime = now;

                if (scale > 1) {
                    isDragging = true;
                    startPanX = e.touches[0].clientX - translateX;
                    startPanY = e.touches[0].clientY - translateY;
                }
            }
        }, { passive: false });

        viewport.addEventListener('touchmove', function(e) {
            if (e.touches.length === 2 && initialPinchDist) {
                // Pinching
                e.preventDefault();
                var currentDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                var pinchRatio = currentDist / initialPinchDist;
                scale = Math.min(Math.max(initialPinchScale * pinchRatio, minScale), maxScale);
                applyTransform(false);
            } else if (e.touches.length === 1 && isDragging && scale > 1) {
                // Panning when zoomed
                e.preventDefault();
                translateX = e.touches[0].clientX - startPanX;
                translateY = e.touches[0].clientY - startPanY;
                applyTransform(false);
            }
        }, { passive: false });

        viewport.addEventListener('touchend', function(e) {
            if (e.touches.length < 2) {
                initialPinchDist = null;
            }
            if (e.touches.length === 0) {
                isDragging = false;
                if (scale < 1.05) {
                    resetZoom(true);
                } else {
                    applyTransform(true);
                }
            }
        });

        // Mouse Drag & Wheel for Desktop in Lightbox
        var isMouseDragging = false;
        var mouseStartX = 0;
        var mouseStartY = 0;

        viewport.addEventListener('mousedown', function(e) {
            if (e.button !== 0) return;
            if (scale > 1) {
                isMouseDragging = true;
                mouseStartX = e.clientX - translateX;
                mouseStartY = e.clientY - translateY;
                viewport.style.cursor = 'grabbing';
            }
        });

        window.addEventListener('mousemove', function(e) {
            if (isMouseDragging && scale > 1) {
                translateX = e.clientX - mouseStartX;
                translateY = e.clientY - mouseStartY;
                applyTransform(false);
            }
        });

        window.addEventListener('mouseup', function() {
            if (isMouseDragging) {
                isMouseDragging = false;
                if (viewport) viewport.style.cursor = scale > 1 ? 'grab' : 'default';
                applyTransform(true);
            }
        });

        viewport.addEventListener('wheel', function(e) {
            e.preventDefault();
            var delta = e.deltaY < 0 ? 0.3 : -0.3;
            scale = Math.min(Math.max(scale + delta, minScale), maxScale);
            if (scale === 1) {
                translateX = 0;
                translateY = 0;
            }
            applyTransform(true);
        }, { passive: false });
    }

    // Expose global open helper
    window.openProductZoomLightbox = openLightbox;
}

function initColorVariantSelection() {
    var swatches = document.querySelectorAll('.detail-color-swatch-btn');
    if (swatches.length === 0) return;

    swatches.forEach(function(btn) {
        btn.addEventListener('click', function() {
            swatches.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');

            activeColor = this.dataset.color;
            var label = document.getElementById('selected-color-name');
            if (label) label.textContent = activeColor;

            onVariantOptionChanged();
        });
    });

    // Set first color as active initially
    var firstActive = document.querySelector('.detail-color-swatch-btn.active') || swatches[0];
    if (firstActive) {
        activeColor = firstActive.dataset.color;
        var label = document.getElementById('selected-color-name');
        if (label) label.textContent = activeColor;
    }
}

function initSizeSelection() {
    var sizeBtns = document.querySelectorAll('.detail-size-btn');
    if (sizeBtns.length === 0) return;

    sizeBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (this.classList.contains('disabled')) return;
            sizeBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');

            activeSize = this.dataset.size;
            var label = document.getElementById('selected-size-name');
            if (label) label.textContent = activeSize;

            onVariantOptionChanged();
        });
    });

    var firstActive = document.querySelector('.detail-size-btn.active') || sizeBtns[0];
    if (firstActive) {
        activeSize = firstActive.dataset.size;
        var label = document.getElementById('selected-size-name');
        if (label) label.textContent = activeSize;
    }
}

function onVariantOptionChanged() {
    if (!currentProductData || !currentProductData.variants) return;

    // Find best matching variant
    var variants = currentProductData.variants;
    var matchedVariant = null;

    // Helper to test if a variant matches target size (direct or in comma-separated list)
    function sizeMatches(variantSize, targetSize) {
        if (!variantSize || !targetSize) return false;
        var sList = variantSize.split(',').map(function(s) { return s.trim().toLowerCase(); });
        return sList.includes(targetSize.toLowerCase());
    }

    // Match both color and size
    if (activeColor && activeSize) {
        matchedVariant = variants.find(function(v) {
            return (v.color_name || '').toLowerCase() === activeColor.toLowerCase() &&
                   sizeMatches(v.size, activeSize);
        });
    }

    // Fallback: match by size FIRST so size-specific variant price takes precedence
    if (!matchedVariant && activeSize) {
        matchedVariant = variants.find(function(v) {
            return sizeMatches(v.size, activeSize);
        });
    }

    // Fallback: match by color
    if (!matchedVariant && activeColor) {
        matchedVariant = variants.find(function(v) {
            return (v.color_name || '').toLowerCase() === activeColor.toLowerCase();
        });
    }

    if (!matchedVariant && variants.length > 0) {
        matchedVariant = variants[0];
    }

    if (matchedVariant) {
        applyVariantToDetailPage(matchedVariant);
    }
}

function applyVariantToDetailPage(variant) {
    // 1. Update Price
    var priceEl = document.getElementById('detail-product-price');
    var origEl = document.getElementById('detail-product-original-price');
    var discountBadge = document.getElementById('detail-discount-badge');

    var basePrice = currentProductData.product ? parseFloat(currentProductData.product.price) : 0;
    var baseOrigPrice = currentProductData.product && currentProductData.product.original_price ? parseFloat(currentProductData.product.original_price) : 0;

    var salePrice = variant.sale_price ? parseFloat(variant.sale_price) : basePrice;
    var origPrice = variant.original_price ? parseFloat(variant.original_price) : baseOrigPrice;

    if (priceEl) {
        priceEl.setAttribute('data-base-price', salePrice);
        if (typeof window.formatCurrencyPrice === 'function') {
            priceEl.textContent = window.formatCurrencyPrice(salePrice);
        } else {
            priceEl.textContent = '₹' + salePrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    if (origEl) {
        if (origPrice > salePrice) {
            origEl.setAttribute('data-base-price', origPrice);
            if (typeof window.formatCurrencyPrice === 'function') {
                origEl.textContent = window.formatCurrencyPrice(origPrice);
            } else {
                origEl.textContent = '₹' + origPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
            origEl.style.display = 'inline';
        } else {
            origEl.style.display = 'none';
        }
    }

    if (discountBadge) {
        if (origPrice > salePrice) {
            var pct = Math.round(((origPrice - salePrice) / origPrice) * 100);
            discountBadge.textContent = 'SAVE ' + pct + '%';
            discountBadge.style.display = 'inline-block';
        } else {
            discountBadge.style.display = 'none';
        }
    }

    // 2. Update Stock Status & Action buttons
    var stockStatusEl = document.getElementById('detail-stock-status');
    var cartBtn = document.getElementById('detail-add-cart-btn');
    var buyNowBtn = document.getElementById('detail-buy-now-btn');
    var qtyInput = document.getElementById('product-qty-input');
    var stockQty = parseInt(variant.stock, 10);

    if (qtyInput) {
        qtyInput.max = stockQty > 0 ? stockQty : 1;
        if (parseInt(qtyInput.value, 10) > stockQty && stockQty > 0) {
            qtyInput.value = stockQty;
        }
    }

    if (stockStatusEl) {
        if (stockQty > 0) {
            stockStatusEl.className = 'stock-status in-stock';
            stockStatusEl.textContent = 'In Stock (' + stockQty + ' available)';
            if (cartBtn) {
                cartBtn.removeAttribute('disabled');
                cartBtn.style.background = 'var(--primary-color, #7a1530)';
                cartBtn.style.cursor = 'pointer';
                cartBtn.innerHTML = '<i class="fa-solid fa-cart-shopping"></i> Add to Cart';
            }
            if (buyNowBtn) {
                buyNowBtn.removeAttribute('disabled');
                buyNowBtn.style.background = 'linear-gradient(135deg, #d4af37 0%, #b8860b 100%)';
                buyNowBtn.style.cursor = 'pointer';
            }
        } else {
            stockStatusEl.className = 'stock-status out-of-stock';
            stockStatusEl.textContent = 'Out of Stock';
            if (cartBtn) {
                cartBtn.setAttribute('disabled', 'disabled');
                cartBtn.style.background = '#94a3b8';
                cartBtn.style.cursor = 'not-allowed';
                cartBtn.innerHTML = '<i class="fa-solid fa-ban"></i> Out of Stock';
            }
            if (buyNowBtn) {
                buyNowBtn.setAttribute('disabled', 'disabled');
                buyNowBtn.style.background = '#94a3b8';
                buyNowBtn.style.cursor = 'not-allowed';
            }
        }
    }

    // 3. Update Hidden Variant ID
    var hiddenVariantIdInput = document.getElementById('selected-variant-id-input');
    if (hiddenVariantIdInput) {
        hiddenVariantIdInput.value = variant.id;
    }

    // 4. Update Gallery Thumbnails for this Variant
    updateGalleryForVariant(variant);
}

function updateGalleryForVariant(variant) {
    var strip = document.getElementById('product-gallery-strip');
    var mainImg = document.getElementById('main-image');
    var mainVideo = document.getElementById('main-video');
    if (!strip) return;

    var imagesToShow = [];

    // If variant has assigned photos, display them
    if (variant.images && variant.images.length > 0) {
        imagesToShow = variant.images.slice();
        // Also preserve any video, youtube, instagram & facebook from fallbackMedia at the end of the strip
        if (currentProductData && currentProductData.fallbackMedia) {
            currentProductData.fallbackMedia.forEach(function(m) {
                if (m.media_type === 'video' || m.media_type === 'youtube' || m.media_type === 'instagram' || m.media_type === 'facebook') {
                    imagesToShow.push(m);
                }
            });
        }
    } else if (currentProductData && currentProductData.fallbackMedia) {
        // Fallback to general gallery
        imagesToShow = currentProductData.fallbackMedia.slice();
    }

    if (imagesToShow.length === 0) return;

    // Reset video player
    if (mainVideo) {
        mainVideo.pause();
        mainVideo.style.display = 'none';
    }
    if (mainImg) {
        mainImg.style.display = 'block';
    }

    // Determine fallback poster image for video
    var posterImg = currentProductData && currentProductData.primaryImgUrl ? currentProductData.primaryImgUrl : (imagesToShow[0] ? (imagesToShow[0].url || imagesToShow[0].media_url) : '');

    // Rebuild thumbnails strip
    strip.innerHTML = '';
    imagesToShow.forEach(function(item, idx) {
        var thumb = document.createElement('div');
        thumb.className = 'gallery-thumbnail-item' + (idx === 0 ? ' active' : '');
        thumb.dataset.type = item.media_type || 'image';
        thumb.dataset.url = item.url || item.media_url;

        var img = document.createElement('img');
        if (item.media_type === 'video' || item.media_type === 'youtube' || item.media_type === 'instagram' || item.media_type === 'facebook') {
            img.src = posterImg || (getAppBaseUrl() + '/assets/images/placeholder.jpg');
            img.alt = item.media_type === 'youtube' ? 'YouTube Short' : (item.media_type === 'instagram' ? 'Instagram Reel' : (item.media_type === 'facebook' ? 'Facebook Video' : 'Product Video'));
        } else {
            img.src = item.url || item.media_url;
            img.alt = 'Thumbnail ' + (idx + 1);
        }
        img.onerror = function() {
            this.onerror = null;
            this.src = getAppBaseUrl() + '/assets/images/placeholder.jpg';
        };
        thumb.appendChild(img);

        if (item.media_type === 'video') {
            var icon = document.createElement('div');
            icon.className = 'thumb-video-icon';
            icon.innerHTML = '<i class="fa-solid fa-play"></i>';
            thumb.appendChild(icon);
        } else if (item.media_type === 'youtube') {
            var ytIcon = document.createElement('div');
            ytIcon.className = 'thumb-video-icon';
            ytIcon.style.cssText = 'background: rgba(220, 38, 38, 0.95); color: #fff;';
            ytIcon.innerHTML = '<i class="fa-brands fa-youtube"></i>';
            thumb.appendChild(ytIcon);
        } else if (item.media_type === 'instagram') {
            var igIcon = document.createElement('div');
            igIcon.className = 'thumb-video-icon';
            igIcon.style.cssText = 'background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff;';
            igIcon.innerHTML = '<i class="fa-brands fa-instagram"></i>';
            thumb.appendChild(igIcon);
            if (item.embed_url) thumb.dataset.embed = item.embed_url;
        } else if (item.media_type === 'facebook') {
            var fbIcon = document.createElement('div');
            fbIcon.className = 'thumb-video-icon';
            fbIcon.style.cssText = 'background: #1877f2; color: #fff;';
            fbIcon.innerHTML = '<i class="fa-brands fa-facebook"></i>';
            thumb.appendChild(fbIcon);
            if (item.embed_url) thumb.dataset.embed = item.embed_url;
        }

        strip.appendChild(thumb);

        // Set first item as main media
        if (idx === 0) {
            if (item.media_type === 'video') {
                if (mainImg) mainImg.style.display = 'none';
                if (mainVideo) {
                    mainVideo.src = item.url || item.media_url;
                    mainVideo.style.display = 'block';
                }
            } else {
                if (mainImg) {
                    var newImgUrl = item.url || item.media_url;
                    if (mainImg.src !== newImgUrl) {
                        mainImg.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
                        mainImg.style.opacity = '0';
                        setTimeout(function() {
                            mainImg.src = newImgUrl;
                            mainImg.style.display = 'block';
                            setTimeout(function() {
                                mainImg.style.opacity = '1';
                            }, 30);
                        }, 240);
                    } else {
                        mainImg.style.display = 'block';
                        mainImg.style.opacity = '1';
                    }
                }
            }
        }
    });
}

/* ==========================================================================
   3. Helpers & Storefront Cart / Buy Now / Review Engine
   ========================================================================== */
function getAppBaseUrl() {
    if (window.appBaseUrl) {
        return window.appBaseUrl.replace(/\/$/, '');
    }
    var baseMeta = document.querySelector('base');
    if (baseMeta && baseMeta.href) {
        return baseMeta.href.replace(/\/$/, '');
    }
    var pathname = window.location.pathname;
    if (pathname.indexOf('/sri/public') !== -1) {
        return window.location.origin + '/sri/public';
    }
    if (pathname.indexOf('/sri') !== -1) {
        return window.location.origin + '/sri';
    }
    return window.location.origin;
}

function showStoreToast(message, type) {
    var existing = document.querySelector('.store-toast');
    if (existing) existing.remove();

    var toast = document.createElement('div');
    toast.className = 'store-toast';
    var iconClass = type === 'error' ? 'fa-circle-xmark' : 'fa-circle-check';
    var iconColor = type === 'error' ? '#ef4444' : '#22c55e';
    toast.innerHTML = '<i class="fa-solid ' + iconClass + '" style="color: ' + iconColor + '; font-size: 18px;"></i><span>' + message + '</span>';
    document.body.appendChild(toast);

    setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3500);
}

function updateHeaderCartBadge(count) {
    var badge = document.getElementById('header-cart-badge');
    var cartLink = document.querySelector('a[href*="/cart"]');
    if (!badge && cartLink) {
        badge = document.createElement('span');
        badge.id = 'header-cart-badge';
        badge.className = 'header-cart-badge';
        cartLink.style.position = 'relative';
        cartLink.appendChild(badge);
    }
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-flex' : 'none';
    }
}

/* ==========================================================================
   Interactive Cart Drawer Engine
   ========================================================================== */
function initCartDrawer() {
    var closeBtn = document.getElementById('cart-drawer-close-btn');
    var backdrop = document.getElementById('cart-drawer-backdrop');

    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeCartDrawer();
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', function(e) {
            e.preventDefault();
            closeCartDrawer();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCartDrawer();
        }
    });

    // Header and mobile bottom bar cart toggle delegation
    document.addEventListener('click', function(e) {
        var cartToggle = e.target.closest('#header-cart-toggle') || e.target.closest('.mobile-bar-cart-tab') || e.target.closest('a[href$="/cart"][title="Shopping Cart"]');
        if (cartToggle && !window.location.pathname.endsWith('/checkout') && !window.location.pathname.endsWith('/cart')) {
            e.preventDefault();
            openCartDrawer();
        }
    });
}

function openCartDrawer(event, preloadData) {
    if (event) {
        event.preventDefault();
    }
    var drawer = document.getElementById('cart-drawer');
    var backdrop = document.getElementById('cart-drawer-backdrop');
    if (!drawer) return;

    drawer.classList.add('active');
    drawer.setAttribute('aria-hidden', 'false');
    if (backdrop) {
        backdrop.classList.add('active');
        backdrop.setAttribute('aria-hidden', 'false');
    }
    document.body.style.overflow = 'hidden';

    if (preloadData) {
        renderCartDrawer(preloadData);
    } else {
        fetchAndRenderCartDrawer();
    }
}

function closeCartDrawer() {
    var drawer = document.getElementById('cart-drawer');
    var backdrop = document.getElementById('cart-drawer-backdrop');
    if (drawer) {
        drawer.classList.remove('active');
        drawer.setAttribute('aria-hidden', 'true');
    }
    if (backdrop) {
        backdrop.classList.remove('active');
        backdrop.setAttribute('aria-hidden', 'true');
    }
    document.body.style.overflow = '';
}

window.openCartDrawer = openCartDrawer;
window.closeCartDrawer = closeCartDrawer;

function fetchAndRenderCartDrawer() {
    var loading = document.getElementById('cart-drawer-loading');
    if (loading) loading.style.display = 'flex';

    var baseUrl = getAppBaseUrl();
    fetch(baseUrl + '/cart/data', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(function(data) {
        if (loading) loading.style.display = 'none';
        renderCartDrawer(data || {});
    })
    .catch(function(err) {
        if (loading) loading.style.display = 'none';
        console.warn('Cart data fetch failed, rendering empty fallback:', err);
        renderCartDrawer({ cart_count: 0, items: [], subtotal: 0 });
    });
}

function renderCartDrawer(data) {
    var itemsList = document.getElementById('cart-drawer-items-list');
    var emptyState = document.getElementById('cart-drawer-empty');
    var footer = document.getElementById('cart-drawer-footer');
    var countBadge = document.getElementById('cart-drawer-count-badge');
    var subtotalVal = document.getElementById('cart-drawer-subtotal-val');
    var shippingBar = document.getElementById('cart-drawer-shipping-bar');
    var shippingLabel = document.getElementById('cart-drawer-shipping-label');
    var progressFill = document.getElementById('cart-drawer-progress-fill');

    var count = data.cart_count || 0;
    var subtotal = parseFloat(data.subtotal) || 0;
    var threshold = parseFloat(data.free_shipping_threshold) || 1000;
    var isFree = !!data.is_free_shipping || (subtotal >= threshold && subtotal > 0);
    var items = data.items || [];

    if (countBadge) countBadge.textContent = count;
    updateHeaderCartBadge(count);

    var savedCurr = 'INR';
    try {
        var raw = localStorage.getItem('sri_user_country_currency');
        if (raw) {
            var parsed = JSON.parse(raw);
            if (parsed && parsed.currency) savedCurr = parsed.currency;
        }
    } catch(e) {}

    var formatPrice = function(val) {
        if (window.formatCurrencyPrice) {
            return window.formatCurrencyPrice(val, savedCurr);
        }
        return '₹' + parseFloat(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    if (subtotalVal) {
        subtotalVal.setAttribute('data-base-price', subtotal);
        subtotalVal.textContent = formatPrice(subtotal);
    }

    // Shipping meter
    if (shippingBar && shippingLabel && progressFill) {
        if (isFree) {
            shippingLabel.className = 'cart-drawer-shipping-label unlocked';
            shippingLabel.innerHTML = '<i class="fa-solid fa-circle-check" style="color: #16a34a;"></i><span>You unlocked FREE Express Shipping!</span>';
            progressFill.style.width = '100%';
            progressFill.classList.add('full');
        } else {
            var diff = Math.max(0, threshold - subtotal);
            var pct = Math.min(100, Math.round((subtotal / threshold) * 100));
            shippingLabel.className = 'cart-drawer-shipping-label';
            shippingLabel.innerHTML = '<i class="fa-solid fa-truck-fast"></i><span>Add ' + formatPrice(diff) + ' more for FREE Express Shipping!</span>';
            progressFill.style.width = pct + '%';
            progressFill.classList.remove('full');
        }
    }

    if (items.length === 0) {
        if (itemsList) itemsList.innerHTML = '';
        if (emptyState) emptyState.style.display = 'block';
        if (footer) footer.style.display = 'none';
        return;
    }

    if (emptyState) emptyState.style.display = 'none';
    if (footer) footer.style.display = 'block';

    if (itemsList) {
        var html = '';
        var baseUrl = getAppBaseUrl();
        items.forEach(function(item) {
            var itemPrice = formatPrice(item.price);
            var itemOrig = item.original_price && parseFloat(item.original_price) > parseFloat(item.price) ? formatPrice(item.original_price) : '';

            html += '<div class="cart-drawer-item-card" id="drawer-item-' + item.key + '">';
            html += '  <a href="' + baseUrl + '/product/' + encodeURIComponent(item.product_slug) + '">';
            html += '    <img src="' + item.image_url + '" onerror="this.onerror=null;this.src=\'' + baseUrl + '/assets/images/placeholder.jpg\';" alt="' + escapeHtml(item.product_name) + '" class="cart-drawer-item-thumb">';
            html += '  </a>';
            html += '  <div class="cart-drawer-item-content">';
            html += '    <h4 class="cart-drawer-item-title"><a href="' + baseUrl + '/product/' + encodeURIComponent(item.product_slug) + '">' + escapeHtml(item.product_name) + '</a></h4>';
            if (item.variant_name) {
                html += '    <div class="cart-drawer-item-variant"><i class="fa-solid fa-palette"></i> ' + escapeHtml(item.variant_name) + '</div>';
            }
            html += '    <div class="cart-drawer-item-price-row">';
            html += '      <span class="cart-drawer-item-sale">' + itemPrice + '</span>';
            if (itemOrig) {
                html += '      <span class="cart-drawer-item-orig">' + itemOrig + '</span>';
            }
            html += '    </div>';
            html += '    <div class="cart-drawer-item-controls">';
            html += '      <div class="cart-drawer-qty-picker">';
            html += '        <button type="button" class="cart-drawer-qty-btn" onclick="updateDrawerItemQty(\'' + item.key + '\', ' + Math.max(1, item.quantity - 1) + ')"><i class="fa-solid fa-minus"></i></button>';
            html += '        <span class="cart-drawer-qty-num">' + item.quantity + '</span>';
            html += '        <button type="button" class="cart-drawer-qty-btn" onclick="updateDrawerItemQty(\'' + item.key + '\', ' + (item.quantity + 1) + ')"><i class="fa-solid fa-plus"></i></button>';
            html += '      </div>';
            html += '      <button type="button" class="cart-drawer-item-remove-btn" onclick="removeDrawerItem(\'' + item.key + '\')"><i class="fa-regular fa-trash-can"></i> Remove</button>';
            html += '    </div>';
            html += '  </div>';
            html += '</div>';
        });
        itemsList.innerHTML = html;
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function updateDrawerItemQty(key, newQty) {
    var baseUrl = getAppBaseUrl();
    var formData = new URLSearchParams();
    formData.append('key', key);
    formData.append('quantity', newQty);

    fetch(baseUrl + '/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData.toString()
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        renderCartDrawer(data);
    })
    .catch(function(err) {
        showStoreToast('Failed to update cart quantity.', 'error');
    });
}
window.updateDrawerItemQty = updateDrawerItemQty;

function removeDrawerItem(key) {
    var baseUrl = getAppBaseUrl();
    var formData = new URLSearchParams();
    formData.append('key', key);

    fetch(baseUrl + '/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData.toString()
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        renderCartDrawer(data);
        showStoreToast('Item removed from cart.', 'success');
    })
    .catch(function(err) {
        showStoreToast('Failed to remove item.', 'error');
    });
}
window.removeDrawerItem = removeDrawerItem;

/* Wishlist Engine */
function initWishlistButtons() {
    var wishlistBtns = document.querySelectorAll('.detail-wishlist-btn');
    if (wishlistBtns.length === 0) return;

    var savedList = [];
    try {
        savedList = JSON.parse(localStorage.getItem('ssptex_wishlist') || '[]');
    } catch(e) { savedList = []; }

    wishlistBtns.forEach(function(btn) {
        var prodId = btn.dataset.productId;
        var icon = btn.querySelector('i');
        if (savedList.indexOf(prodId) !== -1) {
            btn.classList.add('active');
            if (icon) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
            }
        }

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var currList = [];
            try {
                currList = JSON.parse(localStorage.getItem('ssptex_wishlist') || '[]');
            } catch(e) { currList = []; }

            var idx = currList.indexOf(prodId);
            if (idx === -1) {
                currList.push(prodId);
                btn.classList.add('active');
                if (icon) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                }
                showStoreToast('Added to your Wishlist!', 'success');
            } else {
                currList.splice(idx, 1);
                btn.classList.remove('active');
                if (icon) {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                }
                showStoreToast('Removed from your Wishlist.', 'success');
            }
            localStorage.setItem('ssptex_wishlist', JSON.stringify(currList));
        });
    });
}

function initQuantityPicker() {
    var minusBtn = document.getElementById('qty-minus');
    var plusBtn = document.getElementById('qty-plus');
    var input = document.getElementById('product-qty-input');
    if (!minusBtn || !plusBtn || !input) return;

    minusBtn.addEventListener('click', function() {
        var val = parseInt(input.value, 10) || 1;
        if (val > 1) {
            input.value = val - 1;
        }
    });

    plusBtn.addEventListener('click', function() {
        var val = parseInt(input.value, 10) || 1;
        var max = parseInt(input.max, 10) || 999;
        if (val < max) {
            input.value = val + 1;
        } else {
            showStoreToast('Maximum available quantity is ' + max, 'error');
        }
    });
}

function initAddToCartAction() {
    var addBtn = document.getElementById('detail-add-cart-btn');
    if (!addBtn) return;

    addBtn.addEventListener('click', function(e) {
        e.preventDefault();
        var productId = addBtn.dataset.productId;
        var variantIdEl = document.getElementById('selected-variant-id-input');
        var variantId = variantIdEl ? variantIdEl.value : '';
        var qtyEl = document.getElementById('product-qty-input');
        var quantity = qtyEl ? parseInt(qtyEl.value, 10) || 1 : 1;

        if (!productId) return;

        addBtn.disabled = true;
        var originalHtml = addBtn.innerHTML;
        addBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';

        var formData = new URLSearchParams();
        formData.append('product_id', productId);
        if (variantId) formData.append('variant_id', variantId);
        if (activeSize) formData.append('size', activeSize);
        formData.append('quantity', quantity);

        var baseUrl = getAppBaseUrl();

        fetch(baseUrl + '/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData.toString()
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            addBtn.disabled = false;
            addBtn.innerHTML = originalHtml;
            if (data.status === 'success') {
                updateHeaderCartBadge(data.cart_count);
                showStoreToast(data.message || 'Item added to your cart!', 'success');
                openCartDrawer(null, data);
            } else {
                showStoreToast(data.message || 'Could not add item to cart.', 'error');
            }
        })
        .catch(function(err) {
            addBtn.disabled = false;
            addBtn.innerHTML = originalHtml;
            showStoreToast('Failed to add item to cart. Please try again.', 'error');
        });
    });
}

function initBuyNowAction() {
    var buyNowBtn = document.getElementById('detail-buy-now-btn');
    if (!buyNowBtn) return;

    buyNowBtn.addEventListener('click', function(e) {
        e.preventDefault();
        var productId = buyNowBtn.dataset.productId;
        var variantIdEl = document.getElementById('selected-variant-id-input');
        var variantId = variantIdEl ? variantIdEl.value : '';
        var qtyEl = document.getElementById('product-qty-input');
        var quantity = qtyEl ? parseInt(qtyEl.value, 10) || 1 : 1;

        if (!productId) return;

        buyNowBtn.disabled = true;
        buyNowBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

        var baseUrl = getAppBaseUrl();

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = baseUrl + '/buy-now';

        var pInput = document.createElement('input');
        pInput.type = 'hidden';
        pInput.name = 'product_id';
        pInput.value = productId;
        form.appendChild(pInput);

        if (variantId) {
            var vInput = document.createElement('input');
            vInput.type = 'hidden';
            vInput.name = 'variant_id';
            vInput.value = variantId;
            form.appendChild(vInput);
        }

        if (activeSize) {
            var sInput = document.createElement('input');
            sInput.type = 'hidden';
            sInput.name = 'size';
            sInput.value = activeSize;
            form.appendChild(sInput);
        }

        var qInput = document.createElement('input');
        qInput.type = 'hidden';
        qInput.name = 'quantity';
        qInput.value = quantity;
        form.appendChild(qInput);

        document.body.appendChild(form);
        form.submit();
    });
}

function initCustomerReviewsInteraction() {
    var toggleBtn = document.getElementById('btn-toggle-review-form');
    var closeBtn = document.getElementById('btn-close-review-form');
    var cancelBtn = document.getElementById('btn-cancel-review');
    var formCard = document.getElementById('review-submission-card');
    var starPicker = document.getElementById('interactive-star-picker');
    var ratingInput = document.getElementById('review-rating-value');

    if (toggleBtn && formCard) {
        toggleBtn.addEventListener('click', function() {
            if (formCard.style.display === 'none' || !formCard.style.display) {
                formCard.style.display = 'block';
                formCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                formCard.style.display = 'none';
            }
        });
    }

    if (closeBtn && formCard) {
        closeBtn.addEventListener('click', function() {
            formCard.style.display = 'none';
        });
    }

    if (cancelBtn && formCard) {
        cancelBtn.addEventListener('click', function() {
            formCard.style.display = 'none';
        });
    }

    if (starPicker && ratingInput) {
        var stars = starPicker.querySelectorAll('i');
        stars.forEach(function(star) {
            star.addEventListener('mouseover', function() {
                var val = parseInt(this.dataset.value, 10);
                stars.forEach(function(s) {
                    var sVal = parseInt(s.dataset.value, 10);
                    if (sVal <= val) {
                        s.classList.add('hovered');
                    } else {
                        s.classList.remove('hovered');
                    }
                });
            });

            star.addEventListener('mouseout', function() {
                stars.forEach(function(s) {
                    s.classList.remove('hovered');
                });
            });

            star.addEventListener('click', function() {
                var val = parseInt(this.dataset.value, 10);
                ratingInput.value = val;
                stars.forEach(function(s) {
                    var sVal = parseInt(s.dataset.value, 10);
                    if (sVal <= val) {
                        s.classList.add('selected');
                        s.classList.replace('fa-regular', 'fa-solid');
                    } else {
                        s.classList.remove('selected');
                        s.classList.replace('fa-solid', 'fa-regular');
                    }
                });
            });
        });
    }

    window.openReviewFormDirectly = function() {
        if (formCard) {
            formCard.style.display = 'block';
            formCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            var nameInput = document.getElementById('rev-name');
            if (nameInput) setTimeout(function() { nameInput.focus(); }, 350);
        }
    };

    if (window.location.hash === '#customer-reviews-section' || window.location.hash === '#review-submission-card') {
        setTimeout(function() {
            if (window.openReviewFormDirectly) {
                window.openReviewFormDirectly();
            }
        }, 400);
    }
}

function initCardAddToCartButtons() {
    // Intercept card individual "Add Cart" and "Buy Now / Checkout" buttons
    document.addEventListener('click', function(e) {
        var addBtn = e.target.closest('.card-add-cart-btn');
        if (addBtn) {
            e.preventDefault();
            e.stopPropagation();

            var card = addBtn.closest('.product-card') || addBtn.closest('.shorts-product-card');
            var productId = addBtn.dataset.productId || (card ? card.dataset.id : null);
            var variantId = addBtn.dataset.variantId || (card ? card.dataset.activeVariantId : null);

            if (!productId) return;

            var originalHtml = addBtn.innerHTML;
            addBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
            addBtn.disabled = true;

            var formData = new URLSearchParams();
            formData.append('product_id', productId);
            if (variantId) formData.append('variant_id', variantId);
            var activePill = card ? card.querySelector('.card-size-pill.active') : null;
            var selectedSize = (card && card.dataset.selectedSize) ? card.dataset.selectedSize : (activePill ? (activePill.dataset.size || activePill.textContent.trim()) : '');
            if (selectedSize) {
                formData.append('size', selectedSize);
            }
            formData.append('quantity', 1);

            var baseUrl = getAppBaseUrl();

            fetch(baseUrl + '/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData.toString()
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                addBtn.innerHTML = originalHtml;
                addBtn.disabled = false;
                if (data.status === 'success') {
                    updateHeaderCartBadge(data.cart_count);
                    showStoreToast(data.message || 'Item added to your cart!', 'success');
                    openCartDrawer(null, data);
                } else {
                    showStoreToast(data.message || 'Could not add item to cart.', 'error');
                }
            })
            .catch(function() {
                addBtn.innerHTML = originalHtml;
                addBtn.disabled = false;
                showStoreToast('Failed to add item to cart.', 'error');
            });
            return;
        }

        // Intercept card individual "Buy Now" button
        var buyBtn = e.target.closest('.card-buy-now-btn');
        if (buyBtn) {
            e.preventDefault();
            e.stopPropagation();

            var card = buyBtn.closest('.product-card') || buyBtn.closest('.shorts-product-card');
            var productId = buyBtn.dataset.productId || (card ? card.dataset.id : null);
            var variantId = buyBtn.dataset.variantId || (card ? card.dataset.activeVariantId : null);
            var activePill = card ? card.querySelector('.card-size-pill.active') : null;
            var selectedSize = (card && card.dataset.selectedSize) ? card.dataset.selectedSize : (activePill ? (activePill.dataset.size || activePill.textContent.trim()) : '');

            if (!productId) return;

            buyBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            buyBtn.style.pointerEvents = 'none';

            var baseUrl = getAppBaseUrl();
            window.location.href = baseUrl + '/checkout?product_id=' + productId + (variantId ? '&variant_id=' + variantId : '') + (selectedSize ? '&size=' + encodeURIComponent(selectedSize) : '');
            return;
        }
    });

    // Re-render cart drawer if user changes currency while drawer is open
    window.addEventListener('currencyChanged', function() {
        var drawer = document.getElementById('cart-drawer');
        if (drawer && drawer.classList.contains('active') && typeof fetchCartDrawerData === 'function') {
            fetchCartDrawerData();
        }
    });

    // Interactive Product Card Size Pill Click Handler
    document.addEventListener('click', function(e) {
        var pill = e.target.closest('.card-size-pill');
        if (pill) {
            e.preventDefault();
            e.stopPropagation();
            var card = pill.closest('.product-card, .shorts-product-item');
            if (card) {
                card.querySelectorAll('.card-size-pill').forEach(function(p) { p.classList.remove('active'); });
                pill.classList.add('active');
                card.dataset.selectedSize = pill.dataset.size || pill.textContent.trim();
                
                // Update Buy Now button link with selected size if present
                var buyBtn = card.querySelector('.card-buy-now-btn');
                if (buyBtn && buyBtn.href) {
                    try {
                        var u = new URL(buyBtn.href, window.location.origin);
                        u.searchParams.set('size', card.dataset.selectedSize);
                        buyBtn.href = u.pathname + u.search;
                    } catch(err) {}
                }
            }
        }
    });
}


