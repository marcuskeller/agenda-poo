<?php

require_once __DIR__ . '/../../Infraestrutura/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Contacts/ContatoService.php';

$contatoService = new ContatoService();

$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $excluido = $contatoService->excluir($conn, $id);

    $destino = 'ListContactsController.php?msg=' . ($excluido ? 'excluido' : 'erro');

    header('Location: ' . $destino);
    exit;
}

$contato = $contatoService->buscar($conn, $id);

if ($contato === null) {
    header('Location: ListContactsController.php?msg=erro');
    exit;
}

$contatos = $contatoService->listar($conn);

require __DIR__ . '/../../Views/Contacts/DeleteContact/index.php';
