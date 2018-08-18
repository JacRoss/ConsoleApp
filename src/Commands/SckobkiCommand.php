<?php

namespace Jackross\Commands;

use Jackross\SckobkiHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SckobkiCommand extends Command
{
    public function configure()
    {
        $this->setName('brackets:validate')
            ->setDescription('brackets validation')
            ->addArgument('text', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->validate($input->getArgument('text')));
    }

    private function validate(string $text): string
    {
        try {
            return sprintf('<info>Brackets is %s</info>', SckobkiHelper::validate($text) ? 'valid' : 'invalid');
        } catch (\InvalidArgumentException $e) {
            return sprintf('<error>The text [%s] does not have the brackets</error>', $text);
        }
    }
}