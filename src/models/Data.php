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
        if (preg_match('/^([0-9]+)$/ui', $s)) {
            return new Rational((int)$s);
        }
        elseif (preg_match('/^([0-9]+)\.([0-9]+)$/ui', $s)) {
            return new Rational(number: (float)$s);
        }
        elseif (preg_match('/^([0-9]+),([0-9]+)$/ui', $s)) {
            $s = str_replace(',', '.', $s);
            return new Rational(number: (float)$s);
        } elseif (preg_match('/^([0-9]+)\/([0-9]+)$/ui', $s)) {
            $s = explode('/', $s);
            return new Rational((int)$s[0], (int)$s[1]);
        }
        else {
            return new Rational();
        }
    }

    public function read_post(array $post, int $n, int $m): void {
        if (isset($post['f'])) {
            foreach ($post['f'] as $v) {
                $this->function['values'][] = empty($v) ? new Rational() : self::as_rational($v);
            }
        } else {
            throw new \Exception('No \'f\' in $_POST array');
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
                    throw new \Exception("Unknown goal function\'s type: $type");
            }
        } else {
            throw new \Exception('No \'type\' in $_POST array');
        }

        for ($i = 1; $i <= $m; $i++) {
            $key_row = 'limit' . $i;
            $key_sign = 'sign-limit' . $i;
            $key_b = 'b' . $i;

            $this->limits[] = [];

            if (isset($post[$key_row])) {
                foreach ($post[$key_row] as $v) {
                    $this->limits[$i-1]['values'][] = empty($v) ? new Rational() : self::as_rational($v);
                }
            } else {
                throw new \Exception("No '$key_row' in \$_POST array");
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
                        throw new \Exception("Unknown $i'th sign: $sign");
                }
            } else {
                throw new \Exception("No '$key_sign' in \$_POST array");
            }

            if (isset($post[$key_b])) {
                $this->limits[$i-1]['b'] = self::as_rational($post[$key_b]);
            } else {
                throw new \Exception("No '$key_b' in \$_POST array");
            }
        }
    }

    public function toArray(): array {
        return [
            'function' => $this->function,
            'limits' => $this->limits,
        ];
    }
}