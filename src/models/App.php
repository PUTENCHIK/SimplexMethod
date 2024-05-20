<?php

namespace App;

include_once "States.php";
include_once "src/config.php";

class App {
    private int $state;
    private int $n;
    private int $m;

    public function __construct() {
        $this->state = States::$input_values;
        $this->n = get_consts()['min_variables'];
        $this->m = get_consts()['min_limits'];
//        $this->n = 2;
//        $this->m = 2;
    }

    public function getState(): int {
        return $this->state;
    }

    public function setState(string $state): void {
        $this->state = $state;
    }

    public function getN(): int {
        return $this->n;
    }

    public function setN(int $n): void {
        $this->n = $n;
    }

    public function getM(): int {
        return $this->m;
    }

    public function setM(int $m): void {
        $this->m = $m;
    }

    public function check_state(int $st): bool {
        return $this->state === $st;
    }
}