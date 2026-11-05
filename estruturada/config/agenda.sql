-- Criar o banco de dados
CREATE DATABASE agenda;

-- Usar o banco de dados
USE agenda;

-- Criar a tabela contatos
CREATE TABLE contatos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

SELECT * FROM contatos