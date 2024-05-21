<?php

namespace App;

include_once "Rational.php";

abstract class Data {
    abstract public function __construct();

    abstract public function toArray(): array;

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
}