<?php


namespace app\html;

include_once "Element.php";

class Button extends Element {
    public string $type;
    public string $classes;
    public string $text;

    public function __construct($type = "submit", $classes = ["secondary"], $text = "Button") {
        $this->type = $type;
        $this->classes = join(' ', $classes);
        $this->text = $text;
    }

    public function render(): string {
        return "
            <button class='$this->classes' type='$this->type'>
                <span class='horizontal'>$this->text</span>
            </button>
        ";
    }
}