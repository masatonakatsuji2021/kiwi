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

use Exception;
use kiwi\core\routes\Routes;

/**
 * Kiwi Class
 */
class Kiwi {

    // 
    /**
     * Deepest directory search
     * @param string $path search directory path
     * @return array directory and file list
     */
    public static function fileSearch(string $path) : array {
        $target = str_replace("//", "/", $path."/*");
        $search = glob($target);
        $data = [
            "file" => [],
            "dir" => [],
        ];
        foreach ($search as $s_) {
            if (is_dir($s_)) {
                $data["dir"][] = $s_;
                $buffers = self::fileSearch($s_);
                foreach ($buffers["dir"] as $b_) {
                    $data["dir"][] = $b_;
                }
                foreach ($buffers["file"] as $b_) {
                    $data["file"][] = $b_;
                }
            }
            else {
                $data["file"][] = $s_;
            }
        }
        return $data;
    }

    /**
     * Bulk copy within directory
     * @param string $targetPath Copy source directory path
     * @param string $copyPath Destination directory path
     * @param bool $deleteCopy = false If this value is true, copy will be performed after deletion.
     * @return bool Execution result 
     */
    public static function copy(string $targetPath, string $copyPath, bool $deleteCopy = false) : bool {

        if ($deleteCopy) {
            self::delete($copyPath);
        }

        try {
            mkdir($copyPath);

            $targetFiles = self::fileSearch($targetPath);
    
            foreach ($targetFiles["dir"] as $d_) {
                $dirPath = $copyPath . substr($d_, strlen($targetPath));
                $juge = mkdir($dirPath);
                if (!$juge) {
                    throw new Exception();
                }
            }
    
            foreach ($targetFiles["file"] as $f_) {
                $outputPath = $copyPath . substr($f_, strlen($targetPath));
                $juge = copy($f_, $outputPath);
                if (!$juge) {
                    throw new Exception();
                }
            }    
        } catch(Exception $e) {
            return false;
        }
        
        return true;
    }

    /**
     * Bulk deletion in directory
     * @param string $targetPath Directory path to be deleted
     * @param bool Execution result
     */
    public static function delete(string $targetPath) : bool {

        if (!is_dir($targetPath)) {
            return true;
        }

        $targetFiles = self::fileSearch($targetPath);

        try {

            foreach ($targetFiles["file"] as $f_) {
                $juge = unlink($f_);
                if (!$juge) {
                    throw new Exception();
                }
            }

            rsort($targetFiles["dir"]);

            foreach ($targetFiles["dir"] as $d_){
                $juge = rmdir($d_);
                if (!$juge) {
                    throw new Exception();
                }
            }

            $juge = rmdir($targetPath);
            if (!$juge) {
                throw new Exception();
            }

        } catch (Exception $e){
            return false;
        }

        return true;
    }

    /**
     * Version number conversion (string -> integer value)
     * @param string $version version number string
     * @return int version number integer value
     */
    public static function versionOnInteger(string $version) : int {
        $versions = explode(".", $version);
        $val = sprintf("%02d",$versions[0]) . sprintf("%02d", $versions[1]) . sprintf("%02d", $versions[2]); 
        return intval($val);
    }

    /**
     * Version number conversion (integer value -> string)
     * @param int $version version number integer value
     * @param string version number string
     */
    public static function versionOnString(int $version) : string {
        $verStr = sprintf("%06d", $version);
        return intval(substr($verStr, 0, 2)) . "." . intval(substr($verStr, 2, 2)). "." . intval(substr($verStr, 4, 2));
    }

    /**
     * Load env file
     * @param string $filePath env file path
     * @return string load env data
     */
    public static function loadEnv(string $filePath) : array {
        $fs = fopen($filePath, "r");
        $contents = [];
        while (($line = fgets($fs))) {
            $buff = explode("=", $line);
            $buff[0] = trim($buff[0]);
            $buff[1] = trim($buff[1]);
            $contents[$buff[0]] = $buff[1];
        }
        fclose($fs);
        return $contents;
    }

    /**
     * Save env file
     * @param string $filePath Save env file path
     * @param array $envData Save env data
     * @param bool Execution result
     */
    public static function saveEnv(string $filePath, array $envData) : bool {





        return 0;
    }

    // 
    /**
     * Convert first character to uppercase
     * @param string $text
     * @return string first character to uppercase
     */
    public static function upFirst(string $text) : string {
        return strtoupper(substr($text, 0, 1)) . substr($text, 1);
    }

    /**
     * Convert first character to lowercase
     * @param string $test 
     * @return string first character to lowercase
     */
    public static function downFirst(string $text) : string {
        return strtolower(substr($text, 0, 1)) . substr($text, 1);
    }

    /**
     * For autoload execution when using composer package
     * If you use Composer(https://getcomposer.org/) in some library, 
     * use this method to autoload Composer.
     * @return void
     */
    public static function composerAutoload() : void {
        $jsonData = kiwiLoad();
        $version = $jsonData["versions"][Routes::$route -> container];
        require KIWI_ROOT_CONTAINER . "/" . Routes::$route -> container ."/versions/". $version. "/vendor/autoload.php";
    }   
}