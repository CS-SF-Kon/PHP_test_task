<?php
require '../config.php';

session_start();

if (empty($_SESSION['user'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit("Требуется авторизация");
}

function checkCsrfToken() {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ошибка безопасности: недействительный CSRF-токен");
    }
}

checkCsrfToken();

$userId = $_SESSION['user']['id'];
$errors = [];

$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (empty($currentPassword)) {
    $errors[] = "Введите текущий пароль";
}

if (strlen($newPassword) < 8) {
    $errors[] = "Новый пароль должен содержать минимум 8 символов";
} elseif (!preg_match('/[0-9]/', $newPassword)) {
    $errors[] = "Пароль должен содержать хотя бы одну цифру";
}

if ($newPassword !== $confirmPassword) {
    $errors[] = "Новые пароли не совпадают";
}

// Проверка текущего пароля
if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $errors[] = "Текущий пароль неверен";
        }
    } catch (PDOException $e) {
        $errors[] = "Ошибка проверки пароля";
    }
}

// Смена пароля
if (empty($errors)) {
    try {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $userId]);
        
        
        $_SESSION['password_success'] = true;
        header("Location: " . BASE_URL . "/views/account/change_password.php");
        exit();
    } catch (PDOException $e) {
        $errors[] = "Ошибка обновления пароля";
    }
}

// Если есть ошибки
$_SESSION['password_errors'] = $errors;
header("Location: " . BASE_URL . "/views/account/change_password.php");
exit();
?>