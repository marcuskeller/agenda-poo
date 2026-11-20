<?php

function validarContato(array $dados): bool
{
    $nome = trim((string) ($dados['nome'] ?? ''));
    $email = trim((string) ($dados['email'] ?? ''));

    if ($nome === '') {
        return false;
    }

    return $email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
