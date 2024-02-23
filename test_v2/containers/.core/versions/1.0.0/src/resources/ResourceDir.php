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

use DateTIme;

class ResourceDir {

    /**
     * ディレクトリ名
     */
    public string $name;

    /**
     * 作成日時
     */
    public DateTime $createDate;

    /**
     * 更新日時
     */
    public DateTime $modifiedDate;
    
    /**
     * ディレクトリのフルパス
     */
    public string $fullPath;

    /**
     *  内部リソースファイル・ディレクトリ一覧取得
     */
    public function lists() : array {
        return [];
    }

    /**
     * 指定ディレクトリ名のディレクトリ作成
     */
    public function mkdir (string $directoryName) : bool {
        return true;
    }

    /**
     * ディレクトリの削除
     */
    public function rmdir() : bool {
        return true;
    }

    /**
     * 指定ディレクトリ名へ変更
     */
    public function rename (string $directoryName) : bool {
        return true;
    }

    /**
     * 指定ファイル名でのファイル保存
     */
    public function save(string $fileName, string $contents) : bool {
        return true;
    }

    /**
     * 指定パスへのディレクトリのコピー
     */
    public function copy (string $outputPath) : bool {
        return true;
    }
}