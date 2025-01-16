<?php
include '../components/topbar.php';

require_once '../models/product.php';
require_once '../models/cart.php';
require_once '../utils/logger.php';

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    debug_to_console("Invalid product ID");
}

$productId = (int)$_GET['product_id'];
$product = Product::getProductById($productId);

if (!$product) {
    debug_to_console("Product not found");
}

Cart::init();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($quantity > 0 && $quantity <= $product['stock']) {
        Cart::addItem(
            $product['product_id'],
            $product['name'],
            $product['price'],
            $quantity
        );

        header('Location: index.php?product_id=' . $product['product_id'] . '&added=1');
        exit();
    }
}

$addedToCart = isset($_GET['added']) && $_GET['added'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <a href="../products" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
        
        <?php if ($addedToCart): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>Product successfully added to cart!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="h2 mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                        
                        <?php if (isset($product['description'])): ?>
                            <div class="mb-4">
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <?php if (isset($product['sku'])): ?>
                                <p class="mb-2 text-muted">
                                    <i class="bi bi-upc me-2"></i>SKU: <?php echo htmlspecialchars($product['sku']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if (isset($product['stock'])): ?>
                                <p class="mb-2">
                                    <i class="bi bi-box-seam me-2"></i>Stock: 
                                    <span class="badge <?php echo $product['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo (int)$product['stock']; ?> units
                                    </span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <?php if (isset($product['price'])): ?>
                                    <h2 class="h4 mb-4">
                                        Price: <span class="text-primary">$<?php echo number_format($product['price'], 2); ?></span>
                                    </h2>
                                <?php endif; ?>
                                
                                <form method="POST" class="d-grid gap-3">
                                    <div class="form-floating">
                                        <input type="number" 
                                               class="form-control" 
                                               id="quantity" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="<?php echo (int)$product['stock']; ?>" 
                                               required 
                                               <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                        <label for="quantity">Quantity</label>
                                    </div>
                                    
                                    <button type="submit" 
                                            name="add_to_cart" 
                                            class="btn btn-primary btn-lg"
                                            <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                        <i class="bi bi-cart-plus me-2"></i>
                                        <?php echo $product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.querySelector('#quantity').addEventListener('change', function(e) {
            const max = <?php echo (int)$product['stock']; ?>;
            this.value = Math.max(1, Math.min(max, parseInt(this.value) || 1));
        });
    </script>
</body>
</html>
