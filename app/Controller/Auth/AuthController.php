<?php

require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
require_once __DIR__ . '/../../Infrastructure/Auth.php';
require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../Models/Users/UserService.php';
require_once __DIR__ . '/../../Support/global-messages.php';

final class AuthController extends BaseController
{
    private UserService $userService;

    public function initialize(mysqli $conn): void
    {
        $this->setConnection($conn);
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
        $notice = ($_GET['msg'] ?? '') === 'registered' ? GlobalMessages::get('registered') : null;
        $email = '';

        if ($this->isPost()) {
            $email = trim((string) ($_POST['email'] ?? ''));
            $user = $this->userService->authenticate($this->conn, $email, (string) ($_POST['password'] ?? ''));

            if ($user !== null) {
                Auth::login($user);
                $this->redirectTo(Auth::home($user['type']));
            }

            $error = $this->message();
        } elseif (($user = Auth::user()) !== null) {
            $this->redirectTo(Auth::home($user['type']));
        }

        require __DIR__ . '/../../Views/Auth/login.php';
    }

    private function register(): void
    {
        $error = null;
        $data = ['name' => '', 'email' => ''];

        if ($this->isPost()) {
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
                    $this->redirectTo(Auth::home($user['type']));
                }

                $this->redirectTo('AuthController.php?action=login&msg=registered');
            }

            $error = $this->message();
        }

        require __DIR__ . '/../../Views/Auth/register.php';
    }

    private function logout(): void
    {
        if ($this->isPost()) {
            Auth::logout();
        }

        $this->redirectTo('AuthController.php?action=login');
    }

    private function message(): string
    {
        $errorKey = match ($this->userService->getError()) {
            'error_name' => 'error_auth_name',
            'error_duplicate_email' => 'error_duplicate_user_email',
            default => $this->userService->getError(),
        };

        return GlobalMessages::get($errorKey) ?? (string) GlobalMessages::get('error');
    }

}

$controller = new AuthController();
$controller->initialize($conn);
$controller->dispatch($_GET['action'] ?? 'login');
