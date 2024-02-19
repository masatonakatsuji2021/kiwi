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

use kiwi\core\Controller;
use kiwi\core\Routes;

class Rendering {

    public static ?Controller $controllerDelegate = null;
    private static $_dataBuffer = [];

    public static function getView(string $viewPath = null) : string {
        ob_start();
        self::view($viewPath);
        return ob_get_clean();
    }

    public static function view(string $viewPath = null) : void {

        if(!$viewPath) {
            $container = Routes::$route -> container;
            if (isset(self::$controllerDelegate -> viewOnContainer)) {
                $container = self::$controllerDelegate -> viewOnContainer;
            }

            $jsonData = kiwiJsonLoad();
            $version = $jsonData["versions"][$container];

            if (isset(self::$controllerDelegate -> view)) {
                $viewPath = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/". $version. "/views/" . self::$controllerDelegate -> view . ".view";
            }
            else{
                $viewPath = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/". $version ."/views/" . Routes::$route -> controller . "/" . Routes::$route -> action . ".view";
            }        
        }

        if (!file_exists($viewPath)) {
            echo "[View Error] View file not found.";
            return;
        }

        require $viewPath;
    }

    public static function getViewTemplate(string $templatePath = null) : string {
        ob_start();
        self::viewPart($templatePath);
        return ob_get_clean();
    }

    public static function viewTemplate(string $templatePath = null) : void {
        if (!$templatePath) {
            $container = Routes::$route -> container;
            if (isset(self::$controllerDelegate -> viewTemplateOnContainer)) {
                $container = self::$controllerDelegate -> viewTemplateOnContainer;
            }

            $jsonData = kiwiJsonLoad();
            $version = $jsonData["versions"][$container];

            $templatePath = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/". $version. "/viewTemplates/" . self::$controllerDelegate -> viewTemplate . ".view";
        }

        if (!file_exists($templatePath)) {
            echo "[View Error] ViewTemplate file not found.";
            return;
        }

        require $templatePath;
    }

    public static function getViewPart(string $viewPartPath) : string {
        ob_start();
        self::getViewPart($viewPartPath);
        return ob_get_clean();
    }

    public static function viewPart(string $viewPartPath) : void {

        $container = Routes::$route -> container;
        if (isset(self::$controllerDelegate -> viewPartOnContainer)) {
            $container = self::$controllerDelegate -> viewPartOnContainer;
        }

        $jsonData = kiwiJsonLoad();
        $version = $jsonData["versions"][$container];

        $viewPartPath = KIWI_ROOT_CONTAINER . "/" . $container . "/versions/" . $version . "/viewParts/" . $viewPartPath . ".view";
  
        if (!file_exists($viewPartPath)) {
            echo "[View Error] ViwePart file not found.";
            return;
        }

        require $viewPartPath;
    }

    public static function set(string $name, $value) : void {
        self::$_dataBuffer[$name] = $value;
    }

    public static function get(string $name) {
        if (isset(self::$_dataBuffer[$name])) {
            return self::$_dataBuffer[$name];
        }
    }
}