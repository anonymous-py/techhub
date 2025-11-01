<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    redirect('login.php?redirect=checkout.php');
}

$userId = (int)$_SESSION['user_id'];

// Load cart from DB
$items = [];
$cartTotal = 0.00;
try {
    $stmt = $db->prepare("SELECT c.productId, c.quantity, p.productName, p.price, p.productImage FROM cart c JOIN products p ON p.productId=c.productId WHERE c.userId=?");
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll();
    foreach ($items as $it) { $cartTotal += ((float)$it['price']) * ((int)$it['quantity']); }
} catch (Exception $e) {}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <script src="https://js.paystack.co/v1/inline.js"></script>
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="mainContent">
    <div class="container">
        <div class="sectionHeader">
            <h2 class="sectionTitle">Checkout</h2>
            <div class="sectionDivider"></div>
        </div>

        <?php if (empty($items)): ?>
            <div class="noResults">
                <i class="fas fa-shopping-cart fa-3x"></i>
                <h3>Your cart is empty</h3>
                <p><a href="index.php" class="textLink">Continue shopping</a></p>
            </div>
        <?php else: ?>
        <div class="checkoutGrid">
            <section class="checkoutSummary">
                <h3>Order Summary</h3>
                <div class="checkoutItems">
                    <?php foreach ($items as $it): ?>
                        <div class="checkoutItem">
                            <img src="<?php echo htmlspecialchars($it['productImage']); ?>" alt="img">
                            <div class="ciInfo">
                                <div class="ciName"><?php echo htmlspecialchars($it['productName']); ?></div>
                                <div class="ciMeta">Qty: <?php echo (int)$it['quantity']; ?></div>
                            </div>
                            <div class="ciPrice">GHC <?php echo number_format((float)$it['price'] * (int)$it['quantity'], 2); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="checkoutTotals">
                    <div class="ctRow"><span>Subtotal</span><span>GHC <?php echo number_format($cartTotal, 2); ?></span></div>
                    <div class="ctRow"><span>Delivery</span><span>GHC 0.00</span></div>
                    <div class="ctRow ctGrand"><span>Total</span><span id="grandTotal" data-total="<?php echo number_format($cartTotal, 2, '.', ''); ?>">GHC <?php echo number_format($cartTotal, 2); ?></span></div>
                </div>
                <button id="payNowBtn" class="primaryButton largeButton" style="width:100%; margin-top:1rem;">Pay Now</button>
            </section>

            <section class="checkoutDetails">
                <h3>Delivery Details</h3>
                <form id="checkoutForm">
                    <div class="formGroup">
                        <label class="formLabel">Full Name</label>
                        <input type="text" id="fullName" class="formInput" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" required>
                    </div>
                    <div class="formGroup">
                        <label class="formLabel">Email</label>
                        <input type="email" id="email" class="formInput" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" required>
                    </div>
                    <div class="formGroup">
                        <label class="formLabel">Delivery Address</label>
                        <textarea id="address" class="formTextarea" placeholder="House number, street, city" required></textarea>
                    </div>
                </form>
            </section>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('payNowBtn');
    if (!payBtn) return;
    payBtn.addEventListener('click', function() {
        const total = parseFloat(document.getElementById('grandTotal').dataset.total || '0');
        const email = document.getElementById('email').value.trim();
        const fullName = document.getElementById('fullName').value.trim();
        const address = document.getElementById('address').value.trim();
        if (!email || !fullName || !address) {
            alert('Please complete delivery details.');
            return;
        }

        // Create order and initialize Paystack payment
        fetch('includes/checkout_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ 
                action: 'create_order', 
                total: total, 
                address: address,
                email: email
            })
        }).then(r => r.json()).then(data => {
            if (!data.success) throw new Error(data.message || 'Failed to create order');
            const orderRef = data.reference;
            const accessCode = data.access_code;
            
            // Initialize Paystack Inline
            const handler = PaystackPop.setup({
                key: 'pk_test_badb30a68e7aa6913d8c6d4d7dda2b765f4c785c',
                email: email,
                amount: Math.round(total * 100), // Convert to pesewas/kobo
                ref: orderRef,
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Full Name",
                            variable_name: "full_name",
                            value: fullName
                        }
                    ]
                },
                callback: function(response) {
                    // Verify payment on server
                    fetch('includes/checkout_handler.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ action: 'confirm', reference: response.reference })
                    }).then(r => r.json()).then(done => {
                        if (done.success) {
                            window.location = 'receipt.php?ref=' + encodeURIComponent(response.reference);
                        } else {
                            alert('Payment verification failed: ' + (done.message || 'Unknown error'));
                        }
                    }).catch((err) => {
                        console.error(err);
                        alert('Payment verification failed.');
                    });
                },
                onClose: function() {
                    alert('Transaction was not completed, window closed.');
                }
            });
            handler.openIframe();
        }).catch((err) => {
            console.error(err);
            alert('Unable to start checkout: ' + (err.message || 'Unknown error'));
        });
    });
});
</script>
</body>
</html>


