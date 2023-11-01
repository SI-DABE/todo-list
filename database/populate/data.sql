INSERT INTO users (name, email, password) VALUES ('Diego', 'marczal@utfpr.edu.br', '$2y$10$NeMHijq2XZpS9Q1oMZ26beL8Sx27jN8yIBskFgcZPajXaKNr.Jb5q');
INSERT INTO users (name, email, password) VALUES ('Nic', 'nic@gmail.com', '$2y$10$NeMHijq2XZpS9Q1oMZ26beL8Sx27jN8yIBskFgcZPajXaKNr.Jb5q');

INSERT INTO tasks (name, user_id) VALUES ('Estudar PHP com Mysql e Docker', LAST_INSERT_ID());