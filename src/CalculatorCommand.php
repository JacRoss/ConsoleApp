<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 8/10/18
 * Time: 6:21 PM
 */

namespace Jackross;

use Jackross\Strategy\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculatorCommand extends Command
{
    public function configure()
    {
        $this->setName('math:calc')
            ->setDescription('sum two numbers')
            ->addArgument('a', InputArgument::REQUIRED)
            ->addArgument('operation', InputArgument::REQUIRED)
            ->addArgument('b', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            $this->calc(
                $input->getArgument('a'),
                $input->getArgument('b'),
                $input->getArgument('operation'))
        );
    }

    private function calc($a, $b, string $operation): string
    {
        try {
            return sprintf('<info>%s</info>', (new Context($operation))->calculate($a, $b));
        } catch (\InvalidArgumentException  | \DivisionByZeroError $exception) {
            return sprintf('<error>%s</error>>', $exception->getMessage());
        }
    }
}