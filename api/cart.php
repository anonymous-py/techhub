<?php
session_start();
header('Content-Type: application/json');

require_once '../includes/db_connection.php';
require_once '../includes/functions.php';

function respond($ok, $data = [], $status = 200) {
    http_response_code($status);
    echo json_encode(array_merge(['success' => $ok], $data));
    exit;
}

// Require login
if (!isset($_SESSION['user_id'])) {
    respond(false, ['message' => 'Not authenticated'], 401);
}

$userId = (int)$_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

// Only allow POST for mutations, GET for list
if ($method === 'GET') {
    try {
        $stmt = $db->prepare("SELECT c.productId, c.quantity, p.productName, p.price, p.productImage FROM cart c JOIN products p ON p.productId = c.productId WHERE c.userId = ? ORDER BY c.addedAt DESC");
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll();
        respond(true, ['items' => $items]);
    } catch (Exception $e) {
        respond(false, ['message' => 'Failed to load cart']);
    }
}

if ($method !== 'POST') {
    respond(false, ['message' => 'Method not allowed'], 405);
}

$action = $_POST['action'] ?? '';
$productId = isset($_POST['productId']) ? (int)$_POST['productId'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (!in_array($action, ['add', 'update', 'remove', 'clear'], true)) {
    respond(false, ['message' => 'Invalid action'], 400);
}

try {
    switch ($action) {
        case 'add':
            if ($productId <= 0 || $quantity <= 0) respond(false, ['message' => 'Invalid payload'], 400);

            // Ensure product exists in products table (dev mode uses sample data only)
            $existsStmt = $db->prepare("SELECT productId FROM products WHERE productId = ?");
            $existsStmt->execute([$productId]);
            if (!$existsStmt->fetch()) {
                // Try to seed minimal product record using sample data (if available)
                if (function_exists('getSampleProducts')) {
                    $samples = getSampleProducts();
                    foreach ($samples as $sp) {
                        if ((int)$sp['id'] === $productId) {
                            $name = $sp['name'];
                            $desc = $sp['description'];
                            // Parse price like "$2,499.99" => 2499.99
                            $price = (float)preg_replace('/[^0-9.]/', '', $sp['price']);
                            $img = $sp['image'];
                            $seed = $db->prepare("INSERT INTO products (productId, productName, productDescription, price, stockQuantity, categoryId, productImage) VALUES (?, ?, ?, ?, 999, NULL, ?)");
                            try {
                                $seed->execute([$productId, $name, $desc, $price, $img]);
                            } catch (Exception $e) {
                                // ignore if fails (e.g., FK/constraints)
                            }
                            break;
                        }
                    }
                }
            }

            // Upsert: increment if exists
            $stmt = $db->prepare("SELECT quantity FROM cart WHERE userId=? AND productId=?");
            $stmt->execute([$userId, $productId]);
            if ($row = $stmt->fetch()) {
                $newQty = (int)$row['quantity'] + $quantity;
                $upd = $db->prepare("UPDATE cart SET quantity=? WHERE userId=? AND productId=?");
                $upd->execute([$newQty, $userId, $productId]);
            } else {
                $ins = $db->prepare("INSERT INTO cart (userId, productId, quantity) VALUES (?,?,?)");
                $ins->execute([$userId, $productId, $quantity]);
            }
            respond(true, ['message' => 'Added to cart']);

        case 'update':
            if ($productId <= 0 || $quantity < 0) respond(false, ['message' => 'Invalid payload'], 400);
            if ($quantity === 0) {
                $del = $db->prepare("DELETE FROM cart WHERE userId=? AND productId=?");
                $del->execute([$userId, $productId]);
            } else {
                $upd = $db->prepare("UPDATE cart SET quantity=? WHERE userId=? AND productId=?");
                $upd->execute([$quantity, $userId, $productId]);
            }
            respond(true, ['message' => 'Cart updated']);

        case 'remove':
            if ($productId <= 0) respond(false, ['message' => 'Invalid payload'], 400);
            $del = $db->prepare("DELETE FROM cart WHERE userId=? AND productId=?");
            $del->execute([$userId, $productId]);
            respond(true, ['message' => 'Item removed']);

        case 'clear':
            $del = $db->prepare("DELETE FROM cart WHERE userId=?");
            $del->execute([$userId]);
            respond(true, ['message' => 'Cart cleared']);
    }
} catch (Exception $e) {
    respond(false, ['message' => 'Database error'], 500);
}


