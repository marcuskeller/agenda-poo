<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contato</title>
</head>
<body>
    <h1>Editar Contato</h1>

    <form action="atualizar.php" method="post">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($contato->getNome()); ?>">
        </div>

        <div>
            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($contato->getTelefone()); ?>">
        </div>

        <div>
            <label>Email</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($contato->getEmail()); ?>">
        </div>

        <input type="hidden" name="id" value="<?php echo $contato->getId(); ?>">

        <div>
            <input type="submit" value="Atualizar">
            <a href="index.php">Cancelar</a>
        </div>
    </form>
</body>
</html>
