<?php

namespace App\Controllers;

use App\Models\Task;

class TasksController extends BaseController
{
    public function index()
    {
        $task = new Task();
        $tasks = Task::all();

        $this->render('tasks/index', ['task' => $task, 'tasks' => $tasks]);
    }
}
