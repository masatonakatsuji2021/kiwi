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

class LocalContainer extends ContainerInfo {
    
    /**
     * Containerディレクトリパス
     */
    public string $path;

    /**
     * バージョンデータディレクトリのパス
     */
    public string $pathVersions;
    
    /**
     * 書込可能データディレクトリのパス
     */
    public string $pathWritables;
    
    /**
     * テンポラリディレクトリのパス
     */
    public string $pathTemps;
    
    /**
     * リポジトリ情報ディレクトリのパス
     */
    public string $pathRepositories;

    /**
     * Containerのルーティング先URL
     */
    public string $url;
    
    /**
     * 有効/無効
     */
    public bool $status;

    /**
     * 指定zipファイルパスのインポート実行
     */
    public function import(string $importFilePath) : bool {
        return true;
    }

    /**
     * 指定パスへのエクスポート実行
     */
    public function export(string $exportFIlePath) : bool {
        return true;
    }

    /**
     * アンインストール実行
     */
    public function uninstall() : bool {
        return true;
    } 
}