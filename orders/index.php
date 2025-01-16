<?php
include '../components/topbar.php';

require_once '../models/user.php';
require_once '../models/order.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit();
}

try {
    $orders = Order::getOrdersByUser($_SESSION['user_id']);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h2">
                    <i class="bi bi-box-seam me-2"></i>My Orders
                </h1>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <i class="bi bi-inbox display-1 text-muted mb-4"></i>
                    <h2 class="h4 mb-3">No Orders Yet</h2>
                    <p class="text-muted mb-4">Looks like you haven't placed any orders yet.</p>
                    <a href="../products" class="btn btn-primary">
                        <i class="bi bi-cart me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order Date</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $currentDate = null;
                            foreach ($orders as $order): 
                                $orderDate = date('Y-m-d', strtotime($order['order_date']));
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($orderDate !== $currentDate): ?>
                                            <span class="badge bg-secondary">
                                                <?php echo date('M d, Y', strtotime($order['order_date'])); ?>
                                            </span>
                                            <?php $currentDate = $orderDate; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td><?php echo (int)$order['quantity']; ?></td>
                                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>