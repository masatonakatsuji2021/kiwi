<?php

namespace kiwi\core;

class Kiwi {

    public static function upFirst(string $text) : string {
        return strtoupper(substr($text, 0, 1)) . substr($text, 1);
    }

    public static function debug($object) {
        
    }
}