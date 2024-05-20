<?php


namespace App;


abstract class Element {
    abstract public function __construct();

    public function __toString(): string {
        return $this->render();
    }

    abstract function render(): string;
}