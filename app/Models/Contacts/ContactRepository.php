<?php

require_once __DIR__ . '/ContactRepositoryInterface.php';

final class ContactRepository implements ContactRepositoryInterface
{
    public function listAll(mysqli $conn, int $userId): array
    {
        $sql = "SELECT c.id, c.user_id, c.name, c.phone, c.email, c.created_at, u.name AS owner_name, u.email AS owner_email
                FROM contacts c
                INNER JOIN users u ON u.id = c.user_id
                WHERE c.user_id = ?
                ORDER BY c.id";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $contacts = [];

        while ($result && $row = $result->fetch_assoc()) {
            $contacts[] = $this->hydrate($row);
        }

        $stmt->close();

        return $contacts;
    }

    public function findById(mysqli $conn, int $id, int $userId): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $sql = "SELECT c.id, c.user_id, c.name, c.phone, c.email, c.created_at, u.name AS owner_name, u.email AS owner_email
                FROM contacts c
                INNER JOIN users u ON u.id = c.user_id
                WHERE c.id = ? AND c.user_id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('ii', $id, $userId);
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

        $sql = "UPDATE contacts SET name = ?, phone = ?, email = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $name = (string) $contact['name'];
        $phone = (string) $contact['phone'];
        $email = $contact['email'];
        $userId = (int) $contact['user_id'];

        $stmt->bind_param('sssii', $name, $phone, $email, $id, $userId);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function delete(mysqli $conn, int $id, int $userId): bool
    {
        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM contacts WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ii', $id, $userId);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function findDuplicateField(mysqli $conn, int $userId, string $phone, ?string $email, int $excludeId = 0): ?string
    {
        $stmt = $conn->prepare("SELECT phone, email FROM contacts WHERE user_id = ? AND id <> ? AND (phone = ? OR (? IS NOT NULL AND email = ?)) LIMIT 1");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('iisss', $userId, $excludeId, $phone, $email, $email);
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
            'owner_name' => $row['owner_name'] ?? null,
            'owner_email' => $row['owner_email'] ?? null,
        ];
    }
}
