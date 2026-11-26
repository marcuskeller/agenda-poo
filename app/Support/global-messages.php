<?php

final class GlobalMessages
{
    private const ERROR_FIELDS = [
        'error_name' => 'name',
        'error_phone' => 'phone',
        'error_duplicate_phone' => 'phone',
        'error_email' => 'email',
        'error_duplicate_email' => 'email',
    ];

    private const MESSAGES = [
        'created' => 'Contato criado com sucesso.',
        'updated' => 'Contato atualizado com sucesso.',
        'user_updated' => 'Usuário atualizado com sucesso.',
        'registered' => 'Cadastro realizado. Faça login para continuar.',
        'deleted' => 'Contato excluído com sucesso.',
        'activated' => 'Usuário reativado com sucesso.',
        'deactivated' => 'Usuário inativado com sucesso.',
        'error' => 'Não foi possível concluir a operação. Verifique os dados e tente novamente.',
        'error_self' => 'Você não pode inativar o seu próprio usuário.',
        'error_name' => 'Informe o nome do contato.',
        'error_phone' => 'Telefone inválido. Informe DDD e número (10 a 13 dígitos).',
        'error_duplicate_phone' => 'Já existe um contato cadastrado com este telefone.',
        'error_email' => 'E-mail inválido. Verifique o endereço informado.',
        'error_duplicate_email' => 'Já existe um contato cadastrado com este e-mail.',
        'error_user_name' => 'Informe o nome do usuário.',
        'error_duplicate_user_email' => 'Já existe um usuário cadastrado com este e-mail.',
        'error_login' => 'E-mail ou senha incorretos.',
        'error_inactive' => 'Este usuário está inativo. Procure o administrador.',
        'error_auth_name' => 'Informe o seu nome.',
        'error_password' => 'A senha deve ter pelo menos 6 caracteres.',
        'error_password_confirmation' => 'A confirmação não confere com a senha.',
    ];

    public static function get(string $key): string
    {
        if (isset(self::MESSAGES[$key])) {
            return self::MESSAGES[$key];
        }

        return self::MESSAGES['error'];
    }

    public static function fieldFor(?string $key): ?string
    {
        if ($key === null) {
            return null;
        }

        if (isset(self::ERROR_FIELDS[$key])) {
            return self::ERROR_FIELDS[$key];
        }

        return null;
    }
}
