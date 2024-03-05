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

use kiwi\core\Routes;
use kiwi\core\Controller;

class Render {

    public static ?Controller $controllerDelegate = null;
    private static $_dataBuffer = [];

    /**
     * レンダリングへデータをセット
     */
    public static function set(string $name, $value) : void {
        self::$_dataBuffer[$name] = $value;
    }

    /**
     * レンダリングへデータを一括セット
     */
    public static function bind(array $values) : void {
        foreach ($values as $key => $value) {
            self::$_dataBuffer[$key] = $value;
        }
    }

    /**
     * セットされたデータを取得
     */
    public static function get (string $name) {
        if (isset(self::$_dataBuffer[$name])) {
            return self::$_dataBuffer[$name];
        }
    }

    /**
     * 指定URLをルートベースURLに変更
     */
    public static function url (string $url) : string {
        $res = Routes::$route -> full . $url;
        $res = str_replace("//", "/", $res);
        $res = str_replace("http:/", "http://", $res);
        $res = str_replace("https:/", "https://", $res);
        return $res;
    }

    /**
     * Viewファイルのロード
     */
    public static function loadView(string $viewName = null, string $container = null, bool $onResponse = false) : ?string {
        if(!$viewName) {
            if (!$container) {
                $container = Routes::$route -> container;

                if (isset(self::$controllerDelegate -> viewOnContainer)) {
                    $container = self::$controllerDelegate -> viewOnContainer;
                }    
            }

            $jsonData = kiwiLoad();
            $version = $jsonData["versions"][$container];

            if (isset(self::$controllerDelegate -> view)) {
                $viewName = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/". $version. "/views/" . self::$controllerDelegate -> view . ".view";
            }
            else{
                $viewName = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/". $version ."/views/" . Routes::$route -> controller . "/" . Routes::$route -> action . ".view";
            }        
        }

        if (!file_exists($viewName)) {
            echo "[View Error] View file not found.";
            return null;
        }

        if ($onResponse) {
            ob_start();
            require $viewName;
            return ob_get_clean();
        }

        require $viewName;
        return null;
    }

    /**
     * Viewファイルのロード結果の取得
     */
    public static function outView(string $viewName = null, string $container = null) : string {
        return self::loadView($viewName, $container, true);
    }

    /**
     * ViewTemplateファイルのロード
     */
    public static function loadTemplate(string $templateName = null, string $container = null, bool $onResponse = false) : ?string {
        if (!$templateName) {
            if (!$container) {
                $container = Routes::$route -> container;
                if (isset(self::$controllerDelegate -> viewTemplateOnContainer)) {
                    $container = self::$controllerDelegate -> viewTemplateOnContainer;
                }    
            }

            $jsonData = kiwiLoad();
            $version = $jsonData["versions"][$container];

            $templateName = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/". $version. "/viewTemplates/" . self::$controllerDelegate -> viewTemplate . ".view";
        }

        if (!file_exists($templateName)) {
            echo "[View Error] ViewTemplate file not found.";
            return null;
        }

        if ($onResponse) {
            ob_start();
            require $templateName;
            return ob_get_clean();
        }

        require $templateName;
        return null;
    }

    /**
     * ViewTemplateファイルのロード結果の取得
     */
    public static function outTemplate(string $templateName = null, string $container = null) : string {
        return self::loadTemplate($templateName, $container, true);
    }

    /**
     * ViewPartファイルのロード
     */
    public static function loadPart(string $partName, string $container = null, bool $onResponse = false) : ?string {
        if (!$container) {
            $container = Routes::$route -> container;
            if (isset(self::$controllerDelegate -> viewPartOnContainer)) {
                $container = self::$controllerDelegate -> viewPartOnContainer;
            }    
        }

        $jsonData = kiwiLoad();
        $version = $jsonData["versions"][$container];

        $partName = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/" . $version . "/viewParts/" . $partName . ".view";

        if (!file_exists($partName)) {
            echo "[View Error] ViewPart file not found.";
            return null;
        }

        if ($onResponse) {
            ob_start();
            require $partName;
            return ob_get_clean();
        }

        require $partName;
        return null;
    }

    /**
     * ViewPartファイルのロード結果の取得
     */
    public static function outPart(string $partName = null, string $container = null) : string {
        return self::loadPart($partName, $container, true);
    }
}

