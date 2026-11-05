<?php
// Incluir arquivo de conexão
require_once 'config/database.php';

// Processar dados do formulário quando for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar ID
    if (!isset($_POST["id"]) || empty(trim($_POST["id"]))) {
        header("location: error.php");
        exit();
    }
    
    // Inicializar variáveis
    $id = trim($_POST["id"]);
    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);
    
    // Validar campos obrigatórios
    if (empty($nome) || empty($telefone)) {
        header("location: edit.php?id=" . $id . "&error=empty_fields");
        exit();
    }
    
    // Preparar declaração de atualização
    $sql = "UPDATE contatos SET nome = ?, telefone = ?, email = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular variáveis à declaração preparada como parâmetros
        mysqli_stmt_bind_param($stmt, "sssi", $param_nome, $param_telefone, $param_email, $param_id);
        
        // Definir parâmetros
        $param_nome = $nome;
        $param_telefone = $telefone;
        $param_email = $email;
        $param_id = $id;
        
        // Tentar executar a declaração preparada
        if (mysqli_stmt_execute($stmt)) {
            // Registros atualizados com sucesso. Redirecionar para a página principal
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
    // Se não for uma solicitação POST, redirecionar para a página principal
    header("location: index.php");
    exit();
}
?>