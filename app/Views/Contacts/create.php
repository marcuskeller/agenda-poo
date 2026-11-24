<?php

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
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
            <p class="form-note">* Campos obrigatórios</p>

            <?php if (!empty($error)): ?>
                <p class="notice notice--error" role="alert">
                    <span class="notice-mark" aria-hidden="true">!</span>
                    <?= $e($error) ?>
                </p>
            <?php endif; ?>

            <form action="ContactController.php?action=create" method="post">
                <div class="form-grid">
                    <div class="field field--wide">
                        <label class="field-label" for="name">Nome <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="name" name="name" type="text" maxlength="100" autocomplete="name" required value="<?= $e($contact->getName()) ?>" placeholder="Nome completo">
                        <p class="field-hint">Máximo de 100 caracteres</p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="phone">Telefone <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="phone" name="phone" type="tel" maxlength="20" autocomplete="tel" required value="<?= $e($contact->getPhone()) ?>" placeholder="(00) 00000-0000">
                        <p class="field-hint">Máximo de 20 caracteres</p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="email">E-mail (opcional)</label>
                        <input class="field-input" id="email" name="email" type="email" maxlength="100" autocomplete="email" value="<?= $e($contact->getEmail()) ?>" placeholder="nome@exemplo.com">
                        <p class="field-hint">Opcional · máximo de 100 caracteres</p>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
                    <div class="form-action-group">
                        <a class="button-link" href="ContactController.php?action=list">Cancelar</a>
                        <button class="button-primary" type="submit">Salvar contato</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
