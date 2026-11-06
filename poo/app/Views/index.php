<?php
// Incluir arquivo de conexão
require_once 'config/database.php';

// Consulta para selecionar todos os contatos
$sql = "SELECT * FROM contatos ORDER BY nome ASC";
$result = mysqli_query($conn, $sql);

// Verificar se há erros na consulta
if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Telefônica</title>
</head>
<body>
    <h1>Agenda Telefônica</h1>
    
    <a href="create.php">Adicionar Novo Contato</a>
    
    <h2>Lista de Contatos</h2>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
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
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['telefone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['data_cadastro'])); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>">Editar</a> | 
                            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este contato?');">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum contato encontrado.</p>
    <?php endif; ?>
    
    <?php
    // Fechar conexão
    mysqli_close($conn);
    ?>
</body>
</html>