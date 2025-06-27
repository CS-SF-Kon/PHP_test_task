<?php
// данные для подключения к БД
$host = 'localhost';
$db   = 'auth_db';
$user = 'root';
$pass = '';
$charset = 'utf8';

// Настройка PDO на шедшем в комплекте MySQL
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// настройка корневого каталога
define('BASE_URL', '/php_test_task');
?>