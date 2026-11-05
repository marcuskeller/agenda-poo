<?php
// Incluir arquivo de conexão
require_once 'config/database.php';

// Processar operação de exclusão após confirmação
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Obter parâmetro da URL
    $id = trim($_GET["id"]);
    
    // Preparar uma declaração de exclusão
    $sql = "DELETE FROM contatos WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular variáveis à declaração preparada como parâmetros
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Definir parâmetros
        $param_id = $id;
        
        // Tentar executar a declaração preparada
        if (mysqli_stmt_execute($stmt)) {
            // Registros excluídos com sucesso. Redirecionar para a página principal
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
        
        // Fechar declaração
        mysqli_stmt_close($stmt);
    }
    
    // Fechar conexão
    mysqli_close($conn);
} else {
    // Se o ID não foi fornecido, redirecionar para a página de erro
    header("location: error.php");
    exit();
}
?>