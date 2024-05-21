<?php

namespace App\Test;

header('Content-Type: text/plain');

$arr = [1, 2, 3];

$copy = $arr;


$copy[0] = 10;

print_r($copy);
print_r($arr);