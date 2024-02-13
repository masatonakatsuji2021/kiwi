<?php

namespace kiwi\core;

ini_set("display_errors", true);

use Exception;
use kiwi\core\Kiwi;
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
            $be->begin();
        }

        $controllerPath = "\kiwi\\" . Routes::$route -> block . "\\controllers\\". Kiwi::upFirst(Routes::$route -> controller) . "Controller";

        if (!class_exists($controllerPath)) {
            throw new Exception("Controller not found.");
        }

        $c = new $controllerPath();

        $c -> filterBefore();

        if (!method_exists($c, Routes::$route -> action)) {
            throw new Exception("[Error] アクションメソッドが見つかりませんでした");
        }

        if (Routes::$route -> aregments) {

        }
        else {
            $c -> {Routes::$route -> action}();
        }

        $c -> filterAfter();

        print(memory_get_peak_usage());

    }
}