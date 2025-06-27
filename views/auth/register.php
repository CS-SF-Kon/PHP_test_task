<?php
require __DIR__ . '/../../config.php';
session_start();
$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
    <script>
        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorElement = document.getElementById('password-error');
            
            if (password !== confirmPassword) {
                errorElement.textContent = 'Пароли не совпадают';
                return false;
            }
            errorElement.textContent = '';
            return true;
        }
    </script>
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>
<body>
    <div class="auth-container">
        <h1>Регистрация</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/handlers/register_handler.php" method="post" onsubmit="return validatePasswords()">            
            <input type="text" name="login" placeholder="Логин" 
                   value="<?= htmlspecialchars($formData['login'] ?? '') ?>" required>
            
            <input type="email" name="email" placeholder="Email" 
                   value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
            
            <input type="tel" name="phone" placeholder="Телефон" 
                   value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" required>
            
            <input type="password" name="password" placeholder="Пароль" required>

            <input type="password" name="confirm_password" id="confirm_password" placeholder="Подтвердите пароль" required>

            <div id="password-error" class="error-text"></div>

            <div class="smart-captcha" data-sitekey="<ключ_клиентской_части>" data-hl="ru"> <!--нет ключа, работу по внедрению капчи не довёл до конца-->

            </div>

            <button type="submit" class="button">Зарегистрироваться</button>
        </form>
        

        <a href="<?= BASE_URL ?>/index.php">На главную</a>
    </div>
</body>
</html>