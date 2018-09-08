<?php

namespace Jackross\Components\Network;

use Evenement\EventEmitter;
use Jackross\Components\Network\Exceptions\SocketException;
use Jackross\Components\Network\Exceptions\SocketWriteException;
use React\EventLoop\StreamSelectLoop;

/**
 * Class Connection
 * @package Jackross
 *
 * @property StreamSelectLoop $loop;
 */
class Connection extends EventEmitter
{
    private $resource;
    private $loop;

    /**
     * Connection constructor.
     * @param $connection
     * @param StreamSelectLoop $loop
     * @throws SocketException
     */
    public function __construct($connection, StreamSelectLoop $loop)
    {
        if (!is_resource($connection)) {
            throw new \InvalidArgumentException();
        }

        $this->resource = $connection;
        $this->loop = $loop;

        $this->subscription();

        if (stream_set_read_buffer($this->resource, 0) !== 0) {
            throw new SocketException();
        }

        if (stream_set_blocking($this->resource, 0) === false) {
            throw new SocketException();
        }
    }

    private function subscription(): void
    {
        $this->loop->addWriteStream($this->resource, function () {
            $this->emit('sent', []);
        });
        $this->loop->addReadStream($this->resource, [$this, 'handleData']);
    }

    /**
     * @param resource $stream
     */
    public function handleData($stream)
    {
        $payload = '';
        while (($data = stream_get_contents($stream, 2048)) !== '') {
            $payload .= $data;
        }

        if ($payload == '') {
            $this->close();
        }

        $this->emit('data', [$payload]);
    }

    /**
     * @param string $data
     * @throws SocketWriteException
     */
    public function write(string $data)
    {
        if (fwrite($this->resource, $data) === false) {
            throw new SocketWriteException();
        }
    }

    public function close()
    {
        $this->emit('close');
        $this->removeAllListeners();
        $this->loop->removeReadStream($this->resource);

        if (is_resource($this->resource)) {
            fclose($this->resource);
        }
    }
}