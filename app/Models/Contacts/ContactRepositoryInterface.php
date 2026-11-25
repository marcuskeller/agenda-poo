<?php

interface ContactRepositoryInterface
{
    public function listAll(mysqli $conn, int $userId): array;

    public function findById(mysqli $conn, int $id, int $userId): ?array;

    public function insert(mysqli $conn, array $contact): bool;

    public function update(mysqli $conn, int $id, array $contact): bool;

    public function delete(mysqli $conn, int $id, int $userId): bool;

    public function findDuplicateField(mysqli $conn, int $userId, string $phone, ?string $email, int $excludeId = 0): ?string;
}
