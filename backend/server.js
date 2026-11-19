const express = require("express");
const cors = require("cors");
const path = require("path");
const db = require("./db");

const app = express();

app.use(cors());
app.use(express.json());

// Frontend connection
app.use(express.static(path.join(__dirname, "../frontend")));

app.get("/", (req, res) => {
    res.sendFile(path.join(__dirname, "../frontend/index.html"));
});

// CREATE - Add Product
app.post("/api/products", (req, res) => {
    const { product_name, price, quantity } = req.body;

    if (
        !product_name ||
        price === undefined ||
        quantity === undefined ||
        Number(price) < 0 ||
        !Number.isFinite(Number(price)) ||
        !Number.isInteger(Number(quantity)) ||
        Number(quantity) < 0
    ) {
        return res.status(400).json({
            message: "Please enter valid product details"
        });
    }

    const sql = `
        INSERT INTO products (product_name, price, quantity)
        VALUES (?, ?, ?)
    `;

    db.query(sql, [product_name, price, quantity], (err, result) => {
        if (err) {
            return res.status(500).json({ message: err.message });
        }

        res.status(201).json({
            message: "Product added successfully",
            id: result.insertId
        });
    });
});

// READ - Get All Products
app.get("/api/products", (req, res) => {
    db.query(
        "SELECT * FROM products ORDER BY id DESC",
        (err, results) => {
            if (err) {
                return res.status(500).json({ message: err.message });
            }

            res.json(results);
        }
    );
});

// READ - Get Single Product
app.get("/api/products/:id", (req, res) => {
    db.query(
        "SELECT * FROM products WHERE id = ?",
        [req.params.id],
        (err, results) => {
            if (err) {
                return res.status(500).json({ message: err.message });
            }

            if (results.length === 0) {
                return res.status(404).json({
                    message: "Product not found"
                });
            }

            res.json(results[0]);
        }
    );
});

// UPDATE - Edit Product
app.put("/api/products/:id", (req, res) => {
    const { product_name, price, quantity } = req.body;

    if (
        !product_name ||
        price === undefined ||
        quantity === undefined ||
        Number(price) < 0 ||
        !Number.isFinite(Number(price)) ||
        !Number.isInteger(Number(quantity)) ||
        Number(quantity) < 0
    ) {
        return res.status(400).json({
            message: "Please enter valid product details"
        });
    }

    const sql = `
        UPDATE products
        SET product_name = ?, price = ?, quantity = ?
        WHERE id = ?
    `;

    db.query(
        sql,
        [product_name, price, quantity, req.params.id],
        (err, result) => {
            if (err) {
                return res.status(500).json({ message: err.message });
            }

            if (result.affectedRows === 0) {
                return res.status(404).json({
                    message: "Product not found"
                });
            }

            res.json({
                message: "Product updated successfully"
            });
        }
    );
});

// DELETE - Remove Product
app.delete("/api/products/:id", (req, res) => {
    db.query(
        "DELETE FROM products WHERE id = ?",
        [req.params.id],
        (err, result) => {
            if (err) {
                return res.status(500).json({ message: err.message });
            }

            if (result.affectedRows === 0) {
                return res.status(404).json({
                    message: "Product not found"
                });
            }

            res.json({
                message: "Product deleted successfully"
            });
        }
    );
});

// START SERVER
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log("--------------------------------");
    console.log("Node.js Server Started");
    console.log(`Server: http://localhost:${PORT}`);
    console.log(`Product API: http://localhost:${PORT}/api/products`);
    console.log("--------------------------------");
});
