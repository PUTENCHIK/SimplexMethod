<?php
include "app/html/Header.php";
?>

<html lang="ru">
    <head>
        <title>Главная - SimplexMethod</title>
        <meta charset="UTF-8">
        <link href="../static/css/main-style.css" rel="stylesheet">
    </head>
    <body>
        <?= new \app\html\Header("../static/images") ?>

        <div class="main">
            <div class="instruments-container container horizontal">
                <h1>Инструменты:</h1>
                <a href="simplex-method.php">
                    <div class="list-element">Симплекс метод</div>
                </a>
                <a href="#">
                    <div class="list-element">Какой-то ещё метод</div>
                </a>
            </div>
        </div>
        <script src="../static/js/lists.js"></script>
    </body>
</html>