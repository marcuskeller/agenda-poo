<?php

require_once __DIR__ . '/controller/ContatoController.php';

$controller = new ContatoController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller->criar($_POST);
} else {
    $controller->formularioCriar();
}
