<?php

namespace kiwi\core;

class RouteSet {

    public static function add(string $method, string $controller, string $action = null) : string {
        if ($method) {
            $str = "method:" .$method . ", controller:". $controller;
        }
        else {
            $str = "controller:". $controller. ", action:" . $action;
        }

        if ($action) {
            $str .= ", action:" . $action;
        }

        return $str;
    }

    public static function get(string $controller, string $action = null) : string{
        return self::add("get", $controller, $action);
    }

    public static function post(string $controller, string $action = null) : string{
        return self::add("post", $controller, $action);
    }

    public static function put(string $controller, string $action = null) : string{
        return self::add("put", $controller, $action);
    }

    public static function delete(string $controller, string $action = null) : string{
        return self::add("delete", $controller, $action);
    }
}