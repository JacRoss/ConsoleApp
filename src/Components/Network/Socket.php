<?php

namespace Jackross\Components\Network;

use Jackross\Components\Network\Exceptions\SocketAcceptException;
use Jackross\Components\Network\Exceptions\SocketException;
use React\EventLoop\Factory;

/**
 * Class Socket
 * @package Jackross
 *
 * @method Factory $loop;
 */
class Socket
{
    public const TCP_STREAM = 'tcp';
    public const UDP_STREAM = 'udp';

    private $address;
    private $port;
    private $protocol;
    private $resource;
    private $loop;


    public function __construct(string $protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @param string $address
     * @param int $port
     * @return Socket
     */
    public function bind(string $address, int $port): Socket
    {
        $this->address = $address;
        $this->port = $port;

        return $this;
    }

    /**
     * @return Socket
     * @throws SocketException
     */
    public function listen(): Socket
    {
        if (!isset($this->address, $this->port, $this->protocol)) {
            throw new \ArgumentCountError();
        }

        $this->resource = stream_socket_server(
            sprintf('%s://%s:%s',
                $this->protocol,
                $this->address,
                $this->port),
            $errno,
            $errstr,
            STREAM_SERVER_BIND | STREAM_SERVER_LISTEN
        );

        if ($this->resource === false) {
            throw new SocketException($errstr);
        }

        $this->setBlocking(false);

        return $this;
    }

    public function setBlocking(bool $mode)
    {
        stream_set_blocking($this->resource, $mode);
    }

    /**
     * @param callable $callback
     * @throws SocketAcceptException
     */
    public function beginAsyncAccept(callable $callback): void
    {
        $this->loop = Factory::create();

        $this->loop->addReadStream($this->resource, function ($resource) use ($callback) {
            $connection = stream_socket_accept($resource);

            if ($connection === false) {
                throw new SocketAcceptException();
            }

            $callback(new Connection($connection, $this->loop));
        });

        $this->loop->run();
    }

    public function connect(callable $callback, int $timeout = 0)
    {
        $this->createConnectResource($timeout);
        $this->loop = Factory::create();
        $callback(new Connection($this->resource, $this->loop));
        $this->loop->run();
    }

    public function close(): void
    {
        $this->loop->removeWriteStream($this->resource);
        $this->loop->stop();

        if (is_resource($this->resource)) {
            fclose($this->resource);
        }
    }

    private function createConnectResource(int $timeout = 0): void
    {
        $this->resource = stream_socket_client(sprintf(
            '%s://%s:%s',
            $this->protocol,
            $this->address,
            $this->port),
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT | STREAM_CLIENT_ASYNC_CONNECT
        );
    }

}