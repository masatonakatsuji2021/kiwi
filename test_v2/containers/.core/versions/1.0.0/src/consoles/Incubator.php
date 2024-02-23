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
use kiwi\core\containers\ContainerCreateOption;
use kiwi\core\containers\ContainerDevelopment;


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
}