const mysql = require("mysql2");

let poolConfig;

if (process.env.DATABASE_URL || process.env.MYSQL_URL) {
    poolConfig = process.env.DATABASE_URL || process.env.MYSQL_URL;
} else {
    const host = process.env.DB_HOST || process.env.MYSQLHOST || "localhost";
    const isCloud = host !== 'localhost' && host !== '127.0.0.1';
    const useSsl = process.env.DB_SSL === "true" || process.env.DB_PORT === "4000" || isCloud;

    poolConfig = {
        host: host,
        user: process.env.DB_USER || process.env.MYSQLUSER || "root",
        password: process.env.DB_PASSWORD || process.env.MYSQLPASSWORD || "",
        database: process.env.DB_NAME || process.env.MYSQLDATABASE || "sri_sakthi",
        port: Number(process.env.DB_PORT || process.env.MYSQLPORT) || 3306,
        ssl: useSsl ? { rejectUnauthorized: false } : undefined,
        waitForConnections: true,
        connectionLimit: 10,
        queueLimit: 0
    };
}

const db = mysql.createPool(poolConfig);

module.exports = db;
