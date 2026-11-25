<?php

require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
require_once __DIR__ . '/../../Infrastructure/Auth.php';
require_once __DIR__ . '/../../Models/Users/UserService.php';

final class AuthController
{
    private const MESSAGES = [
        'error_login' => 'E-mail ou senha incorretos.',
        'error_inactive' => 'Este usuário está inativo. Procure o administrador.',
        'error_name' => 'Informe o seu nome.',
        'error_email' => 'E-mail inválido. Verifique o endereço informado.',
        'error_password' => 'A senha deve ter pelo menos 6 caracteres.',
        'error_password_confirmation' => 'A confirmação não confere com a senha.',
        'error_duplicate_email' => 'Já existe um usuário cadastrado com este e-mail.',
    ];

    private UserService $userService;

    public function __construct(private mysqli $conn)
    {
        $this->userService = new UserService();
    }

    public function dispatch(string $action): void
    {
        switch ($action) {
            case 'register':
                $this->register();
                return;
            case 'logout':
                $this->logout();
                return;
            case 'login':
            default:
                $this->login();
        }
    }

    private function login(): void
    {
        $error = null;
        $notice = ($_GET['msg'] ?? '') === 'registered' ? 'Cadastro realizado. Faça login para continuar.' : null;
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $user = $this->userService->authenticate($this->conn, $email, (string) ($_POST['password'] ?? ''));

            if ($user !== null) {
                Auth::login($user);
                $this->redirectHome($user['type']);
            }

            $error = $this->message();
        } elseif (Auth::user() !== null) {
            $this->redirectHome(Auth::user()['type']);
        }

        require __DIR__ . '/../../Views/Auth/login.php';
    }

    private function register(): void
    {
        $error = null;
        $data = ['name' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'password_confirmation' => $_POST['password_confirmation'] ?? '',
            ];

            if ($this->userService->register($this->conn, $data)) {
                $user = $this->userService->authenticate($this->conn, (string) $data['email'], (string) $data['password']);

                if ($user !== null) {
                    Auth::login($user);
                    $this->redirectHome($user['type']);
                }

                header('Location: AuthController.php?action=login&msg=registered');
                exit;
            }

            $error = $this->message();
        }

        require __DIR__ . '/../../Views/Auth/register.php';
    }

    private function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Auth::logout();
        }

        header('Location: AuthController.php?action=login');
        exit;
    }

    private function message(): string
    {
        return self::MESSAGES[$this->userService->getError() ?? ''] ?? 'Não foi possível concluir a operação. Tente novamente.';
    }

    private function redirectHome(string $type): never
    {
        header('Location: ' . ($type === 'ADMIN' ? '../Users/UserController.php?action=list' : '../Contacts/ContactController.php?action=list'));
        exit;
    }
}

(new AuthController($conn))->dispatch($_GET['action'] ?? 'login');
