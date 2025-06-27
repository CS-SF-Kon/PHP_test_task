<?php
require '../config.php';


session_start();

if (empty($_SESSION['user'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit("Требуется авторизация");
}

$userId = $_SESSION['user']['id'];
$errors = [];

$data = [
    'login' => trim($_POST['login'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? '')
];

foreach ($data as $field => $value) {
    if (empty($value)) {
        $errors[] = "Поле " . ucfirst($field) . " обязательно для заполнения";
    }
}

if (empty($errors)) {
    try {
        $checks = [
            'login' => 'Логин уже занят',
            'email' => 'Email уже используется',
            'phone' => 'Телефон уже зарегистрирован'
        ];
        
        foreach ($checks as $field => $error) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = ? AND id != ?");
            $stmt->execute([$data[$field], $userId]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = $error;
            }
        }
    } catch (PDOException $e) {
        $errors[] = "Ошибка проверки данных";
    }
}

if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET login = ?, email = ?, phone = ?
            WHERE id = ?
        ");
        $stmt->execute([$data['login'], $data['email'], $data['phone'], $userId]);
        
        $_SESSION['user'] = array_merge($_SESSION['user'], $data);
        
        $_SESSION['profile_update_success'] = true;
        header("Location: " . BASE_URL . "/views/account/index.php");
        exit();
    } catch (PDOException $e) {
        $errors[] = "Ошибка обновления данных";
    }
}

$_SESSION['profile_errors'] = $errors;
$_SESSION['profile_form_data'] = $data;
header("Location: " . BASE_URL . "/views/account/index.php");
exit();
?>