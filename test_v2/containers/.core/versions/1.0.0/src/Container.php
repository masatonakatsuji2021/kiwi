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

use kiwi\core\ContainerConfig;
use kiwi\core\Handling;

class Container {

    private static $configBuffer = [];
    private static $handlingBuffer = [];

    // ContainerConfigクラス取得
    public static function getConfig(string $containerName) : ?ContainerConfig {

        if (
            $containerName == "core" ||
            $containerName == ".core"
        ) {
            // coreおよび.coreは不可
            return null;
        }

        if (isset(self::$configBuffer[$containerName])) {
            return self::$configBuffer[$containerName];
        }

        $classPath = "\kiwi\\" . $containerName . "\ContainerConfig";
                
        if (!class_exists($classPath)) {
            self::$configBuffer[$containerName] = null;
            return null;
        }

        $config = new $classPath;
        self::$configBuffer[$containerName] = $config;

        return $config;
    }
    
    // Handling クラス取得
    public static function getHandling(string $containerName) : ?Handling {
        
        if (
            $containerName == "core" ||
            $containerName == ".core"
        ) {
            // coreおよび.coreは不可
            return null;
        }

        if (isset(self::$handlingBuffer[$containerName])) {
            return self::$handlingBuffer[$containerName];
        }

        $classPath = "\kiwi\\" . $containerName . "\Handling";

        if (!class_exists($classPath)) {
            self::$handlingBuffer[$containerName] = null;
            return null;
        }
        
        $handling = new $classPath;
        self::$handlingBuffer[$containerName] = $handling;

        return $handling;
    }

    /**
     * get kiwi container data
     */
    public static function getKiwi(string $containerName) : ?array {

        $kiwi = kiwiLoad();
        if(!isset($kiwi["versions"][$containerName])){
            return null;
        }

        $path = KIWI_ROOT_CONTAINER . "/" . $containerName . "/versions/" . $kiwi["versions"][$containerName] . "/container.json";

        $getKiwi = json_decode(file_get_contents($path), true);

        return $getKiwi;
    }
}

