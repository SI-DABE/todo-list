<?php

use Core\Routes\Route;
use App\Controllers\NotFoundController;
$rootRoute = new Route;
$rootRoute->get('/tasks',     [App\Controllers\TasksController::class, 'index']);
$rootRoute->get('/tasks/:id', [App\Controllers\TasksController::class, 'show']);
$rootRoute->post('/tasks',    [App\Controllers\TasksController::class, 'create']);
$rootRoute->delete('/tasks',  [App\Controllers\TasksController::class, 'destroy']);
$rootRoute->putErrorHandler('not_found', [App\Controllers\NotFoundController::class,'index']);
