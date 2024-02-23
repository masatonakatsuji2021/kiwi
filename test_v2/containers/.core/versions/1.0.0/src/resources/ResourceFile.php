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

use DateTime;

class ResourceFile {
    
    /**
     * ファイル名
     */
    public string $name;
    
    /**
     * ファイルサイズ
     */
    public int $size;
    
    /**
     * MimeType
     */
    public string $mimeType;
    
    /**
     * 作成日時
     */
    public DateTIme $createDate;
    
    /**
     * 更新日時
     */
    public DateTIme $modifiedDate;

    /**
     * ファイルデータ取得
     */
    public function raw() {

    }

    /**
     * ファイルの削除
     */
    public function unlink() : bool {
        return true;
    }
    
    /**
     * 指定ファイル名へ変更
     */
    public function rename (string $fileName) : bool {
        return true;
    }
    
    /**
     * ファイルの上書き保存
     */
    public function save($contents) : bool {
        return true;
    }

    /**
     * 指定パスへのファイルのコピー
     */
    public function copy(string $outputPath) : bool {
        return true;
    }
}