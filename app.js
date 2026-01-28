document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    loadCategories();
});

async function loadProducts() {
    try {
        const res = await fetch('api.php?action=get_products');
        const products = await res.json();
        const grid = document.getElementById('productGrid');
        const loading = document.getElementById('loading');
        
        loading.style.display = 'none';
        grid.innerHTML = products.map(p => `
            <div class="card product-card">
                <div class="product-info">
                    <span class="product-sku">${p.sku}</span>
                    <h3>${p.name}</h3>
                    <p style="font-size: 0.875rem; color: var(--text-muted)">${p.category_name || 'Uncategorized'}</p>
                    <p class="product-price">${parseFloat(p.price).toLocaleString()} THB</p>
                    <div style="margin: 1rem 0;">
                        <span class="stock-badge ${getStockClass(p.stock_quantity)}">
                            Stock: ${p.stock_quantity}
                        </span>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button class="btn btn-primary" onclick="openStockModal(${p.id}, '${p.name}')" style="flex: 1">Update Stock</button>
                    <button class="btn btn-outline" onclick="deleteProduct(${p.id})" style="padding: 0.5rem; color: var(--danger);">🗑️</button>
                </div>
            </div>
        `).join('');
    } catch (e) {
        console.error('Failed to load products', e);
    }
}

async function loadCategories() {
    const res = await fetch('api.php?action=get_categories');
    const categories = await res.json();
    const select = document.getElementById('categorySelect');
    select.innerHTML = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

function getStockClass(qty) {
    if (qty <= 0) return 'stock-out';
    if (qty < 5) return 'stock-low';
    return 'stock-in';
}

function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function openStockModal(id, name) {
    document.getElementById('stockProductId').value = id;
    document.getElementById('stockModalTitle').innerText = 'Update Stock: ' + name;
    openModal('stockModal');
}

async function saveProduct(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    await fetch('api.php?action=add_product', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    closeModal('productModal');
    e.target.reset();
    loadProducts();
}

async function updateStock(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    await fetch('api.php?action=update_stock', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    closeModal('stockModal');
    e.target.reset();
    loadProducts();
}

async function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        await fetch('api.php?action=delete_product', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        loadProducts();
    }
}
