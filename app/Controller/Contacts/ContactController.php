<?php

require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
require_once __DIR__ . '/../../Infrastructure/Auth.php';
require_once __DIR__ . '/../../Models/Contacts/ContactService.php';
require_once __DIR__ . '/../../Models/Users/UserService.php';
require_once __DIR__ . '/../../Support/global-messages.php';

final class ContactController
{
    private mysqli $conn;
    private ContactService $contactService;
    private int $userId;

    public function initialize(mysqli $conn): void
    {
        $this->conn = $conn;
        $this->contactService = new ContactService();

        $sessionUser = Auth::mustBe('USER');
        $user = (new UserService())->findById($conn, $sessionUser['id']);

        if ($user === null || !$user->isActive()) {
            Auth::logout();
            header('Location: ' . Auth::LOGIN_URL);
            exit;
        }

        $this->userId = $user->getId();
    }

    public function dispatch(string $action): void
    {
        switch ($action) {
            case 'create':
                $this->create();
                return;
            case 'edit':
                $this->edit();
                return;
            case 'delete':
                $this->delete();
                return;
            case 'list':
            default:
                $this->index();
        }
    }

    private function index(): void
    {
        $contacts = $this->contactService->listAll($this->conn, $this->userId);

        require __DIR__ . '/../../Views/Contacts/list.php';
    }

    private function create(): void
    {
        $contact = new ContactEntity();
        $error = null;
        $errorField = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->formData();
            $created = $this->contactService->create($this->conn, $this->userId, $data);

            if ($created) {
                $this->redirect('created');
            }

            $contact->fill($data, $this->userId);
            $error = $this->errorMessage();
            $errorField = $this->errorField();
        }

        require __DIR__ . '/../../Views/Contacts/create.php';
    }

    private function edit(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $error = null;
        $errorField = null;
        $contact = $this->contactService->findById($this->conn, $id, $this->userId);

        if ($contact === null) {
            $this->redirect('error');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->formData();
            $updated = $this->contactService->update($this->conn, $this->userId, $id, $data);

            if ($updated) {
                $this->redirect('updated');
            }

            $contact->fill(['id' => $id] + $data, $contact->getUserId());
            $error = $this->errorMessage();
            $errorField = $this->errorField();
        }

        require __DIR__ . '/../../Views/Contacts/edit.php';
    }

    private function formData(): array
    {
        return [
            'name' => $_POST['name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
        ];
    }

    private function errorMessage(): string
    {
        return GlobalMessages::get($this->contactService->getError()) ?? (string) GlobalMessages::get('error');
    }

    private function errorField(): ?string
    {
        $fields = [
            'error_name' => 'name',
            'error_phone' => 'phone',
            'error_duplicate_phone' => 'phone',
            'error_email' => 'email',
            'error_duplicate_email' => 'email',
        ];

        return $fields[$this->contactService->getError() ?? ''] ?? null;
    }

    private function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deleted = $this->contactService->delete($this->conn, $this->userId, $id);
            $this->redirect($deleted ? 'deleted' : 'error');
        }

        $contact = $this->contactService->findById($this->conn, $id, $this->userId);

        if ($contact === null) {
            $this->redirect('error');
        }

        $contacts = $this->contactService->listAll($this->conn, $this->userId);
        require __DIR__ . '/../../Views/Contacts/delete.php';
    }

    private function redirect(string $message): never
    {
        header('Location: ContactController.php?action=list&msg=' . urlencode($message));
        exit;
    }
}

$controller = new ContactController();
$controller->initialize($conn);
$controller->dispatch($_GET['action'] ?? 'list');
