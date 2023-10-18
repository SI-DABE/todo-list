<?php

namespace App\Controllers;

use App\Lib\Flash;
use App\Models\Task;

class TasksController extends BaseController
{
    public function index()
    {
        $this->authenticated();

        $task = new Task();
        $tasks = Task::all();

        $this->render('tasks/index', compact('task', 'tasks'));
    }

    public function show()
    {
        $task = Task::findById($this->params[':id']);

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
