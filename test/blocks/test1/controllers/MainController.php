<?php

namespace kiwi\test1\controllers;

class MainController {

    public function __construct () {

        print_r(new \Valitron\Validator());
    }
}