<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 8/18/18
 * Time: 8:08 PM
 */

namespace Jackross\Commands;

use Jackross\Components\Calculator\Helper;
use Jackross\Components\Network\{Connection, Socket};
use Jackross\Strategy\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends Command
{

    public function configure()
    {
        $this->setName('server:run')
            ->setDescription('start server')
            ->addArgument('address', InputArgument::REQUIRED)
            ->addArgument('port', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $socket = new Socket(Socket::TCP_STREAM);
        $socket->bind($input->getArgument('address'), (int)$input->getArgument('port'))->listen();

        $output->writeln('<info>Server running...</info>');

        $socket->beginAsyncAccept(function (Connection $connection) use ($output) {
            $output->writeln('<info>New Connection</info>');
            $connection->write('Hello, Im calculator');
            $connection->on('data', function ($data) use ($connection, $output) {
                $output->writeln(sprintf('[Question]:%s', trim($data)));
                $answer = $this->calculate($data);
                $output->writeln(sprintf('[Answer]:%s', $answer));
                $connection->write($answer);
            });
        });
    }

    private function calculate(string $str)
    {
        try {
            $eq = Helper::parse($str);
            return (new Context($eq->operator))->calculate($eq->a, $eq->b);

        } catch (\InvalidArgumentException | \DivisionByZeroError $exception) {
            return 'Error -> ' . $exception->getMessage();
        }
    }
}