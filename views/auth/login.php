<?php
require __DIR__ . '/../../config.php';

session_start();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

$fromRegistration = isset($_GET['success']);

$registrationSuccess = $_SESSION['registration_success'] ?? false;
unset($_SESSION['registration_success']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <h1>Вход в систему</h1>
        <?php if ($registrationSuccess || $fromRegistration): ?>
            <div class="success-message">
                Регистрация прошла успешно! Теперь войдите в систему.
            </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/handlers/login_handler.php" method="post">
            <input type="text" name="login" placeholder="Email или телефон" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" class="button">Войти</button>
        </form>
        
        <div class="auth-links">
            <a href="<?= BASE_URL ?>/views/auth/register.php">Регистрация</a>
            <a href="<?= BASE_URL ?>/index.php">На главную</a>
        </div>
    </div>
</body>
</html>