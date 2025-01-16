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
</head>
<body>
    <h1>Products</h1>
    <ul>
        <?php foreach ($products as $product): ?>
            <?php if (isset($product['product_id']) && isset($product['name'])): ?>
                <li>
                    <a href="../product/index.php?product_id=<?php echo htmlspecialchars($product['product_id']); ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</body>
</html>