<?php

namespace App\Models;

use App\Lib\Paginator;
use App\Lib\Validations;
use App\Models\Base;
use App\Services\ProfileAvatar;
use App\Services\UserTasksService;

class User extends Base
{
    protected static string $table =      'users';
    protected static array  $attributes = ['name', 'email', 'password', 'avatar_name'];

    public function __construct(
        $id = -1,
        protected string $name = '',
        protected string $email = '',
        protected string $password = '',
        protected string | null $avatar_name = null,
        protected string $password_confirmation = ''
    ) {
        parent::__construct($id);
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = trim($password);
        $this->password_confirmation = $password_confirmation;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAvatarName()
    {
        return $this->avatar_name;
    }

    public function validates()
    {
        Validations::notEmpty($this->name, 'name', $this->errors);
        Validations::notEmpty($this->email, 'email', $this->errors);
        Validations::notEmpty($this->password, 'password', $this->errors);

        if (Validations::passwordConfirmation(
            $this->password,
            $this->password_confirmation,
            'password',
            $this->errors
        )) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
    }

    public function authenticate(string $password)
    {
        return password_verify($password, $this->password);
    }

    public function tasks()
    {
        return new UserTasksService($this);
    }

    public function avatar()
    {
        return new ProfileAvatar($this);
    }

    public static function findByEmail(string $email): User | null
    {
        return User::findBy(['email' => $email]);
    }
}
