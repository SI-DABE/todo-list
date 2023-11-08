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
                       value="{$this->getValue($attribute,$type)}"
                       name="{$this->name($attribute)}"
                       class="{$this->classWhenError($attribute)}">
                <span class="invalid-feedback">{$this->object->errors($attribute)}</span>
            </div>
        HTML;
    }

    public function selectFor($attribute, $name, $prompt, $collection)
    {
        $options = "<option value=''>{$prompt}</option>";

        foreach ($collection as $obj) {
            $options .= "<option value='{$obj->getId()}'>{$obj->getName()}</option>";
        }

        return <<<HTML
            <div class="form-group">
                <label for="{$this->id($attribute)}">{$name}</label>
                <select 
                    name="{$this->name($attribute)}" id="{$this->id($attribute)}"
                    class="{$this->classWhenError($attribute)}"
                    >
                    {$options}
                </select>
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

    private function getValue($attribute, $type)
    {
        if ($type === 'password') {
            return '';
        }

        $method = 'get' . $attribute;
        return $this->object->$method();
    }

    private function classWhenError($attribute)
    {
        return $this->object->errors($attribute) ? 'is-invalid' : '';
    }

    private function name($attribute)
    {
        return strtolower("{$this->classNameInSnakeCase()}[{$attribute}]");
    }


    private function id($attribute)
    {
        return strtolower("{$this->classNameInSnakeCase()}_{$attribute}");
    }

    private function className()
    {
        return (new ReflectionClass($this->object))->getShortName();
    }

    private function classNameInSnakeCase()
    {
        return StringUtils::camelToSnakeCase($this->className());
    }
}
