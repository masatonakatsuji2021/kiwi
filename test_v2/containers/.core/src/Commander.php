<?php

namespace kiwi\core;

use Exception;
use kiwi\Config;

class Commander {
    public function __construct() {

        try{
            if (!isset(Config::$useComander)) {
                throw new Exception("[ERROR] This command execution is not allowed.");
            }

            if (Config::$useComander == false){
                throw new Exception("[ERROR] This command execution is not allowed.");
            }

            echo "[Kiwi Commander]";

        } catch (Exception $e){
            echo $e->getMessage();
        }
    }

}