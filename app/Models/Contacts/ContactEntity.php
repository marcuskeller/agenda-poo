<?php

final class ContactEntity
{
    private $id = null;
    private int $userId = 1;
    private string $name = '';
    private string $phone = '';
    private $email = null;
    private $createdAt = null;
    private ?string $ownerName = null;
    private ?string $ownerEmail = null;

    public function fill(array $data, int $userId = 1): void
    {
        $email = trim((string) ($data['email'] ?? ''));

        $this->ownerName = $data['owner_name'] ?? null;
        $this->ownerEmail = $data['owner_email'] ?? null;
        $this->id = isset($data['id']) && (int) $data['id'] > 0 ? (int) $data['id'] : null;
        $this->userId = $userId;
        $this->name = trim((string) ($data['name'] ?? ''));
        $this->phone = trim((string) ($data['phone'] ?? ''));
        $this->email = $email === '' ? null : $email;
        $this->createdAt = $data['created_at'] ?? null;
    }

    public function validate(): bool
    {
        return $this->validationError() === null;
    }

    public function validationError(): ?string
    {
        if (!$this->validateName()) {
            return 'error_name';
        }

        if (!$this->validatePhone()) {
            return 'error_phone';
        }

        if (!$this->validateEmail()) {
            return 'error_email';
        }

        return null;
    }

    private function validatePhone(): bool
    {
        if (preg_match('/^\+?[\d\s()\-]+$/', $this->phone) !== 1) {
            return false;
        }

        $digits = strlen(preg_replace('/\D/', '', $this->phone));

        return $digits >= 10 && $digits <= 13;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function getOwnerEmail(): ?string
    {
        return $this->ownerEmail;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'created_at' => $this->createdAt,
        ];
    }

    private function validateName(): bool
    {
        return $this->name !== '';
    }

    private function validateEmail(): bool
    {
        return $this->email === null || filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
