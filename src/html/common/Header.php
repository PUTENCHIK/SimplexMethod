<?php

namespace App;

include_once "Element.php";

class Header extends Element {
    public string $page_name;

    public function __construct($page_name = "default name") {
        $this->page_name = $page_name;
    }

    public function render(): string {
        return "
            <div class='header'>
                <div class='header__content'>
                    <a href='main.php'><img class='logo' src='../static/images/calculator.png' alt='logo'></a>
                    <div class='site-title'>$this->page_name</div>
                    <div>О сайте</div>
                </div>
            </div>
        ";
    }
}