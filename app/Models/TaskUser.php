<?php

namespace App\Models;

use App\Lib\Validations;
use App\Models\Base;

class TaskUser extends Base
{
    protected static string $table =      'tasks_users';
    protected static array  $attributes = ['task_id', 'user_id'];

    public function __construct(
        protected $id = -1,
        protected $task_id = -1,
        protected $user_id = -1
    ) {
        parent::__construct($id);
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getTaskId()
    {
        return $this->task_id;
    }

    public function validates()
    {
        Validations::notEmpty($this->user_id, 'user_id', $this->errors);
        Validations::uniqueness(['user_id', 'task_id'], $this);
    }
}
