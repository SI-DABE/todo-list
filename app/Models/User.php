<?php

namespace App\Models;

use App\Models\Base;
use Core\Db\Database;

class User extends Base
{

    private string $name;
    private string $email;

    public function __construct(int $id = -1, string $name = '', $email = '')
    {
        parent::__construct($id);
        $this->name = trim($name);
        $this->email = trim($email);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function authenticate(string $password)
    {
        $pdo = Database::getConnection();

        $sql = 'SELECT password FROM users WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $this->email);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return false;
        }

        $row = $stmt->fetch();

        return password_verify($password, $row['password']);
    }

    public static function findByEmail(string $email): User | null
    {
        $pdo = Database::getConnection();

        $sql = 'SELECT id, name, email FROM users WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new User(id: $row['id'], name: $row['name'], email: $row['email']);
    }

    public static function findById(int $id): User | null
    {
        $pdo = Database::getConnection();

        $sql = 'SELECT id, name, email FROM users WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new User(id: $row['id'], name: $row['name'], email: $row['email']);
    }
}
