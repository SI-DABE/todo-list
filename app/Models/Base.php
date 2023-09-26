<?php

namespace App\Models;

abstract class Base
{
    protected array $errors = [];

    public function isValid()
    {
        $this->errors = [];
        $this->validates();

        return empty($this->errors);
    }

    public function errors($index = null)
    {
        if (isset($this->errors[$index])) {
            return $this->errors[$index];
        }

        return null;
    }

    abstract public function validates();
}
