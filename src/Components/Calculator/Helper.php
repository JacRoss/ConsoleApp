<?php

namespace Jackross\Components\Calculator;


class Helper
{
    public static function parse(string $str): EquationEntity
    {
        if (!preg_match(
            '|(\d+)\s*([\+\-\*\/])\s*(\d+)|u',
            trim($str),
            $match)) {
            throw new \InvalidArgumentException('invalid argument');
        }

        unset($match[0]);

        return new EquationEntity(...$match);
    }


}