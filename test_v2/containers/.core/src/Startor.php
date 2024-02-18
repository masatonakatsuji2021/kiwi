<?php
/**
 * kiwi Core Library
 * author   : Masato Nakatsuji
 * created  : 2024.03.01
 * GitHub   : https://www.github.com/masatonakatsuji2021/kiwi_core.git
 */

namespace kiwi\core;

use Exception;

/**
 * Startor Class
 */
class Startor {

    public function __construct(bool $consoleMode = false) {
        try {
            $this->start($consoleMode);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function start($consoleMode = false) : void {

        // 経路探索を開始
        Routes::route($consoleMode);

        if ($consoleMode) {
            self::showShell();
        }
        else {
            self::showController();            
        }
    }
    
    private static function showController() {
        
        $kiwiConfigPath = "kiwi\Config";
        if(class_exists($kiwiConfigPath)){
            $kiwiConfigPath::handleRequest();
        }

        $cc = Container::getConfig(Routes::$route -> container);
        if ($cc) {
            $cc::handleRequest();

            if (isset($cc::$basicAuthority)) {
                // basic authority
                $postUser = null;
                $postPass = null;
                if(isset($_SERVER['PHP_AUTH_USER'])) {
                    $postUser = $_SERVER['PHP_AUTH_USER'];
                }
                if(isset($_SERVER['PHP_AUTH_PW'])) {
                    $postPass = $_SERVER['PHP_AUTH_PW'];
                }
                
                $juge = false;
                if ($postUser && $postPass) {
                    if (
                        $postUser == $cc::$basicAuthority["user"] && 
                        $postPass == $cc::$basicAuthority["pass"]
                    ) {
                        $juge = true;
                    }
                }

                if (!$juge) {
                    header('WWW-Authenticate: Basic realm="Enter username and password."');
                    header('Content-Type: text/plain; charset=utf-8');
                    if (isset($cc::$basicAuthority["failed"])) {
                        echo $cc::$basicAuthority["failed"];
                    }
                    exit;    
                }
            }
    
        }


        $controllerPath = "\kiwi\\" . Routes::$route -> container . "\\app\\controllers\\". Kiwi::upFirst(Routes::$route -> controller) . "Controller";

        if (!class_exists($controllerPath)) {
            self::error("\"" . $controllerPath . "\" is not found");
        }

        // Controllerのインスタンスとセット
        $c = new $controllerPath();
        $c -> view = Routes::$route -> controller . "/" . Routes::$route -> action;

        $c -> handleBefore();

        if (!method_exists($c, Routes::$route -> action)) {
            self::error("Action method not found");
        }

        // アクションメソッドの実行
        if (Routes::$route -> aregments) {
            // 引数がある場合
            $c -> {Routes::$route -> action}(...Routes::$route -> aregments);
        }
        else {
            // 引数が無い場合
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

    private static function error(string $errorMessage) : void {
        throw new Exception("[Initial Error] " . $errorMessage);
    }
}