<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой первый лендинг</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Добро пожаловать!</h1>
        <nav>
            <a href="#about">О нас</a>
            <a href="#contact">Контакты</a>
        </nav>
    </header>

    <main>
        <section id="about">
            <h2>О нас</h2>
            <p>Это тестовый лендинг, созданный на чистом PHP. Здесь можно добавить описание проекта.</p>
        </section>

        <section id="contact">
            <h2>Контакты</h2>
            <form action="send.php" method="post">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Отправить</button>
            </form>
        </section>
    </main>

    <footer>
        &copy; <?php echo date('Y'); ?> Мой лендинг
    </footer>
</body>
</html>