<?php

namespace App\Simplex;

include_once 'src/html/common/Element.php';
include_once 'src/html/common/InputRow.php';
include_once 'src/models/simplex/FunctionTypes.php';

class GoalFunctionRow extends \App\Element {
    public int $amount;
    public ?array $data;

    public function __construct(int $amount = 2, array $data = null) {
        $this->amount = $amount;
        $this->data = $data;
    }

    public function render(): string {
        $input_row = new \App\InputRow($this->amount, "f", $this->data['values'] ?? null);
        $selected_max = (int)$this->data['type'] === FunctionTypes::$max ? ' selected' : '';
        $selected_min = (int)$this->data['type'] === FunctionTypes::$min ? ' selected' : '';

        $html = "<div class='horizontal'>";
        $html .= "<span>f(x)=</span>";
        $html .= $input_row->render();
        $html .= "<span>→</span>";
        $html .= "<select name='type'>
                      <option$selected_max>max</option>
                      <option$selected_min>min</option>
                  </select>";
        $html .= "</div>";

        return $html;
    }
}