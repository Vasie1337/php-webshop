<?php
require_once '../models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    if (User::register($username, $password, $firstname, $lastname, $email)) {
        header('Location: login.php');
        exit;
    } else {
        $error = 'Registration failed';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="firstname">First name:</label><br>
        <input type="text" id="firstname" name="firstname" required><br>
        <label for="lastname">Last name:</label><br>
        <input type="text" id="lastname" name="lastname" required><br>
        <button type="submit">Register</button>
    </form>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>