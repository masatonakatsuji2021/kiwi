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

class FookControl {

    /**
     * load Fook class object
     */
    public static function load(string $fookName) : array {
        $containerVersions = kiwiLoad()["versions"];
        $res = [];
        foreach ($containerVersions as $container => $version) {
             $fullFookName = "kiwi\\" . $container. "\\fooks\\" . $fookName . "Fook";
 ;
             if (!class_exists($fullFookName)) {
                 continue;
             }
 
             $fook = new $fullFookName();
 
             $res[] = $fook;
        }

        return $res;
    }

    /**
     * load fook class Object On Container
     */
    public static function loadOnContainer(string $containerName, string $fookName) {
        $fullFookName = "kiwi\\" . $containerName. "\\fooks\\" . $fookName . "Fook";
        if (!class_exists($fullFookName)) {
            return null;
        }
        return new $fullFookName();
    }

    /**
     * execute fook class method
     */
    public static function excute(string $fookName, string $methodName = "run") : array {
        $lists = self::load($fookName);
        $res = [];
        foreach ($lists as $l_) {
            if (!method_exists($l_, $methodName)) {
                continue;
            }
            $res[] = $l_->{$methodName}();
        }
        return $res;
    }

    /**
     * execute fook class on container
     */
    public static function excuteonContainer(string $containerName, string $fookName, string $methodName = "run") {
        $fook = self::loadOnContainer($containerName, $fookName);
        if (!$fook) {
            return;
        }

        if (!method_exists($fook,$methodName)) {
            return;
        }

        return $fook->{$methodName}();
    }
}