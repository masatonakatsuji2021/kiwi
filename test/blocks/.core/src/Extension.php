<?php

namespace kiwi\core;

class Extension {

    public static function load(string $extensionName) : array {
    
        return [];
    }

    public static function loadOnBlock(string $blockName, string $extensionName) {
    
        return;
    }

    public static function excute(string $extensionName, string $methodName) : array {

        return [];
    }

    public static function excuteonBlock(string $blockName, string $extensionName, string $methodName) {

        return;
    }

}