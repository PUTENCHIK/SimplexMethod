<?php

namespace App;

include_once "AppStates.php";
include_once "Data.php";
include_once "src/config.php";

class App {
    private int $state;
    private int $n;
    private int $m;
    public Data $data;
    public array $errors;

    public function __construct() {
        $this->state = AppStates::$default_values;
        $consts = get_consts();
        $this->n = $consts['min_variables'];
        $this->m = $consts['min_limits'];
        $this->data = new Data();
        $this->errors = [];
    }

    public function getState(): int {
        return $this->state;
    }

    public function setState(int $state): void {
        $this->state = $state;
    }

    public function update_state(): void {
        $consts = get_consts();
        if (! $this->check_state(AppStates::$show_answer) and ($this->n !== $consts['min_variables'] or $this->m !== $consts['min_limits'])) {
            $this->state = AppStates::$input_values;
        }
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

    public function getErrors(): array {
        return $this->errors;
    }

    public function setErrors(array $errors): void {
        $this->errors = $errors;
    }

    public function add_error(string $text): void {
        $this->errors[] = $text;
    }

    public function clear_errors(): void {
        $this->errors = [];
    }

    public function check_state(int $st): bool {
        return $this->state === $st;
    }
}