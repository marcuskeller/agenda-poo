<?php

require_once __DIR__ . '/../../infra/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Users/list.php';
require_once __DIR__ . '/../../Models/Users/edit.php';

$e = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

$id = (int) ($_GET['id'] ?? 0);
$contato = buscarContato($conn, $id);

if ($contato === null) {
    header('Location: ../GlobalMessages/erro.html');
    exit;
}

$contatos = listarContatos($conn);
$total = count($contatos);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir contato | Agenda Telefônica</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="../HomePage/index.php" aria-label="Agenda, ir para contatos">
            <span class="brand-mark" aria-hidden="true">A</span>
            <span class="brand-copy">
                <span class="brand-name">agenda</span>
                <span class="brand-caption">Caderno de contatos</span>
            </span>
        </a>
        <span class="header-caption">Organização pessoal / 01</span>
        <a class="header-link" href="../CreateContact/index.html"><span class="header-link-mark" aria-hidden="true">+</span> Novo contato</a>
    </header>

    <main class="page-shell">
        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Visão geral</p>
                <h1 id="page-title">Contatos <span class="count-mark"><?= $total ?></span></h1>
            </div>
        </section>

        <section class="surface" aria-labelledby="contacts-title">
            <div class="surface-heading">
                <h2 class="surface-title" id="contacts-title">Sua agenda</h2>
                <span class="record-count"><?= $total ?> registro<?= $total === 1 ? '' : 's' ?></span>
            </div>

            <div class="table-scroll">
                <table class="contacts-table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Cadastro</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contatos as $linha): ?>
                            <?php
                            $linhaId = (int) $linha['id'];
                            $data = $linha['data_cadastro']
                                ? date('d/m/Y \à\s H:i', strtotime($linha['data_cadastro']))
                                : '—';
                            ?>
                            <tr>
                                <td class="contact-id" data-label="ID"><?= sprintf('#%03d', $linhaId) ?></td>
                                <td class="contact-name" data-label="Nome"><?= $e($linha['nome']) ?></td>
                                <td data-label="Telefone"><?= $e($linha['telefone']) ?></td>
                                <td class="contact-email" data-label="E-mail"><?= $linha['email'] !== null && $linha['email'] !== '' ? $e($linha['email']) : '—' ?></td>
                                <td class="contact-date" data-label="Cadastro"><?= $e($data) ?></td>
                                <td data-label="Ações">
                                    <div class="row-actions">
                                        <a class="row-action" href="../EditContact/index.php?id=<?= $linhaId ?>">Editar</a>
                                        <a class="row-action row-action--delete" href="index.php?id=<?= $linhaId ?>">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <span>agenda / Caderno de contatos</span>
        <span>Organização pessoal</span>
    </footer>

    <div class="modal-overlay">
        <a class="modal-scrim" href="../HomePage/index.php" aria-label="Cancelar exclusão"></a>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-desc">
            <p class="modal-marker">! Confirmar exclusão</p>
            <h2 class="modal-title" id="modal-title">Excluir contato?</h2>
            <p class="modal-copy" id="modal-desc">Deseja excluir <?= $e($contato['nome']) ?> da sua agenda?</p>
            <p class="modal-copy modal-copy--note">Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <a class="button-secondary" href="../HomePage/index.php">Cancelar</a>
                <a class="button-danger-outline" href="../../Controller/Users/UsersController.php?action=delete&amp;id=<?= (int) $contato['id'] ?>">Confirmar exclusão</a>
            </div>
        </div>
    </div>
</body>
</html>
