<?php
$host     = 'localhost';
$port     = 3309;
$username = 'root';
$password = 'root';
$database = 'agenda';

$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
