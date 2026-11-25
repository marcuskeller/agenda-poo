<?php

final class UserEntity
{
    private ?int $id = null;
    private string $name = '';
    private string $email = '';
    private string $password = '';
    private string $passwordConfirmation = '';
    private string $type = 'USER';
    private bool $active = true;
    private ?string $createdAt = null;

    public function fill(array $data): void
    {
        $this->id = isset($data['id']) && (int) $data['id'] > 0 ? (int) $data['id'] : null;
        $this->name = trim((string) ($data['name'] ?? ''));
        $this->email = strtolower(trim((string) ($data['email'] ?? '')));
        $this->password = (string) ($data['password'] ?? '');
        $this->passwordConfirmation = (string) ($data['password_confirmation'] ?? '');
        $this->type = ($data['type'] ?? 'USER') === 'ADMIN' ? 'ADMIN' : 'USER';
        $this->active = (bool) ($data['active'] ?? true);
        $this->createdAt = $data['created_at'] ?? null;
    }

    public function validationError(bool $requirePassword = true): ?string
    {
        if ($this->name === '') {
            return 'error_name';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            return 'error_email';
        }

        if ($requirePassword || $this->password !== '') {
            if (strlen($this->password) < 6) {
                return 'error_password';
            }

            if ($this->password !== $this->passwordConfirmation) {
                return 'error_password_confirmation';
            }
        }

        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'type' => $this->type,
            'active' => $this->active ? 1 : 0,
            'created_at' => $this->createdAt,
        ];
    }
}
