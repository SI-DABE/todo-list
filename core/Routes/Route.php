<?php

namespace Core\Routes;

class Route
{
    private static $routes = [];

    public static function get($path, $data)
    {
        self::$routes['GET'][$path]['class'] = $data[0];
        self::$routes['GET'][$path]['action'] = $data[1];
    }

    public static function load()
    {
        $method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        $class = self::$routes[$method][$path]['class'];
        $action = self::$routes[$method][$path]['action'];

        $controller = new $class();
        $controller->$action();
    }
}
