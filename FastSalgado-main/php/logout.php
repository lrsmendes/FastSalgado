<?php
session_start(); // Iniciar a sessão

// Encerrar a sessão atual (fazer logout)
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
</head>
<body>
    <p>Você foi desconectado com sucesso. <a href="login.php">Clique aqui</a> para fazer login novamente.</p>
</body>
</html>
