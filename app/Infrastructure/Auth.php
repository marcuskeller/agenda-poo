<?php

final class Auth
{
    public const LOGIN_URL = '../Auth/AuthController.php?action=login';

    public static function login(array $user): void
    {
        Auth::start();
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
        Auth::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function user(): ?array
    {
        Auth::start();

        return $_SESSION['user'] ?? null;
    }

    public static function mustBe(string $type): array
    {
        $user = Auth::user();

        if ($user === null) {
            Auth::redirect(Auth::LOGIN_URL);
        }

        if ($user['type'] !== $type) {
            Auth::redirect(Auth::home($user['type']));
        }

        return $user;
    }

    public static function home(string $type): string
    {
        return $type === 'ADMIN'
            ? '../Users/UserController.php?action=list'
            : '../Contacts/ContactController.php?action=list';
    }

    private static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private static function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
