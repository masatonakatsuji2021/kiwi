<?php

namespace kiwi\main\controllers;

use Exception;
use kiwi\core\controllers\ExceptionController as EC;
use kiwi\core\renders\Render;

class ExceptionController extends EC {

    public string $viewTemplateOnContainer = "main";
    public string $viewTemplate = "def";
    public bool $autoRender = true;

    public function handle() : void {
        Render::set("expTitle", $this->exception->getmessage());
    }

}