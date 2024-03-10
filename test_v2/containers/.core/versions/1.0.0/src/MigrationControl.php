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

/**
 * ***MigrationControl***  
 * Static class object for sequential execution of migration derived classes.
 */
class MigrationControl {

    /**
     * ***install***  
     * Execute migration when installing the specified container.  
     * The upgrade method of the Migration derived class for each version required for installation is executed sequentially.
     * @param string $container Container Name 
     * @return void
     */
    public static function install(string $container = null) : void {
        $startVersion = "0.0.0";
        if (!$container) {
            $endVersion = Routes::$route -> containerVersion;        
        }
        else {
            $endVersion = Container::getKiwi($container)["version"];      
        }
        self::migration($startVersion, $endVersion, $container);
    }

    /**
     * ***uninstall***  
     * Execute migration when uninstalling the specified container.  
     * The downgrade method of the Migration derived class for each version required for uninstallation is executed sequentially.
     * @param string $container Container Name 
     * @return void
     */
    public static function uninstall(string $container = null) : void {
        if (!$container) {
            $startVersion = Routes::$route -> containerVersion;        
        }
        else {
            $startVersion = Container::getKiwi($container)["version"];      
        }
        $endVersion = "0.0.0";
        self::migration($endVersion, $startVersion, $container, true);

    }

    /**
     * ***upgrade***  
     * Execute upgrade migration between specified versions  
     * @param string $startVersion Starting version number before upgrade
     * @param string $endVersion Upgrade target version number
     * @param string $container Container name
     * @return void
     */
    public static function upgrade(string $startVersion, string $endVersion, string $container = null) : void{
        self::migration($startVersion, $endVersion, $container);
    }

    /**
     * ***downgrade***  
     * Execute downgrade migration between specified versions
     * @param string $startVersion Starting version number before downgrade
     * @param string $endVersion downgrade target version number
     * @param string $container Container name
     * @return void
     */
    public static function downgrade(string $startVersion, string $endVersion, string $container = null) : void{
        self::migration($endVersion, $startVersion, $container, true);
    }

    private static function migration(string $startVersion, string $endVersion, string $container = null, bool $downgraded = false) : void {
        $svInt = Kiwi::versionOnInteger($startVersion);
        $evInt = Kiwi::versionOnInteger($endVersion);    

        if (!$container) {
            $container = Routes::$route -> container;
            $containerVersion = Routes::$route -> containerVersion;
        }
        else {
            $containerVersion = kiwiLoad()["versions"][$container];
        }

        $getList = glob(KIWI_ROOTDIR . "/containers/" . $container . "/versions/" . $containerVersion . "/migrations/*");

        $sorted = [];
        foreach ($getList as $g_) {
            $version = str_replace("Migration", "", basename($g_));
            $version = str_replace("_", ".", $version);
            $version = str_replace(".php", "", $version);
            $versionInt = Kiwi::versionOnInteger($version);
            if (!$versionInt) {
                continue;
            }
            if ($versionInt >= $svInt && $versionInt <= $evInt) {
                $sorted[] = $versionInt;
            }
        }

        sort($sorted);

        if ($downgraded) {
            rsort($sorted);
        }

        foreach($sorted as $s_) {
            $versionStr = Kiwi::versionOnString($s_);
            $versionStr = str_replace(".", "_" , $versionStr);
            $miClassname = "kiwi\\". $container . "\\migrations\Migration" . $versionStr;

            $mi = new $miClassname();

            if ($downgraded) {
                $mi->downgrade();
            }
            else {
                $mi->upgrade();
            }
        }
    }
}