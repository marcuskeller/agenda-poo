<?php

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$brandHref = 'AuthController.php?action=login';
$hideHeaderLink = true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Agenda Telefônica</title>
    <link rel="stylesheet" href="../../Views/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

    <main class="page-shell form-shell">
        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Novo usuário</p>
                <h1 id="page-title">Criar conta</h1>
            </div>
        </section>

        <section class="surface form-surface" aria-label="Cadastro de usuário">
            <?php if (!empty($error)): ?>
                <p class="notice notice--error" role="alert">
                    <span class="notice-mark" aria-hidden="true">!</span>
                    <?= $e($error) ?>
                </p>
            <?php endif; ?>

            <form action="AuthController.php?action=register" method="post">
                <div class="form-grid">
                    <div class="field field--wide">
                        <label class="field-label" for="name">Nome <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="name" name="name" type="text" maxlength="100" autocomplete="name" required value="<?= $e($data['name']) ?>">
                    </div>
                    <div class="field field--wide">
                        <label class="field-label" for="email">E-mail <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="email" name="email" type="email" maxlength="100" autocomplete="email" required value="<?= $e($data['email']) ?>">
                    </div>
                    <div class="field">
                        <label class="field-label" for="password">Senha <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="password" name="password" type="password" minlength="6" autocomplete="new-password" required>
                        <p class="field-hint">Mínimo de 6 caracteres</p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="password_confirmation">Confirmar senha <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="password_confirmation" name="password_confirmation" type="password" minlength="6" autocomplete="new-password" required>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
                    <div class="form-action-group">
                        <a class="button-link" href="AuthController.php?action=login">Cancelar</a>
                        <button class="button-primary" type="submit">Cadastrar</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
