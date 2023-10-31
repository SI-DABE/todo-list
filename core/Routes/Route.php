<?php

namespace Core\Routes;

use Core\Exceptions\ExceptionWithHTTPStatus;

class Route
{
    private static $routes = [];

    public static function get($path, $data)
    {
        self::$routes['GET'][$path]['class'] = $data[0];
        self::$routes['GET'][$path]['action'] = $data[1];
    }

    public static function delete($path, $data)
    {
        self::$routes['DELETE'][$path]['class'] = $data[0];
        self::$routes['DELETE'][$path]['action'] = $data[1];
    }

    public static function post($path, $data)
    {
        self::$routes['POST'][$path]['class'] = $data[0];
        self::$routes['POST'][$path]['action'] = $data[1];
    }

    public static function load()
    {
        $method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $path = strtok($_SERVER['REQUEST_URI'], '?');

        $routes = self::$routes[$method];
        $params = [];

        foreach ($routes as $route => $data) {
            if (self::isRightRoute($route, $path, $params)) {
                $class = self::$routes[$method][$route]['class'];
                $action = self::$routes[$method][$route]['action'];

                $controller = new $class();
                $controller->setParams($params + $_GET + $_POST);
                $controller->$action();

                return;
            }
        }

        throw new ExceptionWithHTTPStatus("Route not Found: {$path}", 404);
    }

    private static function isRightRoute($route, $path, &$params)
    {
        $splitedRoute = explode('/', $route);
        $splitedPath = explode('/', $path);

        if (sizeof($splitedRoute) !== sizeof($splitedPath)) {
            return false;
        }

        for ($i = 0; $i < sizeof($splitedRoute); $i++) {
            if (preg_match('/^:[a-z,_]+$/', $splitedRoute[$i])) {
                $params[$splitedRoute[$i]] = $splitedPath[$i];
            } elseif ($splitedRoute[$i] !== $splitedPath[$i]) {
                return false;
            }
        }

        return true;
    }
}
