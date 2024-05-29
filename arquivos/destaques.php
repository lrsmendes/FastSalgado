<?php
// Conexão com o banco de dados
$dbHost = "localhost";
$dbName = "fastimoveis";
$dbUser = "root";
$dbPass = "";

try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para buscar todos os imóveis
    $stmt = $conn->query("SELECT * FROM imoveis");
 

    // Definir a variável para acompanhar o índice do item ativo
    $activeIndex = 0;

} catch(PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastImóveis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- header -->
    <header>
        <div class="logo"><a href="index.php">FastImóveis</a></div>
        <ul class="menu">
            <li><a href="#">Destaques</a></li>
        </ul>
        <div class="header-btn flex">
            <button class="btn login-btn" onclick="window.location.href='../php/login.php'">Login</button>
            <button class="btn signup-btn" onclick="window.location.href='../php/registro.php'">Registro</button>
        </div>
    </header>

    <!-- slider -->
    <div class="slider">
        <div class="list">
            <?php
            // Loop através dos resultados
            $index = 0;
            while ($imovel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $activeClass = $index === $activeIndex ? "active" : "";
                echo "
                <div class=\"item $activeClass\" data-index=\"$index\">
                    <img src=\"../php/{$imovel['foto']}\">
                    <div class=\"content\">
                        <p>{$imovel['categoria']}</p>
                        <h2>{$imovel['status']}</h2>
                        <p>
                        {$imovel['endereco']} {$imovel['cidade']}
                        </p>
                        <p>{$imovel['descricao']}</p>
                    </div>
                </div>
                ";
                $index++;
            }
            ?>
        </div>
        <div class="arrows">
            <button id="prev"><</button>
            <button id="next">></button>
        </div>
        <div class="thumbnail">
            <?php
            // Volte para o início para reutilizar o resultado da consulta
            $stmt->execute();

            // Loop através dos resultados para mostrar as miniaturas
            $index = 0;
            while ($imovel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $activeClass = $index === $activeIndex ? "active" : "";
                echo "
                <div class=\"item $activeClass\" data-index=\"$index\">
                    <img src=\"../php/{$imovel['foto']}\">
                    <div class=\"content\">
                        <button class=\"btn login-btn\" onclick=\"window.location.href='property.php?id={$imovel['id']}'\">Mais</button>
                    </div>
                </div>
                ";
                $index++;
            }
            ?>
        </div>
    </div>

    <script src="index.js"></script>
</body>
</html>
