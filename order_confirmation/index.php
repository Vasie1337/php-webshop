<?php
include '../components/topbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle text-success display-1"></i>
                        </div>
                        <h1 class="h2 mb-4">Order Confirmation</h1>
                        <p class="lead mb-4">Your order has been successfully processed.</p>
                        <div class="d-grid gap-3">
                            <a href="../products" class="btn btn-primary">
                                <i class="bi bi-cart me-2"></i>Continue Shopping
                            </a>
                            <a href="../orders" class="btn btn-outline-secondary">
                                <i class="bi bi-list-ul me-2"></i>View My Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>