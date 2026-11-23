<?php

require_once __DIR__ . '/../../infra/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Users/create.php';
require_once __DIR__ . '/../../Models/Users/edit.php';
require_once __DIR__ . '/../../Models/Users/update.php';
require_once __DIR__ . '/../../Models/Users/delete.php';

$views = '../../Views';
$lista = $views . '/HomePage/index.php';
$erro = $views . '/GlobalMessages/erro.html';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

$dados = [
    'nome' => trim($_POST['nome'] ?? ''),
    'telefone' => trim($_POST['telefone'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
];

switch ($action) {
    case 'create':
        $destino = criarContato($conn, $dados)
            ? $lista . '?msg=criado'
            : $views . '/CreateContact/index-erro.html';
        break;

    case 'update':
        $destino = atualizarContato($conn, $id, $dados)
            ? $lista . '?msg=atualizado'
            : $erro;
        break;

    case 'delete':
        $destino = excluirContato($conn, $id)
            ? $lista . '?msg=excluido'
            : $erro;
        break;

    default:
        $destino = $lista;
}

mysqli_close($conn);

header('location: ' . $destino);
exit;
