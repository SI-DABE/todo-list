<?php

namespace App\Lib;

class Validations {

    public static function notEmpty($value, $key, &$errors) {
        if (empty($value)) {
            $errors[$key] = 'não pode ser vazio';
            return false;
        }

        return true;
    }
}
