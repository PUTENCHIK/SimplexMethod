<?php

namespace App;

use app\src\models\Rational;

include_once "src/models/Rational.php";

ini_set('display_errors', 1);
header('Content-Type: text/plain');

$r = new Rational();

echo $r . "\n";