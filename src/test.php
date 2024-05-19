<?php

namespace src\models;

ini_set('display_errors', 1);
header('Content-Type: text/plain');

include_once 'src/models/Rational.php';

$a = new Rational(17, 124);
$b = new Rational(number: 123123);

echo "$a + $b = " . Rational::add($a, $b) . "\n";
echo "$a - $b = " . Rational::subtract($a, $b) . "\n";
echo "$a * $b = " . Rational::multiply($a, $b) . "\n";
echo "$a / $b = " . Rational::divide($a, $b) . "\n";