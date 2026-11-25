<?php

require_once __DIR__ . '/UserRepositoryInterface.php';

final class UserRepository implements UserRepositoryInterface
{
    private const COLUMNS = 'id, name, email, password, type, active, created_at';

    public function listAll(mysqli $conn): array
    {
        $result = $conn->query('SELECT ' . UserRepository::COLUMNS . ' FROM users ORDER BY id');

        if (!$result) {
            return [];
        }

        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $this->hydrate($row);
        }

        return $users;
    }

    public function findById(mysqli $conn, int $id): ?array
    {
        return $this->findOne($conn, 'id = ?', 'i', $id);
    }

    public function findByEmail(mysqli $conn, string $email): ?array
    {
        return $this->findOne($conn, 'email = ?', 's', $email);
    }

    public function insert(mysqli $conn, array $user): bool
    {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, type) VALUES (?, ?, MD5(?), 'USER')");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('sss', $user['name'], $user['email'], $user['password']);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function update(mysqli $conn, int $id, array $user): bool
    {
        if ($user['password'] !== '') {
            $stmt = $conn->prepare('UPDATE users SET name = ?, email = ?, password = MD5(?) WHERE id = ?');

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param('sssi', $user['name'], $user['email'], $user['password'], $id);
        } else {
            $stmt = $conn->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param('ssi', $user['name'], $user['email'], $id);
        }

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function setActive(mysqli $conn, int $id, bool $active): bool
    {
        $stmt = $conn->prepare('UPDATE users SET active = ? WHERE id = ?');

        if (!$stmt) {
            return false;
        }

        $flag = $active ? 1 : 0;
        $stmt->bind_param('ii', $flag, $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    private function findOne(mysqli $conn, string $where, string $types, $value): ?array
    {
        $stmt = $conn->prepare('SELECT ' . UserRepository::COLUMNS . ' FROM users WHERE ' . $where . ' LIMIT 1');

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param($types, $value);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $row ? $this->hydrate($row) : null;
    }

    private function hydrate(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'password_hash' => $row['password'],
            'type' => $row['type'],
            'active' => (int) $row['active'],
            'created_at' => $row['created_at'],
        ];
    }
}
