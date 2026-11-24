<?php

require_once __DIR__ . '/../../Infraestrutura/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Contacts/ContatoService.php';

$contatoService = new ContatoService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $criado = $contatoService->criar($conn, [
        'nome' => $_POST['nome'] ?? '',
        'telefone' => $_POST['telefone'] ?? '',
        'email' => $_POST['email'] ?? '',
    ]);

    $destino = 'ListContactsController.php?msg=' . ($criado ? 'criado' : 'erro');

    header('Location: ' . $destino);
    exit;
}

require __DIR__ . '/../../Views/Contacts/CreateContact/index.php';
