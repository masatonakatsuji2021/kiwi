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

        $blockEventPath = "\kiwi\\" . Routes::$route -> block . "\\BlockEvent";        
        if (class_exists($blockEventPath)) {
            // BlockEventクラスがあればそれをインスタンス化して、beginを実行
            $be = new $blockEventPath();
            print_r($be);
//            $be->begin();
        }



/*
        $route = Routes::route();
        print_r($route);

*/
    }
}