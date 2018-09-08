<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 9/8/18
 * Time: 6:33 PM
 */

namespace Jackross\Commands;


use Jackross\Components\Http\{Request, Response, Server as Http};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;


class HttpServer extends Command
{
    public function configure()
    {
        $this->setName('server:http')
            ->setDescription('');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $http = new Http();

        $http->handleFunc('/', [$this, 'actionIndex']);


        $http->listenAndServe('127.0.0.1', 8080);
    }


    public function actionIndex(Request $request): Response
    {
        return new Response('Hello,  World');
    }
}