<?php

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$hideHeaderLink = true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar contato | Agenda Telefônica</title>
    <link rel="stylesheet" href="../../Views/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

    <main class="page-shell form-shell">
        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Novo registro</p>
                <h1 id="page-title">Adicionar Contato</h1>
            </div>
        </section>

        <section class="surface form-surface" aria-label="Dados do contato">
            <p class="form-section-label">Informações pessoais</p>

            <?php if (!empty($error) && $errorField === null): ?>
                <p class="notice notice--error" role="alert">
                    <span class="notice-mark" aria-hidden="true">!</span>
                    <?= $e($error) ?>
                </p>
            <?php endif; ?>

            <form action="ContactController.php?action=create" method="post" novalidate>
                <div class="form-grid">
                    <div class="field field--wide">
                        <label class="field-label" for="name">Nome</label>
                        <input class="field-input" id="name" name="name" type="text" maxlength="100" autocomplete="name" required value="<?= $e($contact->getName()) ?>" placeholder="Nome completo" <?= $errorField === 'name' && $contact->getName() !== '' ? 'aria-invalid="true"' : '' ?>>
                        <p class="field-error"><?= $errorField === 'name' && $contact->getName() !== '' ? $e($error) : 'Campo obrigatório.' ?></p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="phone">Telefone</label>
                        <input class="field-input" id="phone" name="phone" type="tel" maxlength="20" autocomplete="tel" required value="<?= $e($contact->getPhone()) ?>" placeholder="(00) 00000-0000" <?= $errorField === 'phone' && $contact->getPhone() !== '' ? 'aria-invalid="true"' : '' ?>>
                        <p class="field-error"><?= $errorField === 'phone' && $contact->getPhone() !== '' ? $e($error) : 'Campo obrigatório.' ?></p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="email">E-mail (opcional)</label>
                        <input class="field-input" id="email" name="email" type="email" maxlength="100" autocomplete="email" value="<?= $e($contact->getEmail()) ?>" placeholder="nome@exemplo.com" <?= $errorField === 'email' ? 'aria-invalid="true"' : '' ?>>
                        <p class="field-error"><?= $errorField === 'email' ? $e($error) : '' ?></p>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions form-actions--end">
                    <div class="form-action-group">
                        <a class="button-link" href="ContactController.php?action=list">Cancelar</a>
                        <button class="button-primary" type="submit">Salvar contato</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

<?php require_once __DIR__ . '/../Components/phone-mask.php'; ?>
<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
