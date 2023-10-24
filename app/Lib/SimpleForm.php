<?php

namespace App\Lib;

use Closure;
use ReflectionClass;

class SimpleForm
{

    private $object;

    public function for($object, $url, $method, Closure $callBack)
    {
        $this->object = $object;

        echo "<form action='{$url}' method='{$method}'>";
        $callBack($this);
        echo '</form>';
    }

    public function inputFor($attribute, $name, $type = 'text')
    {
        return <<<HTML
            <div class="form-group">
                <label for="{$this->id($attribute)}">{$name}</label>
                <input id="{$this->id($attribute)}" 
                       type="{$type}"
                       name="{$this->name($attribute)}"
                       class="{$this->classWhenError($attribute)}">
                <span class="invalid-feedback">{$this->object->errors($attribute)}</span>
            </div>
        HTML;
    }

    public function submit($value)
    {
        return <<<HTML
            <input type="submit" value="{$value}">
        HTML;
    }

    private function classWhenError($attribute)
    {
        return $this->object->errors($attribute) ? 'is-invalid' : '';
    }

    private function name($attribute)
    {
        return strtolower("{$this->className()}[{$attribute}]");
    }


    private function id($attribute)
    {
        return strtolower("{$this->className()}_{$attribute}");
    }

    private function className()
    {
        return (new ReflectionClass($this->object))->getShortName();
    }
}
