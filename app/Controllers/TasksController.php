<?php

namespace App\Controllers;

use App\Lib\Flash;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Core\Debug\Debug;

class TasksController extends BaseController
{
    public function index()
    {
        $this->authenticated();

        $task = new Task();
        $tasks = $this->currentUser()->tasks()->all();

        $this->render('tasks/index', compact('task', 'tasks'));
    }

    public function show()
    {
        $this->authenticated();

        $task_user = new TaskUser();
        $users = User::all();

        $task = $this->currentUser()->tasks()->findById($this->params[':id']);

        $this->render('tasks/show', compact('task', 'task_user', 'users'));
    }

    public function create()
    {
        $this->authenticated();

        $task = $this->currentUser()->tasks()->new(
            name: $this->params['task']['name']
        );

        if ($task->save()) {
            Flash::message('success', 'Tarefa registrada com sucesso!');
            $this->redirectTo('/tasks');
        } else {
            Flash::message('danger', 'Dados incorretos!');

            $tasks = $this->currentUser()->tasks()->all();
            $this->render('tasks/index', [
                'task' => $task,
                'tasks' => $tasks
            ]);
        }
    }

    public function destroy()
    {
        $this->authenticated();

        $task_id = $this->params['task']['id'];
        $task = $this->currentUser()->tasks()->findById($task_id);

        $task->destroy();

        Flash::message('success', 'Tarefa removida com sucesso!');

        $this->redirectTo('/tasks');
    }
}
