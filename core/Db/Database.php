<?php

namespace Core\Db;

use PDO;

class Database
{
    private static $instance = null;

    private string $user, $pwd, $host, $port, $db, $prefix;

    private function __construct()
    {
        $this->prefix = 'mysql';
        $this->user = $_ENV['DB_USERNAME'];
        $this->pwd  = $_ENV['DB_PASSWORD'];
        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'];
        $this->db   = $_ENV['DB_DATABASE'];
    }

    private function __clone()
    {
    }

    private static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function dsn()
    {
        return $this->prefix . ':host=' . $this->host . ';port=' . $this->port . ';';
    }

    private function dsnWithDb()
    {
        return $this->dsn() . 'dbname=' . $this->db;
    }

    public static function getDBConnection()
    {
        $pdo = new PDO(self::getInstance()->dsnWithDb(), self::getInstance()->user, self::getInstance()->pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function getConnection()
    {
        $pdo = new PDO(self::getInstance()->dsn(), self::getInstance()->user, self::getInstance()->pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function create()
    {
        $sql = 'CREATE DATABASE IF NOT EXISTS ' . $_ENV['DB_DATABASE'] . ';';
        self::getConnection()->exec($sql);
    }

    public static function drop()
    {
        $sql = 'DROP DATABASE IF EXISTS ' . $_ENV['DB_DATABASE'] . ';';
        self::getConnection()->exec($sql);
    }

    public static function createTables()
    {
        $sql = file_get_contents('/var/www/database/schema.sql');
        self::getDBConnection()->exec($sql);
    }
}
