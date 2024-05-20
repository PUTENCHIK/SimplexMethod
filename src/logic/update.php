<?php

namespace App;

include_once 'src/models/App.php';
//include_once 'src/config.php';

if (! isset($_SESSION)) {
    session_start();
}

$app = $_SESSION['app'] ?? null;
//$consts = get_consts();

if (! is_null($app)) {
    if (isset($_POST)) {
        if (isset($_POST['variable-amount'])) {
            $app->setN($_POST['variable-amount']);
        }
        if (isset($_POST['limit-amount'])) {
            $app->setM($_POST['limit-amount']);
        }
        $_SESSION['app'] = $app;
    }
}

//header('Location: ../../templates/simplex-method.php');
//exit;