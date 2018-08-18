<?php

use Jackross\Commands\CalculatorCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CalculatorCommanTest extends \PHPUnit\Framework\TestCase
{
    private $command;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $application = new Application();
        $application->add(new  CalculatorCommand());
        $this->command = $application->find('math:calc');

        parent::__construct($name, $data, $dataName);
    }

    public function testEmptyArgument()
    {
        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
        ]);
    }

    public function testResult()
    {
        $a = 1;
        $b = 2;

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'ab' => sprintf('%s+%s', $a, $b),
        ]);

        $this->assertContains((string)($a + $b), $commandTester->getDisplay());
    }
}