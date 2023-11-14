<?php

namespace App\Models;

use App\Lib\Paginator;
use App\Models\Base;
use App\Lib\Validations;
use App\Services\TaskUsersService;

class Task extends Base
{
    protected static string $table =      'tasks';
    protected static array  $attributes = ['name', 'user_id'];

    public function __construct(
        protected $id = -1,
        protected $name = '',
        protected $user_id = -1
    ) {
        parent::__construct($id);
        $this->name = trim($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function validates()
    {
        Validations::notEmpty($this->name, 'name', $this->errors);
    }

    public function collaborators()
    {
        return new TaskUsersService($this);
    }
}
