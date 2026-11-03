<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Telefônica</title>
</head>
<body>
    <h1>Agenda Telefônica</h1>

    <a href="criar.php">Adicionar Novo Contato</a>

    <h2>Lista de Contatos</h2>

    <?php if (count($contatos) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Data de Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contatos as $contato): ?>
                    <tr>
                        <td><?php echo $contato->getId(); ?></td>
                        <td><?php echo htmlspecialchars($contato->getNome()); ?></td>
                        <td><?php echo htmlspecialchars($contato->getTelefone()); ?></td>
                        <td><?php echo htmlspecialchars($contato->getEmail()); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($contato->getDataCadastro())); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $contato->getId(); ?>">Editar</a> |
                            <a href="excluir.php?id=<?php echo $contato->getId(); ?>" onclick="return confirm('Tem certeza que deseja excluir este contato?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum contato encontrado.</p>
    <?php endif; ?>
</body>
</html>
