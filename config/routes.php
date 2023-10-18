<?php

use Core\Routes\Route;

Route::get('/login',     [App\Controllers\AuthController::class, 'new']);
Route::post('/login',    [App\Controllers\AuthController::class, 'create']);
Route::get('/logout',    [App\Controllers\AuthController::class, 'destroy']);

Route::get('/tasks',     [App\Controllers\TasksController::class, 'index']);
Route::get('/tasks/:id', [App\Controllers\TasksController::class, 'show']);
Route::post('/tasks',    [App\Controllers\TasksController::class, 'create']);
Route::delete('/tasks',  [App\Controllers\TasksController::class, 'destroy']);
