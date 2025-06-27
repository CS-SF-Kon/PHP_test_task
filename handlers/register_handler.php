<?php
require '../config.php';

$data = [
    'login' => trim($_POST['login'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'password' => $_POST['password'] ?? '',
    'confirm_password' => $_POST['confirm_password'] ?? ''
];

$errors = [];

foreach (['login', 'email', 'phone', 'password', 'confirm_password'] as $field) {
    if (empty($data[$field])) {
        $errors[] = "Поле " . ucfirst(str_replace('_', ' ', $field)) . " обязательно для заполнения";
    }
}

// для быстрой проверки работоспособности отключал эту хрень, но в целом работает
if ($data['password'] < 8) {
    $errors[] = "Новый пароль должен содержать минимум 8 символов";
} elseif (!preg_match('/[0-9]/', $data['password'])) {
    $errors[] = "Пароль должен содержать хотя бы одну цифру";
}

if ($data['password'] !== $data['confirm_password']) {
    $errors[] = "Пароли не совпадают";
}

if (empty($errors)) {
    try {
        $checks = [
            'login' => 'Логин уже занят',
            'email' => 'Email уже используется',
            'phone' => 'Телефон уже зарегистрирован'
        ];
        
        foreach ($checks as $field => $error) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = ?");
            $stmt->execute([$data[$field]]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = $error;
            }
        }
    } catch (PDOException $e) {
        $errors[] = "Ошибка проверки данных: " . $e->getMessage();
    }
}

if (empty($errors)) {
    try {
        $options = [
            'cost' => 12,
        ];
        $hashedPassword = password_hash(trim($_POST['password']), PASSWORD_BCRYPT, $options);
        
        $stmt = $pdo->prepare("INSERT INTO users (login, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['login'], $data['email'], $data['phone'], $hashedPassword]);
        
        $_SESSION['registration_success'] = true;
        header("Location: " . BASE_URL . "/views/auth/login.php?success=1");
        exit();
    } catch (PDOException $e) {
        $errors[] = "Ошибка регистрации: " . $e->getMessage();
    }
}

if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $data;
    header("Location: ../views/auth/register.php");
    exit();
}
?>