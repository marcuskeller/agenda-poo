<?php

class Validator {

    private array $errors = [];

    public function required(string $field, $value, string $message): void
    {
        if (trim((string) $value) === '') {
            $this->errors[$field] = $message;
        }
    }

    public function verifyEmail(string $field, $value, string $message): void
    {
        $value = trim((string) $value);

        if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[$field] = $message;
        }
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
