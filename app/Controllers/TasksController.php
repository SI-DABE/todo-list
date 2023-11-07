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
        $tasks = $this->currentUser()->tasks();

        $this->render('tasks/index', compact('task', 'tasks'));
    }

    public function show()
    {
        $this->authenticated();

        $task = Task::findBy(
            [
                'id' => $this->params[':id'],
                'user_id' => $this->currentUser()->getId()
            ]
        );
        $this->render('tasks/show', ['task' => $task]);
    }

    public function create()
    {
        $this->authenticated();

        $task = new Task(
            name: $this->params['task']['name'],
            user_id: $this->currentUser()->getId()
        );

        $tasks = $this->currentUser()->tasks();

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
        $this->authenticated();

        $task = Task::findBy(
            [
                'id' => $this->params['task']['id'],
                'user_id' => $this->currentUser()->getId()
            ]
        );

        $task->destroy();

        Flash::message('success', 'Tarefa removida com sucesso!');

        $this->redirectTo('/tasks');
    }
}
