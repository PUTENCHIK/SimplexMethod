<?php

namespace App;

class Rational {
    private int $num;
    private int $den;

    public function __construct(int $n = 0, int $d = 1, float $number = null) {
        if (! is_null($number)) {
            $p = 1;
            while ((int)($number*$p) != ($number*$p)) {
                $p *= 10;
            }
            $this->num = $number*$p;
            $this->den = $p;

            $this->simplify();
        } else {
            $this->num = $n;
            $this->den = $d;

            if ($d === 0 or $n === 0) {
                $this->num = 0;
                $this->den = 1;
            } else {
                if ($this->den < 0) {
                    $this->num *= -1;
                    $this->den *= -1;
                }
                $this->simplify();
            }
        }
    }

    public function simplify() {
        $a = abs($this->num);
        $b = abs($this->den);

        $nod = self::nod($a, $b);

        $this->num = intdiv($this->num, $nod);
        $this->den = intdiv($this->den, $nod);
    }

    static public function nod(int $a, int $b): int {
        while ($a !== $b) {
            if ($a > $b) {
                $a -= $b;
            } else {
                $b -= $a;
            }
        }
        return $a;
    }

    static public function nok(int $a, int $b): int {
        return $a*$b/self::nod($a, $b);
    }

    static private function __add(Rational $first, Rational $second): Rational {
        return new Rational($first->num*$second->den + $second->num*$first->den, $first->den*$second->den);
    }

    static public function add(mixed $first, mixed $second): ?Rational {
        if (gettype($first) === gettype($second) and gettype($first) !== 'object') {
            return null;
        }
        else if (gettype($first) === 'integer') {
            return self::__add(new Rational($first), $second);
        }
        else if (gettype($second) === 'integer') {
            return self::__add($first, new Rational($second));
        }
        else if (gettype($first) === 'double') {
            return self::__add(new Rational(number: $first), $second);
        }
        else if (gettype($second) === 'double') {
            return self::__add($first, new Rational(number: $second));
        }
        else {
            return self::__add($first, $second);
        }
    }

    static public function subtract(mixed $first, mixed $second): ?Rational {
        if (gettype($first) === gettype($second) and gettype($first) !== 'object') {
            return null;
        }
        else if (gettype($first) === 'integer') {
            return self::__add(new Rational($first), self::multiply($second, -1));
        }
        else if (gettype($second) === 'integer') {
            return self::__add($first, new Rational(-$second));
        }
        else if (gettype($first) === 'double') {
            return self::__add(new Rational(number: $first), self::multiply($second, -1));
        }
        else if (gettype($second) === 'double') {
            return self::__add($first, new Rational(number: -$second));
        }
        else {
            return self::__add($first, self::multiply($second, -1));
        }
    }

    static private function __multiply(Rational $first, Rational $second): Rational {
        return new Rational($first->num*$second->num, $first->den*$second->den);
    }

    static public function multiply(mixed $first, mixed $second): ?Rational {
        if (gettype($first) === gettype($second) and gettype($first) !== 'object') {
            return null;
        }
        else if (gettype($first) === 'integer') {
            return self::__multiply(new Rational($first), $second);
        }
        else if (gettype($second) === 'integer') {
            return self::__multiply($first, new Rational($second));
        }
        else if (gettype($first) === 'double') {
            return self::__multiply(new Rational(number: $first), $second);
        }
        else if (gettype($second) === 'double') {
            return self::__multiply($first, new Rational(number: $second));
        }
        else {
            return self::__multiply($first, $second);
        }
    }

    public function invert() {
        if ($this->den === 0 or $this->num === 0) {
            return;
        } else if ($this->num < 0) {
            $this->num *= -1;
            $this->den *= -1;
        }
        $a = $this->num;
        $this->num = $this->den;
        $this->den = $a;
    }

    static public function divide(mixed $first, mixed $second): ?Rational {
        $s = clone $second;

        if (gettype($first) === gettype($second) and gettype($first) !== 'object') {
            return null;
        }
        else if (gettype($first) === 'integer') {
            return self::multiply(new Rational($first), $s->invert());
        }
        else if (gettype($second) === 'integer') {
            $new = new Rational($second);
            $new->invert();
            return self::multiply($first, $new);
        }
        else if (gettype($first) === 'double') {
            return self::multiply(new Rational(number: $first), $s->invert());
        }
        else if (gettype($second) === 'double') {
            $new = new Rational(number: $second);
            $new->invert();
            return self::multiply($first, $new);
        }
        else {
            $s->invert();
            return Rational::__multiply($first, $s);
        }
    }

    static private function __equal(Rational $first, Rational $second): bool {
        return $first->num === $second->num and $first->den === $second->den;
    }

    static public function equal(mixed $first, mixed $second): bool {
        if (gettype($first) === gettype($second) and gettype($first) !== 'object') {
            return $first === $second;
        }
        elseif (gettype($first) === 'integer') {
            return self::__equal(new Rational($first), $second);
        }
        elseif (gettype($second) === 'integer') {
            return self::__equal($first, new Rational($second));
        }
        elseif (gettype($first) === 'double') {
            return self::__equal(new Rational(number: $first), $second);
        }
        elseif (gettype($second) === 'double') {
            return self::__equal($first, new Rational(number: $second));
        }
        else {
            return self::__equal($first, $second);
        }
    }

    static private function __less(Rational $first, Rational $second): bool {
        return ($first->num / $first->den) < ($second->num / $second->den);
    }

    static public function less(mixed $first, mixed $second): bool {
        if (gettype($first) === gettype($second) and gettype($first) !== 'object') {
            return $first < $second;
        }
        elseif (gettype($first) === 'integer') {
            return self::__less(new Rational($first), $second);
        }
        elseif (gettype($second) === 'integer') {
            return self::__less($first, new Rational($second));
        }
        elseif (gettype($first) === 'double') {
            return self::__less(new Rational(number: $first), $second);
        }
        elseif (gettype($second) === 'double') {
            return self::__less($first, new Rational(number: $second));
        }
        else {
            return self::__less($first, $second);
        }
    }

    static private function __more(Rational $first, Rational $second): bool {
        return ($first->num / $first->den) > ($second->num / $second->den);
    }

    static public function more(mixed $first, mixed $second): bool {
        if (gettype($first) === gettype($second) and gettype($first) !== 'object') {
            return $first < $second;
        }
        elseif (gettype($first) === 'integer') {
            return self::__more(new Rational($first), $second);
        }
        elseif (gettype($second) === 'integer') {
            return self::__more($first, new Rational($second));
        }
        elseif (gettype($first) === 'double') {
            return self::__more(new Rational(number: $first), $second);
        }
        elseif (gettype($second) === 'double') {
            return self::__more($first, new Rational(number: $second));
        }
        else {
            return self::__more($first, $second);
        }
    }

    static public function not_equal(mixed $first, mixed $second): bool {
        return !self::equal($first, $second);
    }

    static public function less_equal(mixed $first, mixed $second): bool {
        return !self::more($first, $second);
    }

    static public function more_equal(mixed $first, mixed $second): bool {
        return !self::less($first, $second);
    }

    public function __toString(): string {
        if ($this->den === 1) {
            return $this->num;
        } else {
            return "$this->num/$this->den";
        }
    }
}