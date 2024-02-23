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

namespace kiwi\core\resources;

class Resource {

    /**
     * ResourceType (Resource / Writable / Temporary)
     */
    public static ResourceType $type = ResourceType::Resource;
    
    /**
     * カレントContainer名
     */
    public static string $container;
    
    /**
     * 指定パスの存在可否
     */
    public static function exists (string $path) : bool {
        return true;
    }

    /**
     * 指定パスのディレクトリ判定
     */
    public static function isDirectory (string $path) : bool {
        return true;
    }
    
    /**
     * 指定パスのファイル判定
     */
    public static function isFile (string $path) : bool {
        return true;
    } 

    /**
     * リソース用ディレクトリ直下のリソースファイル・ディレクトリ一覧取得
     */
    public static function lists() : array {
        return [];
    }

    /**
     * 指定パスのリソースファイル・ディレクトリ情報取得
     */
    public static function read (string $path) : ResourceFile | ResourceDir | null {
        return null;
    }
}