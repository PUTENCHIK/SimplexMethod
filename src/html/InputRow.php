<?php

namespace App;

include_once "Element.php";

class InputRow extends Element {
    public int $amount;
    public ?string $prename;

    public function __construct(int $amount = 2, string $prename = null) {
        $this->amount = $amount;
        $this->prename = $prename;
    }

    public function render(): string {
        $html = "<div>";
        for ($i = 1; $i <= $this->amount; $i++) {
            $name = is_null($this->prename) ? "" : "name='$this->prename-$i'";
            $html .= "<input class='coefficient' type='text' $name> <span>x<sub>$i</sub></span>";
            if ($i !== $this->amount) {
                $html .= " + ";
            }
        }
        $html .= "</div>";

        return $html;
    }
}