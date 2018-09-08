<?php

namespace Jackross\Components\Http;


class Response
{
    private const SERVER = 'JackRoss/php - server 0.1';
    private const VERSION = 'HTTP/1.1';
    public $contentType = 'text/html';
    public $statusCode;
    public $body;

    private $headers = [];

    public function __construct(string $text = '', int $statusCode = Status::OK)
    {
        $this->statusCode = $statusCode;
        $this->body = $text;
    }

    public function write(string $text)
    {
        $this->body = $text;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    private function build()
    {
        $this->setHeader('Content-Type', $this->contentType);
        $this->setHeader('Date', (new \DateTime())->format(DATE_COOKIE));
        $this->setHeader('Server', self::SERVER);

        if ($this->body !== null) {
            $this->setHeader('Content-Length:', mb_strlen($this->body));
        }

        $this->setHeader('Connection', 'Closed');

        $payload = sprintf("%s %s %s",
            self::VERSION, $this->statusCode,
            Status::$statusMessages[$this->statusCode]);

        foreach ($this->headers as $key => $value) {
            $payload .= sprintf("\r\n%s: %s", $key, $value);
        }

        if ($this->body !== null) {
            $payload .= "\r\n\r\n" . $this->body;
        }

        return $payload;
    }

    public function __toString()
    {
        return $this->build();
    }
}