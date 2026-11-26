<?php

require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
require_once __DIR__ . '/../../Infrastructure/Auth.php';
require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../Models/Users/UserService.php';
require_once __DIR__ . '/../../Support/global-messages.php';

final class UserController extends BaseController
{
    private UserService $userService;
    private array $currentUser;

    public function initialize(mysqli $conn): void
    {
        $this->setConnection($conn);
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
        $notice = GlobalMessages::get($messageKey === 'updated' ? 'user_updated' : $messageKey);
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

        if ($this->isPost()) {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
            ];

            if ($this->userService->update($this->conn, $id, $data)) {
                $this->redirect('updated');
            }

            $user->fill(['id' => $id, 'type' => $user->getType(), 'active' => $user->isActive(), 'created_at' => $user->getCreatedAt()] + $data);
            $errorKey = match ($this->userService->getError()) {
                'error_name' => 'error_user_name',
                'error_email' => 'error_email',
                'error_duplicate_email' => 'error_duplicate_user_email',
                default => 'error',
            };
            $error = GlobalMessages::get($errorKey);
        }

        require __DIR__ . '/../../Views/Users/edit.php';
    }

    private function toggle(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if (!$this->isPost()) {
            $this->redirect('error');
        }

        $active = $this->userService->toggleActive($this->conn, $id, $this->currentUser['id']);

        if ($active === null) {
            $this->redirect($this->userService->getError() ?? 'error');
        }

        $this->redirect($active ? 'activated' : 'deactivated');
    }

    private function redirect(string $message): never
    {
        $this->redirectTo('UserController.php?action=list&msg=' . urlencode($message));
    }
}

$controller = new UserController();
$controller->initialize($conn);
$controller->dispatch($_GET['action'] ?? 'list');
