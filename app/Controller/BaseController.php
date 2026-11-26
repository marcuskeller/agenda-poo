<?php

abstract class BaseController
{
    protected mysqli $conn;

    protected function setConnection(mysqli $conn): void
    {
        $this->conn = $conn;
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    protected function redirectTo(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
