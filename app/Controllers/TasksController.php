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

    public function show() {
        $task = Task::findById($this->params[':id']);

        $this->render('tasks/show', ['task' => $task]);
    }

    public function create() {
        $task = new Task(name: $this->params['task']['name']);
        $tasks = Task::all();
        
        if ($task->save()) {
          $this->redirectTo('/tasks');
        } else {
            $this->render('tasks/index', ['task' => $task, 'tasks' => $tasks]);
        }
    }

    public function destroy() {
        $id = $this->params['task']['id'];
        $task = Task::findById($id);
        $task->destroy();

        $this->redirectTo('/tasks');
    }
}
