CREATE DATABASE IF NOT EXISTS todo_development;

use todo_development;

DROP TABLE IF EXISTS tasks;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

INSERT INTO tasks (name) VALUES ('Estudar PHP com Mysql e Docker');

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(65) NOT NULL
);

INSERT INTO users (name, email, password) VALUES ('Diego', 'marczal@utfpr.edu.br', '$2y$10$NeMHijq2XZpS9Q1oMZ26beL8Sx27jN8yIBskFgcZPajXaKNr.Jb5q');