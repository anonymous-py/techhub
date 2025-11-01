<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

$ref = $_GET['ref'] ?? '';
$order = null;
$items = [];
if ($ref) {
    try {
        $stmt = $db->prepare("SELECT * FROM orders WHERE paystackReference=?");
        $stmt->execute([$ref]);
        $order = $stmt->fetch();
        if ($order) {
            $it = $db->prepare("SELECT oi.quantity, oi.unitPrice, p.productName FROM order_items oi JOIN products p ON p.productId=oi.productId WHERE oi.orderId=?");
            $it->execute([$order['orderId']]);
            $items = $it->fetchAll();
        }
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<main class="mainContent">
    <div class="container">
        <div class="sectionHeader">
            <h2 class="sectionTitle">Payment Receipt</h2>
            <div class="sectionDivider"></div>
        </div>
        <?php if (!$order): ?>
            <div class="noResults">
                <h3>Receipt not found</h3>
                <p><a href="index.php" class="textLink">Go home</a></p>
            </div>
        <?php else: ?>
            <div class="receiptCard">
                <div><strong>Reference:</strong> <?php echo htmlspecialchars($order['paystackReference']); ?></div>
                <div><strong>Status:</strong> <?php echo htmlspecialchars($order['paymentStatus']); ?></div>
                <div><strong>Total:</strong> GHC <?php echo number_format($order['totalAmount'], 2); ?></div>
                <div><strong>Date:</strong> <?php echo htmlspecialchars($order['createdAt']); ?></div>
            </div>
            <h3 style="margin-top:1rem;">Items</h3>
            <ul>
                <?php foreach ($items as $it): ?>
                    <li><?php echo htmlspecialchars($it['productName']); ?> × <?php echo (int)$it['quantity']; ?> — GHC <?php echo number_format((float)$it['unitPrice'] * (int)$it['quantity'], 2); ?></li>
                <?php endforeach; ?>
            </ul>
            <p style="margin-top:1rem;"><a href="index.php" class="textLink">Continue shopping</a></p>
        <?php endif; ?>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>


