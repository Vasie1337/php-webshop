<?php
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
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname); ?>!</h1>
    <p>Username: <?php echo htmlspecialchars($username); ?></p>
    <p>Email: <?php echo htmlspecialchars(User::getEmail()); ?></p>

    <a href="../logout">Logout</a>
</body>
</html>
