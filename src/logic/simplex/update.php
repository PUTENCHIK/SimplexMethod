<?php

namespace App\Simplex;

include_once $_SERVER['DOCUMENT_ROOT'].'/src/models/common/App.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/src/models/simplex/SimplexData.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/src/models/simplex/SimplexMethod.php';

if (! isset($_SESSION)) {
    session_start();
}

$app = $_SESSION['simplex-app'] ?? null;
$consts = \App\get_simplex_consts();

if (! is_null($app)) {
    if (isset($_POST)) {
        if (isset($_POST['variable-amount'])) {
            $value = $_POST['variable-amount'];
            if (!preg_match('/^([0-9]+)$/ui', $value) or
                    (int)$value > $consts['max_variables'] or
                    (int)$value < $consts['min_variables']) {
                $app->setN($consts['min_variables']);
            } else {
                $app->setN($value);
            }
        }
        if (isset($_POST['limit-amount'])) {
            $value = $_POST['limit-amount'];
            if (!preg_match('/^([0-9]+)$/ui', $value) or
                    (int)$value > $consts['max_limits'] or
                    (int)$value < $consts['min_limits']) {
                $app->setM($consts['min_limits']);
            } else {
                $app->setM($value);
            }
        }
        $app->setState(\App\AppStates::$input_values);
//        $_SESSION['app'] = $app;
    }
}

header('Location: ../../../templates/simplex-method.php');
exit;