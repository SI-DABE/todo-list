<?php

use Core\Routes\Route;

Route::get('/tasks', [App\Controllers\TasksController::class, 'index']);
