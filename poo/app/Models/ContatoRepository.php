<?php

/**
 * Implementará o contrato de persistência usando mysqli e declarações preparadas.
 */
class ContatoRepository implements ContatoRepositoryInterface
{
    public function listarTodos(): array
    {
        throw new LogicException('Implementação pendente.');
    }

    public function buscarPorId(int $id): ?Contato
    {
        throw new LogicException('Implementação pendente.');
    }

    public function inserir(Contato $contato): bool
    {
        throw new LogicException('Implementação pendente.');
    }

    public function atualizar(Contato $contato): bool
    {
        throw new LogicException('Implementação pendente.');
    }

    public function excluir(int $id): bool
    {
        throw new LogicException('Implementação pendente.');
    }
}