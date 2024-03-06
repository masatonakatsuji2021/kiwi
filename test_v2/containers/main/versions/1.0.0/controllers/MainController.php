<?php

namespace kiwi\main\controllers;

use kiwi\core\Controller;
use kiwi\core\Render;
use kiwi\core\Resource;
use kiwi\core\Temporary;
use kiwi\core\Writable;
use kiwi\core\FookControl;

class MainController extends Controller {

    public bool $autoRender = true;
    public string $viewTemplate = "def";

//    public function handleBefore () : void {}

    public function index() {
        Render::set("title","Main Page Title");
    }

    public function page1() {
        $this->autoRender = false;

        echo "Page1 ok....";
    }

    public function page2() {
        $this->autoRender = false;

        /*
        echo Resource::exists("/common/image1.png");
        echo "<br>";
        echo Resource::isDirectory("/common/abc");
        echo "<br>";
        echo Resource::isDirectory("/common/image1.png2");
        echo "<br>";
        echo Resource::isFile("/common/image1.png");
        echo "<br>";
        */
//        print_r(Resource::lists("/common"));
/*
        $get = Resource::get("/common/image1.png");
        header("Content-Type: ". $get->mimeType);
        echo $get->raw();
        */

        // Writable::mkdir("/public/writable_testmkdir");
        // echo writable::remove("/public/deltestDir");
       //  echo Writable::rename("/public/before_name.txt", "/public/after_name.txt");
       // echo Writable::save("/public/save.txt", "save text...");
       // echo Temporary::mkdir("/testdir..");
/*
       $fook = FookControl::excute("Test", "run");

        print_r($fook);
*/
    }

//    public function handleAfter() : void {}

    // public function handleDrawn() : void {}
}
