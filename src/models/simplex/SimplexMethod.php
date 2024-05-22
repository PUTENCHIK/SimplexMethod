<?php

namespace App\Simplex;

use App\Million;

include_once 'src/models/common/Rational.php';
include_once 'src/models/common/Million.php';
include_once 'src/models/common/Answer.php';

include_once 'LimitSigns.php';
include_once "FunctionTypes.php";
include_once 'SimplexIteration.php';

class SimplexMethod extends \App\Answer {
    private array $original;
    private int $n;
    private int $m;
    private int $extra;
    private array $artificial;
    private array $iterations;

    public function __construct(array $start_data) {
        if (self::check_data($start_data)) {
            $this->original = $start_data;
        } else {
            throw new \Exception("No valid start_data array");
        }
        $this->extra = 0;
        $this->n = $this->extract_n();
        $this->m = $this->extract_m();
        $this->add_extra_variables();
        $this->artificial = $this->to_artificial();

        $this->iterations = $this->solve();
    }

    public function getN(): int {
        return $this->n;
    }

    public function getM(): int {
        return $this->m;
    }

    /**
     * @param int $number Нумерация начинается с нуля
     * @return string
     * @throws \Exception
     */
    public function get_var_name(int $number): string {
        $name = ($number < $this->n) ? 'x' : 'u';
        if ($number >= $this->n + $this->extra) {
            throw new \Exception("Impossible number of var: $number");
        }
        $true_number = $name === 'x' ? $number+1 : $number+1 - $this->n ;
        return "<span>$name<sub>$true_number</sub></span>";
    }

    static public function check_data(array $data): bool {
        $flag = isset($data['function']) and isset($data['limits']) and isset($data['function']['values']) and
                isset($data['function']['type']);
        if (! $flag) {
            return false;
        }

        foreach ($data['limits'] as $limit) {
            $flag = isset($limit['values']) and isset($limit['sign']) and isset($limit['b']);
            if (! $flag) {
                return false;
            }
        }

        return true;
    }

    public function extract_n(): int {
        return count($this->original['function']['values']);
    }

    public function extract_m(): int {
        return count($this->original['limits']);
    }

    /**
     * Добавление дополнительных переменных (чтобы все ограничения стали равенствами)
     */
    public function add_extra_variables(): void {
        $limits = $this->original['limits'];
        foreach ($limits as $index => $limit) {
            switch ($limit['sign']) {
                case LimitSigns::$less_eq:
                    for ($i = 0; $i < count($limits); $i++) {
                        $this->original['limits'][$i]['values'][] = new \App\Rational($i === $index ? 1 : 0);
                    }
                    $this->n++;
                    $this->original['function']['values'][] = new \App\Rational(0);
                    $this->original['limits'][$index]['sign'] = LimitSigns::$equal;
                    break;
                case LimitSigns::$more_eq:
                    for ($i = 0; $i < count($limits); $i++) {
                        $this->original['limits'][$i]['values'][] = new \App\Rational($i === $index ? -1 : 0);
                    }
                    $this->n++;
                    $this->original['function']['values'][] = new \App\Rational(0);
                    $this->original['limits'][$index]['sign'] = LimitSigns::$equal;
                    break;
            }
        }
    }

    /**
     * Добавление искусственных переменных (для получения единичной матрицы)
     * @return array
     */
    public function to_artificial(): array {
        $new = $this->original;
        foreach ($new['function']['values'] as $f => $value) {
            $new['function']['values'][$f] = new Million(0, $value);
        }

        $potential = [];
        $limits = $this->original['limits'];

        for ($l = 0; $l < $this->m; $l++) {
            $flag = false;
            for ($v = 0; $v < $this->n; $v++) {
                $mini_flag = true;
                for ($j = 0; $j < $this->m; $j++) {
                    if (($j === $l and \App\Rational::not_equal($limits[$j]['values'][$v], 1)) or
                        ($j !== $l and \App\Rational::not_equal($limits[$j]['values'][$v], 0))) {
                        $mini_flag = false;
                        break;
                    }
                }
                if ($mini_flag) {
                    $flag = true;
                    break;
                }
            }

            $potential[] = $flag ? 1 : 0;
        }

        for ($extra = 1; $extra <= $this->m; $extra++) {
            if ($potential[$extra-1] === 1) {
                continue;
            }
            for ($i = 1; $i <= $this->m; $i++) {
                $new['limits'][$i-1]['values'][] = new \App\Rational($extra === $i ? 1 : 0);
            }
            $this->extra++;
            $new['function']['values'][] = new \App\Million($new['function']['type'] === FunctionTypes::$max ? -1 : 1);
        }

        return $new;
    }

    public function solve(): array {
        $iterations = [];

        $iterations[] = new SimplexIteration($this->artificial);

//        header('Content-Type: text/plain');
//        print_r($iterations[0]);
//        exit;

        return $iterations;
    }

    public function toArray(): array {
        return [
            'original' => $this->original,
            'n' => $this->n,
            'm' => $this->m,
            'extra' => $this->extra,
            'artificial' => $this->artificial,
            'iterations' => $this->iterations,
        ];
    }
}