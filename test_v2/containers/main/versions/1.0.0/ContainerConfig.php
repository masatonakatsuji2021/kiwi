<?php

namespace kiwi\main;

use kiwi\core\ContainerConfig as CC;

class ContainerConfig extends CC {

    public static array $routes = [
        "/" => "controller:main, action:index",
        "/page1" => "controller:main, action:page1",
        "/page2" => "controller:main, action:page2",
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
        /*
        "/common_v2" => [
            "release" => true,
        ],
        */
    ];

    public static array $writables = [
        "/public" => [
            "release" => true,
            "cache-max-age" => 60,
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