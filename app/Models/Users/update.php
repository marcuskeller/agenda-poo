<?php

require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/edit.php';

function atualizarContato(mysqli $conn, int $id, array $dados): bool
{
    if ($id <= 0 || !validarContato($dados) || !buscarContato($conn, $id)) {
        return false;
    }

    $nome = trim((string) ($dados['nome'] ?? ''));
    $telefone = trim((string) ($dados['telefone'] ?? ''));
    $email = trim((string) ($dados['email'] ?? ''));

    $sql = "UPDATE contatos SET nome = ?, telefone = ?, email = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "sssi", $nome, $telefone, $email, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return (bool) $ok;
}
