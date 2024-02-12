<?php
/**
 * kiwiFramework (version 1.0.0)
 * author   : Masato Nakatsuji
 * created  : 2024.02.12
 * GitHub   : https://www.github.com/masatonakatsuji2021/kiwi_framework.git
 */

// kiwi Core library autoload.
require "../../blocks/.core/autoload.php";

// Declare the root directory as a constant.
define("KIWI_ROOTUNDERCUT", 2);
define("KIWI_ROOTDIR", dirname(__DIR__, KIWI_ROOTUNDERCUT));

// Instantiate the Starter class of kiwi Core Library.
new kiwi\core\Startor();
