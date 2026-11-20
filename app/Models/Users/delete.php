<?php

require_once __DIR__ . '/edit.php';

function excluirContato(mysqli $conn, int $id): bool
{
    if ($id <= 0 || !buscarContato($conn, $id)) {
        return false;
    }

    $sql = "DELETE FROM contatos WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return (bool) $ok;
}
