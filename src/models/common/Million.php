<?php

namespace App;

include_once 'Rational.php';

class Million {
    public Rational $k;
    public Rational $b;

    public function __construct(mixed $k = 1, mixed $b = 0) {
        if (gettype($k) === 'integer') {
            $this->k = new Rational($k);
        }
        elseif (gettype($k) === 'double') {
            $this->k = new Rational(number: $k);
        }
        elseif (gettype($k) === 'object') {
            $this->k = $k;
        }
        else {
            throw new \Exception("Parameter k is unknown type");
        }

        if (gettype($b) === 'integer') {
            $this->b = new Rational($b);
        }
        elseif (gettype($b) === 'double') {
            $this->b = new Rational(number: $b);
        }
        elseif (gettype($b) === 'object') {
            $this->b = $b;
        }
        else {
            throw new \Exception("Parameter b is unknown type");
        }
    }

    static public function detect_class(mixed $object): string {
        $type = gettype($object);
        if ($type === 'integer') {
            return 'integer';
        }
        elseif ($type == 'double') {
            return 'float';
        }
        elseif ($type === 'object') {
            if (get_class($object) === Million::class) {
                return 'Million';
            }
            elseif (get_class($object) === Million::class) {
                return 'Rational';
            }
            else {
                return 'other';
            }
        }
        else {
            return 'other';
        }
    }

    static private function __add(Million $first, Million $second): Million {
        return new Million(\App\Rational::add($first->k, $second->k), \App\Rational::add($first->b, $second->b));
    }

    static public function add(mixed $first, mixed $second): ?Million {
        $f_class = self::detect_class($first);
        $s_class = self::detect_class($second);

        if ($f_class !== 'Million' and $s_class !== 'Million') {
            return null;
        }

        if ($f_class !== 'Million') {
            return self::__add(new Million(0, $first), $second);
        }
        elseif ($s_class !== 'Million') {
            return self::__add($first, new Million(0, $second));
        }
        else {
            return self::__add($first, $second);
        }
    }

    static private function __substract(Million $first, Million $second): Million {
        return new Million(\App\Rational::subtract($first->k, $second->k), \App\Rational::subtract($first->b, $second->b));
    }

    static public function subtract(mixed $first, mixed $second): ?Million {
        $f_class = self::detect_class($first);
        $s_class = self::detect_class($second);

        if ($f_class !== 'Million' and $s_class !== 'Million') {
            return null;
        }

        if ($f_class !== 'Million') {
            return self::__substract(new Million(0, $first), $second);
        }
        elseif ($s_class !== 'Million') {
            return self::__substract($first, new Million(0, $second));
        }
        else {
            return self::__substract($first, $second);
        }
    }

    static private function __multiply(Million $first, Rational $second): Million {
        return new Million(Rational::multiply($first->k, $second), Rational::multiply($first->b, $second));
    }

    static public function multiply(mixed $first, mixed $second): ?Million {
        $f_class = self::detect_class($first);
        $s_class = self::detect_class($second);

        if ($f_class !== 'Million' and $s_class !== 'Million') {
            return null;
        }

        if ($f_class !== 'Million') {
            $buff = new Million($first);
            return self::__multiply($second, $buff->k);
        }
        else {
            $buff = new Million($second);
            return self::__multiply($first, $buff->k);
        }
    }

    static private function __equal(Million $first, Million $second): bool {
        return Rational::equal($first->k, $second->k) and Rational::equal($first->b, $second->b);
    }

    static public function equal(mixed $first, mixed $second): bool {
        $f_class = self::detect_class($first);
        $s_class = self::detect_class($second);

        if ($f_class !== 'Million' and $s_class !== 'Million') {
            return $first === $second;
        }

        if ($f_class !== 'Million') {
            return self::__equal(new Million(0, $first), $second);
        }
        elseif ($s_class !== 'Million') {
            return self::__equal($first, new Million(0, $second));
        }
        else {
            return self::__equal($first, $second);
        }
    }

    static private function __less(Million $first, Million $second): bool {
        if (Rational::equal($first->k, $second->k)) {
            return Rational::less($first->b, $second->b);
        } else {
            return Rational::less($first->k, $second->k);
        }
    }

    static public function less(mixed $first, mixed $second): bool {
        $f_class = self::detect_class($first);
        $s_class = self::detect_class($second);

        if ($f_class !== 'Million' and $s_class !== 'Million') {
            return $first === $second;
        }

        if ($f_class !== 'Million') {
            return self::__less(new Million(0, $first), $second);
        }
        elseif ($s_class !== 'Million') {
            return self::__less($first, new Million(0, $second));
        }
        else {
            return self::__less($first, $second);
        }
    }

    static private function __more(Million $first, Million $second): bool {
        if (Rational::equal($first->k, $second->k)) {
            return Rational::more($first->b, $second->b);
        } else {
            return Rational::more($first->k, $second->k);
        }
    }

    static public function more(mixed $first, mixed $second): bool {
        $f_class = self::detect_class($first);
        $s_class = self::detect_class($second);

        if ($f_class !== 'Million' and $s_class !== 'Million') {
            return $first === $second;
        }

        if ($f_class !== 'Million') {
            return self::__more(new Million(0, $first), $second);
        }
        elseif ($s_class !== 'Million') {
            return self::__more($first, new Million(0, $second));
        }
        else {
            return self::__more($first, $second);
        }
    }

    static public function not_equal(mixed $first, mixed $second): bool {
        return ! self::equal($first, $second);
    }

    static public function less_equal(mixed $first, mixed $second): bool {
        return ! self::more($first, $second);
    }

    static public function more_equal(mixed $first, mixed $second): bool {
        return ! self::less($first, $second);
    }

    public function __toString(): string {
        if (Rational::not_equal($this->k, 0)) {
            if (Rational::equal($this->k, 1)) {
                $first = 'M';
            } elseif (Rational::equal($this->k, -1)) {
                $first = '-M';
            } else {
                $first = "$this->k" . 'M';
            }
        } else {
            $first = '';
        }

        if (Rational::not_equal($this->b, 0)) {
            if (Rational::more($this->b, 0)) {
                if (empty($first)) {
                    $second = '';
                } else {
                    $second = ' + ';
                }
                $second .= $this->b;
            } else {
                if (empty($first)) {
                    $second = $this->b;
                } else {
                    $second = ' - ' . Rational::multiply($this->b, -1);
                }
            }
        } else {
            $second = '';
        }

        return ($first === '' and $second === '') ? '0' : $first . $second;
    }
}