<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    echo "<h1>Спасибо, $name!</h1>";
    echo "<p>Ваш email: $email</p>";
    echo '<a href="/php_test_task">Вернуться</a>';
}
?>