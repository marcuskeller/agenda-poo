<?php

require_once __DIR__ . '/UserEntity.php';
require_once __DIR__ . '/UserRepositoryInterface.php';
require_once __DIR__ . '/UserRepository.php';

final class UserService
{
    private ?string $error = null;

    public function getError(): ?string
    {
        return $this->error;
    }

    public function listAll(mysqli $conn): array
    {
        $users = [];

        foreach ((new UserRepository())->listAll($conn) as $row) {
            $users[] = $this->toEntity($row);
        }

        return $users;
    }

    public function findById(mysqli $conn, int $id): ?UserEntity
    {
        $row = (new UserRepository())->findById($conn, $id);

        return $row === null ? null : $this->toEntity($row);
    }

    public function register(mysqli $conn, array $data): bool
    {
        $user = new UserEntity();
        $user->fill($data);
        $repository = new UserRepository();

        return $this->persist($user, $repository, true, $conn);
    }

    public function update(mysqli $conn, int $id, array $data): bool
    {
        $repository = new UserRepository();
        $existing = $repository->findById($conn, $id);

        if ($existing === null) {
            $this->error = 'error';

            return false;
        }

        $user = new UserEntity();
        $user->fill(['id' => $id, 'type' => $existing['type'], 'active' => $existing['active']] + $data);

        return $this->persist($user, $repository, false, $conn);
    }

    public function toggleActive(mysqli $conn, int $id, int $currentUserId): ?bool
    {
        $this->error = null;

        if ($id === $currentUserId) {
            $this->error = 'error_self';

            return null;
        }

        $user = $this->findById($conn, $id);

        if ($user === null) {
            $this->error = 'error';

            return null;
        }

        $active = !$user->isActive();

        if (!(new UserRepository())->setActive($conn, $id, $active)) {
            $this->error = 'error';

            return null;
        }

        return $active;
    }

    public function authenticate(mysqli $conn, string $email, string $password): ?array
    {
        $this->error = null;
        $row = (new UserRepository())->findByEmail($conn, strtolower(trim($email)));

        if ($row === null || !hash_equals($row['password_hash'], md5($password))) {
            $this->error = 'error_login';

            return null;
        }

        if ($row['active'] !== 1) {
            $this->error = 'error_inactive';

            return null;
        }

        return $row;
    }

    private function persist(UserEntity $user, UserRepository $repository, bool $requirePassword, mysqli $conn): bool
    {
        $this->error = $user->validationError($requirePassword);

        if ($this->error !== null) {
            return false;
        }

        $excludeId = $user->getId() ?? 0;
        $sameEmail = $repository->findByEmail($conn, $user->getEmail());

        if ($sameEmail !== null && $sameEmail['id'] !== $excludeId) {
            $this->error = 'error_duplicate_email';

            return false;
        }

        try {
            if ($user->getId() === null) {
                return $repository->insert($conn, $user->toArray());
            }

            return $repository->update($conn, $user->getId(), $user->toArray());
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() !== 1062) {
                throw $e;
            }

            $this->error = 'error_duplicate_email';

            return false;
        }
    }

    private function toEntity(array $row): UserEntity
    {
        $user = new UserEntity();
        $user->fill($row);

        return $user;
    }
}
