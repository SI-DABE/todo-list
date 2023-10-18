<?php

namespace App\Models;

abstract class Base
{
    private int $id;
    protected array $errors = [];

    public function __construct(int $id = -1)
    {
        $this->id = $id;
    }

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

    public function validates()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
}
