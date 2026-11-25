<?php

require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
require_once __DIR__ . '/../../Infrastructure/Auth.php';
require_once __DIR__ . '/../../Models/Users/UserService.php';

final class UserController
{
    private const MESSAGES = [
        'updated' => 'Usuário atualizado com sucesso.',
        'activated' => 'Usuário reativado com sucesso.',
        'deactivated' => 'Usuário inativado com sucesso.',
        'error' => 'Não foi possível concluir a operação.',
        'error_self' => 'Você não pode inativar o seu próprio usuário.',
    ];

    private const FORM_MESSAGES = [
        'error_name' => 'Informe o nome do usuário.',
        'error_email' => 'E-mail inválido. Verifique o endereço informado.',
        'error_duplicate_email' => 'Já existe um usuário cadastrado com este e-mail.',
    ];

    private UserService $userService;
    private array $currentUser;

    public function __construct(private mysqli $conn)
    {
        $this->userService = new UserService();
        $this->currentUser = Auth::mustBe('ADMIN');
    }

    public function dispatch(string $action): void
    {
        switch ($action) {
            case 'edit':
                $this->edit();
                return;
            case 'toggle':
                $this->toggle();
                return;
            case 'list':
            default:
                $this->index();
        }
    }

    private function index(): void
    {
        $users = $this->userService->listAll($this->conn);
        $messageKey = $_GET['msg'] ?? '';
        $notice = UserController::MESSAGES[$messageKey] ?? null;
        $noticeIsError = str_starts_with((string) $messageKey, 'error');
        $currentUserId = $this->currentUser['id'];

        require __DIR__ . '/../../Views/Users/list.php';
    }

    private function edit(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $user = $this->userService->findById($this->conn, $id);
        $error = null;

        if ($user === null) {
            $this->redirect('error');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
            ];

            if ($this->userService->update($this->conn, $id, $data)) {
                $this->redirect('updated');
            }

            $user->fill(['id' => $id, 'type' => $user->getType(), 'active' => $user->isActive(), 'created_at' => $user->getCreatedAt()] + $data);
            $error = UserController::FORM_MESSAGES[$this->userService->getError() ?? ''] ?? UserController::MESSAGES['error'];
        }

        require __DIR__ . '/../../Views/Users/edit.php';
    }

    private function toggle(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $user = $this->userService->findById($this->conn, $id);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $user === null) {
            $this->redirect('error');
        }

        if ($id === $this->currentUser['id']) {
            $this->redirect('error_self');
        }

        $activate = !$user->isActive();
        $ok = $this->userService->setActive($this->conn, $id, $activate);

        $this->redirect($ok ? ($activate ? 'activated' : 'deactivated') : 'error');
    }

    private function redirect(string $message): never
    {
        header('Location: UserController.php?action=list&msg=' . urlencode($message));
        exit;
    }
}

(new UserController($conn))->dispatch($_GET['action'] ?? 'list');
