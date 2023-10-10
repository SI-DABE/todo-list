<?php

namespace App\Models;

use App\Models\Base;
use App\Lib\Validations;
use Core\Db\Database;
use PDO;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function validates()
    {
        Validations::notEmpty($this->name, 'name', $this->errors);
    }

    public function save()
    {
        if ($this->isValid()) {
            $pdo = Database::getConnection();

            $sql = "INSERT INTO tasks (name) VALUES (:name);";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $this->name);

            $stmt->execute();

            $this->id = $pdo->lastInsertId();

            return true;
        }

        return false;
    }

    public function destroy()
    {
        $pdo = Database::getConnection();

        $sql = 'DELETE FROM tasks WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        return ($stmt->rowCount() != 0);
    }

    public static function findById(int $id): Task | null
    {
        $pdo = Database::getConnection();

        $sql = 'SELECT id, name FROM tasks WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new Task(id: $row['id'], name: $row['name']);
    }

    public static function all()
    {
        $tasks = [];

        $pdo = Database::getConnection();
        $resp = $pdo->query('SELECT id, name FROM tasks');

        foreach ($resp as $row) {
            $tasks[] = new Task(id: $row['id'], name: $row['name']);
        }

        return $tasks;
    }
}
