<?php

namespace App\Controllers;

class BaseController
{
    protected $layout = 'application';

    public function render($view, $data = [])
    {
        extract($data);
        $view = ROOT_PATH . '/app/views/' . $view .  '.phtml';
        require ROOT_PATH . '/app/views/layouts/' . $this->layout .  '.phtml';
    }
}
