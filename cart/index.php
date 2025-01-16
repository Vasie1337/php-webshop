<?php
require_once '../models/cart.php';
require_once '../utils/logger.php';

Cart::init();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $productId = (int)$_POST['product_id'];

    if (!Cart::removeItem($productId))
    {
        header('Location: index.php?removed=0');
        exit();
    }

    header('Location: index.php?removed=1');
    exit();
}

$cartItems = Cart::getItems();
$cartTotal = Cart::getTotal();
$itemCount = Cart::getItemCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
</head>
<body>
    <div class="cart-container">
        <h1>Shopping Cart</h1>

        <?php if (isset($_GET['removed']) && $_GET['removed'] == 1): ?>
            <div class="success-message">
                Item successfully removed from cart!
            </div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any items yet.</p>
                <a href="../index.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $productId => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo (int)$item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <button type="submit" name="remove_item" class="remove-button">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <p>Total Items: <?php echo $itemCount; ?></p>
                <p>Total: $<?php echo number_format($cartTotal, 2); ?></p>
            </div>

            <p style="text-align: right;">
                <a href="../checkout" class="checkout-button">Proceed to Checkout</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>