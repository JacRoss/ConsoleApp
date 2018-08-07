<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SumCommandTest extends TestCase
{
    private $command;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $application = new Application();
        $application->add(new\Jackross\SumCommand());
        $this->command = $application->find('math:sum');

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

    public function testInvalidArgument()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'a' => 'a',
            'b' => 1,
        ]);

        $this->assertContains('all arguments must be numbers', $commandTester->getDisplay());
    }

    public function testResult()
    {
        $a = 1;
        $b = 2;
        $result = sprintf("Dmitry says, that %s + %s = %s", $a, $b, $a + $b);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'a' => $a,
            'b' => $b,
        ]);

        $this->assertContains($result, $commandTester->getDisplay());
    }
}