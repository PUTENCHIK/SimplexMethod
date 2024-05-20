<?php

namespace App;

include_once "src/html/Header.php";
include_once "src/html/Button.php";
include_once "src/models/States.php";
include_once "src/models/App.php";
include_once "src/html/GoalFunctionRow.php";
include_once "src/html/LimitRow.php";

include_once "src/config.php";

if (! isset($_SESSION)) {
    session_start();
}
if (! isset($_SESSION['app'])) {
    $app = new App();
    $_SESSION['app'] = $app;
} else {
    $app = $_SESSION['app'];
}

//$app = new App();
//$_SESSION['app'] = $app;

$consts = get_consts();

?>

<html lang="ru">
    <head>
        <title>Симплекс метод - SimplexMethod</title>
        <meta charset="UTF-8">
        <link href="../static/css/simplex-method-style.css" rel="stylesheet">
    </head>
    <body>
        <?= new Header("Симплекс метод") ?>

        <script src="../static/js/input_listener.js"></script>
        <div class="main">
            <div class="data-container container horizontal settings">
                <form method="post" action="../src/logic/update.php" name="input-consts">
                    <div class="settings-box">
                        <label>
                            <span>Количество переменных:</span>
                            <input name="variable-amount" type="number" form="form-generate"
                                   min="<?= $consts['min_variables'] ?>"
                                   max="<?= $consts['max_variables'] ?>"
                                   value="<?= $app->getN() ?>">
                        </label>
                        <label>
                            <span>Количество ограничений:</span>
                            <input name="limit-amount" type="number" form="form-generate"
                                   min="<?= $consts['min_limits'] ?>"
                                   max="<?= $consts['max_limits'] ?>"
                                   value="<?= $app->getM() ?>">
                        </label>
                    </div>
                </form>
            </div>

            <div class="data-container container inputs">
                <div class="goal-function-box">
                    <?= new GoalFunctionRow($app->getN()) ?>
                </div>
                <div class="limits-box">
                    <?php for ($i = 1; $i <= $app->getM(); $i++): ?>
                        <?= new LimitRow($app->getN(), $i) ?>
                    <?php endfor ?>
                </div>
            </div>


            <div class="buttons-box">
                <?php if ($app->check_state(States::$input_values) or $app->check_state(States::$show_answer)): ?>
                    <form method="post" action="../src/logic/input_variables.php" id="form-reset">
                        <?= new Button('submit', ['secondary', 'horizontal'], 'reset', 'Сбросить') ?>
                    </form>
                <?php endif ?>

                <?php if ($app->check_state(States::$default_values) or $app->check_state(States::$input_values)): ?>
                    <form method="post" action="../src/logic/input_variables.php" id="form-solve">
                        <?= new Button('submit', ['primary', 'horizontal'], 'solve', 'Решить') ?>
                    </form>
                <?php endif ?>
            </div>
        </div>

        <script src="../static/js/input_listener.js"></script>
    </body>
</html>