<?php

namespace Jackross\Commands;

use Jackross\Math;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SumCommand extends Command
{
    public function configure()
    {
        $this->setName('math:sum')
            ->setDescription('sum two numbers')
            ->addArgument('a', InputArgument::REQUIRED)
            ->addArgument('b', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->sum($input->getArgument('a'), $input->getArgument('b')));
    }

    private function sum($a, $b): string
    {
        try {
            return sprintf('<info>%s</info>', Math::saySum($a, $b));
        } catch (\TypeError $error) {
            return '<error>all arguments must be numbers</error>';
        }
    }
}