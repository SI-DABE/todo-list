<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Lib\Flash;
use App\Models\User;

class TasksCollaboratorsController extends BaseController
{
    public function add()
    {
        $this->authenticated();

        $task_id = $this->params[':task_id'];
        $user_id = $this->params['task_user']['user_id'];

        $task = $this->currentUser()->tasks()->findById($task_id);
        $task_user = $task->collaborators()->new(
            user_id: $user_id
        );

        if ($task_user->save()) {
            Flash::message('success', 'Colaborador adicionado com sucesso');
            $this->redirectTo("/tasks/{$task_id}");
        } else {
            $users = User::all();
            $this->render('tasks/show', compact('task', 'task_user', 'users'));
        }
    }

    public function destroy()
    {
        $this->authenticated();

        $task_id = $this->params[':task_id'];
        $user_id = $this->params[':id'];

        $task = $this->currentUser()->tasks()->findById($task_id);
        $user_task = $task->collaborators()->findByUserId($user_id);

        $user_task->destroy();

        Flash::message('success', 'Colaborador removido com sucesso!');

        $this->redirectTo("/tasks/{$task_id}");
    }
}
