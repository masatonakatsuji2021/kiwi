<?php

namespace kiwi\core;

class BlockConfig extends Config{

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

    public static function handleRoute() { }

    public static function handleRouteShell() { }

    public static function handleInstall() : void {}
    
    public static function handleUninstall() : void {}
    
    public static function handleImportBefore() : void {}
    
    public static function handleImportAfter() : void {}
    
    public static function handleExportBefore() : void {}
    
    public static function handleExportAfter() : void {}
    
    public static function handleDataDeleteBefore() : void {}
    
    public static function handleDataDeleteAfter() : void {}
}