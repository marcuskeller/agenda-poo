<?php

require_once __DIR__ . '/Contact.php';
require_once __DIR__ . '/ContactRepositoryInterface.php';

final class ContactRepository implements ContactRepositoryInterface
{
    public function listAll(mysqli $conn): array
    {
        $sql = "SELECT id, usuario_id, nome, telefone, email, data_cadastro FROM contatos ORDER BY id";
        $result = $conn->query($sql);

        if (!$result) {
            return [];
        }

        $contacts = [];

        while ($row = $result->fetch_assoc()) {
            $contacts[] = $this->hydrate($row);
        }

        return $contacts;
    }

    public function findById(mysqli $conn, int $id): ?Contact
    {
        if ($id <= 0) {
            return null;
        }

        $sql = "SELECT id, usuario_id, nome, telefone, email, data_cadastro FROM contatos WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $row ? $this->hydrate($row) : null;
    }

    public function insert(mysqli $conn, Contact $contact): bool
    {
        $sql = "INSERT INTO contatos (usuario_id, nome, telefone, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $userId = $contact->getUserId();
        $name = $contact->getName();
        $phone = $contact->getPhone();
        $email = $contact->getEmail();

        $stmt->bind_param('isss', $userId, $name, $phone, $email);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function update(mysqli $conn, Contact $contact): bool
    {
        $id = $contact->getId();

        if ($id === null) {
            return false;
        }

        $sql = "UPDATE contatos SET nome = ?, telefone = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $name = $contact->getName();
        $phone = $contact->getPhone();
        $email = $contact->getEmail();

        $stmt->bind_param('sssi', $name, $phone, $email, $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function delete(mysqli $conn, int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM contatos WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    private function hydrate(array $row): Contact
    {
        $contact = new Contact();
        $contact->fill([
            'id' => $row['id'],
            'name' => $row['nome'],
            'phone' => $row['telefone'],
            'email' => $row['email'],
            'created_at' => $row['data_cadastro'],
        ], (int) $row['usuario_id']);

        return $contact;
    }
}
