<?php

spl_autoload_register(function(string $nameSpace) {
    
    if (!defined("KIWI_ROOTDIR")) {
        define("KIWI_ROOTDIR", __DIR__);
    }

    if (!defined("KIWI_ROOT_CONTAINER")) {
        define("KIWI_ROOT_CONTAINER", KIWI_ROOTDIR. "/containers");
    }

    $spaces = explode("\\", $nameSpace);

    if ($spaces[0] == "kiwi") {
        $spaces2 = $spaces;
        array_shift($spaces2);

        if ($spaces[1] == "core") {
            array_shift($spaces2);
            $path = KIWI_ROOT_CONTAINER . "/.core/src/" . $spaces[2]. ".php";
        }
        else if (isset($spaces[2])) {
            $path = KIWI_ROOT_CONTAINER . "/" . implode("/" , $spaces2) . ".php";
        }
        else {
            $path = KIWI_ROOTDIR . "/" . implode("/" , $spaces2) . ".php";
        }

        if (file_exists($path)) {
            require $path;
        }
    }

}, true, false);