-- Criar o banco de dados
DROP DATABASE IF EXISTS agenda;
CREATE DATABASE IF NOT EXISTS agenda;

-- Usar o banco de dados
USE agenda;

-- Criar a tabela users
DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password CHAR(32) NOT NULL,
    type ENUM('ADMIN', 'USER') NOT NULL DEFAULT 'USER',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
);

-- Criar a tabela de contacts
CREATE TABLE contacts (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    CONSTRAINT fk_contact_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- Insere user admin padrão
INSERT INTO users (name, email, password, type) VALUES 
(
    'Administrador',
    'admin@agenda.com',
    MD5('admin123'),
    'ADMIN'
);