<?php
require_once '../models/user.php';

// Call the static logout method to end the session
User::logout();

// Redirect the user to the login page after logging out
header('Location: login.php');
exit;
