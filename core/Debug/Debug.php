<?php

namespace Core\Debug;

class Debug
{
    public static function dd($value)
    {
        var_dump($value);
        exit;
    }
}
