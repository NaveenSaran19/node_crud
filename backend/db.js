const mysql = require("mysql2");

let poolConfig;

if (process.env.DATABASE_URL) {
    poolConfig = process.env.DATABASE_URL;
} else {
    poolConfig = {
        host: process.env.DB_HOST || "localhost",
        user: process.env.DB_USER || "root",
        password: process.env.DB_PASSWORD || "",
        database: process.env.DB_NAME || "sri_sakthi",
        port: Number(process.env.DB_PORT) || 3306,
        ssl: (process.env.DB_SSL === "true" || process.env.DB_PORT === "4000") ? { rejectUnauthorized: false } : undefined,
        waitForConnections: true,
        connectionLimit: 10,
        queueLimit: 0
    };
}

const db = mysql.createPool(poolConfig);

module.exports = db;
