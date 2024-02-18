<?php

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
     * <= 経路探索時ハンドリング
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