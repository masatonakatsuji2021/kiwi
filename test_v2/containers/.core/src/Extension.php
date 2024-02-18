<?php

namespace kiwi\core;

class Extension {

    public static function load(string $extensionName) : array {
    
        return [];
    }

    public static function loadOnContainer(string $containerName, string $extensionName) {
    
        return;
    }

    public static function excute(string $extensionName, string $methodName) : array {

        return [];
    }

    public static function excuteonContainer(string $containerName, string $extensionName, string $methodName) {

        return;
    }
}