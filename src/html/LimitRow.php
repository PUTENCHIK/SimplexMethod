<?php

namespace App;

include_once "Element.php";
include_once "InputRow.php";
include_once 'src/models/LimitSigns.php';

class LimitRow extends Element {
    public int $amount;
    public int $number;
    public ?array $data;

    public function __construct(int $amount = 2, int $number = 1, array $data = null) {
        $this->amount = $amount;
        $this->number = $number;
        $this->data = $data;
    }

    public function render(): string {
        $input_row = new InputRow($this->amount, "limit$this->number", $this->data['values'] ?? null);
        $selected_1 = (int)$this->data['sign'] === LimitSigns::$less_eq ? ' selected' : '';
        $selected_2 = (int)$this->data['sign'] === LimitSigns::$equal ? ' selected' : '';
        $selected_3 = (int)$this->data['sign'] === LimitSigns::$more_eq ? ' selected' : '';
        $value = ! empty($this->data['b']) ? " value='" . $this->data['b'] . "'" : '';

        $html = "<div class='horizontal'>";
        $html .= $input_row->render();
        $html .= "<select name='sign-limit$this->number'>
                      <option$selected_1><=</option>
                      <option$selected_2>=</option>
                      <option$selected_3>>=</option>
                  </select>";
        $html .= "<input class='coefficient' type='text'$value name='b$this->number'>";
        $html .= "</div>";
        return $html;
    }
}