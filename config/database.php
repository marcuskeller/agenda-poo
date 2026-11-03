<?php

// Responsável pelas credenciais e por abrir a conexão com o banco de dados.
class Database
{
    // ": mysqli" é o tipo de retorno: garante que essa função sempre devolve uma conexão mysqli
    public static function getConexao(): mysqli
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "agenda";

        $conexao = mysqli_connect($host, $username, $password, $database);

        if (!$conexao) {
            die("Falha na conexão: " . mysqli_connect_error());
        }

        mysqli_set_charset($conexao, "utf8");

        return $conexao;
    }
}
