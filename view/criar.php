<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Contato</title>
</head>
<body>
    <h1>Adicionar Novo Contato</h1>

    <form action="criar.php" method="post">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($contato->getNome()); ?>">
            <span><?php echo $contato->getErros()['nome'] ?? ''; ?></span>
        </div>

        <div>
            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($contato->getTelefone()); ?>">
            <span><?php echo $contato->getErros()['telefone'] ?? ''; ?></span>
        </div>

        <div>
            <label>Email</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($contato->getEmail()); ?>">
            <span><?php echo $contato->getErros()['email'] ?? ''; ?></span>
        </div>

        <div>
            <input type="submit" value="Adicionar">
            <a href="index.php">Cancelar</a>
        </div>
    </form>
</body>
</html>
