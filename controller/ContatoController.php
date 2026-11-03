<?php

require_once __DIR__ . '/../model/ContatoRepository.php';

// Recebe as requisições, chama o Model e decide qual View exibir.
class ContatoController
{
    public function listar(): void
    {
        $repository = new ContatoRepository();
        $contatos = $repository->buscarTodos();
        require __DIR__ . '/../view/lista.php';
    }

    public function formularioCriar(): void
    {
        $contato = new Contato();
        require __DIR__ . '/../view/criar.php';
    }

    public function criar(array $dados): void
    {
        $contato = new Contato();
        $contato->setNome($dados['nome'] ?? '');
        $contato->setTelefone($dados['telefone'] ?? '');
        $contato->setEmail($dados['email'] ?? '');

        if (!$contato->validar()) {
            require __DIR__ . '/../view/criar.php';
            return;
        }

        $repository = new ContatoRepository();
        $repository->salvar($contato);
        header("Location: index.php");
        exit();
    }

    public function formularioEditar(int $id): void
    {
        $repository = new ContatoRepository();
        $contato = $repository->buscarPorId($id);

        if ($contato === null) {
            header("Location: view/erro.php");
            exit();
        }

        require __DIR__ . '/../view/editar.php';
    }

    public function atualizar(array $dados): void
    {
        if (empty($dados['id'])) {
            header("Location: view/erro.php");
            exit();
        }

        $contato = new Contato();
        $contato->setId((int) $dados['id']);
        $contato->setNome($dados['nome'] ?? '');
        $contato->setTelefone($dados['telefone'] ?? '');
        $contato->setEmail($dados['email'] ?? '');

        if (!$contato->validar()) {
            header("Location: editar.php?id=" . $contato->getId() . "&error=empty_fields");
            exit();
        }

        $repository = new ContatoRepository();
        $repository->atualizar($contato);
        header("Location: index.php");
        exit();
    }

    public function excluir(int $id): void
    {
        $repository = new ContatoRepository();
        $repository->excluir($id);
        header("Location: index.php");
        exit();
    }
}
