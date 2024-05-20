<?php

namespace App;

include_once "Element.php";

class Button extends Element {
    public string $type;
    public string $classes;
    public string $name;
    public ?string $form;
    public string $text;

    public function __construct($type = "submit",
                                $classes = ["secondary"],
                                $name = "default",
                                $form = null,
                                $text = "Button") {
        $this->type = $type;
        $this->classes = join(' ', $classes);
        $this->name = $name;
        $this->form = $form;
        $this->text = $text;
    }

    public function render(): string {
        $form = is_null($this->form) ? '' : "form=$this->form";
        return "
            <button class='$this->classes' type='$this->type' name='$this->name' $form>
                <span class='horizontal'>$this->text</span>
            </button>
        ";
    }
}