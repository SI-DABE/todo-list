<?php

use Core\Routes\Route;
use App\Controllers\NotFoundController;
Route::get('/tasks',     [App\Controllers\TasksController::class, 'index']);
Route::get('/tasks/:id', [App\Controllers\TasksController::class, 'show']);
Route::post('/tasks',    [App\Controllers\TasksController::class, 'create']);
Route::delete('/tasks',  [App\Controllers\TasksController::class, 'destroy']);
Route::putErrorHandler('not_found',[App\Controllers\NotFoundController::class,'index']);
