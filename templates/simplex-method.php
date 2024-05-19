<?php

namespace app\templates;

use app\html\GoalFunctionRow;

include "app/html/Header.php";
include "app/html/Button.php";
include "app/html/InputRow.php";
include "app/html/GoalFunctionRow.php";
?>

<html lang="ru">
    <head>
        <title>Симплекс метод - SimplexMethod</title>
        <meta charset="UTF-8">
        <link href="../static/css/simplex-method-style.css" rel="stylesheet">
    </head>
    <body>
        <?= new \app\html\Header("../static/images") ?>

        <div class="main">
            <div class="data-container container horizontal">
                <div class="settings-box">
                    <label>
                        <span>Количество переменных:</span>
                        <input name="variable-amount" type="number" min="2" max="10">
                    </label>
                    <label>
                        <span>Количество ограничений:</span>
                        <input name="limit-amount" type="number" min="2" max="10">
                    </label>
                </div>
                <div class="goal-function-box">
                    <?= new GoalFunctionRow(6) ?>
                </div>
                <div class="limits-box">

                </div>
            </div>
            <div class="buttons-box">
                <?= new \app\html\Button('submit', ['primary', 'horizontal'], 'Вычислить ответ') ?>
            </div>

            <script src="../static/js/input_listener.js"></script>
        </div>
    </body>
</html>