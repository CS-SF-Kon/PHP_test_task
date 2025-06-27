<?php
require __DIR__ . '/../../config.php';

session_start();

if (empty($_SESSION['user'])) {
    header("Location: " . BASE_URL . "/auth/login");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = $_SESSION['password_errors'] ?? [];
$success = $_SESSION['password_success'] ?? false;
unset($_SESSION['password_errors'], $_SESSION['password_success']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Смена пароля</title>
    <link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <?php if ($success): ?>
            <div class="success-message">Пароль успешно изменён!</div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <h1>Смена пароля</h1>
        
        <form action="<?= BASE_URL ?>/handlers/change_password.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label for="current_password">Текущий пароль:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">Новый пароль:</label>
                <input type="password" id="new_password" name="new_password" required>
                <small>Минимум 8 символов, включая цифры</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Подтвердите новый пароль:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="button">Сменить пароль</button>
        </form>
        
        <a href="<?= BASE_URL ?>/views/account" class="button">Назад в профиль</a>
    </div>
</body>
</html>