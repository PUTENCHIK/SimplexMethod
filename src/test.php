<?php

namespace App;

include_once "src/models/Data.php";

header('Content-Type: text/plain');

$a = '';

if (isset($a) and strlen($a) > 0) {
    echo "okay";
    echo count_chars($a) > 0;
}
else {
    echo "no";
}