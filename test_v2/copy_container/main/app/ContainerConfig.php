<?php

namespace kiwi\main\app;

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
}