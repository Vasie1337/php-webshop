<?php
include '../components/topbar.php';

require_once 'models/user.php';
require_once 'models/cart.php';

User::init();
Cart::init();

if (!User::isLoggedIn()) {
    header('Location: ../login');
    exit;
}

$username = User::getUsername();
$firstname = User::getFirstname();
$lastname = User::getLastname();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle display-1 text-primary"></i>
                            <h1 class="h3 mt-3">Welcome, <?php echo htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname); ?>!</h1>
                            <p class="text-muted">Nice to see you again</p>
                        </div>

                        <div class="list-group mb-4">
                            <div class="list-group-item">
                                <div class="d-flex w-100">
                                    <div class="me-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Username</h6>
                                        <p class="mb-0"><?php echo htmlspecialchars($username); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100">
                                    <div class="me-3">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Email</h6>
                                        <p class="mb-0"><?php echo htmlspecialchars(User::getEmail()); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="../products" class="btn btn-primary">
                                <i class="bi bi-cart me-2"></i>Start Shopping
                            </a>
                            <a href="../orders" class="btn btn-outline-primary">
                                <i class="bi bi-box me-2"></i>View Orders
                            </a>
                            <a href="../logout" class="btn btn-outline-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
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
