<?php
require_once '../models/product.php';
require_once '../models/cart.php';

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header('Location: ../index.php');
    exit();
}

$productId = (int)$_GET['product_id'];
$product = Product::getProductById($productId);

if (!$product) {
    header('Location: ../index.php');
    exit();
}

Cart::init();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($quantity > 0 && $quantity <= $product['stock']) {
        Cart::addItem(
            $product['id'],
            $product['name'],
            $product['price'],
            $quantity
        );
        header('Location: product.php?product_id=' . $productId . '&added=1');
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
</head>
<body>
    <div class="product-container">
        <a href="../index.php" class="back-link">← Back to Products</a>
        
        <?php if ($addedToCart): ?>
            <div class="success-message">
                Product successfully added to cart!
            </div>
        <?php endif; ?>
        
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <?php if (isset($product['description'])): ?>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <?php endif; ?>
            
            <?php if (isset($product['price'])): ?>
                <p class="price">Price: $<?php echo number_format($product['price'], 2); ?></p>
            <?php endif; ?>
            
            <?php if (isset($product['stock'])): ?>
                <p>Stock: <?php echo (int)$product['stock']; ?> units</p>
            <?php endif; ?>
            
            <?php if (isset($product['sku'])): ?>
                <p>SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
            <?php endif; ?>
            
            <form method="POST" class="add-to-cart-form">
                <label for="quantity">Quantity:</label>
                <input type="number" 
                       id="quantity" 
                       name="quantity" 
                       value="1" 
                       min="1" 
                       max="<?php echo (int)$product['stock']; ?>" 
                       class="quantity-input"
                       required>
                
                <button type="submit" 
                        name="add_to_cart" 
                        class="add-to-cart-button"
                        <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                    <?php echo $product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('#quantity').addEventListener('change', function(e) {
            const max = <?php echo (int)$product['stock']; ?>;
            if (this.value > max) {
                this.value = max;
            }
            if (this.value < 1) {
                this.value = 1;
            }
        });
    </script>
</body>
</html>