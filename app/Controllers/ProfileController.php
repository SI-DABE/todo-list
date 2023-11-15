<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{

    public function show()
    {
        $this->authenticated();

        $this->render('profile/show');
    }

    public function updateAvatar()
    {
        $image = $_FILES['user_avatar'];

        $this->currentUser()->avatar()->update($image);
        $this->redirectTo('/profile');
    }
}
