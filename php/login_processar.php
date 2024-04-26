<?php
$email = $_REQUEST["email"];
$senha = $_REQUEST["senha"];

try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $verificarUsuario = "SELECT id FROM usuarios WHERE email=? AND senha=?";
    $stmt = $conn->prepare($verificarUsuario);
    $stmt->execute([$email, $senha]);
    $row = $stmt->fetch();

    if ($row) {
        $userId = $row["id"]; // Recupera o ID do usuário

        session_start();
        $_SESSION["userId"] = $userId; // Armazena o ID do usuário na sessão

        // Consulta para verificar se o usuário é um administrador
        $query = "SELECT isAdmin FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $isAdmin = $row["isAdmin"]; // Obtém o status de administrador do usuário

            // Verifica se o usuário é administrador e redireciona para o painel apropriado
            if ($isAdmin) {
                header("Location: painel.php");
            } else {
                header("Location: painel.php");
            }
        } else {
            // Se o status de administrador não puder ser verificado, redireciona para a página de erro
            header("Location: error.php");
        }
    } else {
        echo "Login falhou. Verifique suas credenciais. <a href='login.php'>Tentar novamente</a>";
    }

    $stmt = null;
    $conn = null;
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
