<?php

require_once __DIR__ . '/ContactEntity.php';
require_once __DIR__ . '/ContactRepository.php';

final class ContactService
{
    private string $error = '';

    public function getError(): string
    {
        return $this->error;
    }

    private function persist(mysqli $conn, ContactRepository $repository, ContactEntity $contact): bool
    {
        $this->error = '';
        $validationError = $contact->validationError();

        if ($validationError !== null) {
            $this->error = $validationError;
            return false;
        }

        try {
            $duplicate = $repository->findDuplicateField($conn, $contact->getUserId(), $contact->getPhone(), $contact->getEmail(), (int) $contact->getId());

            if ($duplicate !== null) {
                $this->error = 'error_duplicate_' . $duplicate;

                return false;
            }

            if ($contact->getId() === null) {
                return $repository->insert($conn, $contact->toArray());
            }

            return $repository->update($conn, $contact->getId(), $contact->toArray());
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() !== 1062) {
                throw $e;
            }

            $this->error = str_contains($e->getMessage(), 'email') ? 'error_duplicate_email' : 'error_duplicate_phone';

            return false;
        }
    }

    public function listAll(mysqli $conn, int $userId): array
    {
        $repository = new ContactRepository();
        $rows = $repository->listAll($conn, $userId);
        $contacts = [];

        for ($i = 0, $total = count($rows); $i < $total; $i++) {
            $contacts[] = $this->createContactFromRow($rows[$i]);
        }

        return $contacts;
    }

    public function findById(mysqli $conn, int $id, int $userId): ?ContactEntity
    {
        $repository = new ContactRepository();
        $row = $repository->findById($conn, $id, $userId);

        return $row === null ? null : $this->createContactFromRow($row);
    }

    public function create(mysqli $conn, int $userId, array $data): bool
    {
        $contact = new ContactEntity();
        $contact->fill($data, $userId);

        $repository = new ContactRepository();

        return $this->persist($conn, $repository, $contact);
    }

    public function update(mysqli $conn, int $userId, int $id, array $data): bool
    {
        if ($this->findById($conn, $id, $userId) === null) {
            return false;
        }

        $contact = new ContactEntity();
        $contact->fill(['id' => $id] + $data, $userId);

        $repository = new ContactRepository();

        return $this->persist($conn, $repository, $contact);
    }

    public function delete(mysqli $conn, int $userId, int $id): bool
    {
        if ($this->findById($conn, $id, $userId) === null) {
            return false;
        }

        $repository = new ContactRepository();

        return $repository->delete($conn, $id, $userId);
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
