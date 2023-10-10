<?php

use Core\Http\RequestFactory;
use Core\Routes\Route;

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/vendor/autoload.php';

require_once ROOT_PATH . '/config/routes.php';
$request = RequestFactory::createRequest();
$rootRoute->action($request);
