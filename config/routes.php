<?php

use Core\Routes\Route;

Route::get('/',          [App\Controllers\HomeController::class, 'index']);

Route::get('/sign-up',   [App\Controllers\UsersController::class, 'new']);
Route::post('/sign-up',  [App\Controllers\UsersController::class, 'create']);

Route::get('/login',     [App\Controllers\AuthController::class, 'new']);
Route::post('/login',    [App\Controllers\AuthController::class, 'create']);
Route::get('/logout',    [App\Controllers\AuthController::class, 'destroy']);

Route::get('/tasks',     [App\Controllers\TasksController::class, 'index']);
Route::get('/tasks/:id', [App\Controllers\TasksController::class, 'show']);
Route::post('/tasks',    [App\Controllers\TasksController::class, 'create']);
Route::delete('/tasks',  [App\Controllers\TasksController::class, 'destroy']);

Route::post('/tasks/:task_id/collaborators',        [App\Controllers\TasksCollaboratorsController::class, 'add']);
Route::delete('/tasks/:task_id/collaborators/:id',  [App\Controllers\TasksCollaboratorsController::class, 'destroy']);

Route::get('/admins/users',              [App\Controllers\Admins\UsersController::class, 'index']);
Route::get('/admins/users/page/:page',   [App\Controllers\Admins\UsersController::class, 'index']);
