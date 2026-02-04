<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: customer_login.php');
    exit;
}
require 'db.php';
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock_quantity > 0 ORDER BY p.name");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Salapao PC</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="preconnect">
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>Salapao PC Shop</h1>
                <p style="color: var(--text-muted)">Browse our PC components</p>
            </div>
            <button class="btn btn-outline" onclick="location.href='logout.php'">Logout</button>
        </header>

        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product-item card">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <p class="price">฿<?php echo number_format($product['price'], 2); ?></p>
                    <p class="stock">In Stock: <?php echo $product['stock_quantity']; ?></p>
                    <?php if ($product['description']): ?>
                        <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>