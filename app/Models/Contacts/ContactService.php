<?php

require_once __DIR__ . '/Contact.php';
require_once __DIR__ . '/ContactRepositoryInterface.php';
require_once __DIR__ . '/ContactRepository.php';

final class ContactService
{
    public function listAll(mysqli $conn): array
    {
        $repository = new ContactRepository();

        return $repository->listAll($conn);
    }

    public function findById(mysqli $conn, int $id): ?Contact
    {
        $repository = new ContactRepository();

        return $repository->findById($conn, $id);
    }

    public function create(mysqli $conn, array $data): bool
    {
        $contact = new Contact();
        $contact->fill($data);

        if (!$contact->validate()) {
            return false;
        }

        $repository = new ContactRepository();

        return $repository->insert($conn, $contact);
    }

    public function update(mysqli $conn, int $id, array $data): bool
    {
        $repository = new ContactRepository();
        $existing = $repository->findById($conn, $id);

        if ($existing === null) {
            return false;
        }

        $contact = new Contact();
        $contact->fill(['id' => $id] + $data, $existing->getUserId());

        if (!$contact->validate()) {
            return false;
        }

        return $repository->update($conn, $contact);
    }

    public function delete(mysqli $conn, int $id): bool
    {
        $repository = new ContactRepository();

        if ($repository->findById($conn, $id) === null) {
            return false;
        }

        return $repository->delete($conn, $id);
    }
}
