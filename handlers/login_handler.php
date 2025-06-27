<?php
require '../config.php';

session_start();

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password']) ?? '';

$isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
$isPhone = preg_match('/^\+?[0-9]{10,15}$/', $login);

if (!$isEmail && !$isPhone) {
    $_SESSION['errors'] = ['Введите корректный email или телефон'];
    header("Location: " . BASE_URL . "/views/auth/login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT * FROM users 
        WHERE email = :login OR phone = :login
        LIMIT 1
    ");
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['errors'] = ['Нет такого пользователя'];
        header("Location: " . BASE_URL . "/views/auth/login.php");
        exit();
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['errors'] = ['Неправильный пароль'];
        header("Location: " . BASE_URL . "/views/auth/login.php");
        exit();
    }

    $_SESSION['user'] = [
        'id' => $user['id'],
        'login' => $user['login'],
        'email' => $user['email'],
        'phone' => $user['phone']
    ];

    $_SESSION['login_success'] = true;
    header("Location: " . BASE_URL . "/views/account/index.php");
    exit();

} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    $_SESSION['errors'] = ['Ошибка системы, попробуйте позже'];
    header("Location: " . BASE_URL . "/views/auth/login.php");
    exit();
}
?>