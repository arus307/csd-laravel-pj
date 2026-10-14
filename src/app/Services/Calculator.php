<?php

namespace App\Services;

class Calculator
{
    /**
     * 2つの数値を加算する
     *
     * @param int|float $a 第1の加算数
     * @param int|float $b 第2の加算数
     * @return int|float 加算結果
     */
    public function add(int|float $a, int|float $b): int|float
    {
        return $a + $b;
    }
}
