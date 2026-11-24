<?php

require_once __DIR__ . '/Contact.php';

interface ContactRepositoryInterface
{
    public function listAll(mysqli $conn): array;

    public function findById(mysqli $conn, int $id): ?Contact;

    public function insert(mysqli $conn, Contact $contact): bool;

    public function update(mysqli $conn, Contact $contact): bool;

    public function delete(mysqli $conn, int $id): bool;
}
