<?php

namespace Jackross\Components\Http;


use Jackross\Components\Network\Connection;
use Jackross\Components\Network\Socket;

class Server
{
    private $handlers = [];

    public function handleFunc(string $pattern, callable $handler): void
    {
        $this->handlers[$pattern] = $handler;
    }

    public function receiver($data, Connection $connection): void
    {
        $request = Request::fill($data);


        if (!isset($this->handlers[$request->getPath()])) {
            $connection->write(new Response(null, 404));
        }

        $response = $this->handlers[$request->getPath()]($request);
        if ($response instanceof Response) {
            $connection->write((string)$response);
        }

        $connection->close();
    }

    public function listenAndServe(string $ip, int $port): void
    {
        $socket = new Socket(Socket::TCP_STREAM);
        $socket->bind($ip, $port)->listen();
        $socket->beginAsyncAccept(function (Connection $connection) {
            $connection->on('data', function ($data) use ($connection) {
                $this->receiver($data, $connection);
            });
        });
    }
}