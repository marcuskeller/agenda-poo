<?php

function validarNome(array $dados): bool
{
    return trim((string) ($dados['nome'] ?? '')) !== '';
}

function validarEmail(array $dados): bool
{
    $email = trim((string) ($dados['email'] ?? ''));

    return $email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validarContato(array $dados): bool
{
    return validarNome($dados) && validarEmail($dados);
}
