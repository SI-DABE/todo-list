<?php

namespace App\Lib;

class Validations
{
    public static function notEmpty($value, $key, &$errors)
    {
        if (empty($value)) {
            $errors[$key] = 'não pode ser vazio';
            return false;
        }

        return true;
    }

    public static function passwordConfirmation($password, $passwordConfirmation, $key, &$errors)
    {
        if ($password !== $passwordConfirmation) {
            $errors[$key] = 'as senhas devem ser idênticas';
            return false;
        }

        return true;
    }
}
