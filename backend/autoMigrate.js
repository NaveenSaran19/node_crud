const fs = require('fs');
const path = require('path');
const db = require('./db');

function splitSqlStatements(sql) {
    const rawStatements = [];
    let current = '';
    let inString = false;
    let stringChar = '';

    for (let i = 0; i < sql.length; i++) {
        const c = sql[i];
        const next = sql[i + 1];

        if (!inString) {
            if (c === '-' && next === '-') {
                const endLine = sql.indexOf('\n', i);
                i = endLine === -1 ? sql.length : endLine;
                continue;
            }
            if (c === '/' && next === '*') {
                const endBlock = sql.indexOf('*/', i);
                i = endBlock === -1 ? sql.length : endBlock + 1;
                continue;
            }
            if (c === "'" || c === '"' || c === '`') {
                inString = true;
                stringChar = c;
                current += c;
                continue;
            }
            if (c === ';') {
                const trimmed = current.trim();
                if (trimmed) rawStatements.push(trimmed);
                current = '';
                continue;
            }
            current += c;
        } else {
            if (c === '\\') {
                current += c + (next || '');
                i++;
                continue;
            }
            if (c === stringChar) {
                inString = false;
            }
            current += c;
        }
    }
    if (current.trim()) rawStatements.push(current.trim());
    return rawStatements;
}

async function autoMigrate() {
    const dbPromise = db.promise();
    const requiredTables = [
        'settings',
        'users',
        'categories',
        'subcategories',
        'products',
        'product_variants',
        'product_media',
        'orders',
        'order_items',
        'banners',
        'blogs',
        'faqs',
        'testimonials'
    ];

    try {
        console.log('[AutoMigrate] Checking remote database schema...');
        const [existingRows] = await dbPromise.query("SHOW TABLES");
        const existingTables = new Set(existingRows.map(r => Object.values(r)[0].toLowerCase()));
        
        const missingTables = requiredTables.filter(t => !existingTables.has(t.toLowerCase()));

        if (missingTables.length === 0) {
            console.log('[AutoMigrate] All core tables verified in database. Skipping migration.');
            return;
        }

        console.log(`[AutoMigrate] Missing tables detected: [${missingTables.join(', ')}]. Starting automatic schema migration...`);
        const sqlPath = path.join(__dirname, 'schema.sql');
        if (!fs.existsSync(sqlPath)) {
            console.warn('[AutoMigrate] Warning: schema.sql not found at', sqlPath);
            return;
        }

        // Disable foreign key constraints during import to prevent dependency errors
        try {
            await dbPromise.query('SET FOREIGN_KEY_CHECKS = 0');
        } catch (e) {
            console.warn('[AutoMigrate] Warning setting FOREIGN_KEY_CHECKS=0:', e.message);
        }

        const sql = fs.readFileSync(sqlPath, 'utf8');
        const statements = splitSqlStatements(sql);
        console.log(`[AutoMigrate] Executing ${statements.length} SQL schema/seed statements with FOREIGN_KEY_CHECKS=0...`);

        for (let stmt of statements) {
            if (!stmt || stmt.length === 0) continue;
            // Never drop tables
            if (/^\s*DROP TABLE/i.test(stmt)) continue;

            // Use INSERT IGNORE to prevent duplicate key errors on re-run
            if (/^\s*INSERT INTO/i.test(stmt)) {
                stmt = stmt.replace(/^\s*INSERT INTO/i, 'INSERT IGNORE INTO');
            }

            try {
                await dbPromise.query(stmt);
            } catch (err) {
                if (!err.message.includes('already exists') && !err.message.includes('Duplicate entry')) {
                    console.warn('[AutoMigrate] Notice on statement:', err.message);
                }
            }
        }

        // Re-enable foreign key constraints
        try {
            await dbPromise.query('SET FOREIGN_KEY_CHECKS = 1');
        } catch (e) {
            console.warn('[AutoMigrate] Warning restoring FOREIGN_KEY_CHECKS=1:', e.message);
        }

        // Verification
        const [verifyRows] = await dbPromise.query("SHOW TABLES");
        const verifiedTables = new Set(verifyRows.map(r => Object.values(r)[0].toLowerCase()));
        const stillMissing = requiredTables.filter(t => !verifiedTables.has(t.toLowerCase()));

        if (stillMissing.length === 0) {
            console.log('[AutoMigrate] Database schema migration successfully verified! All tables ready.');
        } else {
            console.warn('[AutoMigrate] Some tables still missing after migration:', stillMissing.join(', '));
        }
    } catch (err) {
        console.error('[AutoMigrate] Auto-migration error:', err.message);
        try {
            await dbPromise.query('SET FOREIGN_KEY_CHECKS = 1');
        } catch {}
    }
}

module.exports = { autoMigrate, splitSqlStatements };
