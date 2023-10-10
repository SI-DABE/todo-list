<?php

namespace App\Models;

use App\Models\Base;
use App\Lib\Validations;

class Task extends Base
{
    private const DB_PATH = '../database/tasks.txt';

    private string $name;
    private int $id;

    public function __construct(string $name = '', int $id = -1)
    {
        $this->name = trim($name);
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int {
        return $this->id;
    }

    public function validates()
    {
        Validations::notEmpty($this->name, 'name', $this->errors);

        // if (strlen($this->name) < 3) {
        //     $this->errors['name'] = 'deve ter mais de três caracteres';
        // }
        // if (empty($this->name)) {
        //     $this->errors['name'] = 'não pode ser vazio';
        // }
    }

    public function save()
    {
        if ($this->isValid()) {
            file_put_contents(
                self::DB_PATH,
                $this->name . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
            return true;
        }

        return false;
    }

    public function destroy() {
        $lines = file(self::DB_PATH, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $index => $line) {
            if ($index === $this->id) unset($lines[$index]);
        }

        $data = implode(PHP_EOL, $lines);
        file_put_contents(self::DB_PATH, $data . PHP_EOL);
    }

    public static function findById(int $id): Task | null {
        $tasksFromFile = file(self::DB_PATH, FILE_IGNORE_NEW_LINES);

        foreach ($tasksFromFile as $index => $taskName) {
            if ($id == $index)
                return new Task(name: $taskName, id: $index);
        }

        return null;
    }

    public static function all()
    {
        $tasks = [];

        $tasksFromFile = file(self::DB_PATH, FILE_IGNORE_NEW_LINES);
        foreach ($tasksFromFile as $index => $taskName) {
            $tasks[] = new Task(name: $taskName, id: $index);
        }

        return $tasks;
    }
}
