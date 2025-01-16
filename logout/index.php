<?php
require_once '../models/user.php';
User::logout();
header('Location: ../');
exit;
