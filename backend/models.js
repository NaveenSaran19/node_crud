const db = require('./db');
const dbPromise = db.promise();

async function getSettings() {
    try {
        const [rows] = await dbPromise.query('SELECT `key`, `value` FROM settings');
        const settings = {};
        for (const r of rows) {
            settings[r.key] = r.value;
        }
        return settings;
    } catch (err) {
        console.warn('[Model Warning] getSettings failed:', err.message);
        return {
            site_name: 'Selvi Tex',
            site_tagline: 'Pure Silk & Handloom Sarees',
            contact_phone: '+91 9789296751',
            contact_email: 'selvitex@gmail.com',
            contact_address: 'Elampillai, Salem, Tamil Nadu, India'
        };
    }
}

async function getBanners(position = null) {
    try {
        if (position) {
            const [rows] = await dbPromise.query('SELECT * FROM banners WHERE position = ? AND (is_active = 1 OR is_active IS NULL) ORDER BY id ASC', [position]);
            return rows;
        }
        const [rows] = await dbPromise.query('SELECT * FROM banners WHERE (is_active = 1 OR is_active IS NULL) ORDER BY id ASC');
        return rows;
    } catch (err) {
        console.warn('[Model Warning] getBanners failed:', err.message);
        return [];
    }
}

async function attachVariantsAndMedia(products) {
    if (!products || !Array.isArray(products) || products.length === 0) return;
    const ids = products.map(p => p.id).filter(Boolean);
    if (ids.length === 0) return;

    try {
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
    } catch (err) {
        console.warn('[Model Warning] attachVariantsAndMedia failed:', err.message);
    }
}

async function getProducts({ where = {}, limit = null, orderBy = 'id DESC' } = {}) {
    try {
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
    } catch (err) {
        console.warn('[Model Warning] getProducts failed:', err.message);
        return [];
    }
}

async function getProductBySlugOrId(slugOrId) {
    try {
        const isNum = !isNaN(slugOrId);
        const sql = isNum ? 'SELECT * FROM products WHERE id = ? OR slug = ? LIMIT 1' : 'SELECT * FROM products WHERE slug = ? LIMIT 1';
        const params = isNum ? [slugOrId, slugOrId] : [slugOrId];
        const [rows] = await dbPromise.query(sql, params);
        if (rows.length === 0) return null;
        const product = rows[0];
        await attachVariantsAndMedia([product]);
        return product;
    } catch (err) {
        console.warn('[Model Warning] getProductBySlugOrId failed:', err.message);
        return null;
    }
}

async function getCategories() {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM categories ORDER BY id ASC');
        return rows;
    } catch (err) {
        console.warn('[Model Warning] getCategories failed:', err.message);
        return [];
    }
}

async function getSubcategories(categoryId = null) {
    try {
        if (categoryId) {
            const [rows] = await dbPromise.query('SELECT * FROM subcategories WHERE category_id = ? ORDER BY id ASC', [categoryId]);
            return rows;
        }
        const [rows] = await dbPromise.query('SELECT * FROM subcategories ORDER BY id ASC');
        return rows;
    } catch (err) {
        console.warn('[Model Warning] getSubcategories failed:', err.message);
        return [];
    }
}

async function getTestimonials() {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM testimonials WHERE (is_active = 1 OR is_active IS NULL) ORDER BY id DESC');
        return rows;
    } catch (err) {
        console.warn('[Model Warning] getTestimonials failed:', err.message);
        return [];
    }
}

async function getBlogs(limit = 10) {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM blogs ORDER BY id DESC LIMIT ?', [limit]);
        return rows;
    } catch (err) {
        console.warn('[Model Warning] getBlogs failed:', err.message);
        return [];
    }
}

async function getBlogBySlug(slug) {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM blogs WHERE slug = ? LIMIT 1', [slug]);
        return rows[0] || null;
    } catch (err) {
        console.warn('[Model Warning] getBlogBySlug failed:', err.message);
        return null;
    }
}

async function getFaqs() {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM faqs ORDER BY id ASC');
        return rows;
    } catch (err) {
        console.warn('[Model Warning] getFaqs failed:', err.message);
        return [];
    }
}

async function getPageBySlug(slug) {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM pages WHERE slug = ? LIMIT 1', [slug]);
        return rows[0] || null;
    } catch (err) {
        console.warn('[Model Warning] getPageBySlug failed:', err.message);
        return null;
    }
}

async function getOrders() {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM orders ORDER BY id DESC');
        return rows;
    } catch (err) {
        console.warn('[Model Warning] getOrders failed:', err.message);
        return [];
    }
}

async function getOrderById(id) {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM orders WHERE id = ?', [id]);
        if (rows.length === 0) return null;
        const order = rows[0];
        const [items] = await dbPromise.query('SELECT * FROM order_items WHERE order_id = ?', [order.id]);
        order.items = items;
        return order;
    } catch (err) {
        console.warn('[Model Warning] getOrderById failed:', err.message);
        return null;
    }
}

async function getOrderByNumber(orderNumber) {
    try {
        const [rows] = await dbPromise.query('SELECT * FROM orders WHERE order_number = ?', [orderNumber]);
        if (rows.length === 0) return null;
        const order = rows[0];
        const [items] = await dbPromise.query('SELECT * FROM order_items WHERE order_id = ?', [order.id]);
        order.items = items;
        return order;
    } catch (err) {
        console.warn('[Model Warning] getOrderByNumber failed:', err.message);
        return null;
    }
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