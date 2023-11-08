<?php

namespace App\Lib;

use Core\Db\Database;

class Validations
{
    public static function notEmpty($value, $key, &$errors)
    {
        if (empty($value)) {
            $errors[$key] = 'não pode ser vazio';
            return false;
        }

        return true;
    }

    public static function passwordConfirmation($password, $passwordConfirmation, $key, &$errors)
    {
        if ($password !== $passwordConfirmation) {
            $errors[$key] = 'as senhas devem ser idênticas';
            return false;
        }

        return true;
    }

    public static function uniqueness($fields, $object)
    {
        $table = $object->getTable();
        $conditions = implode(' AND ', array_map(fn ($field) => "{$field} = :{$field}", $fields));

        $sql = <<<SQL
            SELECT id FROM {$table} WHERE {$conditions};
        SQL;

        $pdo = Database::getDBConnection();
        $stmt = $pdo->prepare($sql);
        foreach ($fields as $field) {
            $method = 'get' . StringUtils::snakeToCamelCase($field);
            $stmt->bindValue($field, $object->$method());
        }

        $stmt->execute();

        if ($stmt->rowCount() !== 0) {
            foreach ($fields as $field) {
                $object->addError($field, 'já existe um registro com esse dado');
            }
            return false;
        }

        return true;
    }
}
