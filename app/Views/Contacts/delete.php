<?php

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$total = count($contacts);

$listUrl = 'ContactController.php?action=list';
$createUrl = 'ContactController.php?action=create';
$editUrl = 'ContactController.php?action=edit';
$deleteUrl = 'ContactController.php?action=delete';

$brandHref = $listUrl;
$hideHeaderLink = true;
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
        <section class="welcome" aria-label="Boas-vindas">
            <p class="eyebrow">Boas-vindas</p>
            <p class="welcome-text">Bem-vindo(a), <strong><?= $e(Auth::user()['name'] ?? '') ?></strong>!</p>
        </section>

        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Visão geral</p>
                <h1 id="page-title">Contatos <span class="count-mark"><?= $total ?></span></h1>
            </div>
        </section>

        <section class="surface" aria-labelledby="contacts-title">
            <div class="surface-heading">
                <h2 class="surface-title" id="contacts-title">Sua agenda</h2>
                <div class="surface-actions">
                    <span class="record-count"><?= $total ?> registro<?= $total === 1 ? '' : 's' ?></span>
                    <?php if ($total > 0): ?>
                        <a class="button-primary" href="<?= $e($createUrl) ?>">Adicionar contato</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($total === 0): ?>
                <div class="empty-state">
                    <a class="empty-mark" href="<?= $e($createUrl) ?>" aria-label="Adicionar contato">+</a>
                    <h3 class="empty-title">Nenhum contato ainda</h3>
                    <p class="empty-copy">Sua agenda está vazia. Cadastre o primeiro contato para começar.</p>
                    <a class="button-primary" href="<?= $e($createUrl) ?>">Adicionar contato</a>
                </div>
            <?php else: ?>
                <div class="table-scroll">
                    <table class="contacts-table">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Telefone</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Cadastro</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $item): ?>
                                <?php
                                $itemId = (int) $item->getId();
                                $date = $item->getCreatedAt()
                                    ? date('d/m/Y à\s H:i', strtotime($item->getCreatedAt()))
                                    : '—';
                                ?>
                                <tr>
                                    <td class="contact-name" data-label="Nome"><?= $e($item->getName()) ?></td>
                                    <td data-label="Telefone"><?= $e($item->getPhone()) ?></td>
                                    <td class="contact-email" data-label="E-mail"><?= $item->getEmail() !== null ? $e($item->getEmail()) : '—' ?></td>
                                    <td class="contact-date" data-label="Cadastro"><?= $e($date) ?></td>
                                    <td data-label="Ações">
                                        <div class="row-actions">
                                            <a class="row-action" href="<?= $e($editUrl) ?>&amp;id=<?= $itemId ?>">Editar</a>
                                            <a class="row-action row-action--delete" href="<?= $e($deleteUrl) ?>&amp;id=<?= $itemId ?>">Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <div class="modal-overlay">
        <a class="modal-scrim" href="<?= $e($listUrl) ?>" aria-label="Cancelar exclusão"></a>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-desc">
            <p class="modal-marker">! Confirmar exclusão</p>
            <h2 class="modal-title" id="modal-title">Excluir contato?</h2>
            <p class="modal-copy" id="modal-desc">Deseja excluir <strong><?= $e($contact->getName()) ?></strong> da sua agenda?</p>
            <p class="modal-copy modal-copy--note">Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <a class="button-secondary" href="<?= $e($listUrl) ?>">Cancelar</a>
                <form action="<?= $e($deleteUrl) ?>" method="post">
                    <input type="hidden" name="id" value="<?= (int) $contact->getId() ?>">
                    <button class="button-danger-outline" type="submit">Confirmar exclusão</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
