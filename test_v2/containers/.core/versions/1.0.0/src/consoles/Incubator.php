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

namespace kiwi\core\consoles;

use Exception;
use kiwifw\configs\ProjectConfig;
use kiwi\core\Kiwi;
use kiwi\core\containers\Container;
use kiwi\core\containers\ContainerCreateOption;
use kiwi\core\containers\ContainerDevelopment;
use kiwi\core\containers\ContainerVersionUpOption;
use kiwi\core\developments\PhpCodeMake;


class Incubator {

    public function __construct() {

        try{
            if (!isset(ProjectConfig::$useIncubator)) {
                throw new Exception("[ERROR] This command execution is not allowed.");
            }

            if (ProjectConfig::$useIncubator == false){
                throw new Exception("[ERROR] This command execution is not allowed.");
            }

            $this->start();
        } catch (Exception $e){
            echo $e->getMessage();
        }
    }

    private function start() {
        global $argv;
        array_shift($argv);

        if (count($argv) == 0) {


        }
        else {
            $main = $argv[0];

            if ($main == "container") {
                $sub = $argv[1];

                if ($sub == "create") {
                    $this->create();
                }
                else if ($sub == "versionup" || $sub == "vup") {
                    $this->versionUp();
                }
                else if ($sub == "switch") {
                    $this->switch();
                }
                else if ($sub == "commit") {
                    $this->commit();
                }
                else if ($sub == "export") {
                    $this->export();
                }
            }
        }
    }

    private function create() {
        echo "[Kiwi Incubator Container Create]\n\n";

        $cco = new ContainerCreateOption();

        $value = null;
        while (!$value) {
            echo "Q. Container name? :";
            $value = trim(fgets(STDIN));

            if ($value == "") {
                echo "[ERROR] Container name has not been entered. \n";
                $value = null;
                continue;
            }

            if (
                $value == "core" || 
                $value == ".core"
            ) {
                // coreおよび.coreは不可
                echo "[ERROR] This container cannot be created because it already exists. \n";
                $value = null;
                continue;
            }

            if (Container::getConfig($value)) {
                echo "[ERROR] This container cannot be created because it already exists. \n";
                $value = null;
                continue;
            }
        }

        $cco -> name = $value;

        // version number
        $value = null;
        while (!$value) {
            echo "Q. Initial version number? (1.0.0) :";
            $value = trim(fgets(STDIN));
            if ($value == "") {
                $value = "1.0.0";
            }
        }

        $cco -> version = $value;

        // title
        $value = null;
        while (!$value) {
            echo "Q. Display container title? (" . $cco->name . ") :";
            $value = trim(fgets(STDIN));
            if ($value == "") {
                $value = $cco->name;
            }
        }

        $cco -> title = $value;

        // description
        $value = null;
        echo "Q. Container description? () :";
        $value = trim(fgets(STDIN));

        $cco -> description = $value;
        
        // author
        $value = null;
        echo "Q. Author? () :";
        $value = trim(fgets(STDIN));
        $cco -> author = $value;

        // home url
        $value = null;
        echo "Q. Home url? () :";
        $value = trim(fgets(STDIN));
        
        $cco -> homeUrl = $value;
        
        // home url
        $value = null;
        echo "Q. Email address? () :";
        $value = trim(fgets(STDIN));

        $cco -> email = $value;

        echo "\nCreate a Container with the following content.\n\n";

        echo "  Container name          : " . $cco->name. "\n";
        echo "  Initial version number  : " . $cco->version ."\n";
        echo "  Display container title : " . $cco->title . "\n";
        echo "  Container description   : " . $cco->description . "\n";
        echo "  Author                  : " . $cco->author . "\n";
        echo "  Home url                : " . $cco->homeUrl . "\n";
        echo "  Email address           : " . $cco->email . "\n";
        echo "\n\nIt is ok? (y) :";
        $value = trim(fgets(STDIN));

        if ($value == "" || $value == "y" || $value == "Y"){
            $value = true;
        }
        else {
            $value = false;
        }

        $juge = ContainerDevelopment::create($cco);

    }

    private function versionUp() {
        echo "[Kiwi Incubator Container VersionUp]\n\n";

        $cvo = new ContainerVersionUpOption();

        $value = null;

        while (!$value) {
            echo "Q. コンテナ名を入力 :";
            $value = trim(fgets(STDIN));
            if ($value == ""){
                echo "[ERROR] コンテナ名が未入力です。\n";
                $value = null;
                continue;
             }

             if (
                $value == ".core" ||
                $value == "core"
             ){
                echo "[ERROR] このコンテナ名は利用できません\n";
                $value = null;
                continue;
             }

             if (!Container::getConfig($value)) {
                echo "[ERROR] このコンテナ名のコンテナはインストールされていません \n";
                $value = null;
                continue;
            }
        }

        $cvo -> name = $value;

        $kiwi = Container::getKiwi($value);

        $nowVersion = $kiwi["version"];
        $nowVersion = "1.3.12";
        $patchVersion = Kiwi::nextPatchVersion($nowVersion);
        $minorVersion = Kiwi::nextMinorVersion($nowVersion);
        $majorVersion = Kiwi::nextMajorVersion($nowVersion);
        
        echo "予定しているバージョン番号\n\n";

        echo "  Patch Version   : " . $patchVersion . "\n";
        echo "  Minor Version   : " . $minorVersion . "\n";
        echo "  Major Version   : " . $majorVersion . "\n\n";

        $value = null;
        while (!$value) {
            echo "Q. アップグレードするバージョン種類を入力 [patch/minor/major] (patch) :";
            $value = trim(fgets(STDIN));

            if ($value == ""){
                $value = "patch";
            }

            if (!($value == "patch" || $value == "minor" || $value == "major")){
                echo "[ERROR] 入力されたバージョン種類が不正です\n";
                $value = null;
            }
        }

        $cvo -> nowVersion = $nowVersion;
        $cvo -> nextVersionType = $value;
        if ($cvo -> nextVersionType == "patch") {
            $cvo -> nextVersion = $patchVersion;
        }
        else if ($cvo -> nextVersionType == "minor") {
            $cvo -> nextVersion = $minorVersion;
        }
        else if ($cvo -> nextVersionType == "major") {
            $cvo -> nextVersion = $majorVersion;
        }

        echo "Q. アップグレードバージョンのディレクトリを別途作成しますか? [y/n] (y) :";
        $value = trim(fgets(STDIN));
        if (!$value || $value == "y" || $value == "Y") {
            $cvo -> directoryCopy = true;
        }
        else {
            $cvo -> directoryCopy = false;
        }

        echo "\n";
        echo "  container name              : " . $cvo->name . "\n";
        echo "  version upgrade type        : " . $cvo->nextVersionType . "\n";
        echo "  version                     : " . $cvo->nowVersion . " -> " . $cvo->nextVersion . "\n";
        echo "  create version directory    : " . $cvo->directoryCopy ."\n\n";
        echo "Q. 下記内容でアップグレードバージョンを作成します。よろしいですか？[y/n] (y) :";

        $value = trim(fgets(STDIN));
        if ($value == "" || $value == "y" || $value == "Y"){
            $value = true;
        }
        else {
            $value = false;
        }

        if ($value) {
            ContainerDevelopment::versionUp($cvo);
        }

    }

    public function switch() {
        echo "[Kiwi Container Switch]";
    }

    public function commit() {
        echo "[Kiwi ]";
    }

    public function inset() {
        echo "[Kiwi ]";
    }

    public function export() {
        echo "[Kiwi Export]";
    }

    public function import() {
        echo "[Kiwi Import]";
    }
}