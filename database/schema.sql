CREATE DATABASE IF NOT EXISTS todo_development;

use todo_development;

DROP TABLE IF EXISTS tasks;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

INSERT INTO tasks (name) VALUES ('Estudar PHP com Mysql e Docker');