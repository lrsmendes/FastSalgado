<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Imóvel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos CSS como mencionado anteriormente */
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Verifica se o ID do imóvel foi passado na URL
        if(isset($_GET['id'])) {
            // Recupera o ID do imóvel da URL
            $imovel_id = $_GET['id'];

            // Conexão com o banco de dados
            $dbHost = "localhost";
            $dbName = "fastimoveis";
            $dbUser = "root";
            $dbPass = "";

            try {
                $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Consulta SQL para buscar as informações do imóvel com base no ID
                $stmt = $conn->prepare("SELECT * FROM imoveis WHERE id = ?");
                $stmt->execute([$imovel_id]);

                // Verifica se o imóvel foi encontrado
                if($stmt->rowCount() > 0) {
                    $imovel = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Exibe as informações do imóvel
                    echo "<h1>Informações do Imóvel</h1>";
                    echo "<p>ID do Imóvel: {$imovel['id']}</p>";
                    echo "<p>Cidade: {$imovel['cidade']}</p>";
                    echo "<p>Endereço: {$imovel['endereco']}</p>";
                    echo "<p>Categoria: {$imovel['categoria']}</p>";
                    echo "<p>Preço: R$ {$imovel['preco']}</p>";
                    echo "<p>Status: {$imovel['status']}</p>";
                    // Exibe a imagem do imóvel
                    echo "<img src='../php/{$imovel['foto']}' alt='{$imovel['foto']}'>";
                } else {
                    echo "<p>Imóvel não encontrado.</p>";
                }
            } catch(PDOException $e) {
                echo "Erro: " . $e->getMessage();
            }
            $conn = null; // Fecha a conexão
        } else {
            // Se nenhum ID foi passado na URL, redireciona para a página inicial
            header("Location: index.html");
            exit();
        }
        ?>
    </div>
</body>
</html>
