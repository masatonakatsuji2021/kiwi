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

namespace kiwi\core\containers;

use kiwi\core\configs\ContainerConfig;

class Container {

    // ContainerConfigクラス取得
    public static function getConfig(string $containerName) : ?ContainerConfig {

        $container = "\kiwi\\" . $containerName . "\ContainerConfig";
                
        if (!class_exists($container)) {
            return null;
        }

        return new $container;
    }
    
    // インストール済Containerリスト取得
    public static function locals(array $options = null) : array {
        return [];  
    }
    
    // 指定Container名のインストール済Container取得
    public static function local(string $ContainerName) : ?LocalContainer{
        return null;
    }
    
    // 指定リポジトリ情報での最新版RemoteContainer取得
    public static function remote(ContainerRepository $containerRepository) : ?RemoteContainer {
        return null;
    }
    
    // 指定Containerのバージョンデータリスト取得
    public static function versions(string $containerName) : ?array {
        return [];
    }

    // 指定Container, バージョン番号のバージョンデータContainerの取得
    public static function getVersion(string $ContainerName, string $version) : ?VersionContainer {
        return null;
    }

    // 指定Containerファイルをバージョンデータへセット
    public static function setVersion(string $containerName, string $containerFilePath) : bool {
        return true;
    }    
}

