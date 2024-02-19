<?php

namespace kiwi\main;

use kiwi\core\ContainerConfig as CC;
use kiwi\core\Kiwi;

class ContainerConfig extends CC {

    public static array $routes = [
        "/" => "controller:main, action:index",
        "/sub" => [
            "/" => "controller:sub, action:index",
            "/detail/{id}" => "controller:sub, action:detail",
            "/show/{?id}" => "controller:sub, action:show",
        ],
    ];

    /*
    public static function handleRequest(): void
    {
        self::$basicAuthority = [
            "user" => "1234",
            "pass" => "abcd",
            "failed" => "This page cannot be viewed.",
        ];
    }
    */
}