<?php

namespace kiwi\main;

use kiwi\core\configs\ContainerConfig as CC;

class ContainerConfig extends CC {

    public static array $routes = [
        "/" => "controller:main, action:index",
        "/sub" => [
            "/" => "controller:sub, action:index",
            "/detail/{id}" => "controller:sub, action:detail",
            "/show/{?id}" => "controller:sub, action:show",
        ],
    ];

    public static array $resources = [
        "/common" => [
            "release" => true,
            "cache-max-age" => 3600,
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