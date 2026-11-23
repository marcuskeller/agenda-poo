<?php

require_once __DIR__ . '/../../infra/ConfigDatabase.php';
require_once __DIR__ . '/../../Models/Users/edit.php';

$e = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

$id = (int) ($_GET['id'] ?? 0);
$contato = buscarContato($conn, $id);

if ($contato === null) {
    header('Location: ../GlobalMessages/erro.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar contato | Agenda Telefônica</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="../HomePage/index.php" aria-label="Agenda, ir para contatos">
            <span class="brand-mark" aria-hidden="true">A</span>
            <span class="brand-copy">
                <span class="brand-name">agenda</span>
                <span class="brand-caption">Caderno de contatos</span>
            </span>
        </a>
        <span class="header-caption">Organização pessoal / 03</span>
        <a class="header-link" href="../HomePage/index.php">Todos os contatos</a>
    </header>

    <main class="page-shell form-shell">
        <section class="page-intro" aria-labelledby="page-title">
            <div>
                <p class="eyebrow">Atualização</p>
                <h1 id="page-title">Editar Contato</h1>
            </div>
        </section>

        <section class="surface form-surface" aria-label="Dados do contato">
            <p class="form-section-label">Informações pessoais</p>
            <p class="form-note">* Campos obrigatórios</p>

            <form action="../../Controller/Users/UsersController.php" method="post">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= (int) $contato['id'] ?>">

                <div class="form-grid">
                    <div class="field field--wide">
                        <label class="field-label" for="nome">Nome <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="nome" name="nome" type="text" maxlength="100" autocomplete="name" required value="<?= $e($contato['nome']) ?>">
                        <p class="field-hint">Máximo de 100 caracteres</p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="telefone">Telefone <span class="required-mark" aria-hidden="true">*</span></label>
                        <input class="field-input" id="telefone" name="telefone" type="tel" maxlength="20" autocomplete="tel" required value="<?= $e($contato['telefone']) ?>">
                        <p class="field-hint">Máximo de 20 caracteres</p>
                    </div>
                    <div class="field">
                        <label class="field-label" for="email">E-mail (opcional)</label>
                        <input class="field-input" id="email" name="email" type="email" maxlength="100" autocomplete="email" value="<?= $e($contato['email']) ?>">
                        <p class="field-hint">Opcional · máximo de 100 caracteres</p>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
                    <div class="form-action-group">
                        <a class="button-link" href="../HomePage/index.php">Cancelar</a>
                        <button class="button-primary" type="submit">Salvar alterações</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        <span>agenda / Caderno de contatos</span>
        <span>Organização pessoal</span>
    </footer>
</body>
</html>
