<?php

namespace App\Models;

use App\Models\Base;
use App\Lib\Validations;
use Core\Db\Database;
use PDO;

class Task extends Base
{
    private string $name;
    private int $userId;

    public function __construct(
        string $name = '',
        int $id = -1,
        int $userId = -1
    ) {
        parent::__construct($id);
        $this->name = trim($name);
        $this->userId = $userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function validates()
    {
        Validations::notEmpty($this->name, 'name', $this->errors);
    }

    public function save()
    {
        if ($this->isValid()) {
            $pdo = Database::getDBConnection();

            $sql = "INSERT INTO tasks (name, user_id) VALUES (:name, :user_id);";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $this->name);
            $stmt->bindValue(':user_id', $this->userId);

            $stmt->execute();

            $this->setId($pdo->lastInsertId());

            return true;
        }

        return false;
    }

    public function destroy()
    {
        $pdo = Database::getDBConnection();

        $sql = 'DELETE FROM tasks WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $this->getId());

        $stmt->execute();

        return ($stmt->rowCount() != 0);
    }

    public static function where($conditions)
    {
        $sql = 'SELECT id, name, user_id FROM tasks WHERE ';
        $sqlConditions = array_map(function ($column) {
            return "{$column} = :{$column}";
        }, array_keys($conditions));

        $sql .= implode(' AND ', $sqlConditions);

        $pdo = Database::getDBConnection();
        $stmt = $pdo->prepare($sql);

        foreach ($conditions as $column => $value) {
            $stmt->bindValue($column, $value);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task(
                id: $row['id'],
                name: $row['name'],
                userId: $row['user_id']
            );
        }
        return $tasks;
    }


    public static function findBy($conditions)
    {
        return self::where($conditions)[0];
    }

    public static function findById(int $id): Task | null
    {
        $pdo = Database::getDBConnection();

        $sql = 'SELECT id, name, user_id FROM tasks WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new Task(
            id: $row['id'],
            name: $row['name'],
            userId: $row['user_id']
        );
    }

    public static function all()
    {
        $tasks = [];

        $pdo = Database::getDBConnection();
        $resp = $pdo->query('SELECT id, name, user_id FROM tasks');

        foreach ($resp as $row) {
            $tasks[] = new Task(
                id: $row['id'],
                name: $row['name'],
                userId: $row['user_id']
            );
        }

        return $tasks;
    }
}
