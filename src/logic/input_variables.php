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
//            header('Content-Type: text/plain');
//            print_r($_POST);
            $app->data = new Data();
            try {
                $app->data->read_post($_POST, $app->getN(), $app->getM());
//                print_r($app->data->toArray());
            } catch (\Exception $ex) {
                echo $ex;
            }
            $app->setState(AppStates::$show_answer);
        }
        $_SESSION['app'] = $app;
    }
}

header('Location: ../../templates/simplex-method.php');
exit;