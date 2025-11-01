<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    redirect('login.php?redirect=cart.php');
}

$userId = (int)$_SESSION['user_id'];
$cartItems = [];
$subtotal = 0;

// Load cart from database for this user
try {
    $stmt = $db->prepare("
        SELECT c.productId, c.quantity, 
               p.productName, p.price, p.productImage 
        FROM cart c
        JOIN products p ON p.productId = c.productId
        WHERE c.userId = ?
        ORDER BY c.addedAt DESC
    ");
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll();

    foreach ($items as $it) {
        $qty = (int)$it['quantity'];
        $price = (float)$it['price'];
        $cartItems[] = [
            'product_id' => (int)$it['productId'],
            'name' => $it['productName'],
            'price' => $price,
            'quantity' => $qty,
            'image_url' => $it['productImage'],
            'item_total' => $price * $qty
        ];
        $subtotal += $price * $qty;
    }
} catch (Exception $e) {
    error_log('Cart load error: ' . $e->getMessage());
}

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart']) && isset($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $productId => $quantity) {
            $productId = (int)$productId;
            $quantity = max(1, (int)$quantity);
            try {
                $upd = $db->prepare("UPDATE cart SET quantity=? WHERE userId=? AND productId=?");
                $upd->execute([$quantity, $userId, $productId]);
            } catch (Exception $e) {}
        }
        redirect('cart.php');
    } elseif (isset($_POST['remove_item']) && isset($_POST['product_id'])) {
        $productId = (int)$_POST['product_id'];
        try {
            $del = $db->prepare("DELETE FROM cart WHERE userId=? AND productId=?");
            $del->execute([$userId, $productId]);
        } catch (Exception $e) {}
        redirect('cart.php');
    }
}

$shipping = $subtotal > 0 ? 0 : 0; // Free shipping for demo
$tax = $subtotal * 0.10; // 10% tax
$total = $subtotal + $shipping + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="mainContent">
        <div class="container">
            <div class="cartPage">
                <h1 class="pageTitle">Your Shopping Cart</h1>
                
                <?php if (empty($cartItems)): ?>
                    <div class="emptyCart">
                        <i class="fas fa-shopping-cart"></i>
                        <h2>Your cart is empty</h2>
                        <p>Looks like you haven't added anything to your cart yet</p>
                        <a href="index.php" class="btn btnPrimary">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="cartLayout">
                        <div class="cartItems">
                            <form method="post" class="cartForm">
                                <div class="cartHeader">
                                    <div class="cartHeaderItem productCol">Product</div>
                                    <div class="cartHeaderItem priceCol">Price</div>
                                    <div class="cartHeaderItem quantityCol">Quantity</div>
                                    <div class="cartHeaderItem totalCol">Total</div>
                                    <div class="cartHeaderItem actionCol"></div>
                                </div>

                                <?php foreach ($cartItems as $item): 
                                    $product = $item;
                                    $productId = $product['product_id'];
                                ?>
                                    <div class="cartItem">
                                        <div class="cartItemProduct">
                                            <img src="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                 class="cartItemImage"
                                                 onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'">
                                            <div class="cartItemDetails">
                                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                                <p class="cartItemSku">SKU: TH-<?php echo str_pad($productId, 4, '0', STR_PAD_LEFT); ?></p>
                                            </div>
                                        </div>
                                        <div class="cartItemPrice">
                                            GHC <?php echo number_format($product['price'], 2); ?>
                                        </div>
                                        <div class="cartItemQuantity">
                                            <input type="number" 
                                                   name="quantity[<?php echo $productId; ?>]" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   min="1" 
                                                   class="quantityInput">
                                        </div>
                                        <div class="cartItemTotal">
                                            GHC <?php echo number_format($item['item_total'], 2); ?>
                                        </div>
                                        <div class="cartItemActions">
                                            <button type="submit" 
                                                    name="remove_item" 
                                                    value="1" 
                                                    class="removeItemBtn"
                                                    onclick="this.form.querySelector('input[name=\"product_id\"]').value=<?php echo $productId; ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <input type="hidden" name="product_id" value="">
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="cartActions">
                                    <a href="index.php" class="continueShopping">
                                        <i class="fas fa-arrow-left"></i> Continue Shopping
                                    </a>
                                    <button type="submit" name="update_cart" class="btn btnSecondary">
                                        <i class="fas fa-sync-alt"></i> Update Cart
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="cartSummary">
                            <h3>Order Summary</h3>
                            <div class="summaryRow">
                                <span>Subtotal</span>
                                <span>GHC <?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="summaryRow">
                                <span>Shipping</span>
                                <span><?php echo $shipping > 0 ? 'GHC ' . number_format($shipping, 2) : 'Free'; ?></span>
                            </div>
                            <div class="summaryRow">
                                <span>Tax</span>
                                <span>GHC <?php echo number_format($tax, 2); ?></span>
                            </div>
                            <div class="summaryRow total">
                                <span>Total</span>
                                <span>GHC <?php echo number_format($total, 2); ?></span>
                            </div>
                        <!-- <a href="checkout.php" class="btn btnPrimary btnBlock">Proceed to Checkout</a> -->
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script>
    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCountOnCartPage();
    });

    // Quantity input handling
    document.querySelectorAll('.quantityInput').forEach(input => {
        input.addEventListener('change', function() {
            this.value = Math.max(1, parseInt(this.value) || 1);
        });
    });

    // Function to update cart count from this page
    async function updateCartCountOnCartPage() {
        const cartCount = document.getElementById('cartCount');
        if (!cartCount) return;
        
        try {
            const res = await fetch('api/cart.php');
            const json = await res.json();
            if (json.success && Array.isArray(json.items)) {
                cartCount.textContent = json.items.length;
            } else {
                // Fallback to localStorage
                const cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
                cartCount.textContent = cart.length;
            }
        } catch (e) {
            // Fallback to localStorage
            const cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
            cartCount.textContent = cart.length;
        }
    }
    </script>
</body>
</html>