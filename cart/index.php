<?php
include '../components/topbar.php';

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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Shopping Cart</h1>

        <?php if (isset($_GET['removed']) && $_GET['removed'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>Item successfully removed from cart!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                <h2 class="mb-3">Your cart is empty</h2>
                <p class="text-muted mb-4">Looks like you haven't added any items yet.</p>
                <a href="../index.php" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
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
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                        <button type="submit" name="remove_item" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash me-1"></i>Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1">Total Items: <strong><?php echo $itemCount; ?></strong></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1">Total: <strong>$<?php echo number_format($cartTotal, 2); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="../index.php" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
                <a href="../checkout" class="btn btn-primary">
                    <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>