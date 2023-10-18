<?php

namespace App\Controllers;

use App\Lib\Flash;
use App\Models\User;

class BaseController
{
    protected $layout = 'application';
    protected $params = [];
    private $currentUser = null;

    public function render($view, $data = [])
    {
        extract($data);
        $view = ROOT_PATH . '/app/views/' . $view .  '.phtml';
        require ROOT_PATH . '/app/views/layouts/' . $this->layout .  '.phtml';
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    protected function redirectTo(string $address)
    {
        header('location: ' . $address);
        exit();
    }

    public function currentUser()
    {
        if ($this->currentUser === null && isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            $this->currentUser = User::findById($id);
        }

        return $this->currentUser;
    }

    public function authenticated()
    {
        if ($this->currentUser() === null) {
            Flash::message('danger', 'Você deve estar logado para acessar essa página');
            $this->redirectTo('/login');
        }
    }
}
