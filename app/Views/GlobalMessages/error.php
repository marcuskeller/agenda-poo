<?php

$brandHref = '../../Controller/Contacts/ContactController.php?action=list';
$headerCaption = 'Organização pessoal / 04';
$headerLinkHref = '../../Controller/Contacts/ContactController.php?action=list';
$headerLinkText = 'Todos os contatos';
$showPlus = false;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro | Agenda Telefônica</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php require_once __DIR__ . '/../Components/navbar.php'; ?>

    <main class="page-shell form-shell">
        <section class="surface error-surface" aria-labelledby="page-title">
            <p class="modal-marker">! Atenção</p>
            <h1 id="page-title">Ocorreu um erro</h1>
            <p class="error-copy">Não foi possível concluir a operação. Verifique os dados informados e tente novamente.</p>
            <a class="button-primary" href="../../Controller/Contacts/ContactController.php?action=list">Voltar para contatos</a>
        </section>
    </main>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>
</body>
</html>
