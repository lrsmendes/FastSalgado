<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit();
}

// Recupera o ID do usuário da sessão
$userId = $_SESSION["userId"];

try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter o status de administrador do usuário pelo ID
    $query = "SELECT isAdmin FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId]);

    // Recupera o resultado da consulta
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $isAdmin = $row["isAdmin"]; // Obtém o status de administrador do usuário

        // Se não for administrador, exibe uma mensagem de erro e encerra o script
        if (!$isAdmin) {
            echo "Você não tem permissão para excluir registros.";
            exit();
        }
    } else {
        echo "Erro: Não foi possível verificar o status de administrador do usuário.";
        exit();
    }

    // Se chegou até aqui, o usuário é um administrador e pode continuar com a exclusão do registro

    // Seção de lógica para excluir o registro continua aqui...

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
