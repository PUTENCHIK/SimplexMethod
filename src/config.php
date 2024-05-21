<?php

namespace App;

function get_simplex_consts(): array {
    return [
        'min_variables' => 2,
        'max_variables' => 10,
        'min_limits' => 2,
        'max_limits' => 10,
    ];
}

function get_snow_consts(): array {
    return [
        'min_sectors' => 2,
        'max_sectors' => 10,
        'min_places' => 2,
        'max_places' => 10,
    ];
}