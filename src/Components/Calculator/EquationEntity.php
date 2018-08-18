<?php

namespace Jackross\Components\Calculator;


class EquationEntity
{
    public $a;
    public $b;
    public $operator;

    public function __construct(int $a, string $operator, int $b)
    {
        $this->a = $a;
        $this->b = $b;
        $this->operator = $operator;
    }

}