<?php
// Incluir arquivo de conexão
require_once 'config/database.php';

// Inicializar variáveis
$nome = $telefone = $email = "";
$nome_err = $telefone_err = $email_err = "";

// Processar dados do formulário quando for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validar nome
    if (empty(trim($_POST["nome"]))) {
        $nome_err = "Por favor, informe um nome.";
    } else {
        $nome = trim($_POST["nome"]);
    }
    
    // Validar telefone
    if (empty(trim($_POST["telefone"]))) {
        $telefone_err = "Por favor, informe um telefone.";
    } else {
        $telefone = trim($_POST["telefone"]);
    }
    
    // Validar email (opcional)
    if (!empty(trim($_POST["email"]))) {
        if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email_err = "Por favor, informe um email válido.";
        } else {
            $email = trim($_POST["email"]);
        }
    }
    
    // Verificar erros de entrada antes de inserir no banco de dados
    if (empty($nome_err) && empty($telefone_err) && empty($email_err)) {
        
        // Preparar declaração de inserção
        $sql = "INSERT INTO contatos (nome, telefone, email) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Vincular variáveis à declaração preparada como parâmetros
            mysqli_stmt_bind_param($stmt, "sss", $param_nome, $param_telefone, $param_email);
            
            // Definir parâmetros
            $param_nome = $nome;
            $param_telefone = $telefone;
            $param_email = $email;
            
            // Tentar executar a declaração preparada
            if (mysqli_stmt_execute($stmt)) {
                // Registros criados com sucesso. Redirecionar para a página principal
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            
            // Fechar declaração
            mysqli_stmt_close($stmt);
        }
    }
    
    // Fechar conexão
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Contato</title>
</head>
<body>
    <h1>Adicionar Novo Contato</h1>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo $nome; ?>">
            <span><?php echo $nome_err; ?></span>
        </div>
        
        <div>
            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo $telefone; ?>">
            <span><?php echo $telefone_err; ?></span>
        </div>
        
        <div>
            <label>Email</label>
            <input type="text" name="email" value="<?php echo $email; ?>">
            <span><?php echo $email_err; ?></span>
        </div>
        
        <div>
            <input type="submit" value="Adicionar">
            <a href="index.php">Cancelar</a>
        </div>
    </form>
</body>
</html>