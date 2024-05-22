<?php

namespace App;

include $_SERVER['DOCUMENT_ROOT'].'/src/html/common/Header.php';

?>

<html lang="ru">
    <head>
        <title>Главная</title>
        <meta charset="UTF-8">
        <link href="../static/css/main-style.css" rel="stylesheet">
    </head>
    <body>
        <?= new Header("Главная страница") ?>

        <div class="main">
            <div class="instruments-container container horizontal">
                <h1>Инструменты:</h1>
                <a href="simplex-method.php">
                    <div class="list-element">Симплекс метод</div>
                </a>
                <a href="snow-task.php">
                    <div class="list-element">Задача об уборке снега</div>
                </a>
            </div>
        </div>
        <script src="../static/js/lists.js"></script>
    </body>
</html>