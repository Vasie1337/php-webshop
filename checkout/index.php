<?php
include '../components/topbar.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="card-title mb-4">
                            <i class="bi bi-credit-card me-2"></i>Checkout
                        </h1>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?php 
                                    echo htmlspecialchars($_SESSION['error']);
                                    unset($_SESSION['error']);
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="order-summary mb-4">
                            <h2 class="h4 mb-3">Order Summary</h2>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
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
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="fw-bold">Total:</td>
                                            <td class="fw-bold">$<?php echo number_format($cartTotal, 2); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <form method="POST" class="d-grid gap-2">
                            <button type="submit" name="process_order" class="btn btn-primary btn-lg">
                                <i class="bi bi-lock-fill me-2"></i>Confirm Order
                            </button>
                            <a href="cart.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Cart
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>