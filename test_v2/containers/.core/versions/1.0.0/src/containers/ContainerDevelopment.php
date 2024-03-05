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

namespace kiwi\core\containers;

use kiwi\core\Kiwi;
use kiwi\core\developments\PhpCodeMake;

class ContainerDevelopment {

    /**
     * Containerの新規作成
     */
    public static function create(ContainerCreateOption $cco) : bool {
       
        echo "\nCreate Start..\n";

        echo "mkdir /" . $cco->name . "\n";
        mkdir(KIWI_ROOT_CONTAINER . "/" . $cco->name);

        echo "mkdir /" . $cco->name . "/versions" . "\n";
        mkdir(KIWI_ROOT_CONTAINER . "/" . $cco->name . "/versions");

        echo "mkdir /" . $cco->name . "/writables" . "\n";
        mkdir(KIWI_ROOT_CONTAINER . "/" . $cco->name . "/writables");

        echo "mkdir /" . $cco->name . "/temps" . "\n";
        mkdir(KIWI_ROOT_CONTAINER . "/" . $cco->name . "/temps");

        echo "mkdir /" . $cco->name . "/versions/". $cco->version . "\n";
        mkdir(KIWI_ROOT_CONTAINER . "/" . $cco->name . "/versions/". $cco->version);

        // make container.json
        echo "make  /" . $cco->name . "/versions/". $cco->version . "/versions/" . $cco->version . "/container.json" . "\n";
        $ccoArray = (array)$cco;
        $ccoArray["comment"] = "new release.";
        file_put_contents(KIWI_ROOT_CONTAINER . "/". $cco->name . "/versions/" . $cco->version . "/container.json", json_encode($ccoArray, JSON_PRETTY_PRINT));

        // make history.json
        /*
        $release = [
            [
                "version" => $cco->version,
                "modifiedDate" => date("Y-m-d H:i:s"),
                "name" => $cco->name,
                "title" => $cco->title,
                "comment" => "new release.",
            ],
        ];
        echo "make  /" . $cco->name . "/versions/". $cco->version . "/versions/" . $cco->version . "/history.json" . "\n";
        file_put_contents(KIWI_ROOT_CONTAINER . "/". $cco->name . "/versions/" . $cco->version . "/history.json", json_encode($release, JSON_PRETTY_PRINT));
        */

        // load kiwi file
        $kiwi = kiwiLoad();

        // backup Kiwi file
        rename(KIWI_ROOTDIR . "/configs/Kiwi", KIWI_ROOTDIR . "/configs/Kiwi.backup");

        // update Kiwi
        $kiwi["routes"]["/" . $cco->name] = [
            "container" => $cco->name,
        ];
        $kiwi["versions"][$cco->name] = $cco->version;

        echo "update /configs/Kiwi\n";
        file_put_contents(KIWI_ROOTDIR . "/configs/Kiwi", PhpCodeMake::createReturnArray($kiwi));

        // sample file copy
        Kiwi::copy(
            dirname(__DIR__) . "/consoles/createSample" , 
            KIWI_ROOT_CONTAINER . "/" . $cco->name . "/versions/". $cco->version, 
            true, 
            function($path) use ($cco) {
                echo "copy " . $path . "\n";
                $content = file_get_contents($path);
                $content = str_replace("xxxxxxxx", $cco->name, $content);
                file_put_contents($path, $content);
            }
        );

        echo "\nCreate Complete!" . "\n";
        return true;
    }

    /**
     * Containerの次バージョン追加
     */
    public static function versionUp(ContainerVersionUpOption $containerVersionUpOption) : bool {

        echo "version up....";

        return true;
    }
}
