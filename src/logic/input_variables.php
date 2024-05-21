<?php

namespace App;

include_once 'src/models/App.php';
include_once 'src/config.php';
include_once 'src/models/Data.php';

if (! isset($_SESSION)) {
    session_start();
}

$app = $_SESSION['app'] ?? null;
$consts = get_consts();

if (! is_null($app)) {
    if (isset($_POST)) {
        if (isset($_POST['reset'])) {
            $app->setN($consts['min_variables']);
            $app->setM($consts['min_limits']);
            $app->data = new Data();
            $app->setState(AppStates::$default_values);
        } else if (isset($_POST['solve'])) {
            $app->data = new Data();
            $app->setErrors($app->data->read_post($_POST, $app->getN(), $app->getM()));
            if (empty($app->getErrors())) {
                $app->setState(AppStates::$show_answer);
            } else {
                $app->setState(AppStates::$input_values);
            }
        }
        $_SESSION['app'] = $app;
    }
}

header('Location: ../../templates/simplex-method.php');
exit;