<?php

namespace App\Models;

use App\Lib\Validations;
use App\Models\Base;
use Core\Db\Database;

class User extends Base
{
    private string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmation;

    public function __construct(
        int $id = -1,
        string $name = '',
        $email = '',
        $password = '',
        $passwordConfirmation = ''
    ) {
        parent::__construct($id);
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = trim($password);
        $this->passwordConfirmation = trim($passwordConfirmation);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function validates()
    {
        Validations::notEmpty($this->name, 'name', $this->errors);
        Validations::notEmpty($this->email, 'email', $this->errors);
        Validations::notEmpty($this->password, 'password', $this->errors);
        Validations::passwordConfirmation(
            $this->password,
            $this->passwordConfirmation,
            'password',
            $this->errors
        );
    }

    public function authenticate(string $password)
    {
        $pdo = Database::getDBConnection();

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

    public function save()
    {
        if ($this->isValid()) {
            $pdo = Database::getDBConnection();

            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password);";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindValue(':password', password_hash($this->password, PASSWORD_DEFAULT));

            $stmt->execute();

            $this->setId($pdo->lastInsertId());

            return true;
        }

        return false;
    }

    public function tasks()
    {
        return Task::where(['user_id' => $this->getId()]);
    }

    public static function findByEmail(string $email): User | null
    {
        $pdo = Database::getDBConnection();

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
        $pdo = Database::getDBConnection();

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
