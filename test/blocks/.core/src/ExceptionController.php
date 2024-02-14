<?php

namespace kiwi\core;

use Exception;

class ExceptionController extends Controller {

    public function handleBefore(Exception $exp = null) : void {}

    public function handleAfter(Exception $exp = null) : void {}

    public function handleDrawn(Exception $exp = null) : void {} 
}