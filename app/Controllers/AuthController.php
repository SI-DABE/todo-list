<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Lib\Flash;
use App\Models\User;

class AuthController extends BaseController
{
    public function new()
    {
        $this->render('auth/login');
    }

    public function create()
    {
        $email    = $this->params['user']['email'];
        $password = $this->params['user']['password'];

        $user = User::findByEmail($email);

        if ($user && $user->authenticate($password)) {
            Flash::message('success', 'Login realizado com sucesso!');
            $_SESSION['user']['id'] = $user->getId();

            $this->redirectTo('/tasks');
        } else {
            Flash::message('danger', 'Email e/ou senha inválidos!');
            $this->redirectTo('/login');
        }
    }

    public function destroy()
    {
        unset($_SESSION['user']['id']);
        Flash::message('success', 'Logout realizado com sucesso!');
        $this->redirectTo('/login');
    }
}
