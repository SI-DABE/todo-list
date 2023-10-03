<?php

namespace Core\Routes;

use Core\Request;
use Core\RequestFactory;

use App\Controllers\NotFoundConreoller;
class Route
{
    private static $routes = [];
    private static $errorControllers = [];
    private $subRoutes = [];
    private $controllers = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];
    private $dinamic;
    private $labels = [];
    private static Route $instance;
    private function __construct()
    {
        
    }
    public static function explodeURI($path){
        $splitedPath = explode('/',$path);
        array_shift($splitedPath);
        if(str_ends_with($path, '/'))
            array_pop($splitedPath);
        return $splitedPath;
    }
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new Route();
        return self::$instance;
    }
    public static function putErrorHandler($code, $data){
        if(!isset(self::$errorControllers[$code]))
            self::$errorControllers[$code] = [];
        self::$errorControllers[$code]['class'] = $data[0];
        self::$errorControllers[$code]['action'] = $data[1];
    }
    public static function get($path, $data)
    {
        self::getInstance()->addDinamicRoute($path, $data);
    }

    public static function delete($path, $data)
    {
        self::getInstance()->addDinamicRoute($path, $data, 'DELETE');
    }

    public static function post($path, $data)
    {
        self::getInstance()->addDinamicRoute($path, $data, 'POST');
    }
    public static function addCounstanteRoute($path, $data, $method = 'GET'){
        self::$routes[$method][$path]['class'] = $data[0];
        self::$routes[$method][$path]['action'] = $data[1];
    }
    public function addDinamicRoute($path, $data, $method = 'GET'){
        $splitedPath = $path === '/' ? [] : self::explodeURI($path);
        $this->addDinamicRouteNode($splitedPath, $data, $method);
    }
    public function addDinamicRouteNode($splitedPath, $data, $method = 'GET'){
        if(count($splitedPath) === 0){
            $this->controllers[$method]['class'] = $data[0];
            $this->controllers[$method]['action'] = $data[1];
            return;
        }
        if (preg_match('/^:[a-z,_]+$/', $splitedPath[0])){
            if(!isset($this->dinamic))
                $this->dinamic = new Route();
            $this->labels[] = $splitedPath[0];
            array_shift($splitedPath);
            $this->dinamic->addDinamicRouteNode($splitedPath, $data, $method);
            return;
        } 
        $subRoute = $splitedPath[0];
        if(!isset($this->subRoutes[$subRoute]))
           $this->subRoutes[$subRoute] = new Route();
        array_shift($splitedPath);
        $this->subRoutes[$subRoute]->addDinamicRouteNode($splitedPath, $data, $method);
    }
    public static function load()
    {
        $request = RequestFactory::createRequest();
        self::getInstance()->action($request);
    }
    public function action(Request $request, $path = NULL){    
        if($path === NULL)
            $path = self::explodeURI($request->getPath());
        if(count($path) > 0){
            $subRoute = $path[0];
            array_shift($path);
            if(isset($this->subRoutes[$subRoute]))
                $this->subRoutes[$subRoute]->action($request, $path);
            elseif(isset($this->dinamic))
            {
                foreach($this->labels as $label)
                    $request->putParam($label, $subRoute);
                $this->dinamic->action($request, $path);
            }else
                $this->runController(self::$errorControllers, 'not_found');
        }else
        $this->runController(
            $this->controllers,
            $request->getMethod(),
            $request->getParams()
        );
    }
    public function runController($from, $method, $params = NULL){
        $class  = $from[$method]['class'];
        $action = $from[$method]['action'];
        $controller = new $class();
        $controller->setParams($params);
        $controller->$action();
    }
}
