<?php

namespace kiwi\main\controllers;

use kiwi\core\controllers\Controller;
use kiwi\core\renders\Render;

class MainController extends Controller {

    public bool $autoRender = true;
    public string $viewTemplate = "def";

//    public function handleBefore () : void {}

    public function index(){
        Render::set("title","Main Page Title");
    }

//    public function handleAfter() : void {}

    // public function handleDrawn() : void {    }
}
