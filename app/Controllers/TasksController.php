<?php

namespace App\Controllers;

use App\Lib\Flash;
use App\Models\Task;

class TasksController extends BaseController
{
    public function index()
    {
        $task = new Task();
        $tasks = Task::all();

        $taskAccessHistory = $_COOKIE['tasks'] ?? [];

        $this->render('tasks/index', compact('task', 'tasks', 'taskAccessHistory'));
    }

    public function show()
    {
        $task = Task::findById($this->params[':id']);

        setcookie("tasks[{$task->getId()}]", $task->getName(), strtotime('+1 days'), '/');

        $this->render('tasks/show', ['task' => $task]);
    }

    public function create()
    {
        $task = new Task(name: $this->params['task']['name']);
        $tasks = Task::all();

        if ($task->save()) {
            Flash::message('success', 'Tarefa registrada com sucesso!');
            $this->redirectTo('/tasks');
        } else {
            Flash::message('danger', 'Dados incorretos!');
            $this->render('tasks/index', [
                  'task' => $task,
                  'tasks' => $tasks
                ]);
        }
    }

    public function destroy()
    {
        $id = $this->params['task']['id'];
        $task = Task::findById($id);
        $task->destroy();

        Flash::message('success', 'Tarefa removida com sucesso!');

        $this->redirectTo('/tasks');
    }
}
