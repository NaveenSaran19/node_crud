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
    try {
        console.log('[AutoMigrate] Checking remote database schema...');
        const [tables] = await dbPromise.query("SHOW TABLES LIKE 'settings'");
        if (tables && tables.length > 0) {
            console.log('[AutoMigrate] Database schema verified: "settings" table exists. Skipping auto-migration.');
            return;
        }

        console.log('[AutoMigrate] Table "settings" does not exist! Running automatic initialization...');
        const sqlPath = path.join(__dirname, 'schema.sql');
        if (!fs.existsSync(sqlPath)) {
            console.warn('[AutoMigrate] Warning: schema.sql not found at', sqlPath);
            return;
        }

        const sql = fs.readFileSync(sqlPath, 'utf8');
        const statements = splitSqlStatements(sql);
        console.log(`[AutoMigrate] Executing ${statements.length} SQL schema/seed statements...`);

        for (const stmt of statements) {
            if (!stmt || stmt.length === 0) continue;
            try {
                await dbPromise.query(stmt);
            } catch (err) {
                if (!err.message.includes('already exists') && !err.message.includes('Unknown table')) {
                    console.warn('[AutoMigrate] Notice on statement:', err.message);
                }
            }
        }

        console.log('[AutoMigrate] Database initialization completed successfully! All tables and data are ready.');
    } catch (err) {
        console.error('[AutoMigrate] Auto-migration error:', err.message);
    }
}

module.exports = { autoMigrate, splitSqlStatements };
