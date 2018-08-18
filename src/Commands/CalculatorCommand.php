<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 8/10/18
 * Time: 6:21 PM
 */

namespace Jackross\Commands;

use Jackross\Components\Calculator\Helper;
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
            ->setDescription('simple calculator')
            ->addArgument('ab', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->calc($input->getArgument('ab')));
    }

    private function calc(string $ab): string
    {
        try {
            $eq = Helper::parse($ab);
            return sprintf('<info>%s</info>', (new Context($eq->operator))->calculate($eq->a, $eq->b));
        } catch (\InvalidArgumentException  | \DivisionByZeroError $exception) {
            return sprintf('<error>%s</error>', $exception->getMessage());
        }
    }
}