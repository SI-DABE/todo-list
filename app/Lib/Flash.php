<?php

namespace App\Lib;

class Flash
{
    public static function message($type = null, $value = null)
    {
        if (isset($type) && isset($value)) {
            $_SESSION['flash'][$type] = $value;
            return true;
        }

        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $flash;
    }
}
