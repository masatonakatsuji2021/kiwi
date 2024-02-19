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

class ContainerConfig extends Config{

    /**
     * **Web Routing List**
     * This is a route search list for each URL on the web.
     */
    public static array $routes = [];

    /**
     * **Console Routing List**  
     * This is the route search list for each path when running the console.
     */
    public static array $routeShells;


    /**
     * **Resource data access settings**
     */
    public static array $resources;

    /**
     * 経路探索時ハンドリング
     */
    public static function handleRoute(array $routes) : ?array {
        return null;
    }

    /**
     * コンソール経路探索時ハンドリング
     */
    public static function handleRouteShell(array $routeShells) : ?array {
        return null;
    }

    /**
     * インストール時実行用ハンドリング
     */
    public static function handleInstall() : void{}

    /**
     * アンインストール時実行用ハンドリング
     */
    public static function handleUninstall() : void{}

    /**
     * インポート直前ハンドリング
     */
    public static function handleImportBefore() : void{}

    /**
     * インポート直後ハンドリング
     */
    public static function handleImportAfter() : void{}

    /**
     * エクスポート直前ハンドリング
     */
    public static function handleExportBefore() : void{}

    /**
     * エクスポート直後ハンドリング
     */
    public static function handleExportAfter() : void{}

    /**
     * データ削除直前ハンドリング
     */
    public static function handleDataDeleteBefore() : void{}

    /**
     * データ削除直後ハンドリング
     */
    public static function handleDataDeleteAfter() : void{}
}