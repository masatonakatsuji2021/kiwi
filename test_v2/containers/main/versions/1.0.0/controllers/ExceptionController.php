<?php

namespace kiwi\main\controllers;

use Exception;
use kiwi\core\ExceptionController as EC;
use kiwi\core\Rendering;

class ExceptionController extends EC {

    public string $viewTemplateOnContainer = "main";
    public string $viewTemplate = "def";
    public bool $autoRender = true;

    public function handle(Exception $exp) : void {
        Rendering::set("expTitle", $exp->getmessage());
    }

}