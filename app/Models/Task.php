<?php

namespace App\Models;

use App\Models\Base;
use App\Lib\Validations;

class Task extends Base {
    private const DB_PATH = '../database/tasks.txt';
    
    private string $name;

    public function __construct(string $name = '') {
        $this->name = trim($name);
    }

    public function getName(): string {
        return $this->name;
    }

    public function validates() {
        Validations::notEmpty($this->name, 'name', $this->errors);

        // if (strlen($this->name) < 3) {
        //     $this->errors['name'] = 'deve ter mais de três caracteres';
        // }
        // if (empty($this->name)) {
        //     $this->errors['name'] = 'não pode ser vazio';
        // }
    }

    public function save() {
        if ($this->isValid()) {
            file_put_contents(self::DB_PATH,
                              $this->name . PHP_EOL,
                              FILE_APPEND | LOCK_EX);
            return true;
        }

        return false;
    }

    public static function all() {
        $tasks = [];
        
        $tasksFromFile = file(self::DB_PATH, FILE_IGNORE_NEW_LINES);
        foreach($tasksFromFile as $taskName) {
            $tasks[] = new Task($taskName);
        }

        return $tasks;
    }
}