<?php

spl_autoload_register(function(string $nameSpace) {
    
    if (!defined("KIWI_ROOTDIR")) {
        define("KIWI_ROOTDIR", __DIR__);
    }

    $spaces = explode("\\", $nameSpace);

    if ($spaces[0] == "kiwi") {
        $spaces2 = $spaces;
        array_shift($spaces2);

        if ($spaces[1] == "core") {
            array_shift($spaces2);
            $path = KIWI_ROOTDIR . "/blocks/.core/src/" . $spaces[2]. ".php";
        }
        else if ($spaces[1] == "main") {
            array_shift($spaces2);
            $path = KIWI_ROOTDIR . "/blocks/.main/" . implode("/" , $spaces2) . ".php";
        }
        else if (!isset($spaces[2])) {
            $path = KIWI_ROOTDIR . "/" . implode("/" , $spaces2) . ".php";
        }
        else {
            $path = KIWI_ROOTDIR . "/blocks/" . implode("/" , $spaces2) . ".php";
        }

        if (file_exists($path)) {
            require $path;
//            throw new Exception("[initial Error] Could not find kiwiCore class file.");
        }
    }

}, true, false);