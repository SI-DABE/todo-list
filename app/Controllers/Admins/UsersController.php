<?php

namespace App\Controllers\Admins;

use App\Controllers\BaseController;
use App\Lib\Paginator;
use App\Models\Task;
use App\Models\User;

class UsersController extends BaseController
{
    public function index()
    {
        $page = isset($this->params[':page']) ? $this->params[':page'] : 1;
        $paginator = User::paginator(page: $page, per_page: 5);
        $this->render('admins/users/index', compact('paginator'));
    }
}
