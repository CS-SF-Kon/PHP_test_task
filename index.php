<?php
require __DIR__ . '../config.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'register':
        require 'views/auth/register.php';
        break;
    case 'login':
        require 'views/auth/login.php';
        break;
    default:
        require 'views/index.php';
}
?>