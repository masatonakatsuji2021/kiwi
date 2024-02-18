<?php

namespace kiwi\core;

ini_set("display_errors", true);

use Exception;
use kiwi\core\Kiwi;
use kiwi\core\Routes;
use kiwi\core\Rendering;

class Startor {

    public function __construct(bool $consoleMode = false) {
        try {
            $this->start($consoleMode);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function start($consoleMode = false) : void {
        if(!class_exists("kiwi\Config")){
            throw new Exception("[Initial Error] Config class not found.");
        } 

        // 経路探索を開始
        Routes::route($consoleMode);

        $blockEventPath = "\kiwi\\" . Routes::$route -> block . "\\BlockEvent";        
        if (class_exists($blockEventPath)) {
            // BlockEventクラスがあればそれをインスタンス化して、beginを実行
            $be = new $blockEventPath();
            $be->begin();
        }

        if ($consoleMode) {
            self::showShell();
        }
        else {
            self::showController();            
        }
    }
    
    private static function showController() {
        
        $controllerPath = "\kiwi\\" . Routes::$route -> block . "\\controllers\\". Kiwi::upFirst(Routes::$route -> controller) . "Controller";

        if (!class_exists($controllerPath)) {
            throw new Exception("Controller not found.");
        }

        // Controllerのインスタンスとセット
        $c = new $controllerPath();
        $c -> view = Routes::$route -> controller . "/" . Routes::$route -> action;

        $c -> handleBefore();

        if (!method_exists($c, Routes::$route -> action)) {
            throw new Exception("[Error] Action method not found.");
        }

        if (Routes::$route -> aregments) {
            $c -> {Routes::$route -> action}(...Routes::$route -> aregments);
        }
        else {
            $c -> {Routes::$route -> action}();
        }

        $c -> handleAfter();

        // rendering
        Rendering::$controllerDelegate = $c;
        if ($c -> autoRender) {
            if ($c -> viewTemplate) {
                Rendering::viewTemplate();
            }
            else {
                Rendering::view();
            }
        }

        $c -> handleDrawn();

        print(memory_get_peak_usage());
    }

    private static function showShell (){

    }
}