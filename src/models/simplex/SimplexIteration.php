<?php

namespace App\Simplex;

use App\Million;
use App\Rational;

include_once 'src/models/common/Rational.php';
include_once 'src/models/common/Million.php';

include_once 'FunctionTypes.php';

class SimplexIteration {
    public array $function;
    public int $type;
    public array $matrix;
    public array $b;
    public array $basis;                // индексы переменных базиса (начиная с нуля)
    public array $deltas;               // оценки класса Million
    public ?int $chosen_column;         // индекс колонки с мин/макс оценкой (начиная с нуля)
    public ?array $rating;              // столбец с оценками замены строки
    public ?int $chosen_row;            // индекс строки с мин/макс оценкой (начиная с нуля)

    public function __construct(array $data = null, SimplexIteration $previous = null) {
        if (! is_null($data)) {
            $this->function = self::extract_function($data['function']);
            $this->type = self::extract_type($data['function']);
            $this->matrix = self::extract_matrix($data['limits']);
            $this->b = self::extract_b($data['limits']);

            $this->detect_basis();

        } else {
            $this->function = $previous->function;
            $this->type = $previous->type;
            $this->matrix = $previous->matrix;
            $this->b = $previous->b;
            $this->basis = $previous->basis;

            $this->update_matrix($previous->chosen_column, $previous->chosen_row);
        }

        $this->count_deltas();
        if ($this->check_optimality()) {
            $this->chosen_column = null;
            $this->rating = null;
            $this->chosen_row = null;
            return;
        }

        $this->choose_column();
        $this->count_rating();
        $this->choose_row();
    }

    /**
     * f_object = ['values' => [], 'type' = int]
     * @param array $f_object
     * @return array
     */
    static public function extract_function(array $f_object): array {
        return $f_object['values'];
    }

    static public function extract_type(array $f_object): int {
        return $f_object['type'];
    }

    /**
     * limits_object = [ ['values' => [], 'sign' => int, 'b' => Rational], ... ]
     * @param array $limits_object
     * @return array
     */
    static public function extract_matrix(array $limits_object): array {
        $matrix = [];
        foreach ($limits_object as $limit) {
            $matrix[] = $limit['values'];
        }

        return $matrix;
    }

    static public function extract_b(array $limits_object): array {
        $bs = [];
        foreach ($limits_object as $limit) {
            $bs[] = $limit['b'];
        }

        return $bs;
    }

    public function detect_basis(): void {
        $l_amount = count($this->matrix);

        for ($limit = 0; $limit < $l_amount; $limit++) {
            $this->basis[] = -1;
            for ($variable = 0; $variable < count($this->function); $variable++) {
                $flag = true;
                for ($j = 0; $j < $l_amount; $j++) {
                    $matrix_row = $this->matrix[$j];
                    if (($limit === $j and \App\Rational::not_equal($matrix_row[$variable], 1)) or
                        ($limit !== $j and \App\Rational::not_equal($matrix_row[$variable], 0))) {
                        $flag = false;
                        break;
                    }
                }
                if ($flag) {
                    $this->basis[$limit] = $variable;
                }
            }
        }
    }

    public function get_basis_values(): array {
        $values = [];
        foreach ($this->basis as $basis) {
            $values[] = $this->function[$basis];
        }

        return $values;
    }

    public function count_deltas(): void {
        $function = $this->function;
        $basis_values = $this->get_basis_values();

        for ($v = 0; $v < count($function); $v++) {
            $delta = new Million(0, 0);

            foreach ($this->matrix as $index => $row) {
                $multy = Million::multiply($basis_values[$index], $row[$v]);
                $delta = Million::add($delta, $multy);

            }
            $this->deltas[] = Million::subtract($delta, $function[$v]);
        }
    }

    public function choose_column(): void {
        $chosen = [
            'index' => 0,
            'value' => $this->deltas[0],
        ];
        foreach ($this->deltas as $index => $value) {
            switch ($this->type) {
                case FunctionTypes::$max:
                    if (Million::less($value, $chosen['value'])) {
                        $chosen['index'] = $index;
                        $chosen['value'] = $value;
                    };
                    break;
                case FunctionTypes::$min:
                    if (Million::more($value, $chosen['value'])) {
                        $chosen['index'] = $index;
                        $chosen['value'] = $value;
                    };
                    break;
            }
        }
        $this->chosen_column = $chosen['index'];
    }

    public function count_rating(): void {
        for ($j = 0; $j < count($this->matrix); $j++) {
            $b = $this->b[$j];
            $elem = clone $this->matrix[$j][$this->chosen_column];

            if (Rational::less_equal($elem, 0)) {
                $this->rating[] = null;
            } else {
                $this->rating[] = Rational::divide($b, $elem);
            }
        }
    }

    public function choose_row(): void {
        $chosen = null;
        foreach ($this->rating as $index => $value) {
            if (! is_null($value)) {
                $chosen = [
                    'index' => $index,
                    'value' => $value,
                ];
            }
        }

        if (is_null($chosen)) {
            throw new \Exception('All Q rates are equal 0');
        }

        foreach ($this->rating as $index => $value) {
            if (is_null($value)) {
                continue;
            }
            if (Rational::less($value, $chosen['value'])) {
                $chosen['index'] = $index;
                $chosen['value'] = $value;
            };

//            switch ($this->type) {
//                case FunctionTypes::$max:
//                    if (Rational::less($value, $chosen['value'])) {
//                        $chosen['index'] = $index;
//                        $chosen['value'] = $value;
//                    };
//                    break;
//                case FunctionTypes::$min:
//                    if (Rational::more($value, $chosen['value'])) {
//                        $chosen['index'] = $index;
//                        $chosen['value'] = $value;
//                    };
//                    break;
//            }
        }
        $this->chosen_row = $chosen['index'];
    }

    public function check_optimality(): bool {
        foreach ($this->deltas as $delta) {
            switch ($this->type) {
                case FunctionTypes::$max:
                    if (Million::less($delta, 0)) {
                        return false;
                    }
                    break;
                case FunctionTypes::$min:
                    if (Million::more($delta, 0)) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    public function can_continue(): bool {
        foreach ($this->rating as $rate) {
            if (! is_null($rate)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Делит выбранный ряд на ячейку в пересечении, приводит к нулям ячейки в выбранном столбце, меняет массив b
     * @param int $chosen_column
     * @param int $chosen_row
     */
    public function update_matrix(int $chosen_column, int $chosen_row): void {
        header('Content-Type: text/plain');
        $chosen_value = clone $this->matrix[$chosen_row][$chosen_column];

        for ($v = 0; $v < count($this->function); $v++) {
            $this->matrix[$chosen_row][$v] = Rational::divide($this->matrix[$chosen_row][$v], $chosen_value);
        }
        $this->b[$chosen_row] = Rational::divide($this->b[$chosen_row], $chosen_value);

        for ($row = 0; $row < count($this->matrix); $row++) {
            if ($row !== $chosen_row and Rational::not_equal($this->matrix[$row][$chosen_column], 0)) {
                $factor = $this->matrix[$row][$chosen_column];
                echo 'factor: ' . $factor . "\n";
                foreach ($this->matrix[$row] as $index => $value) {
                    $minus = Rational::multiply($this->matrix[$chosen_row][$index], $factor);
                    $this->matrix[$row][$index] = Rational::subtract($value, $minus);
                }
                $this->b[$row] = Rational::subtract($this->b[$row], Rational::multiply($this->b[$chosen_row], $factor));
            }
        }
        $this->basis[$chosen_row] = $chosen_column;

//        exit;
    }

    public function toArray(): array {
        return [
            'function' => $this->function,
            'type' => $this->type,
            'matrix' => $this->matrix,
            'b' => $this->b,
            'basis' => $this->basis,
            'deltas' => $this->deltas,
            'chosen_column' => $this->chosen_column,
            'rating' => $this->rating,
            'chosen_row' => $this->chosen_row,
        ];
    }
}