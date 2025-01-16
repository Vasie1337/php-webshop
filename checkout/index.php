<?php
require_once '../models/cart.php';
require_once '../models/order.php';
require_once '../utils/logger.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

Cart::init();

$cartItems = Cart::getItems();
$cartTotal = Cart::getTotal();

if (empty($cartItems)) {
    header('Location: ../cart');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_order'])) {
    try {
        $order = new Order();
        
        foreach ($cartItems as $item) {
            $order->create($_SESSION['user_id'], $item['product_id'], $item['quantity'], $item['price'] * $item['quantity']);
        }

        Cart::clear();

        header('Location: ../order_confirmation');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <div class="checkout-container">
        <h1>Checkout</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo (int)$item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($cartTotal, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form method="POST" class="checkout-form">
            <button type="submit" name="process_order" class="checkout-button">
                Confirm Order
            </button>
        </form>

        <p>
            <a href="cart.php" class="back-to-cart">Back to Cart</a>
        </p>
    </div>
</body>
</html>