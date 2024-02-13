<?php

namespace kiwi\main\controllers;

use kiwi\core\Controller;

class MainController extends Controller {

    public function filterBefore () : void {
        echo "filter before....OK";
    }

    public function index(){
        echo "Hallo main Page!";
    }
}
