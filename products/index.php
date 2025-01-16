<?php
include '../components/topbar.php';

require_once '../models/product.php';

$products = Product::getAllProducts();
if (!is_array($products)) {
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h2">
                    <i class="bi bi-grid me-2"></i>Products
                </h1>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>No products available at the moment.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($products as $product): ?>
                    <?php if (isset($product['product_id']) && isset($product['name'])): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm hover-shadow">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </h5>
                                    
                                    <?php if (isset($product['description'])): ?>
                                        <p class="card-text text-muted mb-3">
                                            <?php echo mb_strimwidth(htmlspecialchars($product['description']), 0, 100, "..."); ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (isset($product['price'])): ?>
                                        <h6 class="mb-3 text-primary">
                                            $<?php echo number_format($product['price'], 2); ?>
                                        </h6>
                                    <?php endif; ?>

                                    <a href="../product/index.php?product_id=<?php echo htmlspecialchars($product['product_id']); ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
</body>
</html>