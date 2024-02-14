<?php

namespace kiwi;

use kiwi\core\Config as BaseConfig;

class Config extends BaseConfig{

    public static array $blocks = [
        "/" => "main",
        "/test1" => "test1",
        "/t2" => "test2",
    ];

}