<?php

final class Auth
{
    public const LOGIN_URL = '../Auth/AuthController.php?action=login';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(array $user): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'type' => $user['type'],
        ];
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function user(): ?array
    {
        self::start();

        return $_SESSION['user'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return (self::user()['type'] ?? null) === 'ADMIN';
    }

    public static function requireUser(): array
    {
        $user = self::user();

        if ($user === null) {
            self::redirect(self::LOGIN_URL);
        }

        if ($user['type'] === 'ADMIN') {
            self::redirect('../Users/UserController.php?action=list');
        }

        return $user;
    }

    public static function requireAdmin(): array
    {
        $user = self::user();

        if ($user === null) {
            self::redirect(self::LOGIN_URL);
        }

        if ($user['type'] !== 'ADMIN') {
            self::redirect('../Contacts/ContactController.php?action=list');
        }

        return $user;
    }

    private static function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
