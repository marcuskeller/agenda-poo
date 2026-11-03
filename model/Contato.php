<?php

// Representa um contato e sabe validar seus próprios dados.
class Contato
{
    private ?int $id = null;
    private string $nome = "";
    private string $telefone = "";
    private string $email = "";
    private ?string $dataCadastro = null;

    private array $erros = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = trim($nome);
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): void
    {
        $this->telefone = trim($telefone);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }

    public function getDataCadastro(): ?string
    {
        return $this->dataCadastro;
    }

    public function setDataCadastro(string $dataCadastro): void
    {
        $this->dataCadastro = $dataCadastro;
    }

    public function getErros(): array
    {
        return $this->erros;
    }

    public function validar(): bool
    {
        $this->erros = [];

        if (empty($this->nome)) {
            $this->erros['nome'] = "Por favor, informe um nome.";
        }

        if (empty($this->telefone)) {
            $this->erros['telefone'] = "Por favor, informe um telefone.";
        }

        if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->erros['email'] = "Por favor, informe um email válido.";
        }

        return empty($this->erros);
    }
}
