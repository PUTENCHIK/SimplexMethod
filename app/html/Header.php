<?php


namespace app\html;

include_once "Element.php";

class Header extends Element {
    public string $page_name;

    public function __construct($page_name = "default name") {
        $this->page_name = $page_name;
    }

    public function render(): string {
        include "src/config.php";
        $img_path = get_consts()['images_path'];

        return "
            <div class='header'>
                <div class='header__content'>
                    <img class='logo' src='$img_path/calculator.png' alt='logo'>
                    <div class='site-title'>Симплекс метод</div>
                    <div>О сайте</div>
                </div>
            </div>
        ";
    }
}