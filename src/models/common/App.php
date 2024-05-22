<?php

namespace App;

include_once 'AppStates.php';
include_once 'Data.php';
include_once 'Answer.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/src/config.php';

class App {
    private int $state;
    public Data $data;
    public ?Answer $answer;
    public array $errors;

    public function __construct(Data $data) {
        $this->state = AppStates::$default_values;
        $consts = get_simplex_consts();
        $this->data = $data;
        $this->errors = [];
    }

    public function getState(): int {
        return $this->state;
    }

    public function setState(int $state): void {
        $this->state = $state;
    }

    public function update_state(): void {
        $consts = \App\get_simplex_consts();
        if (! $this->check_state(AppStates::$show_answer) and ($this->getN() !== $consts['min_variables'] or $this->getM() !== $consts['min_limits'])) {
            $this->state = AppStates::$input_values;
        }
    }

    public function getN(): int {
        return $this->data->getN();
    }

    public function setN(int $n): void {
        $this->data->setN($n);
    }

    public function getM(): int {
        return $this->data->getM();
    }

    public function setM(int $m): void {
        $this->data->setM($m);
    }

    public function getAnswer(): Answer {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): void {
        $this->answer = $answer;
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