<?php

function buscarContato(mysqli $conn, int $id): ?array
{
    if ($id <= 0) {
        return null;
    }

    $sql = "SELECT id, usuario_id, nome, telefone, email FROM contatos WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    $contato = $resultado ? mysqli_fetch_assoc($resultado) : null;

    mysqli_stmt_close($stmt);

    return $contato ?: null;
}
