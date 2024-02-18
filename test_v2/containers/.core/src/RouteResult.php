<?php

namespace kiwi\core;

use Exception;

class RouteResult {
    public string $domain;
    public string $full;
    public string $ip;
    public string $url;
    public string $method;
    public bool $successed;
    public string $container;
    public string $containerPath;
    public string $action;
    public ?array $aregments = null;
    public Exception $exception;
}