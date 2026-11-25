<?php

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$total = count($users);

$listUrl = 'UserController.php?action=list';
$editUrl = 'UserController.php?action=edit';
$toggleUrl = 'UserController.php?action=toggle';

$brandHref = $listUrl;
$headerCaption = 'Administração / Usuários';
$hideHeaderLink = true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários | Agenda Telefônica</title>
    <link rel="stylesheet" href="../../Views/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

    <main class="page-shell">
        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Administração</p>
                <h1 id="page-title">Usuários <span class="count-mark"><?= $total ?></span></h1>
            </div>
        </section>

        <?php if ($notice !== null): ?>
            <p class="notice <?= $noticeIsError ? 'notice--error' : 'notice--success' ?>" role="<?= $noticeIsError ? 'alert' : 'status' ?>">
                <span class="notice-mark" aria-hidden="true"><?= $noticeIsError ? '!' : '&#10003;' ?></span>
                <?= $e($notice) ?>
            </p>
        <?php endif; ?>

        <section class="surface" aria-labelledby="users-title">
            <div class="surface-heading">
                <h2 class="surface-title" id="users-title">Todos os usuários</h2>
                <span class="record-count"><?= $total ?> registro<?= $total === 1 ? '' : 's' ?></span>
            </div>

            <div class="table-scroll">
                <table class="contacts-table">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Status</th>
                            <th scope="col">Cadastro</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $item): ?>
                            <?php
                            $itemId = (int) $item->getId();
                            $date = $item->getCreatedAt()
                                ? date('d/m/Y \à\s H:i', strtotime($item->getCreatedAt()))
                                : '—';
                            ?>
                            <tr>
                                <td class="contact-name" data-label="Nome"><?= $e($item->getName()) ?></td>
                                <td class="contact-email" data-label="E-mail"><?= $e($item->getEmail()) ?></td>
                                <td data-label="Tipo"><?= $item->getType() === 'ADMIN' ? 'Administrador' : 'Usuário' ?></td>
                                <td data-label="Status">
                                    <span class="status-badge <?= $item->isActive() ? '' : 'status-badge--inactive' ?>"><?= $item->isActive() ? 'Ativo' : 'Inativo' ?></span>
                                </td>
                                <td class="contact-date" data-label="Cadastro"><?= $e($date) ?></td>
                                <td data-label="Ações">
                                    <div class="row-actions">
                                        <a class="row-action" href="<?= $e($editUrl) ?>&amp;id=<?= $itemId ?>">Editar</a>
                                        <?php if ($itemId !== $currentUserId): ?>
                                            <form action="<?= $e($toggleUrl) ?>" method="post">
                                                <input type="hidden" name="id" value="<?= $itemId ?>">
                                                <button class="row-action row-action--delete" type="submit"><?= $item->isActive() ? 'Inativar' : 'Reativar' ?></button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
