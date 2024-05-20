<?php

namespace App;

include_once "src/models/Data.php";

header('Content-Type: text/plain');

$arr = [
    '12' => [1, 2],
    'asdas' => 12312
];

print_r($arr ?? null);

//$data = new Data();
//
//echo Data::as_rational('78.345/234');