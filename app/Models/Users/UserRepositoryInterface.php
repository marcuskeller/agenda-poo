<?php

interface UserRepositoryInterface
{
    public function listAll(mysqli $conn): array;

    public function findById(mysqli $conn, int $id): ?array;

    public function findByEmail(mysqli $conn, string $email): ?array;

    public function insert(mysqli $conn, array $user): bool;

    public function update(mysqli $conn, int $id, array $user): bool;

    public function setActive(mysqli $conn, int $id, bool $active): bool;
}
