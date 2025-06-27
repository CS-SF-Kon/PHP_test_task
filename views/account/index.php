<?php
require __DIR__ . '/../../config.php';

session_start();

if (empty($_SESSION['user'])) {
    header("Location: " . BASE_URL . "/views/auth/login.php");
    exit();
}

$updateSuccess = $_SESSION['profile_update_success'] ?? false;
$errors = $_SESSION['profile_errors'] ?? [];
$formData = $_SESSION['profile_form_data'] ?? $_SESSION['user'];
unset($_SESSION['profile_update_success'], $_SESSION['profile_errors'], $_SESSION['profile_form_data']);

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой аккаунт</title>
    <link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="account-container">
        <?php if ($updateSuccess): ?>
            <div class="success-message">Данные успешно обновлены!</div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <h1>Мой профиль</h1>
        
        <form action="<?= BASE_URL ?>/handlers/update_profile.php" method="post" class="profile-form">            
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" 
                       value="<?= htmlspecialchars($formData['login']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($formData['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон:</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?= htmlspecialchars($formData['phone']) ?>" required>
            </div>
            
            <button type="submit" class="button">Сохранить изменения</button>
        </form>
        
        <div class="account-actions">
            <a href="<?= BASE_URL ?>/views/account/change_password.php" class="button">Сменить пароль</a>
            <a href="<?= BASE_URL ?>/handlers/logout.php" class="button logout">Выйти</a>
        </div>
    </div>
</body>
</html>