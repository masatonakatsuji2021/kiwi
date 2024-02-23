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
use kiwi\core\controllers\Controller;

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
        return Routes::$route -> full . $url;
    }
}