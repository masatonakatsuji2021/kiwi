<?php

namespace kiwi\core;

ini_set("display_errors", true);

use Exception;
use kiwi\core\Routes;

class Startor {

    public function __construct() {
        try {
            $this->start();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function start() {
        if(!class_exists("kiwi\AppConfig")){
            throw new Exception("[Initial Error] AppConfig class not found.");
        } 

        Routes::route();

/*
        $route = Routes::route();
        print_r($route);

*/
    }
}