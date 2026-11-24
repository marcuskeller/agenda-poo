<?php

interface ContactRepositoryInterface
{
    public function listAll(mysqli $conn): array;

    public function findById(mysqli $conn, int $id): ?array;

    public function insert(mysqli $conn, array $contact): bool;

    public function update(mysqli $conn, int $id, array $contact): bool;

    public function delete(mysqli $conn, int $id): bool;

    public function findDuplicateField(mysqli $conn, string $phone, ?string $email, int $excludeId = 0): ?string;
}
