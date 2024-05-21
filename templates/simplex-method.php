<?php

namespace App;

include_once "src/html/Header.php";
include_once "src/html/Button.php";
include_once "src/models/AppStates.php";
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

$function = ! empty($app->data->getFunction()) ? $app->data->getFunction() : null;
$limits = ! empty($app->data->getLimits()) ? $app->data->getLimits() : null;
//print_r($limits);

$app->update_state();

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

        <div class="main">
            <?php if (! empty($app->getErrors())): ?>
                <div class="container horizontal error"><?= $app->getErrors()[0] ?></div>
            <?php $app->clear_errors(); ?>
            <?php endif ?>

            <div class="data-container container settings">
                <form method="post" action="../src/logic/update.php" name="input-consts">
                    <div class="settings-box">
                        <label>
                            <span>Количество переменных:</span>
                            <input name="variable-amount" type="number"
                                   min="<?= $consts['min_variables'] ?>"
                                   max="<?= $consts['max_variables'] ?>"
                                   value="<?= $app->getN() ?>">
                        </label>
                        <label>
                            <span>Количество ограничений:</span>
                            <input name="limit-amount" type="number"
                                   min="<?= $consts['min_limits'] ?>"
                                   max="<?= $consts['max_limits'] ?>"
                                   value="<?= $app->getM() ?>">
                        </label>
                    </div>
                </form>
            </div>

            <div class="data-container container inputs">
				<form method="post" action="../src/logic/input_variables.php" id="data">
					<div class="goal-function-box">
                        <?= new GoalFunctionRow($app->getN(), $function) ?>
					</div>
					<div class="limits-box">
                        <?php for ($i = 1; $i <= $app->getM(); $i++): ?>
							<?php $limit = $limits[$i-1] ?? null ?>
                            <?= new LimitRow($app->getN(), $i, $limit) ?>
                        <?php endfor ?>
					</div>
				</form>
            </div>

            <div class="buttons-box">
                <?php if (! $app->check_state(AppStates::$default_values)): ?>
                    <form method="post" action="../src/logic/input_variables.php">
                        <?= new Button('submit', ['secondary', 'horizontal'], 'reset', text: 'Сбросить') ?>
                    </form>
                <?php endif ?>

<!--                --><?php //if ($app->check_state(AppStates::$default_values) or $app->check_state(AppStates::$input_values)): ?>
                <?= new Button('submit', ['primary'], 'solve', 'data', 'Решить') ?>
<!--                --><?php //endif ?>
            </div>

			<?php if ($app->check_state(AppStates::$show_answer)): ?>
                <div class="horizontal container matrix-box">
                    <div class="data-container">
                        <h2>Исходная матрица</h2>
                    </div>
                    <div class="horizontal matrix">
                        <div class="matrix-head-row">
                            <?php for ($i = 1; $i <= count($limits); $i++): ?>
                                <div class="matrix-cell">
                                    <span>x<sub><?= $i ?></sub></span>
                                </div>
                            <?php endfor ?>
                            <div class="matrix-cell">b</div>
                        </div>
                        <?php foreach ($limits as $limit): ?>
                            <div class="matrix-row">
                                <?php foreach ($limit['values'] as $value): ?>
                                    <div class="matrix-cell"><?= $value ?></div>
                                <?php endforeach ?>
                                <div class="matrix-cell"><?= $limit['b'] ?></div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
			<?php endif ?>
        </div>

        <script src="../static/js/input_listener.js"></script>
    </body>
</html>