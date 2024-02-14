<?php

namespace kiwi\main\controllers;

use kiwi\core\Controller;

class MainController extends Controller {

    public bool $autoRender = true;
    public string $viewTemplate = "def";

    public function handleBefore () : void {
        echo "before!";
    }

    public function index(){
        echo " run! ";
    }

    public function handleAfter() : void {
        echo "after!";
    }

    public function handleDrawn() : void {
        echo "rendering!!";
    }
}
