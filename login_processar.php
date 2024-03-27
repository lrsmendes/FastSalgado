<?php
$email = $_REQUEST["email"];
$senha = $_REQUEST["senha"];

try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $verificarUsuario = "SELECT * FROM usuarios WHERE email=? AND senha=?";
    $stmt = $conn->prepare($verificarUsuario);
    $stmt->execute([$email, $senha]);
    $row = $stmt->fetch();

    if ($row) {
        $nomeUsuario = $row["nome"];
        session_start();
        $_SESSION["nomeUsuario"] = $nomeUsuario;

        // Verifica se é conta de administrador
        if ($email === "adminbr@gmail.com") {
            header("Location: painel.php");
        } else {
            header("Location: painel.php");
        }
    } else {
        echo "Login falhou. Verifique suas credenciais. <a href='index.php'>Tentar novamente</a>";
    }

    $stmt = null;
    $conn = null;
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
