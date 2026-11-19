const db = require('./db');
const dbPromise = db.promise();

async function getSettings() {
    const [rows] = await dbPromise.query('SELECT `key`, `value` FROM settings');
    const settings = {};
    for (const r of rows) {
        settings[r.key] = r.value;
    }
    return settings;
}

async function getBanners(position = null) {
    if (position) {
        const [rows] = await dbPromise.query('SELECT * FROM banners WHERE position = ? AND (is_active = 1 OR is_active IS NULL) ORDER BY id ASC', [position]);
        return rows;
    }
    const [rows] = await dbPromise.query('SELECT * FROM banners WHERE (is_active = 1 OR is_active IS NULL) ORDER BY id ASC');
    return rows;
}

async function attachVariantsAndMedia(products) {
    if (!products || !Array.isArray(products) || products.length === 0) return;
    const ids = products.map(p => p.id).filter(Boolean);
    if (ids.length === 0) return;

    const placeholders = ids.map(() => '?').join(',');
    const [variants] = await dbPromise.query(`SELECT * FROM product_variants WHERE product_id IN (${placeholders}) AND (status = 1 OR status IS NULL)`, ids);
    const [media] = await dbPromise.query(`SELECT * FROM product_media WHERE product_id IN (${placeholders}) AND (is_active = 1 OR is_active IS NULL) ORDER BY sort_order ASC, id ASC`, ids);

    const variantMap = {};
    for (const v of variants) {
        if (!variantMap[v.product_id]) variantMap[v.product_id] = [];
        variantMap[v.product_id].push(v);
    }

    const mediaMap = {};
    for (const m of media) {
        if (!mediaMap[m.product_id]) mediaMap[m.product_id] = [];
        mediaMap[m.product_id].push(m);
    }

    for (const p of products) {
        p.variants = variantMap[p.id] || [];
        p.media = mediaMap[p.id] || [];
    }
}

async function getProducts({ where = {}, limit = null, orderBy = 'id DESC' } = {}) {
    let sql = 'SELECT * FROM products';
    const params = [];
    const clauses = [];

    for (const [k, v] of Object.entries(where)) {
        if (v !== undefined && v !== null) {
            clauses.push(`\`${k}\` = ?`);
            params.push(v);
        }
    }

    if (clauses.length > 0) {
        sql += ' WHERE ' + clauses.join(' AND ');
    }

    if (orderBy) {
        sql += ' ORDER BY ' + orderBy;
    }

    if (limit) {
        sql += ' LIMIT ' + parseInt(limit, 10);
    }

    const [rows] = await dbPromise.query(sql, params);
    return rows;
}

async function getProductBySlugOrId(slugOrId) {
    const isNum = !isNaN(slugOrId);
    const sql = isNum ? 'SELECT * FROM products WHERE id = ? OR slug = ? LIMIT 1' : 'SELECT * FROM products WHERE slug = ? LIMIT 1';
    const params = isNum ? [slugOrId, slugOrId] : [slugOrId];
    const [rows] = await dbPromise.query(sql, params);
    if (rows.length === 0) return null;
    const product = rows[0];
    await attachVariantsAndMedia([product]);
    return product;
}

async function getCategories() {
    const [rows] = await dbPromise.query('SELECT * FROM categories ORDER BY id ASC');
    return rows;
}

async function getSubcategories(categoryId = null) {
    if (categoryId) {
        const [rows] = await dbPromise.query('SELECT * FROM subcategories WHERE category_id = ? ORDER BY id ASC', [categoryId]);
        return rows;
    }
    const [rows] = await dbPromise.query('SELECT * FROM subcategories ORDER BY id ASC');
    return rows;
}

async function getTestimonials() {
    const [rows] = await dbPromise.query('SELECT * FROM testimonials WHERE (is_active = 1 OR is_active IS NULL) ORDER BY id DESC');
    return rows;
}

async function getBlogs(limit = 10) {
    const [rows] = await dbPromise.query('SELECT * FROM blogs ORDER BY id DESC LIMIT ?', [limit]);
    return rows;
}

async function getBlogBySlug(slug) {
    const [rows] = await dbPromise.query('SELECT * FROM blogs WHERE slug = ? LIMIT 1', [slug]);
    return rows[0] || null;
}

async function getFaqs() {
    const [rows] = await dbPromise.query('SELECT * FROM faqs ORDER BY id ASC');
    return rows;
}

async function getPageBySlug(slug) {
    const [rows] = await dbPromise.query('SELECT * FROM pages WHERE slug = ? LIMIT 1', [slug]);
    return rows[0] || null;
}

async function getOrders() {
    const [rows] = await dbPromise.query('SELECT * FROM orders ORDER BY id DESC');
    return rows;
}

async function getOrderById(id) {
    const [rows] = await dbPromise.query('SELECT * FROM orders WHERE id = ?', [id]);
    if (rows.length === 0) return null;
    const order = rows[0];
    const [items] = await dbPromise.query('SELECT * FROM order_items WHERE order_id = ?', [order.id]);
    order.items = items;
    return order;
}

async function getOrderByNumber(orderNumber) {
    const [rows] = await dbPromise.query('SELECT * FROM orders WHERE order_number = ?', [orderNumber]);
    if (rows.length === 0) return null;
    const order = rows[0];
    const [items] = await dbPromise.query('SELECT * FROM order_items WHERE order_id = ?', [order.id]);
    order.items = items;
    return order;
}

module.exports = {
    db,
    dbPromise,
    getSettings,
    getBanners,
    getProducts,
    getProductBySlugOrId,
    attachVariantsAndMedia,
    getCategories,
    getSubcategories,
    getTestimonials,
    getBlogs,
    getBlogBySlug,
    getFaqs,
    getPageBySlug,
    getOrders,
    getOrderById,
    getOrderByNumber
};
