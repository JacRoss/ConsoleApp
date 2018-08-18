<?php

namespace Jackross\Commands;

use Jackross\Components\Network\Connection;
use Jackross\Components\Network\Socket;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ClientCommand extends Command
{

    private $input;
    private $output;


    public function configure()
    {
        $this->setName('server:math')
            ->addArgument('address', InputArgument::REQUIRED)
            ->addArgument('port', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $socket = new Socket(Socket::TCP_STREAM);
        $socket->bind($input->getArgument('address'), (int)$input->getArgument('port'));

        $socket->connect(function (Connection $connection) use ($output) {
            $output->writeln('<info>New Connection</info>');
            $connection->on('data', function ($data) use ($output, $connection) {
                $output->writeln(sprintf('[Answer]:%s', $data));
                $question = $this->question();
                $connection->write($question);

            });
        }, 60);

    }

    /**
     * @return string
     */
    private function question(): string
    {
        $helper = $this->getHelper('question');
        return $helper->ask($this->input, $this->output, new Question('Calculate: '));
    }
}