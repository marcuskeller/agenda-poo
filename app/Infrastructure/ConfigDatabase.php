<?php
$host = '127.0.0.1';
$port = 3306;
$username = 'root';
$password = '';
$database = 'agenda';

$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

date_default_timezone_set('America/Sao_Paulo');
mysqli_query($conn, "SET time_zone = '-03:00'");
