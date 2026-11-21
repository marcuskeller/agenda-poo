<?php

function listarContatos(mysqli $conn): array
{
    $sql = "SELECT id, usuario_id, nome, telefone, email, data_cadastro FROM contatos ORDER BY id";

    $resultado = mysqli_query($conn, $sql);

    if (!$resultado) {
        return [];
    }

    $contatos = [];

    while ($linha = mysqli_fetch_assoc($resultado)) {
        $contatos[] = $linha;
    }

    return $contatos;
}
