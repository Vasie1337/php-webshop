<?php
require_once '../models/product.php';

$products = Product::getAllProducts();
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
            <li>
                <a href="product.php?product_id=<?php echo $product['product_id']; ?>">
                    <?php echo htmlspecialchars($product['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>