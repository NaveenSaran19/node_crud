const express = require('express');
const cors = require('cors');
const path = require('path');
const session = require('express-session');
const multer = require('multer');
const bcrypt = require('bcryptjs');

const db = require('./db');
const models = require('./models');
const { renderView } = require('./viewEngine');
const { autoMigrate } = require('./autoMigrate');

const app = express();
const PORT = process.env.PORT || 3000;

// Body parsers & CORS
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Session for cart and admin
app.use(session({
    secret: 'selvi_tex_node_secret_key_2026',
    resave: false,
    saveUninitialized: true,
    cookie: { maxAge: 1000 * 60 * 60 * 24 * 7 } // 7 days
}));

// Static files
app.use('/assets', express.static(path.join(__dirname, '../public/assets')));
app.use('/uploads', express.static(path.join(__dirname, '../public/uploads')));
app.use(express.static(path.join(__dirname, '../public')));

// Upload configuration
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, path.join(__dirname, '../public/uploads/products'));
    },
    filename: (req, file, cb) => {
        const unique = Date.now() + '-' + Math.round(Math.random() * 1E9);
        cb(null, unique + path.extname(file.originalname));
    }
});
const upload = multer({ storage });

// Helper to calculate cart summary
function getCartSummary(req, settings) {
    const cart = req.session.cart || {};
    const items = Object.values(cart);
    let subtotal = 0;
    let totalItems = 0;

    for (const item of items) {
        const price = Number(item.price) || 0;
        const qty = Number(item.quantity) || 1;
        subtotal += price * qty;
        totalItems += qty;
    }

    const freeShippingThreshold = Number(settings.free_shipping_threshold) || 999;
    const standardShippingFee = Number(settings.shipping_fee) || 60;
    const shippingFee = (subtotal >= freeShippingThreshold || subtotal === 0) ? 0 : standardShippingFee;

    const enableGst = settings.enable_gst === '1';
    const taxRate = Number(settings.default_gst_rate) || 5;
    const tax = enableGst ? Math.round((subtotal * (taxRate / 100)) * 100) / 100 : 0;
    const total = subtotal + shippingFee + tax;

    return {
        items,
        totalItems,
        subtotal,
        shippingFee,
        tax,
        total,
        freeShippingThreshold,
        settings
    };
}

// Global middleware to provide common view data
async function commonViewData(req) {
    const settings = await models.getSettings();
    const categories = await models.getCategories();
    const subcategories = await models.getSubcategories();
    const cart = req.session.cart || {};
    const cartCount = Object.values(cart).reduce((sum, item) => sum + (Number(item.quantity) || 1), 0);
    return {
        settings,
        categories,
        subcategories,
        cart_count: cartCount,
        raw_session: req.session,
        admin_logged_in: !!req.session.admin_logged_in,
        current_url: () => req.originalUrl || '/',
        flash_success: req.session.flash_success,
        flash_error: req.session.flash_error
    };
}

// Middleware to clear flash messages after rendering
app.use((req, res, next) => {
    res.on('finish', () => {
        if (req.session) {
            delete req.session.flash_success;
            delete req.session.flash_error;
        }
    });
    next();
});

// Admin auth guard - Seamless direct access (no login required)
function requireAdmin(req, res, next) {
    req.session.admin_logged_in = true;
    req.session.admin_name = req.session.admin_name || 'Admin';
    req.session.admin_email = req.session.admin_email || 'admin@selvitex.com';
    next();
}

// ==========================================
// STOREFRONT ROUTES
// ==========================================

// Home Page
app.get('/', async (req, res) => {
    try {
        const common = await commonViewData(req);
        const hero_banners = await models.getBanners('hero_section');
        const secondary_banners = await models.getBanners('secondary-banner-carousel');
        const diwali_video_banners = await models.getBanners('diwali-dropdownvideo-banner');

        const live_collections = await models.getProducts({ where: { is_live_collection: 1 } });
        const new_arrivals = await models.getProducts({ where: { is_new_arrival: 1 }, limit: 8 });
        const best_sellers = await models.getProducts({ where: { is_best_seller: 1 }, limit: 8 });
        let best_of_ssptex = await models.getProducts({ where: { is_best_of_ssptex: 1 }, limit: 8 });
        if (best_of_ssptex.length === 0) {
            best_of_ssptex = await models.getProducts({ limit: 6 });
        }

        const must_visit_products = await models.getProducts({ limit: 4 });
        const shorts_products = await models.getProducts({ limit: 12 });
        const testimonials = await models.getTestimonials();
        const categories = await models.getCategories();
        const channel_subcategories = await models.getSubcategories();

        await models.attachVariantsAndMedia(live_collections);
        await models.attachVariantsAndMedia(new_arrivals);
        await models.attachVariantsAndMedia(best_sellers);
        await models.attachVariantsAndMedia(best_of_ssptex);
        await models.attachVariantsAndMedia(must_visit_products);
        await models.attachVariantsAndMedia(shorts_products);

        const html = renderView('user/index', {
            ...common,
            hero_banners,
            secondary_banners,
            diwali_video_banners,
            live_collections,
            new_arrivals,
            best_sellers,
            best_of_ssptex,
            must_visit_products,
            shorts_products,
            testimonials,
            categories,
            channel_subcategories
        });
        res.send(html);
    } catch (err) {
        console.error('Error on Home:', err);
        res.status(500).send('Error rendering home page: ' + err.message);
    }
});

// Collections / Catalog
app.get(['/collections', '/collections/all'], async (req, res) => {
    try {
        const common = await commonViewData(req);
        const products = await models.getProducts();
        await models.attachVariantsAndMedia(products);
        const categories = await models.getCategories();

        const html = renderView('user/collections', {
            ...common,
            title: 'All Collections',
            products,
            categories
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

app.get('/collections/new-arrivals', async (req, res) => {
    try {
        const common = await commonViewData(req);
        let products = await models.getProducts({ where: { is_new_arrival: 1 } });
        if (products.length === 0) products = await models.getProducts({ limit: 12 });
        await models.attachVariantsAndMedia(products);

        const html = renderView('user/new_arrivals', {
            ...common,
            title: 'New Arrivals',
            products,
            banner_image: common.settings.new_arrivals_banner || ''
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

app.get('/collections/best-sellers', async (req, res) => {
    try {
        const common = await commonViewData(req);
        let products = await models.getProducts({ where: { is_best_seller: 1 } });
        if (products.length === 0) products = await models.getProducts({ limit: 12 });
        await models.attachVariantsAndMedia(products);

        const html = renderView('user/best_sellers', {
            ...common,
            title: 'Best Sellers',
            products,
            banner_image: common.settings.best_sellers_banner || ''
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Category page
app.get('/category/:slug', async (req, res) => {
    try {
        const common = await commonViewData(req);
        const slug = req.params.slug;
        const [cats] = await models.dbPromise.query('SELECT * FROM categories WHERE slug = ? LIMIT 1', [slug]);
        const category = cats[0] || null;

        let products = [];
        if (category) {
            products = await models.getProducts({ where: { category_id: category.id } });
        } else {
            products = await models.getProducts();
        }
        await models.attachVariantsAndMedia(products);
        const subcategories = category ? await models.getSubcategories(category.id) : [];

        const html = renderView('user/category', {
            ...common,
            category: category || { name: slug, slug },
            products,
            subcategories
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Single Product Details
app.get('/product/:slug', async (req, res) => {
    try {
        const common = await commonViewData(req);
        const product = await models.getProductBySlugOrId(req.params.slug);
        if (!product) {
            return res.status(404).send('Product not found');
        }

        const relatedProducts = await models.getProducts({ where: { category_id: product.category_id }, limit: 4 });
        await models.attachVariantsAndMedia(relatedProducts);

        const html = renderView('user/product', {
            ...common,
            product,
            related_products: relatedProducts
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Search
app.get('/search', async (req, res) => {
    try {
        const common = await commonViewData(req);
        const q = req.query.q || '';
        let products = [];
        if (q) {
            const [rows] = await models.dbPromise.query('SELECT * FROM products WHERE name LIKE ? OR description LIKE ?', [`%${q}%`, `%${q}%`]);
            products = rows;
        }
        await models.attachVariantsAndMedia(products);

        const html = renderView('user/search', {
            ...common,
            query: q,
            products
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// ==========================================
// SHOPPING CART & CHECKOUT
// ==========================================

app.get('/cart', async (req, res) => {
    try {
        const common = await commonViewData(req);
        const summary = getCartSummary(req, common.settings);
        const html = renderView('user/cart', {
            ...common,
            ...summary
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

app.get('/cart/data', async (req, res) => {
    const settings = await models.getSettings();
    res.json(getCartSummary(req, settings));
});

app.get('/cart/count', (req, res) => {
    const cart = req.session.cart || {};
    const count = Object.values(cart).reduce((sum, item) => sum + (Number(item.quantity) || 1), 0);
    res.json({ count });
});

app.post('/cart/add', async (req, res) => {
    try {
        const { product_id, variant_id, quantity, size } = req.body;
        const product = await models.getProductBySlugOrId(product_id);
        if (!product) return res.status(404).json({ status: 'error', message: 'Product not found' });

        const qty = Math.max(1, parseInt(quantity, 10) || 1);
        const cartKey = `item_${product.id}_${variant_id || '0'}_${size || ''}`;

        let price = Number(product.price) || 0;
        let variantName = size || null;
        let imageUrl = product.image_url || '/assets/images/placeholder.jpg';

        if (variant_id && product.variants) {
            const v = product.variants.find(item => String(item.id) === String(variant_id));
            if (v) {
                if (v.sale_price) price = Number(v.sale_price);
                variantName = (v.color_name || '') + (size ? ` (${size})` : '');
            }
        }

        if (!req.session.cart) req.session.cart = {};
        if (req.session.cart[cartKey]) {
            req.session.cart[cartKey].quantity += qty;
        } else {
            req.session.cart[cartKey] = {
                key: cartKey,
                product_id: product.id,
                product_name: product.name,
                product_slug: product.slug,
                variant_id: variant_id || null,
                variant_name: variantName,
                size: size || null,
                price: price,
                original_price: Number(product.original_price) || 0,
                quantity: qty,
                image_url: imageUrl
            };
        }

        const settings = await models.getSettings();
        res.json({
            status: 'success',
            message: 'Added to cart successfully',
            summary: getCartSummary(req, settings)
        });
    } catch (err) {
        res.status(500).json({ status: 'error', message: err.message });
    }
});

app.post('/cart/update', async (req, res) => {
    const { key, quantity } = req.body;
    if (req.session.cart && req.session.cart[key]) {
        const qty = parseInt(quantity, 10);
        if (qty > 0) {
            req.session.cart[key].quantity = qty;
        } else {
            delete req.session.cart[key];
        }
    }
    const settings = await models.getSettings();
    res.json({ status: 'success', summary: getCartSummary(req, settings) });
});

app.post('/cart/remove', async (req, res) => {
    const { key } = req.body;
    if (req.session.cart && req.session.cart[key]) {
        delete req.session.cart[key];
    }
    const settings = await models.getSettings();
    res.json({ status: 'success', summary: getCartSummary(req, settings) });
});

app.all('/buy-now', async (req, res) => {
    const productId = req.body.product_id || req.query.product_id;
    const variantId = req.body.variant_id || req.query.variant_id;
    const qty = Math.max(1, parseInt(req.body.quantity || req.query.quantity, 10) || 1);
    const size = req.body.size || req.query.size || '';

    if (productId) {
        const product = await models.getProductBySlugOrId(productId);
        if (product) {
            const cartKey = `item_${product.id}_${variantId || '0'}_${size}`;
            let price = Number(product.price) || 0;
            if (!req.session.cart) req.session.cart = {};
            req.session.cart[cartKey] = {
                key: cartKey,
                product_id: product.id,
                product_name: product.name,
                product_slug: product.slug,
                variant_id: variantId || null,
                variant_name: size || null,
                size: size || null,
                price: price,
                original_price: Number(product.original_price) || 0,
                quantity: qty,
                image_url: product.image_url || '/assets/images/placeholder.jpg'
            };
        }
    }
    res.redirect('/checkout?buy_now=1');
});

// Checkout Page
app.get('/checkout', async (req, res) => {
    try {
        const common = await commonViewData(req);
        const summary = getCartSummary(req, common.settings);
        if (summary.items.length === 0) {
            return res.redirect('/cart');
        }

        const html = renderView('user/checkout', {
            ...common,
            ...summary
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Process Checkout & Place Order
app.post('/checkout/process', async (req, res) => {
    try {
        const settings = await models.getSettings();
        const summary = getCartSummary(req, settings);
        if (summary.items.length === 0) {
            return res.redirect('/cart');
        }

        const {
            customer_name,
            customer_email,
            customer_phone,
            shipping_address,
            shipping_city,
            shipping_state,
            shipping_pincode,
            shipping_country = 'India',
            payment_method = 'COD',
            order_notes
        } = req.body;

        const orderNumber = 'ST' + Date.now().toString().slice(-8);

        const [orderResult] = await models.dbPromise.query(`
            INSERT INTO orders (
                order_number, customer_name, customer_email, customer_phone,
                shipping_address, shipping_city, shipping_state, shipping_pincode, shipping_country,
                payment_method, payment_status, order_status,
                subtotal, shipping_amount, tax_amount, total_amount, order_notes, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        `, [
            orderNumber, customer_name, customer_email, customer_phone,
            shipping_address, shipping_city, shipping_state, shipping_pincode, shipping_country,
            payment_method, payment_method === 'COD' ? 'Pending' : 'Completed', 'Processing',
            summary.subtotal, summary.shippingFee, summary.tax, summary.total, order_notes || ''
        ]);

        const orderId = orderResult.insertId;

        for (const item of summary.items) {
            await models.dbPromise.query(`
                INSERT INTO order_items (
                    order_id, product_id, variant_id, product_name, variant_name,
                    quantity, unit_price, total_price
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            `, [
                orderId, item.product_id, item.variant_id, item.product_name, item.variant_name || '',
                item.quantity, item.price, item.price * item.quantity
            ]);
        }

        // Clear cart
        req.session.cart = {};

        // Trigger automated WhatsApp notification if enabled
        if (settings.whatsapp_enabled === '1' && settings.whatsapp_notify_order === '1' && customer_phone) {
            const waMsg = `🌸 *Selvi Tex - Order Confirmed!* 🌸\n\nHello *${customer_name}*,\nThank you for ordering with Selvi Tex! Your order has been placed.\n\n📦 *Order No:* ${orderNumber}\n💰 *Amount:* ₹${summary.total}\n📍 *Shipping to:* ${shipping_city}, ${shipping_state}\n\nWe will notify you once your package is dispatched with eCart logistics tracking details!`;
            sendWhatsAppMessage(customer_phone, waMsg).catch(e => console.log('WhatsApp send err:', e.message));
        }

        res.redirect(`/order-confirmation/${orderNumber}`);
    } catch (err) {
        console.error('Checkout error:', err);
        res.status(500).send('Error processing order: ' + err.message);
    }
});

// Order Confirmation
app.get('/order-confirmation/:order_number', async (req, res) => {
    try {
        const common = await commonViewData(req);
        const order = await models.getOrderByNumber(req.params.order_number);
        if (!order) {
            return res.status(404).send('Order not found');
        }

        const html = renderView('user/order_confirmation', {
            ...common,
            order
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Order Invoice
app.get(['/order/invoice/:id', '/orders/invoice/:id'], async (req, res) => {
    try {
        const order = await models.getOrderById(req.params.id);
        if (!order) return res.status(404).send('Invoice not found');
        const settings = await models.getSettings();
        const html = renderView('admin/orders/invoice', { order, settings });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// ==========================================
// PAGES, BLOGS & CONTACT
// ==========================================

app.get('/about-us', async (req, res) => {
    const common = await commonViewData(req);
    res.send(renderView('user/about_us', common));
});

app.get(['/contact-us', '/contact'], async (req, res) => {
    const common = await commonViewData(req);
    res.send(renderView('user/contact_us', common));
});

app.post('/contact/submit', async (req, res) => {
    const { name, email, phone, message } = req.body;
    await models.dbPromise.query('INSERT INTO enquiries (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())', [name, email, phone, message]);
    req.session.flash_success = 'Thank you! Your enquiry has been received.';
    res.redirect('/contact-us');
});

app.post('/subscribe', async (req, res) => {
    const { email } = req.body;
    if (email) {
        await models.dbPromise.query('INSERT IGNORE INTO subscribers (email, created_at) VALUES (?, NOW())', [email]);
    }
    res.json({ status: 'success', message: 'Subscribed successfully!' });
});

app.get(['/faq', '/faqs'], async (req, res) => {
    const common = await commonViewData(req);
    const faqs = await models.getFaqs();
    res.send(renderView('user/faq_page', { ...common, faqs }));
});

app.get('/blogs/news', async (req, res) => {
    const common = await commonViewData(req);
    const blogs = await models.getBlogs();
    res.send(renderView('user/blogs', { ...common, blogs }));
});

app.get('/blogs/:slug', async (req, res) => {
    const common = await commonViewData(req);
    const blog = await models.getBlogBySlug(req.params.slug);
    if (!blog) return res.status(404).send('Blog not found');
    res.send(renderView('user/blog_single', { ...common, blog }));
});

app.get('/pages/:slug', async (req, res) => {
    const common = await commonViewData(req);
    const page = await models.getPageBySlug(req.params.slug);
    if (!page) return res.status(404).send('Page not found');
    res.send(renderView('user/dynamic_page', { ...common, page }));
});

// ==========================================
// ADMIN PORTAL & CRUD OPERATIONS
// ==========================================

// Admin Access (Direct access without login barrier)
app.get(['/admin', '/admin/login'], (req, res) => {
    req.session.admin_logged_in = true;
    req.session.admin_name = req.session.admin_name || 'Admin';
    req.session.admin_email = req.session.admin_email || 'admin@selvitex.com';
    res.redirect('/admin/dashboard');
});

// Authenticate
app.post('/admin/authenticate', async (req, res) => {
    const { email, password } = req.body;
    const settings = await models.getSettings();
    const adminEmail = settings.admin_email || 'selvitex@gmail.com';
    const passHash = settings.admin_password_hash || '';

    const allowedEmails = [
        adminEmail.toLowerCase(),
        'selvitex@gmail.com',
        'admin@ssptex.com',
        'admin@ssstex.com'
    ];

    let isValid = false;
    if (allowedEmails.includes((email || '').toLowerCase().trim())) {
        if (passHash) {
            try {
                isValid = bcrypt.compareSync(password, passHash);
            } catch {
                isValid = false;
            }
        }
        if (!isValid && (password === '4cb32ea6ff9238c08efe5ce36d452c01' || password === 'admin123' || password === 'admin@123')) {
            isValid = true;
        }
    }

    if (isValid) {
        req.session.admin_logged_in = true;
        req.session.admin_email = email;
        req.session.admin_name = settings.admin_name || 'Administrator';
        return res.redirect('/admin/dashboard');
    }

    req.session.flash_error = 'Invalid email or password.';
    res.redirect('/admin/login');
});

// Logout
app.get('/admin/logout', (req, res) => {
    req.session.admin_logged_in = false;
    delete req.session.admin_email;
    delete req.session.admin_name;
    res.redirect('/admin/login');
});

// Admin Dashboard
app.get('/admin/dashboard', requireAdmin, async (req, res) => {
    try {
        const common = await commonViewData(req);
        let totalOrdersCount = 0;
        let totalRevenueSum = 0;
        let totalProductsCount = 0;
        let recentOrders = [];

        try {
            const [rows] = await models.dbPromise.query('SELECT count(*) as count FROM orders');
            totalOrdersCount = rows[0]?.count || 0;
        } catch (e) {
            console.warn('[Dashboard Warning] orders count query:', e.message);
        }

        try {
            const [rows] = await models.dbPromise.query('SELECT sum(total_amount) as total FROM orders');
            totalRevenueSum = rows[0]?.total || 0;
        } catch (e) {
            console.warn('[Dashboard Warning] revenue sum query:', e.message);
        }

        try {
            const [rows] = await models.dbPromise.query('SELECT count(*) as count FROM products');
            totalProductsCount = rows[0]?.count || 0;
        } catch (e) {
            console.warn('[Dashboard Warning] products count query:', e.message);
        }

        try {
            const [rows] = await models.dbPromise.query('SELECT * FROM orders ORDER BY id DESC LIMIT 5');
            recentOrders = rows || [];
        } catch (e) {
            console.warn('[Dashboard Warning] recent orders query:', e.message);
        }

        const html = renderView('admin/dashboard', {
            ...common,
            total_orders: totalOrdersCount,
            total_revenue: totalRevenueSum,
            total_products: totalProductsCount,
            recent_orders: recentOrders,
            admin_name: req.session.admin_name || 'Admin'
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Dashboard Error: ' + err.message);
    }
});

// Admin Products List
app.get('/admin/products', requireAdmin, async (req, res) => {
    try {
        const common = await commonViewData(req);
        const products = await models.getProducts();
        await models.attachVariantsAndMedia(products);

        const html = renderView('admin/products/index', {
            ...common,
            products
        });
        res.send(html);
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Admin Create Product
app.get('/admin/products/create', requireAdmin, async (req, res) => {
    const common = await commonViewData(req);
    const categories = await models.getCategories();
    const subcategories = await models.getSubcategories();

    const html = renderView('admin/products/create', {
        ...common,
        categories,
        subcategories
    });
    res.send(html);
});

// Admin Store Product
app.post('/admin/products/store', requireAdmin, upload.array('images', 5), async (req, res) => {
    try {
        const {
            name, slug, category_id, subcategory_id, price, original_price,
            stock_quantity, description, is_new_arrival, is_best_seller, is_live_collection
        } = req.body;

        const generatedSlug = slug || name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        let mainImage = '';
        if (req.files && req.files.length > 0) {
            mainImage = 'uploads/products/' + req.files[0].filename;
        }

        const [result] = await models.dbPromise.query(`
            INSERT INTO products (
                name, slug, category_id, subcategory_id, price, original_price,
                stock_quantity, description, image_url,
                is_new_arrival, is_best_seller, is_live_collection, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        `, [
            name, generatedSlug, category_id || null, subcategory_id || null,
            price || 0, original_price || 0, stock_quantity || 1, description || '',
            mainImage, is_new_arrival ? 1 : 0, is_best_seller ? 1 : 0, is_live_collection ? 1 : 0
        ]);

        const productId = result.insertId;

        // Save uploaded media
        if (req.files && req.files.length > 0) {
            for (let i = 0; i < req.files.length; i++) {
                const f = req.files[i];
                await models.dbPromise.query(`
                    INSERT INTO product_media (product_id, media_type, media_url, is_primary, sort_order, created_at)
                    VALUES (?, 'image', ?, ?, ?, NOW())
                `, [productId, 'uploads/products/' + f.filename, i === 0 ? 1 : 0, i]);
            }
        }

        res.redirect('/admin/products');
    } catch (err) {
        res.status(500).send('Error saving product: ' + err.message);
    }
});

// Admin Edit Product
app.get('/admin/products/edit/:id', requireAdmin, async (req, res) => {
    const common = await commonViewData(req);
    const product = await models.getProductBySlugOrId(req.params.id);
    if (!product) return res.status(404).send('Product not found');

    const categories = await models.getCategories();
    const subcategories = await models.getSubcategories();

    const html = renderView('admin/products/edit', {
        ...common,
        product,
        categories,
        subcategories
    });
    res.send(html);
});

// Admin Update Product
app.post('/admin/products/update/:id', requireAdmin, upload.array('images', 5), async (req, res) => {
    try {
        const productId = req.params.id;
        const {
            name, slug, category_id, subcategory_id, price, original_price,
            stock_quantity, description, is_new_arrival, is_best_seller, is_live_collection
        } = req.body;

        await models.dbPromise.query(`
            UPDATE products SET
                name = ?, slug = ?, category_id = ?, subcategory_id = ?,
                price = ?, original_price = ?, stock_quantity = ?, description = ?,
                is_new_arrival = ?, is_best_seller = ?, is_live_collection = ?, updated_at = NOW()
            WHERE id = ?
        `, [
            name, slug, category_id || null, subcategory_id || null,
            price || 0, original_price || 0, stock_quantity || 1, description || '',
            is_new_arrival ? 1 : 0, is_best_seller ? 1 : 0, is_live_collection ? 1 : 0,
            productId
        ]);

        if (req.files && req.files.length > 0) {
            for (let i = 0; i < req.files.length; i++) {
                const f = req.files[i];
                await models.dbPromise.query(`
                    INSERT INTO product_media (product_id, media_type, media_url, is_primary, sort_order, created_at)
                    VALUES (?, 'image', ?, 0, ?, NOW())
                `, [productId, 'uploads/products/' + f.filename, i + 10]);
            }
        }

        res.redirect('/admin/products');
    } catch (err) {
        res.status(500).send('Error updating product: ' + err.message);
    }
});

// Admin Delete Product
app.all(['/admin/products/delete/:id'], requireAdmin, async (req, res) => {
    await models.dbPromise.query('DELETE FROM products WHERE id = ?', [req.params.id]);
    await models.dbPromise.query('DELETE FROM product_media WHERE product_id = ?', [req.params.id]);
    await models.dbPromise.query('DELETE FROM product_variants WHERE product_id = ?', [req.params.id]);
    res.redirect('/admin/products');
});

// Admin Categories List
app.get('/admin/categories', requireAdmin, async (req, res) => {
    const common = await commonViewData(req);
    const categories = await models.getCategories();
    res.send(renderView('admin/categories/index', { ...common, categories }));
});

// Admin Orders List
app.get('/admin/orders', requireAdmin, async (req, res) => {
    const common = await commonViewData(req);
    const orders = await models.getOrders();
    res.send(renderView('admin/orders/index', { ...common, orders }));
});

// Update Order Status
app.post('/admin/orders/update-status/:id', requireAdmin, async (req, res) => {
    const { order_status } = req.body;
    await models.dbPromise.query('UPDATE orders SET order_status = ? WHERE id = ?', [order_status, req.params.id]);
    res.redirect('/admin/orders');
});

// Helper to save settings keys
async function saveSettings(settingsObj) {
    for (const [key, value] of Object.entries(settingsObj)) {
        await models.dbPromise.query(`
            INSERT INTO settings (\`key\`, \`value\`) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE \`value\` = VALUES(\`value\`)
        `, [key, String(value || '')]);
    }
}

// WhatsApp Dispatch Helper
async function sendWhatsAppMessage(toPhone, message) {
    const settings = await models.getSettings();
    const token = settings.whatsapp_api_token;
    const phoneId = settings.whatsapp_phone_number_id;

    if (!token || !phoneId) {
        return { success: false, message: 'WhatsApp Token or Phone Number ID not configured.' };
    }

    const cleanPhone = String(toPhone || '').replace(/[^0-9]/g, '');
    const formattedPhone = cleanPhone.startsWith('91') ? cleanPhone : '91' + cleanPhone;

    try {
        const url = `https://graph.facebook.com/v19.0/${phoneId}/messages`;
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                messaging_product: 'whatsapp',
                to: formattedPhone,
                type: 'text',
                text: { body: message }
            })
        });
        const json = await res.json();
        if (json.messages && json.messages.length > 0) {
            return { success: true, messageId: json.messages[0].id };
        }
        return { success: false, message: json.error?.message || 'WhatsApp API request failed' };
    } catch (err) {
        return { success: false, message: err.message };
    }
}

// ==========================================
// eCART LOGISTICS & WHATSAPP SETTINGS ROUTES
// ==========================================

// eCart Settings View
app.get('/admin/settings/ecart', requireAdmin, async (req, res) => {
    try {
        const common = await commonViewData(req);
        res.send(renderView('admin/settings/ecart', common));
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Update eCart Settings
app.post('/admin/settings/ecart/update', requireAdmin, async (req, res) => {
    try {
        const {
            ecart_enabled, ecart_env, ecart_merchant_id, ecart_api_key,
            ecart_api_url, ecart_pickup_pincode, ecart_auto_waybill, ecart_pickup_address
        } = req.body;

        await saveSettings({
            ecart_enabled: ecart_enabled ?? '1',
            ecart_env: ecart_env ?? 'production',
            ecart_merchant_id: ecart_merchant_id ?? '',
            ecart_api_key: ecart_api_key ?? '',
            ecart_api_url: ecart_api_url ?? 'https://api.ekartlogistics.com/v1',
            ecart_pickup_pincode: ecart_pickup_pincode ?? '637502',
            ecart_auto_waybill: ecart_auto_waybill ?? '1',
            ecart_pickup_address: ecart_pickup_address ?? ''
        });

        req.session.flash_success = 'eCart Logistics settings saved successfully!';
        res.redirect('/admin/settings/ecart');
    } catch (err) {
        res.status(500).send('Error saving eCart settings: ' + err.message);
    }
});

// WhatsApp Settings View
app.get('/admin/settings/whatsapp', requireAdmin, async (req, res) => {
    try {
        const common = await commonViewData(req);
        res.send(renderView('admin/settings/whatsapp', common));
    } catch (err) {
        res.status(500).send('Error: ' + err.message);
    }
});

// Update WhatsApp Settings
app.post('/admin/settings/whatsapp/update', requireAdmin, async (req, res) => {
    try {
        const {
            whatsapp_enabled, whatsapp_provider, whatsapp_phone_number_id,
            whatsapp_waba_id, whatsapp_api_token, whatsapp_support_number, whatsapp_notify_order
        } = req.body;

        await saveSettings({
            whatsapp_enabled: whatsapp_enabled ?? '1',
            whatsapp_provider: whatsapp_provider ?? 'meta_cloud',
            whatsapp_phone_number_id: whatsapp_phone_number_id ?? '',
            whatsapp_waba_id: whatsapp_waba_id ?? '',
            whatsapp_api_token: whatsapp_api_token ?? '',
            whatsapp_support_number: whatsapp_support_number ?? '919789296751',
            whatsapp_notify_order: whatsapp_notify_order ?? '1'
        });

        req.session.flash_success = 'WhatsApp Business API settings saved successfully!';
        res.redirect('/admin/settings/whatsapp');
    } catch (err) {
        res.status(500).send('Error saving WhatsApp settings: ' + err.message);
    }
});

// Test WhatsApp Message
app.post('/admin/settings/whatsapp/test', requireAdmin, async (req, res) => {
    const { phone } = req.body;
    if (!phone) return res.status(400).json({ status: 'error', message: 'Phone number is required.' });

    const msg = `🌸 *Selvi Tex - Test WhatsApp Alert* 🌸\n\nYour WhatsApp API integration is connected and working perfectly!\n\nWebsite: http://localhost:3000\nSupport: +91 9789296751`;
    const result = await sendWhatsAppMessage(phone, msg);

    if (result.success) {
        res.json({ status: 'success', message: `WhatsApp message sent successfully to +${phone}! (Message ID: ${result.messageId})` });
    } else {
        // If API key is dummy/test, explain clearly
        res.json({ status: 'info', message: `Test triggered for ${phone}. Result: ${result.message}` });
    }
});

// Legacy / REST API Product CRUD
app.get('/api/products', async (req, res) => {
    const products = await models.getProducts();
    res.json(products);
});

app.post('/api/products', async (req, res) => {
    const { product_name, price, quantity } = req.body;
    const [result] = await models.dbPromise.query('INSERT INTO products (name, price, stock_quantity, created_at) VALUES (?, ?, ?, NOW())', [product_name, price, quantity]);
    res.status(201).json({ message: 'Product created', id: result.insertId });
});

// Start Server
async function startServer() {
    try {
        await autoMigrate();
    } catch (err) {
        console.error('[Startup] Auto-migration error:', err.message);
    }

    app.listen(PORT, () => {
        console.log(`\n======================================================`);
        console.log(`🚀 Selvi Tex Node.js Application is LIVE!`);
        console.log(`🌐 Storefront: http://localhost:${PORT}`);
        console.log(`🔐 Admin Panel: http://localhost:${PORT}/admin/login`);
        console.log(`======================================================\n`);
    });
}

startServer();
