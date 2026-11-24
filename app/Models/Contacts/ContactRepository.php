<?php

require_once __DIR__ . '/ContactRepositoryInterface.php';

final class ContactRepository implements ContactRepositoryInterface
{
    public function listAll(mysqli $conn): array
    {
        $sql = "SELECT id, user_id, name, phone, email, created_at FROM contacts ORDER BY id";
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

    public function findById(mysqli $conn, int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $sql = "SELECT id, user_id, name, phone, email, created_at FROM contacts WHERE id = ?";
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

    public function insert(mysqli $conn, array $contact): bool
    {
        $sql = "INSERT INTO contacts (user_id, name, phone, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $userId = (int) $contact['user_id'];
        $name = (string) $contact['name'];
        $phone = (string) $contact['phone'];
        $email = $contact['email'];

        $stmt->bind_param('isss', $userId, $name, $phone, $email);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function update(mysqli $conn, int $id, array $contact): bool
    {
        if ($id <= 0) {
            return false;
        }

        $sql = "UPDATE contacts SET name = ?, phone = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $name = (string) $contact['name'];
        $phone = (string) $contact['phone'];
        $email = $contact['email'];

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

        $sql = "DELETE FROM contacts WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function findDuplicateField(mysqli $conn, string $phone, ?string $email, int $excludeId = 0): ?string
    {
        $stmt = $conn->prepare("SELECT phone, email FROM contacts WHERE id <> ? AND (phone = ? OR (? IS NOT NULL AND email = ?)) LIMIT 1");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('isss', $excludeId, $phone, $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$row) {
            return null;
        }

        return $row['phone'] === $phone ? 'phone' : 'email';
    }

    private function hydrate(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'user_id' => (int) $row['user_id'],
            'name' => $row['name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'created_at' => $row['created_at'],
        ];
    }
}
