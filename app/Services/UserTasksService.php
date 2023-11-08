<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

class UserTasksService
{

    public function __construct(
        private User $user
    ) {
    }

    public function all()
    {
        return Task::where(['user_id' => $this->user->getId()]);
    }

    public function findById($id): Task | null
    {
        return Task::findBy(
            [
                'id' => $id,
                'user_id' => $this->user->getId()
            ]
        );
    }

    public function new($name): Task
    {
        return new Task(
            name: $name,
            user_id: $this->user->getId()
        );
    }
}
