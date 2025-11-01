<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connection.php';
require_once __DIR__ . '/../paystack/vendor/autoload.php';

use Yabacon\Paystack;
use Yabacon\Paystack\MetadataBuilder;

// Use test key for now - replace with live key in production
define('PAYSTACK_SECRET', 'sk_test_badb30a68e7aa6913d8c6d4d7dda2b765f4c785c');
define('PAYSTACK_PUBLIC', 'pk_test_badb30a68e7aa6913d8c6d4d7dda2b765f4c785c');

function out($ok, $data = [], $code = 200) {
    http_response_code($code);
    echo json_encode(array_merge(['success' => $ok], $data));
    exit;
}

if (!isset($_SESSION['user_id'])) {
    out(false, ['message' => 'Not authenticated'], 401);
}

$userId = (int)$_SESSION['user_id'];
$action = $_POST['action'] ?? '';

try {
    if ($action === 'create_order') {
        $total = (float)($_POST['total'] ?? 0);
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        if ($total <= 0 || $address === '' || $email === '') out(false, ['message' => 'Invalid request'], 400);

        // Convert amount to kobo (Paystack uses kobo as smallest currency unit)
        $amountInKobo = (int)($total * 100);
        
        // Create metadata for Paystack
        $metadata = new MetadataBuilder();
        $metadata->withCustomField('Order From', 'Tech-Hub Checkout');
        $metadata->withCustomField('User ID', $userId);
        $metadata->withCustomField('Delivery Address', $address);
        
        try {
            // Initialize transaction with Paystack
            $paystack = new Paystack(PAYSTACK_SECRET);
            $paystack->disableFileGetContentsFallback();
            
            $txn = $paystack->transaction->initialize([
                'amount' => $amountInKobo,
                'email' => $email,
                'metadata' => $metadata->build()
            ]);
            
            // Get the reference from Paystack response
            $ref = $txn->data->reference;
            
            // Save order to database
            $db->beginTransaction();
            $ins = $db->prepare("INSERT INTO orders (userId, totalAmount, deliveryLocation, orderStatus, paymentStatus, paystackReference) VALUES (?, ?, ?, 'pending', 'pending', ?)");
            $ins->execute([$userId, $total, $address, $ref]);
            $orderId = (int)$db->lastInsertId();

            // Add order items from cart
            $cart = $db->prepare("SELECT c.productId, c.quantity, p.price FROM cart c JOIN products p ON p.productId=c.productId WHERE c.userId=?");
            $cart->execute([$userId]);
            $items = $cart->fetchAll();
            $oi = $db->prepare("INSERT INTO order_items (orderId, productId, quantity, unitPrice) VALUES (?,?,?,?)");
            foreach ($items as $it) {
                $oi->execute([$orderId, (int)$it['productId'], (int)$it['quantity'], (float)$it['price']]);
            }
            $db->commit();
            
            // Return Paystack access code for payment
            out(true, ['reference' => $ref, 'access_code' => $txn->data->access_code, 'orderId' => $orderId]);
            
        } catch (Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            out(false, ['message' => 'Failed to initialize payment: ' . $e->getMessage()], 500);
        }
    }

    if ($action === 'confirm') {
        $ref = $_POST['reference'] ?? '';
        if ($ref === '') out(false, ['message' => 'No reference'], 400);
        
        try {
            // Verify transaction with Paystack
            $paystack = new Paystack(PAYSTACK_SECRET);
            $paystack->disableFileGetContentsFallback();
            
            $verify = $paystack->transaction->verify([
                'reference' => $ref
            ]);
            
            if ($verify->data->status === 'success' && $verify->data->amount >= 0) {
                // Payment successful - update order and clear cart
                $upd = $db->prepare("UPDATE orders SET paymentStatus='completed', orderStatus='processing' WHERE paystackReference=? AND userId=?");
                $upd->execute([$ref, $userId]);
                
                // Clear cart
                $clr = $db->prepare("DELETE FROM cart WHERE userId=?");
                $clr->execute([$userId]);
                
                out(true, ['message' => 'Payment verified successfully', 'gateway_response' => $verify->data->gateway_response]);
            } else {
                out(false, ['message' => 'Payment verification failed', 'gateway_response' => $verify->data->gateway_response ?? 'Unknown error']);
            }
        } catch (Exception $e) {
            out(false, ['message' => 'Failed to verify payment: ' . $e->getMessage()], 500);
        }
    }
} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    out(false, ['message' => 'Checkout error'], 500);
}

out(false, ['message' => 'Invalid action'], 400);


