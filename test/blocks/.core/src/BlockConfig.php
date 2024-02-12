<?php

namespace kiwi\core;

class BlockConfig extends Config {

    /**
     * **Web Routing List**
     * This is a route search list for each URL on the web.
     */
    public static array $routes = [];

    public static function routeHandle() { }

    /**
     * **Console Routing List**  
     * This is the route search list for each path when running the console.
     */
    public static array $routeShells;

    public static function routeShellHandle() { }


    /**
     * **Resource data access settings**
     */
    public static array $resources;
}