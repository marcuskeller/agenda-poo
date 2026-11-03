<?php

require_once __DIR__ . '/IContatoRepository.php';
require_once __DIR__ . '/Contato.php';
require_once __DIR__ . '/../config/database.php';

// Implementação do acesso a dados: só sabe conversar com a tabela "contatos".
class ContatoRepository implements IContatoRepository
{
    public function salvar(Contato $contato): bool
    {
        $conexao = Database::getConexao();

        $sql = "INSERT INTO contatos (nome, telefone, email) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sql);

        $nome = $contato->getNome();
        $telefone = $contato->getTelefone();
        $email = $contato->getEmail();

        mysqli_stmt_bind_param($stmt, "sss", $nome, $telefone, $email);
        $sucesso = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $sucesso;
    }

    public function buscarTodos(): array
    {
        $conexao = Database::getConexao();

        $sql = "SELECT * FROM contatos ORDER BY nome ASC";
        $result = mysqli_query($conexao, $sql);

        $contatos = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $contato = new Contato();
            $contato->setId((int) $row['id']);
            $contato->setNome($row['nome']);
            $contato->setTelefone($row['telefone']);
            $contato->setEmail($row['email']);
            $contato->setDataCadastro($row['data_cadastro']);

            $contatos[] = $contato;
        }

        return $contatos;
    }

    public function buscarPorId(int $id): ?Contato
    {
        $conexao = Database::getConexao();

        $sql = "SELECT * FROM contatos WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$row) {
            return null;
        }

        $contato = new Contato();
        $contato->setId((int) $row['id']);
        $contato->setNome($row['nome']);
        $contato->setTelefone($row['telefone']);
        $contato->setEmail($row['email']);
        $contato->setDataCadastro($row['data_cadastro']);

        return $contato;
    }

    public function atualizar(Contato $contato): bool
    {
        $conexao = Database::getConexao();

        $sql = "UPDATE contatos SET nome = ?, telefone = ?, email = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);

        $nome = $contato->getNome();
        $telefone = $contato->getTelefone();
        $email = $contato->getEmail();
        $id = $contato->getId();

        mysqli_stmt_bind_param($stmt, "sssi", $nome, $telefone, $email, $id);
        $sucesso = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $sucesso;
    }

    public function excluir(int $id): bool
    {
        $conexao = Database::getConexao();

        $sql = "DELETE FROM contatos WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $sucesso = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $sucesso;
    }
}
