<?php

namespace Jackross\Components\Http;


class Status
{
    public const OK = 200;
    public const NOT_FOUND = 404;

    public static $statusMessages = [
        self::OK => 'OK',
        self::NOT_FOUND => 'Not Found'
    ];
}