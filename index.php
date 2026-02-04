 <?php session_start(); if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header('Location: login.php'); exit; } ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salapao PC - Stock Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <header>
            <div>
                <h1>Salapao PC Stock</h1>
                <p style="color: var(--text-muted)">Manage components and inventory</p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="btn btn-primary" onclick="openModal('productModal')">Add New Product</button>
                <button class="btn btn-outline" onclick="location.href='logout.php'">Logout</button>
            </div>

        <div id="productGrid" class="product-grid">
            <!-- Products will be loaded here -->
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content card">
            <h2>Add New Component</h2>
            <form id="productForm" onsubmit="saveProduct(event)">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" required placeholder="e.g. RTX 4090">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" id="categorySelect" required>
                        <!-- Categories will be loaded here -->
                    </select>
                </div>
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" name="sku" required placeholder="Unique Stock ID">
                </div>
                <div class="form-group">
                    <label>Price (THB)</label>
                    <input type="number" name="price" required step="0.01">
                </div>
                <div class="form-group">
                    <label>Initial Stock</label>
                    <input type="number" name="stock_quantity" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" style="flex:1"
                        onclick="closeModal('productModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex:1">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Update Modal -->
    <div id="stockModal" class="modal">
        <div class="modal-content card">
            <h2 id="stockModalTitle">Update Stock</h2>
            <form id="stockForm" onsubmit="updateStock(event)">
                <input type="hidden" name="product_id" id="stockProductId">
                <div class="form-group">
                    <label>Transaction Type</label>
                    <select name="type" required>
                        <option value="IN">Stock In (+)</option>
                        <option value="OUT">Stock Out (-)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" required min="1">
                </div>
                <div class="form-group">
                    <label>Note</label>
                    <textarea name="note" rows="2" placeholder="e.g. New delivery, Customer build"></textarea>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" style="flex:1"
                        onclick="closeModal('stockModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex:1">Apply Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="app.js"></script>
</body>

</html>