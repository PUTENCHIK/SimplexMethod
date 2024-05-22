<?php

namespace App\Simplex;

include_once 'src/models/common/App.php';
include_once 'src/models/simplex/SimplexData.php';
include_once 'src/models/simplex/SimplexMethod.php';
include_once 'src/config.php';

if (! isset($_SESSION)) {
    session_start();
}

$app = $_SESSION['simplex-app'] ?? null;
$consts = \App\get_simplex_consts();

if (! is_null($app)) {
    if (isset($_POST)) {
        if (isset($_POST['reset'])) {
            $app->setN($consts['min_variables']);
            $app->setM($consts['min_limits']);
            $app->data = new SimplexData();
            $app->setAnswer(null);
            $app->setState(\App\AppStates::$default_values);
        } else if (isset($_POST['solve'])) {
            $app->data = new SimplexData($app->getN(), $app->getM());
            $app->setErrors($app->data->read_post($_POST, $app->getN(), $app->getM()));
            if (empty($app->getErrors())) {
                $app->setState(\App\AppStates::$show_answer);
                try {
                    $app->setAnswer(new SimplexMethod($app->data->toArray()));
                } catch (\Exception $ex) {
                    $app->add_error($ex->getMessage());
                }

            } else {
                $app->setState(\App\AppStates::$input_values);
            }
        }
    }
}

header('Location: ../../../templates/simplex-method.php');
exit;