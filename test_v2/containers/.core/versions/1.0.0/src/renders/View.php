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

namespace kiwi\core\renders;

use kiwi\core\routes\Routes;

class View extends Render {

    /**
     * Viewファイルのロード
     */
    public static function load(string $viewPath = null, bool $onResponse = false) : ?string {
        if(!$viewPath) {
            $container = Routes::$route -> container;
            if (isset(self::$controllerDelegate -> viewOnContainer)) {
                $container = self::$controllerDelegate -> viewOnContainer;
            }

            $jsonData = kiwiLoad();
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
            return null;
        }

        if ($onResponse) {
            ob_start();
            require $viewPath;
            return ob_get_clean();
        }

        require $viewPath;
        return null;
    }
}