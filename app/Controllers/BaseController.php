<?php

namespace App\Controllers;

class BaseController
{
    protected $layout = 'application';
    protected $params = [];

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
}
