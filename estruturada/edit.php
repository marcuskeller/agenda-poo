<?php
// Incluir arquivo de conexão
require_once 'config/database.php';

// Verificar se o ID foi passado
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Obter parâmetro da URL
    $id = trim($_GET["id"]);
    
    // Preparar uma declaração select
    $sql = "SELECT * FROM contatos WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular variáveis à declaração preparada como parâmetros
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Definir parâmetros
        $param_id = $id;
        
        // Tentar executar a declaração preparada
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                // Buscar linha de resultado como um array associativo
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Recuperar valores de campos individuais
                $nome = $row["nome"];
                $telefone = $row["telefone"];
                $email = $row["email"];
            } else {
                // URL não contém ID válido
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
        
        // Fechar declaração
        mysqli_stmt_close($stmt);
    }
    
    // Fechar conexão
    mysqli_close($conn);
} else {
    // URL não contém parâmetro de ID
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contato</title>
</head>
<body>
    <h1>Editar Contato</h1>
    
    <form action="update.php" method="post">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo $nome; ?>">
        </div>
        
        <div>
            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo $telefone; ?>">
        </div>
        
        <div>
            <label>Email</label>
            <input type="text" name="email" value="<?php echo $email; ?>">
        </div>
        
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div>
            <input type="submit" value="Atualizar">
            <a href="index.php">Cancelar</a>
        </div>
    </form>
</body>
</html>