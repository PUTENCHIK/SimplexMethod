<?php

namespace App;

use mysql_xdevapi\Session;

include_once "src/models/FunctionTypes.php";
include_once "src/models/LimitSigns.php";
include_once "src/models/Rational.php";

class Data {
    private array $function;
    private array $limits;

    public function __construct() {
        $this->function = [
            'values' => null,
            'type' => FunctionTypes::$max,
        ];
        $this->limits = [];
    }

    public function getFunction(): array {
        return $this->function;
    }

    public function getLimits(): array {
        return $this->limits;
    }

    static public function as_rational(string $s): Rational {
        if (preg_match('/^(-)?([0-9]+)$/ui', $s)) {
            return new Rational((int)$s);
        }
        elseif (preg_match('/^(-)?([0-9]+)\.([0-9]+)$/ui', $s)) {
            return new Rational(number: (float)$s);
        }
        elseif (preg_match('/^(-)?([0-9]+),([0-9]+)$/ui', $s)) {
            $s = str_replace(',', '.', $s);
            return new Rational(number: (float)$s);
        } elseif (preg_match('/^(-)?([0-9]+)\/(-)?([0-9]+)$/ui', $s)) {
            $s = explode('/', $s);
            return new Rational((int)$s[0], (int)$s[1]);
        }
        else {
            throw new \Exception("Can't read rational");
        }
    }

    public function read_post(array $post, int $n, int $m): array {
        $errors = [];

        if (isset($post['f'])) {
            foreach ($post['f'] as $index => $v) {
                $true_index = $index+1;
                if (!isset($v) or strlen($v) < 1) {
                    $this->function['values'][] = '';
                    $errors[] = "Не заполнен $true_index-й коэффициент целевой функции";
//                    throw new \Exception("Не заполнен $true_index-й коэффициент целевой функции");
                } else {
                    try {
                        $this->function['values'][] = self::as_rational($v);
                    } catch (\Exception) {
                        $this->function['values'][] = '';
                        $errors[] = "Невалидный формат ввода $true_index-ого коэффициента целевой функции";
                    }
                }
            }
        } else {
            $errors[] = 'Не передан ключ \'f\' в $POST-массиве';
//            throw new \Exception('Не передан ключ \'f\' в $POST-массиве');
        }

        if (isset($post['type'])) {
            switch ($post['type']) {
                case 'max':
                    $this->function['type'] = FunctionTypes::$max;
                    break;
                case 'min':
                    $this->function['type'] = FunctionTypes::$min;
                    break;
                default:
                    $type = $post['type'];
                    $errors[] = "Неизвестный тип целевой функции: $type";
//                    throw new \Exception("Неизвестный тип целевой функции: $type");
            }
        } else {
            $errors[] = 'Не передан ключ \'type\' в $POST-массиве';
//            throw new \Exception('Не передан ключ \'type\' в $POST-массиве');
        }

        for ($i = 1; $i <= $m; $i++) {
            $key_row = 'limit' . $i;
            $key_sign = 'sign-limit' . $i;
            $key_b = 'b' . $i;

            $this->limits[] = [];

            if (isset($post[$key_row])) {
                foreach ($post[$key_row] as $index => $v) {
                    $true_index = $index+1;
                    if (!isset($v) or strlen($v) < 1) {
                        $this->limits[$i-1]['values'][] = '';
                        $errors[] = "Не заполнен $true_index-й коэффициент $i-ого ограничения";
//                        throw new \Exception("Не заполнен $true_index-й коэффициент $i-ого ограничения");
                    } else {
                        try {
                            $this->limits[$i-1]['values'][] = self::as_rational($v);
                        } catch (\Exception) {
                            $this->limits[$i-1]['values'][] = '';
                            $errors[] = "Невалидный формат ввода $true_index-ого коэффициента $i-ого ограничения";
                        }
                    }
                }
            } else {
                $errors[] = "Не передан ключ \'$key_row\' в \$POST-массиве";
//                throw new \Exception("Не передан ключ \'$key_row\' в \$POST-массиве");
            }

            if (isset($post[$key_sign])) {
                switch ($post[$key_sign]) {
                    case '<=':
                        $this->limits[$i-1]['sign'] = LimitSigns::$less_eq;
                        break;
                    case '>=':
                        $this->limits[$i-1]['sign'] = LimitSigns::$more_eq;
                        break;
                    case '=':
                        $this->limits[$i-1]['sign'] = LimitSigns::$equal;
                        break;
                    default:
                        $sign = $post[$key_sign];
                        $errors[] = "Неизвестный знак $i-ого ограниченя: $sign";
//                        throw new \Exception("Неизвестный знак $i-ого ограниченя: $sign");
                }
            } else {
                $errors[] = "Не передан ключ \'$key_sign\' в \$POST-массиве";
//                throw new \Exception("Не передан ключ \'$key_sign\' в \$POST-массиве");
            }

            if (isset($post[$key_b])) {
                if (strlen($post[$key_b]) < 1) {
                    $this->limits[$i-1]['b'] = '';
                    $errors[] = "Не заполнен коэффициент b $i-ого ограничения";
                } else {
                    try {
                        $this->limits[$i-1]['b'] = self::as_rational($post[$key_b]);
                    } catch (\Exception) {
                        $this->limits[$i-1]['b'] = '';
                        $errors[] = "Невалидный формат ввода коэффициента b $i-ого ограничения";
                    }
                }
            } else {
                $this->limits[$i-1]['b'] = '';
                $errors[] = "Не передан ключ \'$key_b\' в \$POST-массиве";
//                throw new \Exception("Не передан ключ \'$key_b\' в \$POST-массиве");
            }
        }
        return $errors;
    }

    public function toArray(): array {
        return [
            'function' => $this->function,
            'limits' => $this->limits,
        ];
    }
}