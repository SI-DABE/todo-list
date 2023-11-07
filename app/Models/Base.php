<?php

namespace App\Models;

use Core\Db\Database;
use PDO;

abstract class Base
{
    private int $id;
    protected array $errors = [];

    protected static array  $attributes = [];
    protected static string $table = '';

    public function __construct($id = -1)
    {
        $this->id = intval($id);
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

    public function newRecord()
    {
        return ($this->id === -1);
    }

    public function save()
    {
        if ($this->isValid()) {
            $pdo = Database::getDBConnection();

            $table = static::$table;
            $attributes = implode(', ', static::$attributes);
            $values = ':' . implode(', :', static::$attributes);

            $sql = <<<SQL
                INSERT INTO {$table} ({$attributes}) VALUES ({$values});
            SQL;

            $stmt = $pdo->prepare($sql);
            foreach (static::$attributes as $attribute) {
                $stmt->bindValue($attribute, $this->$attribute);
            }

            $stmt->execute();

            $this->setId($pdo->lastInsertId());

            return true;
        }

        return false;
    }

    public function destroy()
    {
        $table = static::$table;

        $sql = <<<SQL
            DELETE FROM {$table} WHERE id = :id;
        SQL;

        $pdo = Database::getDBConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $this->getId());

        $stmt->execute();

        return ($stmt->rowCount() != 0);
    }

    public static function all()
    {
        $models = [];

        $attributes = implode(', ', static::$attributes);
        $table = static::$table;

        $sql = <<<SQL
            SELECT id, {$attributes} FROM {$table};
        SQL;

        $pdo = Database::getDBConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $resp = $stmt->fetchAll(PDO::FETCH_NUM);

        foreach ($resp as $row) {
            $models[] = new static(...$row);
        }

        return $models;
    }

    public static function where($conditions)
    {
        $table = static::$table;
        $attributes = implode(', ', static::$attributes);

        $sql = <<<SQL
            SELECT id, {$attributes} FROM {$table} WHERE 
        SQL;

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
        $rows = $stmt->fetchAll(PDO::FETCH_NUM);

        $models = [];
        foreach ($rows as $row) {
            $models[] = new static(...$row);
        }
        return $models;
    }

    public static function findBy($conditions)
    {
        $resp = self::where($conditions);
        if (isset($resp[0]))
            return $resp[0];

        return null;
    }

    public static function findById(int $id)
    {
        $attributes = implode(', ', static::$attributes);
        $table = static::$table;

        $sql = <<<SQL
            SELECT id, {$attributes} FROM {$table} WHERE id = :id;
        SQL;

        $pdo = Database::getDBConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch(PDO::FETCH_NUM);

        return new static(...$row);
    }
}
