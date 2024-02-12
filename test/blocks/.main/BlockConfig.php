<?php

namespace kiwi\main;

use kiwi\core\BlockConfig as BaseBlockConfig;
use kiwi\core\Routes;

class BlockConfig extends BaseBlockConfig {

    public static array $routes = [
        "/" => "controller:main, action:index",
        "/sub" => [
            "/" => "contrller:sub, action:index",
            "/detail/{id}" => "controller:sub, action:detail",
            "/show/{?id}" => "contrller:sub, action:show",
        ],
    ];
}