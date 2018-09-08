<?php

namespace Jackross\Components\Http;


class Request extends BaseRequest
{
    public function getPath(): string
    {
        return $this->path;
    }
}