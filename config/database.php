<?php
// Configurações do banco de dados
$host = "localhost";
$username = "root";
$password = "senha-db";
$database = "agenda";
$port = 3308;

// Criar conexão
$conn = mysqli_connect($host, $username, $password, $database, $port);

// Verificar conexão
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Definir charset para utf8
mysqli_set_charset($conn, "utf8");
?>