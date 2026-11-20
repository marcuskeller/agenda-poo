<?php

require_once __DIR__ . '/validation.php';

function criarContato(mysqli $conn, array $dados): bool
{
    if (!validarContato($dados)) {
        return false;
    }

    $usuarioId = 1;
    $nome = trim((string) ($dados['nome'] ?? ''));
    $telefone = trim((string) ($dados['telefone'] ?? ''));
    $email = trim((string) ($dados['email'] ?? ''));

    $sql = "INSERT INTO contatos (usuario_id, nome, telefone, email) VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "isss", $usuarioId, $nome, $telefone, $email);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return (bool) $ok;
}
