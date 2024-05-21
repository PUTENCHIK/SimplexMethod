<?php

namespace App\Simplex;

include_once 'src/models/common/Rational.php';
include_once 'src/models/common/Answer.php';
include_once 'src/models/simplex/LimitSigns.php';

class SimplexMethod extends \App\Answer {
    private array $original;
    private int $n;
    private int $m;
    private array $artificial;

    public function __construct(array $start_data) {
        if (self::check_data($start_data)) {
            $this->original = $start_data;
        } else {
            throw new \Exception("No valid start_data array");
        }
        $this->n = $this->extract_n();
        $this->m = $this->extract_m();
        $this->artificial = $this->to_artificial();
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

    public function to_artificial(): array {
        $new = $this->original;
        foreach ($this->original['limits'] as $index => $limit) {
            switch ($limit['sign']) {
                case LimitSigns::$less_eq:
                    for ($i = 0; $i < count($this->original['limits']); $i++) {
                        $new['limits'][$i]['values'][] = new \App\Rational($i === $index ? 1 : 0);
                    }
                    break;
                case LimitSigns::$more_eq:
                    for ($i = 0; $i < count($this->original['limits']); $i++) {
                        $new['limits'][$i]['values'][] = new \App\Rational($i === $index ? -1 : 0);
                    }
                    break;
            }
        }

//        header('Content-Type: text/plain');
//        print_r($new);
//        exit;

        return $new;
    }

    public function toArray(): array {
        return [
            'original' => $this->original,
            'n' => $this->n,
            'm' => $this->m,
            'artificial' => $this->artificial,
        ];
    }
}