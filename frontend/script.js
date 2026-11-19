const API_URL = "/api/products";

const form = document.getElementById("productForm");
const productId = document.getElementById("productId");
const productName = document.getElementById("productName");
const productPrice = document.getElementById("productPrice");
const productQuantity = document.getElementById("productQuantity");

const productTable = document.getElementById("productTable");
const formTitle = document.getElementById("formTitle");
const saveBtn = document.getElementById("saveBtn");
const cancelBtn = document.getElementById("cancelBtn");
const searchInput = document.getElementById("searchInput");
const message = document.getElementById("message");

let products = [];

// READ PRODUCTS
async function loadProducts() {
    try {
        const response = await fetch(API_URL);
        products = await response.json();

        displayProducts(products);
    } catch (error) {
        message.textContent = "Cannot connect to backend server";
    }
}

// DISPLAY PRODUCTS
function displayProducts(data) {
    productTable.innerHTML = "";

    data.forEach(product => {
        const row = document.createElement("tr");

        const idCell = document.createElement("td");
        idCell.textContent = product.id;

        const nameCell = document.createElement("td");
        nameCell.textContent = product.product_name;

        const priceCell = document.createElement("td");
        priceCell.textContent = "₹" + product.price;

        const quantityCell = document.createElement("td");
        quantityCell.textContent = product.quantity;

        const actionCell = document.createElement("td");

        const editButton = document.createElement("button");
        editButton.textContent = "Edit";
        editButton.className = "edit-btn";
        editButton.onclick = () => editProduct(product.id);

        const deleteButton = document.createElement("button");
        deleteButton.textContent = "Delete";
        deleteButton.className = "delete-btn";
        deleteButton.onclick = () => deleteProduct(product.id);

        actionCell.append(editButton, deleteButton);

        row.append(
            idCell,
            nameCell,
            priceCell,
            quantityCell,
            actionCell
        );

        productTable.appendChild(row);
    });
}

// CREATE / UPDATE
form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const data = {
        product_name: productName.value.trim(),
        price: Number(productPrice.value),
        quantity: Number(productQuantity.value)
    };

    const id = productId.value;

    const method = id ? "PUT" : "POST";

    const url = id ? `${API_URL}/${id}` : API_URL;

    try {
        const response = await fetch(url, {
            method,
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Operation failed");
        }

        message.textContent = result.message;

        resetForm();

        await loadProducts();

    } catch (error) {
        message.textContent = error.message;
    }
});

// EDIT PRODUCT
function editProduct(id) {
    const product = products.find(item => item.id == id);

    if (!product) return;

    productId.value = product.id;
    productName.value = product.product_name;
    productPrice.value = product.price;
    productQuantity.value = product.quantity;

    formTitle.textContent = "Edit Product";
    saveBtn.textContent = "Update Product";

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

// DELETE PRODUCT
async function deleteProduct(id) {
    if (!confirm("Are you sure you want to delete this product?")) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: "DELETE"
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message);
        }

        message.textContent = result.message;

        await loadProducts();

    } catch (error) {
        message.textContent = error.message;
    }
}

// SEARCH
searchInput.addEventListener("input", () => {
    const keyword = searchInput.value.toLowerCase();

    const filtered = products.filter(product =>
        product.product_name.toLowerCase().includes(keyword)
    );

    displayProducts(filtered);
});

// RESET
function resetForm() {
    form.reset();

    productId.value = "";

    formTitle.textContent = "Add New Product";
    saveBtn.textContent = "Add Product";
}

// CANCEL
cancelBtn.addEventListener("click", resetForm);

// INITIAL LOAD
loadProducts();