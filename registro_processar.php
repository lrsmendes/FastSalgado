<?php
$nome = $_REQUEST["nome"];
$email = $_REQUEST["email"];
$senha = $_REQUEST["senha"];

try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se o email já está em uso
    $verificaEmail = "SELECT * FROM usuarios WHERE email=?";
    $stmt = $conn->prepare($verificaEmail);
    $stmt->execute([$email]);
    $row = $stmt->fetch();

    if ($row) {
        echo "Email já está em uso. Escolha outro.";
    } else {
        $inserirUsuario = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($inserirUsuario);
        $stmt->execute([$nome, $email, $senha]);
        echo "Registro bem-sucedido. <a href='index.php'>Faça o login</a>";
    }

    $stmt = null;
    $conn = null;
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
