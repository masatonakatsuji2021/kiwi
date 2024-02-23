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

namespace kiwi\core\routes;

class RouteSet {

    public static function add(string $method, string $controller, string $action = null) : string {
        if ($method) {
            $str = "method:" .$method . ", controller:". $controller;
        }
        else {
            $str = "controller:". $controller. ", action:" . $action;
        }

        if ($action) {
            $str .= ", action:" . $action;
        }

        return $str;
    }

    public static function get(string $controller, string $action = null) : string{
        return self::add("get", $controller, $action);
    }

    public static function post(string $controller, string $action = null) : string{
        return self::add("post", $controller, $action);
    }

    public static function put(string $controller, string $action = null) : string{
        return self::add("put", $controller, $action);
    }

    public static function delete(string $controller, string $action = null) : string{
        return self::add("delete", $controller, $action);
    }
}