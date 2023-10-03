<?php

use Core\Routes\Route;

Route::get('/tasks',     [App\Controllers\TasksController::class, 'index']);
Route::get('/tasks/:id', [App\Controllers\TasksController::class, 'show']);
Route::post('/tasks',    [App\Controllers\TasksController::class, 'create']);
Route::delete('/tasks',  [App\Controllers\TasksController::class, 'destroy']);
