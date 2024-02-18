<?php

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