<?php

final class GlobalMessages
{
    private const MESSAGES = [
        'created' => 'Contato criado com sucesso.',
        'updated' => 'Contato atualizado com sucesso.',
        'user_updated' => 'Usuário atualizado com sucesso.',
        'deleted' => 'Contato excluído com sucesso.',
        'activated' => 'Usuário reativado com sucesso.',
        'deactivated' => 'Usuário inativado com sucesso.',
        'error' => 'Não foi possível concluir a operação. Verifique os dados e tente novamente.',
        'error_self' => 'Você não pode inativar o seu próprio usuário.',
        'error_name' => 'Informe o nome do contato.',
        'error_phone' => 'Telefone inválido. Informe DDD e número (10 a 13 dígitos).',
        'error_email' => 'E-mail inválido. Verifique o endereço informado.',
        'error_duplicate_phone' => 'Já existe um contato cadastrado com este telefone.',
        'error_duplicate_email' => 'Já existe um contato cadastrado com este e-mail.',
        'error_user_name' => 'Informe o nome do usuário.',
        'error_duplicate_user_email' => 'Já existe um usuário cadastrado com este e-mail.',
    ];

    public static function get(?string $key, ?string $fallback = null): ?string
    {
        if ($key === null || $key === '') {
            return $fallback;
        }

        return self::MESSAGES[$key] ?? $fallback;
    }
}
