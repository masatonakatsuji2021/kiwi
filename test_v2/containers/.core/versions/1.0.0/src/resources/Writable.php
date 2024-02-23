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

class Writable extends Resource {
    
    /**
     * ResourceType (= Writable)
     */
    public static ResourceType $type = ResourceType::Writable;

    /**
     * 指定パスのディレクトリ作成
     */
    public static function mkdir (string $path) : bool {
        return true;
    }
    
    /**
     * 指定パスのファイル・ディレクトリ削除
     */
    public static function remove (string $path) : bool {
        return true;
    }
    
    /**
     * 指定パスへのファイル・ディレクトリのパス変更
     */
    public static function rename (string $beforePath, string $afterPath) : bool {
        return true;
    }

    /**
     * 指定パスでのファイルの保存
     */
    public static function save (string $path, string $contents) : bool {
        return true;
    }

    /**
     * 指定パスのファイルの追記
     */
    public static function append (string $path, string $addContents) : bool {
        return true;
    }

    /**
     * 指定パスのファイル・ディレクトリのコピー
     */
    public static function copy(string $inputPath, string $outputPath) : bool {
        return true;
    }
    

}