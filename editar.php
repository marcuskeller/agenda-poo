<?php

require_once __DIR__ . '/controller/ContatoController.php';

$controller = new ContatoController();

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $controller->formularioEditar((int) trim($_GET['id']));
} else {
    header("Location: view/erro.php");
    exit();
}
