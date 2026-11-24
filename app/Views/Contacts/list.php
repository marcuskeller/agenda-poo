<?php

$acessoDireto = realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__;

if ($acessoDireto) {
    require_once __DIR__ . '/../../Infrastructure/ConfigDatabase.php';
    require_once __DIR__ . '/../../Models/Contacts/ContactRepository.php';
    $contatos = (new ContactRepository())->listAll($conn);
}

$e = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

$total = count($contatos);

$mensagens = [
    'created' => 'Contato criado com sucesso.',
    'updated' => 'Contato atualizado com sucesso.',
    'deleted' => 'Contato excluído com sucesso.',
    'error' => 'Não foi possível concluir a operação. Verifique os dados e tente novamente.',
];
$aviso = $mensagens[$_GET['msg'] ?? ''] ?? null;

$controladorUrl = $acessoDireto ? '../../Controller/Contacts/ContactController.php' : 'ContactController.php';
$listaUrl = $controladorUrl . '?action=list';
$criarUrl = $controladorUrl . '?action=create';
$editarUrl = $controladorUrl . '?action=edit';
$excluirUrl = $controladorUrl . '?action=delete';

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
    <title>Contatos | Agenda Telefônica</title>
    <link rel="stylesheet" href="<?= $acessoDireto ? '../style.css' : '../../Views/style.css' ?>">
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

        <?php if ($aviso !== null): ?>
            <p class="notice notice--success" role="status">
                <span class="notice-mark" aria-hidden="true">&#10003;</span>
                <?= $e($aviso) ?>
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
                    <a class="button-primary" href="<?= $e($criarUrl) ?>">Adicionar contato</a>
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
                            <?php foreach ($contatos as $contato): ?>
                                <?php
                                $id = (int) $contato->getId();
                                $data = $contato->getCreatedAt()
                                    ? date('d/m/Y \à\s H:i', strtotime($contato->getCreatedAt()))
                                    : '—';
                                ?>
                                <tr>
                                    <td class="contact-id" data-label="ID"><?= sprintf('#%03d', $id) ?></td>
                                    <td class="contact-name" data-label="Nome"><?= $e($contato->getName()) ?></td>
                                    <td data-label="Telefone"><?= $e($contato->getPhone()) ?></td>
                                    <td class="contact-email" data-label="E-mail"><?= $contato->getEmail() !== null ? $e($contato->getEmail()) : '—' ?></td>
                                    <td class="contact-date" data-label="Cadastro"><?= $e($data) ?></td>
                                    <td data-label="Ações">
                                        <div class="row-actions">
                                        <a class="row-action" href="<?= $e($editarUrl) ?>&amp;id=<?= $id ?>">Editar</a>
                                        <a class="row-action row-action--delete" href="<?= $e($excluirUrl) ?>&amp;id=<?= $id ?>">Excluir</a>
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
