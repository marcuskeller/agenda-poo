<?php

$brandHref = '../listContats/index.php';
$headerCaption = 'Organização pessoal / 02';
$headerLinkHref = '../listContats/index.php';
$headerLinkText = 'Todos os contatos';
$showPlus = false;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar contato | Agenda Telefônica</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
<?php require_once __DIR__ . '/../../Components/navbar.php'; ?>

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

            <form action="../../../Controller/Users/UsersController.php" method="post">
                
                <input type="hidden" name="action" value="create">
<div class="form-grid">
                    <div class="field field--wide">
                        <label class="field-label" for="nome">Nome <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="nome" name="nome" type="text" maxlength="100" autocomplete="name" required placeholder="Nome completo">
                        <p class="field-hint">Máximo de 100 caracteres</p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="telefone">Telefone <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="telefone" name="telefone" type="tel" maxlength="20" autocomplete="tel" required placeholder="(00) 00000-0000">
                        <p class="field-hint">Máximo de 20 caracteres</p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="email">E-mail (opcional)</label>
                        <input class="field-input" id="email" name="email" type="email" maxlength="100" autocomplete="email" placeholder="nome@exemplo.com">
                        <p class="field-hint">Opcional · máximo de 100 caracteres</p>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
                    <div class="form-action-group">
                        <a class="button-link" href="../listContats/index.php">Cancelar</a>
                        <button class="button-primary" type="submit">Salvar contato</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

<?php require_once __DIR__ . '/../../Components/footer.php'; ?>
</body>
</html>
