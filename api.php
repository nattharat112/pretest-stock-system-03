<?php
header('Content-Type: application/json');
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_products':
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
        echo json_encode($stmt->fetchAll());
        break;

    case 'get_categories':
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
        echo json_encode($stmt->fetchAll());
        break;

    case 'add_product':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO products (name, category_id, sku, price, stock_quantity, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'],
                $data['category_id'],
                $data['sku'],
                $data['price'],
                $data['stock_quantity'],
                $data['description']
            ]);
            echo json_encode(['status' => 'success', 'id' => $pdo->lastInsertId()]);
        }
        break;

    case 'update_stock':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $pdo->beginTransaction();
            try {
                // Determine quantity change
                $change = ($data['type'] === 'IN') ? $data['quantity'] : -$data['quantity'];

                // Update product stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?");
                $stmt->execute([$change, $data['product_id']]);

                // Log transaction
                $stmt = $pdo->prepare("INSERT INTO stock_transactions (product_id, type, quantity, note) VALUES (?, ?, ?, ?)");
                $stmt->execute([$data['product_id'], $data['type'], $data['quantity'], $data['note']]);

                $pdo->commit();
                echo json_encode(['status' => 'success']);
            } catch (Exception $e) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()], 400);
            }
        }
        break;

    case 'delete_product':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(['status' => 'success']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
