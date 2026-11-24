<?php

require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Contacts/ContactService.php';

final class ContactController
{
    private ContactService $contactService;

    public function __construct(private mysqli $conn)
    {
        $this->contactService = new ContactService();
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
        $contatos = $this->contactService->listAll($this->conn);
        $mensagens = [
            'created' => 'Contato criado com sucesso.',
            'updated' => 'Contato atualizado com sucesso.',
            'deleted' => 'Contato excluído com sucesso.',
            'error' => 'Não foi possível concluir a operação. Verifique os dados e tente novamente.',
        ];
        $aviso = $mensagens[$_GET['msg'] ?? ''] ?? null;

        $conn = $this->conn;
        require __DIR__ . '/../../Views/Contacts/list.php';
    }

    private function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $created = $this->contactService->create($this->conn, [
                'name' => $_POST['nome'] ?? '',
                'phone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
            ]);

            $this->redirect($created ? 'created' : 'error');
        }

        require __DIR__ . '/../../Views/Contacts/create.php';
    }

    private function edit(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updated = $this->contactService->update($this->conn, $id, [
                'name' => $_POST['nome'] ?? '',
                'phone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
            ]);

            $this->redirect($updated ? 'updated' : 'error');
        }

        $contato = $this->contactService->findById($this->conn, $id);

        if ($contato === null) {
            $this->redirect('error');
        }

        require __DIR__ . '/../../Views/Contacts/edit.php';
    }

    private function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deleted = $this->contactService->delete($this->conn, $id);
            $this->redirect($deleted ? 'deleted' : 'error');
        }

        $contato = $this->contactService->findById($this->conn, $id);

        if ($contato === null) {
            $this->redirect('error');
        }

        $contatos = $this->contactService->listAll($this->conn);
        $conn = $this->conn;
        require __DIR__ . '/../../Views/Contacts/delete.php';
    }

    private function redirect(string $message): never
    {
        header('Location: ContactController.php?action=list&msg=' . urlencode($message));
        exit;
    }
}

(new ContactController($conn))->dispatch($_GET['action'] ?? 'list');
