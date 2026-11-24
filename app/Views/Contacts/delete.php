<?php

$e = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

$total = count($contatos);

$listaUrl = 'ContactController.php?action=list';
$criarUrl = 'ContactController.php?action=create';
$editarUrl = 'ContactController.php?action=edit';
$excluirUrl = 'ContactController.php?action=delete';

$brandHref = $listaUrl;
$headerCaption = 'Organização pessoal / 01';
$headerLinkHref = $criarUrl;
$headerLinkText = 'Novo contato';
$showPlus = true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir contato | Agenda Telefônica</title>
    <link rel="stylesheet" href="../../Views/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

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
                            $linhaId = (int) $linha->getId();
                            $data = $linha->getCreatedAt()
                                ? date('d/m/Y \à\s H:i', strtotime($linha->getCreatedAt()))
                                : '—';
                            ?>
                            <tr>
                                <td class="contact-id" data-label="ID"><?= sprintf('#%03d', $linhaId) ?></td>
                                <td class="contact-name" data-label="Nome"><?= $e($linha->getName()) ?></td>
                                <td data-label="Telefone"><?= $e($linha->getPhone()) ?></td>
                                <td class="contact-email" data-label="E-mail"><?= $linha->getEmail() !== null ? $e($linha->getEmail()) : '—' ?></td>
                                <td class="contact-date" data-label="Cadastro"><?= $e($data) ?></td>
                                <td data-label="Ações">
                                    <div class="row-actions">
                                        <a class="row-action" href="<?= $e($editarUrl) ?>?id=<?= $linhaId ?>">Editar</a>
                                        <a class="row-action row-action--delete" href="<?= $e($excluirUrl) ?>?id=<?= $linhaId ?>">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div class="modal-overlay">
        <a class="modal-scrim" href="<?= $e($listaUrl) ?>" aria-label="Cancelar exclusão"></a>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-desc">
            <p class="modal-marker">! Confirmar exclusão</p>
            <h2 class="modal-title" id="modal-title">Excluir contato?</h2>
            <p class="modal-copy" id="modal-desc">Deseja excluir <?= $e($contato->getName()) ?> da sua agenda?</p>
            <p class="modal-copy modal-copy--note">Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <a class="button-secondary" href="<?= $e($listaUrl) ?>">Cancelar</a>
                <form action="<?= $e($excluirUrl) ?>" method="post">
                    <input type="hidden" name="id" value="<?= (int) $contato->getId() ?>">
                    <button class="button-danger-outline" type="submit">Confirmar exclusão</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
