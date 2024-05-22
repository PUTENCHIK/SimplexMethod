<?php

namespace App\Test;

use App\Million;
use App\Rational;

header('Content-Type: text/plain');

include_once "src/models/common/Rational.php";
include_once "src/models/common/Million.php";
include_once "src/models/common/Data.php";

$r1 = new Rational(-3, 4);
$r2 = new Rational(2, 11);

echo (string)Rational::less($r1, $r2);

