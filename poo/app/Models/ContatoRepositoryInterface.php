<?php

/**
 * Definirá o contrato de persistência usado pelo controller sem acoplá-lo ao MySQL.
 */
interface ContatoRepositoryInterface
{
    public function listarTodos(): array;

    public function buscarPorId(int $id): ?Contato;

    public function inserir(Contato $contato): bool;

    public function atualizar(Contato $contato): bool;

    public function excluir(int $id): bool;
}