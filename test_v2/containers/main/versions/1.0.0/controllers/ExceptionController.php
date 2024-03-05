<?php

namespace kiwi\main\controllers;

use Exception;
use kiwi\core\ExceptionController as EC;
use kiwi\core\Render;

class ExceptionController extends EC {

    public string $viewTemplateOnContainer = "main";
    public string $viewTemplate = "def";
    public bool $autoRender = true;

    public function handle() : void {
        Render::set("expTitle", $this->exception->getmessage());
    }

}