<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SckobkiCommandTest extends TestCase
{
    private $command;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $application = new Application();
        $application->add(new\Jackross\SckobkiCommand());
        $this->command = $application->find('brackets:validate');

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

    public function testValidBrackets()
    {
        $text = '((27 + 38) ÷ (77 – 69 x (54 x (26 - 3)))) x (11 x 12 – 17 + 18) – 36 ÷ (32 – 10 x 4)';

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'text' => $text,
        ]);

        $this->assertContains('Brackets is valid', $commandTester->getDisplay());
    }

    public function testInvalidBrackets()
    {
        $text = '(((27 + 38) ÷ ((((77 – 69 x (54 x (26 - 3)))) x ((11 x 12 – 17 + 18) – 36 ÷ (32 – 10 x 4)';

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'text' => $text,
        ]);

        $this->assertContains('Brackets is invalid', $commandTester->getDisplay());
    }

    public function testInvalidBracketsArgument()
    {
        $text = 'Answer to the Ultimate Question of Life, the Universe, and Everything';

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'text' => $text,
        ]);

        $this->assertContains(sprintf('The text [%s] does not have the brackets', $text), $commandTester->getDisplay());
    }
}