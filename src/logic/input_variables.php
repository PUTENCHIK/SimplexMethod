<?php

namespace App;

include_once 'src/models/App.php';
include_once 'src/config.php';

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
            $app->setState(States::$default_values);
        } else if (isset($_POST['solve'])) {
            $app->setN($_POST['variable-amount'] ?? $consts['min_variables']);
            $app->setM($_POST['limit-amount'] ?? $consts['min_limits']);
            $app->setState(States::$show_answer);
        }
        $_SESSION['app'] = $app;
    }
}

header('Location: ../../templates/simplex-method.php');
exit;