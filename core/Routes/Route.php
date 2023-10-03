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
        $splitedPath = explode('/', $path);

        $routes = self::$routes[$method];
        $params = [];
        $rightRoute = null;

        foreach($routes as $route => $data) {
            $splitedRoute = explode('/', $route);

            if (sizeof($splitedRoute) !== sizeof($splitedPath))
                continue;

            for ($i = 0; $i < sizeof($splitedRoute); $i++) { 
                if (preg_match('/^:[a-z,_]+$/', $splitedRoute[$i])){
                   $params[$splitedRoute[$i]] = $splitedPath[$i];
                } elseif ($splitedRoute[$i] !== $splitedPath[$i]) {
                    break;
                }
            }

            $rightRoute = $route;
        }

        $class = self::$routes[$method][$rightRoute]['class'];
        $action = self::$routes[$method][$rightRoute]['action'];

        $controller = new $class();
        $controller->setParams($params + $_GET + $_POST);
        $controller->$action();
    }
}
