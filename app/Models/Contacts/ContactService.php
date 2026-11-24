<?php

require_once __DIR__ . '/ContactEntity.php';
require_once __DIR__ . '/ContactRepositoryInterface.php';
require_once __DIR__ . '/ContactRepository.php';

final class ContactService
{
    private ?string $error = null;

    public function getError(): ?string
    {
        return $this->error;
    }

    private function persist(mysqli $conn, ContactRepository $repository, ContactEntity $contact, callable $operation): bool
    {
        $this->error = $contact->validationError();

        if ($this->error !== null) {
            return false;
        }

        try {
            $duplicate = $repository->findDuplicateField($conn, $contact->getPhone(), $contact->getEmail(), (int) $contact->getId());

            if ($duplicate !== null) {
                $this->error = 'error_duplicate_' . $duplicate;

                return false;
            }

            return $operation();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() !== 1062) {
                throw $e;
            }

            $this->error = str_contains($e->getMessage(), 'email') ? 'error_duplicate_email' : 'error_duplicate_phone';

            return false;
        }
    }

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

        $repository = new ContactRepository();

        return $this->persist($conn, $repository, $contact, fn () => $repository->insert($conn, $contact->toArray()));
    }

    public function update(mysqli $conn, int $id, array $data): bool
    {
        $existing = $this->findById($conn, $id);

        if ($existing === null) {
            return false;
        }

        $contact = new ContactEntity();
        $contact->fill(['id' => $id] + $data, $existing->getUserId());

        $repository = new ContactRepository();

        return $this->persist($conn, $repository, $contact, fn () => $repository->update($conn, $id, $contact->toArray()));
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
