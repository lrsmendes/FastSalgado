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
        <div class="logo"><a href="#">FastImóveis</a></div>
        <ul class="menu">
            <li><a href="destaques.html">Destaques</a></li>
        </ul>
        <div class="header-btn flex">
            <button class="btn login-btn" onclick="window.location.href='../php/login.php'">Login</button>
            <button class="btn signup-btn" onclick="window.location.href='../php/registro.php'">Registro</button>
        </div>
    </header>

    <!-- slider -->

    <div class="slider">
        <!-- list Items -->
        <div class="list">
            <?php
            // Loop através dos resultados
            $index = 0;
            while ($imovel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $activeClass = $index === $activeIndex ? "active" : ""; // Adiciona a classe 'active' se o índice for igual ao item ativo
                echo "
                <div class=\"item $activeClass\">
                    <img src=\"getImage.php?id={$imovel['id']}\"> 
                    <div class=\"content\">
                        <p>{$imovel['endereco']}</p>
                        <h2>{$imovel['categoria']}</h2>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, neque?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum, ex.
                        </p>
                    </div>
                </div>
                ";
                $index++;
            }
            ?>

        </div>
        <!-- button arrows -->
        <div class="arrows">
            <button id="prev"><<</button>
            <button id="next">></button>
        </div>
        <!-- thumbnail -->
        <div class="thumbnail">
            <?php
            // Volte para o início para reutilizar o resultado da consulta
            $stmt->execute();

            // Loop através dos resultados para mostrar as miniaturas
            $index = 0;
            while ($imovel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $activeClass = $index === $activeIndex ? "active" : ""; // Adiciona a classe 'active' se o índice for igual ao item ativo
                echo "
                <div class=\"item $activeClass\">
                    <img src=\"../php/{$imovel['foto']}\">
                    <div class=\"content\">
                        <button class=\"btn login-btn\" onclick=\"window.location.href='property.php?id={$imovel['id']}'\">Mais</button>
                    </div>
                </div>
                ";
                $index++;
            }

            // Fecha a conexão
            $conn = null;
            ?>
        </div>
    </div>

    <script src="index.js"></script>
</body>
</html>
