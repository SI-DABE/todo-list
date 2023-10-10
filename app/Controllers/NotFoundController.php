<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class NotFoundController extends BaseController
{
    public function index()
    {
        $this->render('NotFound');
    }
}
