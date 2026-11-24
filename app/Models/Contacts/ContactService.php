<?php

require_once __DIR__ . '/ContactEntity.php';
require_once __DIR__ . '/ContactRepositoryInterface.php';
require_once __DIR__ . '/ContactRepository.php';

final class ContactService
{
    public function listAll(mysqli $conn): array
    {
        $repository = new ContactRepository();
        $rows = $repository->listAll($conn);
        $contacts = [];

        for ($i = 0, $total = count($rows); $i < $total; $i++) {
            $contacts[] = $this->createContactFromRow($rows[$i]);
        }

        return $contacts;
    }

    public function findById(mysqli $conn, int $id): ?ContactEntity
    {
        $repository = new ContactRepository();
        $row = $repository->findById($conn, $id);

        return $row === null ? null : $this->createContactFromRow($row);
    }

    public function create(mysqli $conn, array $data): bool
    {
        $contact = new ContactEntity();
        $contact->fill($data);

        if (!$contact->validate()) {
            return false;
        }

        $repository = new ContactRepository();

        return $repository->insert($conn, $contact->toArray());
    }

    public function update(mysqli $conn, int $id, array $data): bool
    {
        $existing = $this->findById($conn, $id);

        if ($existing === null) {
            return false;
        }

        $contact = new ContactEntity();
        $contact->fill(['id' => $id] + $data, $existing->getUserId());

        if (!$contact->validate()) {
            return false;
        }

        $repository = new ContactRepository();

        return $repository->update($conn, $id, $contact->toArray());
    }

    public function delete(mysqli $conn, int $id): bool
    {
        if ($this->findById($conn, $id) === null) {
            return false;
        }

        $repository = new ContactRepository();

        return $repository->delete($conn, $id);
    }

    private function createContactFromRow(array $row): ContactEntity
    {
        $contact = new ContactEntity();
        $contact->fill([
            'id' => $row['id'],
            'name' => $row['name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'created_at' => $row['created_at'],
        ], (int) $row['user_id']);

        return $contact;
    }
}
