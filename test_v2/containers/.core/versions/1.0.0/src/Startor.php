<?php
/**
 * MIT License
 *
 * Copyright (c) 2024 Masato Nakatsuji
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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
            if ($consoleMode) {
                echo $e->getMessage();
            } 
            else {
                self::showExceptionController($e);
            }
        }
    }

    private function start($consoleMode = false) : void {

        // 経路探索を開始
        if ($consoleMode) {
            self::showShell();
        }
        else {
            $res = Routes::routeWeb();
            if ($res) {
                self::showController();
            }
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

        if (!Routes::$route -> successed) {
            self::error("Route Controller or Action not found.");
        }

        $controllerPath = "\kiwi\\" . Routes::$route -> container . "\controllers\\". Kiwi::upFirst(Routes::$route -> controller) . "Controller";

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

    private static function showExceptionController (Exception $exception) {
        try {
            $controllerPath = "\kiwi\\" . Routes::$route -> container . "\controllers\\ExceptionController";
            
            if (!class_exists($controllerPath)) {
                $controllerPath = "kiwi\core\ExceptionController";
            }

            // Controllerのインスタンスとセット
            $c = new $controllerPath();
            $c -> view = "exception/index";

            $c->handle($exception);

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


        } catch (Exception $e) {

        }

    }

    private static function showShell (){

 
    }

    private static function error(string $errorMessage) : void {
        throw new Exception("[Initial Error] " . $errorMessage);
    }
}