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
use kiwi\Config;

class Incubator {

    public function __construct() {

        try{
            if (!isset(Config::$useIncubator)) {
                throw new Exception("[ERROR] This command execution is not allowed.");
            }

            if (Config::$useIncubator == false){
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
        echo "[Kiwi Incubator Create]\n\n";

        echo "- Container name :";

        $containerName = fgets(STDIN);

        echo $containerName;

    }
}