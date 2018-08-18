<?php

use \PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testResult()
    {
        $a = 5;
        $b = 6;
        $operator = '+';

        $eq = \Jackross\Components\Calculator\Helper::parse(sprintf('%s%s%s', $a, $operator, $b));
        $ceq = new \Jackross\Components\Calculator\EquationEntity($a, $operator, $b);

        $this->assertEquals($eq, $ceq);
    }

    public function testResultWithSpaces()
    {
        $a = 5;
        $b = 6;
        $operator = '+';

        $eq = \Jackross\Components\Calculator\Helper::parse(sprintf('%s   %s     %s', $a, $operator, $b));
        $ceq = new \Jackross\Components\Calculator\EquationEntity($a, $operator, $b);

        $this->assertEquals($eq, $ceq);
    }

    public function testResultWithTabs()
    {
        $a = 5;
        $b = 6;
        $operator = '+';

        $eq = \Jackross\Components\Calculator\Helper::parse(sprintf('%s             %s        %s', $a, $operator, $b));
        $ceq = new \Jackross\Components\Calculator\EquationEntity($a, $operator, $b);

        $this->assertEquals($eq, $ceq);
    }

    public function testException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $eq = \Jackross\Components\Calculator\Helper::parse('1+f');
    }

}