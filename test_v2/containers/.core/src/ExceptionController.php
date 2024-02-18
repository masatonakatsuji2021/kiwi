<?php

namespace kiwi\core;

use Exception;

class ExceptionController extends Controller {

    public function handle(Exception $exp) : void {
        echo $exp;
    }

    public function handleDrawn() : void {} 
}