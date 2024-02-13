<?php

namespace kiwi\core;

use kiwi\core\Routes;

class BlockEvent {

    public function composerAutoload() : void{
        require Routes::$route -> blockPath . "/vendor/autoload.php";
    }

    public function begin() {

    }
}