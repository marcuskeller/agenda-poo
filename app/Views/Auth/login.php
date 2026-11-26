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
    <title>Entrar | Agenda Telefônica</title>
    <link rel="stylesheet" href="../../Views/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

    <main class="page-shell form-shell">
        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Acesso</p>
                <h1 id="page-title">Entrar</h1>
            </div>
        </section>

        <section class="surface form-surface" aria-label="Login">
            <?php if (!empty($notice)): ?>
                <p class="notice notice--success" role="status">
                    <span class="notice-mark" aria-hidden="true">&#10003;</span>
                    <?= $e($notice) ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <p class="notice notice--error" role="alert">
                    <span class="notice-mark" aria-hidden="true">!</span>
                    <?= $e($error) ?>
                </p>
            <?php endif; ?>

            <form action="AuthController.php?action=login" method="post">
                <div class="form-grid">
                    <div class="field field--wide">
                        <label class="field-label" for="email">E-mail</label>
                        <input class="field-input" id="email" name="email" type="email" maxlength="100" autocomplete="email" required value="<?= $e($email) ?>">
                    </div>
                    <div class="field field--wide">
                        <label class="field-label" for="password">Senha</label>
                        <input class="field-input" id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <div class="form-action-group">
                        <button class="button-primary" type="submit">Entrar</button>
                    </div>
                </div>
            </form>

            <p class="auth-links">Ainda não tem conta? <a href="AuthController.php?action=register">Cadastre-se</a></p>
        </section>
    </main>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
