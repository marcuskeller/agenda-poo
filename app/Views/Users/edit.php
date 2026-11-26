<?php

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$listUrl = 'UserController.php?action=list';
$editUrl = 'UserController.php?action=edit';

$brandHref = $listUrl;
$headerLinkHref = $listUrl;
$headerLinkText = 'Todos os usuários';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuário | Agenda Telefônica</title>
    <link rel="stylesheet" href="../../Views/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

    <main class="page-shell form-shell">
        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Atualização</p>
                <h1 id="page-title">Editar Usuário</h1>
            </div>
        </section>

        <section class="surface form-surface" aria-label="Dados do usuário">
            <?php if (!empty($error)): ?>
                <p class="notice notice--error" role="alert">
                    <span class="notice-mark" aria-hidden="true">!</span>
                    <?= $e($error) ?>
                </p>
            <?php endif; ?>

            <form action="<?= $e($editUrl) ?>" method="post">
                <input type="hidden" name="id" value="<?= (int) $user->getId() ?>">

                <div class="form-grid">
                    <div class="field field--wide">
                        <label class="field-label" for="name">Nome <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="name" name="name" type="text" maxlength="100" required value="<?= $e($user->getName()) ?>">
                    </div>
                    <div class="field field--wide">
                        <label class="field-label" for="email">E-mail <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="email" name="email" type="email" maxlength="100" required value="<?= $e($user->getEmail()) ?>">
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
                    <div class="form-action-group">
                        <a class="button-secondary" href="<?= $e($listUrl) ?>">Cancelar</a>
                        <button class="button-primary" type="submit">Salvar alterações</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
