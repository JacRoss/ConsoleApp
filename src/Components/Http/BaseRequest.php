<?php

namespace Jackross\Components\Http;


class BaseRequest
{
    protected $path;
    protected $version;
    protected $method;
    protected $headers = [];
    protected $query = [];

    public static function fill(string $data)
    {
        $request = new static();
        $request->build($data);

        return $request;
    }

    private function build(string $data)
    {
        $rows = explode("\r\n", $data);
        $this->parseMethod(...explode(' ', array_shift($rows), 3));
        $contentDelimiter = array_search('', $rows);
        $len = $contentDelimiter !== false ? $contentDelimiter : count($rows);
        $this->parseHeaders(array_slice($rows, 0, $len));
    }

    private function parseMethod(string $method, string $path, string $version)
    {
        $this->method = $method;
        $this->version = $version;

        $url = parse_url($path);
        $this->path = $url['path'];

        if (isset($url['query'])) {
            $this->query = parse_str($url['query']);
        }
    }

    public function parseHeaders(array $rows)
    {
        foreach ($rows as $row) {
            $this->addHeaderFromString($row);
        }
    }

    private function addHeaderFromString(string $string)
    {
        $this->addHeader(...explode(':', $string, 2));
    }

    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = trim($value);
    }
}