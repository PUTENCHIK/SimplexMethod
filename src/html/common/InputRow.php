<?php

namespace App;

include_once "Element.php";

class InputRow extends Element {
    public int $amount;
    public ?string $prename;
    public ?array $values;

    public function __construct(int $amount = 2, string $prename = null, array $values = null) {
        $this->amount = $amount;
        $this->prename = $prename;
        $this->values = $values;
    }

    public function render(): string {
        $html = "<div>";
        for ($i = 1; $i <= $this->amount; $i++) {
            $name = is_null($this->prename) ? '' : "name='$this->prename[]'";
            $value = is_null($this->values) ? '' : "value='" . $this->values[$i-1] . "'";

            $html .= "<input class='coefficient' type='text' $value $name> <span>x<sub>$i</sub></span>";
            if ($i !== $this->amount) {
                $html .= " + ";
            }
        }
        $html .= "</div>";

        return $html;
    }
}