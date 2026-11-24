<?php

require_once __DIR__ . '/../../Infraestrutura/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Contacts/ContatoService.php';

$contatoService = new ContatoService();

$contatos = $contatoService->listar($conn);

$mensagens = [
    'criado' => 'Contato criado com sucesso.',
    'atualizado' => 'Contato atualizado com sucesso.',
    'excluido' => 'Contato excluído com sucesso.',
    'erro' => 'Não foi possível concluir a operação. Verifique os dados e tente novamente.',
];

$msg = $_GET['msg'] ?? '';
$aviso = $mensagens[$msg] ?? null;

require __DIR__ . '/../../Views/Contacts/listContats/index.php';
