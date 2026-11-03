<?php

// Contrato do acesso a dados de contatos, independente de como é implementado.
interface IContatoRepository
{
    public function salvar(Contato $contato): bool;
    public function buscarTodos(): array;
    public function buscarPorId(int $id): ?Contato;
    public function atualizar(Contato $contato): bool;
    public function excluir(int $id): bool;
}
