<?php

namespace kiwi\core;

class BlockEvent {

    public $path = "";

    public function composerAutoload() : void{
        require $this->path . "/vendor/autoload.php";
    }

    public function begin() {

    }
}