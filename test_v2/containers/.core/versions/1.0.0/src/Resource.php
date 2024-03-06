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

use DateTIme;

class ResourceType {
    const Resource = 0;
    const Writable = 1;
    const Temporary = 2;
};

class ResourceControl {
    
    /**
     * カレントContainer名
     */
    public static string $container;
    
    protected static function container() {
        if (!isset(self::$container)) {
            self::$container = Routes::$route -> container;
        }
    }

    /**
     * get resource file or directory fullpath
     */
    public static function getPath(int $type, string $path = null) : ?string { 

        if (!$path){
            $path = "/";
        }

        $container = Container::getConfig(self::$container);

        $target = null;

        if ($type !== ResourceType::Temporary) {
            if ($type === ResourceType::Resource) {
                $target = $container::$resources;            
            }
            else if ($type === ResourceType::Writable) {
                $target = $container::$writables;
            }

            if (!$target) {
                return null;
            }
            
            $juge = false;
            foreach ($target as $url => $r_) {
                if (strpos($path . "/", $url . "/") === 0) {
                    $juge = $r_;
                    break;
                }
            }
    
            if (!$juge) {
                return null;
            }
    
            if (!isset($juge["release"])){
                return null;
            }
    
            if (!$juge["release"]){
                return null;
            }    
        }

        if ($type == ResourceType::Resource) {
            $res = KIWI_ROOT_CONTAINER . "/" . self::$container . "/versions/" . Routes::$route -> containerVersion . "/resources/" . $path;
        }
        else if ($type == ResourceType::Writable) {
            $res = KIWI_ROOT_CONTAINER . "/" . self::$container . "/writables/" . $path;
        }
        else if ($type == ResourceType::Temporary) {
            $res = KIWI_ROOT_CONTAINER . "/" . self::$container . "/temporaries/" . $path;
        }

        $res = str_replace("//", "/", $res);
        return $res;
    }

    /**
     * 指定パスの存在可否
     */
    public static function exists (int $type, string $path) : bool {
        self::container();
        $path = self::getPath($type, $path);
        if (!$path) {
            return false;
        }
        return file_exists($path);
    }

    /**
     * 指定パスのディレクトリ判定
     */
    public static function isDirectory (int $type, string $path) : bool {
        self::container();
        $path = self::getPath($type, $path);
        return is_dir($path);
    }
    
    /**
     * 指定パスのファイル判定
     */
    public static function isFile (int $type, string $path) : bool {
        self::container();
        $path = self::getPath($type, $path);
        return is_file($path);
    } 

    /**
     * リソース用ディレクトリ内のファイル・ディレクトリ一覧取得
     */
    public static function lists(int $type, string $path = null) : ?array {
        self::container();
        if ($path) {
            self::isDirectory($type, $path);

            if (!self::isDirectory($type, $path)) {
                return null;
            }    
        }

        $path = self::getPath($type, $path);
        
        if (!$path) {
            return null;
        }

        $glob = glob($path . "/*");

        $res = [];
        foreach ($glob as $g_) {
            if (is_dir($g_)) {
                $buffer = new ResourceDir($g_);
            } 
            else {
                $buffer = new ResourceFile($g_);
            }         
            $res[] = $buffer;
        }

        return $res;
    }

    /**
     * 指定パスのリソースファイル・ディレクトリ情報取得
     */
    public static function get (int $type, string $path) {
        self::container();
        $path = self::getPath($type, $path);
        if (!$path) {
            return null;
        }

        if (is_dir($path)) {
            return new ResourceDir($path);
        }
        else {
            return new ResourceFile($path);
        }
    }

    /**
     * 指定パスのディレクトリ作成
     */
    public static function mkdir (int $type, string $path) : bool {
        self::container();
        $path = self::getPath($type, $path);

        if (!$path){
            return false;
        }

        if (is_dir($path)) {
            return false;
        }
        mkdir($path, 0777, true);
        return true;
    }
    
    /**
     * 指定パスのファイル・ディレクトリ削除
     */
    public static function remove (int $type, string $path) : bool {
        self::container();
        $path = self::getPath($type, $path);

        if (!$path){
            return false;
        }

        if (is_dir($path)) {
            Kiwi::delete($path);
        }
        else {
            if (!file_exists($path)) {
                return false;
            }
            unlink($path);
        }

        return true;
    }
    
    /**
     * 指定パスへのファイル・ディレクトリのパス変更
     */
    public static function rename (int $type, string $beforePath, string $afterPath) : bool {
        self::container();
        $beforePath = self::getPath($type, $beforePath);
        if (!$beforePath) {
            return false;
        }

        $afterPath = self::getPath($type, $afterPath);
        if (!$afterPath) {
            return false;
        }

        if (!(file_exists($beforePath) || is_dir($beforePath))) {
            return false;
        }

        if (file_exists($afterPath) || is_dir($afterPath)){
            return false;
        }
        rename($beforePath, $afterPath);
        return true;
    }

    /**
     * 指定パスでのファイルの保存
     */
    public static function save (int $type, string $filePath, string $contents, int $flags = 0) : bool {
        self::container();
        $filePath = self::getPath($type, $filePath);
        if (!$filePath) {
            return false;
        }
        file_put_contents($filePath, $contents, $flags);
        return true;
    }

    /**
     * 指定パスのファイル・ディレクトリのコピー
     */
    public static function copy(int $type, string $inputPath, string $outputPath) : bool {
        self::container();
        $inputPath = self::getPath($type, $inputPath);
        if (!$inputPath) {
            return false;
        }
        $outputPath = self::getPath($type, $outputPath);
        if (!$outputPath) {
            return false;
        } 

        if (!(file_exists($inputPath) || is_dir($inputPath))) {
            return false;
        }

        if (file_exists($outputPath) || is_dir($outputPath)) {
            return false;
        }

        if (is_dir($inputPath)) {
            Kiwi::copy($inputPath, $outputPath, true);
        }
        else {
            copy($inputPath, $outputPath);
        }
        return true;
    }
    
}

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


    public function __construct(string $path) {
        $this->fullPath = $path;
        $this->name = basename($path);
        $this->createDate = new DateTime(Date("Y-m-d H:i:s", filectime($path)));
        $this->modifiedDate = new DateTime(Date("Y-m-d H:i:s", filemtime($path)));
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
     * ファイルのフルパス
     */
    public string $fullPath;

    public function __construct(string $path) {
        $this->fullPath = $path;
        $this->name = basename($path);
        $this->mimeType = mime_content_type($path);
        $this->size = filesize($path);
        $this->createDate = new DateTime(Date("Y-m-d H:i:s", filectime($path)));
        $this->modifiedDate = new DateTime(Date("Y-m-d H:i:s", filemtime($path)));
    }
    /**
     * ファイルデータ取得
     */
    public function raw() {
        return file_get_contents($this->fullPath);
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

class Resource {

    /**
     * ResourceType (Resource / Writable / Temporary)
     */
    public static int $type = ResourceType::Resource;

    /**
     * get resource file or directory fullpath
     */
    public static function getPath(string $path = null) : ?string { 
        return ResourceControl::getPath(self::$type, $path);
    }

    /**
     * 指定パスの存在可否
     */
    public static function exists (string $path) : bool {
        return ResourceControl::exists(self::$type, $path);
    }

    /**
     * 指定パスのディレクトリ判定
     */
    public static function isDirectory (string $path) : bool {
        return ResourceControl::isDirectory(self::$type, $path);
    }
    
    /**
     * 指定パスのファイル判定
     */
    public static function isFile (string $path) : bool {
        return ResourceControl::isFile(self::$type, $path);
    } 

    /**
     * リソース用ディレクトリ内のファイル・ディレクトリ一覧取得
     */
    public static function lists(string $path = null) : ?array {
        return ResourceControl::lists(self::$type, $path);
    }

    /**
     * 指定パスのリソースファイル・ディレクトリ情報取得
     */
    public static function get (string $path) {
        return ResourceControl::get(self::$type, $path);
    }

    /**
     * 指定パスのディレクトリ作成
     */
    public static function mkdir (string $path) : bool {
        return ResourceControl::mkdir(self::$type, $path);
    }

    /**
     * 指定パスのファイル・ディレクトリ削除
     */
    public static function remove (string $path) : bool {
        return ResourceControl::remove(self::$type, $path);
    }

    /**
     * 指定パスへのファイル・ディレクトリのパス変更
     */
    public static function rename (string $beforePath, string $afterPath) : bool {
        return ResourceControl::rename(self::$type, $beforePath, $afterPath);
    }

    /**
     * 指定パスでのファイルの保存
     */
    public static function save (string $filePath, string $contents, int $flags = 0) : bool {
        return ResourceControl::save(self::$type, $filePath, $contents, $flags);
    }

    /**
     * 指定パスのファイル・ディレクトリのコピー
     */
    public static function copy(string $inputPath, string $outputPath) : bool {
        return ResourceControl::copy(self::$type, $inputPath, $outputPath);
    }
}

class Writable {
    
    /**
     * ResourceType (= Writable)
     */
    public static int $type = ResourceType::Writable;

    /**
     * get resource file or directory fullpath
     */
    public static function getPath(string $path = null) : ?string { 
        return ResourceControl::getPath(self::$type, $path);
    }

    /**
     * 指定パスの存在可否
     */
    public static function exists (string $path) : bool {
        return ResourceControl::exists(self::$type, $path);
    }

    /**
     * 指定パスのディレクトリ判定
     */
    public static function isDirectory (string $path) : bool {
        return ResourceControl::isDirectory(self::$type, $path);
    }
    
    /**
     * 指定パスのファイル判定
     */
    public static function isFile (string $path) : bool {
        return ResourceControl::isFile(self::$type, $path);
    } 

    /**
     * リソース用ディレクトリ内のファイル・ディレクトリ一覧取得
     */
    public static function lists(string $path = null) : ?array {
        return ResourceControl::lists(self::$type, $path);
    }

    /**
     * 指定パスのリソースファイル・ディレクトリ情報取得
     */
    public static function get (string $path) {
        return ResourceControl::get(self::$type, $path);
    }

    /**
     * 指定パスのディレクトリ作成
     */
    public static function mkdir (string $path) : bool {
        return ResourceControl::mkdir(self::$type, $path);
    }

    /**
     * 指定パスのファイル・ディレクトリ削除
     */
    public static function remove (string $path) : bool {
        return ResourceControl::remove(self::$type, $path);
    }

    /**
     * 指定パスへのファイル・ディレクトリのパス変更
     */
    public static function rename (string $beforePath, string $afterPath) : bool {
        return ResourceControl::rename(self::$type, $beforePath, $afterPath);
    }

    /**
     * 指定パスでのファイルの保存
     */
    public static function save (string $filePath, string $contents, int $flags = 0) : bool {
        return ResourceControl::save(self::$type, $filePath, $contents, $flags);
    }

    /**
     * 指定パスのファイル・ディレクトリのコピー
     */
    public static function copy(string $inputPath, string $outputPath) : bool {
        return ResourceControl::copy(self::$type, $inputPath, $outputPath);
    }
}

class Temporary extends Writable {

    /**
     * ResourceType (=Temporary)
     */
    public static int $type = ResourceType::Temporary;

    /**
     * get resource file or directory fullpath
     */
    public static function getPath(string $path = null) : ?string { 
        return ResourceControl::getPath(self::$type, $path);
    }

    /**
     * 指定パスの存在可否
     */
    public static function exists (string $path) : bool {
        return ResourceControl::exists(self::$type, $path);
    }

    /**
     * 指定パスのディレクトリ判定
     */
    public static function isDirectory (string $path) : bool {
        return ResourceControl::isDirectory(self::$type, $path);
    }
    
    /**
     * 指定パスのファイル判定
     */
    public static function isFile (string $path) : bool {
        return ResourceControl::isFile(self::$type, $path);
    } 

    /**
     * リソース用ディレクトリ内のファイル・ディレクトリ一覧取得
     */
    public static function lists(string $path = null) : ?array {
        return ResourceControl::lists(self::$type, $path);
    }

    /**
     * 指定パスのリソースファイル・ディレクトリ情報取得
     */
    public static function get (string $path) {
        return ResourceControl::get(self::$type, $path);
    }

    /**
     * 指定パスのディレクトリ作成
     */
    public static function mkdir (string $path) : bool {
        return ResourceControl::mkdir(self::$type, $path);
    }

    /**
     * 指定パスのファイル・ディレクトリ削除
     */
    public static function remove (string $path) : bool {
        return ResourceControl::remove(self::$type, $path);
    }

    /**
     * 指定パスへのファイル・ディレクトリのパス変更
     */
    public static function rename (string $beforePath, string $afterPath) : bool {
        return ResourceControl::rename(self::$type, $beforePath, $afterPath);
    }

    /**
     * 指定パスでのファイルの保存
     */
    public static function save (string $filePath, string $contents, int $flags = 0) : bool {
        return ResourceControl::save(self::$type, $filePath, $contents, $flags);
    }

    /**
     * 指定パスのファイル・ディレクトリのコピー
     */
    public static function copy(string $inputPath, string $outputPath) : bool {
        return ResourceControl::copy(self::$type, $inputPath, $outputPath);
    }
}