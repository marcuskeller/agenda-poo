-- Criar o banco de dados
DROP DATABASE IF EXISTS agenda;
CREATE DATABASE IF NOT EXISTS agenda;

-- Usar o banco de dados
USE agenda;

-- Criar a tabela usuarios
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha CHAR(32) NOT NULL,
    tipo ENUM('ADMIN', 'USER') NOT NULL DEFAULT 'USER',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
);

-- Criar a tabela de contatos
DROP TABLE IF EXISTS contatos;
CREATE TABLE contatos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    CONSTRAINT fk_contato_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);

-- Insere user admin padrão
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
(
    'Administrador',
    'admin@agenda.com',
    MD5('admin123'),
    'ADMIN'
);