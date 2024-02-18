<?php

namespace kiwi\main\app\controllers;

use kiwi\core\Controller;
use kiwi\core\Rendering;

class MainController extends Controller {

    public bool $autoRender = true;
    public string $viewTemplate = "def";

//    public function handleBefore () : void {}

    public function index(){
        Rendering::set("title","Main Page Title");
    }

//    public function handleAfter() : void {}

//    public function handleDrawn() : void {}
}
