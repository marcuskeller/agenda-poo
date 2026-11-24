<?php

require_once __DIR__ . '/../../Infraestrutura/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Contacts/ContatoService.php';

$contatoService = new ContatoService();

$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atualizado = $contatoService->atualizar($conn, $id, [
        'nome' => $_POST['nome'] ?? '',
        'telefone' => $_POST['telefone'] ?? '',
        'email' => $_POST['email'] ?? '',
    ]);

    $destino = 'ListContactsController.php?msg=' . ($atualizado ? 'atualizado' : 'erro');

    header('Location: ' . $destino);
    exit;
}

$contato = $contatoService->buscar($conn, $id);

if ($contato === null) {
    header('Location: ListContactsController.php?msg=erro');
    exit;
}

require __DIR__ . '/../../Views/Contacts/EditContact/index.php';
