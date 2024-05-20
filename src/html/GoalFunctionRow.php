<?php

namespace App;

include_once 'Element.php';
include_once 'InputRow.php';

class GoalFunctionRow extends Element {
    public int $amount;

    public function __construct(int $amount = 2) {
        $this->amount = $amount;
    }

    public function render(): string {
        $input_row = new InputRow($this->amount, );

        $html = "<div class='horizontal'>";
        $html .= "<span>f(x)=</span>";
        $html .= $input_row->render();
        $html .= "<span>→</span>";
        $html .= "<select>
                      <option>max</option>
                      <option>min</option>
                  </select>";
        $html .= "</div>";

        return $html;
    }
}