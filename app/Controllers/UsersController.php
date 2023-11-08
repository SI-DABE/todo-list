<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Lib\Flash;
use App\Models\User;

class UsersController extends BaseController
{
    public function new()
    {
        $user = new User();
        $this->render('users/new', compact('user'));
    }

    public function create()
    {
        $user = new User(
            name: $this->params['user']['name'],
            email: $this->params['user']['email'],
            password: $this->params['user']['password'],
            password_confirmation: $this->params['user']['password_confirmation']
        );

        if ($user->save()) {
            Flash::message('success', 'Usuário registrado com sucesso!');
            $this->redirectTo('/tasks');
        } else {
            Flash::message('danger', 'Dados incorretos!');
            $this->render('users/new', compact('user'));
        }
    }
}
