<?php

require_once __DIR__ . '/controller/ContatoController.php';

$controller = new ContatoController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller->atualizar($_POST);
} else {
    header("Location: index.php");
    exit();
}
