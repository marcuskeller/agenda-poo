<?php

$directAccess = realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__;

if ($directAccess) {
    require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
    require_once __DIR__ . '/../../Models/Contacts/ContactService.php';
    $contacts = (new ContactService())->listAll($conn);
}

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$total = count($contacts);

$messages = [
    'created' => 'Contato criado com sucesso.',
    'updated' => 'Contato atualizado com sucesso.',
    'deleted' => 'Contato excluído com sucesso.',
    'error' => 'Não foi possível concluir a operação. Verifique os dados e tente novamente.',
    'error_name' => 'Informe o nome do contato.',
    'error_phone' => 'Telefone inválido. Informe DDD e número (10 a 13 dígitos).',
    'error_email' => 'E-mail inválido. Verifique o endereço informado.',
    'error_duplicate_phone' => 'Já existe um contato cadastrado com este telefone.',
    'error_duplicate_email' => 'Já existe um contato cadastrado com este e-mail.',
];
$messageKey = $_GET['msg'] ?? '';
$notice = $messages[$messageKey] ?? null;
$noticeIsError = str_starts_with((string) $messageKey, 'error');

$controllerUrl = $directAccess ? '../../Controller/Contacts/ContactController.php' : 'ContactController.php';
$listUrl = $controllerUrl . '?action=list';
$createUrl = $controllerUrl . '?action=create';
$editUrl = $controllerUrl . '?action=edit';
$deleteUrl = $controllerUrl . '?action=delete';

$brandHref = $listUrl;
$headerCaption = 'Organização pessoal / 01';
$headerLinkHref = $createUrl;
$headerLinkText = 'Novo contato';
$showPlus = true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatos | Agenda Telefônica</title>
    <link rel="stylesheet" href="<?= $directAccess ? '../style.css' : '../../Views/style.css' ?>">
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

        <?php if ($notice !== null): ?>
            <p class="notice <?= $noticeIsError ? 'notice--error' : 'notice--success' ?>" role="<?= $noticeIsError ? 'alert' : 'status' ?>">
                <span class="notice-mark" aria-hidden="true"><?= $noticeIsError ? '!' : '&#10003;' ?></span>
                <?= $e($notice) ?>
            </p>
        <?php endif; ?>

        <section class="surface" aria-labelledby="contacts-title">
            <div class="surface-heading">
                <h2 class="surface-title" id="contacts-title">Sua agenda</h2>
                <span class="record-count"><?= $total ?> registro<?= $total === 1 ? '' : 's' ?></span>
            </div>

            <?php if ($total === 0): ?>
                <div class="empty-state">
                    <span class="empty-mark" aria-hidden="true">+</span>
                    <h3 class="empty-title">Nenhum contato ainda</h3>
                    <p class="empty-copy">Sua agenda está vazia. Cadastre o primeiro contato para começar.</p>
                    <a class="button-primary" href="<?= $e($createUrl) ?>">Adicionar contato</a>
                </div>
            <?php else: ?>
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
                            <?php foreach ($contacts as $contact): ?>
                                <?php
                                $id = (int) $contact->getId();
                                $date = $contact->getCreatedAt()
                                    ? date('d/m/Y \à\s H:i', strtotime($contact->getCreatedAt()))
                                    : '—';
                                ?>
                                <tr>
                                    <td class="contact-id" data-label="ID"><?= sprintf('#%03d', $id) ?></td>
                                    <td class="contact-name" data-label="Nome"><?= $e($contact->getName()) ?></td>
                                    <td data-label="Telefone"><?= $e($contact->getPhone()) ?></td>
                                    <td class="contact-email" data-label="E-mail"><?= $contact->getEmail() !== null ? $e($contact->getEmail()) : '—' ?></td>
                                    <td class="contact-date" data-label="Cadastro"><?= $e($date) ?></td>
                                    <td data-label="Ações">
                                        <div class="row-actions">
                                        <a class="row-action" href="<?= $e($editUrl) ?>&amp;id=<?= $id ?>">Editar</a>
                                        <a class="row-action row-action--delete" href="<?= $e($deleteUrl) ?>&amp;id=<?= $id ?>">Excluir</a>
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

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
