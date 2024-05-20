<?php

namespace App;

include_once "Element.php";
include_once "InputRow.php";

class LimitRow extends Element {
    public int $amount;
    public int $number;

    public function __construct(int $amount = 2, int $number = 1) {
        $this->amount = $amount;
        $this->number = $number;
    }

    public function render(): string {
        $input_row = new InputRow($this->amount);
        $name = "b-$this->number";

        $html = "<div class='horizontal'>";
        $html .= $input_row->render();
        $html .= "<select>
                      <option><=</option>
                      <option>=</option>
                      <option>>=</option>
                  </select>";
        $html .= "<input class='coefficient' type='text' name='$name'>";
        $html .= "</div>";
        return $html;
    }
}