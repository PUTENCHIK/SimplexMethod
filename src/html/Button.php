<?php

namespace App;

include_once "Element.php";

class Button extends Element {
    public string $type;
    public string $classes;
    public string $text;
    public string $name;

    public function __construct($type = "submit", $classes = ["secondary"], $name = "default", $text = "Button") {
        $this->type = $type;
        $this->classes = join(' ', $classes);
        $this->text = $text;
        $this->name = $name;
    }

    public function render(): string {
        return "
            <button class='$this->classes' type='$this->type' name='$this->name'>
                <span class='horizontal'>$this->text</span>
            </button>
        ";
    }
}