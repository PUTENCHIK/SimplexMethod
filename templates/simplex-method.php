<?php

namespace App\Simplex;

include_once "src/html/common/Header.php";
include_once "src/html/common/Button.php";
include_once "src/html/simplex/GoalFunctionRow.php";
include_once "src/html/simplex/LimitRow.php";

include_once "src/models/common/AppStates.php";
include_once "src/models/common/App.php";
include_once "src/models/simplex/SimplexData.php";
include_once "src/models/simplex/SimplexMethod.php";

include_once "src/config.php";

if (! isset($_SESSION)) {
    session_start();
}
if (! isset($_SESSION['simplex-app'])) {
    $app = new \App\App(new SimplexData());
    $_SESSION['simplex-app'] = $app;
} else {
    $app = $_SESSION['simplex-app'];
}

//$app = new \App\App(new SimplexData());
//$_SESSION['simplex-app'] = $app;

$function = ! empty($app->data->getFunction()) ? $app->data->getFunction() : null;
$limits = ! empty($app->data->getLimits()) ? $app->data->getLimits() : null;

//header('Content-Type: text/plain');
//if (! is_null($app->answer))  {
//	print_r($app->answer->toArray());
//}

$app->update_state();

$consts = \App\get_simplex_consts();

?>

<html lang="ru">
    <head>
        <title>Симплекс метод</title>
        <meta charset="UTF-8">
        <link href="../static/css/simplex-method-style.css" rel="stylesheet">
    </head>
    <body>
        <?= new \App\Header("Симплекс метод") ?>

        <div class="main">
            <?php if (! empty($app->getErrors())): ?>
                <div class="container horizontal error"><?= $app->getErrors()[0] ?></div>
            <?php $app->clear_errors(); ?>
            <?php endif ?>

            <div class="data-container container settings">
                <form method="post" action="../src/logic/simplex/update.php" name="input-consts">
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
				<form method="post" action="../src/logic/simplex/input_variables.php" id="data">
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
                <?php if (! $app->check_state(\App\AppStates::$default_values)): ?>
                    <form method="post" action="../src/logic/simplex/input_variables.php">
                        <?= new \App\Button('submit', ['secondary', 'horizontal'], 'reset', text: 'Сбросить') ?>
                    </form>
                <?php endif ?>

<!--                --><?php //if ($app->check_state(AppStates::$default_values) or $app->check_state(AppStates::$input_values)): ?>
                <?= new \App\Button('submit', ['primary'], 'solve', 'data', 'Решить') ?>
<!--                --><?php //endif ?>
            </div>

			<?php if ($app->check_state(\App\AppStates::$show_answer)): ?>

				<?php

				$answer = $app->answer->toArray();
				$original = $answer['original'];
				$original_limits = $original['limits'];

				$artificial = $answer['artificial'];
				$artificial_limits = $artificial['limits'];

				?>

                <div class="horizontal container matrix-box">
                    <div class="data-container">
                        <h2>Исходная матрица</h2>
                    </div>
                    <div class="horizontal matrix">
                        <div class="matrix-head-row">
                            <?php for ($i = 1; $i <= count($original['function']['values']); $i++): ?>
                                <div class="matrix-cell">
                                    <span>x<sub><?= $i ?></sub></span>
                                </div>
                            <?php endfor ?>
                            <div class="matrix-cell">b</div>
                        </div>
                        <?php foreach ($original_limits as $limit): ?>
                            <div class="matrix-row">
                                <?php foreach ($limit['values'] as $value): ?>
                                    <div class="matrix-cell"><?= $value ?></div>
                                <?php endforeach ?>
                                <div class="matrix-cell"><?= $limit['b'] ?></div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

				<div class="horizontal container matrix-box">
					<h2>Матрица с искусственным базисом</h2>
					<div class="horizontal matrix">
						<div class="matrix-head-row">
                            <?php for ($i = 1; $i <= count($artificial['function']['values']); $i++): ?>
								<div class="matrix-cell">
									<span>x<sub><?= $i ?></sub></span>
								</div>
                            <?php endfor ?>
							<div class="matrix-cell">b</div>
						</div>
                        <?php foreach ($artificial_limits as $limit): ?>
							<div class="matrix-row">
                                <?php foreach ($limit['values'] as $value): ?>
									<div class="matrix-cell"><?= $value ?></div>
                                <?php endforeach ?>
								<div class="matrix-cell"><?= $limit['b'] ?></div>
							</div>
                        <?php endforeach ?>
					</div>
				</div>

				<?php
//				header('Content-Type: text/plain');
				print_r($artificial);
				?>
			<?php endif ?>
        </div>

        <script src="../static/js/simplex_input_listener.js"></script>
    </body>
</html>